-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2025 at 07:16 AM
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
(1, 1, '🎉Congratulations to Batch 2024–2025!', 'We proudly celebrate the achievements of our graduating students. Your hard work, dedication, and perseverance have brought you to this milestone.\r\n\r\nWishing you success and happiness in all your future endeavors!\r\n\r\n#Batch2024_2025 #GraduationCelebration', NULL, 'News', '1756801532_a3.jpg', '2025-09-02', NULL, NULL, 'active', '2025-09-02 00:25:32', '2025-09-03 17:39:55'),
(2, 1, 'Join a School Club Today!', 'School Club Registration is now open! Sign up to discover new interests, develop your talents, and enjoy activities with friends.', NULL, 'Event', '1756808028_a1.jpg', '2025-09-02', '2025-09-04 07:00:00', '2025-09-06 07:00:00', 'active', '2025-09-02 02:13:48', '2025-09-03 18:47:19');

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
  `appointment_type_id` int(11) UNSIGNED DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `appointment_datetime` datetime NOT NULL,
  `location` varchar(255) DEFAULT 'School Guidance Office',
  `status` enum('Pending','Approved','Cancelled','Completed') DEFAULT 'Pending',
  `rescheduled_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `last_rescheduled_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `requester_id`, `requester_type`, `student_id`, `counselor_id`, `appointment_type_id`, `reason`, `appointment_datetime`, `location`, `status`, `rescheduled_count`, `last_rescheduled_at`) VALUES
