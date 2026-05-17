<?php
require_once __DIR__ . '/conn.php';
require_once __DIR__ . '/config.php';

function loginAdmin(PDO $conn, $identifier, $password)
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
function fetchUsers(PDO $conn)
{
    $sql = "SELECT * FROM users";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $users;
}

function fetchEditUser(PDO $conn, $userId)
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


function editUser(PDO $conn, $userId, $username, $email, $role)
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

function deleteUser(PDO $conn, $userId)
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

function createBlog(PDO $conn, $userId, $blogTitle, $blogContent, $newImage, $slug, $categoryId, $blogCategoryStr, $seo_title, $seo_description, $seo_keywords)
{
    try {
        $imageName = NULL;
        $maxSize = 3 * 1024 * 1024; // 3MB

        // 1. จัดการเรื่องรูปภาพ (ใช้ค่าจาก $newImage ที่ส่งมาจากหน้าบ้าน)
        if (isset($newImage['name']) && $newImage['error'] === 0) {
            $uploadPathMap = [
                'papermc' => '../img/blogs_image/blogs_server/papermc/',
                'plugin'  => '../img/blogs_image/blogs_plugin/plugin/',
                'server'  => '../img/blogs_image/blogs_server/server/',
                'news'    => '../img/blogs_image/news/',
            ];

            $uploadPath = $uploadPathMap[$blogCategoryStr] ?? '../img/blogs_image/default/';

            // *** เพิ่มส่วนนี้: สร้างโฟลเดอร์อัตโนมัติถ้าไม่มี (ป้องกัน Error) ***
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

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

function fetchAllBlogs( PDO $conn)
{
    try {
        $sql = "SELECT blogs.*, users.username, category.categoryName
                FROM blogs
                JOIN category ON blogs.categoryId = category.categoryId
                JOIN users ON blogs.userId = users.userId";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $error) {
        echo "Error: " . $error->getMessage();
        return [];
    }
}

function fetchEditBlog(PDO $conn, $blogId)
{
    // 🚩 เปลี่ยน SQL ให้ดึง folderPath ออกมาด้วยการ JOIN
    $sql = "SELECT blogs.*, category.categoryName, storage.folderPath 
            FROM blogs 
            LEFT JOIN category ON blogs.categoryId = category.categoryId 
            LEFT JOIN category_storage AS storage ON blogs.categoryId = storage.categoryId 
            WHERE blogs.blogId = :blogId";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':blogId', $blogId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $error) {
        throw new Exception("Database error: " . $error->getMessage());
    }
}

function updateBlog(PDO $conn, $blogId, $blogTitle, $blogContent, $categoryId, $newImage, $oldImage, $seo_title, $seo_description, $seo_keywords, $slug)
{
    try {
        // 🚩 1. แก้ไขให้ดึง 'folderPath' จากตาราง 'category_storage'
        $stmtCat = $conn->prepare("SELECT folderPath FROM category_storage WHERE categoryId = :catId");
        $stmtCat->execute([':catId' => $categoryId]);
        $storage = $stmtCat->fetch(PDO::FETCH_ASSOC);

        // ถ้าหาไม่เจอ ให้ใช้ default
        $basePath = $storage['folderPath'] ?? 'img/blogs_image/default/';
        $uploadPath = '../' . $basePath; 

        $imageName = $oldImage;

        // จัดการอัปโหลดรูปภาพใหม่
        if (isset($newImage) && $newImage['error'] === 0) {
            // ลบรูปภาพเดิม
            if (!empty($oldImage) && file_exists($uploadPath . $oldImage)) {
                unlink($uploadPath . $oldImage);
            }

            $extension = pathinfo($newImage['name'], PATHINFO_EXTENSION);
            $imageName = bin2hex(random_bytes(10)) . '.' . $extension;

            if (!move_uploaded_file($newImage['tmp_name'], $uploadPath . $imageName)) {
                return false;
            }
        }

        // 🚩 2. อัปเดตข้อมูล (ใช้ categoryId แทน blogCategory)
        $sql = "UPDATE blogs SET 
                blogTitle = :title, 
                blogContent = :content, 
                categoryId = :catId, 
                blogImage = :image,
                slug = :slug,
                seo_title = :s_title,
                seo_description = :s_desc,
                seo_keywords = :s_key,
                updatedAt = NOW()
                WHERE blogId = :id";
        
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([
            ':title'   => $blogTitle,
            ':content' => $blogContent,
            ':catId'   => $categoryId,
            ':image'   => $imageName,
            ':slug'    => $slug,
            ':s_title' => $seo_title,
            ':s_desc'  => $seo_description,
            ':s_key'   => $seo_keywords,
            ':id'      => $blogId
        ]);

        return $result;
    } catch (PDOException $e) {
        // เอากลับมาใช้ error_log ตามปกติ (ลบ die() ออกได้เลย)
        error_log("Update Error: " . $e->getMessage());
        return false;
    }
}

function deleteBlog(PDO $conn, $blogId)
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
function getCategory(PDO $conn)
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

function getCategoryById(PDO $conn, int $categoryId)
{
    try {
        $sql = "SELECT * FROM category WHERE categoryId = :categoryId";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
    } catch (PDOException $error) {
        die("Error: " . $error->getMessage());
    }
}

function createCategory(PDO $conn, $categoryName, $description)
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

function updateCategory(PDO $conn, int $categoryId, string $categoryName, string $description): bool
{
    try {
        $sql = "UPDATE category SET categoryName = :categoryName, description = :description WHERE categoryId = :categoryId";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':categoryName', $categoryName);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);

        return $stmt->execute();
    } catch (PDOException $error) {
        die("Error: " . $error->getMessage());
    }
}

function countBlogsByCategory(PDO $conn, int $categoryId): int
{
    $sql = "SELECT COUNT(*) as total FROM blogs WHERE categoryId = :categoryId";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int) ($result['total'] ?? 0);
}

function deleteCategory(PDO $conn, int $categoryId): bool
{
    try {
        if (countBlogsByCategory($conn, $categoryId) > 0) {
            return false;
        }

        $sqlDelete = "DELETE FROM category WHERE categoryId = :categoryId";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);

        return $stmtDelete->execute();
    } catch (PDOException $e) {
        return false;
    }
}

function countCategory(PDO $conn)
{
    $sql = "SELECT COUNT(*) as totalCategory FROM category";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['totalCategory'];
}


//ลบเซิฟเวอร์
function deleteServer(PDO $conn, $serverId)
{
    $stmt = $conn->prepare("DELETE FROM servers WHERE serverId = :serverId");
    return $stmt->execute([':serverId' => $serverId]);
}

//ดึงข้อมูลเซิฟเวอร์ทั้งหมด
function fetchAllServers(PDO $conn)
{
    $stmt = $conn->query("SELECT * FROM servers ORDER BY createdAt DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//อนุมัติเซิร์ฟเวอร์ให้แสดงผลหน้าเว็บ
function approveServer(PDO $conn, $serverId)
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
function rejectServer(PDO $conn, $serverId)
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
