<?php
require_once '../system/websiteSettingsSystem.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
}

$msgSettings = '';

// Save General
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnSaveGeneral'])) {
    $settingsData = [
        'webTitle' => $_POST['webTitle'],
        'heroTitle' => $_POST['heroTitle']
    ];

    $msgSettings = updateGeneralSettings($conn, $settingsData);
}

// Save Announce
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnSaveAnnounce'])) {
    $currentDate = date("Y-m-d H:i:s");
    $announce = [
        'announceText' => $_POST['announceText'],
        'announceDate' => $currentDate
    ];

    $msgSettings = updateAnnounceSettings($conn, $announce);
}

// 🚩 เพิ่ม Logic บันทึก SEO ต่อจากระบบเดิม
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnSaveSEO'])) {
    $seoData = [
        'site_seo_title' => $_POST['site_seo_title'],
        'site_seo_description' => $_POST['site_seo_description'],
        'site_seo_keywords' => $_POST['site_seo_keywords']
    ];

    // เรียกใช้ฟังก์ชันบันทึก (เราจะไปสร้างฟังก์ชันนี้ในขั้นตอนถัดไป)
    $msgSettings = updateSEOSettings($conn, $seoData);
}

//Get data
$settings = getWebsiteSettings($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/output.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Customization</title>
</head>

<body>
    <div class="flex">
        <?php include_once('../components/header-admin.php'); ?>

        <!-- Custom -->
        <div class="w-full min-h-screen flex flex-col items-center justify-center p-4 sm:p-6 md:p-8">
            <div class="w-full max-w-4xl">
                <div class="card-white p-6 mb-6">
                    <h2 class="text-3xl font-bold mb-6 text-center">
                        Website Customization
                    </h2>
                    <h3 class="text-xl font-semibold mb-4 border-b pb-2">General Settings</h3>
                    <?php
                    if ($msgSettings) echo $msgSettings;
                    ?>
                    <form action="" method="post" enctype="multipart/form-data" class="space-y-4">
                        <div class="flex flex-col md:flex-row md:items-center">
                            <label for="site-title" class="w-full md:w-1/4 font-medium text-gray-700">Website Title</label>
                            <input type="text" value="<?php echo htmlspecialchars($settings['webTitle'] ?? ''); ?>" name="webTitle" class="input-form md:w-3/4" required>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center">
                            <label for="site-logo" class="w-full md:w-1/4 font-medium text-gray-700">Website Logo</label>
                            <input type="text" name="webLogo" value="<?php echo htmlspecialchars($settings['webLogo'] ?? ''); ?>" class="input-form md:w-3/4" required>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center">
                            <label for="hero-title" class="w-full md:w-1/4 font-medium text-gray-700">Hero Title</label>
                            <input type="text" value="<?php echo htmlspecialchars($settings['heroTitle'] ?? ''); ?>" name="heroTitle" class="input-form md:w-3/4" required>
                        </div>
                        <div class="flex justify-end pt-4">
                            <button type="submit" name="btnSaveGeneral" class="btn-green-500">
                                <i class="fa-solid fa-save mr-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-white p-6 mb-6">
                    <h3 class="text-xl font-semibold mb-4 border-b pb-2">Announcement Banner</h3>
                    <form action="" method="post" enctype="multipart/form-data" class="space-y-4">
                        <div class="flex flex-col gap-2 md:flex-row md:items-center">
                            <label for="Announce" class="w-full md:w-1/4 font-medium text-gray-700">Announce Text</label>
                            <input type="text" value="<?php echo htmlspecialchars($settings['announceText'] ?? ''); ?>" name="announceText" class="input-form md:w-3/4" required>
                        </div>
                        <div class="flex justify-end pt-4">
                            <button type="submit" name="btnSaveAnnounce" class="btn-green-500">
                                <i class="fa-solid fa-save mr-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-white p-6 mb-6 shadow-sm">
                    <h3 class="text-xl font-semibold mb-4 border-b pb-2 text-blue-600">
                        <i class="fa-solid fa-search mr-2"></i> SEO Optimization
                    </h3>
                    <p class="text-sm text-gray-500 mb-4">* ข้อมูลส่วนนี้จะไปแสดงผลบน Google และ Social Media</p>

                    <form action="" method="post" class="space-y-4">
                        <div class="flex flex-col md:flex-row md:items-start">
                            <label class="w-full md:w-1/4 font-medium text-gray-700 pt-2">SEO Title</label>
                            <div class="w-full md:w-3/4">
                                <input type="text" name="site_seo_title" value="<?php echo htmlspecialchars($settings['site_seo_title'] ?? ''); ?>" class="input-form w-full" placeholder="Zencrafterly - แหล่งรวมเซิร์ฟเวอร์ Minecraft">
                                <p class="text-[10px] text-gray-400 mt-1">แนะนำความยาว 50-60 ตัวอักษร</p>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-start">
                            <label class="w-full md:w-1/4 font-medium text-gray-700 pt-2">Meta Description</label>
                            <div class="w-full md:w-3/4">
                                <textarea name="site_seo_description" rows="3" class="input-form w-full" placeholder="สรุปเนื้อหาเว็บไซต์ของคุณ..."><?php echo htmlspecialchars($settings['site_seo_description'] ?? ''); ?></textarea>
                                <p class="text-[10px] text-gray-400 mt-1">แนะนำความยาว 150-160 ตัวอักษร</p>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-start">
                            <label class="w-full md:w-1/4 font-medium text-gray-700 pt-2">Keywords</label>
                            <div class="w-full md:w-3/4">
                                <input type="text" name="site_seo_keywords" value="<?php echo htmlspecialchars($settings['site_seo_keywords'] ?? ''); ?>" class="input-form w-full" placeholder="Minecraft, เซิร์ฟเวอร์มายคราฟ, โปรโมทเซิร์ฟ">
                                <p class="text-[10px] text-gray-400 mt-1">คั่นแต่ละคำด้วยเครื่องหมายคอมม่า ( , )</p>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t">
                            <button type="submit" name="btnSaveSEO" class="btn-blue-500">
                                <i class="fa-solid fa-cloud-upload-alt mr-2"></i> Update SEO Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>


</body>

</html>