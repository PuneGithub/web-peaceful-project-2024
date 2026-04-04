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

//สคริปต์เช็คสถานะเซิร์ฟเวอร์
document.addEventListener("DOMContentLoaded", function () {
    const statusElements = document.querySelectorAll('.server-status');

    statusElements.forEach(element => {
        const ip = element.getAttribute('data-ip');
        if (!ip) return;

        const setOnline = (players, max) => {
            element.innerHTML = `<i class="fa-solid fa-signal mr-1 text-green-500"></i> <span class="text-green-700">${players} / ${max}</span>`;
            element.className = "server-status inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-green-50";
        };

        const setOffline = () => {
            element.innerHTML = `<i class="fa-solid fa-power-off mr-1 text-red-500"></i> <span class="text-red-700">Offline</span>`;
            element.className = "server-status inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-50";
        };

        // ลองดึงจาก mcsrvstat.us ก่อน
        fetch(`https://api.mcsrvstat.us/3/${ip}`)
            .then(res => res.json())
            .then(data => {
                if (data.online) {
                    setOnline(data.players.online, data.players.max);
                } else {
                    // ถ้าไม่เจอ ลอง API สำรอง minetools.eu
                    fetch(`https://api.minetools.eu/ping/${ip.replace(':', '/')}`)
                        .then(res2 => res2.json())
                        .then(data2 => {
                            if (data2.error) {
                                setOffline();
                            } else {
                                setOnline(data2.players.online, data2.players.max);
                            }
                        })
                        .catch(() => setOffline());
                }
            })
            .catch(() => {
                element.innerHTML = `<i class="fa-solid fa-triangle-exclamation mr-1 text-gray-500"></i> Error`;
            });
    });
});


function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    
    // กำหนดสีตามประเภท
    const bgColor = type === 'success' ? 'bg-green-600' : 'bg-red-600';
    
    toast.className = `${bgColor} text-white px-6 py-3 rounded-xl shadow-lg transform transition-all duration-300 translate-y-10 opacity-0 flex items-center gap-2`;
    toast.innerHTML = `<i class="fa-solid fa-check-circle"></i> ${message}`;
    
    container.appendChild(toast);

    // Animation เด้งขึ้น
    setTimeout(() => {
        toast.classList.remove('translate-y-10', 'opacity-0');
    }, 10);

    // หายไปเองหลังจาก 3 วินาที
    setTimeout(() => {
        toast.classList.add('opacity-0', '-translate-y-10');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// 🚩 ปรับปรุงฟังก์ชัน Copy IP เดิม
function copyIP(id) {
    const text = document.getElementById(id).innerText.trim();
    navigator.clipboard.writeText(text).then(() => {
        // ใช้ Toast แทน Alert
        showToast("คัดลอก IP: " + text + " แล้ว!");
    });
}

// ฟังก์ชันสำหรับส่งคะแนนโหวต (Vote)
function castVote(serverId) {
    const formData = new FormData();
    formData.append('serverId', serverId);

    // 🚩 ตรวจสอบ Path ให้ตรงกับที่คุณวางไฟล์ api_vote.php ไว้
    // หากไฟล์อยู่ที่ system/api_vote.php และหน้า servers.php อยู่โฟลเดอร์หลัก ให้ใช้ path นี้
    fetch('system/api_vote.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            location.reload(); // รีโหลดหน้าเพื่ออัปเดตตัวเลขคะแนน
        } else {
            alert(data.message);
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert("เกิดข้อผิดพลาดในการเชื่อมต่อระบบโหวต");
    });
}



