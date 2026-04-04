<?php
session_start();
require_once("system/conn.php");
require_once("system/config.php");
require_once("system/serverSystem.php");

$category = $_GET['category'] ?? null;
$search = $_GET['search'] ?? null;

//ดึงข้อมูลเซิร์ฟเวอร์
$servers = fetchApprovedServers($conn, null, $category, $search);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายชื่อเซิร์ฟเวอร์ - Zencrafterly</title>
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
</head>

<body class="bg-gray-50">

    <?php include_once("components/header-navbar.php"); ?>

    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="card-white">
            <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                        <i class="fa-solid fa-server text-blue-600 mr-3"></i>
                        รายชื่อเซิร์ฟเวอร์ทั้งหมด
                        <span class="ml-3 bg-blue-100 text-blue-600 text-sm font-bold px-3 py-1 rounded-full">
                            <?= count($servers) ?> Servers
                        </span>
                    </h1>
                    <p class="text-gray-500 mt-2">ค้นหาเซิร์ฟเวอร์ Minecraft ที่ดีที่สุดสำหรับคุณใน Zencrafterly</p>
                </div>

                <?php if (isset($_SESSION['userId'])):  ?>
                    <a href="server/addServer.php" class="btn-green-500 flex items-center whitespace-nowrap">
                        <i class="fa-solid fa-plus mr-2"></i> เพิ่มเซิร์ฟเวอร์
                    </a>
                <?php else: ?>
                    <button onclick="alert('กรุณาเข้าสู่ระบบก่อนเพิ่มเซิร์ฟเวอร์นะ!')"
                        class="bg-gray-400 text-white px-4 py-2 rounded-lg font-bold text-[10px] uppercase shadow-sm flex items-center whitespace-nowrap cursor-not-allowed">
                        <i class="fa-solid fa-plus mr-2"></i> เพิ่มเซิร์ฟเวอร์
                    </button>
                <?php endif; ?>

                <form action="servers.php" method="GET" class="flex w-full md:w-96">
                    <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>"
                        placeholder="ค้นหาชื่อเซิร์ฟเวอร์ หรือ IP..."
                        class="w-full px-4 py-2 border border-gray-200 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-r-lg hover:bg-blue-700 transition">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>

            <div class="flex flex-wrap gap-2 mb-8">
                <a href="servers.php" class="px-5 py-2 rounded-full border <?= !$category ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100' ?> transition text-sm font-bold shadow-sm">
                    ทั้งหมด
                </a>
                <?php $cats = ['Survival', 'Skyblock', 'MiniGames', 'MMORPG']; ?>
                <?php foreach ($cats as $cat): ?>
                    <a href="?category=<?= $cat ?>" class="px-5 py-2 rounded-full border <?= $category === $cat ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100' ?> transition text-sm font-bold shadow-sm">
                        <?= $cat ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <?php if (!empty($servers)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    $rank = 1; //เพิ่มตัวนับอันดับตรงนี้
                    foreach ($servers as $server):
                    ?>
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition p-5 relative">

                            <?php if ($rank <= 3): ?>
                                <div class="absolute top-0 right-0">
                                    <?php
                                    $medalColor = [
                                        1 => 'bg-yellow-400 text-yellow-900', // Gold
                                        2 => 'bg-slate-300 text-slate-700',  // Silver
                                        3 => 'bg-orange-400 text-orange-900' // Bronze
                                    ];
                                    ?>
                                    <div class="<?= $medalColor[$rank] ?> text-[10px] font-black px-3 py-1 rounded-bl-xl shadow-sm uppercase flex items-center gap-1">
                                        <i class="fa-solid fa-crown"></i> Rank #<?= $rank ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="absolute top-0 right-0">
                                    <div class="bg-gray-100 text-gray-400 text-[10px] font-bold px-3 py-1 rounded-bl-xl shadow-sm">
                                        #<?= $rank ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="flex gap-4">
                                <div class="relative">
                                    <img src="img/server-icons/<?= $server['serverImage'] ?: 'default_server.webp' ?>"
                                        class="w-20 h-20 rounded-xl object-cover shadow-inner"
                                        onerror="this.src='img/default_server.webp'">

                                    <span class="absolute -top-2 -left-2 bg-blue-600 text-white text-[10px] w-7 h-7 flex items-center justify-center rounded-full font-bold shadow-lg border-2 border-white">
                                        <?= number_format($server['votes']) ?>
                                    </span>
                                </div>

                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-800 text-lg line-clamp-1 pr-12"><?= htmlspecialchars($server['serverName']) ?></h3>
                                    <p class="text-xs text-gray-400 font-medium uppercase mb-2">
                                        <?= htmlspecialchars($server['serverVersion']) ?> • <?= htmlspecialchars($server['serverCategory']) ?>
                                    </p>
                                    <div class="mt-1">
                                        <span class="server-status inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800" data-ip="<?= htmlspecialchars($server['serverIP']) ?>">
                                            <i class="fa-solid fa-spinner fa-spin mr-1"></i> Checking...
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 flex items-center bg-gray-50 rounded-xl p-2 border border-gray-100">
                                <code class="text-sm font-mono text-gray-600 flex-1 px-2 truncate" id="ip-<?= $server['serverId'] ?>">
                                    <?= htmlspecialchars($server['serverIP']) ?>
                                </code>

                                <button onclick="copyIP('ip-<?= $server['serverId'] ?>')"
                                    class="bg-white border border-gray-200 text-gray-700 text-[10px] font-bold px-4 py-1.5 rounded-lg shadow-sm hover:bg-blue-600 hover:text-white transition uppercase">
                                    Copy
                                </button>

                                <button onclick="castVote(<?= $server['serverId'] ?>)" class="bg-red-500 text-white text-[10px] font-bold px-4 py-1.5 rounded-lg shadow-sm hover:bg-red-600 transition uppercase ml-2 flex items-center gap-1">
                                    <i class="fa-solid fa-heart text-[8px]"></i> Vote
                                </button>
                            </div>
                        </div>
                    <?php
                        $rank++; //เพิ่มค่า $rank ในทุกรอบของ Loop
                    endforeach;
                    ?>
                </div>
            <?php else: ?>

                <div class="text-center py-20 bg-white rounded-3xl shadow-sm border border-dashed border-gray-200">
                    <img src="img/empty-state.svg" class="w-32 mx-auto mb-4 opacity-20">
                    <p class="text-gray-400 font-medium">ไม่พบเซิร์ฟเวอร์ที่ตรงตามเงื่อนไขของคุณ</p>
                    <a href="servers.php" class="text-blue-600 text-sm font-bold mt-2 inline-block underline">ล้างการค้นหา</a>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2"></div>

    <?php include_once("components/footer.php"); ?>

<script src="<?php echo base_url('/js/script.js'); ?>"></script>
</body>

</html>