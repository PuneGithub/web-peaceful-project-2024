<?php
session_start();
require_once("system/conn.php");
require_once("system/config.php");
require_once("system/resourceSystem.php");

$resourceId = (int) ($_GET['id'] ?? 0);

if ($resourceId <= 0) {
    header('Location: ' . base_url('resources'));
    exit;
}

$resource = fetchApprovedResourceById($conn, $resourceId);

if (!$resource) {
    header('HTTP/1.0 404 Not Found');
    header('Location: ' . base_url('404.php'));
    exit;
}

$categoryLabels = getResourceCategories();
$catLabel = $categoryLabels[$resource['category']] ?? $resource['category'];

$catColor = 'bg-blue-500';
if ($resource['category'] === 'map') {
    $catColor = 'bg-green-500';
} elseif ($resource['category'] === 'resource_pack') {
    $catColor = 'bg-purple-500';
} elseif ($resource['category'] === 'skript') {
    $catColor = 'bg-orange-500';
}

$imagePath = base_url('img/resources/' . ($resource['image'] ?? 'default_resource.webp'));
$descShort = mb_substr(strip_tags($resource['description']), 0, 160);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once __DIR__ . '/components/favicon.php'; ?>
    <title><?= htmlspecialchars($resource['name']) ?> v<?= htmlspecialchars($resource['version']) ?> | Zencrafterly Resources</title>
    <meta name="description" content="<?= htmlspecialchars($descShort) ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="<?= base_url('css/output.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body class="bg-gray-50 text-gray-800">

    <?php include_once("components/header-navbar.php"); ?>

    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <a href="<?= base_url('resources') ?>" class="inline-flex items-center text-blue-600 hover:underline mb-6 text-sm font-medium">
            <i class="fa-solid fa-arrow-left mr-2"></i> กลับหน้า Resources
        </a>

        <div class="card-white overflow-hidden border border-gray-100 p-0">
            <div class="relative h-56 md:h-72 bg-gray-100">
                <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($resource['name']) ?>"
                     class="w-full h-full object-cover"
                     onerror="this.onerror=null; this.src='<?= base_url('img/blogs_image/default.webp') ?>'">
                <span class="absolute top-4 left-4 <?= $catColor ?> text-white text-xs font-bold px-3 py-1 rounded-full shadow">
                    <?= htmlspecialchars($catLabel) ?>
                </span>
                <span class="absolute top-4 right-4 bg-white/90 text-gray-800 text-sm font-bold px-3 py-1 rounded-full shadow">
                    v<?= htmlspecialchars($resource['version']) ?>
                </span>
            </div>

            <div class="p-6 md:p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-3"><?= htmlspecialchars($resource['name']) ?></h1>

                <div class="flex flex-wrap gap-4 text-sm text-gray-500 mb-6">
                    <span><i class="fa-solid fa-user mr-1"></i> <?= htmlspecialchars($resource['author'] ?? 'Zencrafterly') ?></span>
                    <span><i class="fa-solid fa-download mr-1"></i> <?= number_format($resource['downloads'] ?? 0) ?> ดาวน์โหลด</span>
                    <span><i class="fa-solid fa-clock mr-1"></i> อัปเดต <?= date('d M Y', strtotime($resource['updatedAt'])) ?></span>
                </div>

                <div class="prose prose-gray max-w-none text-gray-700 leading-relaxed whitespace-pre-wrap mb-8">
                    <?= nl2br(htmlspecialchars($resource['description'])) ?>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-100">
                    <a href="<?= resourceDownloadUrl($resource['resourceId']) ?>"
                       class="flex-1 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-center transition shadow-lg flex items-center justify-center gap-2">
                        <i class="fa-solid fa-download"></i> ดาวน์โหลดเลย
                    </a>
                    <a href="<?= base_url('resources') ?>"
                       class="py-4 px-6 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-center transition">
                        ดูรายการอื่น
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php include_once("components/footer.php"); ?>
</body>
</html>
