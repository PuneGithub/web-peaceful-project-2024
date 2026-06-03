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
    <?php include_once __DIR__ . '/../components/favicon.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/output.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Edit Update</title>
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
                        $userId = $_GET['userId'];
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userId'])) {
                            $username = $_POST['username'];
                            $email = $_POST['email'];
                            $role = $_POST['role'];

                            if (editUser($conn, $userId, $username, $email, $role)) {
                                echo "<div class='alert-green text-center'>Update Successful!</div>";
                            }
                        }

                        ?>
                        <?php
                        $User = fetchEditUser($conn, $userId);
                        
                        ?>
                        <h2 class="text-center text-xl font-bold mb-4">Edit User</h2>
                        <form action="" method="post" class="space-y-4">
                            <input type="hidden" name="userId" value="<?php echo $User['userId']; ?>">
                            <div>
                                <label for="username" class="block text-sm font-medium">username</label>
                                <input type="text" name="username" class="input-form" value="<?php echo $User['username']; ?>" placeholder="Enter username" required>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium">Email</label>
                                <input type="email" name="email" class="input-form" value="<?php echo $User['email']; ?>" placeholder="Enter email" required>
                            </div>
                            <div>
                                <label for="role" class="block text-sm font-medium">role</label>
                                <input type="text" name="role" class="input-form" value="<?php echo $User['role']; ?>" placeholder="Enter role" required>
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