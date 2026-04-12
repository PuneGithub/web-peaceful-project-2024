-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 12, 2026 at 06:54 AM
-- Server version: 8.4.3
-- PHP Version: 8.5.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `peaceful_network`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `blogId` int NOT NULL,
  `userId` int NOT NULL,
  `blogTitle` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `blogContent` text COLLATE utf8mb4_general_ci,
  `views` int NOT NULL DEFAULT '0',
  `status` enum('draft','published','archived') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'published',
  `blogImage` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `seo_title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `seo_description` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `seo_keywords` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `categoryId` int DEFAULT NULL,
  `blogCategory` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `slug` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`blogId`, `userId`, `blogTitle`, `blogContent`, `views`, `status`, `blogImage`, `seo_title`, `seo_description`, `seo_keywords`, `categoryId`, `blogCategory`, `createdAt`, `updatedAt`, `slug`) VALUES
(4, 27, 'วิธีเปิดเซิร์ฟ Minecraft 2026: เล่นกับเพื่อนง่ายๆ ทำได้ด้วยตัวเอง', '<h2 class=\"text-3xl font-bold mb-2\" title=\"วิธีเปิดเซิร์ฟ Minecraft: เล่นกับเพื่อนง่ายๆ ทำได้ด้วยตัวเอง\">วิธีเปิดเซิร์ฟ Minecraft: เล่นกับเพื่อนง่ายๆ ทำได้ด้วยตัวเอง</h2>\r\n<p class=\"my-6\">สวัสดีครับชาว Minecraft ทุกคน! หากคุณกำลังมองหาวิธีสร้างเซิร์ฟเวอร์เพื่อเล่นกับเพื่อนๆ ในปี 2026 ไม่ต้องกังวลอีกต่อไป บทความนี้คือคู่มือฉบับสมบูรณ์ที่จะพาคุณไปเปิดเซิร์ฟเวอร์ Minecraft Java Edition</p>\r\n<h3 class=\"text-2xl font-semibold mb-2\" title=\"สิ่งที่ต้องเตรียมก่อนเปิดเซิร์ฟเวอร์ Minecraft\">สิ่งที่ต้องเตรียมก่อนเปิดเซิร์ฟเวอร์ Minecraft</h3>\r\n<p class=\"mb-2\">ก่อนที่เราจะลงมือเปิดเซิร์ฟเวอร์ มีสิ่งสำคัญบางอย่างที่คุณควรตรวจสอบและเตรียมให้พร้อม...</p>\r\n<ul>\r\n    <li>\r\n        <p class=\"font-bold mb-2\"><strong>สเปคขั้นต่ำสำหรับการเปิดเซิร์ฟเวอร์</strong></p>\r\n        <ul class=\"list-disc pl-5 mb-2\">\r\n            <li>\r\n                <h4 class=\"text-xl font-medium mb-1\">CPU</h4>\r\n                <ul class=\"list-disc pl-5\">\r\n                    <li>ขั้นต่ำ (สำหรับเล่นกับเพื่อน, ไม่เกิน 5 คน) ควรมี CPU จำนวนคอร์อย่างน้อย 2 Core ความเร็วประมาณ 2.5 Ghz ขึ้นไป</li>\r\n                    <li>แนะนำ (สำหรับผู้เล่น 5 - 10 คน หรือมี Plugin/Mod) ควรมี CPU จำนวนคอร์อย่างน้อย 4 Core ความเร็วประมาณ 3.0 ขึ้นไป</li>\r\n                </ul>\r\n            </li>\r\n            <li class=\"mt-2\">\r\n                <h4 class=\"text-xl font-medium mb-1\">RAM</h4>\r\n                <ul class=\"list-disc pl-5\">\r\n                    <li>ขั้นต่ำ (สำหรับเล่นกับเพื่อน, ไม่เกิน 5 คน) ควรมีอย่างน้อย 2 GB</li>\r\n                    <li>แนะนำ (สำหรับผู้เล่น 5 - 10 คน, มี Plugin/Mod) ควรมีอย่างน้อย 4 GB ขึ้นไป</li>\r\n                </ul>\r\n            </li>\r\n            <li class=\"mt-2\">\r\n                <h4 class=\"text-xl font-medium mb-1\">Storage</h4>\r\n                <ul class=\"list-disc pl-5\">\r\n                    <li>ขั้นต่ำ มีพื้นที่ว่างประมาณ 20 GB สำหรับไฟล์เซิร์ฟเวอร์ ไฟล์โลก และการสำรองข้อมูล (ควรจะเป็น SSD เพราะจะช่วยโหลดโลกและ Chunk ได้เร็วขึ้น)</li>\r\n                    <li>แนะนำ มีพื้นที่ว่างประมาณ 50 GB ขึ้นไป โดยจะมีผลอย่างมากหากคุณมีโลกขนาดใหญ่ และ Plugin/Mod เป็นจำนวนมาก</li>\r\n                </ul>\r\n            </li>\r\n        </ul>\r\n    </li>\r\n</ul>\r\n<h3 class=\"text-2xl font-semibold mb-2\" title=\"ตัวรันเซิร์ฟเวอร์ Minecraft มีอะไรบ้าง\">ตัวรันเซิร์ฟเวอร์ Minecraft หลักๆที่นิยมมีใช้กัน มีอะไรบ้าง สำหรับ Java Edition</h3>\r\n<ul class=\"list-disc pl-5 mb-2\">\r\n    <h4>Spigot/Bukkit</h4>\r\n    <li>Spigot เป็น Fork ที่พัฒนาต่อยอดจาก Bukkit ซึ้งเป็นแพลตฟอร์มที่อนุญาตให้ลง Plugin ได้</li>\r\n    <li>ข้อจำกัด ไม่รองรับ Mod โดยตรง</li>\r\n    <h4>PaperMC</h4>\r\n    <li>PaperMC เป็น Fork ที่พัฒนาต่อยอดจาก Spigot โดยเน้นการปรับปรุงประสิทธิภาพ และแก้ไขบั๊กต่างๆ</li>\r\n    <li>ลดอาการ Lag ได้ดีเยี่ยม เหมาะสำหรับเซิร์ฟเวอร์ที่มีผู้เล่นเยอะๆ และยังคงรองรับ Plugin ของ Bukkit/Spigot ได้</li>\r\n    <li>ข้อจำกัด ไม่รองรับ Mod โดยตรง</li>\r\n</ul>\r\n<h3 class=\"text-2xl font-semibold mb-2\">ขอยกตัวอย่างการเปิดเซิร์ฟเวอร์ Minecraft ด้วย PaperMC นะครับ</h3>\r\n<ul>\r\n    <h4 class=\"text-xl font-semibold mb-1\">ขั้นตอนที่ 1: เตรียม Folder Server และ File PaperMC</h4>\r\n    <ul>\r\n        <li>สร้าง Folder ใหม่บน Desktop หรือ Drive ที่คุณต้องการ</li>\r\n        <figure>\r\n            <img src=\"../img/blogs_image/blogs_server/papermc/papermc-01.png\" class=\"rounded-md\" alt=\"สร้าง Folder สำหรับเซิร์ฟเวอร์\">\r\n        </figure>\r\n        <li>ดาวโหลด File PaperMC ได้จากเว็บไซต์ PaperMC: <a href=\"https://papermc.io/\" target=\"_blank\">https://papermc.io/</a></li>\r\n        <figure>\r\n            <img src=\"../img/blogs_image/blogs_server/papermc/papermc-02.png\" class=\"rounded-md\" alt=\"ดาวโหลด File PaperMC\">\r\n        </figure>\r\n        <li>นำไฟล์ PaperMC ที่ดาวโหลดมาใส่ใน Folder ที่สร้างเอาไว้ และเปลี่ยนชื่อเป็น <strong>server.jar</strong> เพื่อความง่ายต่อการรันเซิร์ฟ</li>\r\n        <figure>\r\n            <img src=\"../img/blogs_image/blogs_server/papermc/papermc-03.png\" class=\"rounded-md\" alt=\"นำไฟล์ PaperMC ที่ดาวโหลดมาใส่ใน Folder ที่สร้างเอาไว้\">\r\n        </figure>\r\n    </ul>\r\n    <h4 class=\"text-xl font-semibold mb-1\">ขั้นตอนที่ 2: สร้าง File สำหรับรันเซิร์ฟเวอร์ (Script)</h4>\r\n    <ul>\r\n        <li>เปิด Notepad หรือ Text Editor อันไหนก็ได้</li>\r\n        <li>\r\n            Copy Code นี้ไปวาง:\r\n            <pre class=\"card-code-gray\"><code class=\"language-batch\">java -Xms2G -Xmx2G -jar server.jar --nogui pause</code></pre>\r\n            (ค่า -Xmx และ -Xms ควรปรับให้พอดีตาม RAM ที่ต้องการจัดสรร เช่นคอมพิวเตอร์มี RAM 4GB ควรใส่ค่า -Xms -Xmx เป็น 2GB ก็พอ เพื่อให้ระบบปฏิบัติการมี RAM เหลือใช้ด้วย)\r\n        </li>\r\n        <figure>\r\n            <img src=\"../img/blogs_image/blogs_server/papermc/server-01.png\" class=\"rounded-md\" alt=\"สร้างไฟล์ run.bat ใน Notepad\">\r\n            <figcaption>คัดลอกโค้ดลงใน Notepad </figcaption>\r\n        </figure>\r\n        <li>\r\n            ทำการบันทึก File เป็น \"run.bat\"\r\n            <figure>\r\n                <img src=\"../img/blogs_image/blogs_server/papermc/server-02.png\" class=\"rounded-md\" alt=\"บันทึก File เป็น run.bat\">\r\n            </figure>\r\n        </li>\r\n        <li>\r\n            ทำการคลิกที่ File \'run.bat\'\r\n            <figure>\r\n                <img src=\"../img/blogs_image/blogs_server/papermc/server-03.png\" class=\"rounded-md\" alt=\"ทำการคลิกที่ File \'run.bat\'\">\r\n            </figure>\r\n        </li>\r\n        <li>\r\n            ต้องไปปรับ File eula ก่อนถึงจะรันเซิร์ฟเวอร์ได้\r\n            <figure>\r\n                <img src=\"../img/blogs_image/blogs_server/papermc/server-04.png\" class=\"rounded-md\" alt=\"ต้องไปปรับ File eula ก่อนถึงจะรันเซิร์ฟเวอร์ได้\">\r\n            </figure>\r\n        </li>\r\n        <li>\r\n            แก้ไขตรง eula=false ให้แก้ไขเป็น eula=true และทำการบันทึก\r\n            <figure>\r\n                <img src=\"../img/blogs_image/blogs_server/papermc/server-05.png\" class=\"rounded-md\" alt=\"บันทึก File เป็น run.bat\">\r\n            </figure>\r\n        </li>\r\n        <li>\r\n            เมื่อแก้ไข eula แล้วให้ทำการรันเซิร์ฟอีกครั้ง เมื่อรันเซิร์ฟเสร็จแล้วให้พิมพ์คำสั่ง stop ก่อนเพื่อที่จะไปตั้งค่า server.properties\r\n            <figure>\r\n                <img src=\"../img/blogs_image/blogs_server/papermc/server-06.png\" class=\"rounded-md\" alt=\"เมื่อแก้ไข eula แล้วให้ทำการรันเซิร์ฟอีกครั้ง เมื่อรันเซิร์ฟเสร็จแล้วให้พิมพ์คำสั่ง stop ก่อนเพื่อที่จะไปตั้งค่า server.properties\">\r\n            </figure>\r\n        </li>\r\n    </ul>\r\n    <h4 class=\"text-xl font-semibold mb-1\">ขั้นตอนที่ 3: ตั้งค่าเซิร์ฟเวอร์ Minecraft และเข้าเซิร์ฟเวอร์ Minecraft</h4>\r\n    <ul>\r\n        <li>\r\n            หา File server.properties และทำการเปิดด้วย Notepad หรือ โปรแกรม Editor อืนๆ\r\n            <figure>\r\n                <img src=\"../img/blogs_image/blogs_server/papermc/server-07.png\" class=\"rounded-md\" alt=\"หา File server.properties\">\r\n            </figure>\r\n        </li>\r\n        <li>\r\n            ให้หาคำว่า online-mode จากนั้นปรับ online-mode ให้เป็น false เพื่อให้ผู้เล่น ID แท้ และ ID เถื่อนเข้าได้ แต่ถ้าหากอยากให้ผู้เล่น ID แท้เข้าได้เท่านั้นให้ปรับเป็น true\r\n            <figure>\r\n                <img src=\"../img/blogs_image/blogs_server/papermc/online-mode.png\" class=\"rounded-md\" alt=\"online-mode\">\r\n            </figure>\r\n        </li>\r\n        <li>\r\n            จากกดนั้น SAVE และทำการันเซิร์ฟอีกครั้ง\r\n            <figure>\r\n                <img src=\"../img/blogs_image/blogs_server/papermc/run-server.png\" class=\"rounded-md\" alt=\"run server\">\r\n            </figure>\r\n        </li>\r\n        <li>\r\n            เข้าเกม Minecraft และทำการใส่ IP 127.0.0.1 หรือ localhost ก็ได้จากนั้นกด Done และเข้าเซิร์ฟ\r\n            <figure>\r\n                <img src=\"../img/blogs_image/blogs_server/papermc/localhost.png\" class=\"rounded-md\" alt=\"localhost\">\r\n            </figure>\r\n        </li>\r\n        <li>\r\n            ขั้นตอนการเปิดเซิร์ฟเวอร์ Minecraft เป็นอันเสร็จสิน ถ้าอยากให้เพื่อนเข้ามาเล่นด้วยต้องใช้โปรแกรม ngrok\r\n            <figure>\r\n                <img src=\"../img/blogs_image/blogs_server/papermc/play.png\" class=\"rounded-md\" alt=\"play\">\r\n            </figure>\r\n        </li>\r\n    </ul>\r\n</ul>\r\n<div class=\"mt-8 p-6 bg-blue-50 rounded-2xl border-l-4 border-blue-500\">\r\n    <h4 class=\"text-xl font-bold mb-2 text-blue-800\">ขั้นตอนต่อไป: ทำให้เพื่อนเข้าเล่นได้!</h4>\r\n    <p class=\"text-gray-700\">\r\n        ยินดีด้วยครับ! ตอนนี้เซิร์ฟเวอร์ของคุณพร้อมใช้งานแล้ว แต่ในตอนนี้จะมีแค่คุณคนเดียวที่เข้าเล่นได้ \r\n        หากคุณต้องการให้เพื่อนๆ จากที่บ้านเข้ามาร่วมสนุกด้วยกัน <strong>ไม่ต้องทำ Port Forward</strong> ให้ยุ่งยากครับ \r\n    </p>\r\n    <p class=\"mt-4\">\r\n        <strong>อ่านต่อที่นี่:</strong> \r\n        <a href=\"/blog/how-to-use-ngrok-minecraft\" class=\"text-blue-600 font-bold underline hover:text-blue-800 transition-colors\">\r\n            วิธีใช้ ngrok เชื่อมต่อเซิร์ฟเวอร์ Minecraft เล่นกับเพื่อนได้ทั่วโลก\r\n        </a>\r\n    </p>\r\n</div>', 7, 'published', 'blog_1757338579.png', 'วิธีเปิดเซิร์ฟ Minecraft 2026: เล่นกับเพื่อนง่ายๆ ทำได้ด้วยตัวเอง', 'สวัสดีครับชาว Minecraft ทุกคน! หากคุณกำลังมองหาวิธีสร้างเซิร์ฟเวอร์เพื่อเล่นกับเพื่อนๆ ในปี 2026 ไม่ต้องกังวลอีกต่อไป บทความนี้คือคู่มือฉบับสมบูรณ์ที่จะพาคุณไปเปิดเซิร์ฟเวอร์ Minecraft Java Edition', 'วิธีเปิดเซิร์ฟ Minecraft', NULL, 'papermc', '2025-07-06 07:39:53', '2026-04-06 09:27:07', 'how-to-start-a-minecraft-server-in-2026'),
(9, 27, 'afwafawfawf', 'fwafawf', 0, 'published', 'blog_17693292137c8ae7bf-71d5-49e2-9c16-3bcf82aadb5d.jpg', NULL, NULL, NULL, NULL, 'plugin', '2026-01-25 08:20:13', NULL, 'sfwfffee'),
(10, 27, 'ร่นร่ฟนรก่นฟไก', 'wdawdawdsad', 2, 'published', 'blog_1769329323maxresdefault (3).webp', NULL, NULL, NULL, NULL, 'plugin', '2026-01-25 08:22:03', '2026-04-10 20:49:43', 'กกกากกไไไ'),
(11, 27, 'dsfsef', 'fffff', 0, 'published', 'blog_1769329368maxresdefault (7).webp', NULL, NULL, NULL, NULL, 'papermc', '2026-01-25 08:22:48', NULL, 'sefsefe'),
(12, 27, 'gfhfgh', 'ttytt', 1, 'published', 'blog_1769329426mq2.webp', NULL, NULL, NULL, NULL, 'papermc', '2026-01-25 08:23:46', '2026-02-19 21:40:45', 'fhtfhtfhjj'),
(13, 27, 'ffdbfdhdfhdrgd', 'hthtrhrgdrg', 0, 'published', 'blog_1769329445maxresdefault.jpg', NULL, NULL, NULL, NULL, 'plugin', '2026-01-25 08:24:05', NULL, 'hgfgdrghtjuyku'),
(16, 27, 'dddf', 'grthrthrth', 3, 'published', 'blog_1769831989maxresdefault (5).webp', NULL, NULL, NULL, NULL, 'papermc', '2026-01-31 03:59:49', '2026-03-22 21:06:13', 'efef'),
(17, 27, 'ทดสอบระบบ', '<h1>tester minecraft</h1>', 1, 'published', 'blog_1774619010_23f0cf70-2927-4b76-934f-cff8bca072e5.jpg', 'test1', 'test123456789', 'minecraft , test1', 1, 'papermc', '2026-03-27 20:43:30', '2026-03-27 20:43:55', 'tester-blog');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryId` int NOT NULL,
  `categoryName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`categoryId`, `categoryName`, `description`) VALUES
(1, 'papermc', 'papermc'),
(2, 'plugin', 'plugin'),
(14, 'server', 'server');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `commentId` int NOT NULL,
  `postId` int NOT NULL,
  `userId` int NOT NULL,
  `text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `commentDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lovelogs`
