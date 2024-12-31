<?php
require_once 'conn.php';

function loginAdmin($conn, $identifier, $password)
{
    $sql = "SELECT * FROM users WHERE (email = :identifier OR username = :identifier) AND role = 'admin'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':identifier' => $identifier]);

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        return "ข้อมูลการเข้าสู่ระบบไม่ถูกต้อง";
    }

    if (password_verify($password, $admin['password'])) {

        $_SESSION['admin_logged_in'] = true;
        $_SESSION['userId'] = $admin['userId'];
        $_SESSION['username'] = $admin['username'];
        $_SESSION['role'] = $admin['role'];
        header('Location: ../administrator/dashboard.php');
        exit;
    }

    return "ข้อมูลการเข้าสู่ระบบไม่ถูกต้อง";
}


//manage users
function fetchUsers($conn)
{
    $sql = "SELECT * FROM users";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $users;
}

function countUsers($conn)
{
    $sql = "SELECT COUNT(*) as totalUsers FROM users";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['totalUsers'];
}

function deleteUser($conn, $userId)
{
    try {
        $stmt = $conn->prepare("DELETE FROM users WHERE userId = :userId");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (PDOException $error) {
        echo "Error:" . $error->getMessage();
        return false;
    }
}

//Manage Posts
function fetchAllPosts($conn)
{
    try {
        $sql = "SELECT posts.postId, posts.title, posts.content, posts.imagePost, posts.createdAt, users.username, users.userId  
        FROM posts
        JOIN users ON posts.userId = users.userId";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $error) {
        echo "Error: " . $error->getMessage();
        return [];
    }
}

function countPosts($conn)
{
    $sql = "SELECT COUNT(*) as totalPosts FROM posts";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['totalPosts'];
}

function deletePost($conn, $postId)
{
    try {
        $sql = "SELECT imagePost FROM posts WHERE postId = :postId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
        $stmt->execute();

        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post) {
            //delete image post
            if (!empty($post['imagePost'])) {
                $imagePath = '../img/posts_image/' . $post['imagePost'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $deleteSql = "DELETE FROM posts WHERE postId = :postId";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bindParam(':postId', $postId, PDO::PARAM_INT);
            $deleteStmt->execute();

            return ['status' => true, 'message' => "ทำการลบโพสต์แล้ว!"];
        } else {
            return ['status' => false, 'message' => "ไม่พบโพสต์ที่ต้องการลบ!"];
        }

    } catch (PDOException $error) {
        return ['status' => false, 'message' => "Error: " . $error->getMessage()];
    }
}
