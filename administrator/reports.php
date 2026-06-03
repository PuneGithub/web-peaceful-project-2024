<?php
session_start();
require_once("../system/conn.php");
require_once("../system/config.php");
require_once("../system/reportSystem.php");

const REPORTS_PER_PAGE = 15;

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../account/login.php');
    exit;
}

$statusFilter = $_GET['status'] ?? '';
if ($statusFilter !== '' && !isValidReportStatus($statusFilter)) {
    $statusFilter = '';
}

$page = max(1, (int) ($_GET['page'] ?? 1));

function reportsRedirectUrl($statusFilter = '', $page = 1)
{
    $params = [];
    if ($statusFilter !== '' && isValidReportStatus($statusFilter)) {
        $params['status'] = $statusFilter;
    }
    if ($page > 1) {
        $params['page'] = $page;
    }

    $url = 'reports.php';
    if ($params !== []) {
        $url .= '?' . http_build_query($params);
    }
    return $url;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['reportId'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        die('ตรวจพบการส่งข้อมูลที่ไม่ปลอดภัย (CSRF Token Mismatch)');
    }

    $reportId = (int) $_POST['reportId'];
    $action = $_POST['action'];
    $redirectFilter = $_POST['status_filter'] ?? '';
    $redirectPage = max(1, (int) ($_POST['page'] ?? 1));

    if ($action === 'resolve') {
        updateReportStatus($conn, $reportId, 'resolved');
    } elseif ($action === 'dismiss') {
        updateReportStatus($conn, $reportId, 'dismissed');
    } elseif ($action === 'delete') {
        deleteReport($conn, $reportId);
    }

    header('Location: ' . reportsRedirectUrl($redirectFilter, $redirectPage));
    exit;
}

$statusLabels = getReportStatusLabels();
$statusForQuery = $statusFilter !== '' ? $statusFilter : null;

$totalAll = countReports($conn, null);
$pendingCount = countReports($conn, 'pending');
$totalFiltered = countReports($conn, $statusForQuery);
$statusCounts = [
    ''          => $totalAll,
    'pending'   => $pendingCount,
    'resolved'  => countReports($conn, 'resolved'),
    'dismissed' => countReports($conn, 'dismissed'),
];
$totalPages = max(1, (int) ceil($totalFiltered / REPORTS_PER_PAGE));

if ($page > $totalPages) {
    $page = $totalPages;
}

