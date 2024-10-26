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
                <a href="/web_peaceful_project_2024/account/signup.php" class="btn-blue-400-outline">SIGN UP</a>
                <a href="/web_peaceful_project_2024/account/signin.php" class="btn-green-400-outline">SIGN IN</a>
            </div>
        </div>
    </section>
    <div class="bg-gray-100 min-h-screen p-6">
        <div class="card-gray-300">
            <div class="max-w-7xl max-auto text-center">
                <!-- Heading -->
                <h2>About Us</h2>
                <p class="mt-2 text-gray-500 text-lg">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui doloribus cupiditate quod vitae! Debitis aut voluptatem ducimus? Corrupti consequatur nemo tenetur, totam impedit animi! Nemo iure quibusdam provident dicta illum.
                </p>
            </div>

            <!-- Content Section -->
            <div class="mt-12 max-w-5xl mx-auto flex flex-col md:flex-row bg-white rounded-lg overflow-hidden">

                <img src="https://via.placeholder.com/600x400" alt="About Us" class="w-full md:w-1/2 object-cover">

                <!-- Text Section -->
                <div class="p-8 md:w1/2">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase">Peaceful Network</h3>
                    <p class="text-gray-600 mt-4">
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Consequuntur, saepe?
                    </p>
                </div>
            </div>
        </div>
    </div>


    <!-- footer -->
    <?php include_once("components/footer.php"); ?>
</body>

</html>