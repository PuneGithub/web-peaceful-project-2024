<?php
require_once "system/conn.php";
require_once "system/config.php";
require_once "system/blogSystem.php";
session_start();

$search = trim($_GET['q'] ?? '');
$categoryId = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? 'latest';
if (!in_array($sort, ['latest', 'popular'], true)) {
    $sort = 'latest';
}

$hasFilter = $search !== '' || $categoryId !== '';
$allBlogs = fetchAllBlogs(
    $conn,
    $sort,
    null,
    0,
    $search !== '' ? $search : null,
    $categoryId !== '' ? $categoryId : null
);
$categories = getCategory($conn);

$featuredBlog = null;
$gridBlogs = $allBlogs;

if (!$hasFilter) {
    $featuredBlog = fetchLatestBlog($conn);
    if ($featuredBlog) {
        $featuredId = (int) $featuredBlog['blogId'];
        $gridBlogs = array_values(array_filter(
            $allBlogs,
            static fn(array $blog): bool => (int) $blog['blogId'] !== $featuredId
        ));
    }
}

$defaultImage = base_url('img/blogs_image/default.webp');
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once __DIR__ . '/components/favicon.php'; ?>
    <title>บทความ Minecraft | Zencrafterly</title>
    <meta name="description" content="อ่านบทความ คู่มือ และเทคนิค Minecraft จาก Zencrafterly — การเปิดเซิร์ฟ โปรโมทเซิร์ฟเวอร์ และความรู้สำหรับผู้เล่น">
    <link rel="canonical" href="<?= htmlspecialchars(absolute_url('blogs'), ENT_QUOTES, 'UTF-8') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?= base_url('css/output.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>

