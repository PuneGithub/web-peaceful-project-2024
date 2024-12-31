<?php
require_once '../system/administratorSystem.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/output.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Dashboard</title>
</head>

<body>
    <div class="flex">
        <?php include_once('../components/header-admin.php'); ?>

        <!-- Content -->
        <div class="flex-1 p-6">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 sm:col-span-4">
                    <div class="card-white">
                        <?php $totalUsers = countUsers($conn); ?>
                        <h2 class="font-bold text-lg">จำนวนสมาชิก: <?php echo $totalUsers; ?> บัญชี</h2>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-8">
                    <div class="card-white">
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userId'])) {
                            $userId = $_POST['userId'];
                            // if (deleteUser($conn, $userId)) {
                            //     echo "<div class='alert-success text-center'>ลบผู้ใช้สำเร็จแล้ว!</div>";
                            // }
                        }
                        ?>
                        <h2 class="text-center text-xl font-bold mb-4">Edit User</h2>
                        <form action="" method="post" class="space-y-4">
                            <input type="hidden" name="userId" value="<?php echo $user['userId']; ?>">
                            <div>
                                <label for="username" class="block text-sm font-medium">username</label>
                                <input type="text" name="username" class="input-form" placeholder="Enter username" required>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium">Email</label>
                                <input type="email" name="email" class="input-form" placeholder="Enter email" required>
                            </div>
                            <div>
                                <label for="role" class="block text-sm font-medium">role</label>
                                <input type="text" name="role" class="input-form" placeholder="Enter role" required>
                            </div>
                            <div>
                                <input type="submit" class="btn-blue-500" value="SAVE">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>