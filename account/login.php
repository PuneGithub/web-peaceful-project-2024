<?php
session_start();
require_once '../system/conn.php';
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
<script src="js/script.js"></script>

<body style="background-image: url('../img/bg.webp');">

    <?php include_once("../components/header-navbar.php"); ?>

    <!-- header navbar -->
    <div class="flex items-center justify-center h-screen">
        <div class="card-white w-full max-w-md">
            <h2 class="text-xl font-semibold text-center">LOGIN</h2>
            <?php
            require_once("../system/registration.php");

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $identifier = $_POST['identifier']; //email or username
                $password = $_POST['password'];

                //เรียกใช้ function login
                $result = login($identifier, $password);
                if ($result !== true) {
                    echo "<div class='alert-danger text-center'>" . htmlspecialchars($result) . "</div>";
                }
            }

            ?>
            <form action="" method="post" class="space-y-4">
                <div>
                    <label for="identifier" class="block text-sm font-medium">Email or Username</label>
                    <input type="text" name="identifier" class="input-form" placeholder="Enter email or username" required>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium">Password</label>
                    <input type="password" name="password" class="input-form" placeholder="Enter password" required>
                </div>
                <div>
                    <a href="forgot_password.php" class="text-blue-400">Forgot password</a>
                </div>
                <div>
                    <input type="submit" class="btn-green-500" value="LOGIN">
                </div>
            </form>
        </div>
    </div>

</body>

</html>