<?php
require_once "conn.php";

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

function fetchBlog($conn, $slug)
{
    $stmt = $conn->prepare("SELECT * FROM blogs WHERE slug = :slug LIMIT 1");
    $stmt->bindParam(':slug', $slug);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}