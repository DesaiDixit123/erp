-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 23, 2025 at 04:00 AM
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
-- Database: `industry_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_auth`
--

CREATE TABLE `admin_auth` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_auth`
--

INSERT INTO `admin_auth` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$12$A6eTnDQt/RaRj3nPJArMXucdwygzCcPnsD6zIepTw6D6p5iSXAoxi', '2025-06-10 23:04:13', '2025-06-23 10:11:05');

-- --------------------------------------------------------

--
-- Table structure for table `available_raw_materials`
--

CREATE TABLE `available_raw_materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `raw_material_type_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `available_raw_materials`
--

INSERT INTO `available_raw_materials` (`id`, `raw_material_type_id`, `quantity`, `created_at`, `updated_at`) VALUES
(3, 1, 20400, '2025-06-20 01:51:14', '2025-06-23 05:16:12');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `casting_records`
--

CREATE TABLE `casting_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `machine_no` bigint(20) UNSIGNED NOT NULL,
  `tool_type_id` bigint(20) UNSIGNED NOT NULL,
  `quantity_pcs` int(11) NOT NULL,
  `treem` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `inspection` int(11) DEFAULT NULL,
  `dispatch` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `shift` varchar(255) DEFAULT NULL,
  `machine_operator_name` varchar(255) DEFAULT NULL,
  `quantity_kg` decimal(8,2) DEFAULT NULL,
  `machine_operator_name1` varchar(255) DEFAULT NULL,
  `machine_operator_name2` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `casting_records`
--

INSERT INTO `casting_records` (`id`, `machine_no`, `tool_type_id`, `quantity_pcs`, `treem`, `created_at`, `updated_at`, `inspection`, `dispatch`, `date`, `shift`, `machine_operator_name`, `quantity_kg`, `machine_operator_name1`, `machine_operator_name2`) VALUES
(15, 2, 3, 1100, NULL, '2025-07-28 11:39:49', '2025-07-28 11:40:39', NULL, NULL, '2025-12-05', 'Day', 'hvhvv', 700.00, NULL, NULL),
(16, 1, 7, 3300, NULL, '2025-07-29 03:48:35', '2025-07-29 03:50:12', NULL, NULL, '2025-12-12', 'Day', 'esdrtfhjn', 500.00, NULL, NULL),
(17, 2, 4, 300, NULL, '2025-09-21 08:59:54', '2025-09-21 09:05:32', NULL, NULL, '2025-09-17', 'Day', NULL, 300.00, 'hjhhbj', 'jhhjjh');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `image`, `created_at`, `updated_at`) VALUES
(6, 'sbhcjbh', 'companies/1752408343_8.jpg', '2025-06-23 05:10:19', '2025-07-13 06:35:43'),
(8, 'saghdcbh', NULL, '2025-07-29 04:12:58', '2025-07-29 04:12:58');

-- --------------------------------------------------------

--
-- Table structure for table `consumable_raw_materials`
--

CREATE TABLE `consumable_raw_materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `raw_material_type_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `consumable_raw_materials`
--

INSERT INTO `consumable_raw_materials` (`id`, `raw_material_type_id`, `quantity`, `created_at`, `updated_at`) VALUES
(5, 1, 116150, '2025-06-20 02:13:42', '2025-06-21 00:19:07');

-- --------------------------------------------------------

--
-- Table structure for table `dispatches`
--

CREATE TABLE `dispatches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `tool_type_id` bigint(20) UNSIGNED NOT NULL,
  `quantity_dispatch` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dispatches`
--

INSERT INTO `dispatches` (`id`, `date`, `tool_type_id`, `quantity_dispatch`, `created_at`, `updated_at`) VALUES
(3, '2025-09-25', 7, 500, '2025-09-22 12:10:58', '2025-09-22 19:58:19');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('Admin','Store','Production','Inspection') NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `adhar_image` varchar(255) DEFAULT NULL,
  `pan_image` varchar(255) DEFAULT NULL,
  `user_status` enum('Active','Inactive') NOT NULL DEFAULT 'Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `email`, `phone`, `address`, `password`, `user_type`, `avatar`, `adhar_image`, `pan_image`, `user_status`, `created_at`, `updated_at`) VALUES
