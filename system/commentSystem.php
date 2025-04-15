<?php
require_once("conn.php");
function createComment($conn, $postId, $userId, $text) {

    $commentDate = date("Y-m-d H:i:s");

    $sqlCheck = "SELECT commentDate FROM comments WHERE userId = :userId";

    $currentTime = new DateTime();


    $sql = "INSERT INTO comments (postId, userId, text, commentDate) VALUES (:postId, :userId, :text, :commentDate)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':postId', $postId);
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':text', $text);
    $stmt->bindParam(':commentDate', $commentDate);

    $stmt->execute();

}

function getCommentByPostId($conn, $postId) {
    $sql = "SELECT comments.*, users.username, users.profileImage
    FROM comments
    JOIN users ON comments.userId = users.userId
    WHERE postId = ?
    ORDER BY commentDate ASC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$postId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}