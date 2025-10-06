<?php
//connect database
require_once "system/conn.php";
require_once "system/config.php";
require_once "system/blogSystem.php";
session_start();

$fetchLatestBlog = fetchLatestBlog($conn);

$fetchAllBlogs = fetchAllBlogs($conn);


$image_paths = [
    'papermc' => '/img/blogs_image/blogs_server/papermc/',
    'plugin' => '/img/blogs_image/blogs_plugin/plugin/',
];

$latestImagePath = $image_paths[$fetchLatestBlog['blogCategory']];

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
                <img src="<?php echo base_url($latestImagePath . $fetchLatestBlog['blogImage']); ?>" alt="Example image" class="w-1/2 rounded-lg">

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
                        // กำหนด path รูปภาพสำหรับแต่ละ blog ใน loop
                        $imagePath = $image_paths[$blog['blogCategory']];
                ?>
                        <div class="card-white">
                            <img src="<?php echo base_url($imagePath . $blog['blogImage']); ?>" alt="Example image" class="rounded-lg">

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