(4, 'Meet Patel', 'meetpatel80@gmail.com', '7698129703', 'Nikol', '$2y$12$Xof0eRyJSXZosit/vlJVgOZkAqIJVJ4NYTjwIjuKQyl1Ad22ifcSe', 'Admin', NULL, 'adhar_images/jrpltPicGxDU35T2NApj4NXan0CLvdNorYk6OEO4.jpg', 'pan_images/EnmQmYPj0MX3r9DIING0BCc3sBEq2TMg8wkJw2Yu.jpg', 'Inactive', '2025-06-11 03:56:32', '2025-07-13 06:19:25'),
(6, 'Meet Patel', 'dixitdesai706@gmail.com', '9737080195', 'Nikol Naroda road', '$2y$12$DBKfAxVimJZswh6RQbIC3eVRalmRfMvnxl9jVBl/JmT/AiG5HYkka', 'Admin', 'avatars/xOU6DE9MNGNq8zoVPnjDt78PMHb31EnT3oKAbaw3.jpg', 'adhar_images/wFjTPZ84rJQK1IQnxesTYO6ZWTVUSXh79d98zUt2.jpg', 'pan_images/yfezeaDzJO9oAEiLiN5BicCPTw8H4QGxrmeF36G7.jpg', 'Inactive', '2025-06-23 05:27:04', '2025-07-13 06:19:29'),
(8, 'BHDbhjbhdcnb', 'mbdcbbhhmb@gmail.com', '7485964152', 'Nikol', '$2y$12$Dv3uLpmxZ1mQ0Uv3pIu8Q.kMkMvuzo7gWChRI8sxmSAZL9bIHRUAW', 'Store', NULL, 'adhar_images/1752316396_7.jpg', 'pan_images/1752316396_4.jpg', 'Active', '2025-07-12 05:03:16', '2025-07-13 06:19:38'),
(10, 'Dixit Desai', 'dixitdesai123@gmail.com', '9714153286', 'Nikol', '$2y$12$GC1ddRYAAnid61.4/ryqkeW5PqB7A/HLSHL10qZ4UK3lmte0/IdpG', 'Production', NULL, 'adhar_images/1752478410_6.jpg', 'pan_images/1752478410_4.jpg', 'Active', '2025-07-14 02:03:30', '2025-07-14 02:13:52'),
(11, 'Neel Patel', 'Neetbhadbn@gmail.com', '7485965263', 'Masjdcjn', '$2y$12$ohDtHK7ojTqUjZ5ga0PYd.NUh3Qa.zmv5NMTvWU8PjTuvmOCV.KgO', 'Inspection', NULL, 'adhar_images/1752478457_3.jpg', 'pan_images/1752478457_7.jpg', 'Active', '2025-07-14 02:04:18', '2025-07-14 02:04:31'),
(12, 'Vraj', 'Vrajrohit50@gmail.com', '7874494817', 'Nikol', '$2y$12$yIWxHI4tTSq/08R7jyyZeOoxFH7RoD9Z/Yzjhj86k3IbpTUPd4Ghy', 'Production', NULL, 'adhar_images/1753336395_logo.png', 'pan_images/1753336395_download1.jpg', 'Inactive', '2025-07-24 00:23:15', '2025-07-24 00:23:15');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inspections`
--

CREATE TABLE `inspections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `tool_type_id` bigint(20) UNSIGNED NOT NULL,
  `quantity_inspected` int(11) NOT NULL,
  `ok_quantity` int(11) NOT NULL,
  `rejected_quantity` int(11) NOT NULL,
  `rejection_reason` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inspections`
--

INSERT INTO `inspections` (`id`, `date`, `tool_type_id`, `quantity_inspected`, `ok_quantity`, `rejected_quantity`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(9, '2025-09-25', 7, 1200, 120, 10, 'Non-Filling', '2025-09-21 20:48:53', '2025-09-22 19:58:19');

-- --------------------------------------------------------

--
-- Table structure for table `inwardings`
--

CREATE TABLE `inwardings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `raw_material_type_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_date` date NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `number_of_pcs` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `material_issueds`
--

CREATE TABLE `material_issueds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `raw_material_type_id` bigint(20) UNSIGNED NOT NULL,
  `issue_date` date NOT NULL,
  `quantity` int(11) NOT NULL,
  `shift` enum('Day','Night') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_issueds`
