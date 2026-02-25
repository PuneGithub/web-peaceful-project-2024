<?php
function createServer($conn, $userId, $name, $ip, $version, $category, $desc, $image)
{

    // 1. ใช้ trim() เพื่อตัดช่องว่างหน้า-หลัง IP ออกให้หมด
    $ip = trim($ip);

    //ตรวจสอบ IP ซ้ำ
    $checkSql = "SELECT COUNT(*) FROM servers WHERE serverIP = :ip AND (status = 'approved' OR status = 'pending')";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->execute([':ip' => $ip]);

    if ($checkStmt->fetchColumn() > 0) {
        return "IP_DUPLICATE";
    }

    try {
        $sql = "INSERT INTO servers (userId, serverName, serverIP, serverVersion, serverCategory, serverDescription, serverImage, status) 
                VALUES (:userId, :name, :ip, :version, :category, :desc, :image, 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':userId' => $userId,
            ':name' => $name,
            ':ip' => $ip,
            ':version' => $version,
            ':category' => $category,
            ':desc' => $desc,
            ':image' => $image
        ]);
        return true;
    } catch (PDOException $e) {
         echo "Error: " . $e->getMessage();
    }
}

function fetchApprovedServers($conn, $limit = 3)
{
    try {
        $sql = "SELECT * FROM servers WHERE status = 'approved' ORDER BY votes DESC LIMIT :limit";
        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchALL(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}
