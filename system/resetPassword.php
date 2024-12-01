<?php
session_start();
require_once 'conn.php';

//ตรวจสอบ Token
if (!isset($_GET['token']) || empty($_GET['token'])) {
    die("Invalid or missing reset token.");
}

$token = htmlspecialchars($_GET['token']);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    
    $newPassword = $_POST['newPassword'];
    $conPassword = $_POST['conPassword'];

    if (strlen($newPassword) < 6) {
        $error = "Password must have at least 6 characters.";
    } elseif (!preg_match('/[a-zA-Z]/', $newPassword)) {
        $error = "Password must contain at least on letter (a-z or A-Z)";
    } elseif ($newPassword !== $conPassword) {
        $error = "Passwords do not match.";
    } else {

        $stmt = $conn->prepare("SELECT * FROM users WHERE resetCode = :resetCode");
        $stmt->bindParam(":resetCode", $token);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $hashPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStmt = $conn->prepare("UPDATE users SET password = :password, resetCode = NULL WHERE resetCode = :resetCode");
            $updateStmt->bindParam(":password", $hashPassword);
            $updateStmt->bindParam(":resetCode", $token);

            if ($updateStmt->execute()) {
                $success = "Password has been updated successfully.";
                header("refresh:2; url=../account/login.php");
                exit;
            } else {
                $error = "Failed to update password. Please try again.";
            }
        } else {
            $error = "Invalid or expired reset token.";
        }

    }

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/output.css">
    <title>Reset Password</title>
</head>
<script src="js/script.js"></script>

<body style="background-image: url('../img/bg.webp');">

    <?php include_once("../components/header-navbar.php"); ?>

    <div class="flex items-center justify-center h-screen">
        <div class="card-white w-full max-w-md">
            <h2 class="text-xl font-semibold text-center">Reset Password</h2>
            <?php 
            if (isset($error)) {
                echo "<div class='alert-danger'>" . htmlspecialchars($error) . "</div>";
            }
            if (isset($success)) {
                echo "<div class='alert-green'>" . htmlspecialchars($success) . "</div>";
            }
            ?>
            <form action="" method="post" class="space-y-4">
                <div>
                    <label for="newPassword" class="block text-sm font-medium">New Password</label>
                    <input type="password" name="newPassword" class="input-form" placeholder="Enter new password" required>
                </div>
                <div>
                    <label for="conPassword" class="block text-sm font-medium">Confirm Password</label>
                    <input type="password" name="conPassword" class="input-form" placeholder="Enter confirm password" required>
                </div>
                <div>
                    <input type="submit" class="btn-green-500" value="UPDATE">
                </div>
            </form>
        </div>
    </div>
</body>

</html>