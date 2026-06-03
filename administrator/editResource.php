<?php
session_start();
require_once("../system/conn.php");
require_once("../system/config.php");
require_once("../system/resourceSystem.php");

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

$resourceId = (int) ($_GET['id'] ?? $_POST['resourceId'] ?? 0);
if ($resourceId <= 0) {
    header('Location: manageResources.php');
    exit;
}

$resource = fetchResourceById($conn, $resourceId);
if (!$resource) {
    header('Location: manageResources.php');
    exit;
}

$categories = getResourceCategories();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnUpdateResource'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        die('ตรวจพบการส่งข้อมูลที่ไม่ปลอดภัย (CSRF Token Mismatch)');
    }

    $name = trim($_POST['name'] ?? '');
    $category = $_POST['category'] ?? '';
    $version = trim($_POST['version'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $fileUrl = trim($_POST['fileUrl'] ?? '');
    $status = $_POST['status'] ?? 'approved';
    $imageName = $resource['image'];

    if ($name === '' || $version === '' || $description === '' || $fileUrl === '') {
        $error = 'กรุณากรอกข้อมูลให้ครบถ้วน';
    } elseif (!isValidResourceCategory($category)) {
        $error = 'หมวดหมู่ไม่ถูกต้อง';
    } elseif (!isValidResourceStatus($status)) {
        $error = 'สถานะไม่ถูกต้อง';
    } elseif (!isValidFileUrl($fileUrl)) {
        $error = 'ลิงก์ดาวน์โหลดต้องขึ้นต้นด้วย http:// หรือ https://';
    } else {
        if (!empty($_FILES['resourceImage']['name'])) {
            $uploadResult = uploadResourceImage($_FILES['resourceImage']);
            if ($uploadResult === false) {
                $error = 'รูปภาพไม่ถูกต้อง (รองรับ jpg, png, webp ไม่เกิน 3MB)';
            } elseif ($uploadResult !== null) {
                if ($imageName !== 'default_resource.webp') {
                    $oldPath = dirname(__DIR__) . '/img/resources/' . basename($imageName);
                    if (is_file($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $imageName = $uploadResult;
            }
        }

        if ($error === '') {
            if (updateResource($conn, $resourceId, $name, $category, $version, $description, $fileUrl, $imageName, $status)) {
                $_SESSION['manage_resources_flash'] = ['type' => 'success', 'msg' => 'อัปเดต Resource สำเร็จแล้ว'];
                header('Location: manageResources.php');
                exit;
            }
            $error = 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล';
        }
    }

    $resource = fetchResourceById($conn, $resourceId);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once __DIR__ . '/../components/favicon.php'; ?>
    <title>แก้ไข Resource | Admin Panel</title>
    <link rel="stylesheet" href="<?= base_url('css/output.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
</head>
<body class="bg-gray-50">
    <div class="flex">
        <?php include_once('../components/header-admin.php'); ?>

        <div class="flex-1 p-6">
            <div class="max-w-3xl mx-auto">
                <div class="card-white p-8 shadow-sm">
                    <div class="flex items-center mb-6 border-b pb-4">
                        <a href="manageResources.php" class="text-gray-400 hover:text-blue-500 mr-4"><i class="fa-solid fa-arrow-left fa-lg"></i></a>
                        <h2 class="text-2xl font-bold">แก้ไข Resource #<?= (int) $resource['resourceId'] ?></h2>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert-danger mb-4"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <div class="mb-6 text-center">
                        <img src="<?= base_url('img/resources/' . htmlspecialchars($resource['image'] ?? 'default_resource.webp')) ?>"
                             class="w-32 h-32 object-cover rounded-lg mx-auto border"
                             onerror="this.src='<?= base_url('img/blogs_image/default.webp') ?>'">
                    </div>

                    <form action="" method="POST" enctype="multipart/form-data" class="space-y-5">
                        <?= csrfField() ?>
                        <input type="hidden" name="resourceId" value="<?= (int) $resource['resourceId'] ?>">

                        <div>
                            <label class="block text-sm font-bold mb-1">ชื่อ Plugin / Resource</label>
                            <input type="text" name="name" class="input-form w-full" required
                                   value="<?= htmlspecialchars($resource['name']) ?>">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold mb-1">หมวดหมู่</label>
                                <select name="category" class="input-form w-full bg-white" required>
                                    <?php foreach ($categories as $key => $label): ?>
                                        <option value="<?= $key ?>" <?= $resource['category'] === $key ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($label) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-1">เวอร์ชัน</label>
                                <input type="text" name="version" class="input-form w-full" required
                                       value="<?= htmlspecialchars($resource['version']) ?>">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-1">ลิงก์ดาวน์โหลด (URL)</label>
                            <input type="url" name="fileUrl" class="input-form w-full" required
                                   value="<?= htmlspecialchars($resource['fileUrl']) ?>">
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-1">คำอธิบาย</label>
                            <textarea name="description" rows="5" class="input-form w-full" required><?= htmlspecialchars($resource['description']) ?></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-1">เปลี่ยนรูปปก (ถ้ามี) — ไม่เกิน 3MB</label>
                            <input type="file" name="resourceImage" accept="image/*" class="input-form w-full">
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-1">สถานะ</label>
                            <select name="status" class="input-form w-full bg-white">
                                <option value="approved" <?= $resource['status'] === 'approved' ? 'selected' : '' ?>>เผยแพร่ (Approved)</option>
                                <option value="pending" <?= $resource['status'] === 'pending' ? 'selected' : '' ?>>รอตรวจสอบ (Pending)</option>
                                <option value="rejected" <?= $resource['status'] === 'rejected' ? 'selected' : '' ?>>ปฏิเสธ (Rejected)</option>
                            </select>
                        </div>

                        <button type="submit" name="btnUpdateResource" class="btn-blue-500 w-full py-3">
                            <i class="fa-solid fa-save mr-2"></i> บันทึกการแก้ไข
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
