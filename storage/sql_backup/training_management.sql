-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2025 at 03:10 PM
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
-- Database: `training_management`
--

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
-- Table structure for table `course_requests`
--

CREATE TABLE `course_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `course_title` varchar(255) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `employee_email` varchar(255) NOT NULL,
  `request_reason` text DEFAULT NULL,
  `status` enum('pending','approved','denied') NOT NULL DEFAULT 'pending',
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `review_notes` text DEFAULT NULL,
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_requests`
--

INSERT INTO `course_requests` (`id`, `employee_id`, `course_id`, `course_title`, `employee_name`, `employee_email`, `request_reason`, `status`, `reviewed_by`, `review_notes`, `requested_at`, `reviewed_at`, `created_at`, `updated_at`) VALUES
(9, 4, 10, 'Project Management', 'Juan Dela Cruz', 'juan.delacruz@company.com', NULL, 'approved', 3, 'sigii', '2025-11-29 06:15:39', '2025-11-29 06:15:39', '2025-11-29 06:09:59', '2025-11-29 06:09:59'),
(10, 5, 15, 'Digital Transformation', 'Maria Santos', 'maria.santos@company.com', NULL, 'approved', 3, 'void', '2025-12-06 03:46:58', '2025-12-06 03:46:58', '2025-12-06 03:37:30', '2025-12-06 03:37:30');

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
(1, '2025_10_01_171534_create_training_catalogs_table', 1),
(2, '0001_01_01_000000_create_users_table', 2),
(3, '0001_01_01_000001_create_cache_table', 2),
(4, '0001_01_01_000002_create_jobs_table', 2),
(5, '2025_09_19_145529_add_two_factor_columns_to_users_table', 2),
(6, '2025_09_19_145723_create_personal_access_tokens_table', 2),
(7, '2025_09_23_035036_create_competency_frameworks_table', 2),
(8, '2025_09_24_144657_create_competencies_table', 2),
(9, '2025_09_26_070711_create_role_mappings_table', 2),
(10, '2025_10_02_060008_create_training_materials_table', 3),
(11, '2025_10_02_061407_add_deleted_at_to_training_materials_table', 4),
(12, '2025_10_02_084018_create_training_assignments_table', 3),
(13, '2025_10_02_112935_create_training_assignment_employees_table', 3),
(14, '2025_10_02_112950_create_training_assignment_materials_table', 3);

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

-- --------------------------------------------------------

--
-- Table structure for table `training_assignments`
--

CREATE TABLE `training_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `assignment_title` varchar(255) NOT NULL,
  `training_catalog_id` bigint(20) UNSIGNED NOT NULL,
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `assignment_type` enum('mandatory','optional','development') NOT NULL DEFAULT 'mandatory',
  `start_date` date NOT NULL,
  `due_date` date NOT NULL,
  `instructions` text DEFAULT NULL,
  `status` enum('draft','active','completed','cancelled') NOT NULL DEFAULT 'draft',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `training_assignments`
--

INSERT INTO `training_assignments` (`id`, `assignment_title`, `training_catalog_id`, `priority`, `assignment_type`, `start_date`, `due_date`, `instructions`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(17, 'Job Skill Gap Assessment', 10, 'high', 'mandatory', '2025-11-29', '2025-12-06', 'Gambare', 'active', 3, '2025-11-29 13:59:12', '2025-11-29 13:59:12'),
(18, 'Digital Transformation', 15, 'medium', 'mandatory', '2025-12-06', '2026-01-05', 'Assigned from approved course request. Notes: void', 'active', 3, '2025-12-06 03:46:58', '2025-12-06 03:46:58'),
(19, 'Financial and Accounting Standard', 11, 'high', 'development', '2025-12-06', '2025-12-13', 'ge lang', 'active', 3, '2025-12-06 06:02:15', '2025-12-06 06:02:15');

-- --------------------------------------------------------

--
-- Table structure for table `training_assignment_employees`
--

CREATE TABLE `training_assignment_employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `training_assignment_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('assigned','in_progress','completed','failed','cancelled') NOT NULL DEFAULT 'assigned',
  `assigned_at` datetime NOT NULL DEFAULT current_timestamp(),
  `started_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `progress_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `training_assignment_employees`
--

INSERT INTO `training_assignment_employees` (`id`, `training_assignment_id`, `employee_id`, `status`, `assigned_at`, `started_at`, `completed_at`, `notes`, `progress_percentage`, `created_at`, `updated_at`) VALUES
(17, 17, 1, 'assigned', '2025-11-29 21:59:12', NULL, NULL, NULL, 0.00, '2025-11-29 13:59:12', '2025-11-29 13:59:12'),
(18, 18, 5, 'assigned', '2025-12-06 11:46:58', NULL, NULL, 'Course request approved by Admin User', 0.00, '2025-12-06 03:46:58', '2025-12-06 03:46:58'),
(19, 19, 2, 'assigned', '2025-12-06 14:02:15', NULL, NULL, NULL, 0.00, '2025-12-06 06:02:15', '2025-12-06 06:02:15');

-- --------------------------------------------------------

--
-- Table structure for table `training_assignment_materials`
--

CREATE TABLE `training_assignment_materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `training_assignment_id` bigint(20) UNSIGNED NOT NULL,
  `training_material_id` bigint(20) UNSIGNED NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT 1,
  `order_sequence` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `training_assignment_materials`
