<nav class="bg-gray-800 p-4">
    <div class="container mx-auto flex justify-center items-center space-x-6">
        <!-- Logo -->
        <div class="text-white text-xl font-bold">
            Peaceful Network
        </div>

        <!-- Menu Links Desktop -->
        <div class="hidden sm:flex space-x-6">
            <a href="/index.php" class="font-semibold text-gray-300 hover:text-white">HOME</a>
            <a href="/blog.php" class="font-semibold text-gray-300 hover:text-white">BLOG</a>
            <a href="/forum.php" class="font-semibold text-gray-300 hover:text-white">FORUM</a>
            <a href="/resources.php" class="font-semibold text-gray-300 hover:text-white">RESOURCES</a>
            <a href="/about.php" class="font-semibold text-gray-300 hover:text-white">ABOUT</a>
        </div>

        <!-- Sign Up Button -->
        <div class="hidden sm:block">
            <?php
            if (isset($_SESSION['userId'])) {
            ?>
                <div class="hidden sm:flex items-center space-x-4">
                    <p class="font-bold text-lg text-white"><?php echo $_SESSION['username']; ?></p>
                    <a href="/account/account.php" class="btn-cyan-500">Account</a>
                    <a href="/account/logout.php" class="btn-red-500">Logout</a>
                </div>
            <?php } else { ?>
                <a href="/account/signup.php" class="btn-blue-500 mr-2">SIGN UP</a>
                <a href="/account/login.php" class="btn-green-500">LOGIN</a>
            <?php } ?>
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
            <?php if (isset($_SESSION['userId'])) {
            ?>
                <div class="flex flex-col gap-3">
                    <p class="font-bold text-lg text-white"><?php echo $_SESSION['username']; ?></p>
                    <a href="./account/account.php" class="btn-cyan-500">Account</a>
                    <a href="./account/logout.php" class="btn-red-500">Logout</a>
                </div>
            <?php } else {  ?>
                <div class="flex flex-col gap-3">
                    <a href="./account/signup.php" class="btn-blue-500">SIGN UP</a>
                    <a href="./account/login.php" class="btn-green-500">LOGIN</a>
                </div>
            <?php } ?>
        </div>

    </div>
</nav>