<?php
require_once '../system/administratorSystem.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
}

//ดึงหมวดหมู่มาแสดง

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['btnCategory'])) {

    $categoryName = htmlspecialchars($_POST['categoryName']);
    $description = $_POST['description'];

    $createCategory = createCategory($conn, $categoryName, $description);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once __DIR__ . '/../components/favicon.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/output.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Add Category</title>
</head>

<body>
    <div class="flex">
        <?php include_once('../components/header-admin.php'); ?>

        <!-- Content -->
        <div class="flex-1 p-6">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 sm:col-span-4">
                    <div class="card-white">
                        <h2 class="font-bold text-lg">จำนวนบทความ: <?php ?> บทความ</h2>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-8">
                    <div class="card-white">
                        <div class="flex items-center">
                            <h2 class="font-bold flex-1 text-center text-xl">Add Blog</h2>
                        </div>
                        <?php
                        if (isset($createCategory)) {
                            echo $createCategory;
                        }
                        ?>
                        <form action="" method="post" class="space-y-4" enctype="multipart/form-data">
                            <div>
                                <label for="categoryName" class="block text-sm font-medium">categoryName</label>
                                <input type="text" name="categoryName" class="input-form" placeholder="Enter categoryName" required>
                            </div>
                            <div>
                                <label for="description" class="block text-sm font-medium">description</label>
                                <input type="text" name="description" class="input-form" placeholder="Enter description" required>
                            </div>
                            <div>
                                <input type="submit" class="btn-blue-500" name="btnCategory" value="SAVE">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>