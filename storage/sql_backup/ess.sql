-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2025 at 05:06 PM
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
-- Database: `ess`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessment_results`
--

CREATE TABLE `assessment_results` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `assignment_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `quiz_id` bigint(20) UNSIGNED NOT NULL,
  `score` int(11) NOT NULL DEFAULT 0,
  `total_questions` int(11) NOT NULL DEFAULT 0,
  `correct_answers` int(11) NOT NULL DEFAULT 0,
  `attempt_number` int(11) NOT NULL DEFAULT 1,
  `status` enum('in_progress','completed','failed','passed','retried') NOT NULL DEFAULT 'in_progress',
  `evaluation_status` enum('pending','passed','failed') NOT NULL DEFAULT 'pending',
  `evaluated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `evaluated_at` timestamp NULL DEFAULT NULL,
  `evaluation_notes` text DEFAULT NULL,
  `evaluation_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`evaluation_data`)),
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assessment_results`
--

INSERT INTO `assessment_results` (`id`, `assignment_id`, `employee_id`, `quiz_id`, `score`, `total_questions`, `correct_answers`, `attempt_number`, `status`, `evaluation_status`, `evaluated_by`, `evaluated_at`, `evaluation_notes`, `evaluation_data`, `started_at`, `completed_at`, `created_at`, `updated_at`) VALUES
(15, 22, 'EMP-001', 68, 80, 10, 0, 1, 'passed', 'passed', 3, '2025-11-29 08:12:10', NULL, '{\"competency_1\":\"proficient\",\"competency_2\":\"proficient\",\"competency_3\":\"proficient\",\"competency_4\":\"inconsistent\",\"competency_5\":\"proficient\",\"average_score\":2.8,\"strengths\":\"geggege\",\"areas_for_improvement\":\"hegegegeg\",\"recommendations\":null,\"evaluation_date\":\"2025-11-29 16:12:10\"}', '2025-11-29 07:37:41', '2025-11-29 07:37:41', '2025-11-29 07:37:41', '2025-11-29 08:12:10'),
(18, 25, 'EMP-001', 69, 80, 10, 0, 1, 'passed', 'passed', 3, '2025-11-30 15:33:24', NULL, '{\"competency_1\":\"exceptional\",\"competency_2\":\"exceptional\",\"competency_3\":\"exceptional\",\"competency_4\":\"highly_effective\",\"competency_5\":\"exceptional\",\"average_score\":4.8,\"strengths\":\"Excellent performance across all competencies\",\"areas_for_improvement\":\"\",\"recommendations\":\"Ready for advancement\",\"evaluation_date\":\"2025-12-03 23:57:20\"}', '2025-11-29 16:40:29', '2025-11-29 16:40:29', '2025-11-29 16:40:29', '2025-12-03 15:57:20'),
(19, 26, 'EMP-001', 70, 0, 10, 0, 1, 'retried', 'failed', 3, '2025-11-29 16:42:26', NULL, NULL, '2025-11-29 16:40:55', '2025-11-29 16:40:55', '2025-11-29 16:40:55', '2025-11-29 17:41:43'),
(20, 26, 'EMP-001', 70, 80, 10, 0, 2, 'passed', 'passed', 3, '2025-11-30 15:07:59', NULL, '{\"competency_1\":\"exceptional\",\"competency_2\":\"exceptional\",\"competency_3\":\"exceptional\",\"competency_4\":\"highly_effective\",\"competency_5\":\"exceptional\",\"average_score\":4.8,\"strengths\":\"Excellent performance across all competencies\",\"areas_for_improvement\":\"\",\"recommendations\":\"Ready for advancement\",\"evaluation_date\":\"2025-12-03 23:57:20\"}', '2025-11-30 15:06:29', '2025-11-30 15:06:29', '2025-11-30 15:06:29', '2025-12-03 15:57:20'),
(21, 29, 'EMP-002', 78, 70, 10, 0, 1, 'passed', 'passed', 3, '2025-12-06 05:35:25', NULL, '{\"competency_1\":\"proficient\",\"competency_2\":\"proficient\",\"competency_3\":\"proficient\",\"competency_4\":\"proficient\",\"competency_5\":\"highly_effective\",\"average_score\":3.2,\"strengths\":\"ganbare\",\"areas_for_improvement\":\"gambare\",\"recommendations\":null,\"evaluation_date\":\"2025-12-06 13:35:25\"}', '2025-12-06 04:01:12', '2025-12-06 04:01:12', '2025-12-06 04:01:12', '2025-12-06 05:35:25'),
(22, 30, 'EMP-002', 79, 80, 10, 2, 1, 'passed', 'passed', 3, '2025-12-06 05:35:25', NULL, '{\"competency_1\":\"proficient\",\"competency_2\":\"proficient\",\"competency_3\":\"proficient\",\"competency_4\":\"proficient\",\"competency_5\":\"highly_effective\",\"average_score\":3.2,\"strengths\":\"ganbare\",\"areas_for_improvement\":\"gambare\",\"recommendations\":null,\"evaluation_date\":\"2025-12-06 13:35:25\"}', '2025-12-06 04:06:18', '2025-12-06 04:06:18', '2025-12-06 04:06:18', '2025-12-06 05:35:25'),
(23, 31, 'EMP-002', 77, 70, 10, 0, 1, 'passed', 'passed', 3, '2025-12-06 06:41:11', NULL, '{\"competency_1\":\"proficient\",\"competency_2\":\"proficient\",\"competency_3\":\"proficient\",\"competency_4\":\"highly_effective\",\"competency_5\":\"highly_effective\",\"average_score\":3.4,\"strengths\":\"nnuice\",\"areas_for_improvement\":\"nouice\",\"recommendations\":null,\"evaluation_date\":\"2025-12-06 14:41:11\"}', '2025-12-06 06:28:10', '2025-12-06 06:28:10', '2025-12-06 06:28:10', '2025-12-06 06:41:11'),
(24, 32, 'EMP-002', 78, 80, 10, 0, 1, 'passed', 'passed', 3, '2025-12-06 06:41:11', NULL, '{\"competency_1\":\"proficient\",\"competency_2\":\"proficient\",\"competency_3\":\"proficient\",\"competency_4\":\"highly_effective\",\"competency_5\":\"highly_effective\",\"average_score\":3.4,\"strengths\":\"nnuice\",\"areas_for_improvement\":\"nouice\",\"recommendations\":null,\"evaluation_date\":\"2025-12-06 14:41:11\"}', '2025-12-06 06:28:35', '2025-12-06 06:28:35', '2025-12-06 06:28:35', '2025-12-06 06:41:11'),
(25, 33, 'EMP-004', 56, 80, 10, 0, 1, 'passed', 'passed', 3, '2025-12-14 15:51:23', NULL, '{\"competency_1\":\"exceptional\",\"competency_2\":\"exceptional\",\"competency_3\":\"highly_effective\",\"competency_4\":\"exceptional\",\"competency_5\":\"inconsistent\",\"average_score\":4.2,\"strengths\":\"erewsfsd\",\"areas_for_improvement\":\"fsdfsdfds\",\"recommendations\":null,\"evaluation_date\":\"2025-12-14 23:51:23\"}', '2025-12-14 15:41:40', '2025-12-14 15:41:40', '2025-12-14 15:41:40', '2025-12-14 15:51:23');

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
-- Table structure for table `leaves`
--

CREATE TABLE `leaves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `employee_email` varchar(255) NOT NULL,
  `leave_type` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `remarks` text DEFAULT NULL,
  `approved_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leaves`
