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
        $_SESSION['verifyStatus'] = $admin['verifyStatus'];
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
        $stmtSelect = $conn->prepare("SELECT profileImage FROM users WHERE userId = :userId");
        $stmtSelect->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmtSelect->execute();
        $user = $stmtSelect->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['profileImage']) {
            $imageToDelete = "../img/profile_users/" . $user['profileImage'];

            if (file_exists($imageToDelete) && $user['profileImage'] !== 'default.webp') {
                unlink($imageToDelete);
            }
        }

        $stmtDelete = $conn->prepare("DELETE FROM users WHERE userId = :userId");
        $stmtDelete->bindParam(':userId', $userId, PDO::PARAM_INT);

        return $stmtDelete->execute();
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

function createBlog($conn, $userId, $blogTitle, $blogContent, $newImage, $metaDescription, $slug, $blogCategory)
{

    $maxSize = 3 * 1024 * 1024; // 3MB
    if (!empty($_FILES['blogImage']['name'])) {
        $uploadPathMap = [
            'papermc' => '../img/blogs_image/blogs_server/papermc/',
            'plugin' => '../img/blogs_image/blogs_plugin/plugin/',
        ];
        $uploadPath = $uploadPathMap[$blogCategory] ?? null;

        $fileName = "blog_" . time() . basename($_FILES['blogImage']['name']);
        $targetFilePath = $uploadPath . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        $allowTypes = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        if ($_FILES['blogImage']['size'] < $maxSize) {
            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($_FILES['blogImage']['tmp_name'], $targetFilePath)) {
                    $newImage = $fileName;
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

    $sql = "INSERT INTO blogs (userId, blogTitle, blogContent, createdAt, blogImage, metaDescription, slug, blogCategory) VALUES (:userId, :blogTitle, :blogContent, :createdAt, :blogImage, :metaDescription, :slug, :blogCategory)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':blogTitle', $blogTitle);
    $stmt->bindParam(':blogContent', $blogContent);
    $stmt->bindParam(':createdAt', $createdAt);
    $stmt->bindParam(':blogImage', $newImage);
    $stmt->bindParam(':metaDescription', $metaDescription);
    $stmt->bindParam(':slug', $slug);
    $stmt->bindParam(':blogCategory', $blogCategory);

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

function fetchEditBlog($conn, $blogId)
{

    $sql = "SELECT * FROM blogs WHERE blogId = :blogId";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':blogId', $blogId, PDO::PARAM_INT);
        $stmt->execute();

        // ดึงข้อมูล blog
        $fetchEditBlog = $stmt->fetch(PDO::FETCH_ASSOC);

        return $fetchEditBlog;
    } catch (PDOException $error) {
        // จัดการข้อผิดพลาดในกรณี SQL ล้มเหลว
        throw new Exception("Database error: " . $error->getMessage());
    }
}

function updateBlog($conn, $blogId, $blogTitle, $blogContent, $metaDescription, $blogCategory, $newImage, $oldImage)
{
    try {
        $finalImage = $oldImage;

        // ตรวจสอบว่ามีไฟล์รูปภาพใหม่ถูกอัปโหลดมาหรือไม่
        if (!empty($newImage['name'])) {
            // กำหนดพาธการอัปโหลดตามประเภทของบล็อก
            $upload_path_map = [
                'papermc' => '../img/blogs_image/blogs_server/papermc/',
                'plugin' => '../img/blogs_image/blogs_plugin/plugin/',
            ];

            $uploadPath = $upload_path_map[$blogCategory] ?? null;

            // ตรวจสอบว่าประเภทบล็อกถูกต้องหรือไม่
            if (!$uploadPath) {
                return false;
            }

            $fileType = strtolower(pathinfo($newImage['name'], PATHINFO_EXTENSION));
            $allowTypes = array('jpg', 'jpeg', 'png', 'gif', 'webp');

            // เช็คชนิด File Type
            if (!in_array($fileType, $allowTypes)) {
                return false;
            }

            // สร้างชื่อไฟล์ใหม่ที่ไม่ซ้ำกัน
            $newFileName = "blog_" . time() . '.' . $fileType;
            $targetFilePath = $uploadPath . $newFileName;

            // ย้ายไฟล์ใหม่ไปที่พาธที่ถูกต้อง
            if (move_uploaded_file($newImage['tmp_name'], $targetFilePath)) {
                $finalImage = $newFileName;

                // ลบรูปภาพเก่าถ้ามี และไม่ได้อยู่ในพาธเดียวกับรูปภาพใหม่
                if (!empty($oldImage) && file_exists($uploadPath . $oldImage) && ($oldImage !== $newFileName)) {
                    unlink($uploadPath . $oldImage);
                }
            } else {
                return false; // เกิดข้อผิดพลาดในการย้ายไฟล์
            }
        }

        // อัปเดตข้อมูลในฐานข้อมูล (ส่วนนี้จะถูกเรียกใช้เสมอ)
        $sqlUpdate = "UPDATE blogs 
                        SET blogTitle = :blogTitle, 
                            blogContent = :blogContent, 
                            metaDescription = :metaDescription,
                            blogImage = :blogImage,
                            blogCategory = :blogCategory
                        WHERE blogId = :blogId";

        $stmt = $conn->prepare($sqlUpdate);
        $stmt->bindParam(':blogTitle', $blogTitle, PDO::PARAM_STR);
        $stmt->bindParam(':blogContent', $blogContent, PDO::PARAM_STR);
        $stmt->bindParam(':metaDescription', $metaDescription, PDO::PARAM_STR);
        $stmt->bindParam(':blogImage', $finalImage, PDO::PARAM_STR);
        $stmt->bindParam(':blogCategory', $blogCategory, PDO::PARAM_STR);
        $stmt->bindParam(':blogId', $blogId, PDO::PARAM_INT);

        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function deleteBlog($conn, $blogId)
{
    //Path รูปภาพตาม blogCategory
    $imagePathMap = [
        'papermc' => '../img/blogs_image/blogs_server/papermc/',
        'plugin' => '../img/blogs_image/blogs_plugin/plugin/',
    ];

    try {
        $sqlSelect = "SELECT blogImage, blogCategory FROM blogs WHERE blogId = :blogId";
        $stmtSelect = $conn->prepare($sqlSelect);
        $stmtSelect->bindParam(':blogId', $blogId, PDO::PARAM_INT);
        $stmtSelect->execute();

        $blogSelect = $stmtSelect->fetch(PDO::FETCH_ASSOC);

        if ($blogSelect) {
            $blogImage = $blogSelect['blogImage'];
            $blogCategory = $blogSelect['blogCategory'];

            if (!empty($blogImage)) {
                $imagePath = $imagePathMap[$blogCategory] ?? null;
                if ($imagePath) {
                    $targetFilePath = $imagePath . $blogImage;
                    if (file_exists($targetFilePath)) {
                        unlink($targetFilePath);
                    }
                }
            }
            //delete data
            $sqlDelete = "DELETE FROM blogs WHERE blogId = :blogId";
            $stmtDelete = $conn->prepare($sqlDelete);
            $stmtDelete->bindParam(':blogId', $blogId, PDO::PARAM_INT);
            $stmtDelete->execute();
        }

        return true; //ลบสำเร็จ
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
