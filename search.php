<?php
session_start();
require_once("system/conn.php");
require_once("system/config.php");
require_once("system/blogSystem.php");
require_once("system/serverSystem.php");

// 1. รับค่าค้นหาจาก URL (q)
$q = isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '';

// 2. ดึงข้อมูลที่ตรงเงื่อนไข (สมมติว่าคุณมีฟังก์ชัน search ใน System แล้ว)
// ถ้ายังไม่มีฟังก์ชัน searchBlogs ให้ลองส่งค่า $q เข้าไปที่ฟังก์ชันดึงบทความปกติแล้วกรองด้วย LIKE
$blogResults = []; 
if (!empty($q)) {
    // ดึงบทความที่ชื่อหรือเนื้อหาตรงกับ $q
    $blogResults = fetchAllBlogs($conn, 'latest', null, 0, $q);
    // ดึงเซิร์ฟเวอร์ที่ชื่อหรือ IP ตรงกับ $q
    $serverResults = fetchApprovedServers($conn, null, null, $q);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ผลการค้นหา: <?= $q ?> - Zencrafterly</title>
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
</head>
<body class="bg-gray-50">
    <?php include_once("components/header-navbar.php"); ?>

    <div class="max-w-7xl mx-auto px-4 py-10">
        <h1 class="text-2xl font-bold mb-8 text-gray-800">
            <i class="fa-solid fa-magnifying-glass mr-2 text-blue-600"></i>
            ผลการค้นหาสำหรับ: <span class="text-blue-600">"<?= htmlspecialchars($q) ?>"</span>
        </h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                <h2 class="text-lg font-bold border-b pb-2">บทความที่พบ (<?= count($blogResults) ?>)</h2>
                <?php if (!empty($blogResults)): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($blogResults as $blog): ?>
                            <div class="card-white p-4">
                                <h3 class="font-bold text-blue-600 underline"><a href="blog/<?= $blog['slug'] ?>"><?= $blog['blogTitle'] ?></a></h3>
                                <p class="text-xs text-gray-500 mt-2 line-clamp-2"><?= strip_tags($blog['blogContent']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-400 italic">ไม่พบบทความที่เกี่ยวข้อง</p>
                <?php endif; ?>
            </div>

            <div class="space-y-6">
                <h2 class="text-lg font-bold border-b pb-2">เซิร์ฟเวอร์ที่พบ (<?= count($serverResults) ?>)</h2>
                <?php if (!empty($serverResults)): ?>
                    <div class="flex flex-col gap-4">
                        <?php foreach ($serverResults as $server): ?>
                            <div class="card-white p-4 flex items-center gap-3">
                                <img src="img/server-icons/<?= $server['serverImage'] ?>" class="w-10 h-10 rounded-lg shadow-sm">
                                <div>
                                    <h4 class="font-bold text-sm"><?= $server['serverName'] ?></h4>
                                    <code class="text-[10px] text-gray-400"><?= $server['serverIP'] ?></code>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-400 italic">ไม่พบเซิร์ฟเวอร์ที่เกี่ยวข้อง</p>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <?php include_once("components/footer.php"); ?>
    <script src="js/script.js"></script>
</body>
</html>