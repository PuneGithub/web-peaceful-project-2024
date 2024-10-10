<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/output.css">
    <title>Peaceful Network</title>
</head>
<script src="js/script.js"></script>

<body>
    <nav class="bg-gray-800 p-4">
        <div class="container mx-auto flex justify-center items-center space-x-6">
            <!-- Logo -->
            <div class="text-white text-xl font-bold">
                Peaceful Network
            </div>

            <!-- Menu Links Desktop -->
            <div class="hidden sm:flex space-x-6">
                <a href="#" class="font-semibold text-gray-300 hover:text-white">HOME</a>
                <a href="#" class="font-semibold text-gray-300 hover:text-white">BLOG</a>
                <a href="#" class="font-semibold text-gray-300 hover:text-white">RESOURCES</a>
                <a href="#" class="font-semibold text-gray-300 hover:text-white">ABOUT</a>
            </div>

            <!-- Sign Up Button -->
            <div class="hidden sm:block">
                <a href="#" class="btn-blue-500">SIGN UP</a>
            </div>

            <!-- Hamburger Menu Mobile -->
            <div class="sm:hidden">
                <button class="text-white focus:outline-none" onclick="toggleMenu()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Menu Links Mobile -->
            <div id="mobile-menu" class="hidden sm:hidden">
                <a href="#" class="block font-semibold text-gray-300 hover:text-white py-2">HOME</a>
                <a href="#" class="block font-semibold text-gray-300 hover:text-white py-2">BLOG</a>
                <a href="#" class="block font-semibold text-gray-300 hover:text-white py-2">RESOURCES</a>
                <a href="#" class="block font-semibold text-gray-300 hover:text-white py-2">ABOUT</a>
                <a href="#" class="btn-blue-500">SIGN UP</a>
            </div>

        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative bg-cover bg-center h-96" style="background-image: url('img/bg.webp');">

        <div class="absolute inset-0 bg-black opacity-50"></div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col items-center justify-center h-full text-center text-white">
            <h1 class="text-4xl font-bold mb-4">Minecraft Peaceful Network</h1>
            <p class="text-lg mb-8">Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum, consectetur.</p>
            <div class="space-x-4">
                <a href="#" class="btn-blue-400-outline">SIGN UP</a>
                <a href="#" class="btn-green-400-outline">SIGN IN</a>
            </div>
        </div>
    </section>
    <div class="bg-gray-100 min-h-screen p-6">
        <!-- Post Feed -->
        <div class="max-w-2xl mx-auto space-y-6">
            <div class="bg-white p-4 rounded-lg shadow-md">
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
            </div>
        </div>
    </div>

</body>

</html>