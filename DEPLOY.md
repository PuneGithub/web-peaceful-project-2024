# คู่มือ Deploy (FileZilla)

ใช้เมื่อแก้โค้ดหลายไฟล์แล้วไม่แน่ใจว่าต้องอัปโหลดอะไรขึ้น production

## วิธีที่แนะนำ (ใช้ Git เป็นรายการไฟล์)

### 1) ดูรายการไฟล์ที่ต้องอัปโหลด

เปิด PowerShell ที่โฟลเดอร์โปรเจกต์ แล้วรัน:

```powershell
.\scripts\deploy-list.ps1
```

หรือดับเบิลคลิก `scripts\deploy-list.bat`

สคริปต์จะแสดงไฟล์ที่เปลี่ยนตั้งแต่ **ครั้ง deploy ล่าสุด** (อ้างอิง git tag `last-deploy`)

### 2) อัปโหลดใน FileZilla

- อัปโหลด **เฉพาะไฟล์ในรายการ** ไป path เดียวกันบน server
- ถ้ามีรูปใหม่ใน `img/` แต่ไม่โผล่ในรายการ → อาจยังไม่ได้ `git add` / commit ให้เช็คโฟลเดอร์ `img/` ด้วยตา
- ไฟล์ที่มัก **ไม่ต้อง** อัปทุกครั้ง: `.git/`, `node_modules/`, ไฟล์ `.sql` สำรอง DB

**ทางเลือกใน FileZilla:** เมนู **View → Directory comparison** หรือ **Synchronize** เปรียบเทียบ local กับ remote แล้วอัปเฉพาะที่ต่าง (ระวังอย่าลบไฟล์บน server โดยไม่ตั้งใจ)

### 3) หลังอัปโหลดเสร็จ — บันทึกจุด deploy

```powershell
.\scripts\deploy-list.ps1 -MarkDeployed
```

คำสั่งนี้ตั้ง tag `last-deploy` ที่ commit ปัจจุบัน — รอบถัดไปรายการจะเริ่มนับใหม่จากจุดนี้

---

## ตั้งจุดเริ่มครั้งแรก (ทำครั้งเดียว)

ถ้า production ตรงกับ commit บน GitHub แล้ว ให้รัน:

```powershell
.\scripts\deploy-list.ps1 -MarkDeployed
```

---

## บันทึกรอบ deploy (เขียนมือ — ช่วยจำภาพรวม)

เพิ่มหัวข้อด้านล่างทุกครั้งที่ขึ้น production จริง (สรุปสั้นๆ 2–3 บรรทัด)

### 2026-06-03 — ระบบเซิร์ฟ / resources / reports (commit 9bf6255)

- อัปโหลดตาม commit ใหญ่ครั้งแรก
- ตั้ง tag: `.\scripts\deploy-list.ps1 -MarkDeployed` หลังอัปเสร็จ

<!-- เพิ่มหัวข้อใหม่ด้านบนบรรทัดนี้ -->

---

## โฟลเดอร์ที่มักต้องอัปเมื่อแก้งานประเภทนั้น

| แก้อะไร | มักต้องอัป |
|--------|------------|
| หน้าเว็บ / logic | ไฟล์ `.php` ที่แก้ + `system/*.php` ที่เกี่ยว |
| สไตล์ | `css/output.css` |
| JS | `js/script.js` |
| รูปใหม่ | `img/...` เฉพาะไฟล์/โฟลเดอร์ที่เพิ่ม |
| แอดมิน | `administrator/...` |
| บัญชี | `account/...` |
| ส่วนร่วม | `components/...` |
| URL / SEO | `.htaccess`, `robots.txt`, `sitemap.php` |

**อย่าอัปขึ้น public โดยไม่ตั้งใจ:** `system/conn.php` ถ้า production มีค่า DB ของตัวเองแล้ว

---

## ถาม Cursor ก่อน deploy

ใน **Agent mode** พิมพ์:

> สรุปไฟล์ที่ต้อง upload ขึ้น production จาก git diff ตั้งแต่ last-deploy
