<?php
require_once 'conn.php';

function createPost($conn, $userId, $title, $content, $imagePath, $categoryId)
{

    $sqlCheck = "SELECT createdAt FROM posts WHERE userId = :userId ORDER BY createdAt DESC LIMIT 1";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bindParam(':userId', $userId);
    $stmtCheck->execute();
    $lastPost = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    $currentTime = new DateTime();
    if ($lastPost) {
        $lastPostTime = new DateTime($lastPost['createdAt']);
        $interval = $currentTime->diff($lastPostTime);

        if ($interval->s < 60 && $interval->i === 0) {
            return "<div class='alert-danger'>คุณโพสต์เร็วเกินไป โปรดรอ 60 วินาที</b></div>";
        }
    }

    $maxSize = 3 * 1024 * 1024; // 3MB

    if (!empty($_FILES['imagePost']['name'])) {
        $uploadPath = "img/posts_image/";
        $fileName = "post_" . time() . basename($_FILES['imagePost']['name']);
        $targetFilePath = $uploadPath . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        $allowTypes = array('jpg', 'jpeg', 'png', 'gif');
        if ($_FILES['imagePost']['size'] < $maxSize) {
            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($_FILES['imagePost']['tmp_name'], $targetFilePath)) {
                    $imagePath = $fileName;
                } else {
                    return "<div class='alert-danger'>เกิดข้อผิดพลาดในการอัปโหลดไฟล์</b></div>";
                }
            } else {
                return "<div class='alert-danger'>รองรับเฉพาะไฟล์ JPG, JPEG, PNG และ GIF เท่านั้น</b></div>";
            }
        } else {
            return "<div class='alert-danger'>รูปภาพต้องไม่เกินขนาด 3 MB</b></div>";
        }
    }

    $createdAt = date('Y-m-d H:i:s');

    $sql = "INSERT INTO posts (userId, title, content, createdAt, categoryId, imagePost) VALUES (:userId, :title, :content, :createAt, :categoryId, :imagePost)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':createAt', $createdAt);
    $stmt->bindParam(':categoryId', $categoryId);
    $stmt->bindParam(':imagePost', $imagePath);

    if ($stmt->execute()) {
        return "<div class='alert-green'>โพสต์แล้ว</b></div>";
    } else {
        return "<div class='alert-danger'>เกิดข้อผิดพลาดในการโพสต์</b></div>";
    }
}

function fetchAllPosts($conn)
{
    try {
        $sql = "SELECT posts.postId, posts.title, posts.content, posts.imagePost, posts.createdAt, users.username 
        FROM posts
        JOIN users ON posts.userId = users.userId
        ORDER BY posts.createdAt DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $error) {
        echo "Error:" . $error->getMessage();
        return [];
    }
}

function getCategory($conn)
{
    try {
        $sql = "SELECT * FROM category";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $error) {
        die("Error: " . $error->getMessage());
    }
}

function getPostsByCategory($conn, $categoryId)
{
    $sql = "SELECT 
                posts.postId,
                posts.title,
                posts.content,
                posts.imagePost,
                posts.createdAt,
                posts.viewCount,
                users.username,
                category.categoryName
            FROM
                posts
            INNER JOIN users ON posts.userId = users.userId
            INNER JOIN category ON posts.categoryId = category.categoryId
            WHERE
                posts.categoryId = :categoryId
            ORDER BY
                posts.createdAt DESC
            ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

