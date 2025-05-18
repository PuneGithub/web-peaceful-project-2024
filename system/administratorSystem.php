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

function fetchEditUser($conn, $userId)
{

    $sql = "SELECT * FROM users WHERE userId = :userId";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // ดึงข้อมูลผู้ใช้
        $fetchEditUser = $stmt->fetch(PDO::FETCH_ASSOC);

        return $fetchEditUser;
    } catch (PDOException $error) {
        // จัดการข้อผิดพลาดในกรณี SQL ล้มเหลว
        throw new Exception("Database error: " . $error->getMessage());
    }
}


function editUser($conn, $userId, $username, $email, $role)
{
    $sql = "UPDATE users SET username = :username , email = :email , role = :role WHERE userId = :userId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':role', $role, PDO::PARAM_STR);

    $stmt->execute();

    return "Update Success.";
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

//manage blogs
function createSlug($url)
{
    //แปลงเป็นตัวพิมพ์เล็ก
    $slug = mb_strtolower($url, 'UTF-8');

    //แทนที่ space, tab, เครื่องหมายต่างๆ
    $slug = preg_replace('/[^\p{L}\p{N}]+/u', '-', $slug);

    //ลบ - ซ้ำๆ กัน
    $slug = preg_replace('/-+/', '-', $slug);
    //ตัด - หน้าหลัง
    $slug = trim($slug, '-');

    return $slug;
}

function createBlog($conn, $userId, $blogTitle, $blogContent, $blogImage, $slug)
{

    $maxSize = 3 * 1024 * 1024; // 3MB
    if (!empty($_FILES['blogImage']['name'])) {
        $uploadPath =  "../img/blogs_image/";
        $fileName = "blog_" . time() . basename($_FILES['blogImage']['name']);
        $targetFilePath = $uploadPath . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        $allowTypes = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        if ($_FILES['blogImage']['size'] < $maxSize) {
            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($_FILES['blogImage']['tmp_name'], $targetFilePath)) {
                    $blogImage = $fileName;
                } else {
                    return "<div class='alert-danger'>เกิดข้อผิดพลาดในการอัปโหลดไฟล์</b></div>";
                }
            } else {
                return "<div class='alert-danger'>รองรับเฉพาะไฟล์ JPG, JPEG, PNG, WEBP และ GIF เท่านั้น</b></div>";
            }
        } else {
            return "<div class='alert-danger'>รูปภาพต้องไม่เกินขนาด 3 MB</b></div>";
        }
    }

    $createdAt = date('Y-m-d H:i:s');

    $sql = "INSERT INTO blogs (userId, blogTitle, blogContent, createdAt, blogImage, slug) VALUES (:userId, :blogTitle, :blogContent, :createdAt, :blogImage, :slug)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':blogTitle', $blogTitle);
    $stmt->bindParam(':blogContent', $blogContent);
    $stmt->bindParam(':createdAt', $createdAt);
    $stmt->bindParam(':blogImage', $blogImage);
    $stmt->bindParam(':slug', $slug);

    if ($stmt->execute()) {
        return "<div class='alert-green'>เพิ่มบทความแล้ว</b></div>";
    } else {
        return "<div class='alert-danger'>เกิดข้อผิดพลาดในเพิ่มบทความ</b></div>";
    }
}

function fetchAllBlogs($conn)
{
    try {
        $sql = "SELECT blogs.*, users.username
                FROM blogs
                JOIN users ON blogs.userId = users.userId";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $error) {
        echo "Error: " . $error->getMessage();
        return [];
    }
}
