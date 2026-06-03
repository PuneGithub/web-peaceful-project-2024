<?php
/**
 * นับยอดดาวน์โหลด + redirect ไปลิงก์ไฟล์จริง
 * ใช้ URL: /download/{id} หรือ download.php?id=
 */
require_once("system/conn.php");
require_once("system/config.php");
require_once("system/resourceSystem.php");

$resourceId = (int) ($_GET['id'] ?? 0);

if ($resourceId <= 0) {
    header('Location: ' . base_url('resources'));
    exit;
}

$fileUrl = incrementResourceDownloads($conn, $resourceId);

if ($fileUrl) {
    header('Location: ' . $fileUrl, true, 302);
    exit;
}

header('Location: ' . base_url('resources'));
exit;