$offset = ($page - 1) * REPORTS_PER_PAGE;
$reports = fetchReports($conn, $statusForQuery, REPORTS_PER_PAGE, $offset);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once __DIR__ . '/../components/favicon.php'; ?>
    <title>จัดการการแจ้งปัญหา | Admin Panel</title>
    <link rel="stylesheet" href="<?= base_url('css/output.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="flex">
        <?php include_once('../components/header-admin.php'); ?>

        <div class="flex-1 p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold flex items-center gap-2">
                    <i class="fa-solid fa-bug text-red-500"></i> รายการแจ้งปัญหาจากผู้ใช้
                </h1>
                <a href="dashboard.php" class="text-blue-600 hover:underline">กลับหน้า Dashboard</a>
            </div>

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 sm:col-span-4 space-y-4">
                    <div class="card-white">
                        <h2 class="font-bold text-lg">รายงานทั้งหมด: <?= $totalAll ?></h2>
                        <?php if ($pendingCount > 0): ?>
                            <p class="text-sm text-yellow-600 mt-2">
                                <i class="fa-solid fa-clock mr-1"></i> รอตรวจสอบ: <?= $pendingCount ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="card-white">
                        <p class="text-sm font-bold text-gray-600 mb-2">กรองตามสถานะ</p>
                        <div class="flex flex-col gap-1">
                            <?php foreach ($statusLabels as $key => $label): ?>
                                <?php
                                $filterUrl = reportsRedirectUrl($key, 1);
                                $isActive = $statusFilter === $key;
                                $countForLabel = $statusCounts[$key] ?? 0;
                                ?>
                                <a href="<?= htmlspecialchars($filterUrl) ?>"
                                   class="text-sm px-3 py-2 rounded-lg transition flex items-center justify-between <?= $isActive ? 'bg-blue-600 text-white font-bold' : 'text-gray-600 hover:bg-gray-100' ?>">
                                    <span><?= htmlspecialchars($label) ?></span>
                                    <span class="text-xs <?= $isActive ? 'text-blue-100' : 'text-gray-400' ?>"><?= $countForLabel ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-8">
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-3 border-b bg-gray-50">
                            <span class="text-sm text-gray-600">
                                <?php if ($statusFilter !== ''): ?>
                                    แสดง: <?= htmlspecialchars($statusLabels[$statusFilter] ?? $statusFilter) ?>
                                <?php else: ?>
                                    แสดง: ทั้งหมด
                                <?php endif; ?>
                                (<?= $totalFiltered ?> รายการ)
                            </span>
                            <?php if ($totalPages > 1): ?>
                                <span class="text-xs text-gray-500">
                                    หน้า <?= $page ?> / <?= $totalPages ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[900px]">
                                <thead>
                                    <tr class="bg-gray-50 text-gray-600 text-sm border-b">
                                        <th class="p-4 font-semibold">ผู้แจ้ง</th>
                                        <th class="p-4 font-semibold">IP Address</th>
                                        <th class="p-4 font-semibold">ประเภท</th>
                                        <th class="p-4 font-semibold">เซิร์ฟเวอร์</th>
                                        <th class="p-4 font-semibold">หัวข้อ & รายละเอียด</th>
                                        <th class="p-4 font-semibold">รูปภาพ</th>
                                        <th class="p-4 font-semibold text-center">สถานะ</th>
                                        <th class="p-4 font-semibold text-center">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php if (count($reports) > 0): ?>
                                        <?php foreach ($reports as $r): ?>
                                            <tr class="border-b hover:bg-gray-50 transition">
                                                <td class="p-4">
                                                    <span class="font-bold text-gray-700"><?= htmlspecialchars($r['username'] ?? 'บุคคลทั่วไป') ?></span><br>
                                                    <span class="text-xs text-gray-400"><?= date('d/m/Y H:i', strtotime($r['createdAt'])) ?></span>
                                                </td>
                                                <td class="p-4">
                                                    <?php if (!empty($r['ipAddress'])): ?>
                                                        <span class="text-xs font-mono bg-gray-100 text-gray-600 px-2 py-1 rounded"><?= htmlspecialchars($r['ipAddress']) ?></span>
                                                    <?php else: ?>
                                                        <span class="text-gray-400 text-xs">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="p-4">
                                                    <?php
                                                    $types = [
                                                        'bug' => '<span class="text-red-500"><i class="fa-solid fa-bug"></i> บั๊ก</span>',
                                                        'suggestion' => '<span class="text-yellow-500"><i class="fa-solid fa-lightbulb"></i> เสนอแนะ</span>',
                                                        'user_report' => '<span class="text-orange-500"><i class="fa-solid fa-triangle-exclamation"></i> รีพอร์ต</span>',
                                                        'other' => '<span class="text-gray-500"><i class="fa-solid fa-comment"></i> อื่นๆ</span>',
                                                    ];
                                                    echo $types[$r['type']] ?? htmlspecialchars($r['type']);
                                                    ?>
                                                </td>
                                                <td class="p-4">
                                                    <?php if (!empty($r['serverId']) && !empty($r['serverName'])): ?>
                                                        <a href="<?= base_url('server/' . htmlspecialchars($r['serverSlug'] ?? '', ENT_QUOTES, 'UTF-8')) ?>"
                                                           class="text-blue-600 hover:underline font-semibold text-xs block truncate max-w-[140px]"
                                                           title="<?= htmlspecialchars($r['serverName']) ?>">
                                                            <?= htmlspecialchars($r['serverName']) ?>
                                                        </a>
                                                        <span class="text-[10px] text-gray-400 font-mono">#<?= (int) $r['serverId'] ?></span>
                                                    <?php else: ?>
                                                        <span class="text-gray-400 text-xs">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="p-4 max-w-xs">
                                                    <p class="font-bold text-gray-800 truncate"><?= htmlspecialchars($r['topic']) ?></p>
                                                    <p class="text-gray-500 text-xs mt-1 truncate" title="<?= htmlspecialchars($r['detail']) ?>">
                                                        <?= htmlspecialchars($r['detail']) ?>
                                                    </p>
                                                </td>
                                                <td class="p-4">
                                                    <?php if (!empty($r['image'])): ?>
                                                        <a href="<?= base_url('img/reports/' . $r['image']) ?>" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:underline text-xs">
                                                            <i class="fa-solid fa-image"></i> ดูรูปภาพ
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-gray-400 text-xs">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="p-4 text-center">
                                                    <?php if ($r['status'] === 'pending'): ?>
                                                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">รอตรวจสอบ</span>
                                                    <?php elseif ($r['status'] === 'resolved'): ?>
                                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">แก้ไขแล้ว</span>
                                                    <?php else: ?>
                                                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-bold">เพิกเฉย</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="p-4 text-center">
                                                    <div class="flex items-center justify-center gap-2">
                                                        <?php if ($r['status'] === 'pending'): ?>
                                                            <form method="POST" class="inline" onsubmit="return confirm('ยืนยันว่าปัญหานี้ได้รับการแก้ไขแล้ว?')">
                                                                <?= csrfField() ?>
                                                                <input type="hidden" name="reportId" value="<?= (int) $r['reportId'] ?>">
                                                                <input type="hidden" name="status_filter" value="<?= htmlspecialchars($statusFilter) ?>">
                                                                <input type="hidden" name="page" value="<?= $page ?>">
                                                                <button type="submit" name="action" value="resolve" class="bg-green-500 text-white w-8 h-8 rounded-lg flex items-center justify-center hover:bg-green-600 transition" title="ทำเครื่องหมายว่าแก้ไขแล้ว">
                                                                    <i class="fa-solid fa-check"></i>
                                                                </button>
                                                            </form>
                                                            <form method="POST" class="inline" onsubmit="return confirm('เพิกเฉยปัญหา/สแปมนี้?')">
                                                                <?= csrfField() ?>
                                                                <input type="hidden" name="reportId" value="<?= (int) $r['reportId'] ?>">
                                                                <input type="hidden" name="status_filter" value="<?= htmlspecialchars($statusFilter) ?>">
                                                                <input type="hidden" name="page" value="<?= $page ?>">
                                                                <button type="submit" name="action" value="dismiss" class="bg-gray-300 text-gray-700 w-8 h-8 rounded-lg flex items-center justify-center hover:bg-gray-400 transition" title="เพิกเฉย / สแปม">
                                                                    <i class="fa-solid fa-xmark"></i>
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                        <form method="POST" class="inline" onsubmit="return confirm('ลบรายงานนี้ถาวร? ข้อมูลและรูปภาพจะถูกลบและกู้คืนไม่ได้')">
                                                            <?= csrfField() ?>
                                                            <input type="hidden" name="reportId" value="<?= (int) $r['reportId'] ?>">
                                                            <input type="hidden" name="status_filter" value="<?= htmlspecialchars($statusFilter) ?>">
                                                            <input type="hidden" name="page" value="<?= $page ?>">
                                                            <button type="submit" name="action" value="delete" class="bg-red-500 text-white w-8 h-8 rounded-lg flex items-center justify-center hover:bg-red-600 transition" title="ลบรายงานถาวร">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="p-8 text-center text-gray-500">
                                                <i class="fa-solid fa-inbox text-4xl mb-3 text-gray-300 block"></i>
                                                ไม่มีรายงาน<?= $statusFilter !== '' ? ' ในสถานะนี้' : '' ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if ($totalPages > 1): ?>
                            <div class="flex flex-wrap items-center justify-center gap-2 px-4 py-4 border-t bg-gray-50">
                                <?php if ($page > 1): ?>
                                    <a href="<?= htmlspecialchars(reportsRedirectUrl($statusFilter, $page - 1)) ?>"
                                       class="px-3 py-1.5 text-sm rounded-lg border bg-white text-gray-600 hover:bg-gray-100 transition">
                                        <i class="fa-solid fa-chevron-left mr-1"></i> ก่อนหน้า
                                    </a>
                                <?php endif; ?>

                                <?php
                                $startPage = max(1, $page - 2);
                                $endPage = min($totalPages, $page + 2);
                                for ($i = $startPage; $i <= $endPage; $i++):
                                    $isCurrent = $i === $page;
                                ?>
                                    <a href="<?= htmlspecialchars(reportsRedirectUrl($statusFilter, $i)) ?>"
                                       class="min-w-[2.25rem] text-center px-3 py-1.5 text-sm rounded-lg border transition <?= $isCurrent ? 'bg-blue-600 text-white border-blue-600 font-bold' : 'bg-white text-gray-600 hover:bg-gray-100' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>

                                <?php if ($page < $totalPages): ?>
                                    <a href="<?= htmlspecialchars(reportsRedirectUrl($statusFilter, $page + 1)) ?>"
                                       class="px-3 py-1.5 text-sm rounded-lg border bg-white text-gray-600 hover:bg-gray-100 transition">
                                        ถัดไป <i class="fa-solid fa-chevron-right ml-1"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
