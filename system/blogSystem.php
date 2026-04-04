<?php
require_once __DIR__ . "/conn.php";

function fetchAllBlogs($conn, $sort = 'latest', $limit = null, $offset = 0, $search = null, $categoryId = null)
{
    try {
        $sql = "SELECT blogs.*, users.username, users.profileImage
                FROM blogs
                JOIN users ON blogs.userId = users.userId
                WHERE 1=1";
        
        if ($categoryId !== null && $categoryId !== '') {
            $sql .= " AND blogs.categoryId = :categoryId";
        }

        if ($search !== null && $search !== '') {
            $sql .= " AND (blogs.blogTitle LIKE :search OR blogs.blogContent LIKE :search)";
        }

        if ($sort === 'popular') {
            $sql .= " ORDER BY blogs.views DESC";
        } else {
            $sql .= " ORDER BY blogs.createdAt DESC";
        }

        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $conn->prepare($sql);

        //Bind ค่า Category ID (ถ้ามี)
        if ($categoryId !== null && $categoryId !== '') {
            $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
        }

        //Bind ค่า Search (ถ้ามีการค้นหา)
        if ($search !== null && $search !== '') {
            $searchParam = "%$search%";
            $stmt->bindValue(':search', $searchParam, PDO::PARAM_STR);
        }

        // Bind ค่า Limit และ Offset ถ้ามีการส่งมา
        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $error) {
        echo "Error: " . $error->getMessage();
        return [];
    }
}

function countAllBlogs($conn, $categoryId = null) {
    $sql = "SELECT COUNT(*) FROM blogs WHERE 1=1";
    if ($categoryId) {
        $sql .= " AND categoryId = :catId";
    }
    
    $stmt = $conn->prepare($sql);
    if ($categoryId) {
        $stmt->bindValue(':catId', $categoryId, PDO::PARAM_INT);
    }
    $stmt->execute();
    return $stmt->fetchColumn();
}

function fetchBlog($conn, $slug)
{
    $stmt = $conn->prepare("SELECT * FROM blogs WHERE slug = :slug LIMIT 1");
    $stmt->bindParam(':slug', $slug);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function fetchLatestBlog($conn)
{
    try {
        $sql = "SELECT * FROM blogs ORDER BY createdAt DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $error) {
        echo "Error fetching latest blog:" . $error->getMessage();
        return null;
    }
}

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

function updateViewCount($conn, $blogId)
{
    try {
        $sql = "UPDATE blogs SET views = views + 1 WHERE blogId = :blogId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam('blogId', $blogId, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $error) {
        echo "Error updating view count: " . $error->getMessage();
    }
}
