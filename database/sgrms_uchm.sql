-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 02, 2025 at 03:55 AM
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
-- Database: `sgrms_uchm`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `a_id` varchar(50) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `office_location` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`a_id`, `user_id`, `office_location`) VALUES
('MA25-A001', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `link` text DEFAULT NULL,
  `category` enum('Announcement','News','Event') NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `date_posted` date NOT NULL,
  `start_datetime` datetime DEFAULT NULL,
  `end_datetime` datetime DEFAULT NULL,
  `status` enum('active','archived') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `user_id`, `title`, `description`, `link`, `category`, `image`, `date_posted`, `start_datetime`, `end_datetime`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Sit veritatis porro', 'Voluptas soluta volu', 'https://www.quqikenuryk.ws', 'Announcement', 'default.png', '2025-09-01', NULL, NULL, 'active', '2025-08-31 21:18:23', '2025-09-01 10:40:07'),
(2, 1, 'Nisi aut laboriosam', 'Ut et consectetur v', 'https://www.jyqewyhuf.biz', 'Event', 'default.png', '2025-09-01', '2025-09-04 07:29:00', '2025-09-06 19:29:00', 'active', '2025-08-31 23:29:20', '2025-09-01 10:40:09'),
(3, 1, 'Omnis voluptatibus c', 'Nihil et voluptates', 'https://www.vutuzuruzetuv.cc', 'News', 'default.png', '2025-09-01', NULL, NULL, 'active', '2025-09-01 02:38:23', '2025-09-01 10:40:11');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `requester_id` bigint(20) UNSIGNED NOT NULL,
  `requester_type` enum('Parent','Student') NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `counselor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `appointment_type` enum('Personal Counseling','Career Guidance','Academic Counseling','Conflict Resolution','Family-Related Counseling','Behavioral Counseling','Peer Relationship Counseling','Other','Follow-Up Counseling') NOT NULL,
  `reason` text DEFAULT NULL,
  `appointment_datetime` datetime NOT NULL,
  `location` varchar(255) DEFAULT 'School Guidance Office',
  `status` enum('Pending','Approved','Cancelled','Completed') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `requester_id`, `requester_type`, `student_id`, `counselor_id`, `appointment_type`, `reason`, `appointment_datetime`, `location`, `status`) VALUES
(1, 14, 'Parent', 3, 1, 'Academic Counseling', NULL, '2025-08-28 10:00:00', 'School Guidance Office', 'Approved'),
(2, 15, 'Parent', 3, 1, 'Personal Counseling', NULL, '2025-08-30 14:00:00', 'School Guidance Office', 'Approved'),
(3, 13, 'Parent', 3, 1, 'Follow-Up Counseling', NULL, '2025-08-28 09:00:00', 'School Guidance Office', 'Approved');

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
-- Table structure for table `cases`
--

CREATE TABLE `cases` (
  `case_id` bigint(20) UNSIGNED NOT NULL,
  `case_type_id` bigint(20) UNSIGNED NOT NULL,
  `presenting_problem` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `severity` enum('Low','Intermediate','Severe') NOT NULL,
  `witnesses` text DEFAULT NULL,
  `investigation_notes` text DEFAULT NULL,
  `evidence` text DEFAULT NULL,
  `filed_date` date NOT NULL DEFAULT curdate(),
  `filed_time` time NOT NULL DEFAULT curtime(),
  `status` enum('Pending','Under Investigation','Resolved') NOT NULL DEFAULT 'Pending',
  `action_taken` varchar(255) DEFAULT NULL,
  `resolution_notes` text DEFAULT NULL,
  `resolved_date` date DEFAULT NULL,
  `follow_up_date` date DEFAULT NULL,
  `archived` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `case_students`
--

CREATE TABLE `case_students` (
  `case_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `case_types`
--

CREATE TABLE `case_types` (
  `type_id` bigint(20) UNSIGNED NOT NULL,
  `type_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `counseling_notes`
--

CREATE TABLE `counseling_notes` (
  `note_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `observations` text DEFAULT NULL,
  `recommendations` text DEFAULT NULL,
  `follow_up_needed` tinyint(1) DEFAULT 0,
  `follow_up_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `counselors`
--

CREATE TABLE `counselors` (
  `c_id` varchar(50) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `counselors`
--

INSERT INTO `counselors` (`c_id`, `user_id`) VALUES
('MA25-C001', 2),
('MA25-C002', 3);

-- --------------------------------------------------------

--
-- Table structure for table `educ_levels`
--

CREATE TABLE `educ_levels` (
  `e_id` bigint(20) UNSIGNED NOT NULL,
  `educ_level` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `educ_levels`
--

INSERT INTO `educ_levels` (`e_id`, `educ_level`, `created_at`, `updated_at`) VALUES
(1, 'Junior High School', '2025-08-29 06:12:25', '2025-08-29 06:12:25'),
(2, 'Senior High School', NULL, NULL),
(3, 'Elementary', NULL, NULL),
(4, 'Kindergarten', '2025-08-29 17:33:45', '2025-08-29 17:33:45');

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
(11, '2025_06_07_215720_create_appointment_table', 3),
(29, '0001_01_01_000000_create_users_table', 4),
(30, '0001_01_01_000001_create_cache_table', 4),
(31, '0001_01_01_000002_create_jobs_table', 4),
(32, '2025_05_30_000549_update_users_table_add_custom_fields', 4),
(33, '2025_05_30_001308_add_status_to_users_table', 4),
(34, '2025_06_07_211627_create_counselors_table', 4),
(35, '2025_06_07_213141_create_notifications_table', 4),
(36, '2025_06_07_213540_create_parents_table', 4),
(37, '2025_06_07_215508_create_students_table', 4),
(38, '2025_06_07_215613_create_case_records_table', 4),
(39, '2025_06_07_215721_create_appointment_history_table', 4),
(40, '2025_06_07_221250_create_activity_logs_table', 4),
(41, '2025_06_08_055449_create_appointments_table', 4),
(43, '2025_07_22_155641_change_educ_level_type_in_students_table', 5),
(44, '2025_07_22_162807_add_school_address_religion_status_to_students_table', 6),
(45, '2025_08_22_230923_add_activation_columns_to_users_table', 7),
(46, '2025_08_25_043900_add_google_fields_to_users_table', 8),
(47, '2025_08_28_000001_create_educ_levels_table', 9),
(48, '2025_08_28_000002_create_year_levels_table', 9),
(49, '2025_08_28_000003_update_students_table_for_educ_year_level', 9),
(50, '2025_08_29_000004_update_students_table_for_year_level_only', 10),
(51, '2025_08_29_142324_add_archived_to_cases_table', 11);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `p_id` bigint(50) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`p_id`, `user_id`) VALUES
(1, 91);

-- --------------------------------------------------------

--
-- Table structure for table `parent_link_requests`
--

CREATE TABLE `parent_link_requests` (
  `request_id` bigint(20) NOT NULL,
  `parent_id` bigint(20) NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parent_link_requests`
--

INSERT INTO `parent_link_requests` (`request_id`, `parent_id`, `status`, `requested_at`) VALUES
(1, 1, 'Pending', '2025-08-27 05:07:47');

-- --------------------------------------------------------

--
-- Table structure for table `parent_link_request_students`
--

CREATE TABLE `parent_link_request_students` (
  `pls_id` bigint(20) NOT NULL,
  `request_id` bigint(20) NOT NULL,
  `student_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parent_student`
--

CREATE TABLE `parent_student` (
  `ps_id` bigint(20) NOT NULL,
  `p_id` bigint(20) NOT NULL,
  `s_id` varchar(50) NOT NULL,
  `relation` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `s_id` varchar(50) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `y_id` bigint(20) UNSIGNED DEFAULT NULL,
  `section` varchar(20) DEFAULT NULL,
  `program` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  `religion` varchar(100) DEFAULT NULL,
  `civil_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`s_id`, `user_id`, `y_id`, `section`, `program`, `status`, `religion`, `civil_status`) VALUES
('MA25-0001', 92, 9, 'Sapiente eum aperiam', NULL, 'active', 'Id autem eos ea nost', 'Autem adipisci nobis');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `contact_num` varchar(20) DEFAULT NULL,
  `sex` enum('Male','Female') DEFAULT NULL,
  `bod` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) NOT NULL DEFAULT 'default.jpg',
  `password` varchar(255) NOT NULL,
  `activation_token` varchar(64) DEFAULT NULL,
  `activation_token_expires_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','counselor','student','parent') NOT NULL,
  `status` enum('active','inactive','pending') DEFAULT 'active',
  `remember_token` varchar(100) DEFAULT NULL,
  `login_token` varchar(255) DEFAULT NULL,
  `token_expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `suffix`, `email`, `contact_num`, `sex`, `bod`, `address`, `profile_image`, `password`, `activation_token`, `activation_token_expires_at`, `email_verified_at`, `role`, `status`, `remember_token`, `login_token`, `token_expires_at`, `created_at`, `updated_at`) VALUES
(1, 'Christine', 'Arquilos', 'Abendan', '', 'abendan@gmail.com', '09123456789', 'Female', NULL, NULL, 'christine.png', '$2y$12$4qszi98KSvD9Szf8IU5fnu2W132eqPHcbjE6Y28cY0Ttc4YNaoe1u', NULL, NULL, NULL, 'admin', 'active', NULL, NULL, NULL, '2025-08-08 05:36:51', '2025-08-08 05:36:51'),
(2, 'Johanna', 'Decena', 'Plameran', '', 'jb@gmail.com', '09123456789', 'Female', NULL, NULL, 'johanna.png', '$2y$12$4qszi98KSvD9Szf8IU5fnu2W132eqPHcbjE6Y28cY0Ttc4YNaoe1u', NULL, NULL, NULL, 'counselor', 'active', NULL, NULL, NULL, '2025-06-11 10:42:22', '2025-06-11 10:42:22'),
(3, 'Divine', 'Villondo', 'Romano', '', 'dasai@gmail.com', '123454678', 'Female', '2024-11-14', 'Adadspadk0', 'divine.png', '$2y$12$4qszi98KSvD9Szf8IU5fnu2W132eqPHcbjE6Y28cY0Ttc4YNaoe1u', NULL, NULL, NULL, 'counselor', 'active', NULL, NULL, NULL, '2025-08-09 08:09:50', '2025-08-09 08:09:50'),
(91, 'Christian James', NULL, 'Abendan', NULL, 'chrisbend2004@gmail.com', NULL, 'Male', NULL, NULL, 'default.jpg', '$2y$12$uoak2LLvUcRdh8v6cTBuienhINN5lUNq5nCcR8YXFlPgeWle1fRZe', NULL, NULL, '2025-08-30 04:06:53', 'parent', 'active', NULL, NULL, NULL, '2025-08-30 04:06:33', '2025-08-30 04:06:53'),
(92, 'Rama', 'Ryder Underwood', 'Hendrix', NULL, 'pibofykag@mailinator.com', '657', NULL, '2002-03-10', 'Rerum nihil est tem', 'default.jpg', '$2y$12$Ujm44aLgrd3.0bUUlOf/oO7KbOwbHqEQxND3qNg91IrnTRySLfKJS', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-08-30 04:59:02', '2025-08-30 04:59:02');

-- --------------------------------------------------------

--
-- Table structure for table `year_levels`
--

CREATE TABLE `year_levels` (
  `y_id` bigint(20) UNSIGNED NOT NULL,
  `e_id` bigint(20) UNSIGNED NOT NULL,
  `year_level` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `year_levels`
--

INSERT INTO `year_levels` (`y_id`, `e_id`, `year_level`, `created_at`, `updated_at`) VALUES
(1, 1, 'Grade 7', '2025-08-29 06:12:25', '2025-08-29 06:12:25'),
(2, 2, 'Grade 12', NULL, NULL),
(3, 2, 'Grade 11', NULL, NULL),
(4, 1, 'Grade 10', NULL, NULL),
(5, 1, 'Grade 9', NULL, NULL),
(6, 3, 'Grade 6', NULL, NULL),
(7, 3, 'Grade 5', NULL, NULL),
(8, 3, 'Grade 4', NULL, NULL),
(9, 3, 'Grade 3', NULL, NULL),
(10, 3, 'Grade 2', NULL, NULL),
(11, 3, 'Grade 1', NULL, NULL),
(12, 1, 'Grade 8', '2025-08-29 17:03:00', '2025-08-29 17:03:00'),
(13, 4, 'Kindergarten', '2025-08-29 17:33:45', '2025-08-29 17:33:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`a_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `requester_id` (`requester_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `counselor_id` (`counselor_id`);

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
-- Indexes for table `cases`
--
ALTER TABLE `cases`
  ADD PRIMARY KEY (`case_id`),
  ADD KEY `idx_case_type_id` (`case_type_id`);

--
-- Indexes for table `case_students`
--
ALTER TABLE `case_students`
  ADD PRIMARY KEY (`case_id`,`student_id`),
  ADD KEY `idx_student_id` (`student_id`);

--
-- Indexes for table `case_types`
--
ALTER TABLE `case_types`
  ADD PRIMARY KEY (`type_id`),
  ADD UNIQUE KEY `type_name` (`type_name`);

--
-- Indexes for table `counseling_notes`
--
ALTER TABLE `counseling_notes`
  ADD PRIMARY KEY (`note_id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `counselors`
--
ALTER TABLE `counselors`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `educ_levels`
--
ALTER TABLE `educ_levels`
  ADD PRIMARY KEY (`e_id`);

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`p_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `parent_link_requests`
--
ALTER TABLE `parent_link_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `fk_parent_request` (`parent_id`);

--
-- Indexes for table `parent_link_request_students`
--
ALTER TABLE `parent_link_request_students`
  ADD PRIMARY KEY (`pls_id`),
  ADD KEY `fk_request_student` (`request_id`),
  ADD KEY `fk_student_request` (`student_id`);

--
-- Indexes for table `parent_student`
--
ALTER TABLE `parent_student`
  ADD PRIMARY KEY (`ps_id`),
  ADD KEY `fk_parent_ps` (`p_id`),
  ADD KEY `fk_student_ps` (`s_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`s_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `students_y_id_foreign` (`y_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `year_levels`
--
ALTER TABLE `year_levels`
  ADD PRIMARY KEY (`y_id`),
  ADD KEY `year_levels_e_id_foreign` (`e_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cases`
--
ALTER TABLE `cases`
  MODIFY `case_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `case_types`
--
ALTER TABLE `case_types`
  MODIFY `type_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `counseling_notes`
--
ALTER TABLE `counseling_notes`
  MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `educ_levels`
--
ALTER TABLE `educ_levels`
  MODIFY `e_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `p_id` bigint(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `parent_link_requests`
--
ALTER TABLE `parent_link_requests`
  MODIFY `request_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `parent_link_request_students`
--
ALTER TABLE `parent_link_request_students`
  MODIFY `pls_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parent_student`
--
ALTER TABLE `parent_student`
  MODIFY `ps_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `year_levels`
--
ALTER TABLE `year_levels`
  MODIFY `y_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`requester_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `appointments_ibfk_4` FOREIGN KEY (`counselor_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `cases`
--
ALTER TABLE `cases`
  ADD CONSTRAINT `fk_case_type` FOREIGN KEY (`case_type_id`) REFERENCES `case_types` (`type_id`) ON UPDATE CASCADE;

--
-- Constraints for table `case_students`
--
ALTER TABLE `case_students`
  ADD CONSTRAINT `fk_case_students_case` FOREIGN KEY (`case_id`) REFERENCES `cases` (`case_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_case_students_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`s_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `counseling_notes`
--
ALTER TABLE `counseling_notes`
  ADD CONSTRAINT `counseling_notes_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `counseling_notes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `counselors`
--
ALTER TABLE `counselors`
  ADD CONSTRAINT `counselors_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `parents`
--
ALTER TABLE `parents`
  ADD CONSTRAINT `parents_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `parent_link_requests`
--
ALTER TABLE `parent_link_requests`
  ADD CONSTRAINT `fk_parent_request` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`p_id`) ON DELETE CASCADE;

--
-- Constraints for table `parent_link_request_students`
--
ALTER TABLE `parent_link_request_students`
  ADD CONSTRAINT `fk_request_student` FOREIGN KEY (`request_id`) REFERENCES `parent_link_requests` (`request_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_student_request` FOREIGN KEY (`student_id`) REFERENCES `students` (`s_id`) ON DELETE CASCADE;

--
-- Constraints for table `parent_student`
--
ALTER TABLE `parent_student`
  ADD CONSTRAINT `fk_parent_ps` FOREIGN KEY (`p_id`) REFERENCES `parents` (`p_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_student_ps` FOREIGN KEY (`s_id`) REFERENCES `students` (`s_id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `students_y_id_foreign` FOREIGN KEY (`y_id`) REFERENCES `year_levels` (`y_id`) ON DELETE SET NULL;

--
-- Constraints for table `year_levels`
--
ALTER TABLE `year_levels`
  ADD CONSTRAINT `year_levels_e_id_foreign` FOREIGN KEY (`e_id`) REFERENCES `educ_levels` (`e_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
