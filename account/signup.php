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
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
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

                // 1. รับค่า Token จาก Turnstile
                $turnstileToken = $_POST['cf-turnstile-response'] ?? null;
                $secretKey = "0x4AAAAAACnkmFIyhYi6srxLOvFWKXCwL7g";

                // 2. ส่งไปเช็คกับ Cloudflare API (Backend Verification)
                $ch = curl_init("https://challenges.cloudflare.com/turnstile/v0/siteverify");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, [
                    'secret'   => $secretKey,
                    'response' => $turnstileToken,
                    'remoteip' => $_SERVER['REMOTE_ADDR']
                ]);
                $response = json_decode(curl_exec($ch), true);
                unset($ch);

                if (!$response['success']) {
                    echo "<div class='alert-danger'>ตรวจพบความผิดปกติ! กรุณายืนยันตัวตนใหม่อีกครั้ง</div>";
                } else {
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

            }
            ?>
            <form action="" method="post" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium">Username</label>
                    <input type="text" name="username" class="input-form" placeholder="Enter username" required>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input type="email" name="email" class="input-form" placeholder="Enter email" required>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium">Password</label>
                    <input type="password" name="password" autocomplete="new-password" class="input-form" placeholder="Enter password" required>
                </div>
                <div>
                    <label for="con_password" class="block text-sm font-medium">Confirm password</label>
                    <input type="password" name="con_password" autocomplete="new-password" class="input-form" placeholder="Enter confirm password" required>
                </div>

                <div class="cf-turnstile" data-sitekey="0x4AAAAAACnkmG5ox7p1kiZA" data-theme="light"></div>
                <div>
                    <input type="checkbox" name="check" required>
                    <label for="check" class="text-sm font-medium">I agree to the Terms and Privacy Policy. <a href="../legal.php" class="text-blue-500" target="_blank">Read More</a></label>
                </div>
                <div>
                    <input type="submit" class="btn-blue-500" value="Sign Up">
                </div>
            </form>
        </div>
    </div>

    <script src="<?php echo base_url('/js/script.js'); ?>"></script>
</body>

</html>