-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2025 at 03:11 PM
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
-- Database: `competency_managements`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessment_schedules`
--

CREATE TABLE `assessment_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `assessment_type` enum('individual','comprehensive','practical','feedback') NOT NULL,
  `scheduled_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('scheduled','in_progress','completed','cancelled','rescheduled') NOT NULL DEFAULT 'scheduled',
  `scheduled_by` bigint(20) UNSIGNED DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assigned_competencies`
--

CREATE TABLE `assigned_competencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `competency_id` bigint(20) UNSIGNED NOT NULL,
  `framework_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assignment_type` enum('development','gap_closure','skill_enhancement','mandatory') NOT NULL DEFAULT 'development',
  `proficiency_level` enum('beginner','intermediate','advanced','expert') DEFAULT NULL,
  `priority` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `target_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('assigned','in_progress','completed','on_hold','cancelled') NOT NULL DEFAULT 'assigned',
  `progress_percentage` int(11) NOT NULL DEFAULT 0,
  `assigned_by` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assigned_competencies`
--

INSERT INTO `assigned_competencies` (`id`, `employee_id`, `employee_name`, `job_title`, `competency_id`, `framework_id`, `assignment_type`, `proficiency_level`, `priority`, `target_date`, `notes`, `status`, `progress_percentage`, `assigned_by`, `assigned_at`, `started_at`, `completed_at`, `created_at`, `updated_at`) VALUES
(3, 'EMP-001', 'Juan Dela Cruz', 'Payroll Specialist', 75, 13, 'gap_closure', NULL, 'high', NULL, 'Skill gap assigned from Role Mapping analysis - Assignment Skills (High priority)', 'assigned', 0, 3, '2025-11-29 09:24:30', NULL, NULL, '2025-11-29 09:24:30', '2025-11-29 09:24:30'),
(4, 'EMP-002', 'Maria Santos', 'HR Officer', 92, 17, 'gap_closure', NULL, 'high', '2025-12-13', 'gambarwe', 'assigned', 0, 3, '2025-12-06 05:55:35', NULL, NULL, '2025-12-06 05:55:35', '2025-12-06 05:55:35'),
(5, 'EMP-002', 'Maria Santos', 'HR Officer', 90, 17, 'gap_closure', NULL, 'high', '2025-12-13', 'gambarwe', 'assigned', 0, 3, '2025-12-06 05:55:36', NULL, NULL, '2025-12-06 05:55:36', '2025-12-06 05:55:36');

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
-- Table structure for table `competencies`
--

CREATE TABLE `competencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `competency_id` varchar(255) DEFAULT NULL,
  `competency_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `framework_id` bigint(20) UNSIGNED NOT NULL,
  `proficiency_levels` int(11) NOT NULL DEFAULT 5,
  `status` enum('active','inactive','draft','archived') NOT NULL DEFAULT 'draft',
  `behavioral_indicators` text DEFAULT NULL,
  `assessment_criteria` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `competencies`
--

INSERT INTO `competencies` (`id`, `competency_id`, `competency_name`, `description`, `category`, `framework_id`, `proficiency_levels`, `status`, `behavioral_indicators`, `assessment_criteria`, `notes`, `created_at`, `updated_at`) VALUES
(58, 'CMP-001', 'Strategic Thinking', 'Ability to develop long-term strategies, anticipate future trends, and align organizational goals with business objectives.', NULL, 9, 5, 'active', 'Analyzes market trends; Creates actionable strategic plans; Aligns team objectives with company vision; Identifies growth opportunities', 'Strategic plan development; Business case creation; Long-term goal achievement rate', 'Core competency for senior leadership roles', '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(59, 'CMP-002', 'Team Leadership', 'Ability to lead, motivate, and develop team members while fostering a positive and productive work environment.', NULL, 9, 5, 'active', 'Provides regular feedback; Delegates effectively; Resolves team conflicts; Mentors team members', 'Team performance metrics; Employee engagement scores; Retention rates', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(60, 'CMP-003', 'Decision Making', 'Ability to make timely, well-informed decisions by analyzing data, evaluating risks, and considering stakeholder impact.', NULL, 9, 5, 'active', 'Gathers relevant information; Weighs pros and cons; Makes timely decisions; Takes accountability for outcomes', 'Decision quality assessment; Time to decision; Stakeholder satisfaction', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(61, 'CMP-004', 'Change Management', 'Ability to lead organizational change initiatives, manage resistance, and ensure successful adoption of new processes.', NULL, 9, 5, 'active', 'Communicates change vision; Addresses resistance; Supports transition; Measures adoption', 'Change adoption rates; Employee feedback; Project success metrics', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(62, 'CMP-005', 'Software Development', 'Proficiency in programming languages, software design patterns, and development methodologies.', NULL, 10, 5, 'active', 'Writes clean, maintainable code; Follows coding standards; Participates in code reviews; Implements best practices', 'Code quality metrics; Bug rates; Peer review feedback; Technical interviews', 'Core competency for developers and engineers', '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(63, 'CMP-006', 'Database Management', 'Skills in database design, optimization, querying, and administration across various database systems.', NULL, 10, 5, 'active', 'Designs efficient schemas; Writes optimized queries; Implements backup strategies; Monitors performance', 'Query performance; Data integrity; System uptime; Technical assessments', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(64, 'CMP-007', 'Cloud Computing', 'Knowledge of cloud platforms, services, deployment strategies, and cloud architecture best practices.', NULL, 10, 5, 'active', 'Deploys cloud solutions; Manages cloud resources; Implements security best practices; Optimizes costs', 'Cloud certifications; Project implementations; Cost optimization metrics', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(65, 'CMP-008', 'System Architecture', 'Ability to design scalable, reliable, and secure system architectures that meet business requirements.', NULL, 10, 5, 'active', 'Creates architecture diagrams; Evaluates technology options; Ensures scalability; Documents designs', 'Architecture reviews; System performance; Technical documentation quality', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(66, 'CMP-009', 'Verbal Communication', 'Ability to express ideas clearly and effectively in spoken communication across various settings.', NULL, 11, 5, 'active', 'Speaks clearly; Adapts communication style; Engages audience; Handles Q&A effectively', 'Presentation feedback; Meeting effectiveness; Peer assessments', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(67, 'CMP-010', 'Written Communication', 'Proficiency in creating clear, professional written documents including emails, reports, and proposals.', NULL, 11, 5, 'active', 'Writes clearly; Uses appropriate tone; Proofreads work; Structures documents logically', 'Document quality reviews; Writing samples; Feedback from stakeholders', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(68, 'CMP-011', 'Active Listening', 'Ability to fully focus on, understand, and respond appropriately to verbal and non-verbal communication.', NULL, 11, 5, 'active', 'Maintains eye contact; Asks clarifying questions; Paraphrases for understanding; Shows empathy', '360-degree feedback; Stakeholder surveys; Observation assessments', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(69, 'CMP-012', 'Conflict Resolution', 'Skills in managing and resolving conflicts constructively while maintaining professional relationships.', NULL, 11, 5, 'active', 'Identifies conflict sources; Mediates discussions; Finds common ground; Follows up on resolutions', 'Conflict resolution outcomes; Team feedback; HR incident tracking', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(70, 'CMP-013', 'Customer Relationship Management', 'Ability to build and maintain strong, positive relationships with customers throughout their journey.', NULL, 12, 5, 'active', 'Builds rapport quickly; Maintains regular contact; Anticipates needs; Handles escalations professionally', 'Customer satisfaction scores; Retention rates; NPS scores', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(71, 'CMP-014', 'Problem Resolution', 'Skills in identifying, analyzing, and resolving customer issues efficiently and effectively.', NULL, 12, 5, 'active', 'Identifies root causes; Provides timely solutions; Follows up; Documents issues', 'First call resolution rate; Resolution time; Customer feedback', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(72, 'CMP-015', 'Service Excellence', 'Commitment to delivering exceptional service that exceeds customer expectations consistently.', NULL, 12, 5, 'active', 'Goes above and beyond; Personalizes service; Maintains professionalism; Seeks feedback', 'Service quality metrics; Customer testimonials; Mystery shopper scores', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(73, 'CMP-016', 'Product Knowledge', 'Deep understanding of company products, services, features, and how they benefit customers.', NULL, 12, 5, 'active', 'Explains features clearly; Recommends appropriate solutions; Stays updated on changes; Trains others', 'Product knowledge tests; Sales conversion rates; Customer feedback', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(74, 'CMP-017', 'Project Planning', 'Ability to create comprehensive project plans including scope, timeline, resources, and risk management.', NULL, 13, 5, 'active', 'Defines clear objectives; Creates detailed timelines; Allocates resources effectively; Identifies risks', 'Plan quality reviews; Project success rates; Stakeholder feedback', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(75, 'CMP-018', 'Stakeholder Management', 'Skills in identifying, engaging, and managing expectations of all project stakeholders.', NULL, 13, 5, 'active', 'Identifies all stakeholders; Communicates regularly; Manages expectations; Addresses concerns', 'Stakeholder satisfaction surveys; Communication effectiveness; Conflict frequency', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(76, 'CMP-019', 'Agile Methodology', 'Proficiency in Agile frameworks including Scrum, Kanban, and iterative development practices.', NULL, 13, 5, 'active', 'Facilitates ceremonies; Manages backlog; Tracks velocity; Promotes continuous improvement', 'Sprint completion rates; Team velocity; Retrospective outcomes', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(77, 'CMP-020', 'Risk Management', 'Ability to identify, assess, and mitigate project risks throughout the project lifecycle.', NULL, 13, 5, 'active', 'Conducts risk assessments; Creates mitigation plans; Monitors risks; Escalates appropriately', 'Risk identification accuracy; Mitigation effectiveness; Issue occurrence rates', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(78, 'CMP-021', 'Data Analysis', 'Skills in collecting, processing, and analyzing data to extract meaningful insights and support decision-making.', NULL, 14, 5, 'active', 'Cleans data effectively; Applies appropriate methods; Interprets results accurately; Communicates findings', 'Analysis quality; Insight accuracy; Business impact of recommendations', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(79, 'CMP-022', 'Data Visualization', 'Ability to create compelling visual representations of data that communicate insights effectively.', NULL, 14, 5, 'active', 'Chooses appropriate chart types; Creates clear dashboards; Tells data stories; Uses tools proficiently', 'Dashboard usage metrics; Stakeholder feedback; Visualization best practices adherence', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(80, 'CMP-023', 'Statistical Methods', 'Knowledge of statistical techniques for data analysis, hypothesis testing, and predictive modeling.', NULL, 14, 5, 'active', 'Applies correct statistical tests; Interprets significance; Validates assumptions; Documents methodology', 'Statistical accuracy; Model performance; Peer reviews', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(81, 'CMP-024', 'Business Intelligence', 'Proficiency in BI tools and techniques to transform data into actionable business insights.', NULL, 14, 5, 'active', 'Builds effective reports; Automates data pipelines; Maintains data quality; Supports self-service analytics', 'Report accuracy; User adoption; Data freshness', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(82, 'CMP-025', 'Regulatory Knowledge', 'Understanding of relevant laws, regulations, and industry standards that apply to the organization.', NULL, 15, 5, 'active', 'Stays current with regulations; Interprets requirements; Advises on compliance; Monitors changes', 'Compliance audit results; Regulatory knowledge tests; Advisory quality', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(83, 'CMP-026', 'Risk Assessment', 'Ability to identify, evaluate, and prioritize organizational risks across various domains.', NULL, 15, 5, 'active', 'Conducts thorough assessments; Quantifies risks; Prioritizes appropriately; Documents findings', 'Risk identification accuracy; Assessment quality; Audit findings', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(84, 'CMP-027', 'Policy Development', 'Skills in creating, implementing, and maintaining organizational policies and procedures.', NULL, 15, 5, 'active', 'Drafts clear policies; Ensures stakeholder input; Communicates changes; Reviews regularly', 'Policy compliance rates; User understanding; Audit compliance', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(85, 'CMP-028', 'Ethics & Integrity', 'Commitment to ethical conduct, integrity, and promoting a culture of compliance.', NULL, 15, 5, 'active', 'Models ethical behavior; Reports violations; Supports investigations; Promotes awareness', 'Ethics training completion; Incident reports; Culture surveys', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(86, 'CMP-029', 'Talent Acquisition', 'Skills in attracting, evaluating, and hiring top talent that fits organizational needs and culture.', NULL, 16, 5, 'active', 'Sources effectively; Conducts structured interviews; Evaluates cultural fit; Manages candidate experience', 'Time to hire; Quality of hire; Candidate satisfaction; Diversity metrics', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(87, 'CMP-030', 'Performance Management', 'Ability to implement and manage performance evaluation systems that drive employee development.', NULL, 16, 5, 'active', 'Sets clear expectations; Provides regular feedback; Conducts fair evaluations; Supports improvement', 'Appraisal completion rates; Employee satisfaction; Performance improvement outcomes', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(88, 'CMP-031', 'Employee Relations', 'Skills in maintaining positive employee relations, handling grievances, and fostering workplace harmony.', NULL, 16, 5, 'active', 'Handles complaints fairly; Mediates disputes; Maintains confidentiality; Promotes positive culture', 'Employee engagement scores; Grievance resolution rates; Turnover rates', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(89, 'CMP-032', 'Learning & Development', 'Ability to design, implement, and evaluate training programs that develop employee skills.', NULL, 16, 5, 'active', 'Identifies training needs; Designs effective programs; Measures impact; Supports career development', 'Training effectiveness; Skill development metrics; Career progression rates', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(90, 'CMP-033', 'Financial Analysis', 'Ability to analyze financial data, identify trends, and provide insights for business decisions.', NULL, 17, 5, 'active', 'Interprets financial statements; Performs ratio analysis; Identifies variances; Provides recommendations', 'Analysis accuracy; Insight quality; Decision support effectiveness', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(91, 'CMP-034', 'Budgeting & Forecasting', 'Skills in developing accurate budgets and financial forecasts that support strategic planning.', NULL, 17, 5, 'active', 'Creates realistic budgets; Tracks variances; Updates forecasts; Collaborates with stakeholders', 'Budget accuracy; Forecast reliability; Variance explanations', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(92, 'CMP-035', 'Accounting Standards', 'Knowledge of accounting principles, standards (GAAP/IFRS), and proper financial reporting.', NULL, 17, 5, 'active', 'Applies standards correctly; Ensures accurate reporting; Stays current with changes; Documents properly', 'Audit results; Reporting accuracy; Compliance rates', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(93, 'CMP-036', 'Internal Controls', 'Ability to design, implement, and monitor internal controls to safeguard assets and ensure accuracy.', NULL, 17, 5, 'active', 'Identifies control needs; Implements procedures; Tests effectiveness; Reports deficiencies', 'Control testing results; Audit findings; Risk mitigation effectiveness', NULL, '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(94, 'CMP-037', 'Digital Strategy', 'Ability to develop and execute digital transformation strategies aligned with business objectives.', NULL, 18, 5, 'draft', 'Identifies digital opportunities; Creates roadmaps; Aligns with business goals; Measures success', 'Strategy execution; Digital adoption rates; Business impact metrics', 'Under development', '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(95, 'CMP-038', 'Innovation Management', 'Skills in fostering innovation, evaluating new technologies, and driving continuous improvement.', NULL, 18, 5, 'draft', 'Encourages experimentation; Evaluates innovations; Implements improvements; Manages failures constructively', 'Innovation pipeline; Implementation success; ROI on innovations', 'Under development', '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(96, 'CMP-039', 'Technology Adoption', 'Ability to evaluate, implement, and drive adoption of new technologies across the organization.', NULL, 18, 5, 'draft', 'Assesses technology fit; Plans implementations; Trains users; Monitors adoption', 'Adoption rates; User proficiency; Technology utilization', 'Under development', '2025-11-29 05:23:39', '2025-11-29 05:23:39'),
(97, 'CMP-040', 'Digital Literacy', 'Foundational skills in using digital tools, platforms, and technologies effectively.', NULL, 18, 5, 'draft', 'Uses digital tools proficiently; Adapts to new platforms; Supports others; Maintains security awareness', 'Tool proficiency assessments; Productivity metrics; Security compliance', 'Under development', '2025-11-29 05:23:39', '2025-11-29 05:23:39');

-- --------------------------------------------------------

--
-- Table structure for table `competency_assignments`
--

CREATE TABLE `competency_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `competency_id` varchar(255) NOT NULL,
  `competency_name` varchar(255) NOT NULL,
  `action_type` enum('critical','training','mentoring') NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','in_progress','completed') NOT NULL DEFAULT 'pending',
  `assigned_by` bigint(20) UNSIGNED NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `competency_frameworks`
