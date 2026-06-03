<?php
require_once '../system/administratorSystem.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

$msgServer = $_SESSION['manage_servers_flash'] ?? null;
unset($_SESSION['manage_servers_flash']);

$statusFilter = $_GET['status'] ?? '';
if ($statusFilter !== '' && !in_array($statusFilter, ['pending', 'approved', 'rejected'], true)) {
    $statusFilter = '';
}

function manageServersRedirectUrl($statusFilter = '')
{
    $url = 'manageServers.php';
    if ($statusFilter !== '' && in_array($statusFilter, ['pending', 'approved', 'rejected'], true)) {
        $url .= '?status=' . urlencode($statusFilter);
    }
    return $url;
}

// อนุมัติ / ปฏิเสธ / ลบ — POST + CSRF (pattern เดียวกับ manageResources)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['serverId'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        die('ตรวจพบการส่งข้อมูลที่ไม่ปลอดภัย (CSRF Token Mismatch)');
    }

    $serverId = (int) $_POST['serverId'];
    $action = $_POST['action'];
    $redirectFilter = $_POST['status_filter'] ?? '';

    if ($serverId > 0) {
        if ($action === 'approve') {
            if (approveServer($conn, $serverId)) {
                $_SESSION['manage_servers_flash'] = "<div class='alert-green'>อนุมัติเซิร์ฟเวอร์เรียบร้อย! ตอนนี้แสดงบนหน้าเว็บแล้ว</div>";
            } else {
                $_SESSION['manage_servers_flash'] = "<div class='alert-danger'>ไม่สามารถอนุมัติเซิร์ฟเวอร์นี้ได้</div>";
            }
        } elseif ($action === 'reject') {
            if (rejectServer($conn, $serverId)) {
                $_SESSION['manage_servers_flash'] = "<div class='alert-danger'>ปฏิเสธเซิร์ฟเวอร์เรียบร้อยแล้ว</div>";
            } else {
                $_SESSION['manage_servers_flash'] = "<div class='alert-danger'>ไม่สามารถปฏิเสธเซิร์ฟเวอร์นี้ได้</div>";
            }
        } elseif ($action === 'delete') {
            if (deleteServer($conn, $serverId)) {
                $_SESSION['manage_servers_flash'] = "<div class='alert-green'>ลบข้อมูลเซิร์ฟเวอร์สำเร็จแล้ว</div>";
            } else {
                $_SESSION['manage_servers_flash'] = "<div class='alert-danger'>เกิดข้อผิดพลาดในการลบข้อมูล</div>";
            }
        }
    }

    header('Location: ' . manageServersRedirectUrl($redirectFilter));
    exit;
}

$allServers = fetchAllServers($conn);
if (!is_array($allServers)) {
    $allServers = [];
}

$totalServers = count($allServers);
$pendingCount = count(array_filter($allServers, fn($s) => $s['status'] === 'pending'));

$fetchAllServers = $statusFilter === ''
    ? $allServers
    : fetchAllServers($conn, $statusFilter);

$statusLabels = [
    '' => 'ทั้งหมด',
    'pending' => 'รออนุมัติ',
    'approved' => 'อนุมัติแล้ว',
    'rejected' => 'ปฏิเสธแล้ว',
];
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once __DIR__ . '/../components/favicon.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/output.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Manage Servers</title>
</head>

