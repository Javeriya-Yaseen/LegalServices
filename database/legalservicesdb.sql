-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2025 at 02:24 AM
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
-- Database: `legalservicesdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `lawyer_id` int(11) NOT NULL,
  `schedule_date` datetime NOT NULL,
  `status` enum('Pending','Confirmed','Completed') DEFAULT 'Pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `customer_id`, `lawyer_id`, `schedule_date`, `status`, `notes`, `created_at`) VALUES
(1, 5, 14, '2025-05-14 12:48:32', 'Confirmed', 'your appointment is confirmed', '2025-05-14 10:50:21'),
(2, 6, 13, '2025-06-19 23:13:00', 'Pending', 'Very Important case.', '2025-05-19 18:14:21'),
(3, 2, 18, '2025-06-20 00:26:00', 'Confirmed', 'ok ok ok ', '2025-05-19 19:26:46');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `city_id` int(11) NOT NULL,
  `city_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`city_id`, `city_name`) VALUES
(4, 'Faisalabad'),
(3, 'Islamabad'),
(1, 'Karachi'),
(2, 'Lahore'),
(5, 'Rawalpindi');

-- --------------------------------------------------------

--
-- Table structure for table `lawyers_profile`
--

CREATE TABLE `lawyers_profile` (
  `lawyer_id` int(11) NOT NULL,
  `specialization_id` int(11) NOT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `contact_info` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lawyers_profile`
--

INSERT INTO `lawyers_profile` (`lawyer_id`, `specialization_id`, `experience_years`, `bio`, `contact_info`) VALUES
(1, 3, 3, 'best lawyer', '03110101202'),
(7, 4, 4, 'i hirring you ', '123654789658'),
(13, 5, 5, 'i am a lawyer', '123654789658'),
(14, 3, 7, 'i am a lawyer', '789654123695'),
(18, 4, 4, 'new lawyer', '0314-12345678');

-- --------------------------------------------------------

--
-- Table structure for table `specializations`
--

CREATE TABLE `specializations` (
  `specialization_id` int(11) NOT NULL,
  `specialization_name` varchar(100) NOT NULL,
  `service_icon` text DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `specializations`
--

INSERT INTO `specializations` (`specialization_id`, `specialization_name`, `service_icon`, `description`) VALUES
(1, 'Criminal Law', '<i class=\"fa fa-gavel\"></i>', 'Criminal Law deals with offenses against the state, including prosecution and defense of crimes.'),
(2, 'Family Law', '<i class=\"fa fa-users\"></i>', 'Family Law focuses on matters such as marriage, divorce, child custody, and other family-related issues.'),
(3, 'Business Law', '<i class=\"fa fa-hand-holding-usd\"></i>', 'Business Law covers the formation, operation, and disputes of businesses, including contracts, mergers, and compliance.'),
(4, 'Civil Law', '<i class=\"fa fa-landmark\"></i>', 'Civil Law involves legal disputes between individuals or organizations, covering contracts, property, and personal injury.'),
(5, 'Education Law', '<i class=\"fa fa-graduation-cap\"></i>', 'Education Law addresses legal issues related to schools, students, teachers, and educational institutions.'),
(6, 'Cyber Law', '<i class=\"fa fa-globe\"></i>', 'Cyber Law covers issues involving the internet, digital communications, data protection, and cybercrimes.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `user_type` enum('Customer','Lawyer','Admin') NOT NULL,
  `city_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `contact_info` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `profile_photo`, `user_type`, `city_id`, `created_at`, `contact_info`) VALUES
(1, 'madiha', 'madiha@gmail.com', 'admin123', NULL, 'Admin', 2, '2025-05-07 11:09:08', NULL),
(2, 'Javeriya Yaseen', 'javeriya8@gmail.com', 'customer123', NULL, 'Customer', 1, '2025-05-09 11:56:48', NULL),
(3, 'aliya', 'aliya@gmail.com', 'aliya123', NULL, 'Lawyer', 3, '2025-05-13 10:10:51', NULL),
(4, 'ujala', 'ujalatariq18@gmail.com', '12345678', NULL, 'Lawyer', 2, '2025-05-13 11:01:28', NULL),
(5, 'jannat', 'jannat12@gmail.com', 'jannat123', NULL, 'Customer', 4, '2025-05-13 11:10:37', NULL),
(6, 'amna', 'amna123@gmail.com', '12345678', NULL, 'Customer', 1, '2025-05-13 11:34:23', NULL),
(7, 'shahmeer', 'shahmeer@gmail.com', '12345678', NULL, 'Lawyer', 1, '2025-05-13 11:35:59', NULL),
(13, 'Ali Raza', 'abc@xyz.com', '12345678', NULL, 'Lawyer', 1, '2025-05-13 12:27:35', NULL),
(14, 'rose', 'rose12@gmail.com', '12345678', NULL, 'Lawyer', NULL, '2025-05-13 12:39:21', NULL),
(15, 'shahmeer', 'shahmeer12@gmail.com', '12345678', NULL, 'Admin', 1, '2025-05-14 10:30:51', NULL),
(16, 'arsalan', 'arsalan12@gmail.com', '12345678', NULL, 'Customer', 3, '2025-05-14 11:10:10', ''),
(17, 'Syed Murtaza Hussain', 'intersoltech.usa@outlook.com', '12345678', 'user_17_1747679860.png', 'Admin', 1, '2025-05-19 17:44:44', NULL),
(18, 'The Lawyer', 'lawyer@outlook.com', '12345678', 'user_18_1747682304.png', 'Lawyer', 4, '2025-05-19 19:06:39', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `lawyer_id` (`lawyer_id`),
  ADD KEY `schedule_date` (`schedule_date`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`city_id`),
  ADD UNIQUE KEY `city_name` (`city_name`);

--
-- Indexes for table `lawyers_profile`
--
ALTER TABLE `lawyers_profile`
  ADD PRIMARY KEY (`lawyer_id`),
  ADD KEY `specialization_id` (`specialization_id`);

--
-- Indexes for table `specializations`
--
ALTER TABLE `specializations`
  ADD PRIMARY KEY (`specialization_id`),
  ADD UNIQUE KEY `specialization_name` (`specialization_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `email_2` (`email`),
  ADD KEY `user_type` (`user_type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `specializations`
--
ALTER TABLE `specializations`
  MODIFY `specialization_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`lawyer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `lawyers_profile`
--
ALTER TABLE `lawyers_profile`
  ADD CONSTRAINT `lawyers_profile_ibfk_1` FOREIGN KEY (`lawyer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lawyers_profile_ibfk_2` FOREIGN KEY (`specialization_id`) REFERENCES `specializations` (`specialization_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`city_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
