<?php
session_start();
require_once 'conn.php';

// 1. เพิ่มการเช็ค !empty เพื่อให้ชัวร์ว่ามีค่าส่งมาจริงๆ
if (isset($_GET['token']) && !empty($_GET['token'])) {
    
    // ตัดช่องว่างหน้า-หลังเผื่อ User ก๊อปปี้ลิงก์มาผิด
    $token = trim($_GET['token']);

    try {
        // 2. เปลี่ยน SELECT * เป็น SELECT userId (ลดภาระ Database)
        $stmt = $conn->prepare("SELECT userId FROM users WHERE verifyEmail = :token AND verifyStatus = 'unverified'");
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $user = $stmt->fetch(PDO::FETCH_ASSOC); 
            $userId = $user['userId'];

            $updateStmt = $conn->prepare("UPDATE users SET verifyStatus = 'verified', verifyEmail = NULL WHERE verifyEmail = :token");
            $updateStmt->bindParam(':token', $token);
            $updateStmt->execute();

            // 🚩 ----------------- ส่วนที่ต้องเพิ่ม ----------------- 🚩
            // อัปเดตสถานะใน SESSION ทันที เพื่อไม่ให้ User ต้อง Logout แล้วเข้าใหม่
            // (ดักไว้ 3 รูปแบบยอดฮิตที่นักพัฒนามักใช้ตั้งชื่อ Session)
            if (isset($_SESSION['userId']) && $_SESSION['userId'] == $userId) {
                $_SESSION['verifyStatus'] = 'verified';
            } elseif (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $userId) {
                $_SESSION['verifyStatus'] = 'verified';
            } elseif (isset($_SESSION['user']['userId']) && $_SESSION['user']['userId'] == $userId) {
                $_SESSION['user']['verifyStatus'] = 'verified';
            }
            // 🚩 ------------------------------------------------ 🚩

            $_SESSION['success'] = "Email verification successful! You can now log in.";
            // ถ้าหน้า verifySuccessful.php มีการเรียกใช้ config.php เราอาจจะเปลี่ยนไปใช้ header("Location: " . base_url('account/login.php')) แทนได้
            header("Location: verifySuccessful.php"); 
            exit;
        } else {
            $_SESSION['error'] = "Invalid or expired token.";
            header("Location: ../index.php");
            exit;
        }
    } catch (PDOException $e) {
        // 3. 🚩 เก็บ Error ไว้ใน Log หลังบ้านแทนการโชว์ให้ User เห็น
        error_log("Verify Email Error: " . $e->getMessage());
        
        // แสดงข้อความทั่วๆ ไปให้ User เห็น
        $_SESSION['error'] = "System error occurred. Please try again later.";
        header("Location: ../index.php");
        exit;
    }
} else {
    $_SESSION['error'] = "No token provided.";
    header("Location: ../index.php");
    exit;
}