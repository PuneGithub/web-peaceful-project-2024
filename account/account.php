<?php
session_start();
require_once '../system/conn.php';
require_once '../system/accountSystem.php';

//Check user login
if (!isset($_SESSION['userId'])) {
    header("Location: ./login.php");
    exit();
}

//Change Password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['oldPassword'], $_POST['newPassword'], $_POST['conPassword'])) {
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $conPassword = $_POST['conPassword'];

    $resultPassword = changePassword($conn, $_SESSION['userId'], $oldPassword, $newPassword, $conPassword);
    if ($resultPassword === "Password changed successfully") {
        $msgSuccess = $resultPassword;
    } else {
        $msgError = $resultPassword;
    }
}

//Profile Image
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profileImage'])) {
    $result = uploadProfileImage($conn);
    if ($result === "Upload successful!") {
        $msgImagesuccess = $result;
    } else {
        $msgImageerror = $result;
    }
}

try {
    $sql = "SELECT profileImage FROM users WHERE userId = :userId";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':userId' => $_SESSION['userId']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $profileImage = $user['profileImage'] ?? 'default-profile.webp'; // หากไม่มีรูป ใช้ค่าเริ่มต้น
} catch (PDOException $error) {
    die("Database error: " . $error->getMessage());
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
            <?php if (isset($msgImagesuccess)): ?>
                <div class="alert-green text-center"><?php echo htmlspecialchars($msgImagesuccess); ?></div>
            <?php endif; ?>
            <?php if (isset($msgImageerror)): ?>
                <div class="alert-danger text-center"><?php echo htmlspecialchars($msgImageerror); ?></div>
            <?php endif; ?>
            <!-- Profile -->
            <div class="flex items-center justify-center">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="relative mb-4">
                        <img src="../img/profile_users/<?php echo htmlspecialchars($profileImage); ?>"
                            alt="Profile Image"
                            class="w-32 h-32 rounded-full object-cover border-4 border-blue-500">

                        <label for="profileImage"
                            class="absolute bottom-0 right-0 bg-blue-500 text-white p-2 rounded-full cursor-pointer">
                            <i class="fa fa-camera"></i>
                        </label>
                        <input type="file" id="profileImage" name="profileImage" class="hidden" accept="image/*">
                    </div>

                    <div>
                        <input type="submit" class="btn-blue-500" value="Upload Image">
                    </div>
                </form>
            </div>
            <?php

            if (isset($msgError)):
            ?>
                <div class="alert-danger text-center"><?php echo htmlspecialchars($msgError); ?></div>
            <?php endif; ?>
            <?php if (isset($msgSuccess)):
            ?>
                <div class="alert-green text-center"><?php echo htmlspecialchars($msgSuccess); ?></div>
            <?php endif; ?>
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