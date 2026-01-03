-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 30, 2025 at 05:04 PM
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
-- Database: `opvenio_hr2`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `activity` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `activity`, `details`, `status`, `created_at`, `updated_at`) VALUES
(51, 3, 'Admin User', 'assign_competency', 'Assigned competency \'Stakeholder Management\' to Juan Dela Cruz - Type: gap_closure, Priority: high', 'Success', '2025-11-29 09:24:30', '2025-11-29 09:24:30'),
(52, 3, 'Admin User', 'assign_skill_gap', 'Assigned skill gap action for Juan Dela Cruz - Competency: assignment_skills, Action: critical', 'Success', '2025-11-29 13:26:34', '2025-11-29 13:26:34'),
(53, 3, 'Admin User', 'assign_skill_gap', 'Assigned skill gap action for Maria Santos - Competency: accountability, Action: critical', 'Success', '2025-12-06 05:39:28', '2025-12-06 05:39:28'),
(54, 3, 'Admin User', 'assign_competency', 'Assigned competency \'Accounting Standards\' to Maria Santos - Type: gap_closure, Priority: high', 'Success', '2025-12-06 05:55:35', '2025-12-06 05:55:35'),
(55, 3, 'Admin User', 'assign_competency', 'Assigned competency \'Financial Analysis\' to Maria Santos - Type: gap_closure, Priority: high', 'Success', '2025-12-06 05:55:36', '2025-12-06 05:55:36');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `details` varchar(255) DEFAULT NULL,
  `time_in` timestamp NULL DEFAULT NULL,
  `time_out` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Success',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `user_name`, `activity`, `details`, `time_in`, `time_out`, `status`, `created_at`, `updated_at`) VALUES
