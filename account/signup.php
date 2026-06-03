<?php
session_start();
require_once("../system/config.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once __DIR__ . '/../components/favicon.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/output.css">
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <title>Sign Up | Zencrafterly</title>
</head>

<body style="background-image: url('<?= base_url('img/bg.webp'); ?>'); background-size: cover; background-position: center;">

    <?php include_once("../components/header-navbar.php"); ?>

    <div class="flex items-center justify-center min-h-screen py-10">
        <div class="card-white w-full max-w-md">
            <h2 class="text-xl font-semibold text-center mb-4">Sign Up</h2>
            <?php
            //ตรวจสอบ form
            if ($_SERVER['REQUEST_METHOD'] === "POST") {

                // 1. รับค่า Token จาก Turnstile
                $turnstileToken = $_POST['cf-turnstile-response'] ?? null;

                if (!verifyTurnstileToken($turnstileToken)) {
                    echo "<div class='alert-danger'><i class='fa-solid fa-robot mr-2'></i>ตรวจพบความผิดปกติ! กรุณายืนยันตัวตนใหม่อีกครั้ง</div>";
                } else {
                    require_once('../system/registration.php');
    
                    $username = htmlspecialchars(str_replace(' ', '', $_POST['username']));
                    $email = htmlspecialchars(str_replace(' ', '', $_POST['email']));
                    $password = $_POST['password'];
                    $con_password = $_POST['con_password'];
    
                    if ($password == $con_password) {
                        $result = signup($username, $password, $email);
                        if ($result === true) {
                            echo "<div class='alert-green'><i class='fa-regular fa-circle-check mr-2'></i>Registration successful! <b><a href='login.php' class='underline'>Click to login</a></b></div>";
                        } else {
                            echo "<div class='alert-danger'>" . htmlspecialchars($result) . "</div>";
                        }
                    } else {
                        echo "<div class='alert-danger'><i class='fa-solid fa-triangle-exclamation mr-2'></i>Passwords do not match</div>";
                    }
                }
            }
            ?>
            <form action="" method="post" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium">Username</label>
                    <input type="text" name="username" class="input-form w-full" placeholder="Enter username" required>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input type="email" name="email" class="input-form w-full" placeholder="Enter email" required>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium">Password</label>
                    <input type="password" name="password" autocomplete="new-password" class="input-form w-full" placeholder="Enter password" required>
                </div>
                <div>
                    <label for="con_password" class="block text-sm font-medium">Confirm password</label>
                    <input type="password" name="con_password" autocomplete="new-password" class="input-form w-full" placeholder="Enter confirm password" required>
                </div>

                <div class="flex justify-center my-4">
                    <div class="cf-turnstile" data-sitekey="<?= htmlspecialchars(TURNSTILE_SITE_KEY, ENT_QUOTES, 'UTF-8') ?>" data-theme="light"></div>
                </div>
                
                <div class="flex items-start">
                    <input type="checkbox" name="check" id="check" class="mt-1 mr-2" required>
                    <label for="check" class="text-sm font-medium text-gray-600">I agree to the Terms and Privacy Policy. <a href="<?= base_url('legal.php') ?>" class="text-blue-500 hover:underline" target="_blank">Read More</a></label>
                </div>
                <div class="pt-2">
                    <input type="submit" class="btn-blue-500 w-full cursor-pointer" value="Sign Up">
                </div>
            </form>
        </div>
    </div>

    <script src="<?= base_url('js/script.js'); ?>"></script>
</body>

</html>