<?php

/** หมวดหมู่ Resource ที่อนุญาต */
function getResourceCategories()
{
    return [
        'plugin'        => 'Plugin',
        'map'           => 'Map (แผนที่)',
        'resource_pack' => 'Resource Pack',
        'skript'        => 'Skript',
    ];
}

function isValidResourceCategory($category)
{
    return array_key_exists($category, getResourceCategories());
}

function isValidResourceStatus($status)
{
    return in_array($status, ['pending', 'approved', 'rejected'], true);
}

function isValidResourceSort($sort)
{
    return in_array($sort, ['latest', 'popular', 'alphabet'], true);
}

function isValidFileUrl($url)
{
    return filter_var($url, FILTER_VALIDATE_URL) !== false
        && preg_match('#^https?://#i', $url);
}

/**
 * อัปโหลดรูปปก Resource ไปที่ img/resources/
 */
function uploadResourceImage($file)
{
    if (empty($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $maxSize = 3 * 1024 * 1024;
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowed, true)) {
        return false;
    }
    if ($file['size'] > $maxSize) {
        return false;
    }
    if (getimagesize($file['tmp_name']) === false) {
        return false;
    }

    $uploadDir = dirname(__DIR__) . '/img/resources/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $newName = 'resource_' . time() . '_' . uniqid() . '.' . $ext;
    if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
        return $newName;
    }

    return false;
}

// ดึงข้อมูล Resources สำหรับหน้าสาธารณะ (เฉพาะ approved)
function fetchAllResources($conn, $search = '', $category = '', $sort = 'latest')
{
    if (!isValidResourceSort($sort)) {
        $sort = 'latest';
    }
    if ($category !== '' && !isValidResourceCategory($category)) {
        $category = '';
    }

    $sql = "SELECT r.*, u.username as author 
            FROM resources r 
            LEFT JOIN users u ON r.userId = u.userId 
            WHERE r.status = 'approved'";

    $params = [];

    if (!empty($search)) {
        $sql .= " AND (r.name LIKE :search OR r.description LIKE :search OR u.username LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }

    if (!empty($category)) {
        $sql .= " AND r.category = :category";
        $params[':category'] = $category;
    }

    if ($sort === 'popular') {
        $sql .= " ORDER BY r.downloads DESC";
    } elseif ($sort === 'alphabet') {
        $sql .= " ORDER BY r.name ASC";
    } else {
        $sql .= " ORDER BY r.updatedAt DESC";
    }

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Fetch Resources Error: " . $e->getMessage());
        return [];
    }
}

// ดึงทั้งหมดสำหรับแอดมิน
function fetchAllResourcesAdmin($conn)
{
    $sql = "SELECT r.*, u.username as author 
            FROM resources r 
            LEFT JOIN users u ON r.userId = u.userId 
            ORDER BY r.createdAt DESC";
    try {
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Fetch Admin Resources Error: " . $e->getMessage());
        return [];
    }
}

function fetchResourceById($conn, $resourceId)
{
    $sql = "SELECT r.*, u.username as author 
            FROM resources r 
            LEFT JOIN users u ON r.userId = u.userId 
            WHERE r.resourceId = :resourceId";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':resourceId' => $resourceId]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

/** ดึง resource สำหรับหน้าสาธารณะ (เฉพาะ approved) */
function fetchApprovedResourceById($conn, $resourceId)
{
    $sql = "SELECT r.*, u.username as author 
            FROM resources r 
            LEFT JOIN users u ON r.userId = u.userId 
            WHERE r.resourceId = :resourceId AND r.status = 'approved'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':resourceId' => $resourceId]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * เพิ่มยอดดาวน์โหลด 1 ครั้ง แล้วคืน fileUrl (เฉพาะ approved)
 */
function incrementResourceDownloads($conn, $resourceId)
{
    try {
        $stmt = $conn->prepare(
            "UPDATE resources SET downloads = downloads + 1 
             WHERE resourceId = :resourceId AND status = 'approved'"
        );
        $stmt->execute([':resourceId' => $resourceId]);

        if ($stmt->rowCount() === 0) {
            return null;
        }

        $fetch = $conn->prepare(
            "SELECT fileUrl FROM resources WHERE resourceId = :resourceId AND status = 'approved'"
        );
        $fetch->execute([':resourceId' => $resourceId]);
        $row = $fetch->fetch(PDO::FETCH_ASSOC);

        return ($row && isValidFileUrl($row['fileUrl'])) ? $row['fileUrl'] : null;
    } catch (PDOException $e) {
        error_log("Increment Downloads Error: " . $e->getMessage());
        return null;
    }
}

/** URL หน้ารายละเอียด resource */
function resourceDetailUrl($resourceId)
{
    return base_url('resource/' . (int) $resourceId);
}

/** URL ดาวน์โหลด (นับยอดแล้ว redirect) */
function resourceDownloadUrl($resourceId)
{
    return base_url('download/' . (int) $resourceId);
}

function createResource($conn, $userId, $name, $category, $version, $description, $fileUrl, $imageName, $status = 'approved')
{
    if (!isValidResourceCategory($category) || !isValidResourceStatus($status) || !isValidFileUrl($fileUrl)) {
        return false;
    }

    try {
        $sql = "INSERT INTO resources (userId, name, category, version, description, fileUrl, image, status) 
                VALUES (:userId, :name, :category, :version, :description, :fileUrl, :image, :status)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':userId' => $userId,
            ':name' => $name,
            ':category' => $category,
            ':version' => $version,
            ':description' => $description,
            ':fileUrl' => $fileUrl,
            ':image' => $imageName ?: 'default_resource.webp',
            ':status' => $status,
        ]);
    } catch (PDOException $e) {
        error_log("Create Resource Error: " . $e->getMessage());
        return false;
    }
}

function updateResource($conn, $resourceId, $name, $category, $version, $description, $fileUrl, $imageName, $status)
{
    if (!isValidResourceCategory($category) || !isValidResourceStatus($status) || !isValidFileUrl($fileUrl)) {
        return false;
    }

    try {
        $sql = "UPDATE resources SET 
                name = :name, category = :category, version = :version, 
                description = :description, fileUrl = :fileUrl, image = :image, status = :status 
                WHERE resourceId = :resourceId";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':category' => $category,
            ':version' => $version,
            ':description' => $description,
            ':fileUrl' => $fileUrl,
            ':image' => $imageName,
            ':status' => $status,
            ':resourceId' => $resourceId,
        ]);
    } catch (PDOException $e) {
        error_log("Update Resource Error: " . $e->getMessage());
        return false;
    }
}

function updateResourceStatus($conn, $resourceId, $status)
{
    if (!isValidResourceStatus($status)) {
        return false;
    }
    $sql = "UPDATE resources SET status = :status WHERE resourceId = :resourceId";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        ':status' => $status,
        ':resourceId' => $resourceId,
    ]);
}

function deleteResource($conn, $resourceId)
{
    try {
        $resource = fetchResourceById($conn, $resourceId);
        if (!$resource) {
            return false;
        }

        if (!empty($resource['image']) && $resource['image'] !== 'default_resource.webp') {
            $imageName = basename($resource['image']);
            $filePath = dirname(__DIR__) . '/img/resources/' . $imageName;
            if (is_file($filePath)) {
                unlink($filePath);
            }
        }

        $stmt = $conn->prepare("DELETE FROM resources WHERE resourceId = :resourceId");
        return $stmt->execute([':resourceId' => $resourceId]);
    } catch (PDOException $e) {
        error_log("Delete Resource Error: " . $e->getMessage());
        return false;
    }
}
