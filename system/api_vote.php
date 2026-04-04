<?php
session_start();
require_once("conn.php");
require_once("serverSystem.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

if (!isset($_SESSION['userId'])) {
    echo json_encode(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบก่อนโหวตนะคนดี']);
    exit;
}

$serverId = $_POST['serverId'] ?? null;
$userId = $_SESSION['userId'];

// ... ส่วนดึงค่า $serverId และ $userId ...

if ($serverId) {
    $result = voteServer($conn, $serverId, $userId);

    if ($result === true) {
        echo json_encode(['status' => 'success', 'message' => 'โหวตสำเร็จ! ขอบคุณที่สนับสนุน']);
    } elseif ($result === "UNVERIFIED_ACCOUNT") {
        // แจ้งเตือนเรื่องการยืนยันอีเมล
        echo json_encode([
            'status' => 'error', 
            'message' => 'คุณยังไม่ได้ยืนยันอีเมล กรุณายืนยันตัวตนก่อนร่วมกิจกรรมโหวตนะครับ'
        ]);
    } elseif ($result === "LOGIN_REQUIRED") {
        echo json_encode(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบก่อนโหวต']);
    } elseif (is_array($result) && $result['status'] === 'COOLDOWN') {
        echo json_encode([
            'status' => 'error', 
            'message' => "ใจเย็นๆ อีก {$result['remaining']} นาทีค่อยมาโหวตใหม่นะ"
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดบางอย่าง']);
    }
}
