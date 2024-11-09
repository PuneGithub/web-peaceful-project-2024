-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 09, 2024 at 12:10 PM
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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `verifyEmail` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `resetCode` mediumint DEFAULT NULL,
  `createDate` date NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `username`, `password`, `email`, `verifyEmail`, `resetCode`, `createDate`, `status`, `role`) VALUES
(1, 'test', '$2y$10$.imm9ghLgruAT83XCEyV8.0iUkBFlVQ65TnmTr4MNdvm9DIj4R/Kq', 'test@gmail.com', NULL, NULL, '2024-11-08', 'offline', 'user'),
(2, 'dwada', '$2y$10$8s7oWrZ9fYpWJ7keJr.g0OXcHTOXR8d7Jz3YSoAsP0MM4pdoWqpga', 'awdad@gmail.com', NULL, NULL, '2024-11-08', 'offline', 'user'),
(3, 'dawdw', '$2y$10$/HEjov4dsu93OiF7x1WEgeHaru74wayUAjLMfR7QmixSq7TfzRJSq', 'awdawdsssss@gmail.com', NULL, NULL, '2024-11-08', 'offline', 'user'),
(4, 'asdwadasdwad', '$2y$10$8ihU5KcSGv1M5iJWnLub7ejsVp6QT3FYpAcJrHaOdYWaHCRrtDHKu', 'awdawdawdsssss@gmail.com', NULL, NULL, '2024-11-08', 'offline', 'user'),
(5, '5555', '$2y$10$3U5fcnoijqzvBVnagotGq.h.UQ0EQ2/UAPwJJ0r4usVLsj8dU2Tbu', 'dawdssss@awda.net', NULL, NULL, '2024-11-08', 'offline', 'user'),
(6, 'test2', '$2y$10$ELdQwf8IIHcFIfZb7nLmLuS/KCTgVxuWiRzu1rcGVVpQfsZXo9OqW', 'test2@gmail.com', NULL, NULL, '2024-11-08', 'offline', 'user'),
(7, 'a b c', '$2y$10$b6.NQ2OI0AwLfuQlBfzyD.NdVZB7ewh50pPcncsjjQjuYm5gJ1YAK', 'awdabcccc@wda.com', NULL, NULL, '2024-11-08', 'offline', 'user'),
(8, 'aabbcc', '$2y$10$SSMY8O2A7vFFZmE6kLAChexomBjqaCcEvhuGRJ4r.CuM2S03ABCqu', 'waw@gmail.com', NULL, NULL, '2024-11-08', 'offline', 'user'),
(9, 'test3&lt;h1&gt;test4&lt;/h1&gt;', '$2y$10$/thbg72vPk3rV7kRwk7ubO9Qz6OlxlDyNOOM0Jz3lMR2eIPpzpRMq', 'test3test4@gmail.com', NULL, NULL, '2024-11-08', 'offline', 'user'),
(10, 'dawdawdawd', '$2y$10$FZVIbb0A76aXXgKu7OufkeWsrEBz50Y9/zWkxa4U34MrO3nVgOtH2', 'ssdadsasdsad@gmail.com', NULL, NULL, '2024-11-08', 'offline', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
