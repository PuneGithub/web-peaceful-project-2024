<?php
require_once 'conn.php';

function createPost($conn, $userId, $title, $content)
{
    $sql = "INSERT INTO posts (userId, title, content) VALUES (:userId, :title, :content)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);

    if ($stmt->execute()) {
        return "โพสต์แล้ว";
    } else {
        return "เกิดข้อผิดพลาดในการโพสต์";
    }
}