--

CREATE TABLE `competency_frameworks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `framework_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `effective_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','inactive','draft','archived') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `competency_frameworks`
--

INSERT INTO `competency_frameworks` (`id`, `framework_name`, `description`, `effective_date`, `end_date`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(9, 'Leadership & Management', 'Competencies required for leadership roles including strategic thinking, team management, decision-making, and organizational development skills.', '2025-05-29', NULL, 'active', 'Core framework for all management and leadership positions across the organization.', '2025-11-28 17:33:31', '2025-11-28 17:33:31'),
(10, 'Technical Skills', 'Technical competencies covering software development, IT infrastructure, data management, and technology-related skills across all proficiency levels.', '2025-05-29', NULL, 'active', 'Framework for IT, Engineering, and Technical departments.', '2025-11-28 17:33:31', '2025-11-28 17:33:31'),
(11, 'Communication & Interpersonal', 'Competencies focused on verbal and written communication, presentation skills, active listening, and building effective professional relationships.', '2025-07-29', NULL, 'active', 'Applicable to all employees regardless of department or role.', '2025-11-28 17:33:31', '2025-11-28 17:33:31'),
(12, 'Customer Service', 'Competencies for customer-facing roles including customer relationship management, problem resolution, and service excellence.', '2025-08-29', NULL, 'active', 'Primary framework for Customer Support, Sales, and Account Management teams.', '2025-11-28 17:33:31', '2025-11-28 17:33:31'),
(13, 'Project Management', 'Competencies related to project planning, execution, monitoring, risk management, and stakeholder communication.', '2025-06-29', NULL, 'active', 'For project managers, team leads, and anyone involved in project delivery.', '2025-11-28 17:33:31', '2025-11-28 17:33:31'),
(14, 'Data & Analytics', 'Competencies for data analysis, statistical methods, business intelligence tools, and data-driven decision making.', '2025-09-29', NULL, 'active', 'Framework for Data Analysts, BI Specialists, and Data Scientists.', '2025-11-28 17:33:31', '2025-11-28 17:33:31'),
(15, 'Compliance & Risk', 'Competencies covering regulatory compliance, risk assessment, policy adherence, and ethical business practices.', '2025-07-29', NULL, 'active', 'Essential for Legal, Compliance, and Risk Management departments.', '2025-11-28 17:33:31', '2025-11-28 17:33:31'),
(16, 'Human Resources', 'Competencies for HR professionals including talent acquisition, employee relations, performance management, and organizational development.', '2025-08-29', NULL, 'active', 'Core framework for all HR department roles.', '2025-11-28 17:33:31', '2025-11-28 17:33:31'),
(17, 'Finance & Accounting', 'Competencies related to financial management, accounting principles, budgeting, forecasting, and financial analysis.', '2025-06-29', NULL, 'active', 'Framework for Finance, Accounting, and Treasury departments.', '2025-11-28 17:33:31', '2025-11-28 17:33:31'),
(18, 'Digital Transformation', 'Competencies for driving digital initiatives, change management, innovation, and adopting new technologies.', '2025-10-29', NULL, 'draft', 'New framework under development for digital transformation initiatives.', '2025-11-28 17:33:31', '2025-11-28 17:33:31');

-- --------------------------------------------------------

--
-- Table structure for table `development_plans`
--

CREATE TABLE `development_plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `plan_title` varchar(255) NOT NULL,
  `objectives` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`objectives`)),
  `timeline` varchar(255) NOT NULL,
  `resources` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`resources`)),
  `status` enum('active','completed','on_hold','cancelled') NOT NULL DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `target_date` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `job_role` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_skills_assessments`
--

CREATE TABLE `employee_skills_assessments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` int(11) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `competency_id` bigint(20) UNSIGNED NOT NULL,
  `current_level` enum('Beginner','Intermediate','Advanced','Expert','Master') NOT NULL DEFAULT 'Beginner',
  `assessment_method` enum('self_assessment','manager_assessment','skill_test','performance_review') NOT NULL DEFAULT 'self_assessment',
  `assessed_by` varchar(255) DEFAULT NULL,
  `assessment_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('active','outdated','pending_review') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
