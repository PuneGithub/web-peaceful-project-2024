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
