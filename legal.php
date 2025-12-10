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

<body style="background-image: url('img/bg.webp');">

    <!-- header navbar -->
    <?php
    include_once("components/header-navbar.php");
    ?>

    <div class="container mx-auto p-4">
        <div class="card-white">
            <h1 class="text-4xl font-bold mb-3">ข้อกำหนด และโยบายเว็บไซต์</h1>
    
            <nav class="flex justify-center space-x-4 mb-10 p-3 bg-gray-300 rounded-lg">
                <a href="#privacy" class="text-blue-600 hover:text-blue-800">นโยบายความเป็นส่วนตัว</a>
                <span class="text-gray-400">|</span>
                <a href="#terms" class="text-blue-600 hover:text-blue-800">ข้อกำหนดและเงื่อนไข</a>
            </nav>
    
            <div id="privacy">
                <h2 class="text-3xl">1. นโยบายความเป็นส่วนตัว</h2>
                <p class="text-gray-600 mb-3">ปรับปรุงล่าสุด 04/10/2025</p>
    
                <h3 class="text-xl mt-3 mb-2">1.1 การเก็บรวบรวมข้อมูล</h3>
                <p class="mb-3">
                    เว็บไซต์นี้รวบรวมข้อมูลส่วนบุคคลที่จำเป็น "อีเมล และรหัสผ่าน","ชื่อผู้ใช้","รูปโปรไฟล์" เพื่อใช้การงาน
                </p>
    
                <h3 class="text-xl mt-3 mb-2">1.2 สิทธิของผู้ใช้งาน</h3>
                <p class="mb-3">
                    คุณมีสิทธิในการเข้าถึง แก้ไข หรือขอลบบัญชีของคุณได้ โดยสามารถแจ้งผ่านช่องทางที่กำหนด.
                </p>
            </div>
    
            <div id="terms">
                <div class="text-3xl">2. ข้อกำหนดและเงื่อนไขการใช้บริการ</div>
                <p class="text-gray-600 mb-3">ปรับปรุงล่าสุด 04/10/2025</p>
    
                <h3 class="text-xl mt-3 mb-2">2.1 บัญชีผู้ใช้</h3>
                <p class="mb-3">
                    ผู้ใช้ต้องรับผิดชอบในการรักษาความลับของรหัสผ่านและข้อมูลบัญชี. ห้ามมิให้มีการใช้ชื่อผู้ใช้หรือรหัสผ่านของผู้อื่นโดยไม่ได้รับอนุญาต.
                </p>
    
                <h3 class="text-xl mt-3 mb-2">2.2 ข้อจำกัดในการใช้งาน</h3>
                <p class="mb-3">
                    ห้ามใช้เว็บไซต์เพื่อโพสต์เนื้อหาที่ผิดกฎหมาย, หมิ่นประมาท, หรือละเมิดสิทธิ์ของบุคคลอื่น การละเมิดข้อกำหนดเหล่านี้อาจส่งผลให้บัญชีถูกระงับ
                </p>
            </div>
        </div>


    </div>
</body>

</html>