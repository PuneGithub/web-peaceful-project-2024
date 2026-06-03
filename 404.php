<?php
require_once("system/config.php");
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once __DIR__ . '/components/favicon.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="<?= base_url('/css/output.css') ?>">
    <title>404 - ไม่พบหน้านี้ | Zencrafterly</title>
</head>

<body class="min-h-screen bg-gray-900 flex flex-col items-center justify-center text-white bg-cover bg-fixed bg-center" style="background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('<?= base_url('/img/bg.webp') ?>');">

    <div class="text-center p-10 bg-black/40 backdrop-blur-md rounded-3xl border border-white/10 shadow-2xl max-w-lg mx-4">
        
        <div class="mb-6 relative">
            <i class="fa-solid fa-map-location-dot text-8xl text-yellow-500 animate-bounce"></i>
            <div class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full uppercase shadow-lg">Error</div>
        </div>

        <h1 class="text-7xl font-black mb-4 tracking-tighter">404</h1>
        <h2 class="text-2xl font-bold mb-4 uppercase text-yellow-400 italic">โอ้ะ! คุณหลงทางในป่าลึกซะแล้ว</h2>
        
        <p class="text-gray-300 mb-8 leading-relaxed">
            หน้าที่คุณกำลังมองหาอาจจะถูกย้าย ถูกลบ <br class="hidden md:block"> 
            หรืออาจโดน <span class="text-green-400 font-bold italic">Creeper</span> ระเบิดไปแล้วก็เป็นได้!
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?= base_url('/index.php') ?>" class="btn-blue-500 flex items-center justify-center gap-2 px-8 py-3 text-lg font-bold shadow-lg hover:scale-105 transition-transform">
                <i class="fa-solid fa-house"></i> กลับหน้าหลัก
            </a>
            <a href="<?= base_url('/blogs.php') ?>" class="btn-green-500 flex items-center justify-center gap-2 px-8 py-3 text-lg font-bold shadow-lg hover:scale-105 transition-transform">
                <i class="fa-solid fa-book"></i> อ่านบทความอื่นๆ
            </a>
        </div>

        <div class="mt-10 text-gray-500 text-sm border-t border-white/10 pt-6">
            หากคุณคิดว่าเป็นข้อผิดพลาดจากทางเรา โปรดแจ้งที่ <br>
            <span class="text-gray-300 font-medium italic">support@zencrafterly.com</span>
        </div>
    </div>

</body>
</html>