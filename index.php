<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//connect database
session_start();
require_once("system/conn.php");
require_once("system/config.php");
require_once("system/blogSystem.php");
require_once("system/commentSystem.php");
require_once("system/loveSystem.php");
require_once("system/websiteSettingsSystem.php");

$sortBy = $_GET['sort'] ?? 'latest'; //กำหนดค่าเริ่มต้นเป็น 'latest' หากไม่มีค่าใน query string

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['btnPost'])) {

    $userId = $_SESSION['userId'];
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    $categoryId = htmlspecialchars($_POST['categoryId']);

    $imagePath = NULL;
    // $postResult = createPost($conn, $userId, $title, $content, $imagePath, $categoryId);

    if ($postResult) {
        $_SESSION['message'] = $postResult;
        header("Location: " . base_url('/index.php'));
        exit;
    }
}

$image_blogs_paths = [
    'papermc' => '/img/blogs_image/blogs_server/papermc/',
    'plugin' => '/img/blogs_image/blogs_plugin/plugin/',
];

//ระบบ Post
// $getCategory = getCategory($conn);

//ดึง Settings
$settings = getWebsiteSettings($conn);

$fetchAllBlogs = fetchAllBlogs($conn);

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
                        <a href="account/managePosts.php" class="btn-blue-500"><i class="fa-solid fa-pen-to-square"></i> Manage Posts</a>
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
            <!-- Tablet -->
            <div class="flex flex-row sm:flex-col gap-4 lg:hidden">
                <div class="max-w-2xl mx-auto space-y-5">
                    <div class="card-white">
                        <div class="flex items-center space-x-4">
                            <h2 class="text-lg font-bold text-gray-500">Category</h2>
                        </div>

                        <?php
                        if (!empty($getCategory)):
                        ?>
                            <!-- Category Buttons -->
                            <div class="flex flex-col p-3 space-y-3">
                                <?php foreach ($getCategory as $category): ?>
                                    <a href="category.php?categoryId=<?php echo $category['categoryId']; ?>" class="btn-blue-500-full"><?php echo $category['categoryName']; ?></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="max-w-2xl mx-auto space-y-5 hidden lg:block">
                <div class="card-white">
                    <div class="flex items-center space-x-4">
                        <h2 class="text-lg font-bold text-gray-500">Category</h2>
                    </div>

                    <?php
                    if (!empty($getCategory)):
                    ?>
                        <!-- Category Buttons -->
                        <div class="flex flex-col p-3 space-y-3">
                            <?php foreach ($getCategory as $category): ?>
                                <a href="category.php?categoryId=<?php echo $category['categoryId']; ?>" class="btn-blue-500-full"><?php echo $category['categoryName']; ?></a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="flex flex-col">
                <!-- Form Post -->
                <?php if (isset($_SESSION['userId'])) { ?>

                    <?php if ($_SESSION['verifyStatus'] === "verified") {
                    ?>
                        <?php
                        if ($message):
                            echo $message;
                        endif;
                        ?>
                        <div class="w-full max-w-md mx-auto">
                            <!-- Toggle Post Button -->
                            <button id="togglePost" class="btn-blue-500 w-full">
                                Create Post
                            </button>

                            <form action="" id="postForm" method="post" class="hidden bg-white shadow-md rounded-sm m-4 p-4" enctype="multipart/form-data">
                                <h2 class="text-lg font-bold mb-4 text-center">Post Form</h2>

                                <div class="mb-4">
                                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title</label>
                                    <input type="text" name="title" class="input-form" placeholder="Enter title" required>
                                </div>
                                <div class="mb-4">
                                    <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Content</label>
                                    <textarea name="content" rows="4" class="input-form" placeholder="Enter content" required></textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Select Category</label>
                                    <select name="categoryId" id="categoryId" class="input-form" required>
                                        <option value="" disabled selected>Select Category</option>
                                        <option value="1">Minecraft Java Edition</option>
                                        <option value="2">Minecraft Bedrock Edition</option>
                                        <option value="3">Promote Minecraft Server</option>
                                        <option value="4">Other Games</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Upload Image</label>
                                    <input type="file" name="imagePost" accept="image/*">
                                </div>

                                <!-- Submit Button -->
                                <div class="text-center">
                                    <input type="submit" name="btnPost" class="btn-green-500 w-full" value="Post">
                                </div>
                            </form>
                        </div>
                    <?php } ?>
                    <br>
                <?php } ?>

                <!-- Filler Post -->
                <div class="flex space-x-2 mb-4 w-full mmax-w-md mx-auto">
                    <a href="?sort=latest" class="<?= ($sortBy === 'latest' || $sortBy === '') ? 'btn-blue-500-full' : 'btn-gray-500-full' ?>">
                        <i class="fa-solid fa-clock"></i> โพสต์ล่าสุด
                    </a>
                    <a href="?sort=most_loved" class="<?= ($sortBy === 'most_loved') ? 'btn-red-500-full' : 'btn-gray-500-full' ?>">
                        <i class="fa-solid fa-heart"></i> โพสต์ยอดนิยม
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

                <?php
                if (!empty($fetchAllBlogs)) {
                    foreach ($fetchAllBlogs as $blogs) {

                        $imagePath = $image_blogs_paths[$blogs['blogCategory']];
                ?>
                        <article class="card-white overflow-hidden mt-3 hover:shadow-2xl transition-shadow duration-300 transform hover:-translate-y-1">
                            <a href="blog/<?php echo $blogs['slug']; ?>" class="block relative h-48 overflow-hidden group">
                                <img
                                    src="<?php echo base_url($imagePath . $blogs['blogImage']); ?>"
                                    alt="Blog Cover"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <span class="absolute top-4 left-4 bg-blue-600 text-white text-xs px-3 py-1">
                                    Technology
                                </span>
                            </a>
                            <div class="p-6">
                                <i class="fa-regular fa-calendar mr-2"></i>
                                <span><?php echo $blogs['createdAt']; ?></span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">
                                <a href="blog/<?php echo $blogs['slug']; ?>"><?php echo $blogs['blogTitle']; ?></a>
                            </h3>
                            <div class="flex item-center justify-between pt-4">
                                <div class="flex-item-center">
                                    <img src="https://i.pravatar.cc/150?img=32" alt="Avatar" class="w-8 h-8 rounded-full mr-2">
                                    <span href="#" class="text-sm font-medium text-gray-700"><?php echo $blogs['username']; ?></span>
                                </div>
                                <a href="blog/<?php echo $blogs['slug']; ?>" class="btn-blue-500 flex items-center group">
                                    อ่านรายละเอียด
                                </a>
                            </div>
                        </article>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <!-- footer -->
    <?php include_once("components/footer.php"); ?>

    <script src="js/script.js"></script>
    <!-- JavaScript สำหรับปุ่ม Love -->
    <script src="js/scriptLove.js"></script>
    <!-- JavaScript สำหรับปุ่ม Comments -->
    <!-- <script src="js/scriptComments.js"></script> -->
</body>

</html>