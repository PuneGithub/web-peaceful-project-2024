<?php
session_start();
require_once(__DIR__ . "/../system/conn.php");
require_once(__DIR__ . "/../system/config.php");
require_once(__DIR__ . "/../system/serverSystem.php");

//เช็ค login
if (!isset($_SESSION['userId'])) {
    $_SESSION['message'] = "โปรดเข้าสู่ระบบก่อนเพิ่มเซิร์ฟเวอร์";
    header("Location: ../account/login");
    exit();
}

if (($_SESSION['verifyStatus'] ?? '') !== 'verified') {
    $_SESSION['flash_message'] = "<div class='alert-danger mb-4'><i class='fa-solid fa-envelope'></i> โปรดยืนยัน Email ก่อนเพิ่มเซิร์ฟเวอร์</div>";
    header('Location: ' . base_url('server/myServers.php'));
    exit();
}

$message = "";

// เมื่อมีการกดปุ่ม Add Server
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['btnAddServer'])) {

    // ตรวจสอบ CSRF token ก่อนประมวลผลใดๆ
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $message = "<div class='bg-red-100 text-red-700 p-3 rounded mb-4'>คำขอไม่ถูกต้องหรือหมดอายุ กรุณาลองส่งฟอร์มใหม่อีกครั้ง</div>";
    } else {
        $userId = $_SESSION['userId'];
        // เก็บข้อมูลดิบลงฐานข้อมูล แล้วค่อย escape ตอนแสดงผล (ป้องกัน double-escape)
        $serverName = trim($_POST['serverName']);
        $serverIP = trim($_POST['serverIP']);
        $serverVersion = trim($_POST['serverVersion']);
        $serverCategory = trim($_POST['serverCategory']);
        $serverDescription = $_POST['serverDescription'];
        $imageFile = $_FILES['serverImage'];

        $serverSlug = createSlug($serverName);

        $imageName = "default_server.webp";
        $uploadError = null;

        if (isset($imageFile) && $imageFile['error'] === UPLOAD_ERR_OK) {
            $maxSize = 3 * 1024 * 1024; // จำกัดขนาดไม่เกิน 3MB
            $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
            $allowedMime = ['image/jpeg', 'image/png', 'image/webp'];

            $ext = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));

            // ตรวจชนิดไฟล์จากเนื้อหาจริง ไม่เชื่อแค่นามสกุล
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $imageFile['tmp_name']);

            if ($imageFile['size'] > $maxSize) {
                $uploadError = "ไฟล์รูปภาพมีขนาดเกิน 3MB กรุณาเลือกไฟล์ที่เล็กลง";
            } elseif (!in_array($ext, $allowedExt, true) || !in_array($mime, $allowedMime, true)) {
                $uploadError = "รองรับเฉพาะไฟล์รูปภาพ jpg, jpeg, png และ webp เท่านั้น";
            } else {
                $newName = "server_" . time() . "." . $ext;
                $uploadPath = __DIR__ . "/../img/server-icons/" . $newName;

                if (move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
                    $imageName = $newName;
                } else {
                    $uploadError = "ไม่สามารถอัปโหลดไฟล์รูปภาพได้ กรุณาลองใหม่อีกครั้ง";
                }
            }
        } elseif (isset($imageFile) && $imageFile['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadError = "เกิดข้อผิดพลาดในการอัปโหลดไฟล์รูปภาพ กรุณาลองใหม่อีกครั้ง";
        }

        if ($uploadError !== null) {
            $message = "<div class='bg-red-100 text-red-700 p-3 rounded mb-4'>" . $uploadError . "</div>";
            $result = null;
        } else {
            $result = createServer($conn, $userId, $serverName, $serverSlug, $serverIP, $serverVersion, $serverCategory, $serverDescription, $imageName);
        }

        if ($result === "AUTO_APPROVED") {
            // กรณีสำเร็จ: สมาชิกเก่า (Approved ทันที) -> redirect กัน submit ซ้ำ (PRG)
            $_SESSION['flash_message'] = "<div class='bg-blue-100 text-blue-700 p-3 rounded mb-4'>ยินดีด้วย! บัญชีของคุณมีความน่าเชื่อถือสูง เซิร์ฟเวอร์ถูกอนุมัติทันที</div>";
            header("Location: " . base_url('server/myServers.php'));
            exit();
        } elseif ($result === true) {
            // กรณีสำเร็จ: สมาชิกใหม่ (รอตรวจสอบ) -> redirect กัน submit ซ้ำ (PRG)
            $_SESSION['flash_message'] = "<div class='bg-green-100 text-green-700 p-3 rounded mb-4'>ส่งข้อมูลสำเร็จ! โปรดรอแอดมินตรวจสอบ (ใช้เวลาไม่เกิน 24 ชม.)</div>";
            header("Location: " . base_url('server/myServers.php'));
            exit();
        } elseif ($result === "IP_DUPLICATE") {
            //  กรณีติดขัด: IP ซ้ำ
            $message = "<div class='bg-yellow-100 text-yellow-700 p-3 rounded mb-4'>IP นี้ถูกลงทะเบียนไว้แล้ว หรือกำลังรอการตรวจสอบจากแอดมิน</div>";
        } elseif ($result === 'UNVERIFIED_ACCOUNT') {
            $_SESSION['flash_message'] = "<div class='alert-danger mb-4'><i class='fa-solid fa-envelope'></i> โปรดยืนยัน Email ก่อนเพิ่มเซิร์ฟเวอร์</div>";
            header('Location: ' . base_url('server/myServers.php'));
            exit();
        } elseif ($result === false) {
            // กรณีผิดพลาด: เช่น Database พัง หรือไฟล์อัปโหลดมีปัญหา
            $message = "<div class='bg-red-100 text-red-700 p-3 rounded mb-4'>เกิดข้อผิดพลาดบางอย่างในการบันทึกข้อมูล กรุณาลองใหม่อีกครั้ง</div>";
        }
        // หมายเหตุ: ถ้า $result === null แปลว่าหยุดตั้งแต่ตอนตรวจไฟล์รูป ($message ถูกตั้งไว้แล้ว)
    }
}
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
    <title>Add Server</title>
