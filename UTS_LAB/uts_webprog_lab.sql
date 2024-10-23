-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2024 at 10:10 AM
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
-- Database: `uts_pti_lab`
--

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `todo_id` int(11) DEFAULT NULL,
  `task` varchar(255) DEFAULT NULL,
  `description` varchar(350) NOT NULL,
  `is_checked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `todo_id`, `task`, `description`, `is_checked`) VALUES
(35, 27, 'kerjain webprog', 'hhhhh', 0),
(67, 26, 'adfas', 'ngajar bimbel', 0),
(68, 26, 'asdfasd', 'ngajar bimbel', 0),
(69, 26, 'rawr', 'ngajar bimbel', 0),
(70, 26, 'kelaz', 'ngajar bimbel', 0),
(92, 28, 'ngajar', 'kerja bosku', 1),
(93, 28, 'makan', 'kerja bosku', 1),
(94, 28, 'tidur', 'kerja bosku', 0),
(95, 28, 'ok', 'kerja bosku', 1),
(96, 28, 'slebew', 'kerja bosku', 0);

-- --------------------------------------------------------

--
-- Table structure for table `todo_lists`
--

CREATE TABLE `todo_lists` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `todo_lists`
--

INSERT INTO `todo_lists` (`id`, `user_id`, `title`, `created_at`, `category`) VALUES
(26, 8, 'ngajar', '2024-10-09 07:58:12', 'Work'),
(27, 8, 'hi', '2024-10-09 07:58:58', 'Work'),
(28, 8, 'Kerja', '2024-10-12 06:10:59', 'Personal');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `filepath` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `date`, `password`, `created_at`, `filepath`) VALUES
(8, 'Frendhy', 'frendhyzhuang@gmail.com', '2005-01-26', '$2y$10$XfNDfvE.ush3J/0Q3Rrt3eCgoyhH2h03oOZX.FmuOEF/HZfn94QEK', '2024-10-09 06:51:47', 'uploads/SNAPSHOT.png'),
(10, 'Frendhyy', 'frendhyzhuangg@gmail.com', '2005-01-26', '$2y$10$DH.YTePtbFUFny1V/Bo/eO2I85ozHMjDwlgtYHHYfnlpp2jvm/xci', '2024-10-09 06:52:31', 'uploads/SNAPSHOT.png'),
(11, 'Budi', 'budi123@gmail.com', '2023-11-11', '$2y$10$7GDdhXKXggCj4rrdih7iS.CAtZWlBgow2p9al8UihROdDjkTj.VUe', '2024-10-12 17:19:38', 'uploads/SNAPSHOT.png'),
(16, 'Siti', 'siti@gmail.com', '2005-01-25', '$2y$10$wfbXyjeXYCr60kuPZ/KTsOSQHenlazd88Pb1WrhjHaOueXUPO73Yu', '2024-10-12 18:29:57', 'uploads/Dokumentasi.png'),
(17, 'Andi', 'andi@gmail.com', '2005-02-02', '$2y$10$nq/qndY.aAMfrKXmg5nmQeOjXG/b3qrdv8ArtaOMrrujGXbs6qBp6', '2024-10-12 18:31:01', 'uploads/Frame 7 (1).png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `todo_id` (`todo_id`);

--
-- Indexes for table `todo_lists`
--
ALTER TABLE `todo_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `todo_lists`
--
ALTER TABLE `todo_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`todo_id`) REFERENCES `todo_lists` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `todo_lists`
--
ALTER TABLE `todo_lists`
  ADD CONSTRAINT `todo_lists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