--

INSERT INTO `material_issueds` (`id`, `raw_material_type_id`, `issue_date`, `quantity`, `shift`, `created_at`, `updated_at`) VALUES
(5, 1, '2025-08-17', 200, 'Day', '2025-07-14 18:54:04', '2025-07-14 18:58:43');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(5, '0001_01_01_000000_create_users_table', 1),
(6, '0001_01_01_000001_create_cache_table', 1),
(7, '0001_01_01_000002_create_jobs_table', 1),
(8, '2025_06_11_043224_create_admin_auth_table', 2),
(9, '2025_06_11_083152_create_employees_table', 3),
(10, '2025_06_11_095311_create_companies_table', 4),
(11, '2025_06_11_105955_create_tools_table', 5),
(12, '2025_06_12_133503_create_raw_material_types_table', 6),
(13, '2025_06_12_133523_create_available_raw_materials_table', 6),
(14, '2025_06_12_133639_create_consumable_raw_materials_table', 6),
(15, '2025_06_13_102107_create_casting_records_table', 7),
(16, '2025_06_20_095757_add_treem_to_casting_records_table', 8),
(17, '2025_06_21_165319_add_inspection_and_dispatch_to_casting_records_table', 9),
(18, '2025_07_12_101136_modify_user_type_in_employees_table', 10),
(19, '2025_07_12_102029_drop_user_type_from_employees_table', 11),
(20, '2025_07_12_102149_add1_user_type_to_employees_table', 12),
(21, '2025_07_13_234309_add_component_type_to_tools_table', 13),
(22, '2025_07_14_003332_create_vendor_table', 14),
(23, '2025_07_14_011032_modefied_rae_materials', 15),
(24, '2025_07_14_011529_modify_type_column_in_raw_material_types_table', 16),
(25, '2025_07_14_023159_create_inwardings_table', 17),
(26, '2025_07_14_024806_create_material_issueds_table', 18),
(27, '2025_07_14_234521_add_quantity_to_inwardings_table', 19),
(30, '2025_07_24_073124_update_casting_records_add_new_fields', 20),
(31, '2025_07_24_102846_create_process_records_table', 21),
(34, '2025_07_24_113226_alter_type_name_length_in_process_records_table', 22),
(36, '2025_07_25_113655_create_triming_table', 23),
(38, '2025_07_28_061632_create_inspections_table', 24),
(39, '2025_07_29_081505_create_dispatches_table', 25);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `process_records`
--

CREATE TABLE `process_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type_name` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `shift` enum('Day','Night') NOT NULL,
  `machine_no` int(11) DEFAULT 0,
  `tool_type_id` bigint(20) UNSIGNED NOT NULL,
  `machine_operator_name` varchar(255) DEFAULT NULL,
  `quantity_kg` decimal(10,2) DEFAULT NULL,
  `quantity_pcs` int(11) DEFAULT NULL,
  `quantity_inspected` int(11) DEFAULT NULL,
  `ok_number` int(11) DEFAULT NULL,
  `rejected_number` int(11) DEFAULT NULL,
  `reason_of_rejected` varchar(255) DEFAULT NULL,
  `quantity_dispatch` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `machine_operator_name1` varchar(255) DEFAULT NULL,
  `machine_operator_name2` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `process_records`
--