(209, 3, 'Admin User', 'Logout', 'User logged out', NULL, '2025-11-28 15:17:16', 'Success', '2025-11-28 15:17:16', '2025-11-28 15:17:16'),
(210, 3, 'Admin User', 'Logout', 'User logged out', NULL, '2025-11-28 15:17:16', 'Success', '2025-11-28 15:17:16', '2025-11-28 15:17:16'),
(211, 3, 'Admin User', 'Login', 'User logged in', '2025-11-28 15:19:08', NULL, 'Success', '2025-11-28 15:19:08', '2025-11-28 15:19:08'),
(212, 3, 'Admin User', 'Login', 'User logged in', '2025-11-28 15:19:08', NULL, 'Success', '2025-11-28 15:19:08', '2025-11-28 15:19:08'),
(213, 3, 'Admin User', 'Logout', 'User logged out', '2025-11-29 05:26:37', '2025-11-29 06:06:57', 'Success', '2025-11-29 05:26:37', '2025-11-29 06:06:57'),
(214, 3, 'Admin User', 'Logout', 'User logged out', '2025-11-29 05:26:37', '2025-11-29 06:06:57', 'Success', '2025-11-29 05:26:37', '2025-11-29 06:06:57'),
(215, 4, 'Juan Dela Cruz', 'Logout', 'User logged out', '2025-11-29 06:08:57', '2025-11-29 06:10:55', 'Success', '2025-11-29 06:08:57', '2025-11-29 06:10:55'),
(216, 4, 'Juan Dela Cruz', 'Logout', 'User logged out', '2025-11-29 06:08:57', '2025-11-29 06:10:55', 'Success', '2025-11-29 06:08:57', '2025-11-29 06:10:55'),
(217, 3, 'Admin User', 'Logout', 'User logged out', '2025-11-29 06:11:11', '2025-11-29 06:18:32', 'Success', '2025-11-29 06:11:11', '2025-11-29 06:18:32'),
(218, 3, 'Admin User', 'Logout', 'User logged out', '2025-11-29 06:11:11', '2025-11-29 06:18:32', 'Success', '2025-11-29 06:11:11', '2025-11-29 06:18:32'),
(219, 4, 'Juan Dela Cruz', 'Logout', 'User logged out', '2025-11-29 06:20:18', '2025-11-29 06:25:15', 'Success', '2025-11-29 06:20:18', '2025-11-29 06:25:15'),
(220, 4, 'Juan Dela Cruz', 'Logout', 'User logged out', '2025-11-29 06:20:18', '2025-11-29 06:25:15', 'Success', '2025-11-29 06:20:18', '2025-11-29 06:25:15'),
(221, 3, 'Admin User', 'Logout', 'User logged out', '2025-11-29 06:25:34', '2025-11-29 07:15:57', 'Success', '2025-11-29 06:25:34', '2025-11-29 07:15:57'),
(222, 3, 'Admin User', 'Logout', 'User logged out', '2025-11-29 06:25:34', '2025-11-29 07:15:57', 'Success', '2025-11-29 06:25:34', '2025-11-29 07:15:57'),
(223, 4, 'Juan Dela Cruz', 'Logout', 'User logged out', '2025-11-29 07:16:18', '2025-11-29 07:37:55', 'Success', '2025-11-29 07:16:18', '2025-11-29 07:37:55'),
(224, 4, 'Juan Dela Cruz', 'Logout', 'User logged out', '2025-11-29 07:16:18', '2025-11-29 07:37:55', 'Success', '2025-11-29 07:16:18', '2025-11-29 07:37:55'),
(225, 3, 'Admin User', 'Login', 'User logged in', '2025-11-29 07:38:10', NULL, 'Success', '2025-11-29 07:38:10', '2025-11-29 07:38:10'),
(226, 3, 'Admin User', 'Login', 'User logged in', '2025-11-29 07:38:10', NULL, 'Success', '2025-11-29 07:38:10', '2025-11-29 07:38:10'),
(227, 3, 'Admin User', 'Logout', 'User logged out', '2025-11-29 13:26:19', '2025-11-29 14:45:14', 'Success', '2025-11-29 13:26:19', '2025-11-29 14:45:14'),
(228, 3, 'Admin User', 'Logout', 'User logged out', '2025-11-29 13:26:19', '2025-11-29 14:45:14', 'Success', '2025-11-29 13:26:19', '2025-11-29 14:45:14'),
(229, 4, 'Juan Dela Cruz', 'Logout', 'User logged out', '2025-11-29 14:45:35', '2025-11-29 14:54:55', 'Success', '2025-11-29 14:45:35', '2025-11-29 14:54:55'),
(230, 4, 'Juan Dela Cruz', 'Logout', 'User logged out', '2025-11-29 14:45:35', '2025-11-29 14:54:55', 'Success', '2025-11-29 14:45:35', '2025-11-29 14:54:55'),
(231, 3, 'Admin User', 'Logout', 'User logged out', '2025-11-29 14:55:27', '2025-11-29 16:31:20', 'Success', '2025-11-29 14:55:27', '2025-11-29 16:31:20'),
(232, 3, 'Admin User', 'Logout', 'User logged out', '2025-11-29 14:55:27', '2025-11-29 16:31:20', 'Success', '2025-11-29 14:55:27', '2025-11-29 16:31:20'),
(233, 4, 'Juan Dela Cruz', 'Logout', 'User logged out', '2025-11-29 16:31:49', '2025-11-29 16:37:04', 'Success', '2025-11-29 16:31:49', '2025-11-29 16:37:04'),
(234, 4, 'Juan Dela Cruz', 'Logout', 'User logged out', '2025-11-29 16:31:49', '2025-11-29 16:37:04', 'Success', '2025-11-29 16:31:49', '2025-11-29 16:37:04'),
(235, 3, 'Admin User', 'Logout', 'User logged out', '2025-11-29 16:37:18', '2025-11-29 16:39:24', 'Success', '2025-11-29 16:37:18', '2025-11-29 16:39:24'),
(236, 3, 'Admin User', 'Logout', 'User logged out', '2025-11-29 16:37:18', '2025-11-29 16:39:24', 'Success', '2025-11-29 16:37:18', '2025-11-29 16:39:24'),
(237, 4, 'Juan Dela Cruz', 'Logout', 'User logged out', '2025-11-29 16:39:52', '2025-11-29 16:41:14', 'Success', '2025-11-29 16:39:52', '2025-11-29 16:41:14'),
(238, 4, 'Juan Dela Cruz', 'Logout', 'User logged out', '2025-11-29 16:39:52', '2025-11-29 16:41:14', 'Success', '2025-11-29 16:39:52', '2025-11-29 16:41:14'),
(239, 3, 'Admin User', 'Logout', 'User logged out', '2025-11-29 16:41:33', '2025-11-29 17:08:20', 'Success', '2025-11-29 16:41:33', '2025-11-29 17:08:20'),
(240, 3, 'Admin User', 'Logout', 'User logged out', '2025-11-29 16:41:33', '2025-11-29 17:08:20', 'Success', '2025-11-29 16:41:33', '2025-11-29 17:08:20'),
(241, 4, 'Juan Dela Cruz', 'Login', 'User logged in', '2025-11-29 17:08:45', NULL, 'Success', '2025-11-29 17:08:45', '2025-11-29 17:08:45'),
(242, 4, 'Juan Dela Cruz', 'Login', 'User logged in', '2025-11-29 17:08:45', NULL, 'Success', '2025-11-29 17:08:45', '2025-11-29 17:08:45'),
(243, 4, 'Juan Dela Cruz', 'Logout', 'User logged out', '2025-11-30 14:49:24', '2025-11-30 15:06:40', 'Success', '2025-11-30 14:49:24', '2025-11-30 15:06:40'),
(244, 4, 'Juan Dela Cruz', 'Logout', 'User logged out', '2025-11-30 14:49:24', '2025-11-30 15:06:40', 'Success', '2025-11-30 14:49:24', '2025-11-30 15:06:40'),
(245, 3, 'Admin User', 'Login', 'User logged in', '2025-11-30 15:06:58', NULL, 'Success', '2025-11-30 15:06:58', '2025-11-30 15:06:58'),
(246, 3, 'Admin User', 'Login', 'User logged in', '2025-11-30 15:06:58', NULL, 'Success', '2025-11-30 15:06:58', '2025-11-30 15:06:58'),
(247, 3, 'Admin User', 'Login', 'User logged in', '2025-12-03 14:57:07', NULL, 'Success', '2025-12-03 14:57:07', '2025-12-03 14:57:07'),
(248, 3, 'Admin User', 'Login', 'User logged in', '2025-12-03 14:57:07', NULL, 'Success', '2025-12-03 14:57:07', '2025-12-03 14:57:07'),
(249, 3, 'Admin User', 'Logout', 'User logged out', '2025-12-06 03:18:26', '2025-12-06 03:36:29', 'Success', '2025-12-06 03:18:26', '2025-12-06 03:36:29'),
(250, 3, 'Admin User', 'Logout', 'User logged out', '2025-12-06 03:18:26', '2025-12-06 03:36:29', 'Success', '2025-12-06 03:18:26', '2025-12-06 03:36:29'),
(251, 5, 'Maria Santos', 'Logout', 'User logged out', '2025-12-06 03:36:53', '2025-12-06 03:37:47', 'Success', '2025-12-06 03:36:53', '2025-12-06 03:37:47'),
(252, 5, 'Maria Santos', 'Logout', 'User logged out', '2025-12-06 03:36:53', '2025-12-06 03:37:47', 'Success', '2025-12-06 03:36:53', '2025-12-06 03:37:47'),
(253, 3, 'Admin User', 'Logout', 'User logged out', '2025-12-06 03:40:18', '2025-12-06 03:53:21', 'Success', '2025-12-06 03:40:18', '2025-12-06 03:53:21'),
(254, 3, 'Admin User', 'Logout', 'User logged out', '2025-12-06 03:40:18', '2025-12-06 03:53:21', 'Success', '2025-12-06 03:40:18', '2025-12-06 03:53:21'),
(255, 5, 'Maria Santos', 'Logout', 'User logged out', '2025-12-06 03:53:49', '2025-12-06 04:07:31', 'Success', '2025-12-06 03:53:49', '2025-12-06 04:07:31'),
(256, 5, 'Maria Santos', 'Logout', 'User logged out', '2025-12-06 03:53:50', '2025-12-06 04:07:31', 'Success', '2025-12-06 03:53:50', '2025-12-06 04:07:31'),
(257, 3, 'Admin User', 'Logout', 'User logged out', '2025-12-06 04:07:48', '2025-12-06 05:56:27', 'Success', '2025-12-06 04:07:48', '2025-12-06 05:56:27'),
(258, 3, 'Admin User', 'Logout', 'User logged out', '2025-12-06 04:07:48', '2025-12-06 05:56:27', 'Success', '2025-12-06 04:07:48', '2025-12-06 05:56:27'),
(259, 5, 'Maria Santos', 'Logout', 'User logged out', '2025-12-06 05:56:46', '2025-12-06 05:57:41', 'Success', '2025-12-06 05:56:46', '2025-12-06 05:57:41'),
(260, 5, 'Maria Santos', 'Logout', 'User logged out', '2025-12-06 05:56:46', '2025-12-06 05:57:41', 'Success', '2025-12-06 05:56:46', '2025-12-06 05:57:41'),
(261, 3, 'Admin User', 'Logout', 'User logged out', '2025-12-06 05:57:57', '2025-12-06 06:24:32', 'Success', '2025-12-06 05:57:57', '2025-12-06 06:24:32'),
(262, 3, 'Admin User', 'Logout', 'User logged out', '2025-12-06 05:57:57', '2025-12-06 06:24:32', 'Success', '2025-12-06 05:57:57', '2025-12-06 06:24:32'),
(263, 5, 'Maria Santos', 'Logout', 'User logged out', '2025-12-06 06:26:25', '2025-12-06 06:29:01', 'Success', '2025-12-06 06:26:25', '2025-12-06 06:29:01'),
(264, 5, 'Maria Santos', 'Logout', 'User logged out', '2025-12-06 06:26:25', '2025-12-06 06:29:01', 'Success', '2025-12-06 06:26:25', '2025-12-06 06:29:01'),
(265, 3, 'Admin User', 'Logout', 'User logged out', '2025-12-06 06:31:00', '2025-12-06 10:54:11', 'Success', '2025-12-06 06:31:00', '2025-12-06 10:54:11'),
(266, 3, 'Admin User', 'Logout', 'User logged out', '2025-12-06 06:31:00', '2025-12-06 10:54:11', 'Success', '2025-12-06 06:31:00', '2025-12-06 10:54:11'),
(267, 4, 'Juan Dela Cruz', 'Login', 'User logged in', '2025-12-06 10:54:49', NULL, 'Success', '2025-12-06 10:54:49', '2025-12-06 10:54:49'),
(268, 4, 'Juan Dela Cruz', 'Login', 'User logged in', '2025-12-06 10:54:49', NULL, 'Success', '2025-12-06 10:54:49', '2025-12-06 10:54:49'),
(269, 3, 'Admin User', 'Login', 'User logged in', '2025-12-07 01:32:26', NULL, 'Success', '2025-12-07 01:32:26', '2025-12-07 01:32:26'),
(270, 3, 'Admin User', 'Login', 'User logged in', '2025-12-07 01:32:26', NULL, 'Success', '2025-12-07 01:32:26', '2025-12-07 01:32:26'),
(271, 3, 'Admin User', 'Login', 'User logged in', '2025-12-07 23:32:43', NULL, 'Success', '2025-12-07 23:32:43', '2025-12-07 23:32:43'),
(272, 3, 'Admin User', 'Login', 'User logged in', '2025-12-07 23:32:43', NULL, 'Success', '2025-12-07 23:32:43', '2025-12-07 23:32:43'),
(273, 3, 'Admin User', 'Login', 'User logged in', '2025-12-11 06:04:27', NULL, 'Success', '2025-12-11 06:04:27', '2025-12-11 06:04:27'),
(274, 3, 'Admin User', 'Login', 'User logged in', '2025-12-11 06:04:27', NULL, 'Success', '2025-12-11 06:04:27', '2025-12-11 06:04:27'),
(275, 3, 'Admin User', 'Logout', 'User logged out', '2025-12-11 06:05:01', '2025-12-11 14:15:17', 'Success', '2025-12-11 06:05:01', '2025-12-11 14:15:17'),
(276, 3, 'Admin User', 'Logout', 'User logged out', '2025-12-11 06:05:01', '2025-12-11 14:15:17', 'Success', '2025-12-11 06:05:01', '2025-12-11 14:15:17'),
(277, 3, 'Admin User', 'Logout', 'User logged out', '2025-12-11 14:15:38', '2025-12-11 14:30:21', 'Success', '2025-12-11 14:15:38', '2025-12-11 14:30:21'),
(278, 3, 'Admin User', 'Logout', 'User logged out', '2025-12-11 14:15:38', '2025-12-11 14:30:21', 'Success', '2025-12-11 14:15:38', '2025-12-11 14:30:21'),
(279, 3, 'Admin User', 'Logout', 'User logged out', '2025-12-11 14:43:05', '2025-12-11 14:46:39', 'Success', '2025-12-11 14:43:05', '2025-12-11 14:46:39'),
(280, 3, 'Admin User', 'Logout', 'User logged out', '2025-12-11 14:43:05', '2025-12-11 14:46:39', 'Success', '2025-12-11 14:43:05', '2025-12-11 14:46:39'),
(281, 12, 'Chester Dapatnapo', 'Logout', 'User logged out', '2025-12-11 14:47:02', '2025-12-11 15:02:48', 'Success', '2025-12-11 14:47:02', '2025-12-11 15:02:48'),
(282, 12, 'Chester Dapatnapo', 'Logout', 'User logged out', '2025-12-11 14:47:02', '2025-12-11 15:02:48', 'Success', '2025-12-11 14:47:02', '2025-12-11 15:02:48'),
(283, 12, 'Chester Dapatnapo', 'Logout', 'User logged out', '2025-12-11 15:02:59', '2025-12-11 15:13:19', 'Success', '2025-12-11 15:02:59', '2025-12-11 15:13:19'),
(284, 12, 'Chester Dapatnapo', 'Logout', 'User logged out', '2025-12-11 15:02:59', '2025-12-11 15:13:19', 'Success', '2025-12-11 15:02:59', '2025-12-11 15:13:19'),
(285, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-11 15:13:43', NULL, 'Success', '2025-12-11 15:13:43', '2025-12-11 15:13:43'),
(286, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-11 15:13:43', NULL, 'Success', '2025-12-11 15:13:43', '2025-12-11 15:13:43'),
(287, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-12 15:07:09', NULL, 'Success', '2025-12-12 15:07:09', '2025-12-12 15:07:09'),
(288, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-12 15:07:09', NULL, 'Success', '2025-12-12 15:07:09', '2025-12-12 15:07:09'),
(289, 12, 'Chester Dapatnapo', 'Logout', 'User logged out', '2025-12-14 15:13:04', '2025-12-14 15:15:40', 'Success', '2025-12-14 15:13:04', '2025-12-14 15:15:40'),
(290, 12, 'Chester Dapatnapo', 'Logout', 'User logged out', '2025-12-14 15:13:04', '2025-12-14 15:15:40', 'Success', '2025-12-14 15:13:04', '2025-12-14 15:15:40'),
(291, 7, 'Angela Cruz', 'Logout', 'User logged out', '2025-12-14 15:17:12', '2025-12-14 15:18:58', 'Success', '2025-12-14 15:17:12', '2025-12-14 15:18:58'),
(292, 7, 'Angela Cruz', 'Logout', 'User logged out', '2025-12-14 15:17:12', '2025-12-14 15:18:58', 'Success', '2025-12-14 15:17:12', '2025-12-14 15:18:58'),
(293, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-14 15:19:40', NULL, 'Success', '2025-12-14 15:19:40', '2025-12-14 15:19:40'),
(294, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-14 15:19:40', NULL, 'Success', '2025-12-14 15:19:40', '2025-12-14 15:19:40'),
(295, 7, 'Angela Cruz', 'Login', 'User logged in', '2025-12-14 15:20:03', NULL, 'Success', '2025-12-14 15:20:03', '2025-12-14 15:20:03'),
(296, 7, 'Angela Cruz', 'Login', 'User logged in', '2025-12-14 15:20:03', NULL, 'Success', '2025-12-14 15:20:03', '2025-12-14 15:20:03'),
(297, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-15 15:33:59', NULL, 'Success', '2025-12-15 15:33:59', '2025-12-15 15:33:59'),
(298, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-15 15:33:59', NULL, 'Success', '2025-12-15 15:33:59', '2025-12-15 15:33:59'),
(299, 3, 'Admin User', 'Login', 'User logged in', '2025-12-15 16:47:04', NULL, 'Success', '2025-12-15 16:47:04', '2025-12-15 16:47:04'),
(300, 3, 'Admin User', 'Login', 'User logged in', '2025-12-15 16:47:04', NULL, 'Success', '2025-12-15 16:47:04', '2025-12-15 16:47:04'),
(301, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-16 14:57:19', NULL, 'Success', '2025-12-16 14:57:19', '2025-12-16 14:57:19'),
(302, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-16 14:57:19', NULL, 'Success', '2025-12-16 14:57:19', '2025-12-16 14:57:19'),
(303, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-17 06:21:16', NULL, 'Success', '2025-12-17 06:21:16', '2025-12-17 06:21:16'),
(304, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-17 06:21:16', NULL, 'Success', '2025-12-17 06:21:16', '2025-12-17 06:21:16'),
(305, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-17 14:27:38', NULL, 'Success', '2025-12-17 14:27:38', '2025-12-17 14:27:38'),
(306, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-17 14:27:38', NULL, 'Success', '2025-12-17 14:27:38', '2025-12-17 14:27:38'),
(307, 3, 'Admin User', 'Logout', 'User logged out', '2025-12-22 14:06:25', '2025-12-22 14:07:10', 'Success', '2025-12-22 14:06:25', '2025-12-22 14:07:10'),
(308, 3, 'Admin User', 'Logout', 'User logged out', '2025-12-22 14:06:25', '2025-12-22 14:07:10', 'Success', '2025-12-22 14:06:25', '2025-12-22 14:07:10'),
(309, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-22 14:07:20', NULL, 'Success', '2025-12-22 14:07:20', '2025-12-22 14:07:20'),
(310, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-22 14:07:20', NULL, 'Success', '2025-12-22 14:07:20', '2025-12-22 14:07:20'),
(311, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-23 06:35:28', NULL, 'Success', '2025-12-23 06:35:28', '2025-12-23 06:35:28'),
(312, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-23 06:35:28', NULL, 'Success', '2025-12-23 06:35:28', '2025-12-23 06:35:28'),
(313, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-27 13:46:56', NULL, 'Success', '2025-12-27 13:46:56', '2025-12-27 13:46:56'),
(314, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-27 13:46:56', NULL, 'Success', '2025-12-27 13:46:56', '2025-12-27 13:46:56'),
(315, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-28 15:01:26', NULL, 'Success', '2025-12-28 15:01:26', '2025-12-28 15:01:26'),
(316, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-28 15:01:26', NULL, 'Success', '2025-12-28 15:01:26', '2025-12-28 15:01:26'),
(317, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-29 13:44:25', NULL, 'Success', '2025-12-29 13:44:25', '2025-12-29 13:44:25'),
(318, 12, 'Chester Dapatnapo', 'Login', 'User logged in', '2025-12-29 13:44:25', NULL, 'Success', '2025-12-29 13:44:25', '2025-12-29 13:44:25');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-52f4ce480d6699fbf5bf578a12f8de53', 'i:1;', 1765725661),
('laravel-cache-52f4ce480d6699fbf5bf578a12f8de53:timer', 'i:1765725661;', 1765725661),
('laravel-cache-90334b343bc2c2d22068896da8c2b648', 'i:2;', 1765552011),
('laravel-cache-90334b343bc2c2d22068896da8c2b648:timer', 'i:1765552011;', 1765552011),
('laravel-cache-af85ddb54cdabd28600fdf1e0412a7c2', 'i:4;', 1765725417),
('laravel-cache-af85ddb54cdabd28600fdf1e0412a7c2:timer', 'i:1765725417;', 1765725417),
('laravel-cache-angela.cruz@example.com|127.0.0.1', 'i:4;', 1765725417),
('laravel-cache-angela.cruz@example.com|127.0.0.1:timer', 'i:1765725417;', 1765725417),
('laravel-cache-b30f09c53be4dc4ae8ae03899f660fa2', 'i:1;', 1767015921),
('laravel-cache-b30f09c53be4dc4ae8ae03899f660fa2:timer', 'i:1767015921;', 1767015921),
('laravel-cache-cf9ad535d6ca1f7f9a0288fca78bb1b7', 'i:1;', 1766412444),
('laravel-cache-cf9ad535d6ca1f7f9a0288fca78bb1b7:timer', 'i:1766412444;', 1766412444),
('laravel-cache-chesterdapatnapo@gmail.com|127.0.0.1', 'i:2;', 1765552015),
('laravel-cache-chesterdapatnapo@gmail.com|127.0.0.1:timer', 'i:1765552015;', 1765552015),
('laravel-cache-external_employees', 'a:4:{i:0;a:6:{s:2:\"id\";i:1;s:11:\"employee_id\";s:7:\"EMP-001\";s:9:\"full_name\";s:14:\"Juan Dela Cruz\";s:5:\"email\";s:25:\"juan.delacruz@company.com\";s:17:\"employment_status\";s:10:\"Terminated\";s:9:\"job_title\";s:18:\"Payroll Specialist\";}i:1;a:6:{s:2:\"id\";i:2;s:11:\"employee_id\";s:7:\"EMP-002\";s:9:\"full_name\";s:12:\"Maria Santos\";s:5:\"email\";s:24:\"maria.santos@company.com\";s:17:\"employment_status\";s:6:\"Active\";s:9:\"job_title\";s:10:\"HR Officer\";}i:2;a:6:{s:2:\"id\";i:3;s:11:\"employee_id\";s:7:\"EMP-003\";s:9:\"full_name\";s:12:\"Jose Ramirez\";s:5:\"email\";s:24:\"jose.ramirez@company.com\";s:17:\"employment_status\";s:6:\"Active\";s:9:\"job_title\";s:21:\"IT Support Specialist\";}i:3;a:6:{s:2:\"id\";i:4;s:11:\"employee_id\";s:7:\"EMP-004\";s:9:\"full_name\";s:11:\"Angela Cruz\";s:5:\"email\";s:23:\"angela.cruz@company.com\";s:17:\"employment_status\";s:6:\"Active\";s:9:\"job_title\";s:13:\"Data Engineer\";}}', 1765986341);

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_19_145529_add_two_factor_columns_to_users_table', 1),
(5, '2025_09_19_145723_create_personal_access_tokens_table', 1),
(6, '2025_09_23_035036_create_competency_frameworks_table', 2),
(7, '2025_09_24_144657_create_competencies_table', 2),
(8, '2025_09_26_070711_create_role_mappings_table', 2),
(9, '2025_10_03_160715_create_quizzes_table', 3),
(10, '2025_10_03_160910_create_quiz_questions_table', 4),
(11, '2025_10_06_144007_add_role_to_users_table', 5),
(12, '2025_10_06_182353_add_employee_id_to_users_table', 6),
(13, '2025_10_08_140957_update_assessment_results_status_enum', 7),
(14, '2025_10_09_000100_create_audit_logs_table', 8),
(15, '2025_10_15_000001_create_activity_logs_table', 9),
(16, '2025_10_20_000001_add_competency_id_to_competencies_table', 10),
(17, '2025_10_26_000001_add_category_to_competencies_table', 11),
(19, '2025_11_07_094809_create_course_requests_table', 12),
(20, '2025_11_10_180509_add_manual_scoring_columns_to_user_answers_table', 13),
(21, '2025_11_18_015130_create_skill_gap_management_tables', 14),
(22, '2025_11_20_112231_create_competency_assignments_table', 15),
(23, '2025_11_26_000000_create_assigned_competencies_table', 16),
(24, '2025_11_30_013842_add_retried_status_to_assessment_results', 17),
(25, '2025_12_06_000000_add_promoted_at_to_promotions', 18),
(26, '2025_12_27_222525_add_employment_status_to_users_table', 19);

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
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('NGEm3NK4KG8aLKVpo7BazQoonDJtVhmEJhtlyWTO', 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiMEdxOGZHaVNnWnBadUdBcFZoclQ3cXdCc3ZtUkdpV0hmWUZETFFnZyI7czozOiJ1cmwiO2E6MDp7fXM6NjoiX2ZsYXNoIjthOjI6e3M6MzoibmV3IjthOjA6e31zOjM6Im9sZCI7YTowOnt9fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTI7fQ==', 1767020834);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','hr_manager','employee') NOT NULL DEFAULT 'employee',
  `employment_status` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `employee_id`, `name`, `email`, `email_verified_at`, `role`, `employment_status`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Test User', 'test@example.com', '2025-09-21 22:50:27', 'employee', NULL, '$2y$12$msl9dZXc5kTTdcDF1i7im.1s/8O8i/7LmNJX2nuVU.Z07SSdRmaai', 'eyJpdiI6IlZGdk1NT1I2Y0c0UHpTVDRHb1BhSmc9PSIsInZhbHVlIjoiejdhcGRFaWVJY1JCekEvUnpxOHEweUlCV2t0VzZuY0NZeU8rbUNINUhrZz0iLCJtYWMiOiI1M2Y5OTM0MGNjOTYwNzk2OGY0NWNkNzc4YzliMDllMzQwOTgxNzhjNDdkZGEwM2E0YjQwM2Q5MjEwMTBkMDdiIiwidGFnIjoiIn0=', 'eyJpdiI6InBLYlFCY0NNUDVSekNBdk1IQ2x5bUE9PSIsInZhbHVlIjoiRUg1d0dVNkNTTnJlNzBmdFlrUmhoUFcxVHJRMTVOZGdtdlBQL0JrYUhGeHdQVkE0ZHpyUkk0VmJJWk5RTWpYZXVYdWFxY3ZTZmxmNGdZRElNc1lhTUhzUlIwY1JiaklFbXptckt2Wmx2dTZnSE5ydW9XQ25GTXd2Q1g4SjVhVUtmVDExYjRxRzZzaXNJeWlKVTNQWXl3MWkxV2owc2N2c2VqdjdCeFZ3dUJsdDBOc25OdlprUk5WWm9iVk4xN2l6TkJlMit2Rlcwd1NFQW5mRlFRRE5vS0phaEtIdGttREs4Z2RtbzNNL0NONjl4cWd0NDMrZXZEckRLaFNCRStiOTJmbFJNUkVqOUZJR3dOc0NOZ2lkQWc9PSIsIm1hYyI6IjA4YmY5YzUwZDk5MTdjODZiZTQ2Y2JhMmYzYWJiNTZkYWM1OGU1NTcxNjA0ZDhmYjgzMWRjNjI3OWZiOGRiMWEiLCJ0YWciOiIifQ==', '2025-10-05 06:44:11', 'BXPnDHdl6F1CtX75Ncn13yOeaWVcWSxcXHevJkwvF1IAh3GU5TdLYs2sRmLk', NULL, NULL, '2025-09-21 22:50:28', '2025-10-05 06:44:11'),
(3, NULL, 'Admin User', 'flappyace3@gmail.com', NULL, 'admin', NULL, '$2y$12$Ce4FMswnyOSoMJFz/pBhoO0AI.ePfQ67P4SqhKzDiy5XIf396lA9q', '', '', '2025-10-04 05:49:01', NULL, NULL, NULL, '2025-10-03 23:02:54', '2025-10-04 05:49:01'),
(6, 'EMP-003', 'Jose Ramirez', 'jose.ramirez@company.com', NULL, 'employee', 'Active', '$2y$12$3VvyKnzYtgoNmt.t2rVgLeLeuvc2/0Uq04uoq.5D1R9YRsuaA0KC.', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 10:31:08', '2025-12-29 15:07:11'),
(7, 'EMP-004', 'Angela Cruz', 'angela.cruz@company.com', NULL, 'employee', 'Active', '$2y$12$aW/5Ix.h4b8LdppsSALL0ONpTwcWhjE4hPJjXmbovzGU5cc63pssO', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 10:31:09', '2025-12-27 15:06:17'),
(11, NULL, 'Ivan Bullo', 'bullo@javescooperative.com', NULL, 'admin', NULL, '$2y$12$k3bOSfUZHgCkBeMJNdWN1eC9kE/ToK5Cxk2BB2CoN9tq.OtuK9kCi', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-18 08:50:50', '2025-10-18 08:50:50'),
(12, NULL, 'Chester Dapatnapo', 'chester@gmail.com', NULL, 'admin', NULL, '$2y$12$GH0qr2oAq2o8yEK3Gxl8Y.vOUCUN1DViQL8ihxF2EBixpm1zvXn/a', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:46:33', '2025-12-11 14:46:33'),
(17, 'EMP-001', 'Juan Dela Cruz', 'juan.delacruz@company.com', NULL, 'employee', 'Terminated', '$2y$12$SWFyKH5Pwwrw50lPfthisOPtyApIo5h/0q1Obs8dnJ/h72WRnZI/6', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-29 15:07:10', '2025-12-29 15:07:10'),
(18, 'EMP-002', 'Maria Santos', 'maria.santos@company.com', NULL, 'employee', 'Active', '$2y$12$/rmEbrnM4giThPWZMvwICe9IENNyn30EydN4vWNvtzSdynYwXmRJu', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-29 15:07:11', '2025-12-29 15:07:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_employee_id_index` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=319;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
