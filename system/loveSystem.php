<?php
require_once('conn.php');

function userHasLoved($conn, $postId, $userId) {
    $loveStmt = $conn->prepare("SELECT * FROM loveLogs WHERE postId = :postId AND userId = :userId");
    $loveStmt->execute(["postId" => $postId, ":userId" => $userId]);
    return $loveStmt->rowCount() > 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = $_POST['postId'];
    $userId = $_SESSION['userId'];

    $checkStmt = $conn->prepare("SELECT * FROM loveLogs WHERE userId = :userId AND postId = :postId");
    $checkStmt->bindParam(":userId", $userId, PDO::PARAM_INT);
    $checkStmt->bindParam(":postId", $postId, PDO::PARAM_INT);

    if ($checkStmt->rowCount() > 0) {
        //ถ้ากดไปแล้ว ลบ Love ออก
        $deleteStmt = $conn->prepare("DELETE FROM loveLogs WHERE userId = :userId AND postId = :postId");
        $deleteStmt->bindParam(":userId", $userId, PDO::PARAM_INT);
        $deleteStmt->bindParam(":userId", $userId, PDO::PARAM_INT);
        $deleteStmt->execute();

        //ลดจำนวน Love ใน posts
        $updateStmt = $conn->prepare("UPDATE posts SET loveCount = loveCount - 1 WHERE postId = :postId");
        $updateStmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        $updateStmt->execute();

        echo json_encode(["success" => true, "action" => "unliked"]);
    } else {
        // ถ้ายังไม่กด Love เพิ่ม Love
        $insertStmt = $conn->prepare("INSERT INTO loveLogs (userId, postId) VALUES (:userId,:postId)");
        $insertStmt->bindParam(":userId", $userId, PDO::PARAM_INT);
        $insertStmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        $insertStmt->execute();

        //เพิ่มจำนวน Love ใน Post
        $updateStmt = $conn->prepare("UPDATE posts SET loveCount = loveCount + 1 WHERE postId = :postId");
        $updateStmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        $updateStmt->execute();

        echo json_encode(["success" => true, "action" => "liked"]);
    }
}