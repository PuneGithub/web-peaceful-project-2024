-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 27, 2024 at 02:10 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

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
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryId` int NOT NULL,
  `categoryName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci
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
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `postId` int NOT NULL,
  `userId` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  `categoryId` int NOT NULL,
  `viewCount` int NOT NULL DEFAULT '0',
  `imagePost` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`postId`, `userId`, `title`, `content`, `createdAt`, `categoryId`, `viewCount`, `imagePost`) VALUES
(2, 22, 'test1', 'test123456789', '2024-12-16 13:45:36', 1, 0, NULL),
(3, 22, 'Welcome', 'bababababa', '2024-12-17 13:44:12', 1, 0, NULL),
(4, 22, 'awdwad', 'dawd5555', '2024-12-17 13:44:50', 1, 0, NULL),
(5, 22, 'test2', 'test23456nnnnggregerg', '2024-12-18 13:59:30', 1, 0, 'post_1734530370My project-1 (6).png'),
(6, 22, 'testd234', 'dawghtretrhfawdwadasdsa', '2024-12-18 14:03:21', 1, 0, 'post_1734530601screenshot-1688223575498.png'),
(7, 22, 'awdad', 'fdfwfwefwefwefd', '2024-12-19 13:40:43', 1, 0, 'post_1734615643Hotpot.png'),
(8, 22, 'testawdaw', 'kfewjpfkjwepofkpwo&lt;br&gt;\nawdawddawdawdawdawdawdawd', '2024-12-19 14:07:40', 1, 0, NULL),
(9, 22, '&lt;h1&gt;Welcome&lt;/h1&gt;', '&lt;b&gt;Hello World&lt;/b&gt;', '2024-12-19 14:08:35', 1, 0, NULL),
(10, 22, 'cooldown1', '12121', '2024-12-19 14:31:39', 1, 0, NULL),
(11, 22, 'awdawda', 'adwawdawd', '2024-12-21 15:16:22', 1, 0, NULL),
(12, 22, 'wwwwwwwwwww', 'wwwwawda', '2024-12-21 15:17:37', 1, 0, NULL),
(13, 22, 'fawfawdawd', 'awdawdawd', '2024-12-22 03:16:05', 1, 0, NULL),
(14, 22, 'asdwadad', 'adwadawdad', '2024-12-22 03:17:16', 1, 0, NULL),
(15, 22, 'adwada546', 'sefsef45646', '2024-12-22 03:23:41', 1, 0, NULL),
(16, 22, 'dawdawd', 'wadawd', '2024-12-22 03:25:08', 1, 0, 'post_1734837908style4.png'),
(17, 22, '251646', '4654564', '2024-12-22 03:58:18', 1, 0, NULL),
(18, 22, 'ไฟก', 'ฟไกดพำเำพเดก', '2024-12-22 13:02:40', 1, 0, 'post_1734872560ดีไซน์ที่ยังไม่ได้ตั้งชื่อ.png'),
(19, 22, 'ไฟก', 'ฟไกดพำเำพเดก', '2024-12-22 13:05:35', 1, 0, 'post_1734872735ดีไซน์ที่ยังไม่ได้ตั้งชื่อ.png'),
(20, 24, 'test123', 'test1d654wa6d4awdawdawda&lt;br&gt;\r\nawdwadojoirgjoijoierjgiojoigrjeoijgoire\r\ngregojeorigjoerjgoijreoijgioerjgiojoijad', '2024-12-22 13:10:22', 1, 0, 'post_1734873022My project-1 (1).png'),
(21, 24, 'test123awd', 'awda4wd654adsd', '2024-12-23 14:06:14', 2, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `profileImage` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `verifyEmail` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `resetCode` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createDate` date NOT NULL,
  `verifyStatus` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'offline',
  `role` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `username`, `password`, `email`, `profileImage`, `verifyEmail`, `resetCode`, `createDate`, `verifyStatus`, `status`, `role`) VALUES
(22, 'test', '$2y$10$gteIkGR5TGNcSAYOFRLNne8ts5.g.RIxhUaq5pSrPuiuttbLHpeti', 'dev.peaceful@gmail.com', 'profile_1733493582.png', NULL, NULL, '2024-11-23', 'verified', 'offline', 'admin'),
(24, 'test123', '$2y$10$jlkbGxbBQm3qXc6aTvNhqekZYggJ5fNgxNgxX7/HfYrfYX7SLTY3C', 'minecraftpune@gmail.com', NULL, NULL, NULL, '2024-12-22', 'verified', 'offline', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryId`);

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
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `postId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

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
