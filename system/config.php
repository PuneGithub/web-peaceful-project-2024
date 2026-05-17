<?php

function base_url($path = '')
{
    // 1. ทำความสะอาด $path: ตัดเครื่องหมาย / ข้างหน้าออก (ถ้ามี)
    // จะได้ไม่เกิดปัญหา / หาย หรือ // เบิ้ลกัน
    $cleanPath = ltrim($path, '/');
    
    $hostUrl = $_SERVER['HTTP_HOST'];

    // 2. ใช้ strpos เพื่อเช็คคำว่า 'localhost' เผื่อกรณีที่มี Port ติดมาด้วย (เช่น localhost:8080)
    if (strpos($hostUrl, 'localhost') !== false) {
        // เติม / คั่นให้เรียบร้อย
        return '/web_peaceful_project_2024/' . $cleanPath;
    } else {
        // บนเซิร์ฟเวอร์จริง ให้ใส่ / นำหน้าเสมอ เพื่อให้อ้างอิงจากโฟลเดอร์ Root สุดของเว็บ
        return '/' . $cleanPath;
    }
}