<?php
require_once '../system/administratorSystem.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        // หากไม่มี Token หรือ Token ไม่ตรงกัน ให้หยุดการทำงานทันที
        die("<div style='color: #f00;'>ตรวจพบการส่งข้อมูลที่ไม่ปลอดภัย (CSRF Token Mismatch) <br><a href='customization.php' class='text-blue-500 underline'>กลับไปหน้าตั้งค่า</a></div>");
    }
}

$totalUsers = countUsers($conn);
$pendingServers = countPendingServers($conn);
$pendingReports = countPendingReports($conn);
$pendingResources = countPendingResources($conn);
$totalPending = $pendingServers + $pendingReports + $pendingResources;
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
    <title>Dashboard</title>
</head>

<body>
    <div class="flex">
        <?php include_once('../components/header-admin.php'); ?>

        <!-- Content -->
        <div class="flex-1 p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                <?php if ($totalPending > 0): ?>
                    <p class="text-sm text-yellow-600 mt-1">
                        <i class="fa-solid fa-bell mr-1"></i>
                        มีงานรอดำเนินการ <?= $totalPending ?> รายการ
                    </p>
                <?php else: ?>
                    <p class="text-sm text-gray-500 mt-1">ไม่มีรายการรออนุมัติหรือรอตรวจสอบในขณะนี้</p>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
                <div class="card-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">สมาชิกทั้งหมด</p>
                            <p class="text-3xl font-black text-gray-800 mt-1"><?= (int) $totalUsers ?></p>
                        </div>
                        <span class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                            <i class="fa-solid fa-users"></i>
                        </span>
                    </div>
                </div>

                <a href="manageServers.php?status=pending"
                   class="card-white block transition hover:shadow-md <?= $pendingServers > 0 ? 'ring-2 ring-yellow-300' : '' ?>">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">เซิร์ฟรออนุมัติ</p>
                            <p class="text-3xl font-black mt-1 <?= $pendingServers > 0 ? 'text-yellow-600' : 'text-gray-800' ?>">
                                <?= $pendingServers ?>
                            </p>
                        </div>
                        <span class="w-12 h-12 rounded-xl bg-yellow-50 text-yellow-600 flex items-center justify-center text-xl">
                            <i class="fa-solid fa-server"></i>
                        </span>
                    </div>
                    <?php if ($pendingServers > 0): ?>
                        <p class="text-xs text-yellow-600 mt-3 font-medium">
                            <i class="fa-solid fa-arrow-right mr-1"></i> ไปจัดการเซิร์ฟเวอร์
                        </p>
                    <?php endif; ?>
                </a>

                <a href="reports.php"
                   class="card-white block transition hover:shadow-md <?= $pendingReports > 0 ? 'ring-2 ring-orange-300' : '' ?>">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">แจ้งปัญหารอตรวจ</p>
                            <p class="text-3xl font-black mt-1 <?= $pendingReports > 0 ? 'text-orange-600' : 'text-gray-800' ?>">
                                <?= $pendingReports ?>
                            </p>
                        </div>
                        <span class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl">
                            <i class="fa-solid fa-bug"></i>
                        </span>
                    </div>
                    <?php if ($pendingReports > 0): ?>
                        <p class="text-xs text-orange-600 mt-3 font-medium">
                            <i class="fa-solid fa-arrow-right mr-1"></i> ไปตรวจรายงาน
                        </p>
                    <?php endif; ?>
                </a>

                <a href="manageResources.php"
                   class="card-white block transition hover:shadow-md <?= $pendingResources > 0 ? 'ring-2 ring-purple-300' : '' ?>">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Resources รออนุมัติ</p>
                            <p class="text-3xl font-black mt-1 <?= $pendingResources > 0 ? 'text-purple-600' : 'text-gray-800' ?>">
                                <?= $pendingResources ?>
                            </p>
                        </div>
                        <span class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                            <i class="fa-solid fa-cube"></i>
                        </span>
                    </div>
                    <?php if ($pendingResources > 0): ?>
                        <p class="text-xs text-purple-600 mt-3 font-medium">
                            <i class="fa-solid fa-arrow-right mr-1"></i> ไปจัดการ Resources
                        </p>
                    <?php endif; ?>
                </a>
            </div>

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12" id="users">
                    <div class="card-white">
                        <h2 class="font-bold text-xl mb-3">Manage Users</h2>
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
                                                <form action="" method="post" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบผู้ใช้ <?php echo htmlspecialchars($user['username']); ?> ? \nการกระทำนี้ไม่สามารถย้อนกลับได้');">
                                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
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