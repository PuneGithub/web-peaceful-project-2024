<?php

session_start();

require_once("system/conn.php");

require_once("system/config.php");

require_once("system/serverSystem.php");



// รองรับทั้งการเรียกผ่านระบบ Slug (URL คลีน) หรือผ่าน ID ตรงๆ

$server = null;

// ดึงข้อมูลด้วย Slug

if (isset($_GET['slug'])) {

    $slug = $_GET['slug'];

    $stmt = $conn->prepare("

        SELECT s.*, u.username 

        FROM servers s 

        JOIN users u ON s.userId = u.userId 

        WHERE s.serverSlug = :slug AND s.status = 'approved'

    ");

    $stmt->execute([':slug' => $slug]);

    $server = $stmt->fetch(PDO::FETCH_ASSOC);

}

// ดึงข้อมูลด้วย ID

elseif (isset($_GET['id'])) {

    $serverId = $_GET['id'];

    $stmt = $conn->prepare("

        SELECT s.*, u.username 

        FROM servers s 

        JOIN users u ON s.userId = u.userId 

        WHERE s.serverId = :id AND s.status = 'approved'

    ");

    $stmt->execute([':id' => $serverId]);

    $server = $stmt->fetch(PDO::FETCH_ASSOC);

}



// ถ้าไม่พบข้อมูลเซิร์ฟเวอร์ ให้เด้งกลับหน้าหลักหรือหน้า 404

if (!$server) {

    header("HTTP/1.0 404 Not Found");

    header("Location: " . base_url('404.php'));

    exit;

}

$metaDesc = mb_substr(strip_tags($server['serverDescription'] ?? ''), 0, 160);
$serverSlug = trim($server['serverSlug'] ?? '');
$canonicalPath = $serverSlug !== '' ? 'server/' . $serverSlug : 'server-detail.php?id=' . (int) $server['serverId'];
$canonicalUrl = absolute_url($canonicalPath);
$iconFile = $server['serverImage'] ?: 'default_server.webp';
$ogImageUrl = absolute_url('img/server-icons/' . $iconFile);
$ogTitle = $server['serverName'] . ' - เซิร์ฟเวอร์ Minecraft | Zencrafterly';

?>

<!DOCTYPE html>

<html lang="en">



<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php include_once __DIR__ . '/components/favicon.php'; ?>

    <title><?= htmlspecialchars($ogTitle, ENT_QUOTES, 'UTF-8') ?></title>

    <meta name="description" content="<?= htmlspecialchars($metaDesc, ENT_QUOTES, 'UTF-8') ?>">
    <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:title" content="<?= htmlspecialchars($ogTitle, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($metaDesc, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:image" content="<?= htmlspecialchars($ogImageUrl, ENT_QUOTES, 'UTF-8') ?>">

    <link rel="stylesheet" href="<?= base_url('css/output.css') ?>">

    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

    

</head>



<body class="bg-gray-50 text-gray-800">



    <?php include_once("components/header-navbar.php"); ?>



    <div class="max-w-7xl mx-auto px-4 py-10">

        <a href="<?= base_url('servers.php') ?>" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-blue-600 transition mb-6 font-medium">

            <i class="fa-solid fa-arrow-left"></i> กลับไปหน้ารวมเซิร์ฟเวอร์

        </a>



        <div class="card-white mb-8 p-6 md:p-8 flex flex-col md:flex-row gap-6 items-center justify-between">

            <div class="flex flex-col md:flex-row gap-6 items-center text-center md:text-left">

                <img src="<?= htmlspecialchars(base_url('img/server-icons/' . ($server['serverImage'] ?: 'default_server.webp')), ENT_QUOTES, 'UTF-8') ?>"

                    alt="Server Logo" class="w-24 h-24 rounded-2xl object-cover shadow-sm border border-gray-100"

                    onerror="this.onerror=null; this.src='<?= htmlspecialchars(base_url('img/server-icons/default_server.webp'), ENT_QUOTES, 'UTF-8') ?>';">



                <div>

                    <h1 class="text-3xl font-bold mb-2 flex items-center gap-3 justify-center md:justify-start">

                        <?= htmlspecialchars($server['serverName']) ?>

                        <span class="text-xs bg-green-100 text-green-600 px-2.5 py-1 rounded-full font-bold uppercase tracking-wide flex items-center gap-1">

                            <span class="w-2 h-2 rounded-full bg-green-500 inline-block animate-pulse"></span> Online

                        </span>

                    </h1>

                    <p class="text-gray-500 text-sm font-medium flex flex-wrap gap-4 justify-center md:justify-start">

                        <span><i class="fa-solid fa-gamepad text-gray-400 mr-1"></i> เวอร์ชัน: <span class="text-gray-700 font-bold"><?= htmlspecialchars($server['serverVersion'] ?? '1.20.x') ?></span></span>

                        <span><i class="fa-solid fa-user text-gray-400 mr-1"></i> ผู้ดูแล: <span class="text-blue-600 font-semibold"><?= htmlspecialchars($server['username']) ?></span></span>

                    </p>

                </div>

            </div>



            <div class="flex items-center gap-4 border-t md:border-t-0 pt-4 md:pt-0 w-full md:w-auto justify-center">

                <div class="text-center bg-red-50 p-3 px-5 rounded-2xl border border-red-100">

                    <span class="block text-xs font-bold text-red-400 uppercase tracking-wider">คะแนนโหวต</span>

                    <span class="text-3xl font-black text-red-500"><?= number_format($server['votes'] ?? 0) ?></span>

                </div>

                <button onclick="castVote(<?= $server['serverId'] ?>)" class="bg-red-500 text-white font-bold px-6 py-4 rounded-2xl shadow-md hover:bg-red-600 transition flex items-center gap-2 h-full text-lg">

                    <i class="fa-solid fa-heart animate-bounce"></i> โหวตให้เซิร์ฟนี้

                </button>

            </div>

        </div>



        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">



            <div class="lg:col-span-2 space-y-6">

                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6 rounded-3xl shadow-md text-white">

                    <h3 class="font-bold text-lg mb-3"><i class="fa-solid fa-network-wired mr-2"></i> ที่อยู่เซิร์ฟเวอร์ (Server IP)</h3>

                    <div class="flex gap-2 bg-black/20 p-2 rounded-xl backdrop-blur-sm border border-white/10">

                        <input type="text" id="serverIpAddress" readonly value="<?= htmlspecialchars($server['serverIP'] ?? '') ?>"

                            class="bg-transparent w-full font-mono text-lg font-bold px-3 border-none focus:ring-0 select-all text-white">

                        <button onclick="copyServerIp()" class="bg-white text-blue-700 font-bold px-6 py-2.5 rounded-lg shadow hover:bg-gray-100 transition whitespace-nowrap text-sm flex items-center gap-1">

                            <i class="fa-solid fa-copy"></i> คัดลอก IP

                        </button>

                    </div>

                </div>



                <div class="card-white p-6 md:p-8">

                    <h2 class="text-xl font-bold mb-4 border-b pb-3 flex items-center gap-2">

                        <i class="fa-solid fa-file-lines text-blue-500"></i> เกี่ยวกับเซิร์ฟเวอร์นี้

                    </h2>

                    <div class="prose max-w-none text-gray-700 leading-relaxed space-y-4">

                        <?= nl2br(htmlspecialchars($server['serverDescription'] ?? 'ไม่มีคำอธิบายเพิ่มเติมสำหรับเซิร์ฟเวอร์นี้')); ?>

                    </div>

                </div>

            </div>



            <div class="space-y-6">

                <div class="card-white p-6">

                    <h3 class="font-bold text-lg mb-4 border-b pb-3"><i class="fa-solid fa-chart-simple text-blue-500 mr-2"></i> ข้อมูลเซิร์ฟเวอร์</h3>

                    <div class="space-y-4 text-sm font-medium">

                        <div class="flex justify-between items-center py-2 border-b border-gray-50">

                            <span class="text-gray-400">ประเภทแนวเกม</span>

                            <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded text-xs font-bold">

                                <?= htmlspecialchars($server['serverCategory'] ?? 'ไม่มีหมวดหมู่') ?>

                            </span>

                        </div>

                        <div class="flex justify-between items-center py-2 border-b border-gray-50">

                            <span class="text-gray-400">อัปเดตล่าสุด</span>

                            <span class="text-gray-700"><?= date('d/m/Y', strtotime($server['updatedAt'] ?? $server['createdAt'] ?? 'now')) ?></span>

                        </div>

                        <div class="flex justify-between items-center py-2 border-b border-gray-50">

                            <span class="text-gray-400">แจ้งปัญหา</span>

                            <a href="<?= base_url('report?serverId=' . (int) $server['serverId']) ?>"
                               class="text-orange-600 hover:text-orange-700 text-xs font-bold inline-flex items-center gap-1">

                                <i class="fa-solid fa-flag"></i> รายงานเซิร์ฟนี้

                            </a>

                        </div>

                        <div class="flex justify-between items-center py-2">

                            <span class="text-gray-400">แชร์หน้านี้</span>

                            <div class="flex gap-2">

                                <button onclick="shareLink()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center text-gray-600" title="คัดลอกลิงก์หน้าเว็บ">

                                    <i class="fa-solid fa-share-nodes text-xs"></i>

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



        </div>

    </div>



    <script>

        function copyServerIp() {

            var copyText = document.getElementById("serverIpAddress");

            copyText.select();

            copyText.setSelectionRange(0, 99999);

            navigator.clipboard.writeText(copyText.value);



            if (typeof showToast === "function") {

                showToast("คัดลอก Server IP สำเร็จแล้ว!", "success");

            } else {

                alert("คัดลอก IP สำเร็จ: " + copyText.value);

            }

        }



        function shareLink() {

            navigator.clipboard.writeText(window.location.href);

            if (typeof showToast === "function") {

                showToast("คัดลอกลิงก์หน้านี้สำเร็จแล้ว นำไปแชร์ต่อได้เลย!", "success");

            } else {

                alert("คัดลอกลิงก์สำเร็จแล้ว!");

            }

        }

    </script>



    <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2"></div>

    <?php include_once("components/footer.php"); ?>

    

    <script src="<?php echo base_url('/js/script.js'); ?>"></script>

</body>



</html>