--

INSERT INTO `training_assignment_materials` (`id`, `training_assignment_id`, `training_material_id`, `is_required`, `order_sequence`, `created_at`, `updated_at`) VALUES
(21, 17, 16, 1, 1, '2025-11-29 13:59:12', '2025-11-29 13:59:12'),
(22, 18, 29, 1, 1, '2025-12-06 03:46:58', '2025-12-06 03:46:58'),
(23, 18, 30, 1, 2, '2025-12-06 03:46:58', '2025-12-06 03:46:58'),
(24, 19, 17, 1, 1, '2025-12-06 06:02:15', '2025-12-06 06:02:15'),
(25, 19, 18, 1, 2, '2025-12-06 06:02:15', '2025-12-06 06:02:15');

-- --------------------------------------------------------

--
-- Table structure for table `training_catalogs`
--

CREATE TABLE `training_catalogs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `framework_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `training_catalogs`
--

INSERT INTO `training_catalogs` (`id`, `title`, `label`, `description`, `is_active`, `framework_id`, `created_at`, `updated_at`) VALUES
(7, 'Leadership Development', 'Leadership', 'Comprehensive training programs designed to develop leadership skills, strategic thinking, and team management capabilities for current and aspiring leaders.', 1, NULL, '2025-11-28 15:40:07', '2025-11-28 15:40:07'),
(8, 'Technical Skills Training', 'Technical', 'Hands-on technical training courses covering programming, software development, IT infrastructure, and emerging technologies.', 1, NULL, '2025-11-28 15:40:07', '2025-11-28 15:40:07'),
(9, 'Communication Excellence', 'Communication', 'Training programs focused on improving verbal and written communication, presentation skills, and interpersonal effectiveness.', 1, NULL, '2025-11-28 15:40:07', '2025-11-28 15:40:07'),
(10, 'Project Management', 'PM', 'Comprehensive project management training covering methodologies like Agile, Scrum, and traditional waterfall approaches.', 1, NULL, '2025-11-28 15:40:07', '2025-11-28 15:40:07'),
(11, 'Compliance & Ethics', 'Compliance', 'Mandatory training on corporate policies, regulatory compliance, workplace ethics, and legal requirements.', 1, NULL, '2025-11-28 15:40:07', '2025-11-28 15:40:07'),
(12, 'Customer Service Excellence', 'Customer Service', 'Training programs to enhance customer interaction skills, problem-solving, and service delivery excellence.', 1, NULL, '2025-11-28 15:40:07', '2025-11-28 15:40:07'),
(13, 'Data Analytics & Business Intelligence', 'Analytics', 'Courses on data analysis, visualization tools, statistical methods, and business intelligence platforms.', 1, NULL, '2025-11-28 15:40:07', '2025-11-28 15:40:07'),
(14, 'Health & Safety', 'Safety', 'Workplace safety training including emergency procedures, hazard identification, and occupational health standards.', 1, NULL, '2025-11-28 15:40:07', '2025-11-28 15:40:07'),
(15, 'Digital Transformation', 'Digital', 'Training on digital tools, cloud computing, automation, and adapting to technological changes in the workplace.', 1, NULL, '2025-11-28 15:40:07', '2025-11-28 15:40:07'),
(16, 'Human Resources Management', 'HR', 'HR-focused training covering recruitment, performance management, employee relations, and HR best practices.', 0, NULL, '2025-11-28 15:40:07', '2025-11-28 15:40:07');