-- Table structure for table `gap_analyses`
--

CREATE TABLE `gap_analyses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `competency_id` bigint(20) UNSIGNED NOT NULL,
  `framework` varchar(255) NOT NULL,
  `proficiency_level` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `assessment_date` date DEFAULT NULL,
  `status` enum('pending','in_progress','completed','on_hold') NOT NULL DEFAULT 'pending',
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
(11, '0001_01_01_000000_create_users_table', 1),
(12, '0001_01_01_000001_create_cache_table', 1),
(13, '0001_01_01_000002_create_jobs_table', 1),
(14, '2025_09_19_145529_add_two_factor_columns_to_users_table', 1),
(15, '2025_09_19_145723_create_personal_access_tokens_table', 1),
(16, '2025_09_23_035036_create_competency_frameworks_table', 1),
(17, '2025_09_24_144657_create_competencies_table', 1),
(18, '2025_09_26_070711_create_role_mappings_table', 1),
(19, '2025_09_27_145722_create_employees_table', 1),
(20, '2025_09_27_173727_create_gap_analyses_table', 1),
(21, '2025_10_04_213000_add_missing_gap_analysis_fields', 2),
(22, '2025_10_04_214500_remove_employee_foreign_key_from_gap_analyses', 3);

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
-- Table structure for table `role_mappings`
--

CREATE TABLE `role_mappings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_name` varchar(255) NOT NULL,
  `framework_id` bigint(20) UNSIGNED NOT NULL,
  `competency_id` bigint(20) UNSIGNED NOT NULL,
  `proficiency_level` enum('Beginner','Intermediate','Advanced','Expert','Master') NOT NULL DEFAULT 'Beginner',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `notes` text DEFAULT NULL,
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
-- Table structure for table `skill_gap_assignments`
--

CREATE TABLE `skill_gap_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `competency_key` varchar(255) NOT NULL,
  `action_type` enum('critical','training','mentoring') NOT NULL DEFAULT 'training',
  `notes` text DEFAULT NULL,
  `status` enum('pending','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending',
  `assigned_by` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skill_gap_assignments`
--

INSERT INTO `skill_gap_assignments` (`id`, `employee_id`, `employee_name`, `job_title`, `competency_key`, `action_type`, `notes`, `status`, `assigned_by`, `assigned_at`, `completed_at`, `created_at`, `updated_at`) VALUES
(10, 'EMP-001', 'Juan Dela Cruz', 'Payroll Specialist', 'assignment_skills', 'critical', 'Assignment Skills - High priority skill gap identified from Role Mapping analysis', 'completed', 3, '2025-11-29 13:26:34', '2025-12-03 15:18:28', '2025-11-29 13:26:34', '2025-12-03 15:18:28'),
(11, 'EMP-002', 'Maria Santos', 'HR Officer', 'accountability', 'critical', 'Accountability - High priority skill gap identified from Role Mapping analysis', 'completed', 3, '2025-12-06 05:39:28', '2025-12-06 06:41:11', '2025-12-06 05:39:28', '2025-12-06 06:41:11');

-- --------------------------------------------------------

--
-- Table structure for table `trabahador`
--

CREATE TABLE `trabahador` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `emplyment_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for table `assessment_schedules`
--
ALTER TABLE `assessment_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assessment_schedules_employee_id_index` (`employee_id`),
  ADD KEY `assessment_schedules_scheduled_date_index` (`scheduled_date`),
  ADD KEY `assessment_schedules_status_index` (`status`);

--
-- Indexes for table `assigned_competencies`
--
ALTER TABLE `assigned_competencies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_competencies_status_index` (`status`),
  ADD KEY `assigned_competencies_assignment_type_index` (`assignment_type`),
  ADD KEY `assigned_competencies_priority_index` (`priority`),
  ADD KEY `assigned_competencies_target_date_index` (`target_date`),
  ADD KEY `assigned_competencies_employee_id_competency_id_index` (`employee_id`,`competency_id`),
  ADD KEY `assigned_competencies_employee_id_index` (`employee_id`),
  ADD KEY `assigned_competencies_competency_id_index` (`competency_id`),
  ADD KEY `assigned_competencies_framework_id_index` (`framework_id`);

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
-- Indexes for table `competencies`
--
ALTER TABLE `competencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `competencies_competency_id_unique` (`competency_id`),
  ADD KEY `competencies_status_index` (`status`),
  ADD KEY `competencies_framework_id_index` (`framework_id`),
  ADD KEY `competencies_competency_name_index` (`competency_name`);

--
-- Indexes for table `competency_assignments`
--
ALTER TABLE `competency_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `competency_assignments_employee_id_index` (`employee_id`),
  ADD KEY `competency_assignments_competency_id_index` (`competency_id`),
  ADD KEY `competency_assignments_status_index` (`status`);

--
-- Indexes for table `competency_frameworks`
--
ALTER TABLE `competency_frameworks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `competency_frameworks_status_index` (`status`),
  ADD KEY `competency_frameworks_effective_date_index` (`effective_date`),
  ADD KEY `competency_frameworks_framework_name_index` (`framework_name`);

--
-- Indexes for table `development_plans`
--
ALTER TABLE `development_plans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `development_plans_employee_id_index` (`employee_id`),
  ADD KEY `development_plans_status_index` (`status`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_email_unique` (`email`);

--
-- Indexes for table `employee_skills_assessments`
--
ALTER TABLE `employee_skills_assessments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_employee_competency_assessment` (`employee_id`,`competency_id`),
  ADD KEY `employee_skills_assessments_competency_id_foreign` (`competency_id`),
  ADD KEY `employee_skills_assessments_employee_id_competency_id_index` (`employee_id`,`competency_id`),
  ADD KEY `employee_skills_assessments_assessment_date_index` (`assessment_date`),
  ADD KEY `employee_skills_assessments_status_index` (`status`),
  ADD KEY `employee_skills_assessments_job_title_index` (`job_title`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `gap_analyses`
--
ALTER TABLE `gap_analyses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gap_analyses_employee_id_foreign` (`employee_id`),
  ADD KEY `gap_analyses_competency_id_foreign` (`competency_id`);

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
-- Indexes for table `role_mappings`
--
ALTER TABLE `role_mappings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_role_framework_competency` (`role_name`,`framework_id`,`competency_id`),
  ADD KEY `role_mappings_competency_id_foreign` (`competency_id`),
  ADD KEY `role_mappings_status_index` (`status`),
  ADD KEY `role_mappings_role_name_index` (`role_name`),
  ADD KEY `role_mappings_framework_id_competency_id_index` (`framework_id`,`competency_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `skill_gap_assignments`
--
ALTER TABLE `skill_gap_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `skill_gap_assignments_employee_id_index` (`employee_id`),
  ADD KEY `skill_gap_assignments_status_index` (`status`),
  ADD KEY `skill_gap_assignments_action_type_index` (`action_type`);

--
-- Indexes for table `trabahador`
--
ALTER TABLE `trabahador`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `assessment_schedules`
--
ALTER TABLE `assessment_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assigned_competencies`
--
ALTER TABLE `assigned_competencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `competencies`
--
ALTER TABLE `competencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `competency_assignments`
--
ALTER TABLE `competency_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `competency_frameworks`
--
ALTER TABLE `competency_frameworks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `development_plans`
--
ALTER TABLE `development_plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employee_skills_assessments`
--
ALTER TABLE `employee_skills_assessments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gap_analyses`
--
ALTER TABLE `gap_analyses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role_mappings`
--
ALTER TABLE `role_mappings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `skill_gap_assignments`
--
ALTER TABLE `skill_gap_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `trabahador`
--
ALTER TABLE `trabahador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assigned_competencies`
--
ALTER TABLE `assigned_competencies`
  ADD CONSTRAINT `assigned_competencies_competency_id_foreign` FOREIGN KEY (`competency_id`) REFERENCES `competencies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assigned_competencies_framework_id_foreign` FOREIGN KEY (`framework_id`) REFERENCES `competency_frameworks` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `competencies`
--
ALTER TABLE `competencies`
  ADD CONSTRAINT `competencies_framework_id_foreign` FOREIGN KEY (`framework_id`) REFERENCES `competency_frameworks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_skills_assessments`
--
ALTER TABLE `employee_skills_assessments`
  ADD CONSTRAINT `employee_skills_assessments_competency_id_foreign` FOREIGN KEY (`competency_id`) REFERENCES `competencies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gap_analyses`
--
ALTER TABLE `gap_analyses`
  ADD CONSTRAINT `gap_analyses_competency_id_foreign` FOREIGN KEY (`competency_id`) REFERENCES `competencies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_mappings`
--
ALTER TABLE `role_mappings`
  ADD CONSTRAINT `role_mappings_competency_id_foreign` FOREIGN KEY (`competency_id`) REFERENCES `competencies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_mappings_framework_id_foreign` FOREIGN KEY (`framework_id`) REFERENCES `competency_frameworks` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
