-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 12, 2025 at 08:12 AM
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
(1, 1, '🎉Congratulations to Batch 2024–2025!', 'We proudly celebrate the achievements of our graduating students. Your hard work, dedication, and perseverance have brought you to this milestone.\r\n\r\nWishing you success and happiness in all your future endeavors!\r\n\r\n#Batch2024_2025 #GraduationCelebration', NULL, 'News', 'a3.jpg', '2025-09-02', NULL, NULL, 'active', '2025-09-02 00:25:32', '2025-09-08 09:06:37'),
(2, 1, 'Join a School Club Today!', 'Sign up today to explore new interests, develop your talents, and enjoy exciting activities with friends. Joining a club is a great way to build skills, meet new people, and get involved in school life beyond the classroom.\n\nHow to Participate:\n\nVisit the Student Affairs Office or check the official school website to see the list of available clubs.\n\nFill out the registration form and submit it by the deadline.\n\nSome clubs may require a short orientation or interview—details will be provided during sign-up.\n\nLocation:\nRegistration booths will be set up at the School Lobby from 8:00 AM to 4:00 PM, Monday to Friday.\n\nDon’t miss this chance to be part of a community that matches your passion and creativity. Whether you’re into sports, arts, academics, or leadership, there’s a club waiting for you!', NULL, 'Event', 'a1.jpg', '2025-09-02', '2025-09-04 07:00:00', '2025-09-06 07:00:00', 'active', '2025-09-02 02:13:48', '2025-09-11 01:03:55'),
(4, 1, 'Debitis ut tenetur c', 'Lorem cupidatat enim', 'https://www.waxoqaz.tv', 'Announcement', 'default.png', '2025-09-04', NULL, NULL, 'active', '2025-09-04 04:31:19', '2025-09-04 12:33:21'),
(5, 1, 'Ut id laborum In ne', 'Sunt est aliquip qua', 'https://www.pylokomibiduqi.us', 'Announcement', 'default.png', '2025-09-04', NULL, NULL, 'active', '2025-09-04 04:45:36', '2025-09-04 04:45:36'),
(6, 1, 'No Classes on September 8', 'Due to the bad weather', NULL, 'Announcement', 'default.png', '2025-09-05', NULL, NULL, 'active', '2025-09-04 22:48:55', '2025-09-04 22:48:55');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `requester_id` bigint(20) UNSIGNED NOT NULL,
  `requester_type` enum('Parent','Student') NOT NULL,
  `counselor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `appointment_type_id` int(11) UNSIGNED DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `appointment_datetime` datetime NOT NULL,
  `location` varchar(255) DEFAULT 'School Guidance Office',
  `status` enum('Pending','Approved','Cancelled','Completed') DEFAULT 'Pending',
  `rescheduled_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `last_rescheduled_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointment_students`
--

CREATE TABLE `appointment_students` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `student_user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `remarks` varchar(50) NOT NULL,
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
-- Table structure for table `document_requests`
--

CREATE TABLE `document_requests` (
  `request_id` bigint(50) NOT NULL,
  `parent_id` bigint(50) NOT NULL,
  `document_type` varchar(100) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `requested_at` datetime NOT NULL DEFAULT current_timestamp(),
  `rejection_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_request_students`
--

CREATE TABLE `document_request_students` (
  `drs_id` bigint(20) NOT NULL,
  `request_id` bigint(50) NOT NULL,
  `s_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'Kindergarten', '2025-09-11 06:07:26', '2025-09-11 06:07:26'),
(2, 'Elementary', '2025-09-11 06:07:26', '2025-09-11 06:07:26'),
(3, 'Junior High School', '2025-09-11 06:07:26', '2025-09-11 06:07:26'),
(4, 'Senior High School', '2025-09-11 06:07:26', '2025-09-11 06:07:26');

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
(51, '2025_08_29_142324_add_archived_to_cases_table', 11),
(52, '2025_09_09_013027_add_rejection_reason_to_parent_link_requests_table', 12),
(53, '2025_09_09_000000_add_indexes_to_students_and_related_tables', 13),
(54, '2025_09_11_000000_remove_y_id_and_status_from_students_table', 13),
(55, '2025_09_11_000001_create_school_year_and_student_schoolyear_tables', 14);

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

