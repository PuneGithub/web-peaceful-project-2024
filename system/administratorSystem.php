<?php
require_once 'conn.php';

function loginAdmin($conn, $identifier, $password)
{
    $sql = "SELECT * FROM users WHERE email = :identifier OR username = :identifier AND role = 'admin'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':identifier' => $identifier]);

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    $role = 'admin';

    if ($admin) {
        //Check Password
        if (password_verify($password, $admin['password'])) {
            if ($role === 'admin') {
                return "คุณเป็น admin";
            } else {
                return "คุณไม่ใช้ admin";
            }
        } else {
            return "Password ผิด";
        }
    }

}