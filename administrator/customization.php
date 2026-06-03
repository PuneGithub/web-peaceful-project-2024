<?php
require_once '../system/websiteSettingsSystem.php';
require_once __DIR__ . '/../system/config.php';
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        // หากไม่มี Token หรือ Token ไม่ตรงกัน ให้หยุดการทำงานทันที
        die("<div style='color: #f00;'>ตรวจพบการส่งข้อมูลที่ไม่ปลอดภัย (CSRF Token Mismatch) <br><a href='customization.php' class='text-blue-500 underline'>กลับไปหน้าตั้งค่า</a></div>");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnSaveGeneral'])) {
    $webTitle = trim($_POST['webTitle'] ?? '');
    $heroTitle = trim($_POST['heroTitle'] ?? '');

    if ($webTitle === '' || $heroTitle === '') {
        header('Location: customization.php?error=general&reason=validation');
        exit;
    }

    $settingsData = [
        'webTitle' => $webTitle,
        'webLogo' => $_POST['webLogo'] ?? '',
        'heroTitle' => $heroTitle,
    ];

    if (updateGeneralSettings($conn, $settingsData)) {
        header('Location: customization.php?saved=general');
    } else {
        header('Location: customization.php?error=general');
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnSaveAnnounce'])) {
    $announceText = trim($_POST['announceText'] ?? '');

    if ($announceText === '') {
        header('Location: customization.php?error=announce&reason=validation');
        exit;
    }

    $announce = [
        'announceText' => $announceText,
        'announceDate' => date('Y-m-d H:i:s'),
    ];

    if (updateAnnounceSettings($conn, $announce)) {
        header('Location: customization.php?saved=announce');
    } else {
        header('Location: customization.php?error=announce');
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnSaveSEO'])) {
    $seoData = [
        'site_seo_title' => $_POST['site_seo_title'] ?? '',
        'site_seo_description' => $_POST['site_seo_description'] ?? '',
        'site_seo_keywords' => $_POST['site_seo_keywords'] ?? '',
    ];

    if (updateSEOSettings($conn, $seoData)) {
        header('Location: customization.php?saved=seo');
    } else {
        header('Location: customization.php?error=seo');
    }
    exit;
}

function flashMessage(string $section): string
{
    $saved = $_GET['saved'] ?? '';
    $error = $_GET['error'] ?? '';
    $reason = $_GET['reason'] ?? '';

    if ($saved === $section) {
        $messages = [
            'general' => 'บันทึกการตั้งค่าทั่วไปสำเร็จแล้ว',
            'announce' => 'บันทึกประกาศสำเร็จแล้ว',
            'seo' => 'บันทึกข้อมูล SEO สำเร็จแล้ว',
        ];
        $text = $messages[$section] ?? 'บันทึกสำเร็จแล้ว';
        return "<div class='alert-green'><i class='fa-solid fa-circle-check mr-2'></i>{$text}</div>";
    }

    if ($error === $section) {
        if ($reason === 'validation') {
            $text = 'กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน';
        } else {
            $messages = [
                'general' => 'เกิดข้อผิดพลาดในการบันทึกการตั้งค่าทั่วไป',
                'announce' => 'เกิดข้อผิดพลาดในการบันทึกประกาศ',
                'seo' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล SEO',
            ];
            $text = $messages[$section] ?? 'เกิดข้อผิดพลาดในการบันทึก';
        }
        return "<div class='alert-danger'><i class='fa-solid fa-triangle-exclamation mr-2'></i>{$text}</div>";
    }

    return '';
}

$settings = getWebsiteSettings($conn);
if (!$settings) {
    $settings = [];
    $dbError = "<div class='alert-danger mb-4'><i class='fa-solid fa-triangle-exclamation mr-2'></i>ไม่สามารถโหลดการตั้งค่าเว็บไซต์ได้ กรุณาตรวจสอบฐานข้อมูล</div>";
} else {
    $dbError = '';
}

$msgGeneral = flashMessage('general');
$msgAnnounce = flashMessage('announce');
$msgSeo = flashMessage('seo');
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once __DIR__ . '/../components/favicon.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/output.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>ปรับแต่งเว็บไซต์</title>
</head>

<body>
    <div class="flex">
        <?php include_once('../components/header-admin.php'); ?>

        <div class="flex-1 p-6">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold mb-6 text-center">ปรับแต่งเว็บไซต์</h2>

                <?php if ($dbError) {
                    echo $dbError;
                } ?>

                <div class="card-white p-6 mb-6">
                    <h3 class="text-xl font-semibold mb-4 border-b pb-2">การตั้งค่าทั่วไป</h3>
                    <?php if ($msgGeneral) {
                        echo $msgGeneral;
                    } ?>
                    <form action="" method="post" class="space-y-4">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <div class="flex flex-col md:flex-row md:items-center">
                            <label for="webTitle" class="w-full md:w-1/4 font-medium text-gray-700">ชื่อเว็บไซต์</label>
                            <input type="text" id="webTitle" value="<?= htmlspecialchars($settings['webTitle'] ?? '') ?>" name="webTitle" class="input-form md:w-3/4" required maxlength="255">
                        </div>
                        <div class="flex flex-col md:flex-row md:items-start">
                            <label for="webLogo" class="w-full md:w-1/4 font-medium text-gray-700 pt-2">โลโก้ (URL/ path)</label>
                            <div class="w-full md:w-3/4">
                                <input type="text" id="webLogo" name="webLogo" value="<?= htmlspecialchars($settings['webLogo'] ?? '') ?>" class="input-form w-full" maxlength="255" placeholder="img/logo.png">
                                <p class="text-[10px] text-gray-400 mt-1">เว้นว่างได้หากยังไม่มีโลโก้</p>
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center">
                            <label for="heroTitle" class="w-full md:w-1/4 font-medium text-gray-700">หัวข้อ Hero</label>
                            <input type="text" id="heroTitle" value="<?= htmlspecialchars($settings['heroTitle'] ?? '') ?>" name="heroTitle" class="input-form md:w-3/4" required maxlength="255">
                        </div>
                        <div class="flex justify-end pt-4">
                            <button type="submit" name="btnSaveGeneral" class="btn-green-500">
                                <i class="fa-solid fa-save mr-2"></i> บันทึก
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-white p-6 mb-6">
                    <h3 class="text-xl font-semibold mb-4 border-b pb-2">แถบประกาศ</h3>
                    <?php if ($msgAnnounce) {
                        echo $msgAnnounce;
                    } ?>
                    <form action="" method="post" class="space-y-4">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <div class="flex flex-col gap-2 md:flex-row md:items-center">
                            <label for="announceText" class="w-full md:w-1/4 font-medium text-gray-700">ข้อความประกาศ</label>
                            <input type="text" id="announceText" value="<?= htmlspecialchars($settings['announceText'] ?? '') ?>" name="announceText" class="input-form md:w-3/4" required maxlength="255">
                        </div>
                        <?php if (!empty($settings['announceDate'])): ?>
                            <p class="text-xs text-gray-500 md:ml-[25%]">อัปเดตล่าสุด: <?= htmlspecialchars($settings['announceDate']) ?></p>
                        <?php endif; ?>
                        <div class="flex justify-end pt-4">
                            <button type="submit" name="btnSaveAnnounce" class="btn-green-500">
                                <i class="fa-solid fa-save mr-2"></i> บันทึก
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-white p-6 mb-6 shadow-sm">
                    <h3 class="text-xl font-semibold mb-4 border-b pb-2 text-blue-600">
                        <i class="fa-solid fa-search mr-2"></i> SEO
                    </h3>
                    <p class="text-sm text-gray-500 mb-4">ข้อมูลส่วนนี้จะแสดงบน Google และ Social Media</p>
                    <?php if ($msgSeo) {
                        echo $msgSeo;
                    } ?>
                    <form action="" method="post" class="space-y-4">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <div class="flex flex-col md:flex-row md:items-start">
                            <label for="site_seo_title" class="w-full md:w-1/4 font-medium text-gray-700 pt-2">SEO Title</label>
                            <div class="w-full md:w-3/4">
                                <input type="text" id="site_seo_title" name="site_seo_title" value="<?= htmlspecialchars($settings['site_seo_title'] ?? '') ?>" class="input-form w-full" maxlength="255" placeholder="Zencrafterly - แหล่งรวมเซิร์ฟเวอร์ Minecraft">
                                <p class="text-[10px] text-gray-400 mt-1">แนะนำความยาว 50-60 ตัวอักษร</p>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-start">
                            <label for="site_seo_description" class="w-full md:w-1/4 font-medium text-gray-700 pt-2">Meta Description</label>
                            <div class="w-full md:w-3/4">
                                <textarea id="site_seo_description" name="site_seo_description" rows="3" class="input-form w-full" maxlength="255" placeholder="สรุปเนื้อหาเว็บไซต์ของคุณ..."><?= htmlspecialchars($settings['site_seo_description'] ?? '') ?></textarea>
                                <p class="text-[10px] text-gray-400 mt-1">แนะนำความยาว 150-160 ตัวอักษร (สูงสุด 255 ตามฐานข้อมูล)</p>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-start">
                            <label for="site_seo_keywords" class="w-full md:w-1/4 font-medium text-gray-700 pt-2">Keywords</label>
                            <div class="w-full md:w-3/4">
                                <input type="text" id="site_seo_keywords" name="site_seo_keywords" value="<?= htmlspecialchars($settings['site_seo_keywords'] ?? '') ?>" class="input-form w-full" maxlength="255" placeholder="Minecraft, เซิร์ฟเวอร์มายคราฟ, โปรโมทเซิร์ฟ">
                                <p class="text-[10px] text-gray-400 mt-1">คั่นแต่ละคำด้วยเครื่องหมายคอมม่า (,)</p>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t">
                            <button type="submit" name="btnSaveSEO" class="btn-blue-500">
                                <i class="fa-solid fa-cloud-upload-alt mr-2"></i> บันทึก SEO
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>