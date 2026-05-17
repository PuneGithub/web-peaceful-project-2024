<?php
require_once '../system/administratorSystem.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categoryId'])) {
    $categoryId = (int) $_POST['categoryId'];

    if ($categoryId <= 0) {
        header('Location: manageCategory.php?error=invalid');
        exit;
    }

    if (countBlogsByCategory($conn, $categoryId) > 0) {
        header('Location: manageCategory.php?error=in_use');
        exit;
    }

    if (deleteCategory($conn, $categoryId)) {
        header('Location: manageCategory.php?deleted=1');
    } else {
        header('Location: manageCategory.php?error=delete');
    }
    exit;
}

$msgCategory = null;
if (isset($_GET['deleted'])) {
    $msgCategory = "<div class='alert-green'>ลบหมวดหมู่สำเร็จแล้ว</div>";
} elseif (isset($_GET['error'])) {
    $errorMessages = [
        'in_use' => 'ไม่สามารถลบได้ เนื่องจากมีบทความใช้หมวดหมู่นี้อยู่',
        'delete' => 'เกิดข้อผิดพลาดในการลบหมวดหมู่',
        'invalid' => 'รหัสหมวดหมู่ไม่ถูกต้อง',
    ];
    $errorKey = $_GET['error'];
    $message = $errorMessages[$errorKey] ?? 'เกิดข้อผิดพลาด';
    $msgCategory = "<div class='alert-danger'>{$message}</div>";
}

$getCategory = getCategory($conn);
$countCategory = countCategory($conn);
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/output.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>จัดการหมวดหมู่</title>
</head>

<body>
    <div class="flex">
        <?php include_once('../components/header-admin.php'); ?>

        <div class="flex-1 p-6">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 sm:col-span-4">
                    <div class="card-white">
                        <h2 class="font-bold text-lg">จำนวนหมวดหมู่: <?= (int) $countCategory ?> หมวดหมู่</h2>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-8">
                    <div class="card-white">
                        <?php if ($msgCategory) {
                            echo $msgCategory;
                        } ?>
                        <div class="flex items-center">
                            <a href="addCategory.php" class="btn btn-blue-500">เพิ่มหมวดหมู่</a>
                            <h2 class="font-bold flex-1 text-center text-xl">จัดการหมวดหมู่</h2>
                        </div>
                        <div class="overflow-x-auto mt-3">
                            <table class="table-auto w-full border border-slate-400 border-collapse text-center">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="p-2 border">ID</th>
                                        <th class="p-2 border">ชื่อหมวดหมู่</th>
                                        <th class="p-2 border">คำอธิบาย</th>
                                        <th class="p-2 border">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($getCategory)): ?>
                                        <tr>
                                            <td colspan="4" class="p-6 border text-gray-500">
                                                ยังไม่มีหมวดหมู่ —
                                                <a href="addCategory.php" class="text-blue-500 hover:underline">เพิ่มหมวดหมู่แรก</a>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($getCategory as $category): ?>
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="p-2 border"><?= (int) $category['categoryId'] ?></td>
                                                <td class="p-2 border">
                                                    <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded-md text-[10px] font-bold uppercase">
                                                        <?= htmlspecialchars($category['categoryName']) ?>
                                                    </span>
                                                </td>
                                                <td class="p-2 border text-left px-4"><?= htmlspecialchars($category['description'] ?? '') ?></td>
                                                <td class="p-2 border">
                                                    <div class="flex gap-3 justify-center">
                                                        <a href="editCategory.php?categoryId=<?= (int) $category['categoryId'] ?>" class="text-orange-500 hover:text-orange-700 transition" title="แก้ไข">
                                                            <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                                        </a>
                                                        <form action="" method="post" class="inline" onsubmit="return confirm('ยืนยันการลบหมวดหมู่นี้ใช่ไหม?');">
                                                            <input type="hidden" name="categoryId" value="<?= (int) $category['categoryId'] ?>">
                                                            <button type="submit" class="text-red-500 hover:text-red-700 transition" title="ลบ">
                                                                <i class="fa-solid fa-trash fa-lg"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
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
