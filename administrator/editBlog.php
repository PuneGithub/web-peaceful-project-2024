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
    <title>Edit Blog</title>
</head>

<body>
    <div class="flex">
        <?php include_once('../components/header-admin.php'); ?>

        <!-- Content -->
        <div class="flex-1 p-6">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 sm:col-span-4">
                    <div class="card-white">
                        <?php $totalUsers = countUsers($conn); ?>
                        <h2 class="font-bold text-lg">จำนวนสมาชิก: <?php echo $totalUsers; ?> บัญชี</h2>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-8">
                    <div class="card-white">
                        <?php
                        $blogId = $_GET['blogId'];
                        $blog = fetchEditBlog($conn, $blogId);
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['blogId'])) {
                            $blogTitle = $_POST['blogTitle'];
                            $blogContent = $_POST['blogContent'];
                            $metaDescription = $_POST['metaDescription'];
                            $blogCategory = $_POST['blogCategory'];
                            $oldImage = $blog['blogImage'];
                            $newImage = $_FILES['blogImage'];

                            if (updateBlog($conn, $blogId, $blogTitle, $blogContent, $metaDescription, $blogCategory, $newImage, $oldImage)) {
                                echo "<div class='alert-green text-center'>Update Successful!</div>";
                            } else {
                                echo "<div class='alert-danger text-center'>เกิดข้อผิดพลาดในการอัปเดตข้อมูล!</div>";
                            }
                        }

                        $imagePathsMap = [
                            'papermc' => '/img/blogs_image/blogs_server/papermc/',
                            'plugin' => '/img/blogs_image/blogs_plugin/plugin/',
                        ];

                        $imagePaths = $imagePathsMap[$blog['blogCategory']] ?? '';

                        ?>
                        <h2 class="text-center text-xl font-bold mb-4">Edit Blog</h2>
                        <form action="" enctype="multipart/form-data" method="post" class="space-y-4">
                            <input type="hidden" name="blogId" value="<?php echo $blog['blogId']; ?>">
                            <div>
                                <label for="blogTitle" class="block text-sm font-medium">blogTitle</label>
                                <input type="text" name="blogTitle" class="input-form" value="<?php echo $blog['blogTitle']; ?>" placeholder="Enter blogTitle" required>
                            </div>
                            <div>
                                <label for="blog" class="block text-sm font-medium">blogContent</label>
                                <textarea name="blogContent" class="input-form" rows="5" cols="30" id=""><?php echo $blog['blogContent']; ?></textarea>
                            </div>
                            <div>
                                <label for="metaDescription" class="block text-sm font-medium">metaDescription</label>
                                <input type="text" name="metaDescription" class="input-form" value="<?php echo $blog['metaDescription']; ?>" placeholder="Enter metaDescription" required>
                            </div>
                            <div>
                                <label for="blogCategory" class="block text-sm font-medium">blogCategory</label>
                                <select name="blogCategory" class="input-form">
                                    <option value="papermc" <?php echo ($blog['blogCategory'] === 'papermc') ? 'selected' : ''; ?>>PaperMC</option>
                                    <option value="plugin" <?php echo ($blog['blogCategory'] === 'plugin') ? 'selected' : ''; ?>>Plugin</option>
                                </select>
                                <div>
                                    <label for="blogImage" class="block text-sm font-medium">blogImage</label>
                                    <?php if (!empty($blog['blogImage'])): ?>
                                        <p class="text-xs text-gray-500 mb-2">รูปภาพปัจจุบัน: <?php echo htmlspecialchars($blog['blogImage']); ?></p>
                                        <img src="..<?php echo htmlspecialchars($imagePaths . $blog['blogImage']); ?>" alt="" class="w-32 h-32 object-cover mb-2">
                                    <?php endif; ?>
                                    <input type="file" name="blogImage" class="input-form" value="<?php echo $blog['blogImage']; ?>">
                                </div>
                                <div>
                                    <input type="submit" class="btn-blue-500" value="SAVE">
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>