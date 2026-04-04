<?php
function createServer($conn, $userId, $name, $ip, $version, $category, $desc, $image)
{

    // 1. ใช้ trim() เพื่อตัดช่องว่างหน้า-หลัง IP ออกให้หมด
    $ip = trim($ip);

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

    $userSql = "SELECT createDate FROM users WHERE userId = :userId";
    $userStmt = $conn->prepare($userSql);
    $userStmt->execute([':userId' => $userId]);
    $userData = $userStmt->fetch(PDO::FETCH_ASSOC);

    if ($userData) {
        $regDate = new DateTime($userData['createDate']);
        $now = new DateTime();
        $interval = $regDate->diff($now);

        if ($interval->days >= 7) {
            $status = 'approved';
        }
    }


    try {
        $sql = "INSERT INTO servers (userId, serverName, serverIP, serverVersion, serverCategory, serverDescription, serverImage, status) 
                VALUES (:userId, :name, :ip, :version, :category, :desc, :image, :status)";
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([
            ':userId' => $userId,
            ':name' => $name,
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
