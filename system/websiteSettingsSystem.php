<?php
require_once 'conn.php';
function getWebsiteSettings($conn)
{
    try {
        $sql = "SELECT * FROM websettings WHERE webId = 1 LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $error) {
        return false;
    }
}

function updateGeneralSettings($conn, $settingsData)
{
    try {
        $sql = "UPDATE websettings SET
                webTitle = :webTitle,
                heroTitle = :heroTitle
                WHERE webId = 1";

                $stmt = $conn->prepare($sql);

                $stmt->bindParam('webTitle', $settingsData['webTitle']);
                $stmt->bindParam('heroTitle', $settingsData['heroTitle']);

                if ($stmt->execute()) {
                    return "<div class='alert-green'>บันทึกการตั้งค่าสำเร็จ!</div>";
                } else {
                    return "<div class='alert-danger'>เกิดข้อผิดพลาดในการบันทึก</div>";
                }
    } catch (PDOException $error) {
        return "<div class='alert-danger'>Error: " . $error->getMessage() . "</div>";
    }
}
function updateAnnounceSettings($conn, $settingsData)
{
    try {
        $sql = "UPDATE websettings SET
                announceText = :announceText,
                announceDate = :announceDate
                WHERE webId = 1";

                $stmt = $conn->prepare($sql);

                $stmt->bindParam('announceText', $settingsData['announceText']);
                $stmt->bindParam('announceDate', $settingsData['announceDate']);

                if ($stmt->execute()) {
                    return "<div class='alert-green'>บันทึกการตั้งค่าสำเร็จ!</div>";
                } else {
                    return "<div class='alert-danger'>เกิดข้อผิดพลาดในการบันทึก</div>";
                }
    } catch (PDOException $error) {
        return "<div class='alert-danger'>Error: " . $error->getMessage() . "</div>";
    }
}

function updateSEOSettings($conn, $data) {
    try {
        $sql = "UPDATE websettings SET 
                site_seo_title = :site_seo_title, 
                site_seo_description = :site_seo_description, 
                site_seo_keywords = :site_seo_keywords 
                WHERE webId = 1"; // ตรวจสอบ webId ให้ตรงกับในฐานข้อมูล
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':site_seo_title' => $data['site_seo_title'],
            ':site_seo_description' => $data['site_seo_description'],
            ':site_seo_keywords' => $data['site_seo_keywords']
        ]);

        return "<div class='alert-green'><i class='fa-solid fa-circle-check mr-2'></i>บันทึกข้อมูล SEO เรียบร้อยแล้ว!</div>";
    } catch (PDOException $e) {
        return "<div class='alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}