</head>

<body>
    <!-- header -->
    <?php include_once(__DIR__ . '/../components/header-navbar.php'); ?>

    <div class="max-w-4xl mx-auto py-10 px-4">
        <div class="card-white p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6"><i class="fa-solid fa-plus-circle mr-2 text-blue-500"></i>เพิ่มรายชื่อเซิร์ฟเวอร์</h1>

            <?php echo $message; ?>

            <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                <?= csrfField() ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ชื่อเซิร์ฟเวอร์</label>
                        <input type="text" name="serverName" class="input-form" placeholder="Zencrafterly Survival" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">IP เซิร์ฟเวอร์</label>
                        <input type="text" name="serverIP" class="input-form" placeholder="play.zencrafterly.com" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">เวอร์ชั่น (Version)</label>
                        <input type="text" name="serverVersion" class="input-form" placeholder="1.20.1 - 1.21" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">หมวดหมู่</label>
                        <select name="serverCategory" class="input-form" required>
                            <?php foreach (getServerCategories() as $catValue => $catLabel): ?>
                                <option value="<?= htmlspecialchars($catValue) ?>"><?= htmlspecialchars($catLabel) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">คำอธิบายเซิร์ฟเวอร์</label>
                    <textarea name="serverDescription" rows="5" class="input-form" placeholder="บอกจุดเด่นของเซิร์ฟเวอร์คุณที่นี่..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">รูปภาพเซิร์ฟเวอร์ (Icon/Cover)</label>
                    <input type="file" name="serverImage" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <div class="pt-4">
                    <button type="submit" name="btnAddServer" class="w-full py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">
                        เพิ่มรายชื่อเซิร์ฟเวอร์
                    </button>
                </div>
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded-r-lg shadow-sm">
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

    <!-- footer -->
    <?php include_once(__DIR__ . '/../components/footer.php'); ?>
</body>

</html>