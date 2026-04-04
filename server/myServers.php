<?php

//debug
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();
require_once("../system/conn.php");
require_once("../system/config.php");
require_once("../system/serverSystem.php");

// 1. เช็คว่าล็อกอินหรือยัง ถ้ายังให้เด้งไปหน้า login
if (!isset($_SESSION['userId'])) {
    header("Location: ../account/login.php");
    exit;
}

$userId = $_SESSION['userId'];

// 2. จัดการการลบเซิร์ฟเวอร์
$msg = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_serverId'])) {
    $deleteId = $_POST['delete_serverId'];
    
    // Security Check
    $stmt = $conn->prepare("DELETE FROM servers WHERE serverId = :serverId AND userId = :userId");
    if ($stmt->execute([':serverId' => $deleteId, ':userId' => $userId])) {
        $msg = "<div class='alert-green mb-4'><i class='fa-solid fa-check'></i> ลบเซิร์ฟเวอร์เรียบร้อยแล้ว</div>";
    } else {
        $msg = "<div class='alert-danger mb-4'><i class='fa-solid fa-triangle-exclamation'></i> เกิดข้อผิดพลาดในการลบ</div>";
    }
}

// 3. ดึงข้อมูลเซิร์ฟเวอร์ทั้งหมดของ User คนนี้
$myServers = fetchUserServers($conn, $userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เซิร์ฟเวอร์ของฉัน - Zencrafterly</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="../css/output.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include_once("../components/header-navbar.php"); ?>
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="card-white">
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                        <i class="fa-solid fa-server text-blue-600 mr-3"></i> เซิร์ฟเวอร์ของฉัน
                    </h1>
                    <p class="text-gray-500 mt-2">จัดการ แก้ไข และดูสถานะเซิร์ฟเวอร์ที่คุณเพิ่มไว้ในระบบ</p>
                </div>
                
                <a href="addServer.php" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold transition shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> เพิ่มเซิร์ฟเวอร์ใหม่
                </a>
            </div>
    
            <?php if ($msg) echo $msg; ?>
    
            <?php if (!empty($myServers)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($myServers as $server): ?>
                        <div class="card-white p-5 flex flex-col relative overflow-hidden group">
                            
                            <div class="absolute top-4 right-4">
                                <?php
                                    $statusConf = [
                                        'pending' => ['bg-yellow-100 text-yellow-600', 'รอตรวจสอบ', 'fa-clock'],
                                        'approved' => ['bg-green-100 text-green-600', 'ออนไลน์', 'fa-check-circle'],
                                        'rejected' => ['bg-red-100 text-red-600', 'ถูกปฏิเสธ', 'fa-times-circle']
                                    ];
                                    $s = $statusConf[$server['status']] ?? $statusConf['pending'];
                                ?>
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold <?= $s[0] ?> flex items-center gap-1 shadow-sm">
                                    <i class="fa-solid <?= $s[2] ?>"></i> <?= $s[1] ?>
                                </span>
                            </div>
    
                            <div class="flex gap-4 items-center mb-4 mt-2">
                                <img src="../img/server-icons/<?= $server['serverImage'] ?: 'default_server.webp' ?>" 
                                     class="w-16 h-16 rounded-xl object-cover shadow-sm border border-gray-100"
                                     onerror="this.src='../img/default_server.webp'">
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg line-clamp-1"><?= htmlspecialchars($server['serverName']) ?></h3>
                                    <p class="text-xs text-gray-500 font-mono mt-1"><?= htmlspecialchars($server['serverIP']) ?></p>
                                </div>
                            </div>
    
                            <div class="grid grid-cols-2 gap-2 mb-4">
                                <div class="bg-gray-50 p-2 rounded-lg text-center border border-gray-100">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">หมวดหมู่</p>
                                    <p class="text-sm font-bold text-gray-700 truncate"><?= htmlspecialchars($server['serverCategory']) ?></p>
                                </div>
                                <div class="bg-blue-50 p-2 rounded-lg text-center border border-blue-100">
                                    <p class="text-[10px] text-blue-400 font-bold uppercase">คะแนนโหวต</p>
                                    <p class="text-sm font-bold text-blue-700"><?= number_format($server['votes']) ?> <i class="fa-solid fa-heart text-xs"></i></p>
                                </div>
                            </div>
    
                            <div class="mt-auto flex gap-2 pt-2 border-t border-gray-100">
                                <a href="editServer.php?id=<?= $server['serverId'] ?>" 
                                   class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold py-2 rounded-lg transition text-center">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> แก้ไข
                                </a>
                                
                                <form action="" method="post" class="flex-1" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบเซิร์ฟเวอร์นี้? การกระทำนี้ไม่สามารถกู้คืนได้');">
                                    <input type="hidden" name="delete_serverId" value="<?= $server['serverId'] ?>">
                                    <button type="submit" class="w-full bg-red-50 hover:bg-red-500 hover:text-white text-red-500 text-xs font-bold py-2 rounded-lg transition">
                                        <i class="fa-solid fa-trash-can mr-1"></i> ลบ
                                    </button>
                                </form>
                            </div>
    
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-20 bg-white rounded-3xl shadow-sm border border-dashed border-gray-200 mt-6">
                    <i class="fa-solid fa-box-open text-6xl text-gray-200 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">คุณยังไม่มีเซิร์ฟเวอร์</h3>
                    <p class="text-gray-500 mb-6">เริ่มต้นสร้างชุมชนของคุณโดยการเพิ่มเซิร์ฟเวอร์แรกเข้าระบบสิ!</p>
                    <a href="addServer.php" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold shadow-sm hover:bg-blue-700 transition inline-flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> เพิ่มเซิร์ฟเวอร์เลย
                    </a>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <?php include_once("../components/footer.php"); ?>
    
    <script src="<?php echo base_url('/js/script.js'); ?>"></script>
</body>
</html>