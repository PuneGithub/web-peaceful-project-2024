<?php
//connect database
require_once("system/conn.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/output.css">
    <title>Peaceful Network</title>
</head>
<script src="js/script.js"></script>

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
                    <h4 class="font-bold text-2xl">Welcome! <?php echo $_SESSION['username']; ?></h4>
                <?php } else { ?>
                    <a href="/web_peaceful_project_2024/account/signup.php" class="btn-blue-400-outline">SIGN UP</a>
                    <a href="/web_peaceful_project_2024/account/login.php" class="btn-green-400-outline">LOGIN</a>
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

                        <!-- Category Buttons -->
                        <div class="flex flex-col p-3 space-y-3">
                            <a href="#" class="btn-blue-500-full">Minecraft 1</a>
                            <a href="#" class="btn-blue-500-full">Minecraft 2</a>
                            <a href="#" class="btn-blue-500-full">Minecraft 3</a>
                        </div>
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

                    <!-- Category Buttons -->
                    <div class="flex flex-col p-3 space-y-3">
                        <a href="#" class="btn-blue-500-full">Minecraft 1</a>
                        <a href="#" class="btn-blue-500-full">Minecraft 2</a>
                        <a href="#" class="btn-blue-500-full">Minecraft 3</a>
                    </div>
                </div>
            </div>
            <div class="flex flex-col">
                <!-- Post Feed -->
                <div class="max-w-2xl mx-auto space-y-6">
                    <div class="card-white">
                        <div class="flex items-center space-x-4">
                            <img src="https://via.placeholder.com/40" alt="Profile" class="w-10 h-10 rounded-full">
                            <div>
                                <h2 class="font-semibold">John Doe</h2>
                                <span class="text-sm text-gray-500">5 Hours ago</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-gray-800">Lorem ipsum dolor sit amet consectetur adipisicing elit. Perspiciatis commodi laudantium soluta obcaecati illum hic! Aperiam porro laudantium nisi quos qui. Similique iure culpa accusamus molestiae rerum fugit et aperiam.</p>
                            <img src="https://via.placeholder.com/500" alt="Post image" class="mt-2 rounded-lg w-full">
                        </div>

                        <!-- Post Actions -->
                        <div class="mt-4 flex items-center justify-between">
                            <button class="btn-blue-500"><i class="text-red-400 fa-solid fa-heart"></i></button>
                            <button class="btn-blue-500">Comment</button>
                        </div>
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
</body>

</html>