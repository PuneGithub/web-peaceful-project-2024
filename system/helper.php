<?php

/**
 * แหล่งข้อมูลหมวดหมู่เซิร์ฟเวอร์เพียงที่เดียว (single source of truth)
 * key = ค่าที่เก็บใน DB, value = ข้อความที่แสดงให้ผู้ใช้เห็น
 */
function getServerCategories()
{
    return [
        'Survival'  => 'Survival',
        'Skyblock'  => 'Skyblock',
        'MiniGames' => 'Mini Games',
        'MMORPG'    => 'MMORPG',
        'Mod'       => 'Mod',
        'Community' => 'Community',
        'other'     => 'Other',
    ];
}

/**
 * คืนค่า CSRF token ของ session ปัจจุบัน (สร้างใหม่ถ้ายังไม่มี)
 * ต้องเรียกหลัง session_start() แล้วเท่านั้น
 */
function csrfToken()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * สร้าง hidden input สำหรับใส่ในฟอร์ม POST
 */
function csrfField()
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrfToken(), ENT_QUOTES) . '">';
}

/**
 * ตรวจสอบ token ที่ส่งมา เทียบกับใน session (ใช้ hash_equals กัน timing attack)
 */
function verifyCsrfToken($token)
{
    return !empty($_SESSION['csrf_token'])
        && is_string($token)
        && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * คืนค่า IP จริงของผู้ใช้
 * ถ้าเว็บอยู่หลัง Cloudflare ให้ใช้ค่าจาก header CF-Connecting-IP
 * (ไม่งั้น REMOTE_ADDR จะเป็น IP ของ Cloudflare เหมือนกันหมดทุกคน)
 */
function getClientIp()
{
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function createSlug($url)
{
    //แปลงเป็นตัวพิมพ์เล็ก
    $slug = mb_strtolower($url, 'UTF-8');

    //แทนที่ space, tab, เครื่องหมายต่างๆ
    $slug = preg_replace('/[^\p{L}\p{N}]+/u', '-', $slug);

    //ลบ - ซ้ำๆ กัน
    $slug = preg_replace('/-+/', '-', $slug);
    //ตัด - หน้าหลัง
    $slug = trim($slug, '-');

    return $slug;
}