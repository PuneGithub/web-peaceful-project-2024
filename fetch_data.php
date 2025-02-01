<?php
    $url = 'https://jsonplaceholder.typicode.com/posts';
    $response = file_get_contents($url);

    if ($response === false) {
        echo json_encode(['error' => 'Unable to fetch data']);
    }
?>