-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 11, 2025 at 06:00 AM
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

--
-- Dumping data for table `cases`
--

INSERT INTO `cases` (`case_id`, `case_type_id`, `presenting_problem`, `description`, `severity`, `witnesses`, `investigation_notes`, `evidence`, `filed_date`, `filed_time`, `status`, `action_taken`, `resolution_notes`, `resolved_date`, `follow_up_date`, `archived`) VALUES
(1, 1, 'A', 'A', 'Low', 'A', 'A', 'A', '2025-09-05', '11:37:00', 'Under Investigation', 'Magnam et voluptatem', NULL, NULL, NULL, 1),
(2, 1, 'Explicabo Ab repreh', 'AD', 'Low', 'D', 'D', NULL, '2025-09-03', '11:50:00', 'Pending', NULL, NULL, NULL, NULL, 1),
(3, 2, 'Duis occaecat ducimu', 'Et excepturi ut solu', 'Low', 'Dolor amet sint aut', 'Et debitis qui occae', 'Facere exercitation', '1981-09-16', '19:13:00', 'Pending', 'Iusto voluptates aut', 'Ex est dignissimos v', '2025-05-11', '2022-03-30', 1),
(4, 2, 'Corrupti sint conse', 'Voluptatum excepturi', 'Severe', 'Provident aliquip u', 'Dicta qui autem volu', 'Molestiae ea aut min', '1983-02-28', '18:10:00', 'Pending', 'Et doloribus recusan', 'Eius magnam providen', '1985-12-06', '1979-05-17', 0);

-- --------------------------------------------------------

--
-- Table structure for table `case_students`
--

