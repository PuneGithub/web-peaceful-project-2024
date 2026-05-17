<?php
require_once '../system/administratorSystem.php';
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

$categoryId = (int) ($_GET['categoryId'] ?? $_POST['categoryId'] ?? 0);
if ($categoryId <= 0) {
    header('Location: manageCategory.php');
    exit;
}

$category = getCategoryById($conn, $categoryId);
if (!$category) {
    header('Location: manageCategory.php');
    exit;
}

$postAlertHtml = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnCategory'])) {
    $categoryName = trim($_POST['categoryName'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($categoryName === '') {
        $postAlertHtml = "<div class='alert-danger'>กรุณากรอกชื่อหมวดหมู่</div>";
    } elseif (updateCategory($conn, $categoryId, $categoryName, $description)) {
        $postAlertHtml = "<div class='alert-green'>อัปเดตหมวดหมู่สำเร็จแล้ว</div>";
        $category = getCategoryById($conn, $categoryId);
    } else {
        $postAlertHtml = "<div class='alert-danger'>เกิดข้อผิดพลาดในการอัปเดตหมวดหมู่</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/output.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>แก้ไขหมวดหมู่</title>
</head>

<body>
    <div class="flex">
        <?php include_once('../components/header-admin.php'); ?>

        <div class="flex-1 p-6">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 sm:col-span-4">
                    <div class="card-white">
                        <h2 class="font-bold text-lg">แก้ไขหมวดหมู่ #<?= (int) $category['categoryId'] ?></h2>
                        <a href="manageCategory.php" class="text-blue-500 hover:underline text-sm mt-2 inline-block">← กลับไปจัดการหมวดหมู่</a>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-8">
                    <div class="card-white">
                        <h2 class="font-bold text-center text-xl mb-4">แก้ไขหมวดหมู่</h2>
                        <?php if ($postAlertHtml) {
                            echo $postAlertHtml;
                        } ?>
                        <form action="" method="post" class="space-y-4">
                            <input type="hidden" name="categoryId" value="<?= (int) $category['categoryId'] ?>">
                            <div>
                                <label for="categoryName" class="block text-sm font-medium">ชื่อหมวดหมู่</label>
                                <input type="text" id="categoryName" name="categoryName" class="input-form" value="<?= htmlspecialchars($category['categoryName']) ?>" required>
                            </div>
                            <div>
                                <label for="description" class="block text-sm font-medium">คำอธิบาย</label>
                                <input type="text" id="description" name="description" class="input-form" value="<?= htmlspecialchars($category['description'] ?? '') ?>">
                            </div>
                            <div class="flex gap-3">
                                <input type="submit" class="btn-blue-500" name="btnCategory" value="บันทึก">
                                <a href="manageCategory.php" class="btn btn-gray-500 inline-block text-center">ยกเลิก</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
