<?php
session_start();
require_once '../system/conn.php';
require_once("../system/config.php");
require_once("../system/registration.php");

$error = null;
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = $_POST['identifier'];
    $password = $_POST['password'];

    $result = login($identifier, $password);

    if ($result === true) {
        $success = true; // ตั้งค่าสถานะสำเร็จ
    } else {
        $error = $result; // เก็บข้อความ Error ไว้แสดงข้างล่าง
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once __DIR__ . '/../components/favicon.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/output.css">
    <title>Login | Zencrafterly</title>
</head>

<body style="background-image: url('<?= base_url('img/bg.webp'); ?>'); background-size: cover; background-position: center;">

    <?php include_once("../components/header-navbar.php"); ?>

    <div class="flex items-center justify-center h-screen">
        <div class="card-white w-full max-w-md">
            <h2 class="text-xl font-semibold text-center">LOGIN</h2>
            <?php
            if ($success) {
                echo "<div class='alert-green'><i class='fa-regular fa-circle-check'></i> Login successful! Redirecting...</div>";
                echo "<script>
                    setTimeout(function() {
                        window.location.href = '" . base_url() . "';
                    }, 2000);
                </script>";
                exit;
            } elseif ($error) {
                echo "<div class='alert-danger'><i class='fa-solid fa-triangle-exclamation'></i> $error</div>";
            }
            ?>
            <form action="" method="post" class="space-y-4">
                <div>
                    <label for="identifier" class="block text-sm font-medium">Email or Username</label>
                    <input type="text" name="identifier" class="input-form w-full" placeholder="Enter email or username" required>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium">Password</label>
                    <input type="password" name="password" class="input-form w-full" placeholder="Enter password" required>
                </div>
                <div>
                    <a href="forgot_password.php" class="text-blue-500 hover:underline text-sm">Forgot password?</a>
                </div>
                <div class="pt-2">
                    <input type="submit" class="btn-green-500 w-full cursor-pointer" value="LOGIN">
                </div>
            </form>
        </div>
    </div>

    <script src="<?= base_url('js/script.js'); ?>"></script>
</body>

</html>