--

CREATE TABLE `lovelogs` (
  `loveId` int NOT NULL,
  `userId` int NOT NULL,
  `postId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `postId` int NOT NULL,
  `userId` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  `categoryId` int NOT NULL,
  `loveCount` int NOT NULL DEFAULT '0',
  `imagePost` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `servers`
--

CREATE TABLE `servers` (
  `serverId` int NOT NULL,
  `userId` int NOT NULL,
  `serverName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `serverIP` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `serverVersion` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `serverCategory` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `serverDescription` text COLLATE utf8mb4_general_ci,
  `serverImage` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `votes` int NOT NULL DEFAULT '0',
  `status` enum('pending','approved','rejected','') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `servers`
--

INSERT INTO `servers` (`serverId`, `userId`, `serverName`, `serverIP`, `serverVersion`, `serverCategory`, `serverDescription`, `serverImage`, `votes`, `status`, `createdAt`, `updatedAt`) VALUES
(1, 27, 'awdawdwa', '192.168.1.1', '1.21', 'Survival', 'efefwfsdfsef', 'default_server.webp', 5, 'approved', '2026-02-22 06:42:54', '2026-03-18 13:26:32'),
(6, 27, 'fesfsf', '192.168.2.2', '1.20', 'Survival', 'fawdawdawd', 'default_server.webp', 4, 'approved', '2026-02-26 13:41:53', '2026-03-18 13:26:36'),
(7, 27, 'adwadwad', 'play.mine.net', '1.20.1', 'Survival', 'ajdojawoidawd', 'default_server.webp', 1, 'approved', '2026-02-26 14:23:08', '2026-03-03 13:36:37'),
(12, 46, 'awdaw', '192.168.111111', '6546.1', 'Survival', 'dsfesfsf', 'default_server.webp', 0, 'approved', '2026-03-12 13:50:57', '2026-03-13 14:07:27'),
(14, 27, 'wdawdgrtesttest', '1.15.5145.1', '1.21', 'MMORPG', 'waegrdgdfgrgd', 'server_1773547243.jpg', 1, 'approved', '2026-03-15 04:00:43', '2026-03-15 04:00:54'),
(15, 27, 'Yokaicraft Server', 'Yokaicraft.net', '1.21', 'Survival', 'afawdfadawd test setsetse tsetset', 'default_server.webp', 1, 'approved', '2026-03-15 05:03:14', '2026-03-18 13:35:33'),
(16, 27, 'amorycraft', 'play.amorycraft.com', '1.21.11', 'Survival', 'amorycraft test test test\r\ndwadawd \r\ndawdawdad\r\nawdawdawdijawoifjhhsuiofsdf', 'default_server.webp', 1, 'approved', '2026-03-15 05:05:32', '2026-03-18 13:35:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `profileImage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `verifyEmail` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `resetCode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createDate` date NOT NULL,
  `verifyStatus` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'offline',
  `role` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `username`, `password`, `email`, `profileImage`, `verifyEmail`, `resetCode`, `createDate`, `verifyStatus`, `status`, `role`) VALUES
(27, 'test123', '$2y$10$aBi7q5TyKbPlv4ewVJcKjOA9JpoZJU.1umOFNOYjh6AwSDy1l73bm', 'dev.peaceful@gmail.com', 'profile_1746092128.png', NULL, NULL, '2025-01-28', 'verified', 'offline', 'admin'),
(29, 'dwadawd2', '$2y$10$VNWUUkDtcHiafOqYsxD6XO1f1d7uka1cZwttiUU8gyRyBFwnAUwQm', 'wjf1oawdaw@gmail.com', NULL, '7b99387da6ffad9c93ecc9e693a7b7a8bd30a5dd213a17dea80114f0dec39fb8a394937330f114e87c16e5631fb832a79332', NULL, '2025-03-12', 'verified', 'offline', 'user'),
(45, 'mine123', '$2y$10$AgNSm.tBb7MXISlG9FXPreyJ.EmCq7y/P2IRZUzbol0YnFWB1K8Fy', 'koonpune@gmail.com', NULL, NULL, NULL, '2026-02-26', 'verified', 'offline', 'user'),
(46, 'minetest', '$2y$10$VzwE15xxFFhTVbJdLrZV5.vKKWLkG3ghcqt.jBiuRLxltoF2jKCgq', 'minecraftpune@gmail.com', NULL, NULL, NULL, '2026-03-07', 'verified', 'offline', 'user'),
(47, 'awdad', '$2y$10$JTgrrOtq7CXQ0q1LAbc6kuaJnhE5DzWCrFtEELrHy0hnTUJUBeoUW', 'awda55s64wd@gmail.com', NULL, '1be4d4a83e1e1701208356e3630400a84822f054444bea284e2a4ff493679957335ae7f69b7b1430696680884ff09f77e154', NULL, '2026-03-07', 'unverified', 'offline', 'user'),
(48, 'a555', '$2y$10$JJEHYu7Vd/YATX1AXwm1Oe3586Bmqr1Y833m4tMNsKWPYw59sBCr6', 'awdawd@gmail.com', NULL, 'cfa8feb48368525b47aa96a5a9443d9e1a71b3257d51ef17f0670e23bd5a4d7db769f152a883dfa43bd4b2fd7d58c3345ecc', NULL, '2026-03-27', 'unverified', 'offline', 'user'),
(49, 'dwadawd', '$2y$10$t91PdwmVwaB7ol1hbRXBR.u.os7b5UKWmXH5Mcz7PlOLl/3pDV/0W', 'awdwad@gmail.com', NULL, '72d83c8c09e1815fa895890580776b1ad584292e36f7541088ab185fb21f4a48db7243b1f924a043af28f80e35b3ae26409a', NULL, '2026-03-28', 'unverified', 'offline', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `votes_log`
--

CREATE TABLE `votes_log` (
  `voteId` int NOT NULL,
  `serverId` int NOT NULL,
  `userId` int NOT NULL,
  `ipAddress` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `votes_log`
--

INSERT INTO `votes_log` (`voteId`, `serverId`, `userId`, `ipAddress`, `createdAt`) VALUES
(1, 1, 27, '::1', '2026-03-03 13:36:23'),
(2, 6, 27, '::1', '2026-03-03 13:36:32'),
(3, 7, 27, '::1', '2026-03-03 13:36:37'),
(6, 1, 46, '::1', '2026-03-07 06:20:25'),
(7, 6, 46, '::1', '2026-03-07 06:20:36'),
(8, 1, 47, '::1', '2026-03-07 06:29:57'),
(9, 1, 27, '::1', '2026-03-07 07:51:51'),
(10, 6, 27, '::1', '2026-03-07 07:51:55'),
(11, 14, 27, '::1', '2026-03-15 04:00:54'),
(12, 1, 27, '::1', '2026-03-18 13:26:32'),
(13, 6, 27, '::1', '2026-03-18 13:26:36'),
(14, 15, 27, '::1', '2026-03-18 13:35:33'),
(15, 16, 27, '::1', '2026-03-18 13:35:41');

-- --------------------------------------------------------

--
-- Table structure for table `websettings`
--

CREATE TABLE `websettings` (
  `webId` int NOT NULL,
  `webTitle` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'Zencrafterly',
  `webLogo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `webFavicon` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `heroTitle` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'Minecraft Zencrafterly',
  `heroSubtitle` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `webBg` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'bg.webp',
  `announceText` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `announceDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `footerText` varchar(255) COLLATE utf8mb4_general_ci DEFAULT '© 2024 Peaceful Network. All rights reserved.',
  `site_seo_title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `site_seo_description` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `site_seo_keywords` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `websettings`
--

INSERT INTO `websettings` (`webId`, `webTitle`, `webLogo`, `webFavicon`, `heroTitle`, `heroSubtitle`, `webBg`, `announceText`, `announceDate`, `footerText`, `site_seo_title`, `site_seo_description`, `site_seo_keywords`) VALUES
(1, 'Minecraft Zencrafterly', NULL, NULL, 'Minecraft Zencrafterly', NULL, 'bg.webp', 'ประกาศ ทดสอบ update !!!', '2025-12-10 04:10:33', '© 2026 Peaceful Network. All rights reserved.', 'Zencrafterly - แหล่งรวมบทความ สอน มายคราฟ & หาเซิร์ฟเวอร์เจ๋งๆ', 'Zencrafterly รวมบทความสอนมายคราฟ เทคนิคลับ และวิธีสร้างเซิฟ ค้นหาเซิฟเวอร์มายคราฟเจ๋งๆ หรือโปรโมทเซิฟเวอร์ของคุณฟรีวันนี้!', 'minecraft, มายคราฟ, สอนมายคราฟ, วิธีเล่นมายคราฟ, บทความมายคราฟ, เซิฟเวอร์มายคราฟ, โปรโมทเซิฟมายคราฟ, หาเซิฟมายคราฟ, วิธีสร้างเซิฟมายคราฟ, Zencrafterly');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`blogId`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryId`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`commentId`),
  ADD KEY `postId` (`postId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `lovelogs`
--
ALTER TABLE `lovelogs`
  ADD PRIMARY KEY (`loveId`),
  ADD UNIQUE KEY `unique_love` (`userId`,`postId`),
  ADD KEY `postId` (`postId`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`postId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `categoryId` (`categoryId`);

--
-- Indexes for table `servers`
--
ALTER TABLE `servers`
  ADD PRIMARY KEY (`serverId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `votes_log`
--
ALTER TABLE `votes_log`
  ADD PRIMARY KEY (`voteId`),
  ADD KEY `serverId` (`serverId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `websettings`
--
ALTER TABLE `websettings`
  ADD PRIMARY KEY (`webId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `blogId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `commentId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `lovelogs`
--
ALTER TABLE `lovelogs`
  MODIFY `loveId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `postId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `servers`
--
ALTER TABLE `servers`
  MODIFY `serverId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `votes_log`
--
ALTER TABLE `votes_log`
  MODIFY `voteId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `websettings`
--
ALTER TABLE `websettings`
  MODIFY `webId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `postId` FOREIGN KEY (`postId`) REFERENCES `posts` (`postId`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `userId` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `lovelogs`
--
ALTER TABLE `lovelogs`
  ADD CONSTRAINT `lovelogs_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `lovelogs_ibfk_2` FOREIGN KEY (`postId`) REFERENCES `posts` (`postId`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`categoryId`) REFERENCES `category` (`categoryId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `servers`
--
ALTER TABLE `servers`
  ADD CONSTRAINT `servers_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `votes_log`
--
ALTER TABLE `votes_log`
  ADD CONSTRAINT `votes_log_ibfk_1` FOREIGN KEY (`serverId`) REFERENCES `servers` (`serverId`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `votes_log_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
