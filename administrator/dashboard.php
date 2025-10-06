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
                            if (deleteUser($conn, $userId)) {
                                echo "<div class='alert-success text-center'>ลบผู้ใช้สำเร็จแล้ว!</div>";
                            }
                        }
                        ?>
                        <div class="overflow-x-auto">
                            <table class="table-auto w-full border border-slate-400 border-collapse text-center">
                                <thead>
                                    <tr>
                                        <th class="border border-slate-300">userId</th>
                                        <th class="border border-slate-300">username</th>
                                        <th class="border border-slate-300">email</th>
                                        <th class="border border-slate-300">profileImage</th>
                                        <th class="border border-slate-300">createDate</th>
                                        <th class="border border-slate-300">role</th>
                                        <th class="border border-slate-300">Edit</th>
                                        <th class="border border-slate-300">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $users = fetchUsers($conn);
                                    foreach ($users as $user) {
                                    ?>
                                        <tr>
                                            <td class="border border-slate-300"><?php echo htmlspecialchars($user['userId']); ?></td>
                                            <td class="border border-slate-300"><?php echo htmlspecialchars($user['username']); ?></td>
                                            <td class="border border-slate-300"><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td class="border border-slate-300"><img src="../img/profile_users/<?php echo htmlspecialchars($user['profileImage']); ?>" class="w-32 h-32 object-cover rounded-full" alt="profile"></td>
                                            <td class="border border-slate-300"><?php echo htmlspecialchars($user['createDate']); ?></td>
                                            <td class="border border-slate-300"><?php echo htmlspecialchars($user['role']); ?></td>
                                            <td class="border border-slate-300">
                                                <a href="editUser.php?userId=<?php echo $user['userId']; ?>" class="btn-orange-500 inline-block">Edit</a>
                                            </td>
                                            <td class="border border-slate-300">
                                                <form action="" method="post">
                                                    <input type="hidden" name="userId" value="<?php echo $user['userId']; ?>">
                                                    <input type="submit" class="btn-red-500 inline-block" value="Delete">
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>