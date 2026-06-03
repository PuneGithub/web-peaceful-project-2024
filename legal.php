<?php
require_once("system/conn.php");
require_once("system/config.php");
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once __DIR__ . '/components/favicon.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="css/output.css">
    <title>ข้อกำหนดและนโยบาย - Zencrafterly</title>
</head>

<body class="min-h-screen bg-no-repeat bg-cover bg-center bg-fixed" style="background-image: url('img/bg.webp');">

    <?php include_once("components/header-navbar.php"); ?>

    <div class="container mx-auto p-4 md:p-10">
        <div class="card-white shadow-2xl p-8 md:p-12 max-w-5xl mx-auto">
            
            <header class="text-center mb-10">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">ข้อกำหนด และนโยบายเว็บไซต์</h1>
                <p class="text-gray-500 italic">Zencrafterly - แหล่งรวมบทความและสังคมชาว Minecraft</p>
            </header>
    
            <nav class="flex justify-center space-x-6 mb-12 p-4 bg-blue-50 rounded-xl border border-blue-100">
                <a href="#privacy" class="font-bold text-blue-600 hover:text-blue-800 transition"><i class="fa-solid fa-shield-halved mr-1"></i> นโยบายความเป็นส่วนตัว</a>
                <span class="text-gray-300">|</span>
                <a href="#terms" class="font-bold text-blue-600 hover:text-blue-800 transition"><i class="fa-solid fa-file-contract mr-1"></i> ข้อกำหนดและเงื่อนไข</a>
            </nav>
    
            <section id="privacy" class="mb-16 scroll-mt-24">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center font-bold text-xl mr-4 shadow-lg">1</div>
                    <h2 class="text-3xl font-bold text-gray-800">นโยบายความเป็นส่วนตัว (Privacy Policy)</h2>
                </div>
                <p class="text-gray-400 mb-6 border-b pb-2 text-sm">อัปเดตล่าสุด: 28/03/2026</p>
    
                <div class="space-y-6 text-gray-700 leading-relaxed">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">1.1 ข้อมูลที่เราจัดเก็บ</h3>
                        <p>เรามีการจัดเก็บข้อมูลส่วนบุคคลที่จำเป็นสำหรับการให้บริการสมาชิก ได้แก่ ชื่อผู้ใช้ (Username), ที่อยู่อีเมล (Email), และรูปโปรไฟล์ เพื่อใช้ในการยืนยันตัวตนและการติดต่อสื่อสารภายในระบบ</p>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">1.2 คุกกี้และการโฆษณา (Cookies and Advertising)</h3>
                        <p class="mb-3">เว็บไซต์นี้มีการใช้งานคุกกี้ (Cookies) เพื่อพัฒนาประสบการณ์การใช้งาน และเรามีการแสดงโฆษณาจากบุคคลที่สาม:</p>
                        <div class="bg-gray-50 p-5 rounded-lg border-l-4 border-blue-500">
                            <ul class="list-disc ml-6 space-y-2">
                                <li><strong>Google AdSense:</strong> Google ในฐานะผู้ให้บริการบุคคลที่สาม ใช้คุกกี้เพื่อแสดงโฆษณาบนเว็บไซต์นี้ โดยอิงตามการเข้าชมของผู้ใช้งาน</li>
                                <li>คุกกี้ DoubleClick ช่วยให้ Google และพาร์ทเนอร์แสดงโฆษณาที่เหมาะสมตามความสนใจของผู้ใช้</li>
                                <li>ผู้ใช้สามารถเลือกไม่รับการโฆษณาที่ปรับแต่งตามบุคคลได้ที่ <a href="https://www.google.com/settings/ads" target="_blank" class="text-blue-500 underline">Google Ads Settings</a></li>
                            </ul>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">1.3 ไฟล์บันทึกข้อมูล (Log Files)</h3>
                        <p>Zencrafterly ปฏิบัติตามขั้นตอนมาตรฐานในการใช้ไฟล์บันทึก ข้อมูลที่เก็บรวบรวมประกอบด้วย ที่อยู่ IP, ประเภทเบราว์เซอร์, วันที่และเวลาที่เข้าชม เพื่อใช้วิเคราะห์แนวโน้มและบริหารจัดการเว็บไซต์ ข้อมูลเหล่านี้ไม่มีการเชื่อมโยงกับข้อมูลที่สามารถระบุตัวบุคคลได้</p>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">1.4 การป้องกันข้อมูล</h3>
                        <p>เราให้ความสำคัญกับความปลอดภัยของข้อมูลสมาชิก รหัสผ่านของคุณจะถูกเข้ารหัสด้วยมาตรฐานความปลอดภัยสูง และจะไม่มีการนำข้อมูลไปเผยแพร่หรือขายให้กับบุคคลที่สามโดยเด็ดขาด</p>
                    </div>
                </div>
            </section>
    
            <section id="terms" class="mb-16 scroll-mt-24">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-green-600 text-white rounded-lg flex items-center justify-center font-bold text-xl mr-4 shadow-lg">2</div>
                    <h2 class="text-3xl font-bold text-gray-800">ข้อกำหนดและเงื่อนไข (Terms of Service)</h2>
                </div>
                <p class="text-gray-400 mb-6 border-b pb-2 text-sm">อัปเดตล่าสุด: 28/03/2026</p>
    
                <div class="space-y-6 text-gray-700 leading-relaxed">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">2.1 การใช้งานบัญชี</h3>
                        <p>ผู้ใช้ต้องรับผิดชอบในการรักษาความลับของบัญชีและรหัสผ่าน การกระทำใดๆ ที่เกิดขึ้นภายใต้บัญชีของท่านถือเป็นความรับผิดชอบของเจ้าของบัญชีนั้นๆ</p>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">2.2 เนื้อหาและการใช้งาน</h3>
                        <p>ห้ามผู้ใช้โพสต์เนื้อหาที่ผิดกฎหมาย, ละเมิดลิขสิทธิ์, หมิ่นประมาทบุคคลอื่น หรือเนื้อหาที่ขัดต่อศีลธรรมอันดีงาม หากตรวจสอบพบทีมงานขอสงวนสิทธิ์ในการระงับบัญชีโดยไม่ต้องแจ้งให้ทราบล่วงหน้า</p>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">2.3 การจำกัดความรับผิดชอบ</h3>
                        <p>Zencrafterly เป็นเพียงแพลตฟอร์มในการแชร์ความรู้และโปรโมทเซิร์ฟเวอร์ เราไม่รับผิดชอบต่อความเสียหายใดๆ ที่เกิดขึ้นจากการติดต่อสื่อสารหรือการทำธุรกรรมระหว่างผู้ใช้งานกันเอง</p>
                    </div>
                </div>
            </section>
<!-- 
            <section class="mt-12 pt-8 border-t border-gray-100">
                <div class="bg-blue-600 rounded-2xl p-8 text-white text-center shadow-xl">
                    <h2 class="text-2xl font-bold mb-4">หากมีคำถามเพิ่มเติม?</h2>
                    <p class="mb-6 opacity-90">หากคุณมีข้อสงสัยเกี่ยวกับนโยบายความเป็นส่วนตัว หรือต้องการขอใช้สิทธิ์เกี่ยวกับข้อมูลส่วนบุคคล สามารถติดต่อเราได้ที่</p>
                    <div class="flex flex-col md:flex-row justify-center items-center space-y-4 md:space-y-0 md:space-x-6">
                        <div class="flex items-center">
                            <i class="fa-solid fa-envelope mr-2"></i>
                            <span class="font-bold">support@zencrafterly.com</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fa-solid fa-clock mr-2"></i>
                            <span>24/7 Support Support</span>
                        </div>
                    </div>
                </div>
            </section> -->
        </div>
    </div>

    <?php include_once("components/footer.php"); ?>

</body>
</html>