<body class="bg-gray-50 text-gray-800">
    <?php include_once("components/header-navbar.php"); ?>

    <div class="bg-gradient-to-r from-blue-900 to-indigo-800 text-white py-16 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4">บทความ Minecraft</h1>
            <p class="text-blue-200 text-lg md:text-xl max-w-2xl mx-auto mb-8">
                คู่มือ ทิป และความรู้สำหรับเจ้าของเซิร์ฟและผู้เล่น — เปิดเซิร์ฟ ปลั๊กอิน และเทคนิคที่ใช้ได้จริง
            </p>

            <form action="" method="GET" class="max-w-3xl mx-auto flex flex-col md:flex-row gap-3">
                <?php if ($sort !== 'latest'): ?>
                    <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
                <?php endif; ?>
                <?php if ($categoryId !== ''): ?>
                    <input type="hidden" name="category" value="<?= htmlspecialchars($categoryId) ?>">
                <?php endif; ?>
                <div class="flex-grow relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="q" value="<?= htmlspecialchars($search) ?>"
                        placeholder="ค้นหาชื่อบทความหรือเนื้อหา..."
                        class="w-full pl-12 pr-4 py-4 bg-white text-gray-900 rounded-xl shadow-md border-0 focus:outline-none focus:ring-4 focus:ring-blue-400/50 placeholder-gray-400 transition">
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-4 px-8 rounded-xl transition shadow-md hover:shadow-lg border border-blue-400/50">
                    ค้นหา
                </button>
            </form>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">

        <div class="flex flex-wrap gap-2 mb-8">
            <a href="blogs.php<?= $sort !== 'latest' ? '?sort=' . urlencode($sort) : '' ?>"
                class="px-5 py-2 rounded-full border text-sm font-bold shadow-sm transition <?= $categoryId === '' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100' ?>">
                ทั้งหมด
            </a>
            <?php foreach ($categories as $cat): ?>
                <?php
                $catParams = ['category' => $cat['categoryId']];
                if ($search !== '') {
                    $catParams['q'] = $search;
                }
                if ($sort !== 'latest') {
                    $catParams['sort'] = $sort;
                }
                $catHref = 'blogs.php?' . http_build_query($catParams);
                $isActive = (string) $categoryId === (string) $cat['categoryId'];
                ?>
                <a href="<?= htmlspecialchars($catHref) ?>"
                    class="px-5 py-2 rounded-full border text-sm font-bold shadow-sm transition <?= $isActive ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100' ?>">
                    <?= htmlspecialchars($cat['categoryName']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if ($featuredBlog): ?>
            <?php
            $featuredImage = blogImagePath($featuredBlog);
            $featuredSlug = htmlspecialchars($featuredBlog['slug'], ENT_QUOTES, 'UTF-8');
            ?>
            <article class="mb-6 max-w-4xl mx-auto bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition duration-300">
                <div class="flex flex-col md:flex-row">
                    <a href="<?= base_url('blog/' . $featuredSlug) ?>" class="block w-full md:w-2/5 aspect-video bg-gray-100 overflow-hidden group shrink-0">
                        <img src="<?= base_url($featuredImage) ?>"
                            alt="<?= htmlspecialchars($featuredBlog['blogTitle']) ?>"
                            class="w-full h-full object-contain group-hover:scale-[1.02] transition duration-500"
                            onerror="this.onerror=null; this.src='<?= $defaultImage ?>';">
                    </a>
                    <div class="md:w-3/5 p-4 md:p-5 flex flex-col justify-center">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-full uppercase tracking-wide">
                                บทความล่าสุด
                            </span>
                            <?php if (!empty($featuredBlog['categoryName'])): ?>
                                <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-full">
                                    <?= htmlspecialchars($featuredBlog['categoryName']) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <h2 class="text-lg md:text-xl font-bold text-gray-900 leading-snug mb-2 line-clamp-2">
                            <a href="<?= base_url('blog/' . $featuredSlug) ?>" class="hover:text-blue-600 transition">
                                <?= htmlspecialchars($featuredBlog['blogTitle']) ?>
                            </a>
                        </h2>
                        <p class="text-sm text-gray-600 leading-relaxed mb-3 line-clamp-2">
                            <?= htmlspecialchars(blogExcerpt($featuredBlog, 120)) ?>
                        </p>
                        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-400 mb-3">
                            <?php if (!empty($featuredBlog['username'])): ?>
                                <span><i class="fa-solid fa-user mr-1"></i><?= htmlspecialchars($featuredBlog['username']) ?></span>
                            <?php endif; ?>
                            <span><i class="fa-solid fa-calendar mr-1"></i><?= formatBlogDate($featuredBlog['createdAt'] ?? null) ?></span>
                            <span><i class="fa-solid fa-eye mr-1"></i><?= number_format((int) ($featuredBlog['views'] ?? 0)) ?> ครั้ง</span>
                        </div>
                        <a href="<?= base_url('blog/' . $featuredSlug) ?>"
                            class="inline-flex items-center gap-2 self-start bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 text-sm rounded-lg transition shadow-sm">
                            อ่านต่อ <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </article>
        <?php endif; ?>

        <div class="card-white rounded-2xl border border-gray-100 p-4 md:p-6">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 border-b border-gray-100 pb-4">
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fa-solid fa-newspaper text-blue-600 mr-2"></i>
                    <?= $hasFilter ? 'ผลการค้นหา' : 'บทความทั้งหมด' ?>
                    <span class="ml-2 bg-blue-100 text-blue-600 text-sm font-bold px-3 py-1 rounded-full">
                        <?= count($gridBlogs) ?> บทความ
                    </span>
                </h2>

                <form action="" method="GET" id="blogSortForm" class="flex items-center gap-3">
                    <?php if ($search !== ''): ?>
                        <input type="hidden" name="q" value="<?= htmlspecialchars($search) ?>">
                    <?php endif; ?>
                    <?php if ($categoryId !== ''): ?>
                        <input type="hidden" name="category" value="<?= htmlspecialchars($categoryId) ?>">
                    <?php endif; ?>
                    <span class="text-sm text-gray-500">เรียงตาม:</span>
                    <select name="sort" onchange="document.getElementById('blogSortForm').submit();"
                        class="bg-white border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none cursor-pointer">
                        <option value="latest" <?= $sort === 'latest' ? 'selected' : '' ?>>ล่าสุด</option>
                        <option value="popular" <?= $sort === 'popular' ? 'selected' : '' ?>>ยอดนิยม (ยอดวิว)</option>
                    </select>
                </form>
            </div>

        <?php if (!empty($gridBlogs)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($gridBlogs as $blog): ?>
                    <?php
                    $imagePath = blogImagePath($blog);
                    $slug = htmlspecialchars($blog['slug'], ENT_QUOTES, 'UTF-8');
                    ?>
                    <article class="card-white flex flex-col h-full overflow-hidden hover:-translate-y-1 hover:shadow-xl transition duration-300 border border-gray-100 p-0">
                        <a href="<?= base_url('blog/' . $slug) ?>" class="block relative aspect-video overflow-hidden group">
                            <img src="<?= base_url($imagePath) ?>"
                                alt="<?= htmlspecialchars($blog['blogTitle']) ?>"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                onerror="this.onerror=null; this.src='<?= $defaultImage ?>';">
                            <?php if (!empty($blog['categoryName'])): ?>
                                <span class="absolute top-3 left-3 bg-blue-600 text-white text-[10px] font-black px-3 py-1 rounded-full shadow-md uppercase tracking-wider">
                                    <?= htmlspecialchars($blog['categoryName']) ?>
                                </span>
                            <?php endif; ?>
                        </a>

                        <div class="p-5 flex flex-col flex-grow">
                            <h3 class="text-lg font-bold text-gray-900 hover:text-blue-600 transition line-clamp-2 mb-2 leading-snug">
                                <a href="<?= base_url('blog/' . $slug) ?>"><?= htmlspecialchars($blog['blogTitle']) ?></a>
                            </h3>

                            <p class="text-gray-500 text-sm line-clamp-3 mb-4 flex-grow leading-relaxed">
                                <?= htmlspecialchars(blogExcerpt($blog)) ?>
                            </p>

                            <div class="flex items-center justify-between border-t border-gray-50 pt-4 mt-auto">
                                <div class="text-xs text-gray-400 font-medium flex flex-col gap-1">
                                    <span><i class="fa-solid fa-calendar mr-1"></i><?= formatBlogDate($blog['createdAt'] ?? null) ?></span>
                                    <span><i class="fa-solid fa-eye mr-1"></i><?= number_format((int) ($blog['views'] ?? 0)) ?> ครั้ง</span>
                                </div>
                                <a href="<?= base_url('blog/' . $slug) ?>"
                                    class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition font-bold py-2 px-4 rounded-lg text-sm flex items-center gap-2 whitespace-nowrap">
                                    อ่านต่อ <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-16 text-gray-500">
                <i class="fa-solid fa-newspaper text-5xl mb-4 text-gray-300 block"></i>
                <p class="text-lg font-bold text-gray-600">ไม่พบบทความ</p>
                <p class="text-sm mt-1">
                    <?php if ($hasFilter): ?>
                        ลองเปลี่ยนคำค้นหาหรือหมวดหมู่อีกครั้ง
                    <?php else: ?>
                        ยังไม่มีบทความในขณะนี้
                    <?php endif; ?>
                </p>
                <?php if ($hasFilter): ?>
                    <a href="blogs.php" class="inline-block mt-4 text-blue-600 font-semibold hover:underline">ดูบทความทั้งหมด</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        </div>

    </div>

    <?php include_once("components/footer.php"); ?>
    <script src="<?= base_url('js/script.js') ?>"></script>
</body>

</html>
