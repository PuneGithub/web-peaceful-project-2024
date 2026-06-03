<?php
session_start();
require_once("../system/conn.php");
require_once("../system/config.php");
require_once("../system/resourceSystem.php");

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

$categories = getResourceCategories();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnAddResource'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        die('ตรวจพบการส่งข้อมูลที่ไม่ปลอดภัย (CSRF Token Mismatch)');
    }

    $userId = (int) ($_SESSION['userId'] ?? 0);
    if ($userId <= 0) {
        $error = 'ไม่พบข้อมูลผู้ใช้แอดมิน กรุณาเข้าสู่ระบบใหม่';
    } else {
        $name = trim($_POST['name'] ?? '');
        $category = $_POST['category'] ?? '';
        $version = trim($_POST['version'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $fileUrl = trim($_POST['fileUrl'] ?? '');
        $status = $_POST['status'] ?? 'approved';

        if ($name === '' || $version === '' || $description === '' || $fileUrl === '') {
            $error = 'กรุณากรอกข้อมูลให้ครบถ้วน';
        } elseif (!isValidResourceCategory($category)) {
            $error = 'หมวดหมู่ไม่ถูกต้อง';
        } elseif (!isValidResourceStatus($status)) {
            $error = 'สถานะไม่ถูกต้อง';
        } elseif (!isValidFileUrl($fileUrl)) {
            $error = 'ลิงก์ดาวน์โหลดต้องขึ้นต้นด้วย http:// หรือ https://';
        } else {
            $imageName = 'default_resource.webp';
            if (!empty($_FILES['resourceImage']['name'])) {
                $uploadResult = uploadResourceImage($_FILES['resourceImage']);
                if ($uploadResult === false) {
                    $error = 'รูปภาพไม่ถูกต้อง (รองรับ jpg, png, webp ไม่เกิน 3MB)';
                } elseif ($uploadResult !== null) {
                    $imageName = $uploadResult;
                }
            }

            if ($error === '') {
                if (createResource($conn, $userId, $name, $category, $version, $description, $fileUrl, $imageName, $status)) {
                    $_SESSION['manage_resources_flash'] = ['type' => 'success', 'msg' => 'เพิ่ม Resource สำเร็จแล้ว'];
                    header('Location: manageResources.php');
                    exit;
                }
                $error = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once __DIR__ . '/../components/favicon.php'; ?>
    <title>เพิ่ม Resource | Admin Panel</title>
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
                        <h2 class="text-2xl font-bold"><i class="fa-solid fa-plus-circle text-green-500 mr-2"></i>เพิ่ม Resource ใหม่</h2>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert-danger mb-4"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form action="" method="POST" enctype="multipart/form-data" class="space-y-5">
                        <?= csrfField() ?>

                        <div>
                            <label class="block text-sm font-bold mb-1">ชื่อ Plugin / Resource</label>
                            <input type="text" name="name" class="input-form w-full" required placeholder="เช่น EssentialsX">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold mb-1">หมวดหมู่</label>
                                <select name="category" class="input-form w-full bg-white" required>
                                    <option value="">-- เลือกหมวดหมู่ --</option>
                                    <?php foreach ($categories as $key => $label): ?>
                                        <option value="<?= $key ?>"><?= htmlspecialchars($label) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-1">เวอร์ชัน</label>
                                <input type="text" name="version" class="input-form w-full" required placeholder="เช่น 1.20.1">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-1">ลิงก์ดาวน์โหลด (URL)</label>
                            <input type="url" name="fileUrl" class="input-form w-full" required placeholder="https://...">
                            <p class="text-xs text-gray-500 mt-1">ลิงก์ไปยังไฟล์ .jar หรือหน้า Spigot / GitHub / Drive เป็นต้น</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-1">คำอธิบาย</label>
                            <textarea name="description" rows="5" class="input-form w-full" required placeholder="รายละเอียด plugin, วิธีใช้งานเบื้องต้น..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-1">รูปปก (ถ้ามี) — ไม่เกิน 3MB</label>
                            <input type="file" name="resourceImage" accept="image/*" class="input-form w-full">
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-1">สถานะ</label>
                            <select name="status" class="input-form w-full bg-white">
                                <option value="approved">เผยแพร่ทันที (Approved)</option>
                                <option value="pending">รอตรวจสอบ (Pending)</option>
                            </select>
                        </div>

                        <button type="submit" name="btnAddResource" class="btn-blue-500 w-full py-3">
                            <i class="fa-solid fa-save mr-2"></i> บันทึก Resource
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
