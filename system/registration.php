<?php
require_once("conn.php");

$database = new Database();
$conn = $database->getConn();

function signup($username, $password, $email)
{
    global $conn;

    if (strlen($password) < 6) {
        return "Password must have at least 6 characters.";
    }

    if (!preg_match('/[a-zA-Z]/', $password)) {
        return "Password must contain at least on letter (a-z or A-Z)";
    }

    //Check Email
    $checkStmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $checkStmt->bindParam(':email', $email);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        return "This email is already registered.";
    }

    $hashPassword = password_hash($password, PASSWORD_DEFAULT);

    $status = "offline";

    $date = date("Y-m-d");

    try {
        $stmt = $conn->prepare("INSERT INTO users (username, password, email, createDate, status) VALUES (:username, :password, :email, :createDate, :status)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashPassword);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':createDate', $date);
        $stmt->bindParam(':status', $status);
        $stmt->execute();


        return true;
    } catch (Throwable $th) {
        return "เกิดข้อผิดพลาด: " . $th->getMessage();
    }
}

function login($identifier , $password)
{
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :identifier OR username = :identifier");
        $stmt->bindParam(":identifier", $identifier);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                
                $_SESSION['username'] = $user['username'];
                return true;
            }
        } else {
            return "Invalid password";
        }
    } catch (Throwable $th) {
        return "เกิดข้อผิดพลาด:" . $th->getMessage();
    }
}
