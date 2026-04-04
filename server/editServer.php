<?php
session_start();
require_once("../system/config.php");
require_once("../system/conn.php");
require_once("../system/serverSystem.php");

// 1. ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['userId'])) {
    header("Location: ../account/login.php");
    exit;
}

$userId = $_SESSION['userId'];
$serverId = $_GET['id'] ?? null;
$msg = null;

// 2. ดึงข้อมูลเดิมมาแสดง และเช็คสิทธิ์เจ้าของ
$stmt = $conn->prepare("SELECT * FROM servers WHERE serverId = :sid AND userId = :uid");
$stmt->execute([':sid' => $serverId, ':uid' => $userId]);
$server = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$server) {
    die("ไม่พบข้อมูลเซิร์ฟเวอร์ หรือคุณไม่มีสิทธิ์แก้ไขรายการนี้");
}

// 3. Logic การอัปเดตข้อมูล (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = htmlspecialchars($_POST['serverName']);
    $newIP = htmlspecialchars($_POST['serverIP']);
    $newCategory = $_POST['serverCategory'];
    
    // อัปเดตข้อมูลลงฐานข้อมูล
    $updateStmt = $conn->prepare("UPDATE servers SET serverName = :name, serverIP = :ip, serverCategory = :cat, status = 'pending', updatedAt = NOW() WHERE serverId = :sid AND userId = :uid");
    
    if ($updateStmt->execute([':name' => $newName, ':ip' => $newIP, ':cat' => $newCategory, ':sid' => $serverId, ':uid' => $userId])) {
        $msg = "<div class='alert-green mb-4'>อัปเดตข้อมูลสำเร็จ! (ระบบจะรอแอดมินตรวจสอบอีกครั้ง)</div>";
        // ดึงข้อมูลใหม่มาแสดง
        header("refresh:2; url=myServers.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขเซิร์ฟเวอร์ - Zencrafterly</title>
    <link rel="stylesheet" href="../css/output.css">
</head>
<body class="bg-gray-50">
    <?php include_once("../components/header-navbar.php"); ?>

    <div class="max-w-2xl mx-auto px-4 py-10">
        <div class="card-white p-8">
            <h2 class="text-2xl font-bold mb-6">แก้ไขข้อมูล: <?= htmlspecialchars($server['serverName']) ?></h2>
            
            <?php if ($msg) echo $msg; ?>

            <form action="" method="post" class="space-y-5">
                <div>
                    <label class="block text-sm font-bold mb-2">ชื่อเซิร์ฟเวอร์</label>
                    <input type="text" name="serverName" class="input-form w-full" value="<?= htmlspecialchars($server['serverName']) ?>" required>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2">IP Address / Domain</label>
                    <input type="text" name="serverIP" class="input-form w-full" value="<?= htmlspecialchars($server['serverIP']) ?>" required>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2">หมวดหมู่</label>
                    <select name="serverCategory" class="input-form w-full">
                        <option value="Survival" <?= $server['serverCategory'] == 'Survival' ? 'selected' : '' ?>>Survival</option>
                        <option value="Minigame" <?= $server['serverCategory'] == 'Minigame' ? 'selected' : '' ?>>Minigame</option>
                    </select>
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="submit" class="btn-blue-500 flex-1">บันทึกการเปลี่ยนแปลง</button>
                    <a href="myServers.php" class="btn-gray-500 text-center">ยกเลิก</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>