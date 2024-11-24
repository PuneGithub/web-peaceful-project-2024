<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$mail = new PHPMailer(true);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $type = $_POST['type'];

    try {
        //SMTP Setting
        $mail->CharSet = "utf-8";
        $mail->isSMTP();
        $mail->Host = "smtp.mailgun.org";
        $mail->SMTPAuth = true;
        $mail->Username = "peaceful-network@koonpune.com"; // email
        $mail->Password = "3158ec4238dd459ac9bcb2d2a928e6bd-79295dd0-6f16159b";
        $mail->Port = 587;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        //Email Setting
        $mail->setFrom('peaceful-network@koonpune.com', 'Peaceful Network'); //ชื่อผู้ส่ง
        $mail->addAddress($email); // send to email
        $mail->addReplyTo('peaceful-network@koonpune.com', 'Support');


        $mail->addCustomHeader('X-Mailer', 'PHPMailer');

        if ($type === "reset") {
            //ลืม Password
            $message_subject = "รีเซ็ตรหัสผ่าน";
            $mail->Subject = $message_subject;

            $mail->isHTML(true);
            $mail->Body = '<h1>รีเซ็ตรหัสผ่าน</h1><p>คุณได้ทำการร้องขอการรีเซ็ตรหัสผ่าน. กรุณาคลิกลิงก์ด้านล่างเพื่อรีเซ็ตรหัสผ่านของคุณ.</p><a href="your_password_reset_link">คลิกที่นี่เพื่อรีเซ็ตรหัสผ่าน</a>';
            $mail->AltBody = 'คุณได้ทำการร้องขอการรีเซ็ตรหัสผ่าน. กรุณาคลิกลิงก์นี้เพื่อรีเซ็ตรหัสผ่านของคุณ: your_password_reset_link';
        }


        if ($mail->send()) {
            echo "ส่ง email แล้ว";
        }
    } catch (Exception $e) {
        echo "ส่ง email ไม่ผ่าน: " . $mail->ErrorInfo;
    }

    exit;
}
