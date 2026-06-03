<?php
session_start();
require_once("system/conn.php");
require_once("system/config.php");
require_once("system/reportSystem.php");

// ดึงข้อความแจ้งผลจาก session (กรณีเพิ่ง redirect กลับมาหลังส่งฟอร์ม - PRG)
$message = $_SESSION['report_message'] ?? "";
$messageType = $_SESSION['report_message_type'] ?? "";
unset($_SESSION['report_message'], $_SESSION['report_message_type']);

// รายงานจากหน้า server-detail (?serverId=)
$reportServer = null;
$prefillServerId = (int) ($_GET['serverId'] ?? 0);
if ($prefillServerId > 0) {
    $reportServer = resolveReportServerId($conn, $prefillServerId);
}

// ตรวจสอบเมื่อมีการกดส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnSubmitReport'])) {
    // ตรวจ CSRF token กัน CSRF / bot
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        die('ตรวจพบการส่งข้อมูลที่ไม่ปลอดภัย (CSRF Token Mismatch)');
    }

    // ตรวจสอบ Cloudflare Turnstile กันบอท/สแปม
    $turnstileToken = $_POST['cf-turnstile-response'] ?? null;

    if (!verifyTurnstileToken($turnstileToken)) {
        $_SESSION['report_message'] = "ตรวจพบความผิดปกติ! กรุณายืนยันตัวตน (ฉันไม่ใช่บอท) ใหม่อีกครั้ง";
        $_SESSION['report_message_type'] = "error";
        $redirectServerId = (int) ($_POST['serverId'] ?? 0);
        header('Location: ' . reportPageUrl($redirectServerId > 0 ? $redirectServerId : null));
        exit;
    }

    // ถ้าไม่ได้ล็อกอิน ให้ userId เป็น null (อนุญาตให้บุคคลทั่วไปแจ้งได้)
    $userId = $_SESSION['userId'] ?? null;

    // Rate limiting: จำกัดไม่เกิน 5 ครั้ง/ชั่วโมง ต่อ IP กันการสแปม
    $clientIp = getClientIp();
    $rateLimitMax = 5;
    $rateLimitMinutes = 60;
    if (countRecentReportsByIp($conn, $clientIp, $rateLimitMinutes) >= $rateLimitMax) {
        $_SESSION['report_message'] = "คุณส่งรายงานบ่อยเกินไป กรุณารอสักครู่แล้วลองใหม่อีกครั้งภายหลังครับ";
        $_SESSION['report_message_type'] = "error";
        $redirectServerId = (int) ($_POST['serverId'] ?? 0);
        header('Location: ' . reportPageUrl($redirectServerId > 0 ? $redirectServerId : null));
        exit;
    }

    // เก็บค่าดิบ (ไม่ต้อง htmlspecialchars ตอนนี้ - ไปทำตอนแสดงผลแทน เพื่อเลี่ยง double-encoding)
    $topic  = trim($_POST['topic'] ?? '');
    $type   = $_POST['type'] ?? '';
    $detail = trim($_POST['detail'] ?? '');
    $imageFile = $_FILES['reportImage'] ?? null;
    $postServerId = (int) ($_POST['serverId'] ?? 0);
    $serverIdForDb = null;

    if ($postServerId > 0) {
        $validatedServer = resolveReportServerId($conn, $postServerId);
        if ($validatedServer) {
            $serverIdForDb = (int) $validatedServer['serverId'];
        }
    }

    $imageName = null;
    $error = "";

    // ตรวจสอบฝั่งเซิร์ฟเวอร์
    $allowedTypes = ['bug', 'suggestion', 'user_report', 'other'];
    if ($topic === '' || $detail === '') {
        $error = "กรุณากรอกหัวข้อและรายละเอียดให้ครบถ้วน";
    } elseif (!in_array($type, $allowedTypes, true)) {
        $error = "ประเภทการแจ้งไม่ถูกต้อง";
    } elseif ($postServerId > 0 && $serverIdForDb === null) {
        $error = "ไม่พบเซิร์ฟเวอร์ที่เลือก หรือเซิร์ฟเวอร์นี้ไม่อยู่ในระบบแล้ว";
    }

    // จัดการอัปโหลดรูปภาพ (ถ้ามีการแนบมา)
    if ($error === "" && $imageFile && $imageFile['error'] === 0) {
        $maxSize = 3 * 1024 * 1024; // 3MB
        $ext = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed)) {
            $error = "รองรับเฉพาะไฟล์รูปภาพ (jpg, jpeg, png, webp) เท่านั้น";
        } elseif ($imageFile['size'] > $maxSize) {
            $error = "ขนาดรูปภาพต้องไม่เกิน 3MB";
        } elseif (getimagesize($imageFile['tmp_name']) === false) {
            // ยืนยันว่าเป็นไฟล์รูปภาพจริง ไม่ใช่ไฟล์อื่นที่เปลี่ยนนามสกุลมา
            $error = "ไฟล์ที่อัปโหลดไม่ใช่รูปภาพที่ถูกต้อง";
        } else {
            // สร้างโฟลเดอร์ img/reports/ ถ้ายังไม่มี
            $uploadDir = __DIR__ . "/img/reports/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $newName = "report_" . time() . "_" . uniqid() . "." . $ext;
            if (move_uploaded_file($imageFile['tmp_name'], $uploadDir . $newName)) {
                $imageName = $newName;
            }
        }
    }

    // บันทึกลงฐานข้อมูล (ถ้าไม่มี error)
    if ($error === "") {
        if (addReport($conn, $userId, $topic, $type, $detail, $imageName, $clientIp, $serverIdForDb)) {
            $_SESSION['report_message'] = "ส่งรายงานปัญหาสำเร็จ! ขอบคุณที่ช่วยทำให้เว็บของเราดีขึ้นครับ";
            $_SESSION['report_message_type'] = "success";
        } else {
            $_SESSION['report_message'] = "เกิดข้อผิดพลาดในการส่งข้อมูล กรุณาลองใหม่อีกครั้ง";
            $_SESSION['report_message_type'] = "error";
        }
    } else {
        $_SESSION['report_message'] = $error;
        $_SESSION['report_message_type'] = "error";
    }

    // PRG: redirect กลับมาที่หน้าเดิม กันการส่งซ้ำตอนกดรีเฟรช
    $redirectServerId = (int) ($_POST['serverId'] ?? 0);
    if ($error === "" && isset($_SESSION['report_message_type']) && $_SESSION['report_message_type'] === 'success') {
        header('Location: ' . reportPageUrl());
    } else {
        header('Location: ' . reportPageUrl($redirectServerId > 0 ? $redirectServerId : null));
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once __DIR__ . '/components/favicon.php'; ?>
    <title>แจ้งปัญหาการใช้งาน | Zencrafterly</title>
    <link rel="stylesheet" href="<?= base_url('css/output.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>
<body class="bg-gray-50 text-gray-800">

    <?php include_once("components/header-navbar.php"); ?>

    <div class="max-w-3xl mx-auto px-4 py-12">
        <?php if (!empty($message)): ?>
            <div class="mb-6 p-4 rounded-xl font-bold flex items-center gap-3 <?= $messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                <i class="fa-solid <?= $messageType === 'success' ? 'fa-check-circle' : 'fa-triangle-exclamation' ?> text-xl"></i>
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="card-white p-6 md:p-8 rounded-3xl shadow-sm">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold mb-2"><i class="fa-solid fa-bug text-red-500 mr-2"></i>แจ้งปัญหาการใช้งาน</h1>
                <p class="text-gray-500">พบเจอบั๊ก หรือมีข้อเสนอแนะ แจ้งให้ทีมงานทราบได้เลยครับ</p>
            </div>

            <?php if ($reportServer): ?>
                <div class="mb-6 p-4 rounded-xl bg-orange-50 border border-orange-100 text-sm">
                    <p class="font-bold text-orange-800 mb-1">
                        <i class="fa-solid fa-server mr-1"></i> รายงานเกี่ยวกับเซิร์ฟเวอร์
                    </p>
                    <p class="text-orange-700">
                        <?= htmlspecialchars($reportServer['serverName']) ?>
                        <a href="<?= base_url('server/' . htmlspecialchars($reportServer['serverSlug'], ENT_QUOTES, 'UTF-8')) ?>" class="text-orange-600 underline ml-2 text-xs">ดูหน้าเซิร์ฟเวอร์</a>
                    </p>
                </div>
            <?php elseif ($prefillServerId > 0): ?>
                <div class="mb-6 p-4 rounded-xl bg-yellow-50 border border-yellow-100 text-sm text-yellow-800">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> ไม่พบเซิร์ฟเวอร์ที่เลือก — กรุณาเข้ารายงานจากหน้ารายละเอียดเซิร์ฟเวอร์อีกครั้ง
                </div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data" class="space-y-5">
                <?= csrfField() ?>
                <?php if ($reportServer): ?>
                    <input type="hidden" name="serverId" value="<?= (int) $reportServer['serverId'] ?>">
                <?php endif; ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">หัวข้อเรื่อง</label>
                        <input type="text" name="topic" required
                               placeholder="<?= $reportServer ? 'เช่น เซิร์ฟโฆษณาเกินจริง, เนื้อหาไม่เหมาะสม...' : 'เช่น ปุ่มกดไม่ได้, อัปโหลดรูปไม่ขึ้น...' ?>" 
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">ประเภท</label>
                        <select name="type" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white">
                            <option value="bug">🐛 พบบั๊ก / ข้อผิดพลาด</option>
                            <option value="suggestion">💡 เสนอแนะฟีเจอร์ใหม่</option>
                            <option value="user_report"<?= $reportServer ? ' selected' : '' ?>>⚠️ รีพอร์ตผู้ใช้ / เซิร์ฟเวอร์ทำผิดกฎ</option>
                            <option value="other">💬 อื่นๆ</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">รายละเอียด</label>
                    <textarea name="detail" rows="5" required
                              placeholder="<?= $reportServer ? 'อธิบายพฤติกรรมหรือปัญหาของเซิร์ฟเวอร์นี้ให้ชัดเจน (วันที่ เวลา หลักฐาน ฯลฯ)...' : 'อธิบายปัญหาที่คุณพบเจออย่างละเอียด เพื่อให้ทีมงานแก้ไขได้ตรงจุดที่สุดครับ...' ?>" 
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition resize-none"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">รูปภาพประกอบ (ถ้ามี) <span class="font-normal text-gray-400">— ไม่เกิน 3MB</span></label>
                    <input type="file" name="reportImage" accept="image/*" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-gray-50 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <div class="flex justify-center pt-2">
                    <div class="cf-turnstile" data-sitekey="<?= htmlspecialchars(TURNSTILE_SITE_KEY, ENT_QUOTES, 'UTF-8') ?>" data-theme="light"></div>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <button type="submit" name="btnSubmitReport" class="w-full py-4 bg-gray-900 text-white font-bold rounded-xl hover:bg-black transition shadow-lg flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> ส่งรายงานให้ทีมงาน
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php include_once("components/footer.php"); ?>
</body>
</html>