-- --------------------------------------------------------

--
-- Table structure for table `parent_link_requests`
--

CREATE TABLE `parent_link_requests` (
  `request_id` bigint(20) NOT NULL,
  `parent_id` bigint(20) NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `rejection_reason` varchar(255) DEFAULT NULL,
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(50) NOT NULL,
  `number` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

--
-- Dumping data for table `parent_student`
--

INSERT INTO `parent_student` (`ps_id`, `p_id`, `s_id`, `relation`) VALUES
(1, 1, 'MA25-0010', 'Guardian');

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
-- Table structure for table `school_year`
--

CREATE TABLE `school_year` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `year_label` varchar(20) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 0,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `school_year`
--

INSERT INTO `school_year` (`id`, `year_label`, `is_active`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(1, '2025-2026', 1, '2025-06-01', '2026-03-31', '2025-09-11 07:49:32', '2025-09-11 07:49:32');

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
  `section` varchar(20) DEFAULT NULL,
  `program` varchar(100) DEFAULT NULL,
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

INSERT INTO `students` (`s_id`, `user_id`, `section`, `program`, `religion`, `civil_status`, `father_name`, `mother_name`, `guardian_name`, `relationship`, `guardian_contact`, `guardian_email`) VALUES
('MA25-0001', 4, '12B', 'ICT', 'Christian', 'Single', 'Brian G. Romano', 'Cheryl V. Romano', 'Cheryl V. Romano', 'Mother', '09123456789', 'cheryl@gmail.com'),
('MA25-0002', 5, '12C', 'HUMSS', 'Catholic', 'Single', 'Klien Stewart Batol', 'Ava Simgulang Batol', 'David Sailas V. Romano', 'Guardian', '09817970638', 'david@gmail.com'),
('MA25-0003', 6, 'Blue', 'N/A', 'Christian', 'Single', 'Ramon Domingo', 'Elena Domingo', 'Maria Santos', 'Guardian', '09190002230', 'maria.s@example.com'),
('MA25-0004', 7, 'Red', 'N/A', 'Catholic', 'Single', 'Alfredo Lopez', 'Susan Lopez', 'Daniel Cruz', 'Guardian', '09190002231', 'daniel.c@example.com'),
('MA25-0005', 8, 'Daisy', 'N/A', 'Christian', 'Single', 'Roberto Valdez', 'Marites Valdez', 'Carlo Bautista', 'Guardian', '09190002232', 'carlo.b@example.com'),
('MA25-0006', 9, 'Guava', 'N/A', 'Catholic', 'Single', 'Jose Garcia', 'Patricia Garcia', 'Andres Ramos', 'Guardian', '09190002233', 'andres.r@example.com'),
('MA25-0007', 10, 'Honesty', 'N/A', 'Christian', 'Single', 'Antonio Santos', 'Luz Santos', 'Cristina Cruz', 'Guardian', '09190002234', 'cristina.c@example.com'),
('MA25-0008', 11, 'Athena', 'N/A', 'Catholic', 'Single', 'Raul Mendoza', 'Isabel Mendoza', 'Pedro Ramirez', 'Guardian', '09190002235', 'pedro.r@example.com'),
('MA25-0009', 12, 'Hercules', 'N/A', 'Christian', 'Single', 'Manuel Castillo', 'Teresa Castillo', 'Josefina Cruz', 'Guardian', '09190002236', 'josefina.c@example.com'),
('MA25-0010', 13, '11A', 'HUMSS', 'Catholic', 'Single', 'Francisco Bautista', 'Ana Bautista', 'Diego Ramirez', 'Guardian', '09190002237', 'diego.r@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `student_schoolyear`
--

CREATE TABLE `student_schoolyear` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `school_year_id` bigint(20) UNSIGNED NOT NULL,
  `year_level` varchar(20) NOT NULL,
  `section` varchar(20) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Enrolled',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_schoolyear`
--

INSERT INTO `student_schoolyear` (`id`, `student_id`, `school_year_id`, `year_level`, `section`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'MA25-0001', 1, 'Grade 12', '12B', 'Enrolled', NULL, '2025-09-11 08:13:07', '2025-09-11 08:51:55'),
(2, 'MA25-0002', 1, 'Grade 12', '12C', 'Enrolled', NULL, '2025-09-11 08:40:17', '2025-09-11 08:48:51'),
(3, 'MA25-0003', 1, 'Kindergarten', 'Blue', 'Enrolled', NULL, '2025-09-11 17:01:37', '2025-09-11 17:01:37'),
(4, 'MA25-0004', 1, 'Kindergarten', 'Red', 'Enrolled', NULL, '2025-09-11 17:01:37', '2025-09-11 17:01:37'),
(5, 'MA25-0005', 1, 'Grade 3', 'Daisy', 'Enrolled', NULL, '2025-09-11 17:01:37', '2025-09-11 17:01:37'),
(6, 'MA25-0006', 1, 'Grade 4', 'Guava', 'Enrolled', NULL, '2025-09-11 17:01:38', '2025-09-11 17:01:38'),
(7, 'MA25-0007', 1, 'Grade 5', 'Honesty', 'Enrolled', NULL, '2025-09-11 17:01:38', '2025-09-11 17:01:38'),
(8, 'MA25-0008', 1, 'Grade 7', 'Athena', 'Enrolled', NULL, '2025-09-11 17:01:38', '2025-09-11 17:01:38'),
(9, 'MA25-0009', 1, 'Grade 8', 'Hercules', 'Enrolled', NULL, '2025-09-11 17:01:39', '2025-09-11 17:01:39'),
(10, 'MA25-0010', 1, 'Grade 11', '11A', 'Enrolled', NULL, '2025-09-11 17:01:39', '2025-09-11 17:07:05');

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
(1, 'Christine', 'Arquilos', 'Abendan', '', 'abendan@gmail.com', '09123456789', 'Female', NULL, NULL, 'christine.png', '$2y$12$4qszi98KSvD9Szf8IU5fnu2W132eqPHcbjE6Y28cY0Ttc4YNaoe1u', NULL, NULL, NULL, 'admin', 'active', NULL, NULL, NULL, '2025-08-07 13:36:51', '2025-08-07 13:36:51'),
(2, 'Johanna', 'Decena', 'Plameran', '', 'jb@gmail.com', '09123456789', 'Female', NULL, NULL, 'johanna.png', '$2y$12$4qszi98KSvD9Szf8IU5fnu2W132eqPHcbjE6Y28cY0Ttc4YNaoe1u', NULL, NULL, NULL, 'counselor', 'active', NULL, NULL, NULL, '2025-06-10 18:42:22', '2025-06-10 18:42:22'),
(3, 'Divine', 'Villondo', 'Romano', '', 'dasai@gmail.com', '123454678', 'Female', '2024-11-14', 'Adadspadk0', 'divine.png', '$2y$12$4qszi98KSvD9Szf8IU5fnu2W132eqPHcbjE6Y28cY0Ttc4YNaoe1u', NULL, NULL, NULL, 'counselor', 'active', NULL, NULL, NULL, '2025-08-08 16:09:50', '2025-08-08 16:09:50'),
(4, 'David Sailas', 'Villondo', 'Romano', NULL, 'david@gmail.com', '09817970638', 'Male', '2003-02-12', 'Lipata, Minglanilla, Cebu.', 'Defined Body Without Losing Strength.jpg', '$2y$12$z0jcQ8MU6hDAJHyUlmk8G.bzCvoszYeBY9utAnUn13FK6WOXeayt.', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-11 08:13:07', '2025-09-11 08:51:55'),
(5, 'Mercilyn', 'Simgulang', 'Batol', NULL, 'ashlyn@gmail.com', '09457000308', 'Female', '2004-12-04', 'Lipata, Minglanilla, Cebu.', 'download (5).jpg', '$2y$12$6is/Etbr/lnPnHDOjNxM.evUGXI5LblGtj6cNzkgotqUIhYAJhaA6', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-11 08:40:17', '2025-09-11 08:48:51'),
(6, 'Liam', 'Andres', 'Domingo', '', 'liam.domingo@example.com', '09190000003', 'Male', '2017-02-11', '45 Narra St', 'default.png', '$2y$12$EsrOITSnabR3n.W53jeH9.HRdfyzsHAzQEfO1TzRTWJxZdSiFdzVe', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-11 17:01:37', '2025-09-11 17:01:37'),
(7, 'Chloe', 'Ramirez', 'Lopez', '', 'chloe.lopez@example.com', '09190000004', 'Female', '2016-09-23', '78 Acacia St', 'default.png', '$2y$12$mB7Qu1gGUdu8hjbTPIEase1kO09smMXKdLxj/XZ7AivkwpQbdDRhO', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-11 17:01:37', '2025-09-11 17:01:37'),
(8, 'Ethan', 'Reyes', 'Valdez', '', 'ethan.valdez@example.com', '09190000005', 'Male', '2013-03-19', '89 Sampaguita St', 'default.png', '$2y$12$TDCWJWjJ.eiVeJLRyOgurOKEGhVAjocOiQTAAXBmEtvw2pFnADhBy', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-11 17:01:37', '2025-09-11 17:01:37'),
(9, 'Isabella', 'Cruz', 'Garcia', '', 'isabella.garcia@example.com', '09190000006', 'Female', '2012-07-08', '99 Santan St', 'default.png', '$2y$12$aCaZRz0RZT/t/OINDsOp3.RMYgde4.TLjVzF50BgoSPVH2OEp86JK', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-11 17:01:38', '2025-09-11 17:01:38'),
(10, 'Miguel', 'Domingo', 'Santos', '', 'miguel.santos@example.com', '09190000007', 'Male', '2010-11-15', '12 Banaba St', 'default.png', '$2y$12$YR7dWKGmROZjDpHMaEnKduu8YEiTYpaqd8aIKMGimm54EKKfJt89e', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-11 17:01:38', '2025-09-11 17:01:38'),
(11, 'Samantha', 'Reyes', 'Mendoza', '', 'samantha.mendoza@example.com', '09190000008', 'Female', '2009-06-25', '34 Ipil St', 'default.png', '$2y$12$BRNFcGYXZ/RIIyU3uUv.deC2fQfHgR/LO/5utRRnsucQaSAT2UKlm', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-11 17:01:38', '2025-09-11 17:01:38'),
(12, 'Adrian', 'Ramirez', 'Castillo', '', 'adrian.castillo@example.com', '09190000009', 'Male', '2008-04-30', '56 Camia St', 'default.png', '$2y$12$.C73Uzvret4A9UbG6hfey.3zp8xaLDjPPbGtshydtQi0nsFJl9Lcq', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-11 17:01:39', '2025-09-11 17:01:39'),
(13, 'Sofia', 'Andres', 'Bautista', '', 'sofia.bautista@example.com', '09190000010', 'Female', '2007-01-19', '101 Gumamela St', 'download.jpg', '$2y$12$gYpkPP965DYSJXtmrDQu3OCuWa58MBhCq9mXc6/8mfG1A1tnBS6le', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-11 17:01:39', '2025-09-11 17:07:05');

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
(1, 1, 'Kindergarten', '2025-09-10 22:07:26', '2025-09-10 22:07:26'),
(2, 2, 'Grade 1', '2025-09-10 22:07:26', '2025-09-10 22:07:26'),
(3, 2, 'Grade 2', '2025-09-10 22:07:26', '2025-09-10 22:07:26'),
(4, 2, 'Grade 3', '2025-09-10 22:07:26', '2025-09-10 22:07:26'),
(5, 2, 'Grade 4', '2025-09-10 22:07:26', '2025-09-10 22:07:26'),
(6, 2, 'Grade 5', '2025-09-10 22:07:26', '2025-09-10 22:07:26'),
(7, 2, 'Grade 6', '2025-09-10 22:07:26', '2025-09-10 22:07:26'),
(8, 3, 'Grade 7', '2025-09-10 22:07:26', '2025-09-10 22:07:26'),
(9, 3, 'Grade 8', '2025-09-10 22:07:26', '2025-09-10 22:07:26'),
(10, 3, 'Grade 9', '2025-09-10 22:07:26', '2025-09-10 22:07:26'),
(11, 3, 'Grade 10', '2025-09-10 22:07:26', '2025-09-10 22:07:26'),
(12, 4, 'Grade 11', '2025-09-10 22:07:26', '2025-09-10 22:07:26'),
(13, 4, 'Grade 12', '2025-09-10 22:07:26', '2025-09-10 22:07:26');

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
  ADD KEY `counselor_id` (`counselor_id`),
  ADD KEY `fk_appointment_type` (`appointment_type_id`);

--
-- Indexes for table `appointment_students`
--
ALTER TABLE `appointment_students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `student_user_id` (`student_user_id`);

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
  ADD KEY `idx_case_type_id` (`case_type_id`),
  ADD KEY `cases_archived_index` (`archived`),
  ADD KEY `cases_severity_index` (`severity`),
  ADD KEY `cases_filed_date_index` (`filed_date`);

--
-- Indexes for table `case_students`
--
ALTER TABLE `case_students`
  ADD PRIMARY KEY (`case_id`,`student_id`),
  ADD KEY `idx_student_id` (`student_id`),
  ADD KEY `case_students_student_id_index` (`student_id`),
  ADD KEY `case_students_case_id_index` (`case_id`);

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
-- Indexes for table `document_requests`
--
ALTER TABLE `document_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `document_request_students`
--
ALTER TABLE `document_request_students`
  ADD PRIMARY KEY (`drs_id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `fk_drs_student` (`s_id`);

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
-- Indexes for table `school_year`
--
ALTER TABLE `school_year`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `students_s_id_index` (`s_id`),
  ADD KEY `students_user_id_index` (`user_id`);

--
-- Indexes for table `student_schoolyear`
--
ALTER TABLE `student_schoolyear`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_schoolyear_student_id_foreign` (`student_id`),
  ADD KEY `student_schoolyear_school_year_id_foreign` (`school_year_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_email_index` (`email`),
  ADD KEY `users_status_index` (`status`);

--
-- Indexes for table `year_levels`
--
ALTER TABLE `year_levels`
  ADD PRIMARY KEY (`y_id`),
  ADD KEY `year_levels_e_id_foreign` (`e_id`),
  ADD KEY `year_levels_e_id_index` (`e_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `appointment_students`
--
ALTER TABLE `appointment_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `document_requests`
--
ALTER TABLE `document_requests`
  MODIFY `request_id` bigint(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_request_students`
--
ALTER TABLE `document_request_students`
  MODIFY `drs_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `educ_levels`
--
ALTER TABLE `educ_levels`
  MODIFY `e_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `p_id` bigint(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parent_link_requests`
--
ALTER TABLE `parent_link_requests`
  MODIFY `request_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parent_link_request_students`
--
ALTER TABLE `parent_link_request_students`
  MODIFY `pls_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parent_student`
--
ALTER TABLE `parent_student`
  MODIFY `ps_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `school_year`
--
ALTER TABLE `school_year`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student_schoolyear`
--
ALTER TABLE `student_schoolyear`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
  ADD CONSTRAINT `appointments_ibfk_4` FOREIGN KEY (`counselor_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_appointment_type` FOREIGN KEY (`appointment_type_id`) REFERENCES `appointment_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `appointment_students`
--
ALTER TABLE `appointment_students`
  ADD CONSTRAINT `appointment_students_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_students_ibfk_2` FOREIGN KEY (`student_user_id`) REFERENCES `students` (`user_id`) ON DELETE CASCADE;

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
-- Constraints for table `document_requests`
--
ALTER TABLE `document_requests`
  ADD CONSTRAINT `document_requests_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`p_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_schoolyear`
--
ALTER TABLE `student_schoolyear`
  ADD CONSTRAINT `student_schoolyear_school_year_id_foreign` FOREIGN KEY (`school_year_id`) REFERENCES `school_year` (`id`),
  ADD CONSTRAINT `student_schoolyear_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`s_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
