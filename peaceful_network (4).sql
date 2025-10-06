-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 06, 2025 at 01:22 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

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
  `blogImage` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `blogCategory` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  `metaDescription` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`blogId`, `userId`, `blogTitle`, `blogContent`, `blogImage`, `blogCategory`, `createdAt`, `metaDescription`, `slug`) VALUES
(4, 27, 'วิธีเปิดเซิร์ฟ Minecraft 2025: เล่นกับเพื่อนง่ายๆ ทำได้ด้วยตัวเอง', '<h2 class=\"text-3xl font-bold mb-2\" title=\"วิธีเปิดเซิร์ฟ Minecraft: เล่นกับเพื่อนง่ายๆ ทำได้ด้วยตัวเอง\">วิธีเปิดเซิร์ฟ Minecraft: เล่นกับเพื่อนง่ายๆ ทำได้ด้วยตัวเอง</h2>\r\n<p class=\"my-6\">สวัสดีครับชาว Minecraft ทุกคน! หากคุณกำลังมองหาวิธีสร้างเซิร์ฟเวอร์เพื่อเล่นกับเพื่อนๆ ในปี 2025 ไม่ต้องกังวลอีกต่อไป บทความนี้คือคู่มือฉบับสมบูรณ์ที่จะพาคุณไปเปิดเซิร์ฟเวอร์ Minecraft Java Edition</p>\r\n<h3 class=\"text-2xl font-semibold mb-2\" title=\"สิ่งที่ต้องเตรียมก่อนเปิดเซิร์ฟเวอร์ Minecraft\">สิ่งที่ต้องเตรียมก่อนเปิดเซิร์ฟเวอร์ Minecraft</h3>\r\n<p class=\"mb-2\">ก่อนที่เราจะลงมือเปิดเซิร์ฟเวอร์ มีสิ่งสำคัญบางอย่างที่คุณควรตรวจสอบและเตรียมให้พร้อม...</p>\r\n<ul>\r\n    <li>\r\n        <p class=\"font-bold mb-2\"><strong>สเปคขั้นต่ำสำหรับการเปิดเซิร์ฟเวอร์</strong></p>\r\n        <ul class=\"list-disc pl-5 mb-2\">\r\n            <li>\r\n                <h4 class=\"text-xl font-medium mb-1\">CPU</h4>\r\n                <ul class=\"list-disc pl-5\">\r\n                    <li>ขั้นต่ำ (สำหรับเล่นกับเพื่อน, ไม่เกิน 5 คน) ควรมี CPU จำนวนคอร์อย่างน้อย 2 Core ความเร็วประมาณ 2.5 Ghz ขึ้นไป</li>\r\n                    <li>แนะนำ (สำหรับผู้เล่น 5 - 10 คน หรือมี Plugin/Mod) ควรมี CPU จำนวนคอร์อย่างน้อย 4 Core ความเร็วประมาณ 3.0 ขึ้นไป</li>\r\n                </ul>\r\n            </li>\r\n            <li class=\"mt-2\">\r\n                <h4 class=\"text-xl font-medium mb-1\">RAM</h4>\r\n                <ul class=\"list-disc pl-5\">\r\n                    <li>ขั้นต่ำ (สำหรับเล่นกับเพื่อน, ไม่เกิน 5 คน) ควรมีอย่างน้อย 2 GB</li>\r\n                    <li>แนะนำ (สำหรับผู้เล่น 5 - 10 คน, มี Plugin/Mod) ควรมีอย่างน้อย 4 GB ขึ้นไป</li>\r\n                </ul>\r\n            </li>\r\n            <li class=\"mt-2\">\r\n                <h4 class=\"text-xl font-medium mb-1\">Storage</h4>\r\n                <ul class=\"list-disc pl-5\">\r\n                    <li>ขั้นต่ำ มีพื้นที่ว่างประมาณ 20 GB สำหรับไฟล์เซิร์ฟเวอร์ ไฟล์โลก และการสำรองข้อมูล (ควรจะเป็น SSD เพราะจะช่วยโหลดโลกและ Chunk ได้เร็วขึ้น)</li>\r\n                    <li>แนะนำ มีพื้นที่ว่างประมาณ 50 GB ขึ้นไป โดยจะมีผลอย่างมากหากคุณมีโลกขนาดใหญ่ และ Plugin/Mod เป็นจำนวนมาก</li>\r\n                </ul>\r\n            </li>\r\n        </ul>\r\n    </li>\r\n</ul>\r\n<h3 class=\"text-2xl font-semibold mb-2\" title=\"ตัวรันเซิร์ฟเวอร์ Minecraft มีอะไรบ้าง\">ตัวรันเซิร์ฟเวอร์ Minecraft หลักๆที่นิยมมีใช้กัน มีอะไรบ้าง สำหรับ Java Edition</h3>\r\n<ul class=\"list-disc pl-5 mb-2\">\r\n    <h4>Spigot/Bukkit</h4>\r\n    <li>Spigot เป็น Fork ที่พัฒนาต่อยอดจาก Bukkit ซึ้งเป็นแพลตฟอร์มที่อนุญาตให้ลง Plugin ได้</li>\r\n    <li>ข้อจำกัด ไม่รองรับ Mod โดยตรง</li>\r\n    <h4>PaperMC</h4>\r\n    <li>PaperMC เป็น Fork ที่พัฒนาต่อยอดจาก Spigot โดยเน้นการปรับปรุงประสิทธิภาพ และแก้ไขบั๊กต่างๆ</li>\r\n    <li>ลดอาการ Lag ได้ดีเยี่ยม เหมาะสำหรับเซิร์ฟเวอร์ที่มีผู้เล่นเยอะๆ และยังคงรองรับ Plugin ของ Bukkit/Spigot ได้</li>\r\n    <li>ข้อจำกัด ไม่รองรับ Mod โดยตรง</li>\r\n</ul>\r\n<h3 class=\"text-2xl font-semibold mb-2\">ขอยกตัวอย่างการเปิดเซิร์ฟเวอร์ Minecraft ด้วย PaperMC นะครับ</h3>\r\n<ul>\r\n    <h4 class=\"text-xl font-semibold mb-1\">ขั้นตอนที่ 1: เตรียม Folder Server และ File PaperMC</h4>\r\n    <ul>\r\n        <li>สร้าง Folder ใหม่บน Desktop หรือ Drive ที่คุณต้องการ</li>\r\n        <figure>\r\n            <img src=\"../img/blogs_image/blogs_server/papermc/papermc-01.png\" class=\"rounded-md\" alt=\"สร้าง Folder สำหรับเซิร์ฟเวอร์\">\r\n        </figure>\r\n        <li>ดาวโหลด File PaperMC ได้จากเว็บไซต์ PaperMC: <a href=\"https://papermc.io/\" target=\"_blank\">https://papermc.io/</a></li>\r\n        <figure>\r\n            <img src=\"../img/blogs_image/blogs_server/papermc/papermc-02.png\" class=\"rounded-md\" alt=\"ดาวโหลด File PaperMC\">\r\n        </figure>\r\n        <li>นำไฟล์ PaperMC ที่ดาวโหลดมาใส่ใน Folder ที่สร้างเอาไว้ และเปลี่ยนชื่อเป็น <strong>server.jar</strong> เพื่อความง่ายต่อการรันเซิร์ฟ</li>\r\n        <figure>\r\n            <img src=\"../img/blogs_image/blogs_server/papermc/papermc-03.png\" class=\"rounded-md\" alt=\"นำไฟล์ PaperMC ที่ดาวโหลดมาใส่ใน Folder ที่สร้างเอาไว้\">\r\n        </figure>\r\n    </ul>\r\n    <h4 class=\"text-xl font-semibold mb-1\">ขั้นตอนที่ 2: สร้าง File สำหรับรันเซิร์ฟเวอร์ (Script)</h4>\r\n    <ul>\r\n        <li>เปิด Notepad หรือ Text Editor อันไหนก็ได้</li>\r\n        <li>\r\n            Copy Code นี้ไปวาง:\r\n            <pre class=\"card-code-gray\"><code class=\"language-batch\">java -Xms2G -Xmx2G -jar server.jar --nogui pause</code></pre>\r\n            (ค่า -Xmx และ -Xms ควรปรับให้พอดีตาม RAM ที่ต้องการจัดสรร เช่นคอมพิวเตอร์มี RAM 4GB ควรใส่ค่า -Xms -Xmx เป็น 2GB ก็พอ เพื่อให้ระบบปฏิบัติการมี RAM เหลือใช้ด้วย)\r\n        </li>\r\n        <figure>\r\n            <img src=\"../img/blogs_image/blogs_server/papermc/server-01.png\" class=\"rounded-md\" alt=\"สร้างไฟล์ run.bat ใน Notepad\">\r\n            <figcaption>คัดลอกโค้ดลงใน Notepad </figcaption>\r\n        </figure>\r\n        <li>\r\n            ทำการบันทึก File เป็น \"run.bat\"\r\n            <figure>\r\n                <img src=\"../img/blogs_image/blogs_server/papermc/server-02.png\" class=\"rounded-md\" alt=\"บันทึก File เป็น run.bat\">\r\n            </figure>\r\n        </li>\r\n        <li>\r\n            ทำการคลิกที่ File \'run.bat\'\r\n            <figure>\r\n                <img src=\"../img/blogs_image/blogs_server/papermc/server-03.png\" class=\"rounded-md\" alt=\"ทำการคลิกที่ File \'run.bat\'\">\r\n            </figure>\r\n        </li>\r\n        <li>\r\n            ต้องไปปรับ File eula ก่อนถึงจะรันเซิร์ฟเวอร์ได้\r\n            <figure>\r\n                <img src=\"../img/blogs_image/blogs_server/papermc/server-04.png\" class=\"rounded-md\" alt=\"ต้องไปปรับ File eula ก่อนถึงจะรันเซิร์ฟเวอร์ได้\">\r\n            </figure>\r\n        </li>\r\n        <li>\r\n            แก้ไขตรง eula=false ให้แก้ไขเป็น eula=true และทำการบันทึก\r\n            <figure>\r\n                <img src=\"../img/blogs_image/blogs_server/papermc/server-05.png\" class=\"rounded-md\" alt=\"บันทึก File เป็น run.bat\">\r\n            </figure>\r\n        </li>\r\n        <li>\r\n            เมื่อแก้ไข eula แล้วให้ทำการรันเซิร์ฟอีกครั้ง เมื่อรันเซิร์ฟเสร็จแล้วให้พิมพ์คำสั่ง stop ก่อนเพื่อที่จะไปตั้งค่า server.properties\r\n            <figure>\r\n                <img src=\"../img/blogs_image/blogs_server/papermc/server-06.png\" class=\"rounded-md\" alt=\"เมื่อแก้ไข eula แล้วให้ทำการรันเซิร์ฟอีกครั้ง เมื่อรันเซิร์ฟเสร็จแล้วให้พิมพ์คำสั่ง stop ก่อนเพื่อที่จะไปตั้งค่า server.properties\">\r\n            </figure>\r\n        </li>\r\n    </ul>\r\n    <h4 class=\"text-xl font-semibold mb-1\">ขั้นตอนที่ 3: ตั้งค่าเซิร์ฟเวอร์ Minecraft และเข้าเซิร์ฟเวอร์ Minecraft</h4>\r\n    <ul>\r\n        <li>\r\n            หา File server.properties และทำการเปิดด้วย Notepad หรือ โปรแกรม Editor อืนๆ\r\n            <figure>\r\n                <img src=\"../img/blogs_image/blogs_server/papermc/server-07.png\" class=\"rounded-md\" alt=\"หา File server.properties\">\r\n            </figure>\r\n        </li>\r\n        <li>\r\n            ให้หาคำว่า online-mode จากนั้นปรับ online-mode ให้เป็น false เพื่อให้ผู้เล่น ID แท้ และ ID เถื่อนเข้าได้ แต่ถ้าหากอยากให้ผู้เล่น ID แท้เข้าได้เท่านั้นให้ปรับเป็น true\r\n            <figure>\r\n                <img src=\"../img/blogs_image/blogs_server/papermc/online-mode.png\" class=\"rounded-md\" alt=\"online-mode\">\r\n            </figure>\r\n        </li>\r\n        <li>\r\n            จากกดนั้น SAVE และทำการันเซิร์ฟอีกครั้ง\r\n            <figure>\r\n                <img src=\"../img/blogs_image/blogs_server/papermc/run-server.png\" class=\"rounded-md\" alt=\"run server\">\r\n            </figure>\r\n        </li>\r\n        <li>\r\n            เข้าเกม Minecraft และทำการใส่ IP 127.0.0.1 หรือ localhost ก็ได้จากนั้นกด Done และเข้าเซิร์ฟ\r\n            <figure>\r\n                <img src=\"../img/blogs_image/blogs_server/papermc/localhost.png\" class=\"rounded-md\" alt=\"localhost\">\r\n            </figure>\r\n        </li>\r\n        <li>\r\n            ขั้นตอนการเปิดเซิร์ฟเวอร์ Minecraft เป็นอันเสร็จสิน ถ้าอยากให้เพื่อนเข้ามาเล่นด้วยต้องใช้โปรแกรม ngrok\r\n            <figure>\r\n                <img src=\"../img/blogs_image/blogs_server/papermc/play.png\" class=\"rounded-md\" alt=\"play\">\r\n            </figure>\r\n        </li>\r\n    </ul>\r\n</ul>', 'blog_1757338579.png', 'papermc', '2025-07-06 07:39:53', 'วิธีเปิดเซิร์ฟ Minecraft 2025 เล่นกับเพื่อนง่ายๆ ทำได้ด้วยตัวเอง', 'how-to-start-a-minecraft-server-in-2025');

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
(1, 'Minecraft Java Edition', 'Minecraft Java Edition'),
(2, 'Minecraft Bedrock Edition', 'Minecraft Bedrock Edition'),
(3, 'Promote Minecraft Server', 'Promote Minecraft Server'),
(4, 'Other Games', 'Other Games');

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

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`commentId`, `postId`, `userId`, `text`, `commentDate`) VALUES
(60, 39, 27, 'muahahaha', '2025-04-14 03:40:45'),
(65, 39, 27, 'Hello World', '2025-04-15 02:28:32'),
(66, 39, 27, 'สหหหหหหหหหหหหห', '2025-04-15 02:29:01'),
(67, 39, 27, 'สหหหหหหหหหหหหห', '2025-04-15 02:29:14'),
(68, 39, 27, 'สหหหหหหหหหหหหห', '2025-04-15 02:29:27'),
(69, 39, 27, 'สหหหหหหหหหหหหห', '2025-04-15 02:29:46'),
(70, 39, 27, 'สหหหหหหหหหหหหห', '2025-04-15 02:30:15'),
(71, 39, 27, 'สหหหหหหหหหหหหห', '2025-04-15 02:30:25'),
(92, 39, 27, 'ไไไไไ', '2025-04-19 04:32:07'),
(93, 39, 27, 'ฟไกฟไกไฟ', '2025-04-19 04:32:17'),
(97, 39, 27, 'ดีๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆๆ', '2025-04-19 04:40:11'),
(98, 39, 27, '5555555555555555555555555555555555555959555555555555555555555555555555555555555555999999999999999999999999999999999999', '2025-04-19 04:40:31'),
(127, 55, 43, '5555', '2025-09-17 13:54:31'),
(128, 55, 43, 'สวัสดีครับ 555555+', '2025-09-17 13:54:45'),
(129, 39, 43, 'ฟไกฟไก', '2025-09-17 13:55:04'),
(130, 59, 27, '555', '2025-09-22 13:45:10');

