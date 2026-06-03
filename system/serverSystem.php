<?php

function resolveUniqueServerSlug(PDO $conn, string $baseSlug, ?int $excludeServerId = null): string
{
    $slug = $baseSlug;
    $suffix = 2;

    while (true) {
        $sql = 'SELECT COUNT(*) FROM servers WHERE serverSlug = :slug';
        $params = [':slug' => $slug];

        if ($excludeServerId !== null) {
            $sql .= ' AND serverId != :excludeId';
            $params[':excludeId'] = $excludeServerId;
        }

        $checkStmt = $conn->prepare($sql);
        $checkStmt->execute($params);

        if ((int) $checkStmt->fetchColumn() === 0) {
            return $slug;
        }

        $slug = $baseSlug . '-' . $suffix;
        $suffix++;

        if ($suffix > 100) {
            return $baseSlug . '-' . time();
        }
    }
}

function createServer($conn, $userId, $name, $slug, $ip, $version, $category, $desc, $image)
{
    $userStmt = $conn->prepare("SELECT createDate, verifyStatus FROM users WHERE userId = :userId");
    $userStmt->execute([':userId' => $userId]);
    $userData = $userStmt->fetch(PDO::FETCH_ASSOC);

    if (!$userData || $userData['verifyStatus'] !== 'verified') {
        return 'UNVERIFIED_ACCOUNT';
    }

    // 1. ใช้ trim() เพื่อตัดช่องว่างหน้า-หลัง IP ออกให้หมด
    $ip = trim($ip);

    $slug = trim($slug);
    if ($slug === '') {
        $slug = 'server';
    }
    $slug = resolveUniqueServerSlug($conn, $slug);

    //กำหนดระยะเวลา "หมดอายุ" ของข้อมูลเก่า (เช่น 30 วัน)
    $expiryDays = 30;

    // ตรวจสอบ IP ซ้ำ เฉพาะที่ยัง 'Active' อยู่เท่านั้น
    // Active = (สถานะเป็น approved/pending) AND (มีการอัปเดตล่าสุดไม่เกิน 30 วัน)
    $checkSql = "SELECT COUNT(*) FROM servers 
                 WHERE serverIP = :ip 
                 AND (status = 'approved' OR status = 'pending')
                 AND updatedAt > DATE_SUB(NOW(), INTERVAL :days DAY)";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bindValue(':ip', $ip);
    $checkStmt->bindValue(':days', (int)$expiryDays, PDO::PARAM_INT);
    $checkStmt->execute();

    if ($checkStmt->fetchColumn() > 0) {
        return "IP_DUPLICATE";
    }

    $status = 'pending';

    $regDate = new DateTime($userData['createDate']);
    $now = new DateTime();
    $interval = $regDate->diff($now);

    if ($interval->days >= 7) {
        $status = 'approved';
    }


    try {
        $sql = "INSERT INTO servers (userId, serverName, serverSlug, serverIP, serverVersion, serverCategory, serverDescription, serverImage, status) 
                VALUES (:userId, :name, :slug, :ip, :version, :category, :desc, :image, :status)";
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([
            ':userId' => $userId,
            ':name' => $name,
            ':slug' => $slug,
            ':ip' => $ip,
            ':version' => $version,
            ':category' => $category,
            ':desc' => $desc,
            ':image' => $image,
            ':status' => $status
        ]);

        // คืนค่ากลับไปว่าสำเร็จแบบไหน (เพื่อเอาไปโชว์ Message ต่างกันได้)
        if ($result) {
            return ($status === 'approved') ? "AUTO_APPROVED" : true;
        }
        return false;
    } catch (PDOException $e) {
        error_log("Create Server Error: " . $e->getMessage());
        return false;
    }
}

function fetchApprovedServers($conn, $limit = null, $category = null, $search = null)
{
    try {
        // SQL พื้นฐาน: ดึงเฉพาะที่สถานะเป็น approved และเรียงตามยอดโหวต
        $sql = "SELECT * FROM servers WHERE status = 'approved'";
        $params = [];

        if ($category) {
            $sql .= " AND serverCategory = :category";
            $params[':category'] = $category;
        }

        if ($search) {
            $sql .= " AND (serverName LIKE :search OR serverIP LIKE :search)";
            $params[':search'] = "%$search%";
        }

        $sql .= " ORDER BY votes DESC";

        if ($limit !== null && $limit > 0) {
            $sql .= " LIMIT :limit";
        }

        $stmt = $conn->prepare($sql);

        // Bind ค่า parameters ทั้งหมด
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }

        if ($limit !== null && $limit > 0) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Fetch Approved Servers Error: " . $e->getMessage());
        return [];
    }
}

