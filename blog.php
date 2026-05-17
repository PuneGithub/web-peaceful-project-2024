<?php
//connect database
require_once "system/conn.php";
require_once "system/config.php";
require_once "system/blogSystem.php";
session_start();

if (isset($_GET['slug'])) {
    $slug = $_GET['slug'];
    $blog = fetchBlog($conn, $slug);

    if ($blog) {
        $blogId = $blog['blogId'];

        if (!isset($_SESSION['viewed_posts'])) {
            $_SESSION['viewed_posts'] = [];
        }

        if (!in_array($blogId, $_SESSION['viewed_posts'])) {
            updateViewCount($conn, $blogId);
            $_SESSION['viewed_posts'][] = $blogId;
        }


        // 🚩 ทำความสะอาด Path ป้องกันปัญหา / เบิ้ลกัน
        $rawPath = $blog['folderPath'] ?? 'img/blogs_image/default/';
        $cleanPath = trim($rawPath, '/'); // ลบ / ที่อยู่ข้างหน้าสุดและหลังสุดออกก่อน

        // เอา Path ที่สะอาดแล้ว มาต่อกับ / และชื่อรูปภาพ
        $finalImagePath = $cleanPath . '/' . $blog['blogImage'];
    } else {
        header("HTTP/1.0 404 Not Found");
        header("Location: /404.php");
        exit;
    }
} else {
    header("Location: /blogs.php");
    exit;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php
    $titleTag = !empty($blog['seo_title']) ? $blog['seo_title'] : $blog['blogTitle'];
    // ตัดเนื้อหาบางส่วนมาทำ Description ถ้าไม่ได้กรอกไว้หลังบ้าน
    $descTag  = !empty($blog['seo_description']) ? $blog['seo_description'] : mb_substr(strip_tags($blog['blogContent']), 0, 160);
    $keyTag   = !empty($blog['seo_keywords']) ? $blog['seo_keywords'] : "Minecraft, Zencrafterly";
    ?>
    <title><?= htmlspecialchars($titleTag) ?> | Zencrafterly</title>

    <meta name="description" content="<?= htmlspecialchars($descTag) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($keyTag) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?= base_url('/css/output.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/css/style.css') ?>">
</head>
<script src="js/script.js"></script>

<body>
    <!-- header navbar -->
    <?php
    include_once("components/header-navbar.php");
    ?>

    <div class="container mx-auto px-4">
        <div class="card">
            <div class="card-white">
                <img src="<?= base_url($finalImagePath); ?>" alt="<?= htmlspecialchars($blog['blogTitle']); ?>" class="block mx-auto object-contain md:h-96 shadow-lg rounded-lg" onerror="this.onerror=null; this.src='<?= base_url('img/blogs_image/default.webp'); ?>';">
                <h1 class="text-center font-semibold text-2xl">
                    <?php echo $blog['blogTitle']; ?>
                </h1>

                <!-- Content -->
                <div class="px-6">
                    <p class="flex text-lg text-gray-600">
                        <!-- แสดงชื่อหมวดหมู่ที่ดึงมาจากตาราง category -->
                        <span class="mr-2 bg-blue-100 text-blue-600 px-2 py-1 rounded text-sm font-bold">
                            <?= htmlspecialchars($blog['categoryName'] ?? 'General'); ?>
                        </span>

                        By <span class="font-semibold text-blue-600"> KoonPune</span> Created on: <?php echo htmlspecialchars($blog['createdAt']); ?> <span class="ml-auto"><?php echo htmlspecialchars($blog['views']); ?> views</span>
                    </p>
                    <div class="prose max-w-none mt-6 text-gray-800 leading-relaxed">
                        <?php echo $blog['blogContent']; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    <?php
    include_once("components/footer.php");
    ?>

</body>

</html>