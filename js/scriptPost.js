// ใช้ querySelectorAll สำหรับปุ่มเปิด Modal
document.querySelectorAll('[id^="modalToggle-"]').forEach(function (toggleBtn) {
    toggleBtn.addEventListener('click', function () {
        // ดึง postId จาก id ของปุ่ม (สมมุติว่า id มีรูปแบบ modalToggle-{postId})
        var postId = this.id.split('-')[1];
        document.getElementById('modalBackdrop-' + postId).classList.remove('hidden');
    });
});

// ใช้ querySelectorAll สำหรับปุ่มปิด Modal
document.querySelectorAll('[id^="closeModal-"]').forEach(function (closeBtn) {
    closeBtn.addEventListener('click', function () {
        var postId = this.id.split('-')[1];
        document.getElementById('modalBackdrop-' + postId).classList.add('hidden');
    });
});

// ตัวเลือก: ปิด Modal เมื่อคลิกที่ backdrop
document.querySelectorAll('[id^="modalBackdrop-"]').forEach(function (backdrop) {
    backdrop.addEventListener('click', function (event) {
        // หากคลิกที่ backdrop (ไม่ใช่ Modal content)
        if (event.target === this) {
            this.classList.add('hidden');
        }
    });
});