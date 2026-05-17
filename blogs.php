<?php
//connect database
require_once "system/conn.php";
require_once "system/config.php";
require_once "system/blogSystem.php";
session_start();

$fetchLatestBlog = fetchLatestBlog($conn);

$fetchAllBlogs = fetchAllBlogs($conn);


$latestRawPath = $fetchLatestBlog['folderPath'] ?? 'img/blogs_image/default/';
$latestCleanPath = trim($latestRawPath, '/');
$latestFinalImage = $latestCleanPath . '/' . $fetchLatestBlog['blogImage'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Zencrafterly</title>
</head>
<script src="js/script.js"></script>

<body>
    <!-- header navbar -->
    <?php
    include_once("components/header-navbar.php");
    ?>

    <div class="container mx-auto">
        <div class="card-white bg-opacity-75">
            <h2 class="text-2xl font-bold text-center">BLOG</h2>
            <div class="card-white max-w-4xl mx-auto flex items-center space-x-6">
                <!-- Image -->
                <img src="<?php echo base_url($latestFinalImage); ?>" alt="<?= htmlspecialchars($fetchLatestBlog['blogTitle']); ?>" class="w-1/2 rounded-lg object-cover" onerror="this.onerror=null; this.src='img/blogs_image/default.webp'">

                <div class="flex flex-col justify-between w-1/2">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900"><?php echo $fetchLatestBlog['blogTitle']; ?></h2>
                    </div>

                    <a href="blog/<?php echo $fetchLatestBlog['slug']; ?>" class="btn-blue-500 mt-4 self-start">Read More</a>
                </div>
            </div>

            <div class="grid grid-cols-3 grid-rows-2 gap-4 mt-3">
                <?php
                if (!empty($fetchAllBlogs)) {
                    foreach ($fetchAllBlogs as $blog) {
                        // 🚩 3. ดึง Path จาก Database และทำความสะอาด
                        $rawPath = $blog['folderPath'] ?? 'img/blogs_image/default/';
                        $cleanPath = trim($rawPath, '/');
                        $finalImage = $cleanPath . '/' . $blog['blogImage'];
                ?>
                        <div class="card-white">
                            <div class="relative overflow-hidden aspect-video">
                                <img src="<?php echo base_url($finalImage); ?>"
                                    alt="<?= htmlspecialchars($blog['blogTitle']); ?>"
                                    class="rounded-lg object-cover w-full h-full"
                                    onerror="this.onerror=null; this.src='<?= base_url('img/blogs_image/default.webp'); ?>';">
                            </div>

                            <div class="flex flex-col">
                                <div class="space-y-3">
                                    <h2 class="text-2xl font-bold"><?php echo $blog['blogTitle']; ?></h2>
                                </div>
                                <a href="blog/<?php echo $blog['slug']; ?>" class="btn-blue-500 mt-4">Read More</a>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>

        </div>

    </div>

    <!-- footer -->
    <?php
    include_once("components/footer.php");
    ?>

</body>

</html>