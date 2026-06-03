<?php
require_once '../system/administratorSystem.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

//ข้อความเมื่อกดปุ่ม Delete
$msgBlog = null;

//Delete Blogs
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['blogId'])) {
    $blogId = $_POST['blogId'];
    if (deleteBlog($conn, $blogId)) {
        $msgBlog = "<div class='alert-green'>ลบบล็อกสำเร็จแล้ว</div>";
    } else {
        $msgBlog = "<div class='alert-danger'>เกิดข้อผิดพลาดในการลบบล็อก</div>";
    }
}



$fetchAllBlogs = fetchAllBlogs($conn);
$totalBlogs = is_array($fetchAllBlogs) ? count($fetchAllBlogs) : 0;

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
    <title>Manage Posts</title>
</head>

<body>
    <div class="flex">
        <?php include_once('../components/header-admin.php'); ?>

        <!-- Content -->
        <div class="flex-1 p-6">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 sm:col-span-4">
                    <div class="card-white">
                        <h2 class="font-bold text-lg">จำนวนบทความ: <?php echo $totalBlogs; ?> บทความ</h2>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-8">
                    <div class="card-white">
                        <?php if ($msgBlog) {
                            echo $msgBlog;
                        } ?>
                        <div class="flex items-center">
                            <a href="addBlog.php" class="btn btn-blue-500">New Blog</a>
                            <h2 class="font-bold flex-1 text-center text-xl">Manage Blogs</h2>
                        </div>
                        <div class="overflow-x-auto mt-3">
                            <table class="table-auto w-full border border-slate-400 border-collapse text-center">

                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="p-2 border">ID</th>
                                        <th class="p-2 border">หมวดหมู่</th>
                                        <th class="p-2 border">ชื่อบทความ</th>
                                        <th class="p-2 border">สถิติ</th>
                                        <th class="p-2 border">SEO</th>
                                        <th class="p-2 border">วันที่</th>
                                        <th class="p-2 border">จัดการ</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($fetchAllBlogs as $blog):
                                        $hasSEO = (!empty($blog['seo_title']) && !empty($blog['seo_description']));
                                    ?>
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="p-2 border"><?= $blog['blogId'] ?></td>
                                            <td class="p-3">
                                                <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded-md text-[10px] font-bold uppercase">
                                                    <?= htmlspecialchars($blog['categoryName'] ?? 'ไม่มีหมวดหมู่') ?>
                                                </span>
                                            </td>
                                            <td class="p-2 border text-left font-medium px-4"><?= htmlspecialchars($blog['blogTitle']) ?></td>

                                            <td class="p-2 border text-xs">
                                                <i class="fa-solid fa-eye mr-1 text-gray-400"></i> <?= number_format($blog['views']) ?>
                                            </td>
                                            <td class="p-2 border">
                                                <i class="fa-solid <?= $hasSEO ? 'fa-circle-check text-green-500' : 'fa-circle-info text-gray-300' ?>" title="<?= $hasSEO ? 'SEO ทำแล้ว' : 'ยังไม่ได้ทำ SEO' ?>"></i>
                                            </td>

                                            <td class="p-2 border text-[10px] text-gray-500"><?= $blog['createdAt'] ?></td>

                                            <td class="p-2 border">
                                                <div class="flex gap-3 justify-center">
                                                    <a href="editBlog.php?blogId=<?= $blog['blogId'] ?>" class="text-orange-500 hover:text-orange-700 transition" title="แก้ไข">
                                                        <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                                    </a>
                                                    <form action="" method="post" class="inline" onsubmit="return confirm('ยืนยันการลบโพสต์นี้ใช่ไหม?');">
                                                        <input type="hidden" name="blogId" value="<?= $blog['blogId'] ?>">
                                                        <button type="submit" class="text-red-500 hover:text-red-700 transition" title="ลบ">
                                                            <i class="fa-solid fa-trash fa-lg"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
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