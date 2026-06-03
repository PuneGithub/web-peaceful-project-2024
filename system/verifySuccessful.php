<?php
include_once "config.php";
session_start();

// ตรวจสอบ session ว่าได้รับการตั้งค่า และมีสถานะ success
if (isset($_SESSION['success'])) {
    // ล้าง session หลังจากใช้งาน
    unset($_SESSION['success']);
} else {
    // หากไม่มี session หรือไม่ใช่ success ให้เปลี่ยนเส้นทางไปยังหน้าหลัก
    header("Location: ../index.php");
    exit();
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
    <title>Peaceful Network</title>
</head>
<script src="js/script.js"></script>

<body style="background-image: url('../img/bg.webp');">

    <?php include_once("../components/header-navbar.php"); ?>

    <!-- header navbar -->
    <div class="flex items-center justify-center h-screen">
        <div class="card-white">
            <div class="text-center">
                <h2 class="text-xl font-semibold mb-4">
                    Email verification successful! You can now log in.
                </h2>
                <a href="../account/login.php" class="btn-blue-500-full">Back to login page</a>
            </div>
        </div>
    </div>

</body>

</html>