--

INSERT INTO `leaves` (`id`, `employee_id`, `employee_name`, `employee_email`, `leave_type`, `start_date`, `end_date`, `reason`, `status`, `remarks`, `approved_by`, `created_at`, `updated_at`) VALUES
(1, 'EMP-001', 'Juan Dela Cruz', 'juan.delacruz@company.com', 'Personal', '2025-12-06', '2025-12-27', 'dsfdssds', 'pending', 'Awaiting approval', NULL, '2025-12-06 14:24:30', '2025-12-06 14:24:30'),
(2, 'EMP-001', 'Juan Dela Cruz', 'juan.delacruz@company.com', 'Emergency', '2025-12-06', '2026-01-02', 'dfgdfgdfgdf', 'pending', 'Awaiting approval', NULL, '2025-12-06 14:25:00', '2025-12-06 14:25:00');

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
(1, '2025_10_07_163805_create_assessment_results_table', 1),
(2, '2025_10_07_163849_create_user_answers_table', 2),
(3, '0001_01_01_000000_create_users_table', 3),
(4, '0001_01_01_000001_create_cache_table', 4),
(5, '0001_01_01_000002_create_jobs_table', 5),
(6, '2025_09_19_145529_add_two_factor_columns_to_users_table', 6),
(7, '2025_09_19_145723_create_personal_access_tokens_table', 7),
(8, '2025_10_07_191009_add_evaluation_columns_to_assessment_results_table', 8),
(9, '2025_10_08_054132_add_evaluation_data_to_assessment_results_table', 9);

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
-- Table structure for table `payroll_registrations`
--