CREATE TABLE `case_students` (
  `case_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `case_students`
--

INSERT INTO `case_students` (`case_id`, `student_id`) VALUES
(2, 'MA25-0010'),
(3, 'MA25-0010'),
(3, 'MA25-0011'),
(4, 'MA25-0010');

-- --------------------------------------------------------

--
-- Table structure for table `case_types`
--

CREATE TABLE `case_types` (
  `type_id` bigint(20) UNSIGNED NOT NULL,
  `type_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `case_types`
--

INSERT INTO `case_types` (`type_id`, `type_name`, `description`) VALUES
(1, 'A', ''),
(2, 'bullying', '');

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
('MA25-C002', 3),
('MA25-C003', 100),
('MA25-C004', 101),
('MA25-C005', 102),
('MA25-C006', 107);

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
(51, '2025_08_29_142324_add_archived_to_cases_table', 11),
(52, '2025_09_09_013027_add_rejection_reason_to_parent_link_requests_table', 12);

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
(1, 91),
(2, 109),
(3, 110),
(4, 118),
(5, 119),
(7, 121);

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

--
-- Dumping data for table `parent_link_requests`
--

INSERT INTO `parent_link_requests` (`request_id`, `parent_id`, `status`, `rejection_reason`, `requested_at`, `email`, `number`) VALUES
(1, 1, 'Pending', NULL, '2025-09-08 20:24:50', 'chrisbend2004@gmail.com', '09196105986');

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
(1, 1, 'MA25-0003'),
(2, 1, 'MA25-0004');

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
('MA25-0003', 111, 9, '3A', NULL, 'active', 'Christian', 'Single', 'Jessamine Sharp', 'Ifeoma Donaldson', 'Zeph Travis', 'Guardian', '09190002223', 'zeph@example.com'),
('MA25-0004', 112, 1, '7A', NULL, 'active', 'Catholic', 'Single', 'Jenna Mcbride', 'Blythe Simon', 'Kiara Willis', 'Guardian', '09190002224', 'kiara@example.com'),
('MA25-0005', 113, 2, '12A', NULL, 'active', 'Christian', 'Single', 'Jose Reyes', 'Ana Reyes', 'Pedro Cruz', 'Guardian', '09190002225', 'pedro@example.com'),
('MA25-0006', 114, 3, '11A', NULL, 'active', 'Catholic', 'Single', 'Roberto Santos', 'Luisa Santos', 'Carla Lopez', 'Guardian', '09190002226', 'carla@example.com'),
('MA25-0007', 115, 7, '5A', NULL, 'active', 'Christian', 'Single', 'Antonio Ramos', 'Elena Ramos', 'Juan Garcia', 'Guardian', '09190002227', 'juan@example.com'),
('MA25-0008', 116, 12, '8A', NULL, 'active', 'Catholic', 'Single', 'Jose De Guzman', 'Maria De Guzman', 'Pedro Martinez', 'Guardian', '09190002228', 'pedro.m@example.com'),
('MA25-0009', 117, 7, '5A', NULL, 'active', 'Christian', 'Single', 'Jose Reyes', 'Ana Reyes', 'Pedro Cruz', 'Guardian', '09190002229', 'pedro@example.com'),
('MA25-0010', 104, 7, '5A', NULL, 'active', 'Christian', 'Single', NULL, NULL, NULL, NULL, NULL, NULL),
('MA25-0011', 106, 13, 'In qui ad recusandae', NULL, 'active', 'Incididunt sit opti', 'Quisquam suscipit ea', 'Sean Walker', 'Brynn Haley', 'Megan Conrad', 'Occaecat aute corrupti quas voluptas quis dolore omnis sunt quam dolores modi velit dolore voluptatem Excepteur consectetur labore non', 'Iure et beatae in sed qui excepteur qui amet et sed voluptas', 'vynarojyz@mailinator.com'),
('MA25-0012', 122, 3, 'Molestias velit nul', NULL, 'active', 'Duis perferendis con', 'Excepturi non nihil', 'Burke Cash', 'Fredericka Peck', 'Gisela Cleveland', 'Consequatur reprehenderit quod tempore placeat sunt facilis vitae quos', 'Cumque aut consequatur vel est corporis optio assumenda ducimus aperiam sed mollitia architecto temporibus et vitae illum', 'potevojowy@mailinator.com'),
('MA25-0013', 123, 2, 'Corporis eveniet eu', NULL, 'active', 'Culpa fuga A et ex', 'Ut sit irure nobis', 'Lacota Reese', 'Felix Benjamin', 'Delilah Dejesus', 'Commodo iure unde adipisci consequatur non ex qui', 'Et voluptas nihil voluptatem duis sint quia', 'holidubi@mailinator.com');

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
(91, 'Christian James', NULL, 'Abendan', NULL, 'chrisbend2004@gmail.com', '09196105986', 'Male', NULL, NULL, 'default.jpg', '$2y$12$uoak2LLvUcRdh8v6cTBuienhINN5lUNq5nCcR8YXFlPgeWle1fRZe', NULL, NULL, '2025-08-30 04:06:53', 'parent', 'active', NULL, NULL, NULL, '2025-08-30 04:06:33', '2025-08-30 04:06:53'),
(104, 'Juan', 'Cruz', 'Reyes', '', 'juan.reyes@example.com', '09190000000', 'Male', '2010-05-20', '123 Mango St', 'default.png', '$2y$12$qo/GIOI9XCBvL/OHJgOUWu6UFXDHiFvjFUogtF9tXsQ4iQvpheT5u', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-04 00:22:18', '2025-09-04 00:22:18'),
(106, 'Macy', 'Brianna Vinson', 'Dunlap', NULL, 'hypiwisut@mailinator.com', '24', 'Male', '1991-06-02', 'Nam sed aut incidunt', 'default.jpg', '$2y$12$kU296yJirlSEKsBiuK4G8uJkrwSErDCNRrXoGtpBvLZah4xjhnkeu', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-04 19:52:27', '2025-09-04 19:52:27'),
(107, 'Sawyer', 'Quyn Hunt', 'Harvey', NULL, 'sawyer@mailinator.com', '205', NULL, NULL, NULL, 'download.jpg', '$2y$12$fP7uq8ol3Am8b5vYMgsBNOl6tcXCzSj0LP4j3Pq61KovVfodNzSry', NULL, NULL, NULL, 'counselor', 'pending', NULL, NULL, NULL, '2025-09-04 22:30:12', '2025-09-04 22:31:13'),
(109, 'David', NULL, 'Romano', NULL, 'chebry28@gmail.com', '09817970638', NULL, NULL, NULL, 'default.jpg', '$2y$12$3NhrbdSOsTEbas98MqNvpOmfCkTVOCFgc6D36yHtPSyP/MBn6yeOS', NULL, NULL, NULL, 'parent', 'active', NULL, NULL, NULL, '2025-09-05 01:55:33', '2025-09-05 01:55:33'),
(110, 'Sailas', NULL, 'Villondo', NULL, 'davidvillondo@gmail.com', '09817970638', NULL, NULL, NULL, 'default.jpg', '$2y$12$KmYH/Z31gWq3aUESjhoSpuFwh5u/8dAr66P2oH7xZg8GvPvd2ZdAC', NULL, NULL, NULL, 'parent', 'active', NULL, NULL, NULL, '2025-09-05 01:58:51', '2025-09-05 01:58:51'),
(111, 'Alika', 'Allegra', 'Shepard', '', 'dusyz@mailinator.com', '09190000003', 'Female', '2017-11-23', '123 Apple St', 'default.png', '$2y$12$nUNkbUK4tZVrNuVbR65ynO.v/.Pteo1TP1beHlEZM5LTzTGJ05kUm', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-05 19:49:14', '2025-09-05 19:49:14'),
(112, 'Hillary', 'Alfreda', 'Maldonado', '', 'gegepefan@mailinator.com', '09190000004', 'Male', '1986-07-06', '456 Banana Ave', 'default.png', '$2y$12$uM7I9FiL2/K.uKgDk.ux5uW6.d9rDvy8cN.XeaoTb9EOQS5NIvxMW', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-05 19:49:15', '2025-09-05 19:49:15'),
(113, 'Rama', 'Ryder', 'Hendrix', '', 'pibofykag@mailinator.com', '09190000005', 'Male', '2002-03-10', '789 Pine St', 'default.png', '$2y$12$nrsIzRuz0b3zf2E6vnFtpeMGA.FaHMLAlAhL1NiknCm0HRgzU1HqG', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-05 19:49:15', '2025-09-05 19:49:15'),
(114, 'Jillian', 'Dorian', 'Stafford', '', 'tysoquniq@mailinator.com', '09190000006', 'Female', '1999-03-18', '321 Mango St', 'default.png', '$2y$12$EAECDnHGU2yfwzvkVjKQueh2iYLkFyb7F8NEF2WROsB5qhNgJtyU.', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-05 19:49:15', '2025-09-05 19:49:15'),
(115, 'Chelsea', 'Tucker', 'Sullivan', '', 'xegegebewe@mailinator.com', '09190000007', 'Male', '2014-04-24', '654 Orange St', 'default.png', '$2y$12$Zl4TvWefaeGYqsHZ0o8le.NoyvCYPXjuMudFXaTfdpyRE733ZcU2u', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-05 19:49:15', '2025-09-05 19:49:15'),
(116, 'Madeson', 'Justina', 'Henry', '', 'byfidufyx@mailinator.com', '09190000008', 'Female', '2005-12-20', '987 Lemon St', 'default.png', '$2y$12$XZ.BGYBYhaJ/3rqedCXQE.V6owDndp/8Rpo5lldZkQb4ZbSZNFRLG', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-05 19:49:16', '2025-09-05 19:49:16'),
(117, 'Juan', 'Cruz', 'Reyes', '', 'juan.reyes@example.com', '09190000009', 'Male', '2010-05-20', '123 Mango St', 'default.png', '$2y$12$KinAGNeMXEszA3nfh9o1ZulEJbZwAjXWREYV4.TUtAP065ykgiNr6', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-05 19:49:16', '2025-09-05 19:49:16'),
(118, 'Cailin', NULL, 'Summers', NULL, 'channity.wr@gmail.com', '+631234567891', 'Male', NULL, NULL, 'default.jpg', '$2y$12$TNZ0vqwsh9Q8lGni3ZVycumVz1jHSgQR9lqkUhP5KwzHiNhWt/Z3C', 'y3JvPaUAutANbzpqfvyB7rdHxH2m1gGiPwBi7Vjm1XnOtKeqeRiIeL8HusTntfbz', '2025-09-08 02:35:01', NULL, 'parent', 'pending', NULL, NULL, NULL, '2025-09-08 00:35:01', '2025-09-08 00:35:01'),
(119, 'Keefe', NULL, 'Oneill', NULL, 'arquilos.cj@gmail.com', '+631236549871', 'Male', NULL, NULL, 'default.jpg', '$2y$12$B9AkYAPXzvZHp0mAZvYdaei74Yv6m4PfxOVxIXuCGSc256/f8k4XO', NULL, NULL, '2025-09-08 00:49:59', 'parent', 'active', NULL, NULL, NULL, '2025-09-08 00:46:25', '2025-09-08 00:49:59'),
(121, 'Tyrone', NULL, 'Wynn', NULL, 'abendan.c.j.04@gmail.com', '+633214568791', 'Male', NULL, NULL, 'default.jpg', '$2y$12$JTvSC9R0cym70RyyAb5B1OVi4DDt2pPi45T0Ll6XWMBqKJLHONVra', NULL, NULL, '2025-09-08 00:56:33', 'parent', 'active', NULL, NULL, NULL, '2025-09-08 00:55:10', '2025-09-08 00:56:33'),
(122, 'Wylie', 'Wallace Black', 'Marks', NULL, 'botezace@mailinator.com', '121', 'Female', '1993-12-03', 'Voluptatem velit i', 'default.jpg', '$2y$12$fnB1aieFPvEcYHjl7vJqK.Pp2Q2NFcLHTFWcDioyr9aJyn31nQFuK', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-08 22:14:20', '2025-09-08 22:14:20'),
(123, 'Knox', 'Whilemina Levine', 'Moreno', NULL, 'nusara@mailinator.com', '491', 'Male', '2016-02-02', 'Magnam dolorem repud', 'default.jpg', '$2y$12$JABnSbylYPM87uq2Pp6CXeLLFU36LX4c1mDCESAo75xAe82QJ2/dG', NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-08 22:14:28', '2025-09-08 22:14:28');

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
  MODIFY `case_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `case_types`
--
ALTER TABLE `case_types`
  MODIFY `type_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `p_id` bigint(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `parent_link_requests`
--
ALTER TABLE `parent_link_requests`
  MODIFY `request_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `parent_link_request_students`
--
ALTER TABLE `parent_link_request_students`
  MODIFY `pls_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `parent_student`
--
ALTER TABLE `parent_student`
  MODIFY `ps_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

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
-- Constraints for table `counselors`
--
ALTER TABLE `counselors`
  ADD CONSTRAINT `counselors_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `document_requests`
--
ALTER TABLE `document_requests`
  ADD CONSTRAINT `document_requests_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`p_id`) ON DELETE CASCADE;

--
-- Constraints for table `document_request_students`
--
ALTER TABLE `document_request_students`
  ADD CONSTRAINT `document_request_students_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `document_requests` (`request_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_drs_student` FOREIGN KEY (`s_id`) REFERENCES `students` (`s_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
