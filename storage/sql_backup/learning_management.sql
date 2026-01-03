-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2025 at 03:12 PM
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
-- Database: `learning_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessment_assignments`
--

CREATE TABLE `assessment_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `assessment_category_id` bigint(20) UNSIGNED NOT NULL,
  `quiz_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `employee_name` varchar(255) DEFAULT NULL,
  `employee_email` varchar(255) DEFAULT NULL,
  `duration` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `due_date` datetime NOT NULL,
  `max_attempts` varchar(255) NOT NULL DEFAULT '3',
  `status` enum('pending','in_progress','completed','overdue','cancelled') NOT NULL DEFAULT 'pending',
  `attempts_used` int(11) NOT NULL DEFAULT 0,
  `score` decimal(5,2) DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `assignment_metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`assignment_metadata`)),
  `assigned_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assessment_assignments`
--

INSERT INTO `assessment_assignments` (`id`, `assessment_category_id`, `quiz_id`, `employee_id`, `employee_name`, `employee_email`, `duration`, `start_date`, `due_date`, `max_attempts`, `status`, `attempts_used`, `score`, `started_at`, `completed_at`, `notes`, `assignment_metadata`, `assigned_by`, `created_at`, `updated_at`) VALUES
(22, 12, 68, '4', 'Juan Dela Cruz', 'juan.delacruz@company.com', 30, '2025-11-29 15:04:48', '2025-12-29 23:59:59', '3', 'completed', 1, 56.00, '2025-11-29 15:30:12', '2025-11-29 15:37:41', 'gambare (Assigned from grant request approval)', NULL, 3, '2025-11-29 07:04:48', '2025-11-29 08:12:10'),
(25, 12, 69, '1', 'Juan Dela Cruz', 'juan.delacruz@company.com', 45, '2025-11-30 00:40:00', '2025-12-07 00:38:00', '3', 'completed', 1, 80.00, '2025-11-30 00:40:05', '2025-11-30 00:40:29', '[Source: Competency Gap Analysis - Skill Gap Requirement]', '{\"employee_details\":{\"id\":1,\"employee_id\":\"EMP-001\",\"full_name\":\"Juan Dela Cruz\",\"email\":\"juan.delacruz@company.com\",\"employment_status\":\"Terminated\",\"job_title\":\"Payroll Specialist\"},\"quiz_details\":{\"title\":\"Agile & Scrum Certification Prep\",\"category\":\"Project Management\",\"total_questions\":30,\"total_points\":150},\"assigned_at\":\"2025-11-29T16:38:50.821921Z\",\"batch_assignment\":true,\"assignment_source\":\"gap_analysis\"}', 3, '2025-11-29 16:38:50', '2025-11-30 15:33:24'),
(26, 12, 70, '1', 'Juan Dela Cruz', 'juan.delacruz@company.com', 25, '2025-11-30 00:40:00', '2025-12-07 00:38:00', '3', 'completed', 2, 80.00, '2025-11-30 23:05:39', '2025-11-30 23:06:29', '[Source: Competency Gap Analysis - Skill Gap Requirement]', '{\"employee_details\":{\"id\":1,\"employee_id\":\"EMP-001\",\"full_name\":\"Juan Dela Cruz\",\"email\":\"juan.delacruz@company.com\",\"employment_status\":\"Terminated\",\"job_title\":\"Payroll Specialist\"},\"quiz_details\":{\"title\":\"Risk Management Assessment\",\"category\":\"Project Management\",\"total_questions\":15,\"total_points\":75},\"assigned_at\":\"2025-11-29T16:38:50.854561Z\",\"batch_assignment\":true,\"assignment_source\":\"gap_analysis\"}', 3, '2025-11-29 16:38:50', '2025-11-30 15:07:59'),
(29, 15, 78, '5', 'Maria Santos', 'maria.santos@company.com', 30, '2025-12-06 11:47:39', '2026-01-12 23:59:59', '3', 'completed', 1, 64.00, '2025-12-06 11:59:55', '2025-12-06 12:01:12', 'futari (Assigned from grant request approval)', NULL, 3, '2025-12-06 03:47:39', '2025-12-06 05:35:25'),
(30, 15, 79, '5', 'Maria Santos', 'maria.santos@company.com', 25, '2025-12-06 11:47:39', '2026-01-12 23:59:59', '3', 'completed', 1, 64.00, '2025-12-06 12:01:24', '2025-12-06 12:06:18', 'futari (Assigned from grant request approval)', NULL, 3, '2025-12-06 03:47:39', '2025-12-06 05:35:25'),
(31, 15, 77, '2', 'Maria Santos', 'maria.santos@company.com', 20, '2025-12-06 14:10:00', '2025-12-13 14:08:00', '3', 'completed', 1, 68.00, '2025-12-06 14:27:47', '2025-12-06 14:28:10', '[Source: Competency Gap Analysis - Skill Gap Requirement]', '{\"employee_details\":{\"id\":2,\"employee_id\":\"EMP-002\",\"full_name\":\"Maria Santos\",\"date_of_birth\":\"1992-11-25\",\"gender\":\"Female\",\"marital_status\":\"Single\",\"nationality\":\"Filipino\",\"email\":\"maria.santos@company.com\",\"phone\":\"09181234568\",\"address\":\"Pasig City, Philippines\",\"employment_type\":\"Regular\",\"employment_status\":\"Active\",\"job_title\":\"HR Officer\",\"department\":\"Core Human Capital Management\",\"work_location\":\"Main Office\",\"hired_date\":\"2019-06-10\",\"end_date\":null,\"supervisor_id\":1,\"created_at\":\"2025-10-08T03:44:12.000000Z\",\"updated_at\":\"2025-10-08T03:44:12.000000Z\",\"contract\":{\"id\":2,\"employee_id\":2,\"contract_no\":\"CON-2025-002\",\"contract_duration_months\":12,\"start_date\":\"2025-01-15\",\"end_date\":\"2026-01-14\",\"created_at\":\"2025-10-08 04:06:47\",\"updated_at\":\"2025-10-08 04:06:47\"},\"salary_details\":{\"id\":2,\"employee_id\":2,\"salary_grade\":\"SG-8\",\"base_salary\":\"42000.00\",\"allowance\":\"{ \\\"transport\\\": 1000, \\\"meal\\\": 1000 }\",\"pay_type\":\"Monthly\",\"payroll_cycle\":\"Semi-Monthly\",\"tax_status\":\"Married\",\"effective_date\":\"2024-02-15\",\"created_at\":\"2025-10-08T04:02:13.000000Z\",\"updated_at\":\"2025-10-10T00:47:19.000000Z\"},\"government_ids\":{\"id\":33,\"employee_id\":2,\"sss_number\":\"99999\",\"tin_number\":\"99999\",\"philhealth_number\":\"999\",\"pagibig_number\":\"9999\",\"created_at\":\"2025-10-29T00:23:26.000000Z\",\"updated_at\":\"2025-10-29T00:23:26.000000Z\"},\"emergency_contacts\":[{\"id\":22,\"employee_id\":2,\"contact_name\":\"Juan Reyes\",\"relationship\":\"Brother\",\"phone\":\"09283456789\",\"address\":\"45 Sampaguita Ave, Quezon City\"}]},\"quiz_details\":{\"title\":\"Company Culture & Values Quiz\",\"category\":\"Onboarding Assessment\",\"total_questions\":15,\"total_points\":75},\"assigned_at\":\"2025-12-06T06:08:52.499839Z\",\"batch_assignment\":true,\"assignment_source\":\"gap_analysis\"}', 3, '2025-12-06 06:08:52', '2025-12-06 06:41:11'),
(32, 15, 78, '2', 'Maria Santos', 'maria.santos@company.com', 30, '2025-12-06 14:10:00', '2025-12-13 14:08:00', '3', 'completed', 1, 68.00, '2025-12-06 14:28:17', '2025-12-06 14:28:35', '[Source: Competency Gap Analysis - Skill Gap Requirement]', '{\"employee_details\":{\"id\":2,\"employee_id\":\"EMP-002\",\"full_name\":\"Maria Santos\",\"date_of_birth\":\"1992-11-25\",\"gender\":\"Female\",\"marital_status\":\"Single\",\"nationality\":\"Filipino\",\"email\":\"maria.santos@company.com\",\"phone\":\"09181234568\",\"address\":\"Pasig City, Philippines\",\"employment_type\":\"Regular\",\"employment_status\":\"Active\",\"job_title\":\"HR Officer\",\"department\":\"Core Human Capital Management\",\"work_location\":\"Main Office\",\"hired_date\":\"2019-06-10\",\"end_date\":null,\"supervisor_id\":1,\"created_at\":\"2025-10-08T03:44:12.000000Z\",\"updated_at\":\"2025-10-08T03:44:12.000000Z\",\"contract\":{\"id\":2,\"employee_id\":2,\"contract_no\":\"CON-2025-002\",\"contract_duration_months\":12,\"start_date\":\"2025-01-15\",\"end_date\":\"2026-01-14\",\"created_at\":\"2025-10-08 04:06:47\",\"updated_at\":\"2025-10-08 04:06:47\"},\"salary_details\":{\"id\":2,\"employee_id\":2,\"salary_grade\":\"SG-8\",\"base_salary\":\"42000.00\",\"allowance\":\"{ \\\"transport\\\": 1000, \\\"meal\\\": 1000 }\",\"pay_type\":\"Monthly\",\"payroll_cycle\":\"Semi-Monthly\",\"tax_status\":\"Married\",\"effective_date\":\"2024-02-15\",\"created_at\":\"2025-10-08T04:02:13.000000Z\",\"updated_at\":\"2025-10-10T00:47:19.000000Z\"},\"government_ids\":{\"id\":33,\"employee_id\":2,\"sss_number\":\"99999\",\"tin_number\":\"99999\",\"philhealth_number\":\"999\",\"pagibig_number\":\"9999\",\"created_at\":\"2025-10-29T00:23:26.000000Z\",\"updated_at\":\"2025-10-29T00:23:26.000000Z\"},\"emergency_contacts\":[{\"id\":22,\"employee_id\":2,\"contact_name\":\"Juan Reyes\",\"relationship\":\"Brother\",\"phone\":\"09283456789\",\"address\":\"45 Sampaguita Ave, Quezon City\"}]},\"quiz_details\":{\"title\":\"New Employee Orientation Test\",\"category\":\"Onboarding Assessment\",\"total_questions\":25,\"total_points\":100},\"assigned_at\":\"2025-12-06T06:08:52.549362Z\",\"batch_assignment\":true,\"assignment_source\":\"gap_analysis\"}', 3, '2025-12-06 06:08:52', '2025-12-06 06:41:11');

-- --------------------------------------------------------

--
-- Table structure for table `assessment_categories`
--

CREATE TABLE `assessment_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_slug` varchar(255) NOT NULL,
  `category_icon` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `color_theme` enum('blue','green','red','purple','orange','teal','indigo','pink') NOT NULL DEFAULT 'blue',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assessment_categories`
