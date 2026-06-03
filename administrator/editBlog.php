<?php
require_once '../system/administratorSystem.php';
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

$blogId = (int) ($_POST['blogId'] ?? $_GET['blogId'] ?? 0);
if ($blogId <= 0) {
    header('Location: manageBlogs.php');
    exit;
}

$getCategory = getCategory($conn);

$blog = fetchEditBlog($conn, $blogId);
if (!$blog) {
    header('Location: manageBlogs.php');
    exit;
}

$postAlertHtml = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['blogId'])) {
    $blogTitle = $_POST['blogTitle'];
    $blogContent = $_POST['blogContent'];
    $blogCategory = $_POST['blogCategory'];

    $slug = $_POST['slug'];

    $updateSlug = createSlug($slug);

    $seo_title = $_POST['seo_title'];
    $seo_description = $_POST['seo_description'];
    $seo_keywords = $_POST['seo_keywords'];

    $oldImage = $blog['blogImage'];
    $newImage = $_FILES['blogImage'];

    if (updateBlog($conn, $blogId, $blogTitle, $blogContent, $blogCategory, $newImage, $oldImage, $seo_title, $seo_description, $seo_keywords, $updateSlug)) {
        $postAlertHtml = "<div class='alert-green text-center mb-4'><i class='fa-solid fa-check-circle mr-2'></i>อัปเดตบทความสำเร็จ!</div>";
        $blog = fetchEditBlog($conn, $blogId);
    } else {
        $postAlertHtml = "<div class='alert-danger text-center mb-4'><i class='fa-solid fa-triangle-exclamation mr-2'></i>เกิดข้อผิดพลาดในการอัปเดตข้อมูล!</div>";
    }
}

$imagePath = $blog['folderPath'] ?? 'img/blogs_image/default/';

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
    <title>Edit Blog - Zencrafterly</title>
</head>

<body class="bg-gray-50">
    <div class="flex">
        <?php include_once('../components/header-admin.php'); ?>

        <div class="flex-1 p-6">
            <div class="max-w-4xl mx-auto">
                <div class="card-white p-8 shadow-sm">
                    <?= $postAlertHtml ?>

                    <div class="flex items-center mb-6 border-b pb-4">
                        <a href="manageBlogs.php" class="text-gray-400 hover:text-blue-500 mr-4 transition"><i class="fa-solid fa-arrow-left fa-lg"></i></a>
                        <h2 class="text-2xl font-bold text-gray-800">Edit Blog: <span class="text-blue-600">#<?= htmlspecialchars($blog['blogId']) ?></span></h2>
                    </div>

                    <form action="" enctype="multipart/form-data" method="post" class="space-y-6">
                        <input type="hidden" name="blogId" value="<?php echo $blog['blogId']; ?>">

                        <div class="space-y-4">
                            <div>
                                <label for="blogTitle" class="block text-sm font-bold text-gray-700 mb-1">ชื่อบทความ (Blog Title)</label>
                                <input type="text" name="blogTitle" class="input-form w-full" value="<?php echo htmlspecialchars($blog['blogTitle']); ?>" required>
                            </div>

                            <div>
                                <label for="slug" class="block text-sm font-bold text-gray-700 mb-1">Slug</label>
                                <input type="text" name="slug" class="input-form w-full" value="<?php echo htmlspecialchars($blog['slug']); ?>" required>
                            </div>

                            <div>
                                <label for="blogCategory" class="block text-sm font-bold text-gray-700 mb-1">หมวดหมู่ (Category)</label>
                                <select name="blogCategory" class="input-form w-full bg-white" required>
                                    <option value="">-- เลือกหมวดหมู่ --</option>
                                    <?php foreach ($getCategory as $cat): ?>
                                        <option value="<?= $cat['categoryId'] ?>"
                                            <?= ($blog['categoryId'] == $cat['categoryId']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['categoryName']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label for="blogContent" class="block text-sm font-bold text-gray-700 mb-1">เนื้อหาบทความ (Content)</label>
                                <textarea name="blogContent" class="input-form w-full" rows="10" required><?php echo htmlspecialchars($blog['blogContent']); ?></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">รูปภาพหน้าปก (Cover Image)</label>
                                <?php if (!empty($blog['blogImage'])): ?>
                                    <div class="mb-3 p-3 bg-gray-50 rounded-lg border inline-block">
                                        <?php
                                        // ลบเครื่องหมาย / ที่อาจจะซ้ำซ้อนออก
                                        $cleanPath = rtrim($imagePath, '/') . '/';
                                        $imageSrc = "../" . $cleanPath . $blog['blogImage'];
                                        ?>
                                        <img src="<?= htmlspecialchars($imageSrc) ?>" alt="Current Cover" class="w-48 h-32 object-cover rounded shadow-sm mb-2">
                                        <p class="text-xs text-gray-500 font-mono"><?php echo htmlspecialchars($blog['blogImage']); ?></p>
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="blogImage" class="input-form w-full bg-white">
                                <p class="text-[10px] text-gray-400 mt-1">อัปโหลดรูปใหม่เฉพาะเมื่อต้องการเปลี่ยนรูปหน้าปก</p>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t-2 border-gray-100">
                            <h3 class="text-lg font-bold text-blue-600 mb-4">
                                <i class="fa-solid fa-magnifying-glass mr-2"></i> SEO Settings
                            </h3>

                            <div class="grid grid-cols-1 gap-5 bg-blue-50/30 p-5 rounded-xl border border-blue-100">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">SEO Title</label>
                                    <input type="text" name="seo_title" class="input-form w-full bg-white"
                                        value="<?php echo htmlspecialchars($blog['seo_title'] ?? ''); ?>"
                                        placeholder="เช่น: 10 ปลั๊กอินมายคราฟที่ต้องมี - Zencrafterly">
                                    <p class="text-[10px] text-gray-500 mt-1">ถ้าไม่กรอก ระบบจะใช้ "ชื่อบทความ" ด้านบนแทน</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                                    <textarea name="seo_description" rows="3" class="input-form w-full bg-white"
                                        placeholder="สรุปเนื้อหาบทความสั้นๆ ให้น่าคลิก..."><?php echo htmlspecialchars($blog['seo_description'] ?? ''); ?></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Keywords</label>
                                    <input type="text" name="seo_keywords" class="input-form w-full bg-white"
                                        value="<?php echo htmlspecialchars($blog['seo_keywords'] ?? ''); ?>"
                                        placeholder="ปลั๊กอินมายคราฟ, รีวิวปลั๊กอิน, เซิร์ฟเวอร์">
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <button type="submit" class="btn-blue-500 w-full md:w-auto px-8 py-3 text-lg font-bold">
                                <i class="fa-solid fa-save mr-2"></i> บันทึกการเปลี่ยนแปลง
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>