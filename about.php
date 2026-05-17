<?php
//connect database
require_once("system/conn.php");
require_once("system/config.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/output.css">
    <title>Zencrafterly</title>
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
            <h1 class="text-4xl font-bold mb-4">Minecraft Zencrafterly</h1>
            <p class="text-lg mb-8">ศูนย์รวมความรู้ แหล่งรีวิวปลั๊กอิน และพื้นที่โปรโมทเซิร์ฟเวอร์ Minecraft ที่ดีที่สุดสำหรับคุณ</p>
            <div class="space-x-4">
                <a href="/web_peaceful_project_2024/account/signup.php" class="btn-blue-400-outline">SIGN UP</a>
                <a href="/web_peaceful_project_2024/account/signin.php" class="btn-green-400-outline">SIGN IN</a>
            </div>
        </div>
    </section>

    <main class="flex-grow py-16 px-6">
        <div class="max-w-7xl mx-auto">

            <div class="max-w-3xl mx-auto text-center mb-16">
                <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight">เกี่ยวกับเรา</h2>
                <div class="w-24 h-1 bg-blue-500 mx-auto mt-4 rounded-full"></div>
                <p class="mt-6 text-gray-600 text-lg leading-relaxed">
                    Zencrafterly คือแหล่งคอมมูนิตี้และพื้นที่รวบรวมข้อมูลชั้นนำสำหรับคนรัก Minecraft ไม่ว่าคุณจะเป็นผู้เล่นที่กำลังตามหาบ้านหลังใหม่ หรือเป็นเจ้าของเซิร์ฟเวอร์ที่ต้องการเรียนรู้เทคนิคการทำเซิร์ฟเวอร์ เรามีครบจบในที่เดียว
                </p>
            </div>

            <div class="max-w-5xl mx-auto flex flex-col md:flex-row bg-white rounded-2xl shadow-xl overflow-hidden transform transition duration-300 hover:shadow-2xl">

                <img src="img/bg_about.webp" alt="About Us" class="w-full md:w-1/2 object-cover h-64 md:h-auto">

                <div class="p-8 md:p-12 md:w-1/2 flex flex-col justify-center">
                    <span class="text-sm font-bold text-blue-500 tracking-widest uppercase mb-2">Minecraft Knowledge Hub</span>
                    <h3 class="text-3xl font-bold text-gray-800 mb-4">เป้าหมายของเรา</h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        เรามุ่งมั่นที่จะสร้างสังคมแห่งการแบ่งปัน รวบรวมบทความคุณภาพ เทคนิคการปรับแต่งเซิร์ฟเวอร์ (PaperMC, Plugins) และเป็นสะพานเชื่อมระหว่างผู้เล่นกับเจ้าของเซิร์ฟเวอร์เข้าด้วยกันอย่างยั่งยืน
                    </p>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-center"><i class="fa-solid fa-book-open text-blue-500 mr-3"></i> แหล่งเรียนรู้การสร้างเซิร์ฟเวอร์แบบเจาะลึก</li>
                        <li class="flex items-center"><i class="fa-solid fa-bullhorn text-blue-500 mr-3"></i> พื้นที่โปรโมทเซิร์ฟเวอร์เข้าถึงกลุ่มเป้าหมาย</li>
                        <li class="flex items-center"><i class="fa-solid fa-newspaper text-blue-500 mr-3"></i> อัปเดตข่าวสารและเทรนด์ใหม่ๆ ทันเหตุการณ์</li>
                    </ul>
                </div>
            </div>

            <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 mt-16">
                <div class="bg-white p-8 rounded-2xl shadow-md text-center border-t-4 border-blue-500 transition hover:-translate-y-2 duration-300">
                    <div class="w-16 h-16 mx-auto bg-blue-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fa-solid fa-laptop-code text-2xl text-blue-600"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-800 mb-3">Tutorials & Plugins</h4>
                    <p class="text-gray-600 text-sm">รวมบทความสอนทำเซิร์ฟเวอร์ รีวิวปลั๊กอินเด็ดๆ และแนวทางการตั้งค่า PaperMC ที่เจ้าของเซิร์ฟเวอร์ควรรู้</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-md text-center border-t-4 border-green-500 transition hover:-translate-y-2 duration-300">
                    <div class="w-16 h-16 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fa-solid fa-rocket text-2xl text-green-600"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-800 mb-3">Server Promotion</h4>
                    <p class="text-gray-600 text-sm">พื้นที่โฆษณาและโปรโมทเซิร์ฟเวอร์ Minecraft ของคุณ เพื่อดึงดูดผู้เล่นใหม่ๆ ให้เข้ามาร่วมสนุกในคอมมูนิตี้</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-md text-center border-t-4 border-orange-500 transition hover:-translate-y-2 duration-300">
                    <div class="w-16 h-16 mx-auto bg-orange-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fa-solid fa-users text-2xl text-orange-600"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-800 mb-3">Active Community</h4>
                    <p class="text-gray-600 text-sm">ค้นหาเซิร์ฟเวอร์ที่ตรงสไตล์คุณ อ่านรีวิวจากผู้เล่นจริง และพูดคุยแลกเปลี่ยนความคิดเห็นกันในกลุ่ม</p>
                </div>
            </div>

        </div>
    </main>


    <!-- footer -->
    <?php include_once("components/footer.php"); ?>
</body>

</html>