--

INSERT INTO `assessment_categories` (`id`, `category_name`, `category_slug`, `category_icon`, `description`, `color_theme`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(6, 'Technical Skills Assessment', 'technical-skills-assessment', 'fas fa-code', 'Evaluate technical competencies including programming, software development, system administration, and IT infrastructure skills. These assessments measure proficiency in technical tools and technologies.', 'blue', 1, 1, '2025-11-28 17:09:22', '2025-11-28 17:09:22'),
(7, 'Leadership & Management', 'leadership-management', 'fas fa-users-cog', 'Assess leadership capabilities, decision-making skills, team management, strategic thinking, and organizational abilities. Ideal for current and aspiring managers.', 'purple', 1, 1, '2025-11-28 17:09:22', '2025-11-28 17:09:22'),
(8, 'Communication Skills', 'communication-skills', 'fas fa-comments', 'Measure verbal and written communication abilities, presentation skills, active listening, and interpersonal effectiveness in professional settings.', 'green', 1, 1, '2025-11-28 17:09:22', '2025-11-28 17:09:22'),
(9, 'Compliance & Regulations', 'compliance-regulations', 'fas fa-balance-scale', 'Test knowledge of company policies, industry regulations, legal requirements, and ethical standards. Essential for maintaining organizational compliance.', 'red', 1, 1, '2025-11-28 17:09:22', '2025-11-28 17:09:22'),
(10, 'Customer Service Excellence', 'customer-service-excellence', 'fas fa-headset', 'Evaluate customer interaction skills, problem-solving abilities, empathy, and service delivery quality. Designed for customer-facing roles.', 'orange', 1, 1, '2025-11-28 17:09:22', '2025-11-28 17:09:22'),
(11, 'Data Analytics & BI', 'data-analytics-bi', 'fas fa-chart-bar', 'Assess data analysis capabilities, statistical knowledge, visualization skills, and business intelligence tool proficiency.', 'teal', 1, 1, '2025-11-28 17:09:22', '2025-11-28 17:09:22'),
(12, 'Project Management', 'project-management', 'fas fa-tasks', 'Evaluate project planning, execution, monitoring, and closing skills. Includes assessment of Agile, Scrum, and traditional methodologies.', 'indigo', 1, 1, '2025-11-28 17:09:22', '2025-11-28 17:09:22'),
(13, 'Health & Safety', 'health-safety', 'fas fa-hard-hat', 'Test knowledge of workplace safety protocols, emergency procedures, hazard identification, and occupational health standards.', 'pink', 1, 1, '2025-11-28 17:09:22', '2025-11-28 17:09:22'),
(14, 'Product Knowledge', 'product-knowledge', 'fas fa-box-open', 'Assess understanding of company products, services, features, benefits, and competitive advantages. Essential for sales and support teams.', 'blue', 1, 1, '2025-11-28 17:09:22', '2025-11-28 17:09:22'),
(15, 'Onboarding Assessment', 'onboarding-assessment', 'fas fa-user-plus', 'Evaluate new employee understanding of company culture, policies, procedures, and role-specific requirements during the onboarding process.', 'green', 1, 1, '2025-11-28 17:09:22', '2025-11-28 17:09:22');

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
(1, '2025_10_03_050901_create_assessment_categories_table', 1),
(2, '0001_01_01_000000_create_users_table', 2),
(3, '0001_01_01_000001_create_cache_table', 2),
(4, '0001_01_01_000002_create_jobs_table', 2),
(5, '2025_09_19_145529_add_two_factor_columns_to_users_table', 2),
(6, '2025_09_19_145723_create_personal_access_tokens_table', 2),
(7, '2025_09_23_035036_create_competency_frameworks_table', 2),
(8, '2025_09_24_144657_create_competencies_table', 2),
(9, '2025_09_26_070711_create_role_mappings_table', 2),
(10, '2025_10_06_000000_create_assessment_assignments_table', 3),
(11, '2025_10_06_000001_remove_assigned_by_foreign_key_from_assessment_assignments', 4);

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
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quiz_title` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `competency_id` bigint(20) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL,
  `time_limit` int(11) NOT NULL DEFAULT 30,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `total_questions` int(11) NOT NULL DEFAULT 0,
  `total_points` int(11) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `quiz_title`, `category_id`, `competency_id`, `description`, `time_limit`, `status`, `total_questions`, `total_points`, `created_by`, `created_at`, `updated_at`) VALUES
(50, 'Python Fundamentals Quiz', 6, 62, 'Test your knowledge of Python programming basics including data types, control structures, functions, and object-oriented programming concepts.', 30, 'published', 20, 100, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(51, 'SQL Database Proficiency Test', 6, 63, 'Evaluate your SQL skills including queries, joins, aggregations, subqueries, and database design principles.', 45, 'published', 25, 100, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(52, 'Web Development Assessment', 6, 62, 'Comprehensive assessment covering HTML, CSS, JavaScript, and modern web development frameworks.', 60, 'draft', 30, 150, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(53, 'Leadership Styles Assessment', 7, 59, 'Identify and understand different leadership styles and when to apply them effectively in various organizational situations.', 25, 'published', 15, 75, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(54, 'Team Management Skills Quiz', 7, 59, 'Assess your ability to manage teams, delegate tasks, resolve conflicts, and motivate team members.', 30, 'published', 20, 100, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(55, 'Strategic Decision Making Test', 7, 60, 'Evaluate your strategic thinking and decision-making abilities in complex business scenarios.', 40, 'published', 20, 100, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(56, 'Business Writing Skills Test', 8, 67, 'Assess your professional writing skills including email etiquette, report writing, and business correspondence.', 35, 'published', 20, 100, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(57, 'Presentation Skills Assessment', 8, 66, 'Evaluate your ability to create and deliver effective presentations, handle Q&A sessions, and engage audiences.', 25, 'published', 15, 75, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(58, 'Active Listening Quiz', 8, 68, 'Test your understanding of active listening techniques and their application in professional settings.', 20, 'published', 15, 75, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(59, 'Company Policy Compliance Test', 9, 84, 'Mandatory assessment on company policies, code of conduct, and workplace behavior expectations.', 30, 'published', 25, 100, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(60, 'Data Privacy & GDPR Quiz', 9, 82, 'Test your knowledge of data protection regulations, GDPR compliance, and privacy best practices.', 25, 'published', 20, 100, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(61, 'Anti-Harassment Training Assessment', 9, 85, 'Evaluate understanding of harassment prevention, reporting procedures, and creating a respectful workplace.', 20, 'published', 15, 75, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(62, 'Customer Service Fundamentals', 10, 72, 'Assess basic customer service skills including communication, empathy, and problem resolution.', 25, 'published', 20, 100, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(63, 'Handling Difficult Customers Quiz', 10, 71, 'Test your ability to de-escalate situations, manage complaints, and maintain professionalism under pressure.', 30, 'published', 20, 100, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(64, 'Customer Experience Strategy Assessment', 10, 70, 'Evaluate understanding of CX principles, journey mapping, and customer satisfaction metrics.', 35, 'draft', 25, 125, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(65, 'Data Analysis Fundamentals Quiz', 11, 78, 'Test basic data analysis skills including data cleaning, visualization, and interpretation.', 30, 'published', 20, 100, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(66, 'Excel & Spreadsheet Skills Test', 11, 79, 'Assess proficiency in Excel functions, pivot tables, charts, and data manipulation techniques.', 40, 'published', 25, 100, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(67, 'Statistical Analysis Assessment', 11, 80, 'Evaluate understanding of statistical concepts, hypothesis testing, and data-driven decision making.', 45, 'published', 25, 125, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(68, 'Project Management Basics Quiz', 12, 74, 'Test fundamental project management concepts including planning, scheduling, and resource management.', 30, 'published', 20, 100, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(69, 'Agile & Scrum Certification Prep', 12, 76, 'Comprehensive assessment on Agile principles, Scrum framework, and sprint management.', 45, 'published', 30, 150, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(70, 'Risk Management Assessment', 12, 77, 'Evaluate your ability to identify, assess, and mitigate project risks effectively.', 25, 'published', 15, 75, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(71, 'Workplace Safety Orientation Quiz', 13, 83, 'Mandatory safety training covering emergency procedures, hazard identification, and safety protocols.', 20, 'published', 20, 100, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(72, 'Fire Safety & Emergency Response', 13, 83, 'Test knowledge of fire safety procedures, evacuation routes, and emergency response protocols.', 15, 'published', 15, 75, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(73, 'Ergonomics & Workplace Wellness', 13, 88, 'Assess understanding of ergonomic principles, proper workstation setup, and health practices.', 20, 'published', 15, 75, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(74, 'Core Products Overview Quiz', 14, 73, 'Test knowledge of company\'s core products, features, benefits, and use cases.', 30, 'published', 25, 100, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(75, 'Competitive Analysis Assessment', 14, 58, 'Evaluate understanding of competitive landscape and product differentiators.', 25, 'published', 20, 100, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(76, 'New Product Launch Certification', 14, 73, 'Certification quiz for newly launched products and services.', 35, 'draft', 25, 125, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(77, 'Company Culture & Values Quiz', 15, 85, 'Test understanding of company mission, vision, values, and organizational culture.', 20, 'published', 15, 75, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(78, 'New Employee Orientation Test', 15, 89, 'Comprehensive assessment covering all onboarding materials and company procedures.', 30, 'published', 25, 100, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47'),
(79, 'Systems & Tools Training Quiz', 15, 97, 'Test proficiency with company systems, software tools, and internal platforms.', 25, 'published', 20, 100, 1, '2025-11-29 05:50:47', '2025-11-29 05:50:47');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quiz_id` bigint(20) UNSIGNED NOT NULL,
  `question` text NOT NULL,
  `correct_answer` varchar(255) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 1,
  `question_order` int(11) NOT NULL DEFAULT 1,
  `question_type` enum('identification') NOT NULL DEFAULT 'identification',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_questions`
--

INSERT INTO `quiz_questions` (`id`, `quiz_id`, `question`, `correct_answer`, `points`, `question_order`, `question_type`, `created_at`, `updated_at`) VALUES
(82, 50, 'What keyword is used to define a function in Python?', 'def', 5, 1, 'identification', '2025-11-29 06:02:06', '2025-11-29 06:02:06'),
(83, 50, 'What is the output of print(type([]))?', 'list', 5, 2, 'identification', '2025-11-29 06:02:06', '2025-11-29 06:02:06'),
(84, 50, 'Which method is used to add an element to the end of a list?', 'append', 5, 3, 'identification', '2025-11-29 06:02:06', '2025-11-29 06:02:06'),
(85, 50, 'What symbol is used for single-line comments in Python?', '#', 5, 4, 'identification', '2025-11-29 06:02:06', '2025-11-29 06:02:06'),
(86, 50, 'What keyword is used to create a class in Python?', 'class', 5, 5, 'identification', '2025-11-29 06:02:06', '2025-11-29 06:02:06'),
(87, 50, 'What is the default return value of a function that does not explicitly return anything?', 'None', 5, 6, 'identification', '2025-11-29 06:02:06', '2025-11-29 06:02:06'),
(88, 50, 'Which built-in function returns the length of a list?', 'len', 5, 7, 'identification', '2025-11-29 06:02:06', '2025-11-29 06:02:06'),
(89, 50, 'What keyword is used to handle exceptions in Python?', 'try', 5, 8, 'identification', '2025-11-29 06:02:06', '2025-11-29 06:02:06'),
(90, 50, 'What data type is used to store key-value pairs in Python?', 'dictionary', 5, 9, 'identification', '2025-11-29 06:02:06', '2025-11-29 06:02:06'),
(91, 50, 'What keyword is used to import a module in Python?', 'import', 5, 10, 'identification', '2025-11-29 06:02:06', '2025-11-29 06:02:06'),
(92, 51, 'What SQL keyword is used to retrieve data from a database?', 'SELECT', 4, 1, 'identification', '2025-11-29 06:02:06', '2025-11-29 06:02:06'),
(93, 51, 'What clause is used to filter records in SQL?', 'WHERE', 4, 2, 'identification', '2025-11-29 06:02:06', '2025-11-29 06:02:06'),
(94, 51, 'What SQL keyword is used to sort the result set?', 'ORDER BY', 4, 3, 'identification', '2025-11-29 06:02:06', '2025-11-29 06:02:06'),
(95, 51, 'What type of JOIN returns all records from the left table?', 'LEFT JOIN', 4, 4, 'identification', '2025-11-29 06:02:06', '2025-11-29 06:02:06'),
(96, 51, 'What SQL function returns the number of rows?', 'COUNT', 4, 5, 'identification', '2025-11-29 06:02:06', '2025-11-29 06:02:06'),
(97, 51, 'What keyword is used to group rows that have the same values?', 'GROUP BY', 4, 6, 'identification', '2025-11-29 06:02:06', '2025-11-29 06:02:06'),
(98, 51, 'What constraint ensures that a column cannot have NULL values?', 'NOT NULL', 4, 7, 'identification', '2025-11-29 06:02:06', '2025-11-29 06:02:06'),
(99, 51, 'What SQL statement is used to insert new data into a database?', 'INSERT', 4, 8, 'identification', '2025-11-29 06:02:06', '2025-11-29 06:02:06'),
(100, 51, 'What SQL statement is used to modify existing records?', 'UPDATE', 4, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(101, 51, 'What SQL statement is used to delete records from a table?', 'DELETE', 4, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(102, 52, 'What does HTML stand for?', 'HyperText Markup Language', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(103, 52, 'What does CSS stand for?', 'Cascading Style Sheets', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(104, 52, 'What HTML tag is used to create a hyperlink?', 'a', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(105, 52, 'What CSS property is used to change the text color?', 'color', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(106, 52, 'What JavaScript keyword is used to declare a variable?', 'let', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(107, 52, 'What HTTP method is used to send data to a server?', 'POST', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(108, 52, 'What does DOM stand for?', 'Document Object Model', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(109, 52, 'What HTML tag is used to define an unordered list?', 'ul', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(110, 52, 'What CSS property is used to add space inside an element?', 'padding', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(111, 52, 'What JavaScript method is used to select an element by its ID?', 'getElementById', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(112, 53, 'What leadership style involves making decisions without consulting team members?', 'Autocratic', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(113, 53, 'What leadership style focuses on empowering and developing team members?', 'Servant', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(114, 53, 'What leadership style involves sharing decision-making with the team?', 'Democratic', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(115, 53, 'What leadership style focuses on inspiring and motivating through vision?', 'Transformational', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(116, 53, 'What leadership style adapts based on the situation and team needs?', 'Situational', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(117, 53, 'What leadership style uses rewards and punishments to motivate?', 'Transactional', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(118, 53, 'What leadership style gives team members complete freedom to make decisions?', 'Laissez-faire', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(119, 53, 'What quality is essential for building trust as a leader?', 'Integrity', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(120, 53, 'What is the process of assigning tasks to team members called?', 'Delegation', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(121, 53, 'What leadership concept involves leading by demonstrating desired behaviors?', 'Leading by example', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(122, 54, 'What is the process of resolving disagreements between team members called?', 'Conflict resolution', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(123, 54, 'What term describes the collective spirit and morale of a team?', 'Team morale', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(124, 54, 'What is the first stage of team development according to Tuckman?', 'Forming', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(125, 54, 'What is the process of providing constructive criticism to team members?', 'Feedback', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(126, 54, 'What term describes setting clear expectations for team performance?', 'Goal setting', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(127, 54, 'What management style involves closely supervising employees?', 'Micromanagement', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(128, 54, 'What is the process of helping team members develop their skills?', 'Coaching', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(129, 54, 'What type of meeting focuses on daily progress updates?', 'Stand-up', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(130, 54, 'What is the term for a team member who excels in their work and helps others?', 'Role model', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(131, 54, 'What document outlines team roles, responsibilities, and expectations?', 'Team charter', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(132, 55, 'What analysis examines Strengths, Weaknesses, Opportunities, and Threats?', 'SWOT', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(133, 55, 'What is the process of choosing between multiple alternatives called?', 'Decision making', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(134, 55, 'What term describes the potential negative outcome of a decision?', 'Risk', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(135, 55, 'What analysis evaluates the costs versus benefits of a decision?', 'Cost-benefit analysis', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(136, 55, 'What is the tendency to favor information that confirms existing beliefs?', 'Confirmation bias', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(137, 55, 'What term describes involving multiple stakeholders in decision making?', 'Consensus', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(138, 55, 'What is the expected value of an uncertain outcome called?', 'Expected value', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(139, 55, 'What decision-making approach relies on data and evidence?', 'Data-driven', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(140, 55, 'What is the cost of the next best alternative given up called?', 'Opportunity cost', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(141, 55, 'What type of thinking involves breaking down complex problems into parts?', 'Analytical', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(142, 56, 'What is the opening greeting in a business email called?', 'Salutation', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(143, 56, 'What writing quality means being brief and to the point?', 'Concise', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(144, 56, 'What tone is appropriate for professional business communication?', 'Formal', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(145, 56, 'What part of a business letter contains your contact information?', 'Letterhead', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(146, 56, 'What is the closing phrase before your signature in an email?', 'Regards', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(147, 56, 'What writing error involves switching between verb tenses incorrectly?', 'Tense inconsistency', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(148, 56, 'What is a brief summary at the beginning of a report called?', 'Executive summary', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(149, 56, 'What type of writing aims to convince the reader?', 'Persuasive', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(150, 56, 'What is the process of reviewing and correcting written work?', 'Proofreading', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(151, 56, 'What writing principle means using simple words over complex ones?', 'Clarity', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(152, 57, 'What is the opening part of a presentation that captures attention called?', 'Hook', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(153, 57, 'What visual aid software is commonly used for presentations?', 'PowerPoint', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(154, 57, 'What is the fear of public speaking called?', 'Glossophobia', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(155, 57, 'What refers to hand movements and facial expressions during a presentation?', 'Body language', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(156, 57, 'What is a brief rehearsal of a presentation called?', 'Dry run', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(157, 57, 'What section at the end allows the audience to ask questions?', 'Q&A', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(158, 57, 'What is the main point or argument of your presentation called?', 'Key message', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(159, 57, 'What technique involves telling a story to engage the audience?', 'Storytelling', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(160, 57, 'What visual element uses images to represent data?', 'Infographic', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(161, 57, 'What is the act of looking at different audience members called?', 'Eye contact', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(162, 58, 'What is repeating back what someone said in your own words called?', 'Paraphrasing', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(163, 58, 'What type of questions cannot be answered with yes or no?', 'Open-ended', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(164, 58, 'What non-verbal cue shows you are paying attention?', 'Nodding', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(165, 58, 'What barrier to listening involves preparing your response while others speak?', 'Rehearsing', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(166, 58, 'What is the ability to understand and share the feelings of others?', 'Empathy', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(167, 58, 'What listening technique involves summarizing the main points?', 'Summarizing', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(168, 58, 'What term describes giving your full attention to the speaker?', 'Focus', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(169, 58, 'What is the opposite of active listening?', 'Passive listening', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(170, 58, 'What response shows understanding without interrupting?', 'Acknowledgment', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(171, 58, 'What is asking for more details about what was said called?', 'Clarifying', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(172, 59, 'What document outlines expected employee behavior standards?', 'Code of conduct', 4, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(173, 59, 'What term describes following rules and regulations?', 'Compliance', 4, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(174, 59, 'What is the process of reporting policy violations called?', 'Whistleblowing', 4, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(175, 59, 'What type of policy addresses computer and internet use?', 'Acceptable use policy', 4, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(176, 59, 'What document must employees sign acknowledging policy receipt?', 'Acknowledgment form', 4, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(177, 59, 'What is a situation where personal interests conflict with work duties?', 'Conflict of interest', 4, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(178, 59, 'What department typically handles policy violations?', 'Human Resources', 4, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(179, 59, 'What is the consequence for serious policy violations?', 'Termination', 4, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(180, 59, 'What policy protects sensitive company information?', 'Confidentiality policy', 4, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(181, 59, 'What is the process of reviewing policies regularly called?', 'Policy review', 4, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(182, 60, 'What does GDPR stand for?', 'General Data Protection Regulation', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(183, 60, 'What right allows individuals to request deletion of their data?', 'Right to erasure', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(184, 60, 'What is obtaining permission before collecting personal data called?', 'Consent', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(185, 60, 'What type of data includes health information and biometric data?', 'Sensitive data', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(186, 60, 'What is a security incident involving unauthorized data access called?', 'Data breach', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(187, 60, 'What role is responsible for ensuring GDPR compliance in an organization?', 'Data Protection Officer', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(188, 60, 'What is the principle of collecting only necessary data called?', 'Data minimization', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(189, 60, 'What right allows individuals to obtain a copy of their data?', 'Right of access', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(190, 60, 'What is the maximum GDPR fine as a percentage of annual turnover?', '4%', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(191, 60, 'What document explains how an organization uses personal data?', 'Privacy policy', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(192, 61, 'What is unwelcome conduct based on protected characteristics called?', 'Harassment', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(193, 61, 'What type of harassment involves requests for sexual favors?', 'Quid pro quo', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(194, 61, 'What term describes a work environment that is intimidating or hostile?', 'Hostile work environment', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(195, 61, 'What should you do first if you witness harassment?', 'Report it', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(196, 61, 'What policy protects employees who report harassment from retaliation?', 'Anti-retaliation policy', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(197, 61, 'What department should harassment complaints be reported to?', 'Human Resources', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(198, 61, 'What is treating someone unfairly based on their characteristics called?', 'Discrimination', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(199, 61, 'What type of harassment occurs online or through digital communication?', 'Cyberbullying', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(200, 61, 'What is the responsibility of managers when they learn of harassment?', 'Investigate', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(201, 61, 'What training helps prevent harassment in the workplace?', 'Sensitivity training', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(202, 62, 'What is the first thing you should do when greeting a customer?', 'Smile', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(203, 62, 'What quality means putting yourself in the customer\'s shoes?', 'Empathy', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(204, 62, 'What is the process of solving customer problems called?', 'Troubleshooting', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(205, 62, 'What metric measures customer satisfaction with a single question?', 'NPS', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(206, 62, 'What type of customer is unhappy but doesn\'t complain?', 'Silent customer', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(207, 62, 'What is following up after resolving an issue called?', 'Follow-up', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(208, 62, 'What skill involves staying calm under pressure?', 'Patience', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(209, 62, 'What is going beyond customer expectations called?', 'Exceeding expectations', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(210, 62, 'What should you do when you don\'t know the answer?', 'Escalate', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(211, 62, 'What type of language should be avoided with customers?', 'Jargon', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(212, 63, 'What is the first step when dealing with an angry customer?', 'Listen', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(213, 63, 'What technique involves acknowledging the customer\'s feelings?', 'Validation', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(214, 63, 'What should you avoid doing when a customer is upset?', 'Arguing', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(215, 63, 'What is the process of calming down an upset customer?', 'De-escalation', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(216, 63, 'What phrase shows you take responsibility for the issue?', 'I apologize', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(217, 63, 'What should you do if a customer becomes abusive?', 'End the call', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(218, 63, 'What is offering alternatives to solve a problem called?', 'Providing options', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(219, 63, 'What tone of voice is best when handling complaints?', 'Calm', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(220, 63, 'What is documenting the interaction for future reference called?', 'Logging', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(221, 63, 'What is the goal of handling a difficult customer?', 'Resolution', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(222, 64, 'What is a visual representation of the customer\'s journey called?', 'Journey map', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(223, 64, 'What does CX stand for?', 'Customer Experience', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(224, 64, 'What metric measures likelihood to recommend?', 'Net Promoter Score', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(225, 64, 'What is every interaction a customer has with a company called?', 'Touchpoint', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(226, 64, 'What strategy focuses on keeping existing customers?', 'Customer retention', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(227, 64, 'What is listening to customer feedback across channels called?', 'Voice of customer', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(228, 64, 'What type of experience is consistent across all channels?', 'Omnichannel', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(229, 64, 'What is tailoring experiences to individual customers called?', 'Personalization', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(230, 64, 'What is the emotional connection customers have with a brand?', 'Brand loyalty', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(231, 64, 'What design approach puts customers at the center?', 'Customer-centric', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(232, 65, 'What is the process of removing errors from data called?', 'Data cleaning', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(233, 65, 'What type of chart shows proportions of a whole?', 'Pie chart', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(234, 65, 'What statistical measure represents the middle value?', 'Median', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(235, 65, 'What is the average of a set of numbers called?', 'Mean', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(236, 65, 'What type of analysis looks at past data to understand trends?', 'Descriptive', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(237, 65, 'What is a value that is significantly different from other data points?', 'Outlier', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(238, 65, 'What chart type is best for showing trends over time?', 'Line chart', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(239, 65, 'What is the relationship between two variables called?', 'Correlation', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(240, 65, 'What is organizing data into categories called?', 'Classification', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(241, 65, 'What type of data has numerical values?', 'Quantitative', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(242, 66, 'What Excel function calculates the sum of a range?', 'SUM', 4, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(243, 66, 'What Excel function looks up a value in a table?', 'VLOOKUP', 4, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(244, 66, 'What feature allows you to summarize large amounts of data?', 'Pivot table', 4, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(245, 66, 'What Excel function counts cells that meet a condition?', 'COUNTIF', 4, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(246, 66, 'What is the keyboard shortcut to copy in Excel?', 'Ctrl+C', 4, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(247, 66, 'What Excel function returns the current date?', 'TODAY', 4, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(248, 66, 'What is a cell reference that doesn\'t change when copied called?', 'Absolute reference', 4, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(249, 66, 'What Excel function combines text from multiple cells?', 'CONCATENATE', 4, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(250, 66, 'What feature automatically fills cells with a pattern?', 'AutoFill', 4, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(251, 66, 'What Excel function returns the average of a range?', 'AVERAGE', 4, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(252, 67, 'What is a testable prediction about the relationship between variables?', 'Hypothesis', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(253, 67, 'What is the probability of making a Type I error called?', 'Alpha', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(254, 67, 'What statistical test compares means of two groups?', 't-test', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(255, 67, 'What measure shows how spread out data is from the mean?', 'Standard deviation', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(256, 67, 'What is a smaller group selected from a population called?', 'Sample', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(257, 67, 'What type of error occurs when you reject a true null hypothesis?', 'Type I error', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(258, 67, 'What statistical measure ranges from -1 to 1 for correlation?', 'Correlation coefficient', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(259, 67, 'What is the hypothesis that there is no effect called?', 'Null hypothesis', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(260, 67, 'What value indicates statistical significance if below 0.05?', 'p-value', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(261, 67, 'What type of distribution is bell-shaped?', 'Normal distribution', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(262, 68, 'What document defines the project scope, objectives, and stakeholders?', 'Project charter', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(263, 68, 'What chart shows tasks and their durations in a timeline?', 'Gantt chart', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(264, 68, 'What is the longest path through a project network called?', 'Critical path', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(265, 68, 'What is a hierarchical breakdown of project work called?', 'WBS', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(266, 68, 'What project constraint includes scope, time, and cost?', 'Triple constraint', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(267, 68, 'What is a significant event or achievement in a project?', 'Milestone', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(268, 68, 'What type of dependency means one task must finish before another starts?', 'Finish-to-start', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(269, 68, 'What is the process of identifying potential problems in a project?', 'Risk identification', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(270, 68, 'What document tracks changes to project scope?', 'Change log', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(271, 68, 'What is a formal request to change project scope called?', 'Change request', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(272, 69, 'What is a time-boxed iteration in Scrum called?', 'Sprint', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(273, 69, 'Who is responsible for maximizing the value of the product?', 'Product Owner', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(274, 69, 'What is the prioritized list of work in Scrum called?', 'Product backlog', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(275, 69, 'What daily meeting does the Scrum team hold?', 'Daily standup', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(276, 69, 'Who facilitates Scrum events and removes impediments?', 'Scrum Master', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(277, 69, 'What meeting is held at the end of a sprint to demonstrate work?', 'Sprint review', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(278, 69, 'What meeting focuses on improving team processes?', 'Retrospective', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(279, 69, 'What is a short description of a feature from the user\'s perspective?', 'User story', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(280, 69, 'What unit is used to estimate the effort for user stories?', 'Story points', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(281, 69, 'What Agile principle values working software over comprehensive what?', 'Documentation', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(282, 70, 'What is a potential event that could negatively impact a project?', 'Risk', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(283, 70, 'What is the process of reducing the probability or impact of a risk?', 'Mitigation', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(284, 70, 'What risk response involves accepting the consequences?', 'Acceptance', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(285, 70, 'What matrix plots probability versus impact of risks?', 'Risk matrix', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(286, 70, 'What is a risk that could have a positive impact called?', 'Opportunity', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(287, 70, 'What document lists all identified risks and their responses?', 'Risk register', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(288, 70, 'What risk response involves shifting risk to a third party?', 'Transfer', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(289, 70, 'What is a risk that remains after implementing responses?', 'Residual risk', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(290, 70, 'What is a new risk that arises from a risk response?', 'Secondary risk', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(291, 70, 'What risk response eliminates the threat entirely?', 'Avoidance', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(292, 71, 'What does PPE stand for?', 'Personal Protective Equipment', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(293, 71, 'What is the designated meeting point after an evacuation called?', 'Assembly point', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(294, 71, 'What color is typically used for fire extinguishers?', 'Red', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(295, 71, 'What document lists all hazards of a chemical?', 'Safety Data Sheet', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(296, 71, 'What is the first step if you discover a fire?', 'Raise the alarm', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(297, 71, 'What type of hazard involves electricity?', 'Electrical hazard', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(298, 71, 'What is the process of identifying workplace hazards called?', 'Risk assessment', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(299, 71, 'What sign shape indicates a warning?', 'Triangle', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(300, 71, 'What should you do if you see an unsafe condition?', 'Report it', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(301, 71, 'What type of injury is caused by repetitive motions?', 'RSI', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(302, 72, 'What word helps remember how to use a fire extinguisher?', 'PASS', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(303, 72, 'What type of fire extinguisher is used for electrical fires?', 'CO2', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(304, 72, 'What should you do if your clothing catches fire?', 'Stop drop roll', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(305, 72, 'What is the maximum number of people in your evacuation route?', 'Capacity', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(306, 72, 'What color is an emergency exit sign?', 'Green', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(307, 72, 'What should you avoid using during a fire evacuation?', 'Elevator', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(308, 72, 'What is the emergency phone number in most countries?', '911', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(309, 72, 'What should you do if there is smoke in the air?', 'Stay low', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(310, 72, 'What person ensures everyone has evacuated an area?', 'Fire warden', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(311, 72, 'What is regular practice of emergency procedures called?', 'Fire drill', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(312, 73, 'What is the study of people\'s efficiency in their working environment?', 'Ergonomics', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(313, 73, 'What angle should your elbows be at when typing?', '90 degrees', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(314, 73, 'What is the recommended distance between eyes and monitor?', 'Arms length', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(315, 73, 'What should the top of your monitor be level with?', 'Eye level', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(316, 73, 'What rule helps prevent eye strain from screens?', '20-20-20 rule', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(317, 73, 'What type of chair supports proper posture?', 'Ergonomic chair', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(318, 73, 'What should your feet be doing when sitting properly?', 'Flat on floor', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(319, 73, 'What activity helps prevent stiffness from sitting?', 'Stretching', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(320, 73, 'What type of lighting reduces eye strain?', 'Natural light', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(321, 73, 'How often should you take breaks from your screen?', 'Every hour', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(322, 74, 'What makes our product different from competitors?', 'Unique value proposition', 4, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(323, 74, 'What is the primary benefit our customers receive?', 'Value', 4, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(324, 74, 'What group of customers is our product designed for?', 'Target market', 4, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(325, 74, 'What is a key capability of our main product?', 'Core feature', 4, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(326, 74, 'What pricing model does our product use?', 'Subscription', 4, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(327, 74, 'What is our product\'s main competitive advantage?', 'Differentiation', 4, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(328, 74, 'What level of support do premium customers receive?', 'Priority support', 4, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(329, 74, 'What is the process of improving products based on feedback?', 'Iteration', 4, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(330, 74, 'What document describes product features for customers?', 'Product brochure', 4, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(331, 74, 'What is the path customers take to purchase called?', 'Sales funnel', 4, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(332, 75, 'What is the process of evaluating competitors called?', 'Competitive analysis', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(333, 75, 'What are companies offering similar products called?', 'Competitors', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(334, 75, 'What is the percentage of market a company holds?', 'Market share', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(335, 75, 'What analysis examines industry competitive forces?', 'Porter\'s Five Forces', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(336, 75, 'What is a feature that competitors cannot easily copy?', 'Competitive advantage', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(337, 75, 'What is setting prices based on competitor prices called?', 'Competitive pricing', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(338, 75, 'What is a comparison of products side by side called?', 'Feature comparison', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(339, 75, 'What is the threat of new companies entering the market?', 'New entrants', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(340, 75, 'What strategy focuses on being the lowest-cost producer?', 'Cost leadership', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(341, 75, 'What is a company that could potentially become a competitor?', 'Potential competitor', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(342, 76, 'What is the process of introducing a new product to market?', 'Product launch', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(343, 76, 'What is testing a product with a small group before launch?', 'Beta testing', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(344, 76, 'What document outlines the launch strategy and timeline?', 'Launch plan', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(345, 76, 'What is the target date for product availability?', 'Launch date', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(346, 76, 'What training prepares sales teams for a new product?', 'Sales enablement', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(347, 76, 'What is marketing content that explains the product called?', 'Product collateral', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(348, 76, 'What is the initial group of customers for a new product?', 'Early adopters', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(349, 76, 'What metric measures interest before launch?', 'Pre-orders', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(350, 76, 'What is a soft introduction before full launch called?', 'Soft launch', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(351, 76, 'What feedback is gathered after launch to improve?', 'Post-launch feedback', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(352, 77, 'What is the company\'s reason for existing called?', 'Mission', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(353, 77, 'What describes where the company wants to be in the future?', 'Vision', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(354, 77, 'What are the guiding principles of the organization called?', 'Core values', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(355, 77, 'What describes the shared beliefs and behaviors in an organization?', 'Culture', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(356, 77, 'What is treating all employees fairly and equally called?', 'Equity', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(357, 77, 'What value involves being honest and transparent?', 'Integrity', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(358, 77, 'What is including people from diverse backgrounds called?', 'Inclusion', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(359, 77, 'What value focuses on achieving results?', 'Excellence', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(360, 77, 'What is working together toward common goals called?', 'Collaboration', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(361, 77, 'What value involves trying new approaches?', 'Innovation', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(362, 78, 'What department handles payroll and benefits?', 'Human Resources', 4, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(363, 78, 'What is the period when new employees learn about the company?', 'Onboarding', 4, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(364, 78, 'What document outlines your job duties?', 'Job description', 4, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(365, 78, 'What is the person who supervises your work called?', 'Manager', 4, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(366, 78, 'What system is used to track work hours?', 'Time tracking', 4, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(367, 78, 'What is the process of requesting time off called?', 'Leave request', 4, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(368, 78, 'What document contains all company policies?', 'Employee handbook', 4, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(369, 78, 'What is regular feedback on performance called?', 'Performance review', 4, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(370, 78, 'What channel is used for company announcements?', 'Internal communications', 4, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(371, 78, 'What is the experienced employee who guides new hires called?', 'Mentor', 4, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(372, 79, 'What is the main communication tool used for instant messaging?', 'Slack', 5, 1, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(373, 79, 'What platform is used for video conferencing?', 'Zoom', 5, 2, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(374, 79, 'What is the cloud storage platform for documents?', 'Google Drive', 5, 3, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(375, 79, 'What tool is used for project management and task tracking?', 'Jira', 5, 4, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(376, 79, 'What is the process of logging into company systems?', 'Authentication', 5, 5, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(377, 79, 'What security feature requires two verification methods?', 'Two-factor authentication', 5, 6, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(378, 79, 'What is the company\'s internal website for employees?', 'Intranet', 5, 7, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(379, 79, 'What system manages customer information and interactions?', 'CRM', 5, 8, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(380, 79, 'What tool is used for collaborative document editing?', 'Google Docs', 5, 9, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07'),
(381, 79, 'What is the ticket system for IT support requests?', 'Help desk', 5, 10, 'identification', '2025-11-29 06:02:07', '2025-11-29 06:02:07');

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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessment_assignments`
--
ALTER TABLE `assessment_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assessment_assignments_assessment_category_id_foreign` (`assessment_category_id`),
  ADD KEY `assessment_assignments_assigned_by_foreign` (`assigned_by`),
  ADD KEY `assessment_assignments_employee_id_status_index` (`employee_id`,`status`),
  ADD KEY `assessment_assignments_status_due_date_index` (`status`,`due_date`),
  ADD KEY `assessment_assignments_quiz_id_status_index` (`quiz_id`,`status`),
  ADD KEY `assessment_assignments_start_date_index` (`start_date`),
  ADD KEY `assessment_assignments_due_date_index` (`due_date`);

--
-- Indexes for table `assessment_categories`
--
ALTER TABLE `assessment_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `assessment_categories_category_slug_unique` (`category_slug`),
  ADD KEY `assessment_categories_is_active_index` (`is_active`),
  ADD KEY `assessment_categories_category_slug_index` (`category_slug`),
  ADD KEY `assessment_categories_created_by_index` (`created_by`);

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
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quizzes_category_id_foreign` (`category_id`),
  ADD KEY `quizzes_status_category_id_index` (`status`,`category_id`),
  ADD KEY `quizzes_competency_id_index` (`competency_id`);

--
-- Indexes for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_questions_quiz_id_question_order_index` (`quiz_id`,`question_order`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessment_assignments`
--
ALTER TABLE `assessment_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `assessment_categories`
--
ALTER TABLE `assessment_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=382;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assessment_assignments`
--
ALTER TABLE `assessment_assignments`
  ADD CONSTRAINT `assessment_assignments_assessment_category_id_foreign` FOREIGN KEY (`assessment_category_id`) REFERENCES `assessment_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assessment_assignments_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `assessment_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `quiz_questions_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
