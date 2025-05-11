<?php

function base_url($path = '')
{
    $hostUrl = $_SERVER['HTTP_HOST'];

    if ($hostUrl === 'localhost') {
        return '/web_peaceful_project_2024' . $path;
    } else {
        return $path;
    }
}
