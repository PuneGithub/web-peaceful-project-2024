<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once("conn.php");

function forgotPassword($email)
{
    global $conn;

    //ตรวจสอบ email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        return "The registered email address was not found.";
    }

    //สร้าง token สำหรับ reset password
    $resetToken = bin2hex(random_bytes(50));

    $stmt = $conn->prepare("UPDATE users SET resetCode = :resetCode WHERE email = :email");
    $stmt->bindParam(":resetCode", $resetToken);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    //ส่ง email reset password
    require_once 'PHPMailer/Exception.php';
    require_once 'PHPMailer/PHPMailer.php';
    require_once 'PHPMailer/SMTP.php';

    //SMTP Setting
    $mail = new PHPMailer(true);
    $mail->CharSet = "utf-8";
    $mail->isSMTP();
    $mail->Host = "smtp.mailgun.org";
    $mail->SMTPAuth = true;
    $mail->Username = "system@support.peaceful-network.com"; // email
    $mail->Password = "3735e6a4cbb335377053775dfe8da14c-c02fd0ba-3dc50d2e";
    $mail->Port = 587;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    //Email Setting
    $mail->setFrom('system@support.peaceful-network.com', 'Zencrafterly'); //ชื่อผู้ส่ง
    $mail->addAddress($email); // send to email
    $mail->addReplyTo('system@support.peaceful-network.com', 'Support');

    //ส่ง ยืนยันอีเมล์
    $message_subject = "Zencrafterly Reset your password";
    $mail->Subject = $message_subject;

    $mail->isHTML(true);
    $resetLink = "http://localhost/web_peaceful_project_2024/system/resetPassword.php?token=" . $resetToken;
    $mail->Body = '
             <h1>คุณได้ทำการกดลืมรหัสผ่าน</p></h1>
             <p>กรุณาคลิกลิงก์เพื่อรีเซ็ตรหัสผ่านของคุณ: <a href="' . $resetLink . '">คลิกที่นี่</a></p>
             <p>หากคุณไม่ได้กดลืมรหัสผ่าน กรุณาละเว้นข้อความนี้</p>
             <p>จาก <b>Zencrafterly</b></p>
             ';
    $mail->AltBody = 'กรุณาคลิกลิงก์นี้เพื่อรีเซ็ตรหัสผ่านของคุณ: ' . $resetLink;

    if ($mail->send()) {
        return true;
    } else {
        return "ไม่สามารถส่งอีเมล์ได้: " . $mail->ErrorInfo;
    }
}
