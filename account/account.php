<?php
session_start();
require_once '../system/conn.php';

//Check user login
if (!isset($_SESSION['userId'])) {
    header("Location: ./login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/output.css">
    <title>Peaceful Network</title>
</head>
<script src="../js/script.js"></script>

<body style="background-image: url('../img/bg.webp');">

    <?php include_once("../components/header-navbar.php"); ?>

    <!-- header navbar -->
    <div class="flex items-center justify-center h-screen">
        <div class="card-white w-full max-w-md">
            <h2 class="text-xl font-semibold text-center">Account</h2>
            <?php

            ?>
            <form action="" method="post" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="username" class="block text-sm font-medium">Username</label>
                        <input type="text" class="input-form" value="<?php echo $_SESSION['username']; ?>" disabled>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium">Email</label>
                        <input type="text" class="input-form" value="<?php echo $_SESSION['email']; ?>" disabled>
                    </div>
                </div>
                <div>
                    <label for="oldPassword">Old Password</label>
                    <input type="password" name="oldPassword" class="input-form" placeholder="Enter old password" required>
                </div>
                <div>
                    <label for="newPassword">New Password</label>
                    <input type="password" name="newPassword" class="input-form" placeholder="Enter new password" required>
                </div>
                <div>
                    <label for="conPassword">Confirm Password</label>
                    <input type="password" name="conPassword" class="input-form" placeholder="Enter confirm password" required>
                </div>
                <div>
                    <input type="submit" class="btn-blue-500" value="SAVE">
                </div>
            </form>
        </div>
    </div>

</body>

</html>