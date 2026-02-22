<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//connect database
session_start();
require_once("system/conn.php");
require_once("system/config.php");
require_once("system/blogSystem.php");
require_once("system/websiteSettingsSystem.php");

$sortBy = $_GET['sort'] ?? 'latest'; //กำหนดค่าเริ่มต้นเป็น 'latest' หากไม่มีค่าใน query string


$image_blogs_paths = [
    'papermc' => '/img/blogs_image/blogs_server/papermc/',
    'plugin' => '/img/blogs_image/blogs_plugin/plugin/',
];


//ดึง Settings
$settings = getWebsiteSettings($conn);

//ดึงบทความมาแสดง
$fetchAllBlogs = fetchAllBlogs($conn, $sortBy);
$totalBlogs = countAllBlogs($conn);

//ดึงหมวดหมู่บทความมาแสดง
$getBlogCategory = getCategory($conn);

//ดึงข้อความจาก SESSION ถ้ามี
$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/output.css">

    <link rel="icon" href="data:,">
    <title>Zencrafterly</title>
</head>

<body>
    <!-- header navbar -->
    <?php
    include_once("components/header-navbar.php");
    ?>
    <!-- Hero Section -->
    <section class="relative bg-cover bg-center h-96" style="background-image: url('img/bg.webp');">

        <div class="absolute inset-0 bg-black opacity-50"></div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col items-center justify-center h-full text-center text-white">
            <h1 class="text-4xl font-bold mb-4"><?php echo htmlspecialchars($settings['webTitle']); ?></h1>
            <p class="text-lg mb-8"><?php echo htmlspecialchars($settings['heroTitle']); ?></p>
            <div class="space-x-4">
                <?php
                if (isset($_SESSION['userId'])) {
                ?>
                    <h4 class="font-bold text-2xl">Welcome! <?php echo $_SESSION['username']; ?></h4><br>
                    <?php if ($_SESSION['verifyStatus'] === "verified") {
                    ?>
                        <!-- <a href="account/managePosts.php" class="btn-blue-500"><i class="fa-solid fa-pen-to-square"></i> Manage Posts</a> -->
                    <?php } else { ?>
                        <button class="btn-red-500" disabled>โปรดยืนยัน Email ก่อนเริ่มสร้าง POST</button>
                    <?php } ?>
                <?php } else { ?>
                    <a href="<?= base_url('/account/signup.php') ?>" class="btn-blue-400-outline">SIGN UP</a>
                    <a href="<?= base_url('/account/login.php') ?>" class="btn-green-400-outline">LOGIN</a>
                <?php } ?>
            </div>
        </div>
    </section>
    <div class="bg-gray-100 min-h-screen p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">

            <div class="max-w-2xl mx-auto space-y-5 lg:block">
                <!-- Search Blogs -->
                <div class="card-white mb-5">
                    <h2 class="text-lg font-bold text-gray-500 mb-3">Search</h2>
                    <form action="search.php" method="GET" class="relative">
                        <input type="text" name="q" placeholder="ค้นหาบทความ..." class="w-full px-4 py-2 border rounded-full focus:outline-none focus:border-blue-500 bg-gray-50 pr-10">
                        <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-500">
                            <i class="fa-solid fa-search fa-lg cursor-pointer"></i>
                        </button>
                    </form>
                </div>
                <!-- End Search Blogs -->

                <div class="card-white">
                    <div class="flex items-center space-x-4">
                        <h2 class="text-lg font-bold text-gray-500">Category</h2>
                    </div>

                    <?php
                    if (!empty($getBlogCategory)):
                    ?>
                        <!-- Category Buttons -->
                        <div class="flex flex-col p-3 space-y-3">
                            <?php foreach ($getBlogCategory as $category): ?>
                                <a href="category.php?categoryId=<?php echo $category['categoryId']; ?>" class="btn-blue-500-full"><?php echo $category['categoryName']; ?></a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Server List -->
                <div class="card-white">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-500">
                            <i class="fa-solid fa-server mr-2 text-blue-500"></i>เซิร์ฟเวอร์ยอดนิยม
                        </h2>
                        <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded-full animate-pulse">Online</span>
                    </div>

                    <div class="space-y-4">

                    </div>
                </div>

            </div>
            <div class="flex flex-col">
                <!-- Filler Post -->
                <div class="flex space-x-2 mb-4 w-full mmax-w-md mx-auto">
                    <a href="?sort=latest" class="<?= ($sortBy === 'latest') ? 'btn-blue-500-full' : 'btn-gray-500-full' ?>">
                        <i class="fa-solid fa-clock"></i> บทความล่าสุด
                    </a>
                    <a href="?sort=popular" class="<?= ($sortBy === 'popular') ? 'btn-red-500-full' : 'btn-gray-500-full' ?>">
                        <i class="fa-solid fa-heart"></i> บทความยอดนิยม
                    </a>
                </div>

                <!-- Announce -->
                <div class="card-green-100">
                    <div class="flex">
                        <div>
                            <p class="font-bold"><i class="fa-solid fa-bullhorn"></i> ประกาศ! Date: <?php echo htmlspecialchars($settings['announceDate']); ?></p>
                            <p class="text-sm"><?php echo htmlspecialchars($settings['announceText']); ?></p>
                        </div>
                    </div>
                </div>
                <div id="blogGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3"></div>
                <?php
                $initialLimit = 6; // กำหนดจำนวนเริ่มต้น
                $counter = 0;      // ตัวนับ
                if (!empty($fetchAllBlogs)) {
                    foreach ($fetchAllBlogs as $blog) {

                        $imagePath = $image_blogs_paths[$blog['blogCategory']];

                        // ตรวจสอบว่าเกินจำนวนที่กำหนดไหม? ถ้าเกินให้ใส่ class "hidden" และ "blog-item-hidden"
                        $hiddenClass = ($counter >= $initialLimit) ? 'hidden blog-item-hidden' : '';

                        $userPic = !empty($blog['profileImage'])
                            ? '/img/profile_users/' . $blog['profileImage']
                            : '/img/profile_users/profile_default/default.webp';

                        $userProfile = base_url($userPic);
                ?>
                        <article class="<?php echo $hiddenClass; ?> card-white overflow-hidden mt-3 hover:shadow-2xl transition-shadow duration-300 transform hover:-translate-y-1">
                            <a href="blog/<?php echo $blog['slug']; ?>" class="block relative max-h-100 overflow-hidden group">
                                <img
                                    src="<?php echo base_url($imagePath . $blog['blogImage']); ?>"
                                    alt="Blog Cover"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" onerror="this.src='img/blogs_image/default.webp'">
                                <span class="absolute top-4 left-4 bg-blue-600 text-white text-xs px-3 py-1">
                                    <?php echo $blog['blogCategory']; ?>
                                </span>
                            </a>
                            <div class="flex p-6">
                                <i class="fa-regular fa-calendar mr-2"></i>
                                <span><?php echo $blog['createdAt']; ?></span> <span class="ml-auto"><?php echo number_format($blog['views']); ?> <i class="fa-solid fa-eye mr-1"></i></span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">
                                <a href="blog/<?php echo $blog['slug']; ?>"><?php echo $blog['blogTitle']; ?></a>
                            </h3>
                            <div class="flex item-center justify-between pt-4">
                                <div class="flex-item-center">
                                    <img src="<?php echo $userProfile; ?>" alt="Avatar" class="w-8 h-8 rounded-full mr-2">
                                    <span href="#" class="text-sm font-medium text-gray-700"><?php echo $blog['username']; ?></span>
                                </div>
                                <a href="blog/<?php echo $blog['slug']; ?>" class="btn-blue-500 flex items-center group">
                                    อ่านรายละเอียด
                                </a>
                            </div>
                        </article>
                    <?php
                        $counter++; // บวกเลขเพิ่มทีละ 1 
                    }
                    ?>
                <?php } else { ?>
                    <div class="col-span-3 text-center py-10 text-gray-500">
                        ยังไม่มีบทความในขณะนี้
                    </div>
                <?php } ?>

                <?php if ($totalBlogs > $initialLimit) { ?>
                    <div class="text-center mt-8" id="loadMoreContainer">
                        <button id="loadMoreBtn" class="cursor-pointer px-6 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-gray-300 transition focus:outline-none">
                            โหลดบทความเพิ่มเติม <i class="fa-solid fa-chevron-down"></i>
                        </button>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <!-- footer -->
    <?php include_once("components/footer.php"); ?>

    <script src="js/script.js"></script>
    <!-- JavaScript สำหรับปุ่ม Love -->
    <!-- <script src="js/scriptLove.js"></script> -->
    <!-- JavaScript สำหรับปุ่ม Comments -->
    <!-- <script src="js/scriptComments.js"></script> -->
</body>

</html>