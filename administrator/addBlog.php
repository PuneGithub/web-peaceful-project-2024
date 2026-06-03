<?php
require_once '../system/administratorSystem.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
}

//ดึงหมวดหมู่มาแสดง
$getCategory = getCategory($conn);

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['btnBlog'])) {

    $userId = $_SESSION['userId'];
    $blogTitle = $_POST['blogTitle'];
    $blogContent = $_POST['blogContent'];
    $blogUrl = $_POST['blogUrl'];

    //รับค่าจากระบบ SEO
    $seo_title = $_POST['seo_title'];
    $seo_description = $_POST['seo_description'];
    $seo_keywords = $_POST['seo_keywords'];

    $categoryId = $_POST['blogCategory'];

    // หาชื่อหมวดหมู่ (เช่น papermc, plugin) เพื่อใช้ในการเลือก Path เก็บรูป
    $blogCategoryStr = '';
    foreach($getCategory as $cat) {
        if($cat['categoryId'] == $categoryId) {
            $blogCategoryStr = $cat['categoryName']; // สมมติว่าเก็บชื่อหมวดหมู่ไว้ในฟิลด์นี้
            break;
        }
    }

    $newImage = $_FILES['blogImage'];
    $slug = createSlug($blogUrl);

    $blogResult = createBlog($conn, $userId, $blogTitle, $blogContent, $newImage, $slug, $categoryId, $blogCategoryStr, $seo_title, $seo_description, $seo_keywords);
}
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

<body class="bg-gray-50">
    <div class="flex">
        <?php include_once('../components/header-admin.php'); ?>

        <div class="flex-1 p-6">
            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-12 gap-6">
                    
                    <div class="col-span-12">
                        <div class="card-white p-6 shadow-sm flex items-center justify-between">
                            <h2 class="font-bold text-lg text-gray-700">จำนวนบทความทั้งหมด: <span class="text-blue-600"></span> บทความ</h2>
                            <a href="manageBlogs.php" class="text-sm text-blue-500 hover:underline"><i class="fa-solid fa-arrow-left mr-1"></i> กลับหน้าจัดการ</a>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <div class="card-white p-8 shadow-sm">
                            <h2 class="text-2xl font-bold mb-6 border-b pb-4"><i class="fa-solid fa-plus-circle mr-2 text-green-500"></i>เขียนบทความใหม่</h2>
                            
                            <?php if (isset($blogResult)) echo $blogResult; ?>

                            <form action="" method="post" class="space-y-6" enctype="multipart/form-data">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="col-span-2">
                                        <label class="block text-sm font-bold text-gray-700 mb-1">ชื่อบทความ (Blog Title)</label>
                                        <input type="text" name="blogTitle" class="input-form w-full" placeholder="ระบุชื่อบทความที่นี่..." required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">URL Slug (ต่อท้าย /blog/...)</label>
                                        <input type="text" name="blogUrl" class="input-form w-full" placeholder="เช่น how-to-install-plugin" required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">หมวดหมู่ (Category)</label>
                                        <select name="blogCategory" class="input-form w-full bg-white" required>
                                            <option value="">-- เลือกหมวดหมู่ --</option>
                                            <?php if (!empty($getCategory)): ?>
                                                <?php foreach ($getCategory as $cat): ?>
                                                    <option value="<?= $cat['categoryId'] ?>"><?= htmlspecialchars($cat['categoryName']) ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">เนื้อหาบทความ (HTML Content)</label>
                                    <textarea name="blogContent" rows="10" class="input-form w-full" placeholder="ใส่โค้ด HTML หรือเนื้อหาที่นี่..."></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">รูปภาพหน้าปก</label>
                                    <input type="file" name="blogImage" class="input-form w-full bg-white" required>
                                </div>

                                <div class="mt-10 pt-6 border-t-2 border-gray-100">
                                    <h3 class="text-lg font-bold text-blue-600 mb-4"><i class="fa-solid fa-search mr-2"></i> SEO Optimization</h3>
                                    <div class="grid grid-cols-1 gap-4 bg-blue-50/30 p-5 rounded-xl border border-blue-100">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">SEO Title</label>
                                            <input type="text" name="seo_title" class="input-form w-full bg-white" placeholder="ถ้าว่างจะใช้ชื่อบทความปกติ">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                                            <textarea name="seo_description" rows="2" class="input-form w-full bg-white" placeholder="สรุปเนื้อหาสั้นๆ สำหรับ Google..."></textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Keywords</label>
                                            <input type="text" name="seo_keywords" class="input-form w-full bg-white" placeholder="คำค้นหา 1, คำค้นหา 2">
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-6">
                                    <button type="submit" name="btnBlog" class="btn-blue-500 w-full py-4 text-lg font-bold shadow-lg">
                                        <i class="fa-solid fa-save mr-2"></i> บันทึกและเผยแพร่บทความ
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>