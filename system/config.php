<?php
require_once __DIR__ . "/helper.php";

/** Cloudflare Turnstile — site key ใช้ฝั่ง HTML, secret ใช้ฝั่ง server เท่านั้น */
define('TURNSTILE_SITE_KEY', '0x4AAAAAACnkmG5ox7p1kiZA');
define('TURNSTILE_SECRET_KEY', '0x4AAAAAACnkmFIyhYi6srxLOvFWKXCwL7g');

function verifyTurnstileToken(?string $token): bool
{
    if ($token === null || $token === '') {
        return false;
    }

    $ch = curl_init('https://challenges.cloudflare.com/turnstile/v0/siteverify');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'secret'   => TURNSTILE_SECRET_KEY,
        'response' => $token,
        'remoteip' => getClientIp(),
    ]);
    $result = json_decode(curl_exec($ch), true);

    return !empty($result['success']);
}

function base_url($path = '')
{
    // 1. ทำความสะอาด $path: ตัดเครื่องหมาย / ข้างหน้าออก (ถ้ามี)
    // จะได้ไม่เกิดปัญหา / หาย หรือ // เบิ้ลกัน
    $cleanPath = ltrim($path, '/');
    
    $hostUrl = $_SERVER['HTTP_HOST'] ?? '';

    // 2. ใช้ strpos เพื่อเช็คคำว่า 'localhost' เผื่อกรณีที่มี Port ติดมาด้วย (เช่น localhost:8080)
    if ($hostUrl !== '' && strpos($hostUrl, 'localhost') !== false) {
        // เติม / คั่นให้เรียบร้อย
        return '/web_peaceful_project_2024/' . $cleanPath;
    } else {
        // บนเซิร์ฟเวอร์จริง ให้ใส่ / นำหน้าเสมอ เพื่อให้อ้างอิงจากโฟลเดอร์ Root สุดของเว็บ
        return '/' . $cleanPath;
    }
}

function absolute_url($path = '')
{
    $scheme = 'http';
    if (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    ) {
        $scheme = 'https';
    }

    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    return $scheme . '://' . $host . base_url($path);
}