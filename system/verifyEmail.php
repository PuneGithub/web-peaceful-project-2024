<?php
session_start();
require_once 'conn.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    try {
        //ตรวจสอบ token ในฐานข้อมูล
        $stmt = $conn->prepare("SELECT * FROM users WHERE verifyEmail = :token AND verifyStatus = 'unverified'");
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $updateStmt = $conn->prepare("UPDATE users SET verifyStatus = 'verified' , verifyEmail = NULL WHERE verifyEmail = :token");
            $updateStmt->bindParam(':token', $token);
            $updateStmt->execute();

            $_SESSION['success'] = "Email verification successful! You can now log in.";
            header("Location: verifySuccessful.php");
            exit;
        } else {
            $_SESSION['error'] = "Invalid or expired token.";
            header("Location: ../index.php");
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: ../index.php");
        exit;
    }
} else {
    $_SESSION['error'] = "No token provided.";
    header("Location: ../index.php");
    exit;
}
