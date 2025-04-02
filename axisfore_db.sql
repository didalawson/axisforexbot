-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2025 at 11:08 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `axisfore_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`, `created_at`, `updated_at`) VALUES
(1, 'administrator', '$2y$10$pBGclHbwUP39L1Ud8ISkXuMY02Vn1KN/GzOOt7pik3OEZMpIS0UsO', NULL, '2025-03-24 16:17:03', '2025-03-25 08:56:46'),
(2, 'admin', '$2y$10$7gmQq0/zBib/ZgDTyIhyYuSQbmhFYnWmpJiuDnXXJylTUDAQ/3bsa', 'admin@axisbot.com', '2025-03-25 08:19:03', '2025-03-25 08:19:03');

-- --------------------------------------------------------

--
-- Table structure for table `company_settings`
--

CREATE TABLE `company_settings` (
  `id` int(11) NOT NULL,
  `setting_name` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company_settings`
--

INSERT INTO `company_settings` (`id`, `setting_name`, `setting_value`, `updated_at`) VALUES
(1, 'company_name', 'Axisforexbot', '2025-03-29 04:48:10'),
(2, 'company_address', '123 Trading Street, Crypto City, 10001', '2025-03-24 16:22:00'),
(3, 'company_phone', '', '2025-03-28 08:13:48'),
(4, 'company_email', 'support@axisforexbot.com', '2025-03-29 04:48:10'),
(5, 'support_email', 'support@axisforexbot.com', '2025-03-29 04:48:10'),
(6, 'company_website', 'https://axisforexbot.com', '2025-03-29 04:48:10');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','replied') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crypto_addresses`
--

CREATE TABLE `crypto_addresses` (
  `id` int(11) NOT NULL,
  `crypto_type` enum('BTC','ETH','TRC') NOT NULL,
  `address` varchar(255) NOT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crypto_addresses`
--

INSERT INTO `crypto_addresses` (`id`, `crypto_type`, `address`, `active`, `created_at`, `updated_at`) VALUES
(1, 'BTC', '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa', 1, '2025-03-24 10:32:52', '2025-03-24 10:32:52'),
(2, 'ETH', '0x742d35Cc6634C0532925a3b844Bc454e4438f44e', 1, '2025-03-24 10:32:52', '2025-03-24 10:32:52'),
(3, 'TRC', 'TXhZ41Z48qV9tN9tqXqXqXqXqXqXqXqXqX', 1, '2025-03-24 10:32:52', '2025-03-24 10:32:52');

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` enum('pending','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `investments`
--

CREATE TABLE `investments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `percentage` int(11) NOT NULL,
  `plan` varchar(20) NOT NULL,
  `receipt_path` varchar(256) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `rejection_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `investments`
--

INSERT INTO `investments` (`id`, `user_id`, `amount`, `percentage`, `plan`, `receipt_path`, `status`, `created_at`, `updated_at`, `rejection_reason`) VALUES
(1, 6, 44445.00, 12, 'vip', NULL, 'pending', '2025-03-24 10:36:57', '2025-04-01 09:53:37', NULL),
(2, 6, 44445.00, 12, 'vip', NULL, 'pending', '2025-03-24 10:45:51', '2025-04-01 09:53:37', NULL),
(3, 6, 566.00, 5, 'basic', NULL, 'pending', '2025-03-24 11:22:16', '2025-04-01 09:53:37', NULL),
(4, 6, 600.00, 5, 'basic', NULL, 'rejected', '2025-03-24 11:32:50', '2025-04-01 11:13:58', 'no receipt'),
(5, 6, 100.00, 5, 'basic', NULL, 'active', '2025-03-24 11:45:32', '2025-04-01 11:13:42', NULL),
(6, 1, 6000.00, 12, 'vip', NULL, 'active', '2025-03-25 09:54:05', '2025-04-01 09:53:37', NULL),
(7, 7, 100.00, 5, 'basic', '1742971886_7_80% STAFF SALES [PNG].png', 'pending', '2025-03-26 07:49:14', '2025-04-01 09:53:37', NULL),
(8, 1, 500.00, 5, 'basic', NULL, 'inactive', '2025-03-28 08:51:31', '2025-04-01 12:32:22', NULL),
(9, 8, 1000.00, 5, 'basic', NULL, 'pending', '2025-03-28 09:48:12', '2025-04-01 09:53:37', NULL),
(10, 13, 500.00, 5, 'basic', '1743208615_13_WhatsApp Image 2023-11-14 at 11.10.40_0ad1f01e.jpg', 'active', '2025-03-29 01:36:34', '2025-04-01 12:28:32', NULL),
(11, 14, 600.00, 5, 'basic', NULL, 'inactive', '2025-03-29 01:54:37', '2025-04-01 12:28:12', NULL),
(12, 14, 1003.00, 8, 'premium', NULL, 'active', '2025-03-29 02:14:51', '2025-04-01 10:41:21', NULL),
(13, 1, 1000.00, 0, 'basic', '1743492273_1_logoo.jpg', 'active', '2025-04-01 08:24:01', '2025-04-01 10:41:04', NULL),
(14, 1, 5000.00, 0, 'premium', '1743493465_1_artwork (2).png', 'active', '2025-04-01 08:43:59', '2025-04-01 09:53:37', NULL),
(15, 1, 6000.00, 0, 'vip', NULL, 'active', '2025-04-01 09:06:33', '2025-04-01 10:51:42', NULL),
(16, 6, 60000.00, 0, 'vip', '1743502850_6_download (12).jpg', 'active', '2025-04-01 11:19:31', '2025-04-01 10:51:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kyc_submissions`
--

CREATE TABLE `kyc_submissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `address` text NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `document_type` enum('passport','drivers_license','national_id') NOT NULL,
  `document_number` varchar(50) NOT NULL,
  `document_front` varchar(255) NOT NULL,
  `document_back` varchar(255) NOT NULL,
  `selfie` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kyc_submissions`
--

INSERT INTO `kyc_submissions` (`id`, `user_id`, `full_name`, `date_of_birth`, `address`, `phone_number`, `document_type`, `document_number`, `document_front`, `document_back`, `selfie`, `status`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(1, 1, 'dudu mango', '1988-02-11', 'kanya baba malaysia', '81253359233', 'passport', '3467899073', '67eb8bd05a4fb_document_front.jpg', '67eb8bd05b58d_document_back.jpg', '67eb8bd05c305_selfie.jfif', 'approved', NULL, '2025-04-01 06:46:40', '2025-04-01 06:58:30'),
(2, 6, 'p', '1991-12-03', 'galaxy town usa', '6899000', 'national_id', '009u8ytrre', '67eb914130cff_document_front.png', '67eb9141381cf_document_back.png', '67eb9141395d0_selfie.pdf', 'rejected', 'picture is blurry', '2025-04-01 07:09:53', '2025-04-01 07:10:25');

-- --------------------------------------------------------

--
-- Table structure for table `login_history`
--

CREATE TABLE `login_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_history`
--

INSERT INTO `login_history` (`id`, `user_id`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-25 09:33:40'),
(2, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-25 22:50:13'),
(3, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-26 06:54:46'),
(4, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-26 07:16:14'),
(5, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-26 12:20:54'),
(6, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-26 12:21:41'),
(7, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-26 18:12:43'),
(8, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-27 13:55:26'),
(9, 2, '105.117.128.177', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-28 07:53:24'),
(10, 1, '105.117.128.177', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-28 07:56:25'),
(11, 2, '105.117.128.177', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Mobile Safari/537.36', '2025-03-28 08:08:53'),
(12, 1, '197.210.84.160', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.2 Mobile/15E148 Safari/604.1', '2025-03-28 08:12:56'),
(13, 2, '105.117.128.177', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Mobile Safari/537.36', '2025-03-28 08:13:12'),
(14, 2, '102.88.111.243', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-29 00:37:52'),
(15, 2, '105.112.121.213', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-29 00:55:18'),
(16, 2, '102.88.111.243', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-29 02:53:40'),
(17, 2, '105.112.121.213', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Mobile Safari/537.36', '2025-03-29 04:43:44'),
(18, 2, '105.112.121.213', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Mobile Safari/537.36', '2025-03-29 04:43:44'),
(19, 2, '105.112.121.213', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-29 04:50:34'),
(20, 2, '105.117.1.142', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Mobile Safari/537.36', '2025-03-29 16:25:31'),
(21, 1, '105.112.117.215', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-30 15:35:25'),
(22, 1, '102.90.97.110', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.2 Mobile/15E148 Safari/604.1', '2025-03-30 15:36:37'),
(23, 2, '105.112.117.215', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Mobile Safari/537.36', '2025-03-30 20:51:47'),
(24, 1, '105.112.117.215', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-30 20:55:14'),
(25, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 19:31:10'),
(26, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 20:23:17'),
(27, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 21:03:51'),
(28, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 21:08:44'),
(29, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 21:21:10'),
(30, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 22:17:12'),
(31, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 22:42:57'),
(32, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 22:50:40'),
(33, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 22:51:35'),
(34, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 22:55:21'),
(35, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-01 06:12:48'),
(36, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-01 06:13:18'),
(37, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-01 07:11:07'),
(38, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-01 10:16:58'),
(39, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-01 12:26:43');

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `name` varchar(10) NOT NULL COMMENT 'Plan_Name',
  `percent` int(11) NOT NULL COMMENT 'Plan_Percentage',
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Plan_MetaData' CHECK (json_valid(`data`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='plans';

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `name`, `percent`, `data`) VALUES
(1, 'basic', 5, '[\"5% Daily Returns\", \"24/7 Support\", \"Instant Withdrawal\"]'),
(2, 'premium', 8, '[\"8% Daily Returns\", \"Priority Support\", \"Instant Withdrawal\"]'),
(3, 'vip', 12, '[\"12% Daily Returns\", \"VIP Support\", \"Instant Withdrawal\"]');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `amount` decimal(20,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `type`, `amount`, `description`, `created_at`) VALUES
(1, 6, 'investment', 60000.00, 'Investment approved - Vip Plan', '2025-04-01 10:33:50'),
(2, 6, 'investment', 60000.00, 'Investment approved - Vip Plan', '2025-04-01 10:33:56'),
(3, 1, 'investment', 1000.00, 'Investment approved - Basic Plan', '2025-04-01 10:41:04'),
(4, 14, 'investment', 1003.00, 'Investment approved - Premium Plan', '2025-04-01 10:41:23'),
(5, 1, 'investment', 6000.00, 'Investment approved - Vip Plan', '2025-04-01 10:51:14'),
(6, 6, 'investment', 60000.00, 'Investment approved - Vip Plan', '2025-04-01 10:51:19'),
(7, 1, 'investment', 6000.00, 'Investment approved - Vip Plan', '2025-04-01 10:51:42'),
(8, 14, 'investment', 600.00, 'Investment approved - Basic Plan', '2025-04-01 10:51:50'),
(9, 14, 'investment', 600.00, 'Investment approved - Basic Plan', '2025-04-01 10:54:03'),
(10, 6, 'investment', 100.00, 'Investment approved - Basic Plan', '2025-04-01 11:13:42'),
(11, 13, 'investment', 500.00, 'Investment approved - Basic Plan', '2025-04-01 12:28:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(256) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `phone` varchar(15) DEFAULT NULL,
  `country` varchar(256) DEFAULT NULL,
  `state` varchar(256) DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT 1,
  `verification_token` varchar(100) DEFAULT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `referral_id` varchar(100) NOT NULL,
  `my_referral_id` varchar(100) DEFAULT NULL,
  `balance` decimal(20,2) DEFAULT 0.00,
  `active_deposit` decimal(20,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `username`, `password`, `role`, `phone`, `country`, `state`, `email_verified`, `verification_token`, `reset_token`, `reset_token_expires`, `created_at`, `updated_at`, `referral_id`, `my_referral_id`, `balance`, `active_deposit`) VALUES
(1, 'dudu', 'luli', 'dudu@gmail.com', NULL, '$2y$10$2iQXtrrUxYuZHtxZeYZE8u8OylwT60u153v1wW3lD877lNTyQ6HOW', 'user', NULL, NULL, NULL, 1, NULL, NULL, NULL, '2025-03-24 02:27:09', '2025-04-01 10:51:42', '', 'REF3F8958BC', 13000.00, 13000.00),
(2, 'iliyas', 'musiliyu', 'iliyas@gmail.com', NULL, '$2y$10$xzdSoO.c8FiIaqHsmEUu2eIdjx7cm68O0Ayy663YSufor/BgJNnuS', 'user', NULL, NULL, NULL, 1, NULL, NULL, NULL, '2025-03-24 02:37:21', '2025-03-24 02:37:21', '', NULL, 0.00, 0.00),
(4, 'loga', 'luli', 'fada@gmail.com', NULL, '$2y$10$mEv7duUjT15vlSNw4T15gudxmRCp0TdcdDRDs2FI3gVhb7T1kftGe', 'user', NULL, NULL, NULL, 0, '631688213675a291d1efff219b52acc6', NULL, NULL, '2025-03-24 05:23:57', '2025-03-24 05:23:57', '', NULL, 0.00, 0.00),
(5, 'loga', 'musiliyu', 'logamusiliyu@gmail.com', NULL, '$2y$10$wJ8JNslpE.CVZvBftYi7ue1Jrb7UCRtNcec7ZkX/eOeMfsnGqsI1.', 'user', NULL, NULL, NULL, 0, '7e65bde39b27ab73f1790fef9c8f88f2', NULL, NULL, '2025-03-24 05:30:43', '2025-03-24 05:30:43', '', NULL, 0.00, 0.00),
(6, 'peter', 'rock', 'pete@rock.com', NULL, '$2y$10$j.Cwcowt/i22HSbNwPJCluQDI//rx7GnhKBTA7L6qopWX9uTFDvEG', 'user', NULL, NULL, NULL, 0, '7a4ea98bc1a9fbc23316c6b9ac142fb6', NULL, NULL, '2025-03-24 07:01:56', '2025-04-01 11:13:42', '', 'petroc637', 180100.00, 180100.00),
(7, 'Kimberley', 'Sexton', 'qyriru@mailinator.com', NULL, '$2y$10$tWIB7Czez1yaUzbYiaxGwe.VuGoc1IZZhE/Ubt3v6fZRzGTvUFwKK', 'user', NULL, NULL, NULL, 0, '2516b060d02176ba463ddbf10d34fb9b', NULL, NULL, '2025-03-25 22:35:50', '2025-03-25 22:35:50', 'Quisquam veritatis d', 'kimsex100', 0.00, 0.00),
(8, 'Rich', 'Rich', 'nnadiok92@gmail.com', NULL, '$2y$10$OzG3r2aj.ukHufST1xUj5edZyC8zyqJ/fTFMM26bKtLkhzO4g3YIe', 'user', NULL, NULL, NULL, 0, '2ad25fcf1e1a165f5f6c3b303b9201f1', NULL, NULL, '2025-03-28 08:07:56', '2025-03-28 08:07:56', '', 'ricric339', 0.00, 0.00),
(13, 'Francis', 'Okpani', 'francisokpani570@gmail.com', NULL, '$2y$10$hFn02URzx7r4ewo2rlIvOu2dEnSgQsoy5bT9tr9iPfrDA6Ma6gDI.', 'user', NULL, NULL, NULL, 0, 'b3f14dea7f82737e7a79ea68755f4ee3', NULL, NULL, '2025-03-29 00:13:06', '2025-04-01 12:28:32', '', 'fraokp868', 500.00, 500.00),
(14, 'John', 'John', 'daudalawson@gmail.com', NULL, '$2y$10$JF1Dc0ar/jr.1zpyq87A/u/5mWG3AAQ5Ny7OACWBsgIzfa4gS1AjW', 'user', NULL, NULL, NULL, 0, 'f730d53e202b6faae3ea8a623c284188', NULL, NULL, '2025-03-29 00:49:01', '2025-04-01 10:54:03', '', 'johjoh671', 2203.00, 2203.00),
(15, 'Najlaa', 'Rose', 'agbosomadina1@gmail.com', NULL, '$2y$10$TdV41Jafwc62gNoTvt9fhuuAYc.FpRmCGDK4zl1MZNJY3iJtUIMVu', 'user', NULL, NULL, NULL, 0, '7742cd93140fe25a70a394281da7262c', NULL, NULL, '2025-03-29 08:08:16', '2025-03-29 08:08:16', '', 'najros327', 0.00, 0.00),
(16, 'LAURENT ', 'BILLIO', 'BENJAMINPHILIP7878@GMAIL.COM', NULL, '$2y$10$la1/CfwqZTdHEgrGFpK3MOVgh0wOmZ72nGnqm6ERBlNKi8.aTh6s6', 'user', NULL, NULL, NULL, 0, '2916772508a4da6ffd6bde9aece4a4a4', NULL, NULL, '2025-03-29 08:19:51', '2025-03-29 08:19:51', 'BILLIO', 'laubil912', 0.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `user_balance`
--

CREATE TABLE `user_balance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `bonus` decimal(10,0) DEFAULT NULL,
  `active_deposit` decimal(10,0) NOT NULL,
  `profit` decimal(10,0) NOT NULL,
  `referral_bonus` decimal(10,0) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_balance`
--

INSERT INTO `user_balance` (`id`, `user_id`, `balance`, `bonus`, `active_deposit`, `profit`, `referral_bonus`, `last_updated`) VALUES
(1, 6, 1000.00, NULL, 0, 0, 0, '2025-03-24 11:11:41'),
(2, 7, 0.00, NULL, 0, 0, 0, '2025-03-25 22:42:29'),
(3, 1, 5000.00, 0, 0, 5660, 0, '2025-03-31 21:38:01'),
(4, 8, 0.00, NULL, 0, 0, 0, '2025-03-28 08:08:30'),
(5, 13, 0.00, NULL, 0, 0, 0, '2025-03-29 00:15:52'),
(6, 14, 20.00, NULL, 0, 0, 0, '2025-03-29 00:56:08'),
(7, 15, 0.00, NULL, 0, 0, 0, '2025-03-29 08:08:27'),
(8, 16, 0.00, NULL, 0, 0, 0, '2025-03-29 08:19:57'),
(9, 2, 0.00, 0, 0, 0, 0, '2025-03-31 21:38:05');

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE `user_settings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_settings`
--

INSERT INTO `user_settings` (`id`, `user_id`, `first_name`, `last_name`, `phone`, `country`, `state`, `city`, `address`, `avatar_path`, `created_at`, `updated_at`) VALUES
(1, 6, 'peter', 'rock', '234905088942', 'nigeria', 'rivers', 'ph', '2 ph road', NULL, '2025-03-24 10:20:27', '2025-03-24 10:38:47');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_settings`
--

CREATE TABLE `wallet_settings` (
  `id` int(11) NOT NULL,
  `crypto_name` varchar(50) NOT NULL,
  `crypto_symbol` varchar(10) NOT NULL,
  `wallet_address` varchar(255) NOT NULL,
  `network_type` varchar(50) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wallet_settings`
--

INSERT INTO `wallet_settings` (`id`, `crypto_name`, `crypto_symbol`, `wallet_address`, `network_type`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 'USDT', 'USDT', '0x742d35Cc6634C0532925a3b844Bc454e4438f44e', 'TRC20', 1, '2025-03-24 16:21:30', '2025-03-24 16:21:30'),
(5, 'Bitcoin', 'BTC', 'Ffdyuolkbcxswsaasf', 'Btc', 1, '2025-03-29 01:13:31', '2025-03-29 01:13:31');

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `wallet_address` varchar(255) NOT NULL,
  `currency` varchar(50) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `company_settings`
--
ALTER TABLE `company_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_name` (`setting_name`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crypto_addresses`
--
ALTER TABLE `crypto_addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `investments`
--
ALTER TABLE `investments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `kyc_submissions`
--
ALTER TABLE `kyc_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `login_history`
--
ALTER TABLE `login_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_transactions_user_id` (`user_id`),
  ADD KEY `idx_transactions_type` (`type`),
  ADD KEY `idx_transactions_created_at` (`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_balance`
--
ALTER TABLE `user_balance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wallet_settings`
--
ALTER TABLE `wallet_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `company_settings`
--
ALTER TABLE `company_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crypto_addresses`
--
ALTER TABLE `crypto_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `investments`
--
ALTER TABLE `investments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `kyc_submissions`
--
ALTER TABLE `kyc_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `login_history`
--
ALTER TABLE `login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user_balance`
--
ALTER TABLE `user_balance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_settings`
--
ALTER TABLE `user_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wallet_settings`
--
ALTER TABLE `wallet_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `deposits`
--
ALTER TABLE `deposits`
  ADD CONSTRAINT `deposits_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `investments`
--
ALTER TABLE `investments`
  ADD CONSTRAINT `investments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `kyc_submissions`
--
ALTER TABLE `kyc_submissions`
  ADD CONSTRAINT `kyc_submissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `login_history`
--
ALTER TABLE `login_history`
  ADD CONSTRAINT `login_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
