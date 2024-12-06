<?php
require_once("conn.php");

function uploadProfileImage()
{

    session_start();
    if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_FILES['profileImage'])) {
        $uploadPath = "../img/profile_users/";
        $uploadFile = $uploadPath . basename($_FILES['profileImage']['name']);
        $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $maxSize = 3 * 1024 * 1024; // 3 MB

        if (!in_array($fileType, $allowedTypes)) {
            return "Invalid file type. Allowed types:" . implode(", ", $allowedTypes);
        }

        if ($_FILES['profileImage']['size'] > $maxSize) {
            return "File size exceeds the maximum limit of 3 MB.";
        }

        $newFileImage = "profile_" . time() . $fileType;
        $uploadFile = $uploadPath . $newFileImage;

        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $uploadFile)) {

            $sql = "UPDATE users ";
        
            $_SESSION['profileImage'] = $uploadFile;
            header("Location: account.php?upload=success");
            exit;
        } else {
            return "Error uploading file.";
        }
        
    }
}