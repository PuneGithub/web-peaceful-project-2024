# เก็บ HTML บทความ (ไม่ขึ้น production)

โฟลเดอร์นี้ใช้แก้/เก็บเนื้อหาบทความบนเครื่องเท่านั้น — **ไม่ต้องอัปโหลด FTP**

## วิธีใช้

1. แก้ไฟล์ `.html` ในโฟลเดอร์นี้
2. Copy เนื้อหาทั้งหมดไปวางในช่อง **เนื้อหาบทความ** ที่ `administrator/editBlog.php`
3. ตั้งค่า SEO ใน Edit Blog (title / description / keywords) แยกต่างหาก
4. รูปยังใช้ path เดิมบน server เช่น `../img/blogs_image/...` (อัปรูปที่ `img/` บน hosting ตามเดิม)

ลิงก์ภายในใช้ `[BASE_URL]` — ระบบแทนที่อัตโนมัติบนหน้า `blog.php`
