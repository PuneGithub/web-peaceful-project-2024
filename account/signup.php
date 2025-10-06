<?php
session_start();
require_once("../system/config.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/output.css">
    <title>Zencrafterly</title>
</head>
<script src="js/script.js"></script>

<body style="background-image: url('../img/bg.webp');">

    <?php include_once("../components/header-navbar.php"); ?>

    <!-- header navbar -->
    <div class="flex items-center justify-center h-screen">
        <div class="card-white w-full max-w-md">
            <h2 class="text-xl font-semibold text-center">Sign Up</h2>
            <?php
            //ตรวจสอบ form
            if ($_SERVER['REQUEST_METHOD'] === "POST") {
                require_once('../system/registration.php');

                $username = htmlspecialchars(str_replace(' ', '', $_POST['username']));
                $email = htmlspecialchars(str_replace(' ', '', $_POST['email']));
                $password = $_POST['password'];
                $con_password = $_POST['con_password'];

                if ($password == $con_password) {
                    $result = signup($username, $password, $email);
                    if ($result === true) {
                        echo "<div class='alert-green'><i class='fa-regular fa-circle-check'></i> Registration successful! <b><a href='../account/login.php'>Click to login</a></div></b>";
                    } else {
                        echo "<div class='alert-danger'>" . htmlspecialchars($result) . "</div>";
                    }
                } else {
                    echo "<div class='alert-danger'>Passwords do not match</div>";
                }
            }
            ?>
            <form action="" method="post" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium">Username</label>
                    <input type="username" name="username" class="input-form" placeholder="Enter username" required>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input type="email" name="email" class="input-form" placeholder="Enter email" required>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium">Password</label>
                    <input type="password" name="password" class="input-form" placeholder="Enter password" required>
                </div>
                <div>
                    <label for="con_password" class="block text-sm font-medium">Confirm password</label>
                    <input type="password" name="con_password" class="input-form" placeholder="Enter confirm password" required>
                </div>
                <div class="g-recaptcha" data-sitekey="6LeGT_IqAAAAAMcwAKfdc3l_chVDGZKAJoznPwb_">

                </div>
                <div>
                    <input type="checkbox" name="check" required>
                    <label for="check" class="text-sm font-medium">I agree to the Terms and Privacy Policy.</label>
                </div>
                <div>
                    <input type="submit" class="btn-blue-500" value="Sign Up">
                </div>
            </form>
        </div>
    </div>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>

</html>