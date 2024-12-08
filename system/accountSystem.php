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

                return "Upload successful!";
                
                exit;
            } catch (PDOException $error) {
                return "Database error: " . $error->getMessage();
            }
        } else {
            return "Error uploading file.";
        }
        
    }
}

function changePassword($conn , $userId, $oldPassword , $newPassword, $conPassword)
{
    if ($newPassword !== $conPassword) {
        return "New password and confirm password do not match.";
    }

    if (strlen($newPassword) < 6) {
        return "Password must have at least 6 characters.";
    }

    if (!preg_match('/[a-zA-Z]/', $newPassword)) {
        return "Password must contain at least on letter (a-z or A-Z)";
    }
    try {

        //Check Password
        $sql = "SELECT password FROM users WHERE userId = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return "User not found";
        }

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!password_verify($oldPassword, $user['password'])) {
            return "Incorrect old password";
        }

        $hashPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        //update password
        $updateSql = "UPDATE users SET password = :password WHERE userId = :userId";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bindParam(':password', $hashPassword, PDO::PARAM_STR);
        $updateStmt->bindParam(':userId', $userId, PDO::PARAM_INT);

        if ($updateStmt->execute()) {
            return "Password changed successfully";
        } else {
            return "Failed to change password.";
        }
    } catch (PDOException $error) {
        return "Error: " . $error->getMessage();
    }
}