<?php
session_start();
require_once(__DIR__ . '/../system/conn.php');
require_once(__DIR__ . '/../system/config.php');
require_once(__DIR__ . '/../system/serverSystem.php');

if (!isset($_SESSION['userId'])) {
    $_SESSION['message'] = 'โปรดเข้าสู่ระบบก่อนแก้ไขเซิร์ฟเวอร์';
    header('Location: ' . base_url('account/login'));
    exit();
}

$userId = $_SESSION['userId'];
$serverId = (int) ($_GET['id'] ?? 0);
$message = '';

if ($serverId <= 0) {
    header('Location: ' . base_url('server/myServers.php'));
    exit();
}

$server = fetchServerForOwner($conn, $serverId, $userId);

if (!$server) {
    $_SESSION['flash_message'] = "<div class='bg-red-100 text-red-700 p-3 rounded mb-4'>ไม่พบข้อมูลเซิร์ฟเวอร์ หรือคุณไม่มีสิทธิ์แก้ไขรายการนี้</div>";
    header('Location: ' . base_url('server/myServers.php'));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnEditServer'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $message = "<div class='bg-red-100 text-red-700 p-3 rounded mb-4'>คำขอไม่ถูกต้องหรือหมดอายุ กรุณาลองส่งฟอร์มใหม่อีกครั้ง</div>";
    } else {
        $serverName = trim($_POST['serverName']);
        $serverIP = trim($_POST['serverIP']);
        $serverVersion = trim($_POST['serverVersion']);
        $serverCategory = trim($_POST['serverCategory']);
        $serverDescription = $_POST['serverDescription'];
        $imageFile = $_FILES['serverImage'] ?? null;

        $imageName = $server['serverImage'] ?: 'default_server.webp';
        $uploadError = null;

        if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
            $maxSize = 3 * 1024 * 1024;
            $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
            $allowedMime = ['image/jpeg', 'image/png', 'image/webp'];

            $ext = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $imageFile['tmp_name']);
            finfo_close($finfo);

            if ($imageFile['size'] > $maxSize) {
                $uploadError = 'ไฟล์รูปภาพมีขนาดเกิน 3MB กรุณาเลือกไฟล์ที่เล็กลง';
            } elseif (!in_array($ext, $allowedExt, true) || !in_array($mime, $allowedMime, true)) {
                $uploadError = 'รองรับเฉพาะไฟล์รูปภาพ jpg, jpeg, png และ webp เท่านั้น';
            } else {
                $newName = 'server_' . time() . '.' . $ext;
                $uploadPath = __DIR__ . '/../img/server-icons/' . $newName;

                if (move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
                    $oldImage = $server['serverImage'];
                    if (!empty($oldImage) && $oldImage !== 'default_server.webp') {
                        $oldPath = __DIR__ . '/../img/server-icons/' . $oldImage;
                        if (is_file($oldPath)) {
                            unlink($oldPath);
                        }
                    }
                    $imageName = $newName;
                } else {
                    $uploadError = 'ไม่สามารถอัปโหลดไฟล์รูปภาพได้ กรุณาลองใหม่อีกครั้ง';
                }
            }
        } elseif ($imageFile && $imageFile['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadError = 'เกิดข้อผิดพลาดในการอัปโหลดไฟล์รูปภาพ กรุณาลองใหม่อีกครั้ง';
        }

        if ($uploadError !== null) {
            $message = "<div class='bg-red-100 text-red-700 p-3 rounded mb-4'>" . htmlspecialchars($uploadError) . '</div>';
        } else {
            $result = updateServer(
                $conn,
                $serverId,
                $userId,
                $serverName,
                $serverIP,
                $serverVersion,
                $serverCategory,
                $serverDescription,
                $imageName
            );

            if ($result === true) {
                $_SESSION['flash_message'] = "<div class='bg-green-100 text-green-700 p-3 rounded mb-4'>อัปเดตข้อมูลสำเร็จ! ระบบจะรอแอดมินตรวจสอบอีกครั้งก่อนเผยแพร่บนหน้าเว็บ</div>";
                header('Location: ' . base_url('server/myServers.php'));
                exit();
            } elseif ($result === 'IP_DUPLICATE') {
                $message = "<div class='bg-yellow-100 text-yellow-700 p-3 rounded mb-4'>IP นี้ถูกลงทะเบียนไว้แล้ว หรือกำลังรอการตรวจสอบจากแอดมิน</div>";
            } elseif ($result === 'INVALID_CATEGORY') {
                $message = "<div class='bg-red-100 text-red-700 p-3 rounded mb-4'>หมวดหมู่ที่เลือกไม่ถูกต้อง</div>";
            } else {
                $message = "<div class='bg-red-100 text-red-700 p-3 rounded mb-4'>เกิดข้อผิดพลาดบางอย่างในการบันทึกข้อมูล กรุณาลองใหม่อีกครั้ง</div>";
            }
        }

        // รีเฟรชข้อมูลในฟอร์มหลัง POST ที่ไม่ redirect
        $server = fetchServerForOwner($conn, $serverId, $userId) ?: $server;
    }
}

