<?php
//connect database
session_start();
require_once("system/conn.php");
require_once("system/postSystem.php");
require_once("system/loveSystem.php");

$getCategory = getCategory($conn);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/output.css">
    <link rel="icon" href="data:,">
    <title>Peaceful Network</title>
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
            <h1 class="text-4xl font-bold mb-4">Minecraft Peaceful Network</h1>
            <p class="text-lg mb-8">Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum, consectetur.</p>
            <div class="space-x-4">
                <?php
                if (isset($_SESSION['userId'])) {
                ?>
                    <h4 class="font-bold text-2xl">Welcome! <?php echo $_SESSION['username']; ?></h4><br>
                    <a href="account/managePosts.php" class="btn-blue-500"><i class="fa-solid fa-pen-to-square"></i> Manage Posts</a>
                <?php } else { ?>
                    <a href="/account/signup.php" class="btn-blue-400-outline">SIGN UP</a>
                    <a href="/account/login.php" class="btn-green-400-outline">LOGIN</a>
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
                <div class="max-w-2xl mx-auto space-y-5">
                    <div class="card-white">
                        <div class="flex items-center space-x-4">
                            <h2 class="text-lg font-bold text-gray-500">Forums</h2>
                        </div>

                        <!-- Forums Buttons -->
                        <div class="flex flex-col p-3 space-y-3">
                            <a href="#" class="btn-blue-500-full">Ask a question about Minecraft</a>
                            <a href="#" class="btn-blue-500-full">Minecraft 2</a>
                            <a href="#" class="btn-blue-500-full">Minecraft 3</a>
                        </div>
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
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === "POST") {


                        $userId = $_SESSION['userId'];
                        $title = htmlspecialchars($_POST['title']);
                        $content = htmlspecialchars($_POST['content']);
                        $categoryId = htmlspecialchars($_POST['categoryId']);

                        $imagePath = NULL;
                        $postResult = createPost($conn, $userId, $title, $content, $imagePath, $categoryId);

                        if ($postResult) {
                            echo $postResult;
                        }
                    }
                    ?>
                    <div class="w-full max-w-md mx-auto">
                        <!-- Toggle Post Button -->
                        <button id="toggleButton" class="btn-blue-500 w-full">
                            Create Post
                        </button>

                        <form action="" id="postForm" method="post" class="hidden bg-white shadow-md rounded m-4 p-4" enctype="multipart/form-data">
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
                    <br>
                <?php } ?>
                <!-- Post Feed -->
                <div class="space-y-6">
                    <div class="max-w-4xl mx-auto p-4 space-y-4">
                        <?php
                        $fetchPosts = fetchAllPosts($conn);
                        if (!empty($fetchPosts)) {
                            foreach ($fetchPosts as $post) {
                        ?>
                                <div class="card-white">
                                    <div class="flex items-center space-x-4">
                                        <img src="https://via.placeholder.com/40" alt="Profile" class="w-10 h-10 rounded-full">
                                        <div>
                                            <h2 class="font-semibold"><?php echo $post['username']; ?></h2>
                                            <span class="text-sm text-gray-500"><?php echo $post['createdAt']; ?></span>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <p class="text-gray-800"><?php echo $post['title']; ?></p>
                                        <?php if (!empty($post['imagePost']) && file_exists("img/posts_image/" . $post['imagePost'])): ?>
                                            <img src="img/posts_image/<?php echo $post['imagePost']; ?>" alt="Post image" class="mt-2 rounded-lg w-full">
                                        <?php endif; ?>
                                        <p class="text-gray-800"><?php echo $post['content']; ?></p>
                                    </div>

                                    <!-- Post Actions -->
                                    <div class="mt-4 flex items-center justify-between">
                                        <span id="loveCount" class="text-gray-500">Loves: <?php echo $post['loveCount']; ?></span>
                                        <button class="love-btn" data-postid="<?php echo $post['postId']; ?>">
                                            <span class="heart-icon"><?php echo userHasLoved($conn,$post['postId'], $_SESSION['username']) ? "<i class='text-red-400 fa-solid fa-heart'></i>" : "<i class='text-red-300 fa-solid fa-heart'></i>"; ?></span>
                                        </button>
                                        <button class="btn-blue-500">Comment</button>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="max-w-2xl mx-auto space-y-5 hidden lg:block">
                <div class="card-white">
                    <div class="flex items-center space-x-4">
                        <h2 class="text-lg font-bold text-gray-500">Forums</h2>
                    </div>

                    <!-- Forums Buttons -->
                    <div class="flex flex-col p-3 space-y-3">
                        <a href="#" class="btn-blue-500-full">Ask a question about Minecraft</a>
                        <a href="#" class="btn-blue-500-full">Minecraft 2</a>
                        <a href="#" class="btn-blue-500-full">Minecraft 3</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    <?php include_once("components/footer.php"); ?>
    <script src="js/script.js"></script>
    <!-- JavaScript สำหรับปุ่ม Love -->
    <script src="js/scriptLove.js"></script>
</body>

</html>