INSERT INTO `process_records` (`id`, `type_name`, `date`, `shift`, `machine_no`, `tool_type_id`, `machine_operator_name`, `quantity_kg`, `quantity_pcs`, `quantity_inspected`, `ok_number`, `rejected_number`, `reason_of_rejected`, `quantity_dispatch`, `created_at`, `updated_at`, `machine_operator_name1`, `machine_operator_name2`) VALUES
(1, 'Castings', '2025-12-05', 'Day', 2, 3, 'snjcnn', 80.00, 60, NULL, NULL, NULL, NULL, NULL, '2025-07-24 06:02:59', '2025-07-24 06:02:59', NULL, NULL),
(3, 'Trimings', '2025-07-16', 'Day', 6, 4, 'dfghjk', 90.00, 50, NULL, NULL, NULL, NULL, NULL, '2025-07-27 23:08:47', '2025-07-27 23:08:47', NULL, NULL),
(6, 'Inspections', '2025-12-05', 'Day', 0, 3, NULL, NULL, NULL, 50, 5, 50, 'Trimming', NULL, '2025-07-28 04:25:06', '2025-07-28 04:25:06', NULL, NULL),
(7, 'Castings', '2025-12-05', 'Day', 2, 3, 'hvhvv', 500.00, 500, NULL, NULL, NULL, NULL, NULL, '2025-07-28 11:39:49', '2025-07-28 11:39:49', NULL, NULL),
(8, 'Castings', '2025-12-05', 'Day', 2, 3, 'hvhvv', 200.00, 600, NULL, NULL, NULL, NULL, NULL, '2025-07-28 11:40:39', '2025-07-28 11:40:39', NULL, NULL),
(9, 'Trimings', '2025-12-05', 'Night', 3, 4, 'Breaker', 500.00, 600, NULL, NULL, NULL, NULL, NULL, '2025-07-29 01:35:56', '2025-07-29 01:35:56', NULL, NULL),
(10, 'Trimings', '2025-12-05', 'Night', 3, 4, 'Breaker', 500.00, 600, NULL, NULL, NULL, NULL, NULL, '2025-07-29 01:38:43', '2025-07-29 01:38:43', NULL, NULL),
(12, 'Inspections', '2025-12-05', 'Day', 0, 4, NULL, NULL, NULL, 50, 50, 50, 'Trimming', NULL, '2025-07-29 02:05:13', '2025-07-29 02:05:13', NULL, NULL),
(13, 'Inspections', '2025-12-12', 'Day', 0, 3, NULL, NULL, NULL, 80, 80, 88, 'Trimming', NULL, '2025-07-29 02:07:25', '2025-07-29 02:07:25', NULL, NULL),
(15, 'Inspections', '2025-12-12', 'Day', 0, 6, NULL, NULL, NULL, 5000, 500, 500, 'Non-Filling', NULL, '2025-07-29 03:29:23', '2025-07-29 03:29:23', NULL, NULL),
(16, 'Dispatch', '2025-12-12', 'Day', 0, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2100, '2025-07-29 03:29:51', '2025-07-29 03:29:51', NULL, NULL),
(17, 'Trimings', '2025-12-12', 'Night', 2, 4, 'sfcsf', 200.00, 6000, NULL, NULL, NULL, NULL, NULL, '2025-07-29 03:39:03', '2025-07-29 03:39:03', NULL, NULL),
(18, 'Trimings', '2022-12-12', 'Day', 2, 4, 'fgvbhjn', 500.00, 500, NULL, NULL, NULL, NULL, NULL, '2025-07-29 03:39:28', '2025-07-29 03:39:28', NULL, NULL),
(19, 'Inspections', '2025-12-12', 'Day', 0, 4, NULL, NULL, NULL, 500, 500, 500, 'Non-Filling', NULL, '2025-07-29 03:40:14', '2025-07-29 03:40:14', NULL, NULL),
(20, 'Inspections', '2025-12-14', 'Day', 0, 4, NULL, NULL, NULL, 700, 700, 7000, 'Trimming', NULL, '2025-07-29 03:41:40', '2025-07-29 03:41:40', NULL, NULL),
(21, 'Castings', '2025-12-12', 'Day', 1, 7, 'esdrtfhjn', 500.00, 5000, NULL, NULL, NULL, NULL, NULL, '2025-07-29 03:48:35', '2025-07-29 03:48:35', NULL, NULL),
(22, 'Trimings', '2025-12-12', 'Night', 1, 7, 'edfcgvbhn', 5100.00, 500, NULL, NULL, NULL, NULL, NULL, '2025-07-29 03:49:25', '2025-07-29 03:49:25', NULL, NULL),
(23, 'Trimings', '2025-12-12', 'Day', 1, 7, 'dxcfgvbh', 500.00, 1200, NULL, NULL, NULL, NULL, NULL, '2025-07-29 03:50:12', '2025-07-29 03:50:12', NULL, NULL),
(24, 'Castings', '2025-09-17', 'Day', 2, 4, NULL, 500.00, 500, NULL, NULL, NULL, NULL, NULL, '2025-09-21 09:00:58', '2025-09-21 09:00:58', 'hjhhbj', 'jhhjjh'),
(25, 'Trimings', '2025-09-24', 'Night', 2, 4, NULL, 700.00, 700, NULL, NULL, NULL, NULL, NULL, '2025-09-21 09:05:32', '2025-09-21 09:05:32', 'jhjjjh', 'jjjn'),
(26, 'Inspections', '2025-09-25', 'Day', 0, 7, NULL, NULL, NULL, 500, 800, 500, 'Non-Filling', NULL, '2025-09-21 20:48:53', '2025-09-21 20:48:53', NULL, NULL),
(27, 'Inspections', '2025-09-25', 'Day', 0, 7, NULL, NULL, NULL, 1200, 120, 10, 'Non-Filling', NULL, '2025-09-21 20:49:29', '2025-09-21 20:49:29', NULL, NULL),
(28, 'Dispatch', '2025-09-25', 'Day', 0, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 100, '2025-09-22 12:10:58', '2025-09-22 12:10:58', NULL, NULL),
(29, 'Dispatch', '2025-09-25', 'Day', 0, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 200, '2025-09-22 12:15:23', '2025-09-22 12:15:23', NULL, NULL),
(30, 'Dispatch', '2025-09-24', 'Day', 0, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 200, '2025-09-22 19:58:19', '2025-09-22 19:58:19', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `raw_material_types`
--

CREATE TABLE `raw_material_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `measuring_unit` varchar(255) DEFAULT NULL,
  `opening_stock` decimal(10,2) NOT NULL DEFAULT 0.00,
  `type` enum('Raw Material','Consumable') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `raw_material_types`
--

INSERT INTO `raw_material_types` (`id`, `name`, `created_at`, `updated_at`, `measuring_unit`, `opening_stock`, `type`) VALUES
(1, 'Bike Cluch', '2025-06-12 09:04:09', '2025-07-13 20:07:18', '90l', 80.00, 'Raw Material'),
(5, 'afsfgagdgh', '2025-07-13 19:59:06', '2025-07-13 19:59:06', '80kg', 90.00, 'Consumable');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('6WDn8av5t6f46Nk7EeLeKw6AiutVeClWEghL2XgZ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:143.0) Gecko/20100101 Firefox/143.0', 'YTo5OntzOjY6Il90b2tlbiI7czo0MDoiWHJLYlpwanhLWjJrc21wdjk0RE5HSkZ1aE94UjdUaFBsakhvMGxEYSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9kaXNwYXRjaCI7fXM6MTg6ImVtcGxveWVlX2xvZ2dlZF9pbiI7YjoxO3M6MTE6ImVtcGxveWVlX2lkIjtpOjExO3M6MTM6ImVtcGxveWVlX25hbWUiO3M6MTA6Ik5lZWwgUGF0ZWwiO3M6MTQ6ImVtcGxveWVlX2VtYWlsIjtzOjIwOiJOZWV0YmhhZGJuQGdtYWlsLmNvbSI7czoxNDoiZW1wbG95ZWVfaW1hZ2UiO3M6Mjk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9zdG9yYWdlIjtzOjEzOiJlbXBsb3llZV90eXBlIjtzOjEwOiJJbnNwZWN0aW9uIjt9', 1758591050),
('MVL5IrOHBp0WpJ3CXHqGatxP2xDXvEmu9f4lUecC', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:143.0) Gecko/20100101 Firefox/143.0', 'YTo5OntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMjoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2luc3BlY3Rpb24iO31zOjY6Il90b2tlbiI7czo0MDoiYTJPVU1FdFF1ODcwblRYQzZ6ZUV1TktHeWNUckY1SFRLcE1LMU1PNSI7czoxODoiZW1wbG95ZWVfbG9nZ2VkX2luIjtiOjE7czoxMToiZW1wbG95ZWVfaWQiO2k6MTE7czoxMzoiZW1wbG95ZWVfbmFtZSI7czoxMDoiTmVlbCBQYXRlbCI7czoxNDoiZW1wbG95ZWVfZW1haWwiO3M6MjA6Ik5lZXRiaGFkYm5AZ21haWwuY29tIjtzOjE0OiJlbXBsb3llZV9pbWFnZSI7czoyOToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL3N0b3JhZ2UiO3M6MTM6ImVtcGxveWVlX3R5cGUiO3M6MTA6Ikluc3BlY3Rpb24iO30=', 1758507569),
('RYxPdZa3dxlbI2ttODbqWvJTuNEQlpNBONuskxqs', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:143.0) Gecko/20100101 Firefox/143.0', 'YTo5OntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMDoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2Rpc3BhdGNoIjt9czo2OiJfdG9rZW4iO3M6NDA6IlpEdk5EZVg3a2M4M2ZFdk1XNzVtOG5EcENOTHQyclRPQ3FMNVNndEciO3M6MTg6ImVtcGxveWVlX2xvZ2dlZF9pbiI7YjoxO3M6MTE6ImVtcGxveWVlX2lkIjtpOjExO3M6MTM6ImVtcGxveWVlX25hbWUiO3M6MTA6Ik5lZWwgUGF0ZWwiO3M6MTQ6ImVtcGxveWVlX2VtYWlsIjtzOjIwOiJOZWV0YmhhZGJuQGdtYWlsLmNvbSI7czoxNDoiZW1wbG95ZWVfaW1hZ2UiO3M6Mjk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9zdG9yYWdlIjtzOjEzOiJlbXBsb3llZV90eXBlIjtzOjEwOiJJbnNwZWN0aW9uIjt9', 1758563123);

-- --------------------------------------------------------

--
-- Table structure for table `tools`
--

CREATE TABLE `tools` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `component_type` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tools`
--

INSERT INTO `tools` (`id`, `name`, `component_type`, `image`, `created_at`, `updated_at`) VALUES
(3, 'Cluth plate', '', 'tools/xgUsxllDngCsifL21DNgMWmavcdII1XhWc1JAMIS.jpg', '2025-06-20 02:53:09', '2025-06-20 02:53:09'),
(4, 'Break Liner', '', 'tools/SIUfmRSWeoVNnyacp7Ib0X3fpw0RYXc7mKl1ZANr.jpg', '2025-06-20 02:53:39', '2025-06-20 02:53:39'),
(6, 'HGHGhg', 'Casting Tool', 'tools/1752450375_1.jpg', '2025-07-13 18:16:15', '2025-07-13 18:16:15'),
(7, 'vgacg', 'Casting Tool', 'tools/1752451719_4.jpg', '2025-07-13 18:38:39', '2025-07-13 18:44:56'),
(8, 'shbdcbh', 'Casting Tool', NULL, '2025-07-29 04:11:40', '2025-07-29 04:11:40');

-- --------------------------------------------------------

--
-- Table structure for table `trimming_records`
--

CREATE TABLE `trimming_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `shift` varchar(255) NOT NULL,
  `machine_no` int(11) NOT NULL,
  `tool_type_id` bigint(20) UNSIGNED NOT NULL,
  `operator_name` varchar(255) DEFAULT NULL,
  `quantity_kg` double NOT NULL,
  `quantity_pcs` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `operator_name1` varchar(255) DEFAULT NULL,
  `operator_name2` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trimming_records`
--

INSERT INTO `trimming_records` (`id`, `date`, `shift`, `machine_no`, `tool_type_id`, `operator_name`, `quantity_kg`, `quantity_pcs`, `created_at`, `updated_at`, `operator_name1`, `operator_name2`) VALUES
(2, '2025-07-17', 'Night', 2, 3, 'hbsadcnbsbnadc', 50, 50, '2025-07-27 22:50:05', '2025-07-27 22:50:05', NULL, NULL),
(3, '2025-07-18', 'Night', 1, 3, 'shgadchhj', 80, 50, '2025-07-27 22:53:21', '2025-07-27 22:53:21', NULL, NULL),
(8, '2025-09-24', 'Night', 2, 4, 'sfcsf', 200, 6000, '2025-07-29 03:39:03', '2025-09-21 09:05:32', 'jhjjjh', 'jjjn'),
(10, '2025-12-12', 'Night', 1, 7, 'edfcgvbhn', 5100, 0, '2025-07-29 03:49:25', '2025-09-21 20:49:29', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `created_at`, `updated_at`) VALUES
(3, 'JNdnnb', '2025-07-13 19:21:39', '2025-07-13 19:21:39'),
(4, 'HGdvhvh', '2025-07-13 19:21:43', '2025-07-13 19:21:43'),
(5, 'jbsdbn', '2025-07-13 19:21:47', '2025-07-13 19:21:47'),
(6, 'ggvvg', '2025-07-29 04:00:48', '2025-07-29 04:00:48'),
(7, 'jnsdcj', '2025-07-29 04:09:13', '2025-07-29 04:09:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_auth`
--
ALTER TABLE `admin_auth`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_auth_email_unique` (`email`);

--
-- Indexes for table `available_raw_materials`
--
ALTER TABLE `available_raw_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `available_raw_materials_raw_material_type_id_foreign` (`raw_material_type_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `casting_records`
--
ALTER TABLE `casting_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `casting_records_tool_type_id_foreign` (`tool_type_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `consumable_raw_materials`
--
ALTER TABLE `consumable_raw_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `consumable_raw_materials_raw_material_type_id_foreign` (`raw_material_type_id`);

--
-- Indexes for table `dispatches`
--
ALTER TABLE `dispatches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_email_unique` (`email`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `inspections`
--
ALTER TABLE `inspections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inwardings`
--
ALTER TABLE `inwardings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inwardings_raw_material_type_id_foreign` (`raw_material_type_id`),
  ADD KEY `inwardings_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `material_issueds`
--
ALTER TABLE `material_issueds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_issueds_raw_material_type_id_foreign` (`raw_material_type_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `process_records`
--
ALTER TABLE `process_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `process_records_tool_type_id_foreign` (`tool_type_id`);

--
-- Indexes for table `raw_material_types`
--
ALTER TABLE `raw_material_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tools`
--
ALTER TABLE `tools`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trimming_records`
--
ALTER TABLE `trimming_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trimming_records_tool_type_id_foreign` (`tool_type_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_auth`
--
ALTER TABLE `admin_auth`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `available_raw_materials`
--
ALTER TABLE `available_raw_materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `casting_records`
--
ALTER TABLE `casting_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `consumable_raw_materials`
--
ALTER TABLE `consumable_raw_materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `dispatches`
--
ALTER TABLE `dispatches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inspections`
--
ALTER TABLE `inspections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `inwardings`
--
ALTER TABLE `inwardings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material_issueds`
--
ALTER TABLE `material_issueds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `process_records`
--
ALTER TABLE `process_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `raw_material_types`
--
ALTER TABLE `raw_material_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tools`
--
ALTER TABLE `tools`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `trimming_records`
--
ALTER TABLE `trimming_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `available_raw_materials`
--
ALTER TABLE `available_raw_materials`
  ADD CONSTRAINT `available_raw_materials_raw_material_type_id_foreign` FOREIGN KEY (`raw_material_type_id`) REFERENCES `raw_material_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `casting_records`
--
ALTER TABLE `casting_records`
  ADD CONSTRAINT `casting_records_tool_type_id_foreign` FOREIGN KEY (`tool_type_id`) REFERENCES `tools` (`id`);

--
-- Constraints for table `consumable_raw_materials`
--
ALTER TABLE `consumable_raw_materials`
  ADD CONSTRAINT `consumable_raw_materials_raw_material_type_id_foreign` FOREIGN KEY (`raw_material_type_id`) REFERENCES `raw_material_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inwardings`
--
ALTER TABLE `inwardings`
  ADD CONSTRAINT `inwardings_raw_material_type_id_foreign` FOREIGN KEY (`raw_material_type_id`) REFERENCES `raw_material_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inwardings_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `material_issueds`
--
ALTER TABLE `material_issueds`
  ADD CONSTRAINT `material_issueds_raw_material_type_id_foreign` FOREIGN KEY (`raw_material_type_id`) REFERENCES `raw_material_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `process_records`
--
ALTER TABLE `process_records`
  ADD CONSTRAINT `process_records_tool_type_id_foreign` FOREIGN KEY (`tool_type_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trimming_records`
--
ALTER TABLE `trimming_records`
  ADD CONSTRAINT `trimming_records_tool_type_id_foreign` FOREIGN KEY (`tool_type_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
