<?php
session_start();
require_once("system/conn.php");
require_once("system/config.php");
require_once("system/resourceSystem.php"); // ดึงระบบมาใช้

// รับค่าจากการค้นหาและจัดเรียง (ถ้ามี)
$search = $_GET['q'] ?? '';
$category = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? 'latest';

// ดึงข้อมูลจาก Database จริงๆ
$resources = fetchAllResources($conn, $search, $category, $sort);
$categoryLabels = getResourceCategories();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once __DIR__ . '/components/favicon.php'; ?>
    <title>แหล่งรวมปลั๊กอินและทรัพยากร Minecraft | Zencrafterly</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="<?= base_url('css/output.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <script>
        const BASE_URL = "<?= base_url() ?>";
    </script>
</head>

<body class="bg-gray-50 text-gray-800">

    <?php include_once("components/header-navbar.php"); ?>

    <div class="bg-gradient-to-r from-blue-900 to-indigo-800 text-white py-16 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Minecraft Resources</h1>
            <p class="text-blue-200 text-lg md:text-xl max-w-2xl mx-auto mb-8">
                ศูนย์รวม ปลั๊กอิน ม็อด แผนที่ และ Resource Pack สำหรับเซิร์ฟเวอร์ของคุณ ดาวน์โหลดฟรี!
            </p>

            <form action="" method="GET" class="max-w-3xl mx-auto flex flex-col md:flex-row gap-3">
                <?php if (!empty($sort) && $sort !== 'latest'): ?>
                    <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
                <?php endif; ?>
                <div class="flex-grow relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="ค้นหาปลั๊กอิน, ผู้สร้าง, หรือคีย์เวิร์ด..."
                        class="w-full pl-12 pr-4 py-4 bg-white text-gray-900 rounded-xl shadow-md border-0 focus:outline-none focus:ring-4 focus:ring-blue-400/50 placeholder-gray-400 transition">
                </div>

                <select name="category" class="py-4 px-6 bg-white text-gray-800 rounded-xl shadow-md border-0 focus:outline-none focus:ring-4 focus:ring-blue-400/50 transition cursor-pointer">
                    <option value="">ทุกหมวดหมู่</option>
                    <?php foreach ($categoryLabels as $key => $label): ?>
                        <option value="<?= $key ?>" <?= $category === $key ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-4 px-8 rounded-xl transition shadow-md hover:shadow-lg border border-blue-400/50 flex items-center justify-center gap-2">
                    ค้นหา
                </button>
            </form>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">

        <div class="card-white rounded-2xl border border-gray-100 p-4 md:p-6">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 border-b border-gray-100 pb-4">
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fa-solid fa-layer-group text-blue-600 mr-2"></i>อัปเดตล่าสุด
                    <span class="ml-2 bg-blue-100 text-blue-600 text-sm font-bold px-3 py-1 rounded-full">
                        <?= count($resources) ?> รายการ
                    </span>
                </h2>

                <form action="" method="GET" id="filterForm" class="flex items-center gap-3">
                    <?php if (!empty($search)): ?> <input type="hidden" name="q" value="<?= htmlspecialchars($search) ?>"> <?php endif; ?>
                    <?php if (!empty($category)): ?> <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>"> <?php endif; ?>

                    <span class="text-sm text-gray-500">เรียงตาม:</span>
                    <select name="sort" onchange="document.getElementById('filterForm').submit();" class="bg-white border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none cursor-pointer">
                        <option value="latest" <?= $sort == 'latest' ? 'selected' : '' ?>>อัปเดตล่าสุด</option>
                        <option value="popular" <?= $sort == 'popular' ? 'selected' : '' ?>>ดาวน์โหลดสูงสุด</option>
                        <option value="alphabet" <?= $sort == 'alphabet' ? 'selected' : '' ?>>ตัวอักษร (A-Z)</option>
                    </select>
                </form>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (count($resources) > 0): ?>
                <?php foreach ($resources as $res): ?>
                    <div class="card-white flex flex-col h-full overflow-hidden hover:-translate-y-1 hover:shadow-xl transition duration-300 border border-gray-100 p-0">

                        <div class="relative h-48 bg-gray-100 border-b border-gray-100">
                            <img src="<?= base_url('img/resources/' . htmlspecialchars($res['image'] ?? 'default_resource.webp')) ?>" alt="<?= htmlspecialchars($res['name']) ?>" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='<?= base_url('img/blogs_image/default.webp') ?>'">

                            <?php
                            $catColor = 'bg-blue-500';
                            if ($res['category'] == 'map') $catColor = 'bg-green-500';
                            if ($res['category'] == 'resource_pack') $catColor = 'bg-purple-500';
                            if ($res['category'] == 'skript') $catColor = 'bg-orange-500';
                            ?>
                            <span class="absolute top-3 left-3 <?= $catColor ?> text-white text-[10px] font-black px-3 py-1 rounded-full shadow-md uppercase tracking-wider">
                                <?= htmlspecialchars($categoryLabels[$res['category']] ?? $res['category']) ?>
                            </span>

                            <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-gray-800 text-xs font-bold px-2.5 py-1 rounded-full shadow-sm">
                                v<?= htmlspecialchars($res['version']) ?>
                            </span>
                        </div>

                        <div class="p-5 flex flex-col flex-grow">
                            <h3 class="text-xl font-bold text-gray-900 hover:text-blue-600 transition line-clamp-1 mb-1">
                                <a href="<?= resourceDetailUrl($res['resourceId']) ?>"><?= htmlspecialchars($res['name']) ?></a>
                            </h3>

                            <div class="text-xs font-medium text-gray-400 mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-user text-gray-300"></i> <?= htmlspecialchars($res['author'] ?? 'ไม่ทราบชื่อ') ?>
                            </div>

                            <p class="text-gray-500 text-sm line-clamp-3 mb-5 flex-grow leading-relaxed">
                                <?= htmlspecialchars($res['description']) ?>
                            </p>

                            <div class="flex items-center justify-between border-t border-gray-50 pt-4 mt-auto">
                                <div class="text-xs text-gray-400 font-medium flex gap-3">
                                    <span title="ยอดดาวน์โหลด"><i class="fa-solid fa-download mr-1"></i> <?= number_format($res['downloads'] ?? 0) ?></span>
                                    <span title="อัปเดตล่าสุด"><i class="fa-solid fa-clock mr-1"></i> <?= date('d M Y', strtotime($res['updatedAt'])) ?></span>
                                </div>
                                <a href="<?= resourceDownloadUrl($res['resourceId']) ?>" class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition font-bold py-2 px-4 rounded-lg text-sm flex items-center gap-2">
                                    <i class="fa-solid fa-arrow-down"></i> โหลด
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-16 text-gray-500">
                    <i class="fa-solid fa-box-open text-5xl mb-4 text-gray-300 block"></i>
                    <p class="text-lg font-bold text-gray-600">ยังไม่มีข้อมูล Resources ในขณะนี้</p>
                    <p class="text-sm mt-1">หรือลองเปลี่ยนคำค้นหา / หมวดหมู่ดูอีกครั้งนะครับ</p>
                </div>
            <?php endif; ?>
            </div>
        </div>

    </div>

    <?php include_once("components/footer.php"); ?>

    <script src="<?= base_url('js/script.js') ?>"></script>
</body>

</html>