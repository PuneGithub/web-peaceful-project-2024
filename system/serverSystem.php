<?php
function createServer($conn, $userId, $name, $ip, $version, $category, $desc, $image)
{
    try {
        $sql = "INSERT INTO servers (userId, serverName, serverIP, serverVersion, serverCategory, serverDescription, serverImage, status) 
                VALUES (:userId, :name, :ip, :version, :category, :desc, :image, 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':userId' => $userId,
            ':name' => $name,
            ':ip' => $ip,
            ':version' => $version,
            ':category' => $category,
            ':desc' => $desc,
            ':image' => $image
        ]);
        return true;
    } catch (PDOException $e) {
         echo "Error updating view count: " . $e->getMessage();
    }
}
