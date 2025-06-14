<?php
//connect database
require_once "system/conn.php";
require_once "system/config.php";
require_once "system/blogSystem.php";
session_start();

if (isset($_GET['slug'])) {
    $slug = $_GET['slug'];
    $blog = fetchBlog($conn, $slug);

    if (!$blog) {
        header("HTTP/1.0 404 Not Found");
        echo "<h1>ไม่พบบทความ</h1>";
        exit;
    }
} else {
    header("Location: /blogs");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?= base_url('/css/output.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/css/style.css') ?>">
    <title>Peaceful Network</title>
</head>
<script src="js/script.js"></script>

<body>
    <!-- header navbar -->
    <?php
    include_once("components/header-navbar.php");
    ?>

    <div class="container mx-auto">
        <div class="card">
            <div class="card-white">
                <img src="<?= base_url('/img/blogs_image/' . $blog['blogImage']); ?>" alt="" class="block mx-auto object-contain md:h-96 shadow-lg rounded-lg">
                <h1 class="text-center font-semibold text-2xl">
                    <?php echo $blog['blogTitle']; ?>
                </h1>
                <?php echo $blog['blogContent']; ?>
            </div>
        </div>
    </div>

    <!-- footer -->
    <?php
    include_once("components/footer.php");
    ?>

</body>

</html>