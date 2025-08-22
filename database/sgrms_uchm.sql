-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2025 at 11:46 PM
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
  `body` text NOT NULL,
  `link` text NOT NULL,
  `category` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `date_posted` date NOT NULL,
  `status` enum('active','archived') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `user_id`, `title`, `description`, `body`, `link`, `category`, `image`, `date_posted`, `status`, `created_at`, `updated_at`) VALUES
(4, 1, 'Spring Semester Registration Open', 'Registration for the spring semester is now open. Students are encouraged to meet with their class advisors to finalize their tuition.', '\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam a massa ac nulla eleifend ornare. Integer at tellus luctus, viverra risus quis, elementum metus. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras eu bibendum mauris. In nec commodo urna. Aenean bibendum pharetra felis, in pulvinar magna posuere vitae. Quisque sem ante, suscipit ut suscipit quis, venenatis ac ligula. Morbi ornare lectus eros. Nullam consectetur eu tortor ac bibendum. Quisque sagittis metus a lacus aliquam lacinia. Donec cursus enim id nibh fermentum, eu imperdiet purus placerat.\r\n\r\nAenean sit amet finibus nunc. Sed at porttitor ipsum. Pellentesque mollis vitae tellus sit amet sagittis. Nullam pellentesque a felis sed mattis. Suspendisse accumsan ex et elit luctus aliquet. Suspendisse potenti. Nunc elementum urna vel leo rhoncus, in blandit nibh sollicitudin. Proin eu faucibus lectus, quis facilisis libero. Mauris vel dictum massa. Vestibulum ac odio eu justo commodo pulvinar. Fusce maximus lorem sed quam iaculis cursus. Morbi pretium ligula tellus, eu mollis lectus aliquam a.\r\n\r\nDonec feugiat auctor justo. Vivamus feugiat maximus arcu, eget pharetra turpis hendrerit eget. Nam vel justo et sapien imperdiet iaculis. Phasellus interdum lorem quam, quis pretium sem posuere in. Etiam euismod dui lorem, sed dapibus lectus rhoncus nec. Donec in efficitur sem. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vivamus ex mi, ullamcorper eu velit in, ultricies sollicitudin dolor. In ac lacus eros. Sed eget magna id justo aliquam pellentesque sed non quam. Morbi semper pretium urna. Cras egestas accumsan massa. Praesent vel mi vitae tellus tristique pretium vehicula et dui. Integer augue nibh, laoreet quis erat a, posuere fermentum est. Donec id hendrerit neque, ac efficitur ligula. In bibendum libero eget eros molestie, at pulvinar nunc egestas.\r\n\r\nInteger pulvinar elit ligula, at aliquam est sagittis eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ligula mi, sodales at sapien et, consequat placerat augue. Sed fermentum fermentum eros, dignissim suscipit justo sagittis sit amet. Phasellus pulvinar elementum sem ac interdum. In at orci quis sapien tincidunt volutpat. Donec a tincidunt leo, quis eleifend massa. Cras gravida iaculis malesuada. Vestibulum id est vel ex convallis congue. Integer dignissim id augue eget accumsan. Nunc eros ex, scelerisque nec magna eu, molestie ultricies quam. Proin convallis dolor et dolor condimentum aliquet a in orci.\r\n\r\nVivamus quis augue sed erat viverra mollis nec ac tellus. Sed porttitor blandit elementum. Donec fringilla purus in elit hendrerit, a cursus metus vehicula. Phasellus molestie justo quis est pulvinar, sit amet fermentum augue posuere. Vivamus tincidunt metus nisi, ut viverra augue ultricies sed. Phasellus ultricies placerat lacinia. Curabitur neque magna, sagittis ut venenatis ut, mattis in dolor. Nulla pharetra sapien id erat consectetur tempor. Nunc in hendrerit turpis. Nunc lectus lacus, placerat semper pellentesque ac, venenatis eu nisi. Aenean neque metus, commodo nec lacus ut, tempor pretium urna. Suspendisse potenti. Maecenas nisi purus, hendrerit at pellentesque ut, egestas eu libero. Morbi aliquet et metus lacinia hendrerit. Ut ac interdum neque. Ut maximus accumsan mauris, at consectetur nisi', '', 'Announcements', 'a1.jpg', '2025-08-17', 'active', '2025-08-17 19:56:31', '2025-08-17 21:57:29'),
(5, 1, 'No Classes on August 21 ', 'In observance of Ninoy Aquino Day—a special non-working holiday in the Philippines—there will be no classes on Thursday, August 21, 2025.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam a massa ac nulla eleifend ornare. Integer at tellus luctus, viverra risus quis, elementum metus. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras eu bibendum mauris. In nec commodo urna. Aenean bibendum pharetra felis, in pulvinar magna posuere vitae. Quisque sem ante, suscipit ut suscipit quis, venenatis ac ligula. Morbi ornare lectus eros. Nullam consectetur eu tortor ac bibendum. Quisque sagittis metus a lacus aliquam lacinia. Donec cursus enim id nibh fermentum, eu imperdiet purus placerat.\r\n\r\nAenean sit amet finibus nunc. Sed at porttitor ipsum. Pellentesque mollis vitae tellus sit amet sagittis. Nullam pellentesque a felis sed mattis. Suspendisse accumsan ex et elit luctus aliquet. Suspendisse potenti. Nunc elementum urna vel leo rhoncus, in blandit nibh sollicitudin. Proin eu faucibus lectus, quis facilisis libero. Mauris vel dictum massa. Vestibulum ac odio eu justo commodo pulvinar. Fusce maximus lorem sed quam iaculis cursus. Morbi pretium ligula tellus, eu mollis lectus aliquam a.', '', 'Announcements', 'default.png', '2025-08-15', 'active', '2025-08-15 20:00:12', '2025-08-17 21:57:26'),
(6, 1, 'Congratulations Batch 2024-2025', 'We are incredibly proud of your hard work, dedication, and achievements. Wishing you all the best in your next chapter — the future is yours! ', '\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam a massa ac nulla eleifend ornare. Integer at tellus luctus, viverra risus quis, elementum metus. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras eu bibendum mauris. In nec commodo urna. Aenean bibendum pharetra felis, in pulvinar magna posuere vitae. Quisque sem ante, suscipit ut suscipit quis, venenatis ac ligula. Morbi ornare lectus eros. Nullam consectetur eu tortor ac bibendum. Quisque sagittis metus a lacus aliquam lacinia. Donec cursus enim id nibh fermentum, eu imperdiet purus placerat.\r\n\r\nAenean sit amet finibus nunc. Sed at porttitor ipsum. Pellentesque mollis vitae tellus sit amet sagittis. Nullam pellentesque a felis sed mattis. Suspendisse accumsan ex et elit luctus aliquet. Suspendisse potenti. Nunc elementum urna vel leo rhoncus, in blandit nibh sollicitudin. Proin eu faucibus lectus, quis facilisis libero. Mauris vel dictum massa. Vestibulum ac odio eu justo commodo pulvinar. Fusce maximus lorem sed quam iaculis cursus. Morbi pretium ligula tellus, eu mollis lectus aliquam a.\r\n\r\nDonec feugiat auctor justo. Vivamus feugiat maximus arcu, eget pharetra turpis hendrerit eget. Nam vel justo et sapien imperdiet iaculis. Phasellus interdum lorem quam, quis pretium sem posuere in. Etiam euismod dui lorem, sed dapibus lectus rhoncus nec. Donec in efficitur sem. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vivamus ex mi, ullamcorper eu velit in, ultricies sollicitudin dolor. In ac lacus eros. Sed eget magna id justo aliquam pellentesque sed non quam. Morbi semper pretium urna. Cras egestas accumsan massa. Praesent vel mi vitae tellus tristique pretium vehicula et dui. Integer augue nibh, laoreet quis erat a, posuere fermentum est. Donec id hendrerit neque, ac efficitur ligula. In bibendum libero eget eros molestie, at pulvinar nunc egestas.', '', 'News', 'a3.jpg', '2025-07-24', 'active', '2025-07-24 20:01:44', '2025-08-17 21:46:08'),
(7, 1, 'Parent-Teacher Conferences Next Week', 'Parent-Teacher conferences will be held from April 15th to April 17th. Please schedule your appointments through the school portal.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam a massa ac nulla eleifend ornare. Integer at tellus luctus, viverra risus quis, elementum metus. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras eu bibendum mauris. In nec commodo urna. Aenean bibendum pharetra felis, in pulvinar magna posuere vitae. Quisque sem ante, suscipit ut suscipit quis, venenatis ac ligula. Morbi ornare lectus eros. Nullam consectetur eu tortor ac bibendum. Quisque sagittis metus a lacus aliquam lacinia. Donec cursus enim id nibh fermentum, eu imperdiet purus placerat.', '', 'Announcements', 'default.png', '2025-05-12', 'active', '2025-05-12 20:04:03', '2025-08-17 21:57:32'),
(8, 1, 'New Script for Grow A Garden!', 'We are pleased to announce the release of a new script for Grow A Garden. This update brings enhanced functionality to support a more efficient and seamless user experience.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin erat sapien, lobortis at luctus ut, efficitur non tortor. Cras aliquet, sem nec sodales malesuada, ipsum mi ornare lorem, nec commodo massa ligula ac elit. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed leo massa, condimentum et ornare sit amet, mattis quis metus. Donec rutrum arcu sed ex porta, at varius mi lacinia. Nulla a pulvinar ipsum, nec dictum dolor. Nullam rhoncus tincidunt libero. Proin sed viverra leo, vel consectetur nibh. Mauris varius tortor ac felis sodales fringilla. Donec sed euismod orci. Mauris vehicula eu nunc nec mollis.', '', 'News', 'default.png', '2025-08-17', 'active', '2025-08-18 01:58:25', '2025-08-19 23:03:29');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `requester_name` varchar(100) NOT NULL,
  `requester_type` enum('parent','teacher') NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','completed','canceled') NOT NULL DEFAULT 'pending',
  `counselor_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `requester_name` varchar(100) NOT NULL,
  `requester_type` enum('parent','teacher') NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','completed','canceled') NOT NULL DEFAULT 'pending',
  `counselor_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointment_history`
--

CREATE TABLE `appointment_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `appointment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` enum('created','approved','rescheduled','canceled','completed') NOT NULL,
  `old_date` date DEFAULT NULL,
  `old_time` time DEFAULT NULL,
  `new_date` date DEFAULT NULL,
  `new_time` time DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `action_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `case_records`
--

CREATE TABLE `case_records` (
  `case_id` bigint(20) UNSIGNED NOT NULL,
  `case_type` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `reported_by` varchar(100) NOT NULL,
  `referred_by` varchar(100) NOT NULL,
  `referral_date` datetime NOT NULL,
  `reason_for_referral` varchar(100) NOT NULL,
  `presenting_problem` varchar(100) NOT NULL,
  `observe_behavior` text NOT NULL,
  `family_background` varchar(100) NOT NULL,
  `academic_history` varchar(100) NOT NULL,
  `social_relationships` varchar(100) NOT NULL,
  `medical_history` varchar(500) NOT NULL,
  `counselor_assessment` varchar(500) NOT NULL,
  `recommendations` varchar(500) NOT NULL,
  `follow_up_plan` varchar(500) NOT NULL,
  `filed_date` date NOT NULL DEFAULT curdate(),
  `filed_time` time NOT NULL DEFAULT curtime(),
  `status` varchar(50) NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('C0001', 2),
('C0002', 3),
('C0003', 4);

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
(44, '2025_07_22_162807_add_school_address_religion_status_to_students_table', 6);

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
  `p_id` varchar(50) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `relationship` varchar(50) DEFAULT NULL
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
  `id_num` varchar(20) NOT NULL,
  `educ_level` varchar(50) DEFAULT NULL,
  `year_level` varchar(20) NOT NULL,
  `section` varchar(20) DEFAULT NULL,
  `program` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  `parent_id` varchar(50) DEFAULT NULL,
  `previous_school_address` varchar(255) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `civil_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `contact_num` varchar(20) DEFAULT NULL,
  `sex` enum('Male','Female') DEFAULT NULL,
  `bod` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) NOT NULL DEFAULT 'default.png',
  `password` varchar(255) NOT NULL,
  `role` enum('admin','counselor','student','parent') NOT NULL,
  `status` enum('active','inactive','pending') DEFAULT 'active',
  `remember_token` varchar(100) DEFAULT NULL,
  `login_token` datetime DEFAULT NULL,
  `token_expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `email`, `contact_num`, `sex`, `bod`, `address`, `profile_image`, `password`, `role`, `status`, `remember_token`, `login_token`, `token_expires_at`, `created_at`, `updated_at`) VALUES
(1, 'Christine', 'Arquilos', 'Abendan', 'abendan@gmail.com', '09123456789', 'Female', NULL, NULL, 'christine.png', '$2y$12$4qszi98KSvD9Szf8IU5fnu2W132eqPHcbjE6Y28cY0Ttc4YNaoe1u', 'admin', 'active', NULL, NULL, NULL, '2025-08-08 05:36:51', '2025-08-08 05:36:51'),
(2, 'Johanna', 'Decena', 'Plameran', 'jb@gmail.com', '09123456789', 'Female', NULL, NULL, 'johanna.png', '$2b$12$dfSB3gpNKyv8LV4XqtVVg..KqAJPIuKCUxB/rATBosqxQBKpMkvC6', 'counselor', 'active', NULL, NULL, NULL, '2025-06-11 10:42:22', '2025-06-11 10:42:22'),
(3, 'Divine', 'Villondo', 'Romano', 'dasai@gmail.com', '123454678', 'Female', '2024-11-14', 'Adadspadk0', 'divine.png', '$2b$12$dfSB3gpNKyv8LV4XqtVVg..KqAJPIuKCUxB/rATBosqxQBKpMkvC6', 'counselor', 'active', NULL, NULL, NULL, '2025-08-09 08:09:50', '2025-08-09 08:09:50'),
(4, 'asd', 'adsa', 'dsadadsada', 'a@mail.com', '123456', NULL, NULL, NULL, 'default.png', '$2y$12$6OZPdh1iLGm7SBZlhqmbhuKDdNuV0r/fbmzyEHAjvS916eAdRM.k6', 'counselor', 'pending', NULL, NULL, NULL, '2025-08-22 03:37:24', '2025-08-22 03:37:24');

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointment_history`
--
ALTER TABLE `appointment_history`
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
-- Indexes for table `case_records`
--
ALTER TABLE `case_records`
  ADD PRIMARY KEY (`case_id`);

--
-- Indexes for table `counselors`
--
ALTER TABLE `counselors`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `user_id` (`user_id`);

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
  ADD KEY `students_parent_fk` (`parent_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

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
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`p_id`),
  ADD CONSTRAINT `students_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