<body>
    <div class="flex">
        <?php include_once('../components/header-admin.php'); ?>

        <div class="flex-1 p-6">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 sm:col-span-4 space-y-4">
                    <div class="card-white">
                        <h2 class="font-bold text-lg">เซิร์ฟเวอร์ทั้งหมด: <?= $totalServers ?></h2>
                        <?php if ($pendingCount > 0): ?>
                            <p class="text-sm text-yellow-600 mt-2">
                                <i class="fa-solid fa-clock mr-1"></i> รออนุมัติ: <?= $pendingCount ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="card-white">
                        <p class="text-sm font-bold text-gray-600 mb-2">กรองตามสถานะ</p>
                        <div class="flex flex-col gap-1">
                            <?php foreach ($statusLabels as $key => $label): ?>
                                <?php
                                $filterUrl = $key === '' ? 'manageServers.php' : 'manageServers.php?status=' . urlencode($key);
                                $isActive = $statusFilter === $key;
                                ?>
                                <a href="<?= $filterUrl ?>"
                                   class="text-sm px-3 py-2 rounded-lg transition <?= $isActive ? 'bg-blue-600 text-white font-bold' : 'text-gray-600 hover:bg-gray-100' ?>">
                                    <?= htmlspecialchars($label) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-8">
                    <div class="card-white">
                        <?php if ($msgServer) {
                            echo $msgServer;
                        } ?>
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="font-bold text-xl">Manage Servers</h2>
                            <?php if ($statusFilter !== ''): ?>
                                <span class="text-xs text-gray-500">
                                    แสดง: <?= htmlspecialchars($statusLabels[$statusFilter] ?? $statusFilter) ?>
                                    (<?= count($fetchAllServers) ?> รายการ)
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="overflow-x-auto mt-3">
                            <table class="table-auto w-full border border-slate-400 border-collapse text-center text-sm">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="border border-slate-300 p-2">ID</th>
                                        <th class="border border-slate-300 p-2">icon</th>
                                        <th class="border border-slate-300 p-2">ชื่อ / IP</th>
                                        <th class="border border-slate-300 p-2">เจ้าของ</th>
                                        <th class="border border-slate-300 p-2">หมวด</th>
                                        <th class="border border-slate-300 p-2">สถานะ</th>
                                        <th class="border border-slate-300 p-2">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($fetchAllServers) > 0): ?>
                                        <?php foreach ($fetchAllServers as $server): ?>
                                            <?php
                                            $iconFile = $server['serverImage'] ?: 'default_server.webp';
                                            $iconUrl = base_url('img/server-icons/' . $iconFile);
                                            $defaultIcon = base_url('img/server-icons/default_server.webp');
                                            ?>
                                            <tr class="hover:bg-gray-50">
                                                <td class="border border-slate-300 p-2"><?= htmlspecialchars($server['serverId']) ?></td>
                                                <td class="border border-slate-300 p-2">
                                                    <img src="<?= htmlspecialchars($iconUrl, ENT_QUOTES, 'UTF-8') ?>"
                                                         alt=""
                                                         class="w-12 h-12 object-cover rounded-sm mx-auto"
                                                         onerror="this.onerror=null; this.src='<?= htmlspecialchars($defaultIcon, ENT_QUOTES, 'UTF-8') ?>';">
                                                </td>
                                                <td class="border border-slate-300 p-2 text-left">
                                                    <span class="font-bold"><?= htmlspecialchars($server['serverName']) ?></span>
                                                    <div class="text-xs text-gray-400 font-mono"><?= htmlspecialchars($server['serverIP']) ?></div>
                                                    <?php if ($server['status'] === 'approved' && !empty($server['serverSlug'])): ?>
                                                        <a href="<?= base_url('server/' . htmlspecialchars($server['serverSlug'], ENT_QUOTES, 'UTF-8')) ?>"
                                                           target="_blank" rel="noopener noreferrer"
                                                           class="text-xs text-blue-600 hover:underline inline-flex items-center gap-1 mt-1">
                                                            <i class="fa-solid fa-arrow-up-right-from-square"></i> ดูหน้าเว็บ
                                                        </a>
                                                    <?php elseif ($server['status'] === 'pending'): ?>
                                                        <span class="text-xs text-gray-400 block mt-1">ยังไม่เผยแพร่บนหน้าเว็บ</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="border border-slate-300 p-2">
                                                    <?= htmlspecialchars($server['username'] ?? 'ไม่ทราบ') ?>
                                                </td>
                                                <td class="border border-slate-300 p-2"><?= htmlspecialchars($server['serverCategory']) ?></td>
                                                <td class="border border-slate-300 p-2">
                                                    <?php
                                                    $statusClass = [
                                                        'pending' => 'bg-yellow-100 text-yellow-600',
                                                        'approved' => 'bg-green-100 text-green-600',
                                                        'rejected' => 'bg-red-100 text-red-600',
                                                    ];
                                                    $currentClass = $statusClass[$server['status']] ?? 'bg-gray-100';
                                                    ?>
                                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold <?= $currentClass ?>">
                                                        <?= strtoupper($server['status']) ?>
                                                    </span>
                                                </td>
                                                <td class="p-3 border">
                                                    <div class="flex justify-center gap-2 flex-wrap">
                                                        <?php if ($server['status'] === 'pending'): ?>
                                                            <form method="POST" class="inline" onsubmit="return confirm('ยืนยันการอนุมัติเซิร์ฟเวอร์นี้?')">
                                                                <?= csrfField() ?>
                                                                <input type="hidden" name="serverId" value="<?= (int) $server['serverId'] ?>">
                                                                <input type="hidden" name="status_filter" value="<?= htmlspecialchars($statusFilter) ?>">
                                                                <button type="submit" name="action" value="approve" class="text-green-500 hover:text-green-700 text-lg" title="อนุมัติ">
                                                                    <i class="fa-solid fa-circle-check"></i>
                                                                </button>
                                                            </form>
                                                            <form method="POST" class="inline" onsubmit="return confirm('ยืนยันการปฏิเสธเซิร์ฟเวอร์นี้?')">
                                                                <?= csrfField() ?>
                                                                <input type="hidden" name="serverId" value="<?= (int) $server['serverId'] ?>">
                                                                <input type="hidden" name="status_filter" value="<?= htmlspecialchars($statusFilter) ?>">
                                                                <button type="submit" name="action" value="reject" class="text-orange-500 hover:text-orange-700 text-lg" title="ปฏิเสธ">
                                                                    <i class="fa-solid fa-circle-xmark"></i>
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>

                                                        <form method="POST" class="inline" onsubmit="return confirm('ยืนยันการลบเซิร์ฟเวอร์นี้?')">
                                                            <?= csrfField() ?>
                                                            <input type="hidden" name="serverId" value="<?= (int) $server['serverId'] ?>">
                                                            <input type="hidden" name="status_filter" value="<?= htmlspecialchars($statusFilter) ?>">
                                                            <button type="submit" name="action" value="delete" class="text-red-500 hover:text-red-700 transition" title="ลบ">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="border border-slate-300 p-8 text-gray-500">
                                                ไม่มีเซิร์ฟเวอร์<?= $statusFilter !== '' ? ' ในสถานะนี้' : '' ?>
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
