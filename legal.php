<?php
require_once("system/conn.php");
require_once("system/config.php");
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

    <div class="container mx-auto p-4">
        <h1 class="text-4xl font-bold mb-3">ข้อกำหนด และโยบายเว็บไซต์</h1>

        <nav class="flex justify-center space-x-4 mb-10 p-3 bg-gray-300 rounded-lg">
            <a href="#privacy" class="text-blue-600 hover:text-blue-800">นโยบายความเป็นส่วนตัว</a>
            <span class="text-gray-400">|</span>
            <a href="#terms" class="text-blue-600 hover:text-blue-800">ข้อกำหนดและเงื่อนไข</a>
        </nav>

        <div class="card" id="privacy">
            <h2 class="">1. นโยบายความเป็นส่วนตัว</h2>
            <p class="text-gray-600 mb-3">ปรับปรุงล่าสุด 04/10/2025</p>

            <h3 class="text-xl mt-3 mb-2">1.1 การเก็บรวบรวมข้อมูล</h3>
        </div>
    </div>
</body>

</html>