<?php
require_once("conn.php");

function uploadProfileImage($conn)
{
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

        $newFileImage = "profile_" . time() . '.' . $fileType;
        $uploadFile = $uploadPath . $newFileImage;

        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $uploadFile)) {
            $imagePath = $newFileImage;
            try {
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE users SET profileImage = :profileImage WHERE userId = :userId";

                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':profileImage' => $imagePath,
                    ':userId' => $_SESSION['userId']
                ]);
            
                $_SESSION['profileImage'] = $imagePath;

                $_SESSION['message'] = "Upload successful!";
                
                header("Location: account.php");
                exit;
            } catch (PDOException $error) {
                return "Database error: " . $error->getMessage();
            }
        } else {
            return "Error uploading file.";
        }
        
    }
}

function changePassword($conn , $oldPassword , $newPassword)
{

}