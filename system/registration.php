<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once("conn.php");


function signup($username, $password, $email)
{
    global $conn;

    $secret = "6LeGT_IqAAAAANnMxxr_nj4q8oWF-sq80YYG4gSo";

    //if check reCAPTCHA v2
    $captcha = $_POST['g-recaptcha-response'];
    $verifiyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . '&response=' . $captcha);

    $responseData = json_decode($verifiyResponse);


    if (!isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response'])) {
        return "คุณไม่ได้กด reCAPTCHA !";
    }

    // ตรวจสอบว่า reCAPTCHA ยืนยันสำเร็จหรือไม่
    if (!$responseData || !$responseData->success) {
        return "ยืนยัน reCAPTCHA ไม่สำเร็จโปรดลองใหม่ !";
    }

    if (strlen($password) < 6) {
        return "รหัสผ่านต้องมีอย่างน้อย 6 ตัว";
    }

    if (!preg_match('/[a-zA-Z]/', $password)) {
        return "รหัสผ่านต้องประกอบด้วยตัวอักษรอย่างน้อยหนึ่งตัว (a-z หรือ A-Z)";
    }

    //Check Email & user
    $checkStmt = $conn->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
    $checkStmt->bindParam(':email', $email);
    $checkStmt->bindParam(':username', $username);
    $checkStmt->execute();

    $existingUser = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser['email'] === $email) {
        return "This email is already registered.";
    }

    if ($existingUser['username'] === $username) {
        return "This username is already registered.";
    }

    $hashPassword = password_hash($password, PASSWORD_DEFAULT);

    $token = bin2hex(random_bytes(50));

    $verifyStatus = "unverified";


    $date = date("Y-m-d");

    try {
        $stmt = $conn->prepare("INSERT INTO users (username, password, email, verifyEmail, createDate, verifyStatus) VALUES (:username, :password, :email, :verifyEmail, :createDate, :verifyStatus)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashPassword);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':verifyEmail', $token);
        $stmt->bindParam(':createDate', $date);
        $stmt->bindParam(':verifyStatus', $verifyStatus);
        $stmt->execute();

        //ส่ง Email ยืนยัน
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
        $mail->setFrom('system@support.peaceful-network.com', 'Peaceful Network'); //ชื่อผู้ส่ง
        $mail->addAddress($email); // send to email
        $mail->addReplyTo('system@support.peaceful-network.com', 'Support');


        $mail->addCustomHeader('X-Mailer', 'PHPMailer');

        //ส่ง ยืนยันอีเมล์
        $message_subject = "Peaceful Network Email Verification";
        $mail->Subject = $message_subject;

        $mail->isHTML(true);
        $verificationLink = "http://localhost/web_peaceful_project_2024/system/verifyEmail.php?token=" . $token;
        $mail->Body = '
         <h1>ยินดีต้อนรับ!</h1> <p>ขอบคุณที่สมัครใช้งาน <b>Peaceful Network</b>.</p>
         <p>กรุณาคลิกลิงก์เพื่อยืนยันอีเมลของคุณ: <a href="' . $verificationLink . '">คลิกที่นี่</a></p>
         <p>หากคุณไม่ได้ลงทะเบียนกับเรา กรุณาละเว้นข้อความนี้</p>
         <p>จาก <b>Peaceful Network</b></p>
         ';
        $mail->AltBody = 'กรุณาคลิกลิงก์นี้เพื่อยืนยันอีเมลของคุณ: ' . $verificationLink;

        if ($mail->send()) {
            return true;
        } else {
            return "ไม่สามารถส่งอีเมล์ได้: " . $mail->ErrorInfo;
        }
    } catch (Throwable $th) {
        return "เกิดข้อผิดพลาด: " . $th->getMessage();
    }
}

function login($identifier, $password)
{
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :identifier OR username = :identifier");
        $stmt->bindParam(":identifier", $identifier);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {

                //เก็บ Session
                $_SESSION['userId'] = $user['userId'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];

                echo "<div class='alert-green'>Login successful! Redirecting...</div>";

                header('refresh: 2; url=../index.php');
                exit;
            } else {
                return "การเข้าสู่ระบบล้มเหลว: รหัสผ่านไม่ถูกต้อง";
            }
        } else {
            return "การเข้าสู่ระบบล้มเหลว: ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
        }
    } catch (Throwable $th) {
        return "เกิดข้อผิดพลาด:" . $th->getMessage();
    }
}