(1, 91, 'Parent', 92, 1, 2, NULL, '2025-09-03 18:00:00', 'School Guidance Office', 'Approved', 0, NULL),
(2, 91, 'Parent', 92, 1, 1, NULL, '2025-08-30 14:00:00', 'School Guidance Office', 'Approved', 0, NULL),
(3, 91, 'Parent', 92, 1, 1, NULL, '2025-08-28 09:00:00', 'School Guidance Office', 'Approved', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `appointment_types`
--

CREATE TABLE `appointment_types` (
  `id` int(11) UNSIGNED NOT NULL,
  `type_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_types`
--

INSERT INTO `appointment_types` (`id`, `type_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Personal Counseling', 'Individual guidance session for personal matters', '2025-09-02 09:03:18', '2025-09-02 09:03:18'),
(2, 'Career Guidance', 'Guidance session focused on career planning and advice', '2025-09-02 09:03:18', '2025-09-02 09:03:18'),
(3, 'Academic Counseling', 'Guidance related to academic performance and planning', '2025-09-02 09:03:18', '2025-09-02 09:03:18');

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
('MA25-C002', 3),
('MA25-C003', 100),
('MA25-C004', 101),
('MA25-C005', 102);

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
(1, 1, 'Pending', '2025-09-03 09:41:48');

-- --------------------------------------------------------

--
-- Table structure for table `parent_link_request_students`
--

CREATE TABLE `parent_link_request_students` (
  `pls_id` bigint(20) NOT NULL,
  `request_id` bigint(20) NOT NULL,
  `student_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parent_link_request_students`
--

INSERT INTO `parent_link_request_students` (`pls_id`, `request_id`, `student_id`) VALUES
(1, 1, 'MA25-0001');

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
  `civil_status` varchar(50) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `relationship` varchar(255) DEFAULT NULL,
  `guardian_contact` varchar(255) DEFAULT NULL,
  `guardian_email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`s_id`, `user_id`, `y_id`, `section`, `program`, `status`, `religion`, `civil_status`, `father_name`, `mother_name`, `guardian_name`, `relationship`, `guardian_contact`, `guardian_email`) VALUES
('MA25-0001', 92, 9, 'Sapiente eum aperiam', NULL, 'active', 'Id autem eos ea nost', 'Autem adipisci nobis', 'dasdasdasd', NULL, NULL, NULL, NULL, NULL),
('MA25-0002', 93, 11, 'Ad dolores et eum se', NULL, 'active', 'Quisquam amet natus', 'Sit nesciunt offici', 'Jenna Mcbride', 'Blythe Simon', 'Kiara Willis', 'Consequat Quibusdam optio et odit quidem quaerat incidunt', 'Sit inventore id deleniti voluptatem Rerum anim omnis reprehenderit dolores odit qui saepe repellendus', 'hikagiku@mailinator.com'),
('MA25-0003', 94, 3, 'Eligendi non iste es', NULL, 'active', 'Eum id saepe perfer', 'Nostrud sit adipisi', 'Jessamine Sharp', 'Ifeoma Donaldson', 'Zeph Travis', 'Vero et vel corporis consequat Quis velit amet illo dicta exercitationem labore culpa qui', 'Quis excepteur qui reiciendis sint inventore', 'qylo@mailinator.com'),
('MA25-0010', 95, 7, '5A', NULL, 'inactive', 'Christian', 'Single', NULL, NULL, NULL, NULL, NULL, NULL),
('MA25-0011', 97, 2, 'Nobis in nostrum sun', NULL, 'active', 'Ratione quod in aliq', 'Id irure et rerum de', 'Hillary Oneil', 'Rama Sharpe', 'Colleen Anthony', 'Exercitationem tempor modi temporibus ipsum corrupti', 'Illo fugiat quos voluptatibus sed dolore labore sint labore nisi mollit magnam at ex qui aut', 'molucoqe@mailinator.com'),
('MA25-0012', 98, 11, 'Aut sed a ut laborum', NULL, 'active', 'Fugiat veniam offi', 'Fugit ipsum magnam', 'Florence Kane', 'Amal Spence', 'Kareem Pennington', 'Inventore earum in ut aut id laboris culpa voluptates hic ipsum nulla', 'Sit in reprehenderit nostrud vero aliquam deserunt laborum Aut', 'vafoputut@mailinator.com'),
('MA25-0013', 99, 6, 'Quasi quibusdam sit', NULL, 'active', 'Eu fuga Et pariatur', 'Consectetur dolor sa', 'Ina Workman', 'Morgan Preston', 'Odysseus Sanchez', 'In error id omnis sunt officiis nihil soluta tenetur sed', 'Labore incididunt vitae saepe nesciunt a voluptas repudiandae dignissimos debitis harum soluta dolorum', 'murowodef@mailinator.com');

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
(92, 'Rama', 'Ryder Underwood', 'Hendrix', NULL, 'pibofykag@mailinator.com', '657', 'Male', '2002-03-10', 'Rerum nihil est tem', 'default.jpg', '$2y$12$Ujm44aLgrd3.0bUUlOf/oO7KbOwbHqEQxND3qNg91IrnTRySLfKJS', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-08-30 04:59:02', '2025-09-03 18:07:50'),
(93, 'Hillary', 'Alfreda Dawson', 'Maldonado', NULL, 'gegepefan@mailinator.com', '154', 'Male', '1986-07-06', 'Sit ullamco ratione', 'default.jpg', '$2y$12$9jHbYZJResPS4T8lgm5H4OAXVbBK8Z3hboxuvzW/KweDKhifaNzyq', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-03 18:08:12', '2025-09-03 18:10:01'),
(94, 'Alika', 'Allegra Branch', 'Shepard', NULL, 'dusyz@mailinator.com', '111', 'Female', '2017-11-23', 'Ducimus eu quia ut', 'default.jpg', '$2y$12$mhyzVJtE4xlDGvOpuqMqV.diRJVqoEpWAVlHLQb9LYcC3GjyrwdKy', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-03 18:10:16', '2025-09-03 18:10:16'),
(95, 'Juan', 'Cruz', 'Reyes', '', 'juan.reyes@example.com', '09190000000', 'Male', '2010-05-20', '123 Mango St', 'default.png', '$2y$12$Z5rrYTbhOfuLE8WalDzasueG1gXNh7EHyBRfKgn45g0u30wUdOK62', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-03 19:38:46', '2025-09-03 19:38:46'),
(97, 'Jillian', 'Dorian Marks', 'Stafford', NULL, 'tysoquniq@mailinator.com', '404', 'Female', '1999-03-18', 'Corporis quos enim h', 'default.jpg', '$2y$12$5N23OwBaPSxAZu.Sbq36WehhnyVmHGlmLrPXaXE9RnONzarHY13WS', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-03 19:46:28', '2025-09-03 19:46:28'),
(98, 'Chelsea', 'Tucker Huber', 'Sullivan', NULL, 'xegegebewe@mailinator.com', '498', 'Male', '2014-04-24', 'Quam eveniet volupt', 'user_68b90da3a7e98.jpg', '$2y$12$Xf0TP75YsLe0TRSf8iBkbe7AkkSz.7QZ5AHOcsBiFPe3xYMgER.Fm', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-03 19:55:15', '2025-09-03 19:55:15'),
(99, 'Madeson', 'Justina Macdonald', 'Henry', NULL, 'byfidufyx@mailinator.com', '275', 'Female', '2005-12-20', 'Non eum qui non rem', 'user_68b90dbcadb72.jpg', '$2y$12$OkGRGxIWxmRWlhp7Wrm.7OKgNzzlBQTFgupowN7Khec/cCIRIsO4O', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-03 19:55:40', '2025-09-03 19:55:40'),
(100, 'Catherine', 'Hop Wiley', 'Owens', NULL, 'wowod@mailinator.com', '923', NULL, NULL, NULL, '1756962664_68b91f688dc7c.jpg', '$2y$12$29bg/bOmPgzn3d3lJWCkCOTfG6rpueUZje/PuWFiHcs5XPWkmHpQe', NULL, NULL, NULL, 'counselor', 'pending', NULL, NULL, NULL, '2025-09-03 21:11:04', '2025-09-03 21:11:04'),
(101, 'Ifeoma', 'Lacota Woodard', 'Nielsen', NULL, 'lovekolyda@mailinator.com', '27', NULL, NULL, NULL, '1756962826_68b9200ae5039.jpg', '$2y$12$/7c9aLX.6uyXHb4/zjfb1eBn8a74LNYBtm5X0XHnd8gX2Ed533A56', NULL, NULL, NULL, 'counselor', 'pending', NULL, NULL, NULL, '2025-09-03 21:13:47', '2025-09-03 21:13:47'),
(102, 'Nerea', 'Cairo Park', 'Eaton', NULL, 'gigomatut@mailinator.com', '736', NULL, NULL, NULL, 'default.jpg', '$2y$12$n76uH8Q5I5mE30P1MJPsweOajH6Gri8JqC9C79OsMWcglH8cVTlsm', NULL, NULL, NULL, 'counselor', 'pending', NULL, NULL, NULL, '2025-09-03 21:14:51', '2025-09-03 21:14:51');

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
  ADD KEY `counselor_id` (`counselor_id`),
  ADD KEY `fk_appointment_type` (`appointment_type_id`);

--
-- Indexes for table `appointment_types`
--
ALTER TABLE `appointment_types`
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
-- AUTO_INCREMENT for table `appointment_types`
--
ALTER TABLE `appointment_types`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `pls_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `parent_student`
--
ALTER TABLE `parent_student`
  MODIFY `ps_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

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
  ADD CONSTRAINT `appointments_ibfk_4` FOREIGN KEY (`counselor_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_appointment_type` FOREIGN KEY (`appointment_type_id`) REFERENCES `appointment_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

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