CREATE TABLE `payroll_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) DEFAULT NULL,
  `employee_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `payment_method` enum('Bank Transfer','GCash','Maya') NOT NULL DEFAULT 'Bank Transfer',
  `bank_name` varchar(255) NOT NULL,
  `bank_branch` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `account_type` varchar(255) DEFAULT NULL,
  `id_type` varchar(255) DEFAULT NULL,
  `id_number` varchar(255) DEFAULT NULL,
  `proof_of_account_path` varchar(255) DEFAULT NULL,
  `valid_id_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `remarks` text DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_answers`
--

CREATE TABLE `user_answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `result_id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `user_answer` text NOT NULL,
  `correct_answer` text NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `points_earned` int(11) NOT NULL DEFAULT 0,
  `points_possible` int(11) NOT NULL DEFAULT 1,
  `manual_score` decimal(5,2) DEFAULT NULL,
  `evaluator_comments` text DEFAULT NULL,
  `manually_graded` tinyint(1) NOT NULL DEFAULT 0,
  `graded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `graded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_answers`
--

INSERT INTO `user_answers` (`id`, `result_id`, `question_id`, `user_answer`, `correct_answer`, `is_correct`, `points_earned`, `points_possible`, `manual_score`, `evaluator_comments`, `manually_graded`, `graded_by`, `graded_at`, `created_at`, `updated_at`) VALUES
(78, 15, 262, 'Hello', 'Project charter', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-29 08:11:04', '2025-11-29 07:37:41', '2025-11-29 08:11:04'),
(79, 15, 263, 'My', 'Gantt chart', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-29 08:11:04', '2025-11-29 07:37:41', '2025-11-29 08:11:04'),
(80, 15, 264, 'Name', 'Critical path', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-29 08:11:04', '2025-11-29 07:37:41', '2025-11-29 08:11:04'),
(81, 15, 265, 'Is', 'WBS', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-29 08:11:04', '2025-11-29 07:37:41', '2025-11-29 08:11:04'),
(82, 15, 266, 'Arigato', 'Triple constraint', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-29 08:11:04', '2025-11-29 07:37:41', '2025-11-29 08:11:04'),
(83, 15, 267, 'Owaimo', 'Milestone', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-29 08:11:04', '2025-11-29 07:37:41', '2025-11-29 08:11:04'),
(84, 15, 268, 'shine', 'Finish-to-start', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-29 08:11:04', '2025-11-29 07:37:41', '2025-11-29 08:11:04'),
(85, 15, 269, 'gege', 'Risk identification', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-29 08:11:04', '2025-11-29 07:37:41', '2025-11-29 08:11:04'),
(86, 15, 270, 'uouou', 'Change log', 0, 0, 5, 0.00, NULL, 1, 3, '2025-11-29 08:11:04', '2025-11-29 07:37:41', '2025-11-29 08:11:04'),
(87, 15, 271, 'more', 'Change request', 0, 0, 5, 0.00, NULL, 1, 3, '2025-11-29 08:11:04', '2025-11-29 07:37:41', '2025-11-29 08:11:04'),
(108, 18, 272, 'asd', 'Sprint', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-30 15:33:24', '2025-11-29 16:40:29', '2025-11-30 15:33:24'),
(109, 18, 273, 'asdsa', 'Product Owner', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-30 15:33:24', '2025-11-29 16:40:29', '2025-11-30 15:33:24'),
(110, 18, 274, 'asdsa', 'Product backlog', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-30 15:33:24', '2025-11-29 16:40:29', '2025-11-30 15:33:24'),
(111, 18, 275, 'sadsadsad', 'Daily standup', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-30 15:33:24', '2025-11-29 16:40:29', '2025-11-30 15:33:24'),
(112, 18, 276, 'sadsadsad', 'Scrum Master', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-30 15:33:24', '2025-11-29 16:40:29', '2025-11-30 15:33:24'),
(113, 18, 277, 'asqwerqwer', 'Sprint review', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-30 15:33:24', '2025-11-29 16:40:29', '2025-11-30 15:33:24'),
(114, 18, 278, 'asdasdsad', 'Retrospective', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-30 15:33:24', '2025-11-29 16:40:29', '2025-11-30 15:33:24'),
(115, 18, 279, 'erewwr', 'User story', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-30 15:33:24', '2025-11-29 16:40:29', '2025-11-30 15:33:24'),
(116, 18, 280, 'dsfsdds', 'Story points', 0, 0, 5, 0.00, NULL, 1, 3, '2025-11-30 15:33:24', '2025-11-29 16:40:29', '2025-11-30 15:33:24'),
(117, 18, 281, 'erterrete', 'Documentation', 0, 0, 5, 0.00, NULL, 1, 3, '2025-11-30 15:33:24', '2025-11-29 16:40:29', '2025-11-30 15:33:24'),
(118, 19, 282, 'ytryry', 'Risk', 0, 0, 5, NULL, NULL, 0, NULL, NULL, '2025-11-29 16:40:55', '2025-11-29 16:40:55'),
(119, 19, 283, 'tryrtry', 'Mitigation', 0, 0, 5, NULL, NULL, 0, NULL, NULL, '2025-11-29 16:40:55', '2025-11-29 16:40:55'),
(120, 19, 284, 'rtytrytry', 'Acceptance', 0, 0, 5, NULL, NULL, 0, NULL, NULL, '2025-11-29 16:40:55', '2025-11-29 16:40:55'),
(121, 19, 285, 'trytryr', 'Risk matrix', 0, 0, 5, NULL, NULL, 0, NULL, NULL, '2025-11-29 16:40:55', '2025-11-29 16:40:55'),
(122, 19, 286, 'trytryrtyr', 'Opportunity', 0, 0, 5, NULL, NULL, 0, NULL, NULL, '2025-11-29 16:40:55', '2025-11-29 16:40:55'),
(123, 19, 287, 'trytrtry', 'Risk register', 0, 0, 5, NULL, NULL, 0, NULL, NULL, '2025-11-29 16:40:55', '2025-11-29 16:40:55'),
(124, 19, 288, 'trytrr', 'Transfer', 0, 0, 5, NULL, NULL, 0, NULL, NULL, '2025-11-29 16:40:55', '2025-11-29 16:40:55'),
(125, 19, 289, 'trytrytry', 'Residual risk', 0, 0, 5, NULL, NULL, 0, NULL, NULL, '2025-11-29 16:40:55', '2025-11-29 16:40:55'),
(126, 19, 290, 'tryrtytr', 'Secondary risk', 0, 0, 5, NULL, NULL, 0, NULL, NULL, '2025-11-29 16:40:55', '2025-11-29 16:40:55'),
(127, 19, 291, 'rtytrytr', 'Avoidance', 0, 0, 5, NULL, NULL, 0, NULL, NULL, '2025-11-29 16:40:55', '2025-11-29 16:40:55'),
(128, 20, 282, 'dunno', 'Risk', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-30 15:07:59', '2025-11-30 15:06:29', '2025-11-30 15:07:59'),
(129, 20, 283, 'maybe', 'Mitigation', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-30 15:07:59', '2025-11-30 15:06:29', '2025-11-30 15:07:59'),
(130, 20, 284, 'this', 'Acceptance', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-30 15:07:59', '2025-11-30 15:06:29', '2025-11-30 15:07:59'),
(131, 20, 285, 'time', 'Risk matrix', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-30 15:07:59', '2025-11-30 15:06:29', '2025-11-30 15:07:59'),
(132, 20, 286, 'happy', 'Opportunity', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-30 15:07:59', '2025-11-30 15:06:29', '2025-11-30 15:07:59'),
(133, 20, 287, 'to serve', 'Risk register', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-30 15:07:59', '2025-11-30 15:06:29', '2025-11-30 15:07:59'),
(134, 20, 288, 'adn long', 'Transfer', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-30 15:07:59', '2025-11-30 15:06:29', '2025-11-30 15:07:59'),
(135, 20, 289, 'live aplha', 'Residual risk', 1, 0, 5, 5.00, NULL, 1, 3, '2025-11-30 15:07:59', '2025-11-30 15:06:29', '2025-11-30 15:07:59'),
(136, 20, 290, 'johnson miya', 'Secondary risk', 0, 0, 5, 0.00, NULL, 1, 3, '2025-11-30 15:07:59', '2025-11-30 15:06:29', '2025-11-30 15:07:59'),
(137, 20, 291, 'done betch', 'Avoidance', 0, 0, 5, 0.00, NULL, 1, 3, '2025-11-30 15:07:59', '2025-11-30 15:06:29', '2025-11-30 15:07:59'),
(138, 21, 362, 'i dunno', 'Human Resources', 1, 0, 4, 4.00, NULL, 1, 3, '2025-12-06 04:23:24', '2025-12-06 04:01:12', '2025-12-06 04:23:24'),
(139, 21, 363, 'i dunno', 'Onboarding', 1, 0, 4, 4.00, NULL, 1, 3, '2025-12-06 04:23:24', '2025-12-06 04:01:12', '2025-12-06 04:23:24'),
(140, 21, 364, 'sleeping', 'Job description', 1, 0, 4, 4.00, NULL, 1, 3, '2025-12-06 04:23:24', '2025-12-06 04:01:12', '2025-12-06 04:23:24'),
(141, 21, 365, 'supervisor', 'Manager', 0, 0, 4, 0.00, NULL, 1, 3, '2025-12-06 04:23:24', '2025-12-06 04:01:12', '2025-12-06 04:23:24'),
(142, 21, 366, 'time management', 'Time tracking', 0, 0, 4, 0.00, NULL, 1, 3, '2025-12-06 04:23:24', '2025-12-06 04:01:12', '2025-12-06 04:23:24'),
(143, 21, 367, 'request leave', 'Leave request', 1, 0, 4, 4.00, NULL, 1, 3, '2025-12-06 04:23:24', '2025-12-06 04:01:12', '2025-12-06 04:23:24'),
(144, 21, 368, 'terms and condition', 'Employee handbook', 1, 0, 4, 4.00, NULL, 1, 3, '2025-12-06 04:23:24', '2025-12-06 04:01:12', '2025-12-06 04:23:24'),
(145, 21, 369, 'performance management', 'Performance review', 0, 0, 4, 0.00, NULL, 1, 3, '2025-12-06 04:23:24', '2025-12-06 04:01:12', '2025-12-06 04:23:24'),
(146, 21, 370, 'viber', 'Internal communications', 1, 0, 4, 4.00, NULL, 1, 3, '2025-12-06 04:23:24', '2025-12-06 04:01:12', '2025-12-06 04:23:24'),
(147, 21, 371, 'dunno', 'Mentor', 1, 0, 4, 4.00, NULL, 1, 3, '2025-12-06 04:23:24', '2025-12-06 04:01:12', '2025-12-06 04:23:24'),
(148, 22, 372, 'viber', 'Slack', 1, 0, 5, 5.00, NULL, 1, 3, '2025-12-06 04:22:50', '2025-12-06 04:06:18', '2025-12-06 04:22:50'),
(149, 22, 373, 'zoom', 'Zoom', 1, 5, 5, 5.00, NULL, 1, 3, '2025-12-06 04:22:50', '2025-12-06 04:06:18', '2025-12-06 04:22:50'),
(150, 22, 374, 'one cloud', 'Google Drive', 1, 0, 5, 5.00, NULL, 1, 3, '2025-12-06 04:22:50', '2025-12-06 04:06:18', '2025-12-06 04:22:50'),
(151, 22, 375, 'jira', 'Jira', 1, 5, 5, 5.00, NULL, 1, 3, '2025-12-06 04:22:50', '2025-12-06 04:06:18', '2025-12-06 04:22:50'),
(152, 22, 376, 'login system', 'Authentication', 0, 0, 5, 0.00, NULL, 1, 3, '2025-12-06 04:22:50', '2025-12-06 04:06:18', '2025-12-06 04:22:50'),
(153, 22, 377, 'login', 'Two-factor authentication', 1, 0, 5, 5.00, NULL, 1, 3, '2025-12-06 04:22:50', '2025-12-06 04:06:18', '2025-12-06 04:22:50'),
(154, 22, 378, 'mis', 'Intranet', 1, 0, 5, 5.00, NULL, 1, 3, '2025-12-06 04:22:50', '2025-12-06 04:06:18', '2025-12-06 04:22:50'),
(155, 22, 379, 'dunno', 'CRM', 0, 0, 5, 0.00, NULL, 1, 3, '2025-12-06 04:22:50', '2025-12-06 04:06:18', '2025-12-06 04:22:50'),
(156, 22, 380, 'dunno either', 'Google Docs', 1, 0, 5, 5.00, NULL, 1, 3, '2025-12-06 04:22:50', '2025-12-06 04:06:18', '2025-12-06 04:22:50'),
(157, 22, 381, 'dunno', 'Help desk', 1, 0, 5, 5.00, NULL, 1, 3, '2025-12-06 04:22:50', '2025-12-06 04:06:18', '2025-12-06 04:22:50'),
(158, 23, 352, 'duinno', 'Mission', 1, 0, 5, 5.00, NULL, 1, 3, '2025-12-06 06:40:31', '2025-12-06 06:28:10', '2025-12-06 06:40:31'),
(159, 23, 353, 'fsdfgsdafa', 'Vision', 1, 0, 5, 5.00, NULL, 1, 3, '2025-12-06 06:40:31', '2025-12-06 06:28:10', '2025-12-06 06:40:31'),
(160, 23, 354, 'sdfsdafasdf', 'Core values', 1, 0, 5, 5.00, NULL, 1, 3, '2025-12-06 06:40:31', '2025-12-06 06:28:10', '2025-12-06 06:40:31'),
(161, 23, 355, 'sfsdafsdafsd', 'Culture', 1, 0, 5, 5.00, NULL, 1, 3, '2025-12-06 06:40:31', '2025-12-06 06:28:10', '2025-12-06 06:40:31'),
(162, 23, 356, 'sdfsdafdasfa', 'Equity', 1, 0, 5, 5.00, NULL, 1, 3, '2025-12-06 06:40:31', '2025-12-06 06:28:10', '2025-12-06 06:40:31'),
(163, 23, 357, 'sdafasdfsa', 'Integrity', 1, 0, 5, 5.00, NULL, 1, 3, '2025-12-06 06:40:31', '2025-12-06 06:28:10', '2025-12-06 06:40:31'),
(164, 23, 358, 'dsfsdafdasfsdf', 'Inclusion', 0, 0, 5, 0.00, NULL, 1, 3, '2025-12-06 06:40:31', '2025-12-06 06:28:10', '2025-12-06 06:40:31'),
(165, 23, 359, 'sdfsfsdf', 'Excellence', 1, 0, 5, 5.00, NULL, 1, 3, '2025-12-06 06:40:31', '2025-12-06 06:28:10', '2025-12-06 06:40:31'),
(166, 23, 360, 'sfsdfadsfasdfsda', 'Collaboration', 0, 0, 5, 0.00, NULL, 1, 3, '2025-12-06 06:40:31', '2025-12-06 06:28:10', '2025-12-06 06:40:31'),
(167, 23, 361, 'sdfsdghfghfg', 'Innovation', 0, 0, 5, 0.00, NULL, 1, 3, '2025-12-06 06:40:31', '2025-12-06 06:28:10', '2025-12-06 06:40:31'),
(168, 24, 362, 'fghfghfg', 'Human Resources', 1, 0, 4, 4.00, NULL, 1, 3, '2025-12-06 06:40:00', '2025-12-06 06:28:35', '2025-12-06 06:40:00'),
(169, 24, 363, 'gfhfghgfhgf', 'Onboarding', 1, 0, 4, 4.00, NULL, 1, 3, '2025-12-06 06:40:00', '2025-12-06 06:28:35', '2025-12-06 06:40:00'),
(170, 24, 364, 'fghfghfghgf', 'Job description', 1, 0, 4, 4.00, NULL, 1, 3, '2025-12-06 06:40:00', '2025-12-06 06:28:35', '2025-12-06 06:40:00'),
(171, 24, 365, 'fghfghfghg', 'Manager', 1, 0, 4, 4.00, NULL, 1, 3, '2025-12-06 06:40:00', '2025-12-06 06:28:35', '2025-12-06 06:40:00'),
(172, 24, 366, 'gfhgfhfg', 'Time tracking', 1, 0, 4, 4.00, NULL, 1, 3, '2025-12-06 06:40:00', '2025-12-06 06:28:35', '2025-12-06 06:40:00'),
(173, 24, 367, 'hfghfgfgh', 'Leave request', 1, 0, 4, 4.00, NULL, 1, 3, '2025-12-06 06:40:00', '2025-12-06 06:28:35', '2025-12-06 06:40:00'),
(174, 24, 368, 'hfghfghfgh', 'Employee handbook', 1, 0, 4, 4.00, NULL, 1, 3, '2025-12-06 06:40:00', '2025-12-06 06:28:35', '2025-12-06 06:40:00'),
(175, 24, 369, 'hfghfghgf', 'Performance review', 1, 0, 4, 4.00, NULL, 1, 3, '2025-12-06 06:40:00', '2025-12-06 06:28:35', '2025-12-06 06:40:00'),
(176, 24, 370, 'fghfghfghfgh', 'Internal communications', 0, 0, 4, 0.00, NULL, 1, 3, '2025-12-06 06:40:00', '2025-12-06 06:28:35', '2025-12-06 06:40:00'),
(177, 24, 371, 'fghfghfghfg', 'Mentor', 0, 0, 4, 0.00, NULL, 1, 3, '2025-12-06 06:40:00', '2025-12-06 06:28:35', '2025-12-06 06:40:00'),
(178, 25, 142, 'sdfds', 'Salutation', 1, 0, 5, 5.00, NULL, 1, 3, '2025-12-14 15:50:17', '2025-12-14 15:41:40', '2025-12-14 15:50:17'),
(179, 25, 143, 'sdfdsfd', 'Concise', 1, 0, 5, 5.00, NULL, 1, 3, '2025-12-14 15:50:17', '2025-12-14 15:41:40', '2025-12-14 15:50:17'),
(180, 25, 144, 'fsdfdsfss', 'Formal', 1, 0, 5, 5.00, NULL, 1, 3, '2025-12-14 15:50:17', '2025-12-14 15:41:40', '2025-12-14 15:50:17'),
(181, 25, 145, 'sdfdsfsd', 'Letterhead', 1, 0, 5, 5.00, NULL, 1, 3, '2025-12-14 15:50:17', '2025-12-14 15:41:40', '2025-12-14 15:50:17'),
(182, 25, 146, 'sdfsdfsdf', 'Regards', 1, 0, 5, 5.00, NULL, 1, 3, '2025-12-14 15:50:18', '2025-12-14 15:41:40', '2025-12-14 15:50:18'),
(183, 25, 147, 'sdfsdfdsf', 'Tense inconsistency', 1, 0, 5, 5.00, NULL, 1, 3, '2025-12-14 15:50:18', '2025-12-14 15:41:40', '2025-12-14 15:50:18'),
(184, 25, 148, 'sdfdsfds', 'Executive summary', 1, 0, 5, 5.00, NULL, 1, 3, '2025-12-14 15:50:18', '2025-12-14 15:41:40', '2025-12-14 15:50:18'),
(185, 25, 149, 'sdfdsfsd', 'Persuasive', 0, 0, 5, 0.00, NULL, 1, 3, '2025-12-14 15:50:18', '2025-12-14 15:41:40', '2025-12-14 15:50:18'),
(186, 25, 150, 'sdfsdfds', 'Proofreading', 1, 0, 5, 5.00, NULL, 1, 3, '2025-12-14 15:50:18', '2025-12-14 15:41:40', '2025-12-14 15:50:18'),
(187, 25, 151, 'sdfsdf', 'Clarity', 0, 0, 5, 0.00, NULL, 1, 3, '2025-12-14 15:50:18', '2025-12-14 15:41:40', '2025-12-14 15:50:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessment_results`
--
ALTER TABLE `assessment_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assessment_results_employee_id_quiz_id_index` (`employee_id`,`quiz_id`),
  ADD KEY `assessment_results_assignment_id_index` (`assignment_id`),
  ADD KEY `assessment_results_evaluated_by_foreign` (`evaluated_by`);

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
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leaves_employee_id_index` (`employee_id`),
  ADD KEY `leaves_employee_email_index` (`employee_email`),
  ADD KEY `leaves_status_index` (`status`),
  ADD KEY `leaves_leave_type_index` (`leave_type`),
  ADD KEY `leaves_start_date_end_date_index` (`start_date`,`end_date`);

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
-- Indexes for table `payroll_registrations`
--
ALTER TABLE `payroll_registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payroll_registrations_user_id_index` (`user_id`),
  ADD KEY `payroll_registrations_employee_id_index` (`employee_id`),
  ADD KEY `payroll_registrations_status_index` (`status`),
  ADD KEY `payroll_registrations_email_index` (`email`);

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
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_answers`
--
ALTER TABLE `user_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_answers_result_id_question_id_index` (`result_id`,`question_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessment_results`
--
ALTER TABLE `assessment_results`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

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
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `payroll_registrations`
--
ALTER TABLE `payroll_registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_answers`
--
ALTER TABLE `user_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assessment_results`
--
ALTER TABLE `assessment_results`
  ADD CONSTRAINT `assessment_results_evaluated_by_foreign` FOREIGN KEY (`evaluated_by`) REFERENCES `opvenio_hr2`.`users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_answers`
--
ALTER TABLE `user_answers`
  ADD CONSTRAINT `user_answers_result_id_foreign` FOREIGN KEY (`result_id`) REFERENCES `assessment_results` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
