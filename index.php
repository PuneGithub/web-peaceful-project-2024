<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//connect database
session_start();
require_once("system/conn.php");
require_once("system/config.php");
require_once("system/blogSystem.php");
require_once("system/serverSystem.php");
require_once("system/websiteSettingsSystem.php");

$categoryId = $_GET['categoryId'] ?? null; // รับค่าหมวดหมู่จาก URL
$sortBy = $_GET['sort'] ?? 'latest';       // รับการเรียงลำดับ


$image_blogs_paths = [
    'papermc' => '/img/blogs_image/blogs_server/papermc/',
    'plugin' => '/img/blogs_image/blogs_plugin/plugin/',
    'server'  => '/img/blogs_image/blogs_server/server/',
];



//ดึงบทความมาแสดง
$fetchAllBlogs = fetchAllBlogs($conn, $sortBy, $categoryId);
$totalBlogs = countAllBlogs($conn, $categoryId);

//ดึง Settings
$settings = getWebsiteSettings($conn);

//ดึงหมวดหมู่บทความมาแสดง
$getBlogCategory = getCategory($conn);

//ดึงรายชื่อเซิฟเวอร์มาแสดง
$topServers = fetchApprovedServers($conn, 3);

//ดึงข้อความจาก SESSION ถ้ามี
$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://zencrafterly.com/"/>

    <?php
    $seoTitle = !empty($settings['site_seo_title']) ? $settings['site_seo_title'] : $settings['webTitle'];
    $seoDesc  = !empty($settings['site_seo_description']) ? $settings['site_seo_description'] : $settings['heroTitle'];
    $seoKey   = !empty($settings['site_seo_keywords']) ? $settings['site_seo_keywords'] : "Minecraft, บทความ, โปรโมทเซิร์ฟเวอร์";
    ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/output.css">

    <link rel="icon" href="data:,">
    <title><?= htmlspecialchars($seoTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($seoDesc) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($seoKey) ?>">
    <meta name="author" content="Zencrafterly Team">

    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= base_url('/') ?>">
    <meta property="og:title" content="<?= htmlspecialchars($seoTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($seoDesc) ?>">

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
                        <button class="btn-red-500" disabled>โปรดยืนยัน Email ก่อนเพิ่มเซิร์ฟเวอร์</button>
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
                        <input type="text" name="q" placeholder="ค้นหาบทความ..." class="w-full px-4 py-2 border rounded-full focus:outline-none focus:border-blue-500 bg-gray-50 pr-10" required>
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

                    <div class="flex flex-col p-3 space-y-3">
                        <a href="index.php" class="<?= !$categoryId ? 'btn-blue-500-full' : 'btn-gray-500-full' ?>">
                            ทั้งหมด
                        </a>

                        <?php if (!empty($getBlogCategory)): ?>
                            <?php foreach ($getBlogCategory as $category): ?>
                                <a href="index.php?categoryId=<?php echo $category['categoryId']; ?>&sort=<?= $sortBy ?>"
                                    class="<?= ($categoryId == $category['categoryId']) ? 'btn-blue-500-full' : 'btn-gray-500-full' ?>">
                                    <?php echo $category['categoryName']; ?>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Server List -->
                <div class="card-white">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-500">
                            <i class="fa-solid fa-server mr-2 text-blue-500"></i>เซิร์ฟเวอร์ยอดนิยม
                        </h2>
                        <span class="text-[10px] bg-green-100 text-green-600 px-2 py-1 rounded-full animate-pulse font-bold">SERVER ONLINE</span>
                    </div>

                    <div class="space-y-4">
                        <?php if (!empty($topServers)): ?>
                            <?php
                            $rank = 1;
                            foreach ($topServers as $server):
                                $medalColor = [1 => 'text-yellow-400', 2 => 'text-slate-300', 3 => 'text-orange-400'];
                            ?>
                                <div class="group border-b border-gray-50 pb-3 last:border-0 last:pb-0 relative">
                                    <div class="flex items-center space-x-3">
                                        <div class="relative">
                                            <img src="img/server-icons/<?= $server['serverImage'] ?: 'default_server.webp' ?>"
                                                class="w-12 h-12 rounded-xl object-cover border-2 border-gray-100 group-hover:border-blue-400 transition"
                                                onerror="this.src='img/server-icons/default_server.webp'">

                                            <span class="absolute -top-1 -left-1 <?= $medalColor[$rank] ?> text-xs drop-shadow-md">
                                                <i class="fa-solid fa-crown"></i>
                                            </span>
                                        </div>

                                        <div class="flex-1">
                                            <h3 class="font-bold text-sm text-gray-800 leading-tight flex items-center gap-1">
                                                <?= htmlspecialchars($server['serverName']) ?>
                                                <span class="text-[10px] text-blue-500 font-normal">(<?= number_format($server['votes']) ?> โหวต)</span>
                                            </h3>

                                            <span class="server-status text-[9px] font-bold" data-ip="<?= htmlspecialchars($server['serverIP']) ?>">
                                                <i class="fa-solid fa-spinner fa-spin"></i> Checking...
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mt-2 flex items-center bg-gray-50 rounded-lg p-1 border border-gray-100">
                                        <code class="text-[10px] flex-1 px-2 font-mono text-gray-500 truncate" id="ip-<?= $server['serverId'] ?>">
                                            <?= htmlspecialchars($server['serverIP']) ?>
                                        </code>
                                        <button onclick="copyIP('ip-<?= $server['serverId'] ?>')"
                                            class="bg-white border text-[9px] px-2 py-1 rounded shadow-sm hover:bg-blue-600 hover:text-white transition font-bold uppercase">
                                            COPY
                                        </button>
                                    </div>
                                </div>
                            <?php
                                $rank++;
                            endforeach;
                            ?>
                        <?php else: ?>
                            <div class="text-center py-6">
                                <i class="fa-solid fa-ghost text-gray-200 text-3xl mb-2"></i>
                                <p class="text-xs text-gray-400 italic">ยังไม่มีเซิร์ฟเวอร์แนะนำในขณะนี้</p>
                            </div>
                        <?php endif; ?>
                    </div>


                    <a href="servers.php" class="block text-center mt-5 py-2 text-xs font-bold text-blue-500 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                        ดูเซิร์ฟเวอร์ทั้งหมด <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
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

    <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2"></div>
    <!-- footer -->
    <?php include_once("components/footer.php"); ?>

    <script src="js/script.js"></script>

    <!-- JavaScript สำหรับปุ่ม Love -->
    <!-- <script src="js/scriptLove.js"></script> -->
    <!-- JavaScript สำหรับปุ่ม Comments -->
    <!-- <script src="js/scriptComments.js"></script> -->
</body>

</html>