//vote
function voteServer($conn, $serverId, $userId = null)
{
    if (!$userId) {
        return "LOGIN_REQUIRED";
    }

    //เพิ่มการตรวจสอบสถานะการยืนยันอีเมล
    $userSql = "SELECT verifyStatus FROM users WHERE userId = :userId";
    $userStmt = $conn->prepare($userSql);
    $userStmt->execute([':userId' => $userId]);
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);

    // ตรวจสอบว่าถ้าไม่ใช่ 'verified' ให้ตีกลับทันที
    if (!$user || $user['verifyStatus'] !== 'verified') {
        return "UNVERIFIED_ACCOUNT";
    }

    $ip = $_SERVER['REMOTE_ADDR'];

    //เช็ค Cooldown 1 ชั่วโมง (อ้างอิงจาก userId เป็นหลัก)
    $checkSql = "SELECT createdAt FROM votes_log 
                 WHERE serverId = :serverId 
                 AND userId = :userId 
                 AND createdAt > DATE_SUB(NOW(), INTERVAL 1 HOUR)
                 ORDER BY createdAt DESC LIMIT 1";
    
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->execute([
        ':serverId' => $serverId,
        ':userId' => $userId
    ]);

    $lastVote = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($lastVote) {
        $nextVoteTime = strtotime($lastVote['createdAt']) + 3600;
        $remainingMinutes = ceil(($nextVoteTime - time()) / 60);
        return ['status' => 'COOLDOWN', 'remaining' => $remainingMinutes];
    }

    try {
        // เริ่มต้น Transaction เพื่อความปลอดภัยของข้อมูล
        $conn->beginTransaction();

        $updateSql = "UPDATE servers SET votes = votes + 1, updatedAt = NOW() WHERE serverId = :serverId";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->execute([':serverId' => $serverId]);

        $logSql = "INSERT INTO votes_log (serverId, userId, ipAddress) VALUES (:serverId, :userId, :ip)";
        $logStmt = $conn->prepare($logSql);
        $logStmt->execute([
            ':serverId' => $serverId,
            ':userId' => $userId,
            ':ip' => $ip
        ]);

        $conn->commit();
        return true;
    } catch (Exception $e) {

        $conn->rollBack();
        error_log("Vote System Error: " . $e->getMessage());
        return false;
        
    }
}

function fetchUserServers($conn, $userId) 
{
    try {
        $stmt = $conn->prepare("SELECT * FROM servers WHERE userId = :userId ORDER BY createdAt DESC");
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Fetch User Servers Error: " . $e->getMessage());
        return [];
    }
}

function fetchServerForOwner(PDO $conn, $serverId, $userId)
{
    try {
        $stmt = $conn->prepare(
            'SELECT * FROM servers WHERE serverId = :serverId AND userId = :userId'
        );
        $stmt->execute([
            ':serverId' => (int) $serverId,
            ':userId' => (int) $userId,
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    } catch (PDOException $e) {
        error_log('fetchServerForOwner: ' . $e->getMessage());
        return null;
    }
}

/**
 * อัปเดตเซิร์ฟเวอร์ของเจ้าของ — หลังแก้ไข status กลับเป็น pending รอแอดมินตรวจ
 * @return true|string  true = สำเร็จ | IP_DUPLICATE | NOT_FOUND | INVALID_CATEGORY | false = DB error
 */
function updateServer(PDO $conn, $serverId, $userId, $name, $ip, $version, $category, $desc, $imageName)
{
    $server = fetchServerForOwner($conn, $serverId, $userId);
    if (!$server) {
        return 'NOT_FOUND';
    }

    $name = trim($name);
    $ip = trim($ip);
    $version = trim($version);
    $category = trim($category);

    if (!array_key_exists($category, getServerCategories())) {
        return 'INVALID_CATEGORY';
    }

    $expiryDays = 30;
    $checkSql = "SELECT COUNT(*) FROM servers
                 WHERE serverIP = :ip
                 AND serverId != :excludeId
                 AND (status = 'approved' OR status = 'pending')
                 AND updatedAt > DATE_SUB(NOW(), INTERVAL :days DAY)";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bindValue(':ip', $ip);
    $checkStmt->bindValue(':excludeId', (int) $serverId, PDO::PARAM_INT);
    $checkStmt->bindValue(':days', (int) $expiryDays, PDO::PARAM_INT);
    $checkStmt->execute();

    if ((int) $checkStmt->fetchColumn() > 0) {
        return 'IP_DUPLICATE';
    }

    $baseSlug = createSlug($name);
    if ($baseSlug === '') {
        $baseSlug = 'server';
    }
    $slug = resolveUniqueServerSlug($conn, $baseSlug, (int) $serverId);

    try {
        $sql = "UPDATE servers SET
                    serverName = :name,
                    serverSlug = :slug,
                    serverIP = :ip,
                    serverVersion = :version,
                    serverCategory = :category,
                    serverDescription = :desc,
                    serverImage = :image,
                    status = 'pending',
                    updatedAt = NOW()
                WHERE serverId = :serverId AND userId = :userId";

        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([
            ':name'      => $name,
            ':slug'      => $slug,
            ':ip'        => $ip,
            ':version'   => $version,
            ':category'  => $category,
            ':desc'      => $desc,
            ':image'     => $imageName,
            ':serverId'  => (int) $serverId,
            ':userId'    => (int) $userId,
        ]);

        return $result ? true : false;
    } catch (PDOException $e) {
        error_log('Update Server Error: ' . $e->getMessage());
        return false;
    }
}
