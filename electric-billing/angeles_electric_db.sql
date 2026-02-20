-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2026 at 01:48 PM
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
-- Database: `angeles_electric_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `advisories`
--

CREATE TABLE `advisories` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `advisories`
--

INSERT INTO `advisories` (`id`, `title`, `category`, `description`, `created_at`) VALUES
(1, 'Billing Advisory', 'Billing Advisories', 'Updates and notices related to your electricity bill.', '2026-02-18 16:39:52'),
(2, 'Maintenance Schedule', 'Maintenance Schedule', 'Upcoming scheduled maintenance activities.', '2026-02-18 16:39:52'),
(3, 'Power Interruption Notice', 'Power Interruption Notice', 'Announcements about scheduled power outages.', '2026-02-18 16:39:52');

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `month` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('Paid','Unpaid') DEFAULT 'Unpaid',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`id`, `user_id`, `month`, `amount`, `due_date`, `status`, `created_at`) VALUES
(1, 1, 'February 2026', 3400.00, '2026-02-14', 'Unpaid', '2026-02-18 16:39:52'),
(2, 1, 'January 2026', 3000.00, '2026-01-14', 'Paid', '2026-02-18 16:39:52'),
(3, 1, 'December 2025', 3700.00, '2025-12-14', 'Paid', '2026-02-18 16:39:52'),
(4, 1, 'November 2025', 2900.00, '2025-11-14', 'Paid', '2026-02-18 16:39:52'),
(5, 1, 'October 2025', 3300.00, '2025-10-14', 'Paid', '2026-02-18 16:39:52'),
(6, 1, 'March 2026', 4500.00, '2026-03-15', 'Unpaid', '2026-02-18 17:02:14'),
(7, 3, 'April 2026', 4200.00, '2026-04-15', 'Paid', '2026-02-19 13:32:03'),
(8, 3, 'feb 2026', 1000.00, '2026-04-15', 'Paid', '2026-02-19 13:39:11'),
(9, 3, 'june 2026', 4200.00, '2026-04-15', 'Paid', '2026-02-19 13:39:11'),
(10, 3, 'july 2026', 2000.00, '2026-04-15', 'Unpaid', '2026-02-19 13:39:11');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `bill_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `reference_number` varchar(50) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `bill_id`, `amount`, `payment_method`, `reference_number`, `payment_date`) VALUES
(1, 3, 7, 4200.00, 'GCash', 'REF36390', '2026-02-19 13:35:28'),
(2, 3, 9, 4200.00, 'E-Wallet', 'REF73664', '2026-02-19 15:01:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `account_number` varchar(20) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dob` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `account_number`, `name`, `email`, `password`, `dob`, `address`, `contact_no`, `created_at`) VALUES
(1, '123456789', 'Jeon Jungkook', 'jeon.jungkook@gmail.com', '$2y$10$Wl1sPqC9vT9uYjR2jzJ6lu9Pz2nYFZk1WcPqXr0V6mQYJ5X6QzE6G', '1997-09-01', '1234 Celina St., Angeles City, Pampanga', '09678431245', '2026-02-18 16:39:52'),
(3, '454125072', 'Abea Venice P. Aquino', 'abeaaquino29@gmail.com', '$2y$10$A/8eOa9aEkL.RVgvcvySxuczPw8Dp2qQllPB1VfEGZr52LPk2jPhu', '2006-07-29', NULL, NULL, '2026-02-18 16:41:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advisories`
--
ALTER TABLE `advisories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `bill_id` (`bill_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `account_number` (`account_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `advisories`
--
ALTER TABLE `advisories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
