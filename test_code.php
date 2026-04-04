<h2 class="text-3xl font-bold mb-2" title="วิธีใช้ ngrok เชื่อมต่อเซิร์ฟเวอร์ Minecraft: เล่นกับเพื่อนได้ทั่วโลก ไม่ต้อง Port Forward">วิธีใช้ ngrok เชื่อมต่อเซิร์ฟเวอร์ Minecraft: เล่นกับเพื่อนได้ทั่วโลก ไม่ต้อง Port Forward</h2>

<p class="my-6">สวัสดีครับ! หลังจากที่เราได้รู้วิธีเปิดเซิร์ฟเวอร์ Minecraft ในเครื่องตัวเองไปแล้ว ปัญหาใหญ่ที่หลายคนเจอคือ "เพื่อนที่อยู่คนละบ้านเข้าเล่นไม่ได้" ใช่ไหมครับ? ปกติเราต้องทำ Port Forward ที่เร้าเตอร์ซึ่งยุ่งยากมาก แต่วันนี้ผมมีวิธีที่ง่ายกว่านั้นด้วยการใช้ <strong>ngrok</strong> ครับ</p>

<h3 class="text-2xl font-semibold mb-2" title="ngrok คืออะไร">ngrok คืออะไร?</h3>
<p class="mb-2">ngrok คือโปรแกรมที่ทำหน้าที่สร้าง "อุโมงค์" (Tunnel) เชื่อมต่อจากคอมพิวเตอร์ของเราออกสู่โลกอินเทอร์เน็ต ทำให้เพื่อนสามารถเชื่อมต่อเข้าเซิร์ฟเวอร์ Minecraft ของเราได้โดยที่เราไม่ต้องไปตั้งค่าเร้าเตอร์ให้วุ่นวายครับ</p>

<ul>
<li>
<p class="font-bold mb-2"><strong>ข้อดีของการใช้ ngrok</strong></p>
<ul class="list-disc pl-5 mb-2">
<li>ไม่ต้องทำ Port Forwarding ที่เร้าเตอร์</li>
<li>ใช้งานได้ฟรี (สำหรับเวอร์ชันพื้นฐาน)</li>
<li>มีความปลอดภัยสูง เพราะไม่ต้องเปิดพอร์ตที่ตัวเครื่องโดยตรง</li>
<li>ตั้งค่าเสร็จภายในไม่กี่นาที</li>
</ul>
</li>
</ul>

<h3 class="text-2xl font-semibold mb-2">ขั้นตอนการใช้งาน ngrok เพื่อเปิดเซิร์ฟเวอร์</h3>
<ul>
<h4 class="text-xl font-semibold mb-1">ขั้นตอนที่ 1: สมัครสมาชิกและดาวน์โหลด ngrok</h4>
<ul>
<li>เข้าไปสมัครสมาชิกที่เว็บไซต์ <a href="https://ngrok.com/" target="_blank" class="text-blue-600 underline">ngrok.com</a> (แนะนำให้ Login ด้วย Google เพื่อความรวดเร็ว)</li>
<li>ดาวน์โหลดโปรแกรม ngrok สำหรับ Windows มาไว้ในเครื่อง</li>
<figure>
<img src="../img/blogs_image/blogs_server/ngrok/ngrok-01.png" class="rounded-md" alt="หน้าดาวน์โหลด ngrok">
</figure>
<li>แตกไฟล์ที่ดาวน์โหลดมาไว้ในโฟลเดอร์ที่คุณต้องการ (แนะนำให้วางไว้ในโฟลเดอร์เดียวกับไฟล์รันเซิร์ฟเวอร์ Minecraft ของคุณ)</li>
</ul>

