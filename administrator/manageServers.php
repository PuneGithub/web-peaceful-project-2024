<?php
require_once '../system/administratorSystem.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

$msgServer = $_SESSION['manage_servers_flash'] ?? null;
unset($_SESSION['manage_servers_flash']);

// Logic สำหรับการลบเซิร์ฟเวอร์ (POST + redirect กันส่งซ้ำตอนรีเฟรช)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['serverId'])) {
    $serverId = (int) $_POST['serverId'];
    if ($serverId > 0) {
        if (deleteServer($conn, $serverId)) {
            $_SESSION['manage_servers_flash'] = "<div class='alert-green'>ลบข้อมูลเซิร์ฟเวอร์สำเร็จแล้ว</div>";
        } else {
            $_SESSION['manage_servers_flash'] = "<div class='alert-danger'>เกิดข้อผิดพลาดในการลบข้อมูล</div>";
        }
    }
    header('Location: manageServers.php');
    exit;
}

// Logic สำหรับการอนุมัติ (GET แล้ว redirect ให้ URL สะอาด)
if (isset($_GET['approveId'])) {
    $serverId = (int) $_GET['approveId'];
    if ($serverId > 0 && approveServer($conn, $serverId)) {
        $_SESSION['manage_servers_flash'] = "<div class='alert-green'>อนุมัติเซิร์ฟเวอร์เรียบร้อย! ตอนนี้ออนไลน์แล้ว</div>";
    } elseif ($serverId > 0) {
        $_SESSION['manage_servers_flash'] = "<div class='alert-danger'>ไม่สามารถอนุมัติเซิร์ฟเวอร์นี้ได้</div>";
    }
    header('Location: manageServers.php');
    exit;
}

// Logic สำหรับการปฏิเสธ
if (isset($_GET['rejectId'])) {
    $serverId = (int) $_GET['rejectId'];
    if ($serverId > 0 && rejectServer($conn, $serverId)) {
        $_SESSION['manage_servers_flash'] = "<div class='alert-danger'>ปฏิเสธเซิร์ฟเวอร์เรียบร้อยแล้ว</div>";
    } elseif ($serverId > 0) {
        $_SESSION['manage_servers_flash'] = "<div class='alert-danger'>ไม่สามารถปฏิเสธเซิร์ฟเวอร์นี้ได้</div>";
    }
    header('Location: manageServers.php');
    exit;
}

// ดึงข้อมูลเซิร์ฟเวอร์ทั้งหมด
$fetchAllServers = fetchAllServers($conn);
if (!is_array($fetchAllServers)) {
    $fetchAllServers = [];
}
$totalServers = count($fetchAllServers);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/output.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Manage Servers</title>
</head>

<body>
    <div class="flex">
        <?php include_once('../components/header-admin.php'); ?>

        <!-- Content -->
        <div class="flex-1 p-6">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 sm:col-span-4">
                    <div class="card-white">
                        <h2 class="font-bold text-lg">เซิร์ฟเวอร์ทั้งหมด: <?php echo $totalServers; ?></h2>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-8">
                    <div class="card-white">
                        <?php if ($msgServer) {
                            echo $msgServer;
                        } ?>
                        <div class="flex items-center">
                            <h2 class="font-bold flex-1 text-center text-xl">Manage Servers</h2>
                        </div>
                        <div class="overflow-x-auto mt-3">
                            <table class="table-auto w-full border border-slate-400 border-collapse text-center">
                                <thead>
                                    <tr>
                                        <th class="border border-slate-300">serverId</th>
                                        <th class="border border-slate-300">icon</th>
                                        <th class="border border-slate-300">serverName / IP</th>
                                        <th class="border border-slate-300">serverCategory</th>
                                        <th class="border border-slate-300">status</th>
                                        <th class="border border-slate-300">manage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($fetchAllServers as $server) {
                                    ?>
                                        <tr>
                                            <td class="border border-slate-300"><?php echo htmlspecialchars($server['serverId']); ?></td>
                                            <td class="border border-slate-300"><img src="../img/server-icons/<?php echo $server['serverImage'] ?: 'default_server.webp'; ?>" alt="serverIcon" class="w-12 h-12 object-cover rounded-sm max-w-32"></td>
                                            <td class="border border-slate-300">
                                                <?php echo htmlspecialchars($server['serverName']); ?>
                                                <div class="text-xs text-gray-400 font-mono"><?php echo htmlspecialchars($server['serverIP']); ?></div>
                                            </td>
                                            <td class="border border-slate-300"><?php echo htmlspecialchars($server['serverCategory']); ?></td>
                                            <td class="border border-slate-300">
                                                <?php
                                                // แสดงป้ายกำกับตามสถานะ
                                                $statusClass = [
                                                    'pending' => 'bg-yellow-100 text-yellow-600',
                                                    'approved' => 'bg-green-100 text-green-600',
                                                    'rejected' => 'bg-red-100 text-red-600'
                                                ];
                                                $currentClass = $statusClass[$server['status']] ?? 'bg-gray-100';
                                                ?>
                                                <span class="px-2 py-1 rounded-full text-[10px] font-bold <?php echo $currentClass; ?>">
                                                    <?php echo strtoupper($server['status']); ?>
                                                </span>
                                            </td>
                                            <td class="p-3 border">
                                                <div class="flex justify-center gap-2">

                                                    <?php if ($server['status'] === 'pending'): ?>
                                                        <a href="?approveId=<?= $server['serverId'] ?>"
                                                            onclick="return confirm('ยืนยันการอนุมัติเซิร์ฟเวอร์นี้?')"
                                                            class="text-green-500 hover:text-green-700 text-lg" title="อนุมัติ">
                                                            <i class="fa-solid fa-circle-check"></i>
                                                        </a>

                                                        <a href="?rejectId=<?= $server['serverId'] ?>"
                                                            onclick="return confirm('ยืนยันการปฏิเสธเซิร์ฟเวอร์นี้?')"
                                                            class="text-orange-500 hover:text-orange-700 text-lg" title="ปฏิเสธ">
                                                            <i class="fa-solid fa-circle-xmark"></i>
                                                        </a>
                                                    <?php endif; ?>

                                                    <a href="reviewServer.php?id=<?php echo $server['serverId']; ?>"
                                                        class="text-orange-500 hover:text-orange-700 transition">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </a>
                                                    <form action="" method="post" onsubmit="return confirm('ยืนยันการลบเซิร์ฟเวอร์นี้?');" class="inline">
                                                        <input type="hidden" name="serverId" value="<?php echo $server['serverId']; ?>">
                                                        <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    </form>
                                                </div>
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