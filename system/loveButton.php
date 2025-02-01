<?php
// include_once("conn.php");
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $data = json_decode(file_get_contents("php://input"));

//     $postId = $data->postId;

//     updateLoveCount($conn, $postId);

    
//     $loveCount = $conn->getLoveCount($postId);
    
//     $response = [
//         'loveCount' => $loveCount
//     ];
//     header('Content-Type: application/json');
//     echo json_encode($response);
// }