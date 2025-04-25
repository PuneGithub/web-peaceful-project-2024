<?php
require_once("conn.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function createComment($conn, $postId, $userId, $text)
{

    $commentDate = date("Y-m-d H:i:s");

    $sqlCheck = "SELECT commentDate FROM comments WHERE userId = :userId ORDER BY commentDate DESC LIMIT 1";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bindParam(':userId', $userId);
    $stmtCheck->execute();
    $lastComment = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    $currentTime = new DateTime();

    if ($lastComment) {
        $lastPostTime = new DateTime($lastComment['commentDate']);
        $interval = $currentTime->diff($lastPostTime);

        if ($interval->s < 10 && $interval->i === 0) {
            return "<div class='alert-danger'>คุณโพสต์เร็วเกินไป โปรดรอ 10 วินาที</b></div>";
        }
    }


    $sql = "INSERT INTO comments (postId, userId, text, commentDate) VALUES (:postId, :userId, :text, :commentDate)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':postId', $postId);
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':text', $text);
    $stmt->bindParam(':commentDate', $commentDate);

    $stmt->execute();

    // สร้าง HTML ของคอมเมนต์ใหม่ แล้วส่งกลับไปแสดง
    return "
        <div class='mb-2 border-b pb-2'>
            <div class='flex items-center gap-2'>
                
                
                <span class='text-xs text-gray-400'>{$commentDate}</span>
            </div>
            <p class='text-gray-700 mt-1 text-sm'>" . htmlspecialchars($text) . "</p>
        </div>";
}

function getCommentByPostId($conn, $postId)
{
    $sql = "SELECT comments.*, users.username, users.profileImage
    FROM comments
    JOIN users ON comments.userId = users.userId
    WHERE postId = ?
    ORDER BY commentDate DESC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$postId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//Comment System
if (isset($_SESSION['userId'])) {
    if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['btnComment'])) {
        $postId = $_POST['postId'] ?? null;
        $text = $_POST['comment'] ?? null;
        $userId = $_SESSION['userId'] ?? null;

        if (!$postId || !$text || !$userId) {
            echo "<div class='text-red-500 text-sm'>เกิดข้อผิดพลาด: กรุณาล็อกอินก่อนแสดงความคิดเห็น</div>";
            exit;
        }

        echo createComment($conn, $postId, $userId, $text);
    }
}
