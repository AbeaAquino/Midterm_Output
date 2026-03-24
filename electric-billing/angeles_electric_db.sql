SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `admin_id` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `admins` (`id`, `admin_id`, `password`) VALUES
(1, '1001', '$2y$10$DIdythi.4I6HZlYLANOja.yr8RqBHFR6S5aGjY6l.4g1Od/hxOtyG');

CREATE TABLE `advisories` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `advisories` (`id`, `title`, `category_id`, `description`, `created_at`) VALUES
(33, 'Billing Reminder and Payment Notice', 1, 'Date: March 31, 2026\nTime: 11:00 AM - 1:00 AM\nAffected Area/s: \n\nThis is to inform all concerned users that billing statements for the current period have been issued. Please ensure that payments are settled on or before the due date to avoid service interruptions or additional charges.\n\nAdditional Notes: Kindly verify your billing details and contact support immediately for any discrepancies. Multiple payment methods are available for your convenience.', '2026-03-24 08:16:36'),
(34, 'Power Interruption Advisory', 3, 'Date: March 25, 2026\nTime: 2:30 PM - 1:00 AM\nAffected Area/s: Sto. Rosario, Angeles City\n\nThis is to notify all affected users in Sto. Rosario, Angeles City of a scheduled power interruption. This may result in temporary service disruptions.\n\nAdditional Notes: Please take necessary precautions and plan accordingly. Services will be restored once power supply is normalized. We appreciate your understanding.', '2026-03-24 08:19:06');

CREATE TABLE `advisory_categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `advisory_categories` (`id`, `category_name`) VALUES
(1, 'Billing Advisories'),
(2, 'Maintenance Schedule'),
(3, 'Power Interruption Notice');

CREATE TABLE `bills` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `billing_month` varchar(20) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('Paid','Unpaid') DEFAULT 'Unpaid',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `bills` (`id`, `user_id`, `billing_month`, `amount`, `due_date`, `status`, `created_at`) VALUES
(14, 5, 'March 2026', 4000.00, '2026-03-31', 'Paid', '2026-03-24 07:55:58'),
(15, 5, 'April 2026', 5000.00, '2026-04-30', 'Unpaid', '2026-03-24 07:56:18');

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `bill_id` int(11) DEFAULT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `reference_number` varchar(50) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `payments` (`id`, `user_id`, `bill_id`, `payment_method_id`, `amount`, `reference_number`, `payment_date`) VALUES
(5, 5, 14, 3, 4000.00, 'REF73059', '2026-03-24 07:58:06');

CREATE TABLE `payment_methods` (
  `id` int(11) NOT NULL,
  `method_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `payment_methods` (`id`, `method_name`) VALUES
(3, 'Bank Transfer'),
(2, 'Cash'),
(1, 'GCash');

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

INSERT INTO `users` (`id`, `account_number`, `name`, `email`, `password`, `dob`, `address`, `contact_no`, `created_at`) VALUES
(3, '466712199', 'Ryence Cortez', 'abea@gmail.com', '$2y$10$KDVkn4WYzU.rp6328ITLc.21jHyBXxIKa/97ZsUSTxlKo9gz7eFWG', '2025-02-04', '04 st.matthew purok 7', '12345678910', '2026-03-24 06:14:52'),
(5, '538882179', 'Abea Aquino', 'abeaaquino29@gmail.com', '$2y$10$k.rrbAhyTqDvaAEn6OFPA.vmjHFZdOY4KFbH4v7o4gkcERgz4/Bwe', '2006-07-29', 'Angeles City', '09262287984', '2026-03-24 06:46:15'),
(6, '923435908', 'Test User', 'test@email.com', '$2y$10$UvwugZtwb.dQZ2MmioXs1ubRIYgiyJoGniqplX6kHiCHcA1oj5GkS', '2024-07-29', 'Sto. Rosario, Angeles City', '09212387984', '2026-03-24 08:21:16');

ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_id` (`admin_id`);

ALTER TABLE `advisories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

ALTER TABLE `advisory_categories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_month` (`user_id`,`billing_month`);

ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `bill_id` (`bill_id`),
  ADD KEY `payment_method_id` (`payment_method_id`);

ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `method_name` (`method_name`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `account_number` (`account_number`);

ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `advisories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

ALTER TABLE `advisory_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `advisories`
  ADD CONSTRAINT `advisories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `advisory_categories` (`id`);

ALTER TABLE `bills`
  ADD CONSTRAINT `bills_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`id`),
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`);

COMMIT;
