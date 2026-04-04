<?php
require_once("../system/config.php");

// ประมวลผลเมื่อมีการกดส่งฟอร์ม (POST)
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    require_once '../system/resetCode.php';
    $email = htmlspecialchars(str_replace(' ', '', $_POST['email']));

    $result = forgotPassword($email);

    // 🚩 ใช้ระบบ Redirect เพื่อป้องกันผู้ใช้กด F5 (Refresh) แล้วส่งเมล์ซ้ำ
    if ($result === true) {
        $_SESSION['msg_success'] = "ระบบได้ส่งลิงก์รีเซ็ตรหัสผ่านไปที่อีเมลของคุณแล้ว (โปรดเช็คในกล่องจดหมายขยะด้วย)";
    } else {
        $_SESSION['msg_error'] = $result;
    }

    // รีเฟรชกลับมาหน้าเดิม เพื่อล้างค่า POST ทิ้ง
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
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
            <h2 class="text-xl font-semibold text-center">Forgot Password</h2>
            <?php
            // 🚩 แสดงข้อความแจ้งเตือนจาก Session แล้วลบทิ้ง
            if (isset($_SESSION['msg_success'])) {
                echo "<div class='alert-green mb-4'><i class='fa-regular fa-circle-check'></i> " . $_SESSION['msg_success'] . "</div>";
                unset($_SESSION['msg_success']);
            }
            if (isset($_SESSION['msg_error'])) {
                echo "<div class='alert-danger mb-4'>" . htmlspecialchars($_SESSION['msg_error']) . "</div>";
                unset($_SESSION['msg_error']);
            }
            ?>
            <form action="" method="post" class="space-y-4" onsubmit="return disableSubmitButton(this)">
                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input type="email" name="email" class="input-form" placeholder="Enter email" required>
                    <input type="hidden" name="type" value="reset">
                </div>
                <div>
                    <input type="submit" id="submitBtn" class="btn-green-500 cursor-pointer" value="Send">
                </div>
            </form>
        </div>
    </div>

    <script>
        function disableSubmitButton(form) {
            // หาปุ่ม Submit
            const btn = document.getElementById('submitBtn');

            // เปลี่ยนข้อความบนปุ่มให้ผู้ใช้รู้ว่าระบบกำลังทำงาน
            btn.value = "กำลังส่งอีเมล โปรดรอสักครู่...";

            // ปิดการใช้งานปุ่ม ไม่ให้กดซ้ำได้อีก!
            btn.disabled = true;

            // ทำให้ปุ่มดูเป็นสีเทาๆ (ถ้าใช้ Tailwind ก็เติมคลาสลงไปครับ)
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            btn.classList.remove('cursor-pointer');

            // ปล่อยให้ฟอร์ม submit ตามปกติ
            return true;
        }
    </script>

</body>

</html>