$currentIcon = $server['serverImage'] ?: 'default_server.webp';
$iconUrl = base_url('img/server-icons/' . $currentIcon);
$defaultIcon = base_url('img/server-icons/default_server.webp');
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once __DIR__ . '/../components/favicon.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/output.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>แก้ไขเซิร์ฟเวอร์ - Zencrafterly</title>
</head>

<body>
    <?php include_once(__DIR__ . '/../components/header-navbar.php'); ?>

    <div class="max-w-4xl mx-auto py-10 px-4">
        <div class="card-white p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">
                <i class="fa-solid fa-pen-to-square mr-2 text-blue-500"></i>แก้ไขเซิร์ฟเวอร์
            </h1>
            <p class="text-sm text-gray-500 mb-6">
                สถานะปัจจุบัน:
                <span class="font-bold uppercase"><?= htmlspecialchars($server['status']) ?></span>
                — หลังบันทึกจะเปลี่ยนเป็น <span class="text-yellow-600 font-bold">pending</span> รอแอดมินตรวจสอบ
            </p>

            <?= $message ?>

            <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                <?= csrfField() ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ชื่อเซิร์ฟเวอร์</label>
                        <input type="text" name="serverName" class="input-form" value="<?= htmlspecialchars($server['serverName']) ?>" placeholder="Zencrafterly Survival" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">IP เซิร์ฟเวอร์</label>
                        <input type="text" name="serverIP" class="input-form" value="<?= htmlspecialchars($server['serverIP']) ?>" placeholder="play.zencrafterly.com" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">เวอร์ชั่น (Version)</label>
                        <input type="text" name="serverVersion" class="input-form" value="<?= htmlspecialchars($server['serverVersion'] ?? '') ?>" placeholder="1.20.1 - 1.21" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">หมวดหมู่</label>
                        <select name="serverCategory" class="input-form" required>
                            <?php foreach (getServerCategories() as $catValue => $catLabel): ?>
                                <option value="<?= htmlspecialchars($catValue) ?>" <?= $server['serverCategory'] === $catValue ? 'selected' : '' ?>><?= htmlspecialchars($catLabel) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">คำอธิบายเซิร์ฟเวอร์</label>
                    <textarea name="serverDescription" rows="5" class="input-form" placeholder="บอกจุดเด่นของเซิร์ฟเวอร์คุณที่นี่..."><?= htmlspecialchars($server['serverDescription'] ?? '') ?></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">รูปภาพเซิร์ฟเวอร์ (Icon/Cover)</label>
                    <div class="flex items-center gap-4 mb-3">
                        <img src="<?= htmlspecialchars($iconUrl, ENT_QUOTES, 'UTF-8') ?>"
                             alt="Server icon"
                             class="w-16 h-16 rounded-xl object-cover border border-gray-200"
                             onerror="this.onerror=null; this.src='<?= htmlspecialchars($defaultIcon, ENT_QUOTES, 'UTF-8') ?>';">
                        <span class="text-xs text-gray-500">รูปปัจจุบัน — อัปโหลดใหม่เพื่อเปลี่ยน (ไม่เกิน 3MB)</span>
                    </div>
                    <input type="file" name="serverImage" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <button type="submit" name="btnEditServer" class="flex-1 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">
                        บันทึกการเปลี่ยนแปลง
                    </button>
                    <a href="<?= base_url('server/myServers.php') ?>" class="flex-1 py-3 text-center bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition">
                        ยกเลิก
                    </a>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg shadow-sm">
                    <div class="flex">
                        <div class="shrink-0">
                            <i class="fa-solid fa-circle-info text-blue-500 mt-1"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-blue-800">เพื่อให้ระบบแสดงสถานะ "ออนไลน์" ได้ถูกต้อง:</h3>
                            <div class="mt-2 text-xs text-blue-700 space-y-1">
                                <p>1. สำหรับ **Java Edition**: หากเวอร์ชันต่ำกว่า 1.7 กรุณาตั้งค่า <code>enable-query=true</code> ในไฟล์ server.properties</p>
                                <p>2. สำหรับ **Bedrock Edition**: กรุณาเปิด Query Port และเช็คว่า Firewall ไม่ได้บล็อกพอร์ตการเชื่อมต่อ</p>
                                <p>3. หากใช้ระบบป้องกัน (Anti-DDoS) บางเจ้า อาจทำให้ระบบดึงจำนวนผู้เล่นไม่ได้</p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php include_once(__DIR__ . '/../components/footer.php'); ?>
</body>

</html>
