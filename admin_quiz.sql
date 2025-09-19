-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2025 at 06:48 AM
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
-- Database: `student_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_quiz`
--

CREATE TABLE `admin_quiz` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option1` varchar(255) NOT NULL,
  `option2` varchar(255) NOT NULL,
  `option3` varchar(255) NOT NULL,
  `option4` varchar(255) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_quiz`
--

INSERT INTO `admin_quiz` (`id`, `question`, `option1`, `option2`, `option3`, `option4`, `answer`, `created_at`) VALUES
(2, 'What does CPU stand for?', 'Central Processing Unit', 'Computer Power Unit', 'Central Program Utility', 'Control Processing Unit', 'Central Processing Unit', '2025-03-23 06:32:03'),
(4, 'Who is the father of Internet?', 'Vin Diesel', 'Vint Cerf', 'Waley Bayola', 'Kiko Estrada', 'Vint Cerf', '2025-03-23 06:52:31'),
(6, 'RJ45 is used in?', 'Home', 'Office', 'Networking', 'Building', 'Networking', '2025-03-23 10:54:34'),
(7, 'Who is the Father of Computer?', 'Many Pacquiao', 'Charles Babbage', 'King Charles VIII', 'King Charles IV', 'Charles Babbage', '2025-03-23 10:57:28'),
(8, 'Which device is used to connect a computer to the internet?', 'Printer', 'Router', 'Scanner', 'Monitor', 'Router', '2025-03-23 11:43:21'),
(9, 'Which component is responsible for temporarily storing data while the computer is running?', 'ROM', 'RAM', 'Hard Drive', 'USB Flash Drive', 'RAM', '2025-03-23 11:47:23'),
(10, 'Which the following is an example of an output device?', 'Mouse ', 'Keyboard', 'Monitor', 'Scanner', 'Monitor', '2025-03-23 11:48:38'),
(11, 'What is the main function of RAM?', 'To store permanent data', 'To process graphics', 'To temporarily store data for quick access', 'To cool down the processor', 'To temporarily store data for quick access', '2025-03-23 11:49:40'),
(12, 'Which storage device is non-volatile and stores data permanently?', 'RAM', 'ROM', 'Hard Disk Drive (HDD)', 'Cache Memory', 'Hard Disk Drive (HDD)', '2025-03-23 14:28:11'),
(13, 'What does ROM stand for?', 'Read-only Memory', 'Random Output Memory', 'Run Operating Memory', 'Real Object Memory', 'Read-only Memory', '2025-03-23 14:30:13'),
(14, 'Which of the following is an example of a system software?', 'Microsoft Word', 'Windows 10', 'Google Chrome', 'Adobe Photoshop', 'Windows 10', '2025-03-23 14:34:37'),
(15, 'What type of software allows a user to create and edit documents?', 'System software', 'Utility Software ', 'Application Software ', 'Firmware', 'Application Software ', '2025-03-23 14:36:20'),
(16, 'What is the function of a hard drive in a computer?', 'To process data', 'To store files and programs permanently', 'To provide internet access', 'To improve graphics quality', 'To store files and programs permanently', '2025-03-23 14:44:34'),
(17, 'Which of the following is the main function of an operating system?', 'Compiling programs', 'Managing hardware and software resources', 'Running anti-virus scans ', 'Designing user interfaces', 'Managing hardware and software resources', '2025-03-23 14:49:08'),
(18, 'What does HTTP stand for?', 'Hyper Text Transmission Protocol', 'Hyper Transfer Text Protocol', 'Hyper Text Transfer Protocol', 'High Transmission Text Protocol', 'Hyper Text Transfer Protocol', '2025-03-23 14:50:46'),
(19, 'What is the main purpose of a firewall in a computer?', 'To speed up the internet connection', 'To protect against unauthorized access', 'To store backup files ', 'To enhance display resolution', 'To protect against unauthorized access', '2025-03-23 14:53:53'),
(20, 'Which files is commonly used for an executable file in Windows?', '.exe', '.txt', '.jpg', '.mp3', '.exe', '2025-03-23 14:55:11'),
(21, 'Which of the following is an example of hardware?', 'Windows 11', 'Microsoft Office', 'Keyboard ', 'Google Chrome', 'Keyboard ', '2025-03-23 14:56:33'),
(22, 'Which storage device is commonly used to transfer files between computers?', 'RAM', 'Hard Disk Drive (HDD)', 'USB Flash Drive ', 'Graphics Card', 'USB Flash Drive ', '2025-03-23 14:58:10'),
(23, 'What does WWW stand for in a website URL?', 'Web Wide Window ', 'World Wide Web', 'Wireless Web Widget', 'Wide Web World', 'World Wide Web', '2025-03-23 14:59:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_quiz`
--
ALTER TABLE `admin_quiz`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_quiz`
--
ALTER TABLE `admin_quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
