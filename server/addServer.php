<?php
session_start();
require_once(__DIR__ . "/../system/conn.php");
require_once(__DIR__ . "/../system/config.php");
require_once(__DIR__ . "/../system/serverSystem.php");

//เช็ค login
if (!isset($_SESSION['userId'])) {
    $_SESSION['message'] = "โปรดเข้าสู่ระบบก่อนเพิ่มเซิร์ฟเวอร์";
    header("Location: ../account/login");
    exit();
}

$message = "";

// เมื่อมีการกดปุ่ม Add Server
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['btnAddServer'])) {
    $userId = $_SESSION['userId'];
    $serverName = htmlspecialchars($_POST['serverName']);
    $serverIP = htmlspecialchars($_POST['serverIP']);
    $serverVersion = htmlspecialchars($_POST['serverVersion']);
    $serverCategory = htmlspecialchars($_POST['serverCategory']);
    $serverDescription = $_POST['serverDescription'];
    $imageFile = $_FILES['serverImage'];

    // โค้ดสำหรับเช็คว่าฟอร์มส่งข้อมูลมาไหม (ถ้าหน้าจอขึ้นข้อมูล แปลว่าปุ่มทำงานปกติ)
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    $imageName = "default_server.webp";
    if ($imageFile['error'] === 0) {
        $ext = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
        $newName = "server_" . time() . "." . $ext;
        $uploadPath = __DIR__ . "/../img/server-icons/" . $newName;

        if (move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
            $imageName = $newName;
        }
    }

    $result = createServer($conn, $userId, $serverName, $serverIP, $serverVersion, $serverCategory, $serverDescription, $imageName);

    if ($result === true) {
        $message = "<div class='bg-green-100 text-green-700 p-3 rounded mb-4'>ส่งข้อมูลสำเร็จ!</div>";
    } elseif ($result === "IP_DUPLICATE") {
        $message = "<div class='bg-yellow-100 text-yellow-700 p-3 rounded mb-4'> IP นี้ถูกใช้งานแล้ว หรือกำลังรอการตรวจสอบ</div>";
    } else {
        $message = "<div class='bg-red-100 text-red-700 p-3 rounded mb-4'> เกิดข้อผิดพลาดในการบันทึกข้อมูล</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/output.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Manage Posts</title>
</head>

<body>
    <!-- header -->
    <?php include_once(__DIR__ . '/../components/header-navbar.php'); ?>

    <div class="max-w-4xl mx-auto py-10 px-4">
        <div class="card-white p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6"><i class="fa-solid fa-plus-circle mr-2 text-blue-500"></i>เพิ่มรายชื่อเซิร์ฟเวอร์</h1>

            <?php echo $message; ?>

            <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ชื่อเซิร์ฟเวอร์</label>
                        <input type="text" name="serverName" class="input-form" placeholder="Zencrafterly Survival" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">IP เซิร์ฟเวอร์</label>
                        <input type="text" name="serverIP" class="input-form" placeholder="play.zencrafterly.com" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">เวอร์ชั่น (Version)</label>
                        <input type="text" name="serverVersion" class="input-form" placeholder="1.20.1 - 1.21" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">หมวดหมู่</label>
                        <select name="serverCategory" class="input-form" required>
                            <option value="Survival">Survival</option>
                            <option value="Skyblock">Skyblock</option>
                            <option value="MiniGames">Mini Games</option>
                            <option value="MMORPG">MMORPG</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">คำอธิบายเซิร์ฟเวอร์</label>
                    <textarea name="serverDescription" rows="5" class="input-form" placeholder="บอกจุดเด่นของเซิร์ฟเวอร์คุณที่นี่..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">รูปภาพเซิร์ฟเวอร์ (Icon/Cover)</label>
                    <input type="file" name="serverImage" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <div class="pt-4">
                    <button type="submit" name="btnAddServer" class="w-full py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">
                        เพิ่มรายชื่อเซิร์ฟเวอร์
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- footer -->
    <?php include_once(__DIR__ . '/../components/footer.php'); ?>
</body>

</html>