<h4 class="text-xl font-semibold mb-1">ขั้นตอนที่ 2: เชื่อมต่อบัญชี (Authtoken)</h4>
<ul>
    <li>ในหน้าเว็บไซต์ ngrok Dashboard ให้ไปที่เมนู <strong>Your Authtoken</strong> แล้วคัดลอกรหัส Token ของคุณมา</li>
    <li>เปิดโปรแกรม <strong>ngrok.exe</strong> ขึ้นมา จากนั้นนำรหัสมาวางต่อท้ายคำสั่งนี้แล้วกด Enter:</li>
    <pre class="card-code-gray"><code class="language-batch">ngrok config add-authtoken รหัส_TOKEN_ของคุณ</code></pre>
    <figure>
        <img src="../img/blogs_image/blogs_server/ngrok/ngrok-02.png" class="rounded-md" alt="ใส่รหัส Authtoken">
    </figure>
</ul>

<h4 class="text-xl font-semibold mb-1">ขั้นตอนที่ 3: เริ่มการสร้างอุโมงค์เชื่อมต่อ (Start Tunnel)</h4>
<ul>
    <li>เมื่อเชื่อมต่อบัญชีเสร็จแล้ว ให้เปิดเซิร์ฟเวอร์ Minecraft ของคุณทิ้งไว้</li>
    <li>ที่โปรแกรม ngrok ให้พิมพ์คำสั่งเพื่อเปิดพอร์ต 25565 (พอร์ตมาตรฐานของ Minecraft) ดังนี้:</li>
    <pre class="card-code-gray"><code class="language-batch">ngrok tcp 25565</code></pre>
    <li>จากนั้นคุณจะเห็นหน้าจอแสดงสถานะ <strong>Forwarding</strong> พร้อมที่อยู่ IP สำหรับส่งให้เพื่อน</li>
    <figure>
        <img src="../img/blogs_image/blogs_server/ngrok/ngrok-03.png" class="rounded-md" alt="สถานะ Forwarding ของ ngrok">
    </figure>
</ul>

<h4 class="text-xl font-semibold mb-1">ขั้นตอนที่ 4: การแชร์ IP ให้เพื่อนเข้าเล่น</h4>
<ul>
    <li>ในบรรทัด <strong>Forwarding</strong> ให้คัดลอกที่อยู่หลังจาก <code>tcp://</code> เช่น <code>0.tcp.jp.ngrok.io:12345</code></li>
    <li>นำ IP นี้ส่งให้เพื่อนไปใส่ในช่อง <strong>Server Address</strong> ในเกม Minecraft</li>
    <figure>
        <img src="../img/blogs_image/blogs_server/ngrok/ngrok-04.png" class="rounded-md" alt="การใส่ IP ในเกม">
    </figure>
    <li><strong>ข้อควรระวัง:</strong> หากคุณใช้เวอร์ชันฟรี ทุกครั้งที่คุณปิดและเปิดโปรแกรม ngrok ใหม่ เลขพอร์ต (ตัวเลขด้านหลัง) จะเปลี่ยนไปเสมอ อย่าลืมส่ง IP ใหม่ให้เพื่อนด้วยนะครับ</li>
</ul>
</ul>

<div class="mt-8 p-6 bg-blue-50 rounded-2xl border-l-4 border-blue-500">
<h4 class="text-xl font-bold mb-2 text-blue-800">ย้อนกลับไปดูวิธีเตรียมเซิร์ฟเวอร์</h4>
<p class="text-gray-700">
หากใครยังไม่มีตัวเซิร์ฟเวอร์ Minecraft หรือยังไม่ได้เตรียมไฟล์ PaperMC แนะนำให้กลับไปดูคู่มือเริ่มต้นที่นี่ก่อนครับ เพื่อให้มั่นใจว่าเซิร์ฟเวอร์ของคุณรันได้ปกติก่อนที่จะเชื่อมต่อ ngrok
</p>
<p class="mt-4">
<strong>อ่านต่อที่นี่:</strong>
<a href="<?= base_url('/blog/how-to-setup-minecraft-server') ?>" class="text-blue-600 font-bold underline hover:text-blue-800 transition-colors">
วิธีเปิดเซิร์ฟ Minecraft: เล่นกับเพื่อนง่ายๆ ทำได้ด้วยตัวเอง
</a>
</p>
</div>