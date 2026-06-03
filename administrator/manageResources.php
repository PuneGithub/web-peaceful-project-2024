<?php
session_start();
require_once("../system/conn.php");
require_once("../system/config.php");
require_once("../system/resourceSystem.php");

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

$flash = $_SESSION['manage_resources_flash'] ?? null;
unset($_SESSION['manage_resources_flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['resourceId'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        die('ตรวจพบการส่งข้อมูลที่ไม่ปลอดภัย (CSRF Token Mismatch)');
    }

    $resourceId = (int) $_POST['resourceId'];
    $action = $_POST['action'];

    if ($resourceId > 0) {
        if ($action === 'approve') {
            if (updateResourceStatus($conn, $resourceId, 'approved')) {
                $_SESSION['manage_resources_flash'] = ['type' => 'success', 'msg' => 'อนุมัติ Resource เรียบร้อยแล้ว'];
            } else {
                $_SESSION['manage_resources_flash'] = ['type' => 'error', 'msg' => 'ไม่สามารถอนุมัติได้'];
            }
        } elseif ($action === 'reject') {
            if (updateResourceStatus($conn, $resourceId, 'rejected')) {
                $_SESSION['manage_resources_flash'] = ['type' => 'success', 'msg' => 'ปฏิเสธ Resource เรียบร้อยแล้ว'];
            } else {
                $_SESSION['manage_resources_flash'] = ['type' => 'error', 'msg' => 'ไม่สามารถปฏิเสธได้'];
            }
        } elseif ($action === 'delete') {
            if (deleteResource($conn, $resourceId)) {
                $_SESSION['manage_resources_flash'] = ['type' => 'success', 'msg' => 'ลบ Resource เรียบร้อยแล้ว'];
            } else {
                $_SESSION['manage_resources_flash'] = ['type' => 'error', 'msg' => 'ไม่สามารถลบได้'];
            }
        }
    }

    header('Location: manageResources.php');
    exit;
}

$resources = fetchAllResourcesAdmin($conn);
$categories = getResourceCategories();
$totalResources = count($resources);
$pendingCount = count(array_filter($resources, fn($r) => $r['status'] === 'pending'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once __DIR__ . '/../components/favicon.php'; ?>
    <title>จัดการ Resources | Admin Panel</title>
    <link rel="stylesheet" href="<?= base_url('css/output.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="flex">
        <?php include_once('../components/header-admin.php'); ?>

        <div class="flex-1 p-6">
            <?php if ($flash): ?>
                <div class="mb-4 p-4 rounded-lg font-bold <?= $flash['type'] === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                    <?= htmlspecialchars($flash['msg']) ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 sm:col-span-4 space-y-4">
                    <div class="card-white">
                        <h2 class="font-bold text-lg">Resources ทั้งหมด: <?= $totalResources ?></h2>
                        <?php if ($pendingCount > 0): ?>
                            <p class="text-sm text-yellow-600 mt-2"><i class="fa-solid fa-clock mr-1"></i> รออนุมัติ: <?= $pendingCount ?></p>
                        <?php endif; ?>
                    </div>
                    <a href="addResource.php" class="btn-blue-500 w-full text-center block py-3">
                        <i class="fa-solid fa-plus mr-2"></i> เพิ่ม Resource ใหม่
                    </a>
                </div>

                <div class="col-span-12 sm:col-span-8">
                    <div class="card-white">
                        <h2 class="font-bold text-xl text-center mb-4">Manage Resources</h2>
                        <div class="overflow-x-auto">
                            <table class="table-auto w-full border border-slate-400 border-collapse text-center text-sm">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="border border-slate-300 p-2">รูป</th>
                                        <th class="border border-slate-300 p-2">ชื่อ / หมวด</th>
                                        <th class="border border-slate-300 p-2">เวอร์ชัน</th>
                                        <th class="border border-slate-300 p-2">ดาวน์โหลด</th>
                                        <th class="border border-slate-300 p-2">สถานะ</th>
                                        <th class="border border-slate-300 p-2">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($resources) > 0): ?>
                                        <?php foreach ($resources as $res): ?>
                                            <tr class="hover:bg-gray-50">
                                                <td class="border border-slate-300 p-2">
                                                    <img src="<?= base_url('img/resources/' . htmlspecialchars($res['image'] ?? 'default_resource.webp')) ?>"
                                                         alt="" class="w-12 h-12 object-cover rounded mx-auto"
                                                         onerror="this.src='<?= base_url('img/blogs_image/default.webp') ?>'">
                                                </td>
                                                <td class="border border-slate-300 p-2 text-left">
                                                    <span class="font-bold"><?= htmlspecialchars($res['name']) ?></span>
                                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($categories[$res['category']] ?? $res['category']) ?></div>
                                                </td>
                                                <td class="border border-slate-300 p-2">v<?= htmlspecialchars($res['version']) ?></td>
                                                <td class="border border-slate-300 p-2"><?= number_format($res['downloads'] ?? 0) ?></td>
                                                <td class="border border-slate-300 p-2">
                                                    <?php
                                                    $statusClass = [
                                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                                        'approved' => 'bg-green-100 text-green-700',
                                                        'rejected' => 'bg-red-100 text-red-700',
                                                    ];
                                                    $cls = $statusClass[$res['status']] ?? 'bg-gray-100 text-gray-700';
                                                    ?>
                                                    <span class="px-2 py-1 rounded-full text-xs font-bold <?= $cls ?>">
                                                        <?= strtoupper($res['status']) ?>
                                                    </span>
                                                </td>
                                                <td class="border border-slate-300 p-2">
                                                    <div class="flex justify-center gap-2 flex-wrap">
                                                        <?php if ($res['status'] === 'pending'): ?>
                                                            <form method="POST" class="inline" onsubmit="return confirm('อนุมัติ Resource นี้?')">
                                                                <?= csrfField() ?>
                                                                <input type="hidden" name="resourceId" value="<?= (int) $res['resourceId'] ?>">
                                                                <button type="submit" name="action" value="approve" class="text-green-500 hover:text-green-700 text-lg" title="อนุมัติ">
                                                                    <i class="fa-solid fa-circle-check"></i>
                                                                </button>
                                                            </form>
                                                            <form method="POST" class="inline" onsubmit="return confirm('ปฏิเสธ Resource นี้?')">
                                                                <?= csrfField() ?>
                                                                <input type="hidden" name="resourceId" value="<?= (int) $res['resourceId'] ?>">
                                                                <button type="submit" name="action" value="reject" class="text-orange-500 hover:text-orange-700 text-lg" title="ปฏิเสธ">
                                                                    <i class="fa-solid fa-circle-xmark"></i>
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                        <a href="editResource.php?id=<?= (int) $res['resourceId'] ?>" class="text-blue-500 hover:text-blue-700" title="แก้ไข">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </a>
                                                        <form method="POST" class="inline" onsubmit="return confirm('ลบ Resource นี้ถาวร?')">
                                                            <?= csrfField() ?>
                                                            <input type="hidden" name="resourceId" value="<?= (int) $res['resourceId'] ?>">
                                                            <button type="submit" name="action" value="delete" class="text-red-500 hover:text-red-700" title="ลบ">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="border border-slate-300 p-8 text-gray-500">
                                                ยังไม่มี Resource — <a href="addResource.php" class="text-blue-600 underline">เพิ่มรายการแรก</a>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
