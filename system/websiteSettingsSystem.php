<?php
require_once 'conn.php';

function truncateSetting(string $value, int $max = 255): string
{
    return mb_substr(trim($value), 0, $max);
}

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

function updateGeneralSettings(PDO $conn, array $settingsData): bool
{
    try {
        $sql = "UPDATE websettings SET
                webTitle = :webTitle,
                webLogo = :webLogo,
                heroTitle = :heroTitle
                WHERE webId = 1";

        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':webTitle' => truncateSetting($settingsData['webTitle'] ?? ''),
            ':webLogo' => truncateSetting($settingsData['webLogo'] ?? ''),
            ':heroTitle' => truncateSetting($settingsData['heroTitle'] ?? ''),
        ]);
    } catch (PDOException $error) {
        return false;
    }
}

function updateAnnounceSettings(PDO $conn, array $settingsData): bool
{
    try {
        $sql = "UPDATE websettings SET
                announceText = :announceText,
                announceDate = :announceDate
                WHERE webId = 1";

        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':announceText' => truncateSetting($settingsData['announceText'] ?? ''),
            ':announceDate' => $settingsData['announceDate'],
        ]);
    } catch (PDOException $error) {
        return false;
    }
}

function updateSEOSettings(PDO $conn, array $data): bool
{
    try {
        $sql = "UPDATE websettings SET
                site_seo_title = :site_seo_title,
                site_seo_description = :site_seo_description,
                site_seo_keywords = :site_seo_keywords
                WHERE webId = 1";

        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':site_seo_title' => truncateSetting($data['site_seo_title'] ?? ''),
            ':site_seo_description' => truncateSetting($data['site_seo_description'] ?? ''),
            ':site_seo_keywords' => truncateSetting($data['site_seo_keywords'] ?? ''),
        ]);
    } catch (PDOException $e) {
        return false;
    }
}