-- --------------------------------------------------------

--
-- Table structure for table `training_materials`
--

CREATE TABLE `training_materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lesson_title` varchar(255) NOT NULL,
  `training_catalog_id` bigint(20) UNSIGNED NOT NULL,
  `competency_id` bigint(20) UNSIGNED NOT NULL,
  `proficiency_level` int(11) NOT NULL,
  `lesson_content` longtext NOT NULL,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `training_materials`
--

INSERT INTO `training_materials` (`id`, `lesson_title`, `training_catalog_id`, `competency_id`, `proficiency_level`, `lesson_content`, `status`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(5, 'Introduction to Leadership Fundamentals', 7, 1, 1, '<h2>Leadership Fundamentals</h2><p>This lesson covers the core principles of effective leadership, including understanding different leadership styles, the importance of emotional intelligence, and building trust within teams.</p><h3>Learning Objectives</h3><ul><li>Understand the difference between management and leadership</li><li>Identify your personal leadership style</li><li>Learn the fundamentals of servant leadership</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(6, 'Strategic Decision Making', 7, 1, 2, '<h2>Strategic Decision Making</h2><p>Learn how to make informed decisions that align with organizational goals. This module covers decision-making frameworks, risk assessment, and stakeholder analysis.</p><h3>Key Topics</h3><ul><li>SWOT Analysis for decision making</li><li>Risk-benefit evaluation</li><li>Consensus building techniques</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(7, 'Executive Leadership & Vision', 7, 1, 3, '<h2>Executive Leadership</h2><p>Advanced leadership concepts for senior executives, focusing on organizational vision, change management at scale, and building high-performance cultures.</p><h3>Advanced Topics</h3><ul><li>Creating and communicating organizational vision</li><li>Leading through transformation</li><li>Building executive presence</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(8, 'Programming Basics with Python', 8, 2, 1, '<h2>Python Programming Basics</h2><p>Get started with Python programming. Learn syntax, data types, control structures, and basic problem-solving techniques.</p><h3>Topics Covered</h3><ul><li>Python installation and setup</li><li>Variables and data types</li><li>Loops and conditional statements</li><li>Functions and modules</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(9, 'Web Development with Laravel', 8, 2, 2, '<h2>Laravel Web Development</h2><p>Build modern web applications using the Laravel PHP framework. Learn MVC architecture, routing, database management, and authentication.</p><h3>Course Content</h3><ul><li>Laravel installation and configuration</li><li>Routing and controllers</li><li>Eloquent ORM and migrations</li><li>Blade templating engine</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(10, 'System Architecture & Design Patterns', 8, 2, 3, '<h2>Advanced System Architecture</h2><p>Master enterprise-level software architecture, design patterns, and scalable system design principles.</p><h3>Advanced Concepts</h3><ul><li>Microservices architecture</li><li>Design patterns (SOLID, DRY, KISS)</li><li>Scalability and performance optimization</li><li>CI/CD pipelines</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(11, 'Effective Business Writing', 9, 3, 1, '<h2>Business Writing Essentials</h2><p>Learn to write clear, concise, and professional business documents including emails, reports, and proposals.</p><h3>Writing Skills</h3><ul><li>Email etiquette and best practices</li><li>Structuring business documents</li><li>Grammar and punctuation review</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(12, 'Presentation Skills Mastery', 9, 3, 2, '<h2>Presentation Skills</h2><p>Develop compelling presentation skills to engage audiences and deliver impactful messages. Learn storytelling techniques and visual design principles.</p><h3>Key Areas</h3><ul><li>Structuring presentations for impact</li><li>Using visual aids effectively</li><li>Handling Q&A sessions</li><li>Managing presentation anxiety</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(13, 'Executive Communication & Influence', 9, 3, 3, '<h2>Executive Communication</h2><p>Advanced communication strategies for leaders including stakeholder management, crisis communication, and building organizational influence.</p><h3>Expert Topics</h3><ul><li>C-suite communication strategies</li><li>Crisis communication management</li><li>Influencing without authority</li></ul>', 'draft', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(14, 'Project Management Fundamentals', 10, 4, 1, '<h2>PM Fundamentals</h2><p>Introduction to project management concepts, methodologies, and tools. Learn the project lifecycle and basic planning techniques.</p><h3>Core Concepts</h3><ul><li>Project lifecycle phases</li><li>Work breakdown structures</li><li>Basic scheduling and Gantt charts</li><li>Stakeholder identification</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(15, 'Agile & Scrum Methodology', 10, 4, 2, '<h2>Agile & Scrum</h2><p>Deep dive into Agile methodology and Scrum framework. Learn sprint planning, daily standups, retrospectives, and backlog management.</p><h3>Agile Practices</h3><ul><li>Scrum roles and ceremonies</li><li>User story writing</li><li>Sprint planning and execution</li><li>Velocity and burndown charts</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(16, 'Program & Portfolio Management', 10, 4, 3, '<h2>Program & Portfolio Management</h2><p>Advanced concepts for managing multiple projects, resource allocation, and strategic alignment of project portfolios.</p><h3>Advanced Topics</h3><ul><li>Portfolio optimization</li><li>Resource capacity planning</li><li>Strategic alignment metrics</li><li>PMO governance</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(17, 'Workplace Code of Conduct', 11, 5, 1, '<h2>Code of Conduct</h2><p>Understanding company policies, ethical standards, and expected workplace behaviors. This mandatory training covers harassment prevention and reporting procedures.</p><h3>Key Policies</h3><ul><li>Anti-harassment policy</li><li>Conflict of interest guidelines</li><li>Reporting mechanisms</li><li>Disciplinary procedures</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(18, 'Data Privacy & GDPR Compliance', 11, 5, 2, '<h2>Data Privacy Compliance</h2><p>Learn about data protection regulations, GDPR requirements, and best practices for handling sensitive information.</p><h3>Compliance Areas</h3><ul><li>GDPR principles and rights</li><li>Data processing requirements</li><li>Breach notification procedures</li><li>Privacy by design</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(19, 'Anti-Money Laundering (AML) Training', 11, 5, 3, '<h2>AML Compliance</h2><p>Advanced compliance training on anti-money laundering regulations, suspicious activity detection, and regulatory reporting requirements.</p><h3>AML Topics</h3><ul><li>KYC (Know Your Customer) procedures</li><li>Red flags and suspicious activities</li><li>SAR filing requirements</li><li>Regulatory framework overview</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(20, 'Customer Service Basics', 12, 6, 1, '<h2>Customer Service Fundamentals</h2><p>Learn the basics of providing excellent customer service, including active listening, empathy, and problem resolution.</p><h3>Core Skills</h3><ul><li>Active listening techniques</li><li>Empathy in customer interactions</li><li>Professional phone etiquette</li><li>Basic problem resolution</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(21, 'Handling Difficult Customers', 12, 6, 2, '<h2>Difficult Customer Management</h2><p>Strategies for de-escalating tense situations, managing complaints, and turning negative experiences into positive outcomes.</p><h3>De-escalation Techniques</h3><ul><li>Recognizing escalation triggers</li><li>Calm communication strategies</li><li>Complaint resolution framework</li><li>When to escalate to management</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(22, 'Customer Experience Strategy', 12, 6, 3, '<h2>CX Strategy Development</h2><p>Design and implement customer experience strategies that drive loyalty and business growth. Learn journey mapping and NPS optimization.</p><h3>Strategic Topics</h3><ul><li>Customer journey mapping</li><li>NPS and satisfaction metrics</li><li>Voice of customer programs</li><li>Service design thinking</li></ul>', 'draft', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(23, 'Introduction to Data Analysis', 13, 7, 1, '<h2>Data Analysis Basics</h2><p>Get started with data analysis using spreadsheets and basic statistical concepts. Learn data cleaning and simple visualization techniques.</p><h3>Foundation Skills</h3><ul><li>Data types and structures</li><li>Basic Excel/Sheets formulas</li><li>Data cleaning best practices</li><li>Creating basic charts</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(24, 'SQL for Data Analysis', 13, 7, 2, '<h2>SQL Data Analysis</h2><p>Master SQL queries for extracting, transforming, and analyzing data from relational databases. Learn joins, aggregations, and subqueries.</p><h3>SQL Topics</h3><ul><li>SELECT statements and filtering</li><li>JOINs and table relationships</li><li>Aggregation functions</li><li>Window functions</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(25, 'Advanced Analytics & Machine Learning', 13, 7, 3, '<h2>Advanced Analytics</h2><p>Explore predictive analytics, machine learning concepts, and advanced statistical modeling for business insights.</p><h3>Advanced Topics</h3><ul><li>Predictive modeling fundamentals</li><li>Regression and classification</li><li>A/B testing and experimentation</li><li>ML model deployment basics</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(26, 'Workplace Safety Orientation', 14, 8, 1, '<h2>Safety Orientation</h2><p>Essential workplace safety training covering emergency procedures, hazard awareness, and personal protective equipment usage.</p><h3>Safety Basics</h3><ul><li>Emergency evacuation procedures</li><li>Fire safety and extinguisher use</li><li>PPE requirements</li><li>Incident reporting</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(27, 'Ergonomics & Workplace Wellness', 14, 8, 2, '<h2>Ergonomics Training</h2><p>Learn proper workstation setup, posture, and wellness practices to prevent repetitive strain injuries and promote health.</p><h3>Wellness Topics</h3><ul><li>Workstation ergonomics</li><li>Stretching and movement breaks</li><li>Eye strain prevention</li><li>Mental health awareness</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(28, 'Safety Management Systems', 14, 8, 3, '<h2>Safety Management</h2><p>Advanced training for safety managers on implementing and maintaining comprehensive safety management systems.</p><h3>Management Topics</h3><ul><li>OSHA compliance requirements</li><li>Safety audit procedures</li><li>Incident investigation methods</li><li>Safety culture development</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(29, 'Digital Tools for Modern Work', 15, 9, 1, '<h2>Digital Tools Overview</h2><p>Introduction to modern digital collaboration tools, cloud services, and productivity applications for the digital workplace.</p><h3>Tools Covered</h3><ul><li>Cloud storage and sharing</li><li>Video conferencing best practices</li><li>Team collaboration platforms</li><li>Digital document management</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(30, 'Process Automation Fundamentals', 15, 9, 2, '<h2>Process Automation</h2><p>Learn how to identify automation opportunities and implement workflow automation using low-code/no-code platforms.</p><h3>Automation Topics</h3><ul><li>Process mapping for automation</li><li>RPA (Robotic Process Automation) basics</li><li>Low-code automation tools</li><li>Measuring automation ROI</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(31, 'Leading Digital Transformation', 15, 9, 3, '<h2>Digital Transformation Leadership</h2><p>Strategic approaches to leading organizational digital transformation initiatives, change management, and building digital-first cultures.</p><h3>Leadership Topics</h3><ul><li>Digital strategy development</li><li>Change management for digital initiatives</li><li>Building digital capabilities</li><li>Measuring transformation success</li></ul>', 'draft', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(32, 'HR Fundamentals for Managers', 16, 10, 1, '<h2>HR Basics for Managers</h2><p>Essential HR knowledge for people managers including hiring basics, performance conversations, and employment law fundamentals.</p><h3>Manager Essentials</h3><ul><li>Interview best practices</li><li>Performance feedback techniques</li><li>Basic employment law</li><li>Documentation requirements</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(33, 'Talent Acquisition Strategies', 16, 10, 2, '<h2>Talent Acquisition</h2><p>Modern recruitment strategies, employer branding, and candidate experience optimization for attracting top talent.</p><h3>Recruitment Topics</h3><ul><li>Job description optimization</li><li>Sourcing strategies</li><li>Employer branding</li><li>Structured interviewing</li></ul>', 'published', 1, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL),
(34, 'Strategic HR Business Partnership', 16, 10, 3, '<h2>HR Business Partnership</h2><p>Advanced HR strategy for business partners including organizational development, workforce planning, and HR analytics.</p><h3>Strategic HR Topics</h3><ul><li>HR metrics and analytics</li><li>Workforce planning</li><li>Organizational design</li><li>Culture transformation</li></ul>', 'archived', 0, '2025-11-28 15:47:30', '2025-11-28 15:47:30', NULL);

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
-- Indexes for table `course_requests`
--
ALTER TABLE `course_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_requests_employee_id_status_index` (`employee_id`,`status`),
  ADD KEY `course_requests_course_id_status_index` (`course_id`,`status`),
  ADD KEY `course_requests_status_index` (`status`);

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
-- Indexes for table `training_assignments`
--
ALTER TABLE `training_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `training_assignments_status_start_date_index` (`status`,`start_date`),
  ADD KEY `training_assignments_due_date_index` (`due_date`),
  ADD KEY `training_assignments_training_catalog_id_index` (`training_catalog_id`);

--
-- Indexes for table `training_assignment_employees`
--
ALTER TABLE `training_assignment_employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_assignment_employee` (`training_assignment_id`,`employee_id`),
  ADD KEY `training_assignment_employees_employee_id_index` (`employee_id`),
  ADD KEY `training_assignment_employees_status_index` (`status`),
  ADD KEY `training_assignment_employees_assigned_at_index` (`assigned_at`);

--
-- Indexes for table `training_assignment_materials`
--
ALTER TABLE `training_assignment_materials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_assignment_material` (`training_assignment_id`,`training_material_id`),
  ADD KEY `training_assignment_materials_training_material_id_index` (`training_material_id`),
  ADD KEY `training_assignment_materials_order_sequence_index` (`order_sequence`);

--
-- Indexes for table `training_catalogs`
--
ALTER TABLE `training_catalogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `training_catalogs_title_index` (`title`),
  ADD KEY `training_catalogs_is_active_index` (`is_active`),
  ADD KEY `training_catalogs_framework_id_index` (`framework_id`);

--
-- Indexes for table `training_materials`
--
ALTER TABLE `training_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `training_materials_training_catalog_id_status_index` (`training_catalog_id`,`status`),
  ADD KEY `training_materials_competency_id_proficiency_level_index` (`competency_id`,`proficiency_level`),
  ADD KEY `training_materials_is_active_index` (`is_active`),
  ADD KEY `training_materials_deleted_at_index` (`deleted_at`);

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
-- AUTO_INCREMENT for table `course_requests`
--
ALTER TABLE `course_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training_assignments`
--
ALTER TABLE `training_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `training_assignment_employees`
--
ALTER TABLE `training_assignment_employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `training_assignment_materials`
--
ALTER TABLE `training_assignment_materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `training_catalogs`
--
ALTER TABLE `training_catalogs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `training_materials`
--
ALTER TABLE `training_materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `training_assignments`
--
ALTER TABLE `training_assignments`
  ADD CONSTRAINT `training_assignments_training_catalog_id_foreign` FOREIGN KEY (`training_catalog_id`) REFERENCES `training_catalogs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `training_assignment_employees`
--
ALTER TABLE `training_assignment_employees`
  ADD CONSTRAINT `training_assignment_employees_training_assignment_id_foreign` FOREIGN KEY (`training_assignment_id`) REFERENCES `training_assignments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `training_assignment_materials`
--
ALTER TABLE `training_assignment_materials`
  ADD CONSTRAINT `training_assignment_materials_training_assignment_id_foreign` FOREIGN KEY (`training_assignment_id`) REFERENCES `training_assignments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `training_assignment_materials_training_material_id_foreign` FOREIGN KEY (`training_material_id`) REFERENCES `training_materials` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
