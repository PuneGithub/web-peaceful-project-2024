<script>
        const BASE_URL = "<?= base_url() ?>";
</script>
<nav class="bg-gray-800 p-4 shadow-md">
    <div class="container mx-auto">
        <div class="flex justify-between items-center">

            <div class="flex-shrink-0">
                <a href="<?= base_url('/index') ?>" class="text-white text-2xl font-bold hover:text-blue-400 transition">
                    Zencrafterly
                </a>
            </div>

            <div class="hidden lg:flex items-center space-x-8">
                <div class="flex space-x-6">
                    <a href="<?= base_url('/index') ?>" class="font-semibold text-gray-300 hover:text-white transition">HOME</a>
                    <a href="<?= base_url('/servers') ?>" class="font-semibold text-gray-300 hover:text-white transition">SERVERS</a>
                    <a href="<?= base_url('/blogs') ?>" class="font-semibold text-gray-300 hover:text-white transition">BLOG</a>
                    <a href="<?= base_url('/resources') ?>" class="font-semibold text-gray-300 hover:text-white transition">RESOURCES</a>
                    <a href="<?= base_url('/about') ?>" class="font-semibold text-gray-300 hover:text-white transition">ABOUT</a>
                    <a href="<?= base_url('/report') ?>" class="font-semibold text-gray-300 hover:text-white transition"><i class="fa-solid fa-bug mr-1"></i>แจ้งปัญหา</a>
                </div>

                <div class="flex items-center space-x-3 border-l border-gray-700 pl-6">
                    <?php if (isset($_SESSION['userId'])): ?>
                        <span class="text-white font-medium mr-2">
                            <i class="fa-solid fa-user-circle mr-1"></i> <?= $_SESSION['username']; ?>
                        </span>
                        <a href="<?= base_url('/server/myServers') ?>" class="btn-lime-500 text-xs py-2">
                            <i class="fa-solid fa-list-check"></i> เซิร์ฟเวอร์ของฉัน
                        </a>
                        <a href="<?= base_url('/account/account') ?>" class="btn-cyan-500 text-xs py-2">Account</a>
                        <a href="<?= base_url('/account/logout') ?>" class="btn-red-500 text-xs py-2">Logout</a>
                    <?php else: ?>
                        <a href="<?= base_url('/account/signup') ?>" class="btn-blue-500 text-sm">SIGN UP</a>
                        <a href="<?= base_url('/account/login') ?>" class="btn-green-500 text-sm">LOGIN</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="lg:hidden flex items-center">
                <button class="text-gray-300 hover:text-white focus:outline-none cursor-pointer p-2" onclick="toggleMenu()">
                    <svg id="menu-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="hidden lg:hidden mt-4 pb-4 space-y-2 border-t border-gray-700 pt-4">
            <a href="<?= base_url('/index') ?>" class="block font-semibold text-gray-300 hover:text-white py-2">HOME</a>
            <a href="<?= base_url('/servers') ?>" class="block font-semibold text-gray-300 hover:text-white py-2">SERVERS</a>
            <a href="<?= base_url('/blogs') ?>" class="block font-semibold text-gray-300 hover:text-white py-2">BLOG</a>
            <a href="<?= base_url('/resources') ?>" class="block font-semibold text-gray-300 hover:text-white py-2">RESOURCES</a>
            <a href="<?= base_url('/about') ?>" class="block font-semibold text-gray-300 hover:text-white py-2">ABOUT</a>
            <a href="<?= base_url('/report') ?>" class="block font-semibold text-gray-300 hover:text-white py-2"><i class="fa-solid fa-bug mr-2"></i>แจ้งปัญหาการใช้งาน</a>

            <hr class="border-gray-700 my-2">

            <?php if (isset($_SESSION['userId'])): ?>
                <div class="flex flex-col gap-2">
                    <p class="font-bold text-white mb-1"><i class="fa-solid fa-user mr-2"></i><?= $_SESSION['username']; ?></p>
                    <a href="<?= base_url('/server/myServers') ?>" class="btn-lime-500 w-full text-center py-2 text-sm">เซิร์ฟเวอร์ของฉัน</a>
                    <a href="<?= base_url('/account/account') ?>" class="btn-cyan-500 w-full text-center py-2 text-sm">Account</a>
                    <a href="<?= base_url('/account/logout') ?>" class="btn-red-500 w-full text-center py-2 text-sm">Logout</a>
                </div>
            <?php else: ?>
                <div class="flex flex-col gap-2">
                    <a href="<?= base_url('/account/signup') ?>" class="btn-blue-500 w-full text-center py-2">SIGN UP</a>
                    <a href="<?= base_url('/account/login') ?>" class="btn-green-500 w-full text-center py-2">LOGIN</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

<script>
    function toggleMenu() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    }
</script>