-- --------------------------------------------------------

--
-- Table structure for table `lovelogs`
--

CREATE TABLE `lovelogs` (
  `loveId` int NOT NULL,
  `userId` int NOT NULL,
  `postId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lovelogs`
--

INSERT INTO `lovelogs` (`loveId`, `userId`, `postId`) VALUES
(106, 27, 39),
(105, 27, 59),
(103, 43, 39),
(102, 43, 55);

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

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`postId`, `userId`, `title`, `content`, `createdAt`, `categoryId`, `loveCount`, `imagePost`) VALUES
(39, 27, 'test123ฟฟรไฟกdawd', 'dwadawdfhwdwadawd&lt;br&gt;\r\ndaw4d65asddwad', '2025-03-05 13:38:08', 1, 3, 'post_17411829287c8ae7bf-71d5-49e2-9c16-3bcf82aadb5d.jpg'),
(55, 43, 'ทดสอบ Post', 'ทดสอบ Post ทดสอบ Post 555+', '2025-09-17 13:54:17', 1, 1, 'post_1758117365My project-1 (1).png'),
(56, 43, 'wda', 'fgregerg', '2025-09-18 13:49:20', 2, 0, NULL),
(57, 43, 'tessseers', 'eresrsersersrser', '2025-09-18 13:54:19', 1, 0, NULL),
(58, 43, 'ดำดไดไ', 'กฟไกไฟ', '2025-09-18 14:03:24', 1, 0, 'post_1758204204My project-1 (1).png'),
(59, 43, 'dwadad', 'safewfwef', '2025-09-18 14:06:03', 1, 1, 'post_1758204363ดีไซน์ที่ยังไม่ได้ตั้งชื่อ.png');

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
(27, 'test123', '$2y$10$P6s3We3rkqeMQZl9yEo.UO..qKEZQAHB1wRMk5uyCq0X.yNsxsLxW', 'dev.peaceful@gmail.com', 'profile_1746092128.png', NULL, NULL, '2025-01-28', 'verified', 'offline', 'admin'),
(29, 'dwadawd', '$2y$10$VNWUUkDtcHiafOqYsxD6XO1f1d7uka1cZwttiUU8gyRyBFwnAUwQm', 'wjf1oawdaw@gmail.com', NULL, '7b99387da6ffad9c93ecc9e693a7b7a8bd30a5dd213a17dea80114f0dec39fb8a394937330f114e87c16e5631fb832a79332', NULL, '2025-03-12', 'unverified', 'offline', 'user'),
(30, 'muhaha', '$2y$10$0Fw/bjdaZ.fqDHj/oEbG..hDcwYOZkh7Pbux4fsuPKAJ.WDydJttK', 'muhahahah@gmail.com', NULL, '38faaeb0bc9eef6a1db85087385ccb3769729beea459b1729bc565d1b0de272ba6fcc8728d428c582ba231462fb914c37e79', NULL, '2025-03-12', 'unverified', 'offline', 'user'),
(43, 'testsystem', '$2y$10$xmqio/3f2SJDi0oTT3wnz.yv4HgmC1wmlE.07MdL4v.mW94rP2mD2', 'minecraftpune@gmail.com', 'profile_1758117219.png', NULL, NULL, '2025-09-17', 'verified', 'offline', 'user');

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `blogId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
