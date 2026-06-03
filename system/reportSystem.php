<?php

/** URL หน้าแจ้งปัญหา (รองรับ ?serverId= เมื่อรายงานจากหน้าเซิร์ฟเวอร์) */
function reportPageUrl($serverId = null)
{
    $url = base_url('report.php');
    $serverId = (int) $serverId;
    if ($serverId > 0) {
        $url .= '?serverId=' . $serverId;
    }
    return $url;
}

/** ตรวจว่า serverId ชี้ไปเซิร์ฟเวอร์ที่ approved จริง */
function resolveReportServerId($conn, $serverId)
{
    $serverId = (int) $serverId;
    if ($serverId <= 0) {
        return null;
    }

    try {
        $stmt = $conn->prepare(
            "SELECT serverId, serverName, serverSlug
             FROM servers
             WHERE serverId = :id AND status = 'approved'"
        );
        $stmt->execute([':id' => $serverId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    } catch (PDOException $e) {
        error_log("Resolve Report Server Error: " . $e->getMessage());
        return null;
    }
}

function addReport($conn, $userId, $topic, $type, $detail, $imageName, $ipAddress = null, $serverId = null)
{
    try {
        $sql = "INSERT INTO reports (userId, ipAddress, serverId, topic, type, detail, image, status) 
                VALUES (:userId, :ipAddress, :serverId, :topic, :type, :detail, :image, 'pending')";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':userId' => $userId,
            ':ipAddress' => $ipAddress,
            ':serverId' => $serverId,
            ':topic' => $topic,
            ':type' => $type,
            ':detail' => $detail,
            ':image' => $imageName
        ]);
        
    } catch (PDOException $e) {
        error_log("Add Report Error: " . $e->getMessage());
        return false;
    }
}

/**
 * นับจำนวนรายงานที่ส่งจาก IP เดิมภายใน X นาทีที่ผ่านมา (สำหรับ rate limiting)
 */
function countRecentReportsByIp($conn, $ipAddress, $minutes)
{
    try {
        $sql = "SELECT COUNT(*) FROM reports 
                WHERE ipAddress = :ipAddress 
                AND createdAt >= (NOW() - INTERVAL :minutes MINUTE)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':ipAddress', $ipAddress);
        $stmt->bindValue(':minutes', (int) $minutes, PDO::PARAM_INT);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Count Reports Error: " . $e->getMessage());
        return 0;
    }
}

// ดึงข้อมูลการแจ้งปัญหาทั้งหมด (เรียงจากล่าสุดไปเก่าสุด)
function getReportStatusLabels()
{
    return [
        ''          => 'ทั้งหมด',
        'pending'   => 'รอตรวจสอบ',
        'resolved'  => 'แก้ไขแล้ว',
        'dismissed' => 'เพิกเฉย',
    ];
}

function isValidReportStatus($status)
{
    return in_array($status, ['pending', 'resolved', 'dismissed'], true);
}

function countReports(PDO $conn, $status = null)
{
    try {
        $sql = 'SELECT COUNT(*) FROM reports r';
        $params = [];

        if ($status !== null && isValidReportStatus($status)) {
            $sql .= ' WHERE r.status = :status';
            $params[':status'] = $status;
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log('countReports: ' . $e->getMessage());
        return 0;
    }
}

function fetchReports(PDO $conn, $status = null, $limit = 20, $offset = 0)
{
    try {
        $sql = 'SELECT r.*, u.username, s.serverName, s.serverSlug
                FROM reports r
                LEFT JOIN users u ON r.userId = u.userId
                LEFT JOIN servers s ON r.serverId = s.serverId';
        $params = [];

        if ($status !== null && isValidReportStatus($status)) {
            $sql .= ' WHERE r.status = :status';
            $params[':status'] = $status;
        }

        $sql .= ' ORDER BY r.createdAt DESC LIMIT :limit OFFSET :offset';

        $stmt = $conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('fetchReports: ' . $e->getMessage());
        return [];
    }
}

/** คงไว้เพื่อ backward compatibility */
function fetchAllReports($conn)
{
    return fetchReports($conn, null, 1000, 0);
}

// อัปเดตสถานะการแจ้งปัญหา (เช่น pending -> resolved)
function updateReportStatus($conn, $reportId, $status)
{
    $sql = "UPDATE reports SET status = :status WHERE reportId = :reportId";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        ':status' => $status,
        ':reportId' => $reportId
    ]);
}

/**
 * ลบรายงานจาก DB และลบไฟล์รูปใน img/reports/ (ถ้ามี)
 */
function deleteReport($conn, $reportId)
{
    try {
        $stmt = $conn->prepare("SELECT image FROM reports WHERE reportId = :reportId");
        $stmt->execute([':reportId' => $reportId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false;
        }

        if (!empty($row['image'])) {
            $imageName = basename($row['image']);
            $filePath = dirname(__DIR__) . '/img/reports/' . $imageName;
            if (is_file($filePath)) {
                unlink($filePath);
            }
        }

        $deleteStmt = $conn->prepare("DELETE FROM reports WHERE reportId = :reportId");
        return $deleteStmt->execute([':reportId' => $reportId]);
    } catch (PDOException $e) {
        error_log("Delete Report Error: " . $e->getMessage());
        return false;
    }
}