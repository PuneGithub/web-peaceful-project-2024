<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once("conn.php");


function signup($username, $password, $email)
{
    global $conn;

    if (strlen($password) < 6) {
        return "Password must have at least 6 characters.";
    }

    if (!preg_match('/[a-zA-Z]/', $password)) {
        return "Password must contain at least on letter (a-z or A-Z)";
    }

    //Check Email
    $checkStmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $checkStmt->bindParam(':email', $email);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        return "This email is already registered.";
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
        $mail->Username = "peaceful-network@koonpune.com"; // email
        $mail->Password = "3158ec4238dd459ac9bcb2d2a928e6bd-79295dd0-6f16159b";
        $mail->Port = 587;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        //Email Setting
        $mail->setFrom('peaceful-network@koonpune.com', 'Peaceful Network'); //ชื่อผู้ส่ง
        $mail->addAddress($email); // send to email
        $mail->addReplyTo('peaceful-network@koonpune.com', 'Support');


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

                echo "<div class='alert-green'>Login successful! Redirecting...</div>";

                header('refresh: 2; url=../index.php');
                exit;
            } else {
                return "Login failed: Invalid Password.";
            }
        } else {
            return "Login failed: Invalid username or password.";
        }
    } catch (Throwable $th) {
        return "เกิดข้อผิดพลาด:" . $th->getMessage();
    }
}

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
    $mail->Username = "peaceful-network@koonpune.com"; // email
    $mail->Password = "3158ec4238dd459ac9bcb2d2a928e6bd-79295dd0-6f16159b";
    $mail->Port = 587;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    //Email Setting
    $mail->setFrom('peaceful-network@koonpune.com', 'Peaceful Network'); //ชื่อผู้ส่ง
    $mail->addAddress($email); // send to email
    $mail->addReplyTo('peaceful-network@koonpune.com', 'Support');

    //ส่ง ยืนยันอีเมล์
    $message_subject = "Peaceful Network Email Verification";
    $mail->Subject = $message_subject;

    $mail->isHTML(true);
    $resetLink = "http://localhost/web_peaceful_project_2024/system/resetPassword.php?token=" . $resetToken;
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
}
