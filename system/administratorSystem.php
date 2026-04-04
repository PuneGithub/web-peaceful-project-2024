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

function createBlog($conn, $userId, $blogTitle, $blogContent, $newImage, $slug, $categoryId, $blogCategoryStr, $seo_title, $seo_description, $seo_keywords)
{
    try {
        $imageName = NULL;
        $maxSize = 3 * 1024 * 1024; // 3MB

        // 1. จัดการเรื่องรูปภาพ (ใช้ค่าจาก $newImage ที่ส่งมาจากหน้าบ้าน)
        if (isset($newImage['name']) && $newImage['error'] === 0) {
            $uploadPathMap = [
                'papermc' => '../img/blogs_image/blogs_server/papermc/',
                'plugin' => '../img/blogs_image/blogs_plugin/plugin/',
                'server' => '../img/blogs_image/blogs_server/server/',
                ];
            $uploadPath = $uploadPathMap[$blogCategoryStr] ?? '../img/blogs_image/default/';

            $fileName = "blog_" . time() . "_" . basename($newImage['name']);
            $targetFilePath = $uploadPath . $fileName;
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

            $allowTypes = array('jpg', 'jpeg', 'png', 'gif', 'webp');
            
            if ($newImage['size'] <= $maxSize) {
                if (in_array($fileType, $allowTypes)) {
                    if (move_uploaded_file($newImage['tmp_name'], $targetFilePath)) {
                        $imageName = $fileName;
                    } else {
                        return "<div class='alert-danger'>เกิดข้อผิดพลาดในการอัปโหลดไฟล์</div>";
                    }
                } else {
                    return "<div class='alert-danger'>รองรับเฉพาะไฟล์ JPG, JPEG, PNG, WEBP และ GIF เท่านั้น</div>";
                }
            } else {
                return "<div class='alert-danger'>รูปภาพต้องไม่เกินขนาด 3 MB</div>";
            }
        }

        // 2. คำสั่ง SQL INSERT (ปรับให้ตรงกับ Table Structure ล่าสุด)
        // 🚩 ลบ metaDescription ออก และเพิ่ม seo_title, seo_description, seo_keywords, categoryId
        $sql = "INSERT INTO blogs (
                    userId, blogTitle, blogContent, blogImage, slug, 
                    categoryId, blogCategory, 
                    seo_title, seo_description, seo_keywords, createdAt
                ) VALUES (
                    :userId, :title, :content, :image, :slug, 
                    :catId, :catStr, 
                    :s_title, :s_desc, :s_key, NOW()
                )";

        $stmt = $conn->prepare($sql);
        
        $result = $stmt->execute([
            ':userId'   => $userId,
            ':title'    => $blogTitle,
            ':content'  => $blogContent,
            ':image'    => $imageName,
            ':slug'     => $slug,
            ':catId'    => $categoryId,    // 🚩 บันทึก ID ตัวเลข (แก้บัคที่เคยเจอ)
            ':catStr'   => $blogCategoryStr, // 🚩 บันทึกชื่อหมวดหมู่
            ':s_title'  => $seo_title,
            ':s_desc'   => $seo_description,
            ':s_key'    => $seo_keywords
        ]);

        if ($result) {
            return "<div class='alert-green'>เพิ่มบทความสำเร็จแล้ว!</div>";
        } else {
            return "<div class='alert-danger'>เกิดข้อผิดพลาดในการบันทึกข้อมูล</div>";
        }

    } catch (PDOException $e) {
        error_log($e->getMessage());
        return "<div class='alert-danger'>Database Error: " . $e->getMessage() . "</div>";
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

function updateBlog($conn, $blogId, $blogTitle, $blogContent, $blogCategory, $newImage, $oldImage, $seo_title, $seo_description, $seo_keywords, $slug) {
    try {
        $imageName = $oldImage; // ตั้งค่าเริ่มต้นเป็นชื่อรูปเดิม

        // 1. จัดการเรื่องรูปภาพ (ถ้ามีการอัปโหลดใหม่)
        if (isset($newImage) && $newImage['error'] === 0) {
            
            // กำหนด Path ตามหมวดหมู่ (เหมือนที่คุณใช้ในหน้าบ้าน)
            $paths = [
                'papermc' => '../img/blogs_image/blogs_server/papermc/',
                'plugin' => '../img/blogs_image/blogs_plugin/plugin/'
            ];
            
            $uploadPath = $paths[$blogCategory] ?? '../img/blogs_image/default/';
            
            // ลบรูปภาพเก่าทิ้งก่อน (ถ้ามีรูปเก่าและไฟล์นั้นมีอยู่จริง)
            if (!empty($oldImage) && file_exists($uploadPath . $oldImage)) {
                unlink($uploadPath . $oldImage);
            }

            // ตั้งชื่อไฟล์ใหม่เพื่อป้องกันชื่อซ้ำ
            $extension = pathinfo($newImage['name'], PATHINFO_EXTENSION);
            $imageName = bin2hex(random_bytes(10)) . '.' . $extension;

            // ย้ายไฟล์ขึ้นเซิร์ฟเวอร์
            if (!move_uploaded_file($newImage['tmp_name'], $uploadPath . $imageName)) {
                return false; // ถ้าย้ายไฟล์ไม่สำเร็จ ให้หยุดการทำงาน
            }
        }

        // 2. คำสั่ง SQL Update
        $sql = "UPDATE blogs SET 
                blogTitle = :title, 
                blogContent = :content, 
                blogCategory = :category, 
                blogImage = :image,
                slug = :slug,
                seo_title = :s_title,
                seo_description = :s_desc,
                seo_keywords = :s_key,
                updatedAt = NOW()
                WHERE blogId = :id";

        $stmt = $conn->prepare($sql);
        
        $result = $stmt->execute([
            ':title'    => $blogTitle,
            ':content'  => $blogContent,
            ':category' => $blogCategory,
            ':image'    => $imageName,
            ':slug'     => $slug,
            ':s_title'  => $seo_title,
            ':s_desc'   => $seo_description,
            ':s_key'    => $seo_keywords,
            ':id'       => $blogId
        ]);

        return $result;

    } catch (PDOException $e) {
        error_log($e->getMessage());
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

//Category
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

function createCategory($conn, $categoryName, $description)
{
    try {
        $sql = "INSERT INTO category (categoryName, description) VALUES (:categoryName, :description)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':categoryName', $categoryName);
        $stmt->bindParam(':description', $description);

        if ($stmt->execute()) {
            return "<div class='alert-green'>เพิ่มหมวดหมู่แล้ว</b></div>";
        } else {
            return "<div class='alert-danger'>เกิดข้อผิดพลาดในเพิ่มหมวดหมู่</b></div>";
        }
    } catch (PDOException $error) {
        die("Error:" . $error->getMessage());
    }
}

function deleteCategory($conn, $categoryId)
{
    try {
        //delete data
        $sqlDelete = "DELETE FROM category WHERE categoryId = :categoryId";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmtDelete->execute();

        return true; //ลบสำเร็จ
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function countCategory($conn)
{
    $sql = "SELECT COUNT(*) as totalCategory FROM category";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['totalCategory'];
}


//ลบเซิฟเวอร์
function deleteServer($conn, $serverId)
{
    $stmt = $conn->prepare("DELETE FROM servers WHERE serverId = :serverId");
    return $stmt->execute([':serverId' => $serverId]);
}

//ดึงข้อมูลเซิฟเวอร์ทั้งหมด
function fetchAllServers($conn)
{
    $stmt = $conn->query("SELECT * FROM servers ORDER BY createdAt DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//อนุมัติเซิร์ฟเวอร์ให้แสดงผลหน้าเว็บ
function approveServer($conn, $serverId)
{
    try {
        $sql = "UPDATE servers SET status = 'approved', updatedAt = NOW() WHERE serverId = :serverId";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':serverId' => $serverId]);
    } catch (PDOException $e) {
        error_log("Approve Error: " . $e->getMessage());
        return false;
    }
}

//ปฏิเสธการนำเซิร์ฟเวอร์ลงระบบ
function rejectServer($conn, $serverId)
{
    try {
        $sql = "UPDATE servers SET status = 'rejected', updatedAt = NOW() WHERE serverId = :serverId";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':serverId' => $serverId]);
    } catch (PDOException $e) {
        error_log("Reject Error: " . $e->getMessage());
        return false;
    }
}
