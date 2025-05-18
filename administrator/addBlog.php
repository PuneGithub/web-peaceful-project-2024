<?php
require_once '../system/administratorSystem.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/output.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Manage Posts</title>
</head>

<body>
    <div class="flex">
        <?php include_once('../components/header-admin.php'); ?>

        <!-- Content -->
        <div class="flex-1 p-6">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 sm:col-span-4">
                    <div class="card-white">
                        <?php $totalPosts = countPosts($conn); ?>
                        <h2 class="font-bold text-lg">จำนวนโพสต์: <?php echo $totalPosts; ?> โพสต์</h2>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-8">
                    <div class="card-white">
                        <div class="flex items-center">
                            <h2 class="font-bold flex-1 text-center text-xl">Add Blog</h2>
                        </div>
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['btnBlog'])) {

                            $userId = $_SESSION['userId'];
                            $blogTitle = htmlspecialchars($_POST['blogTitle']);
                            $blogContent = $_POST['blogContent'];
                            $blogUrl = $_POST['blogUrl'];
                            $slug = createSlug($blogUrl);

                            $blogImage = NULL;
                            $blogResult = createBlog($conn, $userId, $blogTitle, $blogContent, $blogImage, $slug);

                            if ($blogResult) {
                                echo $blogResult;
                            }
                        }
                        ?>
                        <form action="" method="post" class="space-y-4" enctype="multipart/form-data">
                            <div>
                                <label for="blogTitle" class="block text-sm font-medium">blogTitle</label>
                                <input type="text" name="blogTitle" class="input-form" placeholder="Enter blogTitle" required>
                            </div>
                            <div>
                                <label for="blogUrl" class="block text-sm font-medium">blogUrl</label>
                                <input type="text" name="blogUrl" class="input-form" placeholder="Enter blogUrl" required>
                            </div>
                            <div>
                                <label for="Code HTML" class="block text-sm font-medium">Code HTML</label>
                                <textarea name="blogContent" id="blogContent" class="input-form" rows="5" cols="30"></textarea>
                            </div>
                            <div>
                                <label for="blogImage" class="block text-sm font-medium">blogImage</label>
                                <input type="file" name="blogImage">
                            </div>
                            <div>
                                <input type="submit" class="btn-blue-500" name="btnBlog" value="SAVE">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>