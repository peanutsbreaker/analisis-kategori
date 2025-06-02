-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2025 at 06:12 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `expense_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `user_id`, `description`, `amount`, `category`, `date`) VALUES
(1, 1, 'pengeluaran dari gaji bulanan', 12000.00, 'Makanan', '2025-05-24 02:44:14'),
(4, 2, 'pengeluaran dari gaji bulanan', 10000.00, 'Makanan', '2025-05-24 04:37:37'),
(5, 2, 'pengeluaran dari gaji bulanan', 20000.00, 'Transportasi', '2025-05-25 14:21:55'),
(6, 2, 'pengeluaran dari gaji bulanan', 80000.00, 'Transportasi', '2025-05-25 14:22:10'),
(7, 2, 'pengeluaran dari gaji bulanan', 90000.00, 'Pendidikan', '2025-05-25 14:22:20'),
(8, 2, 'pengeluaran dari gaji bulanan', 20000.00, 'Hiburan', '2025-05-26 02:54:27');

-- --------------------------------------------------------

--
-- Table structure for table `salaries`
--

CREATE TABLE `salaries` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salaries`
--

INSERT INTO `salaries` (`id`, `user_id`, `salary`, `date`) VALUES
(1, NULL, 3000000.00, '2025-05-22 03:47:24'),
(2, NULL, 12000000.00, '2025-05-24 02:34:41'),
(3, NULL, 1000000.00, '2025-05-24 02:43:59'),
(4, NULL, 1000000.00, '2025-05-24 02:51:41'),
(5, NULL, 1000000.00, '2025-05-24 02:52:20'),
(6, NULL, 1000000.00, '2025-05-24 03:34:43'),
(7, 2, 10000000.00, '2025-05-24 03:47:23'),
(8, 2, 1000000.00, '2025-05-24 03:47:28'),
(9, 2, 1002000.00, '2025-05-24 03:47:44'),
(10, 2, 1200000.00, '2025-05-24 03:56:18'),
(11, 2, 1000000.00, '2025-05-24 03:56:24'),
(12, 2, 1000.00, '2025-05-24 04:15:15'),
(13, 2, 1000000.00, '2025-05-24 04:15:24'),
(14, 2, 1000000.00, '2025-05-24 04:22:14'),
(15, 2, 10000.00, '2025-05-24 04:37:16'),
(16, 2, 5000.00, '2025-05-24 20:11:04'),
(17, 2, 1000000.00, '2025-05-26 02:54:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES
(1, 'mandaireng', '123', 'sepiananda23@gmail.com'),
(2, 'anda', '123', 'sepiananda23@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `salaries`
--
ALTER TABLE `salaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `salaries`
--
ALTER TABLE `salaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `salaries`
--
ALTER TABLE `salaries`
  ADD CONSTRAINT `salaries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
