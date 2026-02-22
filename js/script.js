console.log("script.js loaded!");
//JavaScript toggle menu
function toggleMenu() {
    const menu = document.getElementById('mobile-menu')
    menu.classList.toggle('hidden')
}


document.addEventListener('DOMContentLoaded', function () {
    const loadMoreBtn = document.getElementById('loadMoreBtn');

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function () {

            // 1. หาบทความที่ซ่อนอยู่
            const hiddenItems = document.querySelectorAll('.blog-item-hidden');

            // 2. แสดงผลทั้งหมดทันที
            hiddenItems.forEach(function (item) {
                item.classList.remove('hidden');
                item.classList.remove('blog-item-hidden');
            });

            // 3. ซ่อนปุ่มกดทิ้งไปเลย (เพราะโหลดหมดแล้ว)
            document.getElementById('loadMoreContainer').style.display = 'none';

        });
    }

});


