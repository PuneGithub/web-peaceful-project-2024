<nav class="bg-gray-800 p-4">
    <div class="container mx-auto flex justify-center items-center space-x-6">
        <!-- Logo -->
        <div class="text-white text-xl font-bold">
            Peaceful Network
        </div>

        <!-- Menu Links Desktop -->
        <div class="hidden sm:flex space-x-6">
            <a href="/web_peaceful_project_2024/index.php" class="font-semibold text-gray-300 hover:text-white">HOME</a>
            <a href="/web_peaceful_project_2024/blog.php" class="font-semibold text-gray-300 hover:text-white">BLOG</a>
            <a href="/web_peaceful_project_2024/resources.php" class="font-semibold text-gray-300 hover:text-white">RESOURCES</a>
            <a href="/web_peaceful_project_2024/about.php" class="font-semibold text-gray-300 hover:text-white">ABOUT</a>
        </div>

        <!-- Sign Up Button -->
        <div class="hidden sm:block">
            <a href="/web_peaceful_project_2024/account/signup.php" class="btn-blue-500 mr-2">SIGN UP</a>
            <a href="/web_peaceful_project_2024/account/signin.php" class="btn-green-500">SIGN IN</a>
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
            <div class="flex flex-col gap-3">
                <a href="" class="btn-blue-500">SIGN UP</a>
                <a href="" class="btn-green-500">SIGN IN</a>
            </div>
        </div>

    </div>
</nav>