-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 12, 2025 at 02:39 AM
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
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `commentId` int NOT NULL,
  `postId` int NOT NULL,
  `userId` int NOT NULL,
  `text` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
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

--
-- Dumping data for table `lovelogs`
--

INSERT INTO `lovelogs` (`loveId`, `userId`, `postId`) VALUES
(6, 32, 39),
(7, 32, 43);

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
  `loveCount` int NOT NULL DEFAULT '0',
  `imagePost` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`postId`, `userId`, `title`, `content`, `createdAt`, `categoryId`, `loveCount`, `imagePost`) VALUES
(39, 27, 'test123ฟฟรไฟกdawd', 'dwadawdfhwdwadawd&lt;br&gt;\r\ndaw4d65asddwad', '2025-03-05 13:38:08', 1, 1, 'post_17411829287c8ae7bf-71d5-49e2-9c16-3bcf82aadb5d.jpg'),
(43, 32, 'awdad4564', 'awdrf4w5ef4wef', '2025-03-23 12:23:09', 1, 1, NULL),
(44, 27, 'wdadad', 'efwefewf', '2025-04-07 02:54:19', 1, 0, NULL),
(45, 27, 'sadawdawd', 'sadawdawdawdawdawdawdawdawdawdawd\r\ndawdawdadaw\r\nawdawdfejfuihgihdshfiuhifuehuifhuif\r\ndjaiowjdojaoidjioe', '2025-04-07 03:03:54', 1, 0, NULL),
(46, 27, 'muhahahahahah', 'muhahahahahah', '2025-04-07 03:09:28', 1, 0, NULL);

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
(27, 'test123', '$2y$10$P6s3We3rkqeMQZl9yEo.UO..qKEZQAHB1wRMk5uyCq0X.yNsxsLxW', 'dev.peaceful@gmail.com', NULL, NULL, NULL, '2025-01-28', 'verified', 'offline', 'user'),
(28, 'pune2024', '$2y$10$s9suTUqwdWrWkseR5p7hrOz1oAaUvVaqjQlAzMc7akzUhBZRs.6pC', 'minecraftpune@gmail.com', NULL, NULL, NULL, '2025-03-07', 'verified', 'offline', 'user'),
(29, 'dwadawd', '$2y$10$VNWUUkDtcHiafOqYsxD6XO1f1d7uka1cZwttiUU8gyRyBFwnAUwQm', 'wjf1oawdaw@gmail.com', NULL, '7b99387da6ffad9c93ecc9e693a7b7a8bd30a5dd213a17dea80114f0dec39fb8a394937330f114e87c16e5631fb832a79332', NULL, '2025-03-12', 'unverified', 'offline', 'user'),
(30, 'muhaha', '$2y$10$0Fw/bjdaZ.fqDHj/oEbG..hDcwYOZkh7Pbux4fsuPKAJ.WDydJttK', 'muhahahah@gmail.com', NULL, '38faaeb0bc9eef6a1db85087385ccb3769729beea459b1729bc565d1b0de272ba6fcc8728d428c582ba231462fb914c37e79', NULL, '2025-03-12', 'unverified', 'offline', 'user'),
(32, 'test007', '$2y$10$AGMu1FZUxoH8C26eJOHFiusMFiRhfaw/YqaQ7KpMUrW4PojRSxSUO', 'ajwdjaoiwd@gmail.com', NULL, '6af9e1047a28ada22910cb23be58656649bfbdbf470c9031a06db333f96924f7b20e807db1fbb2f96155ba1f4419b6553c20', NULL, '2025-03-23', 'unverified', 'offline', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryId`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`commentId`);

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
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `commentId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lovelogs`
--
ALTER TABLE `lovelogs`
  MODIFY `loveId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `postId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

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
