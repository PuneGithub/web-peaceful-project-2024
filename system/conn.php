<?php
class Database {
    private $host = "localhost";
    private $dbname = "koonpune_peaceful_network";
    private $username = "root";
    private $password = "";
    private $conn;

    // Constructor ทำการเชื่อมต่อฐานข้อมูลอัตโนมัติเมื่อมีการสร้างออบเจกต์
    public function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset=utf8", $this->username, $this->password);

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (\Throwable $e) {
            echo "การเชื่อมต่อล้มเหลว: " . $e->getMessage();
            exit;
        }
    }

    public function getConn() 
    {
        return $this->conn;
    }
      
}

$database = new Database();
$conn = $database->getConn();
