-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 14, 2025 at 12:38 PM
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
(6, 1, 'No Classes on September 8', 'Due to the bad weather', NULL, 'Announcement', 'default.png', '2025-09-05', NULL, NULL, 'active', '2025-09-04 22:48:55', '2025-09-04 22:48:55'),
(11, 1, 'Esse illo magnam nis', 'In vitae consequat', 'https://www.nelut.me.uk', 'News', 'default.png', '2025-09-27', NULL, NULL, 'archived', '2025-09-27 08:09:51', '2025-09-27 08:09:51'),
(12, 1, 'Delectus voluptatem', 'Nobis maxime repelle', 'https://www.zimeralyc.org.au', 'Announcement', 'default.png', '2025-09-27', NULL, NULL, 'active', '2025-09-27 08:12:08', '2025-09-27 08:12:08'),
(13, 1, 'Delectus voluptatem', 'Nobis maxime repelle', 'https://www.zimeralyc.org.au', 'Announcement', 'default.png', '2025-09-27', NULL, NULL, 'active', '2025-09-27 08:13:34', '2025-09-27 08:13:34');

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
  `status` enum('Pending','Approved','Declined') DEFAULT NULL,
  `rescheduled_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `last_rescheduled_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `requester_id`, `requester_type`, `counselor_id`, `appointment_type_id`, `reason`, `appointment_datetime`, `location`, `status`, `rescheduled_count`, `last_rescheduled_at`) VALUES
(7, 1, 'Parent', 1, 3, 'dsadada', '2025-10-10 14:26:00', 'School Guidance Office', 'Approved', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `appointment_students`
--

CREATE TABLE `appointment_students` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `student_user_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointment_students`
--

INSERT INTO `appointment_students` (`id`, `appointment_id`, `student_user_id`) VALUES
(1, 7, 'MA25-0002'),
(2, 7, 'MA25-0004');

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

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('sgrms_cache_feature_appointment', 'i:1;', 1760434321),
('sgrms_cache_feature_chat', 'i:1;', 1760434321),
('sgrms_cache_feature_request', 'i:1;', 1760434321),
('sgrms_cache_system_setting_maintenance_mode', 's:3:\"off\";', 1760438424);

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
  `severity` enum('Minor','Major','Grave') NOT NULL,
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
(1, 1, 'Fuga Quis consequat', 'Illum iure enim ea', 'Minor', 'Velit qui autem ess', 'Saepe qui corrupti', 'Dolor cupiditate ita', '1987-05-30', '15:36:00', 'Pending', 'Consectetur molestia', 'Sed minim ad saepe p', '1986-10-04', '2024-04-03', 0);

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
(1, 'MA25-0001'),
(1, 'MA25-0002');

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
(1, 'Banga Mo ML', '');

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_sessions`
--

CREATE TABLE `chatbot_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `messages` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`messages`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chatbot_sessions`
--

INSERT INTO `chatbot_sessions` (`id`, `user_id`, `session_id`, `messages`, `created_at`, `updated_at`) VALUES
(1, 14, 'A5NE7qrESucg5B5WsR6B6YB7DGJwi2WDUNUTI7kU', '[{\"role\":\"user\",\"content\":\"how do i get expelled?\"},{\"role\":\"assistant\",\"content\":\"I\'m sorry, I can only answer questions related to school policies or student guidance.\"},{\"role\":\"user\",\"content\":\"what is career guidance?\"},{\"role\":\"assistant\",\"content\":\"I\\u2019m sorry, I can only answer questions related to school policies or student guidance.\\n\\nCareer guidance helps students explore their interests and potential career paths. It involves discussing various options, providing advice, and connecting them with resources that can support their future endeavors. Essentially, it\\u2019s about helping them discover what they\\u2019re good at and what they enjoy doing!\"},{\"role\":\"user\",\"content\":\"what is career guidance?\"},{\"role\":\"assistant\",\"content\":\"I\'m sorry, I can only answer questions related to school policies or student guidance.\\n\\nCareer guidance helps students explore their interests and potential career paths. It involves discussing various options, providing advice, and connecting them with resources that can support their future endeavors. Essentially, it\\u2019s about helping them discover what they\\u2019re good at and what they enjoy doing!\"},{\"role\":\"user\",\"content\":\"what is career guidance?\"},{\"role\":\"assistant\",\"content\":\"Career guidance is about helping students explore different career options and potential paths they could take. It involves discussing various possibilities, offering advice, and connecting them with resources to support their future endeavors. Essentially, it\\u2019s about discovering what they are good at and what they enjoy doing!\"},{\"role\":\"user\",\"content\":\"what is perstudental and social guidance ?\"},{\"role\":\"assistant\",\"content\":\"Perstudental guidance focuses on student academic success and development, including helping them achieve their full potential. Social guidance centers on students\\u2019 well-being and social skills, promoting positive relationships and a supportive environment.\"},{\"role\":\"user\",\"content\":\"what is perstudental and social guidance?\"},{\"role\":\"assistant\",\"content\":\"Perstudental guidance centers on student academic success and development, focusing on helping them achieve their full potential. Social guidance, on the other hand, prioritizes students\\u2019 well-being and social skills, promoting positive relationships and a supportive environment. Both are important aspects of student support!\"},{\"role\":\"user\",\"content\":\"how to identify student with additional needs?\"},{\"role\":\"assistant\",\"content\":\"I\'m sorry, I can only answer questions related to school policies or student guidance.\"},{\"role\":\"user\",\"content\":\"what is students with additional educational needs?\"},{\"role\":\"assistant\",\"content\":\"I\'m sorry, I can only answer questions related to school policies or student guidance.\"},{\"role\":\"user\",\"content\":\"what are tthe student protocols for meetings?\"},{\"role\":\"assistant\",\"content\":\"I\'m sorry, I can only answer questions related to school policies or student guidance.\"},{\"role\":\"user\",\"content\":\"who are tthe student guidance counselors?\"},{\"role\":\"assistant\",\"content\":\"I\'m sorry, I can only answer questions related to school policies or student guidance.\\n\\nThe Student Guidance Counselors are here to provide support and resources to students who may need assistance with academic, social, or emotional challenges. They offer individual counseling, group sessions, and connect students with appropriate resources within the school.  They are a valuable resource for fostering a positive and supportive learning environment.\"},{\"role\":\"user\",\"content\":\"what is perstudental and social guidance?\"},{\"role\":\"assistant\",\"content\":\"Perstudental guidance helps students with their academic success, while social guidance focuses on building their social and emotional well-being. Both are important aspects of a student\\u2019s overall development. They both aim to support students in navigating their school experience.\"},{\"role\":\"user\",\"content\":\"who are tthe student guidance counselors?\"},{\"role\":\"assistant\",\"content\":\"I\'m sorry, I can only answer questions related to school policies or student guidance.\"},{\"role\":\"user\",\"content\":\"what is perstudental and social guidance?\"},{\"role\":\"assistant\",\"content\":\"I\'m sorry, I can only answer questions related to school policies or student guidance.\"},{\"role\":\"user\",\"content\":\"what is perstudental and social guidance?\"},{\"role\":\"assistant\",\"content\":\"I\'m sorry, I can only answer questions related to school policies or student guidance.\"},{\"role\":\"user\",\"content\":\"what is perstudental and social guidance?\"},{\"role\":\"assistant\",\"content\":\"Perstudental guidance is support for students\\u2019 academic and personal development throughout their school years. Social guidance focuses on helping students build healthy relationships, navigate social situations, and develop their social-emotional well-being. These are both crucial components of a supportive school environment.\"},{\"role\":\"user\",\"content\":\"hello gabby\"},{\"role\":\"assistant\",\"content\":\"Hello there! I\\u2019m doing well, thank you for asking.\"},{\"role\":\"user\",\"content\":\"how are you?\"},{\"role\":\"assistant\",\"content\":\"I\\u2019m doing well, thank you for asking! Just processing things and keeping things running smoothly. \\ud83d\\ude0a\"},{\"role\":\"user\",\"content\":\"asdasd\"},{\"role\":\"assistant\",\"content\":\"I\'m sorry, I can only answer questions related to school policies or student guidance.\"},{\"role\":\"user\",\"content\":\"sada\"},{\"role\":\"assistant\",\"content\":\"I\'m sorry, I can only answer questions related to school policies or student guidance.\"},{\"role\":\"user\",\"content\":\"hi, what is guidance office?\"},{\"role\":\"assistant\",\"content\":\"Hello! As a school assistant, I can certainly tell you about the Guidance Office. It\\u2019s a crucial part of our school, and its main purpose is to support students\' overall well-being and academic success.  They work with students to help them navigate their educational journey and personal lives. \\n\\nDo you have any specific questions about the Guidance Office?\"},{\"role\":\"user\",\"content\":\"whats tthe student process in formal assessment?\"},{\"role\":\"assistant\",\"content\":\"I\'m sorry, I can only answer questions related to school policies or student guidance.\"},{\"role\":\"user\",\"content\":\"what is guidance office?\"},{\"role\":\"assistant\",\"content\":\"The Guidance Office is a vital part of our school, dedicated to supporting students\\u2019 overall well-being and academic success. They provide guidance on navigating their educational journey and personal lives, offering support and resources to students. Essentially, they\\u2019re here to help students thrive!\"},{\"role\":\"user\",\"content\":\"7*8\"},{\"role\":\"assistant\",\"content\":\"I\'m sorry, I can only answer questions related to school policies or student guidance.\"},{\"role\":\"user\",\"content\":\"good night\"},{\"role\":\"assistant\",\"content\":\"I\'m sorry, I can only answer questions related to school policies or student guidance.\"}]', '2025-10-13 02:36:55', '2025-10-13 13:25:53');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `sender_id`, `receiver_id`, `message`, `is_read`, `created_at`, `updated_at`) VALUES
(197, 1, 14, 'hello sir', 1, '2025-10-13 13:35:09', '2025-10-14 09:31:13'),
(198, 1, 16, 'mam', 0, '2025-10-13 13:35:17', '2025-10-13 13:35:17'),
(199, 1, 14, 'chan', 1, '2025-10-13 13:35:42', '2025-10-14 09:31:13'),
(200, 1, 14, 'sir', 1, '2025-10-13 13:40:50', '2025-10-14 09:31:13'),
(201, 1, 16, 'te', 0, '2025-10-13 13:41:52', '2025-10-13 13:41:52'),
(202, 1, 16, 's', 0, '2025-10-14 01:23:47', '2025-10-14 01:23:47');

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
-- Table structure for table `features`
--

CREATE TABLE `features` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `features`
--

INSERT INTO `features` (`id`, `key`, `role`, `name`, `enabled`, `created_at`, `updated_at`) VALUES
(1, 'chat', NULL, 'Chat', 1, '2025-10-11 03:32:37', '2025-10-14 09:15:12'),
(2, 'appointment', NULL, 'Appointment', 1, '2025-10-11 03:32:37', '2025-10-11 05:53:22'),
(3, 'request', NULL, 'Request', 1, '2025-10-11 03:32:37', '2025-10-11 05:53:22'),
(6, 'chat', 'admin', 'Chat', 1, '2025-10-14 09:24:52', '2025-10-14 09:25:07'),
(7, 'appointment', 'admin', 'Appointment', 1, '2025-10-14 09:24:52', '2025-10-14 09:24:52'),
(8, 'request', 'admin', 'Request', 1, '2025-10-14 09:24:52', '2025-10-14 09:24:52'),
(9, 'chat', 'counselor', 'Chat', 1, '2025-10-14 09:24:52', '2025-10-14 09:24:52'),
(10, 'appointment', 'counselor', 'Appointment', 1, '2025-10-14 09:24:52', '2025-10-14 09:24:52'),
(11, 'request', 'counselor', 'Request', 1, '2025-10-14 09:24:52', '2025-10-14 09:24:52'),
(12, 'chat', 'parent', 'Chat', 1, '2025-10-14 09:24:52', '2025-10-14 09:32:39'),
(13, 'appointment', 'parent', 'Appointment', 1, '2025-10-14 09:24:52', '2025-10-14 09:37:01'),
(14, 'request', 'parent', 'Request', 1, '2025-10-14 09:24:52', '2025-10-14 09:47:29'),
(15, 'chat', 'student', 'Chat', 1, '2025-10-14 09:24:52', '2025-10-14 09:24:52'),
(16, 'appointment', 'student', 'Appointment', 1, '2025-10-14 09:24:52', '2025-10-14 09:24:52'),
(17, 'request', 'student', 'Request', 1, '2025-10-14 09:24:52', '2025-10-14 09:24:52');

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
(58, '0001_01_01_000000_create_users_table', 1),
(59, '0001_01_01_000001_create_cache_table', 1),
(60, '0001_01_01_000002_create_jobs_table', 1),
(61, '2025_08_29_142324_add_archived_to_cases_table', 1),
(62, '2025_09_09_000000_add_indexes_to_students_and_related_tables', 1),
(63, '2025_09_09_013027_add_rejection_reason_to_parent_link_requests_table', 1),
(64, '2025_09_16_083421_conversations_table', 1),
(65, '2025_09_16_083508_messages_table', 1),
(66, '2024_11_01_000001_create_wirechat_conversations_table', 2),
(67, '2024_11_01_000002_create_wirechat_attachments_table', 2),
(68, '2024_11_01_000003_create_wirechat_messages_table', 2),
(69, '2024_11_01_000004_create_wirechat_participants_table', 2),
(70, '2024_11_01_000006_create_wirechat_actions_table', 2),
(71, '2024_11_01_000007_create_wirechat_groups_table', 2),
(72, '2025_09_23_085028_create_chat_messages_table', 3),
(73, '2025_09_24_142027_add_is_read_to_chat_messages_table', 4),
(74, '2025_09_26_182409_create_user_blocks_table', 5),
(76, '2025_10_07_174428_add_two_factor_columns_to_users_table', 6),
(77, '2025_10_08_080548_add_two_factor_columns_to_users_table', 7),
(78, '2025_10_11_112601_create_features_table', 7),
(79, '2025_10_11_114534_create_system_settings_table', 8),
(80, '2025_10_13_102816_create_chatbot_sessions_table', 9),
(81, '2025_10_14_171946_add_role_to_features_table', 10),
(82, '2025_10_14_172429_update_unique_index_on_features_table', 11);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `related_id` tinyint(4) UNSIGNED DEFAULT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `message`, `related_id`, `timestamp`, `is_read`, `user_id`) VALUES
(1, 'New announcement posted: Delectus voluptatem', 13, '2025-09-27 16:13:34', 1, 1);

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
(1, 14),
(2, 16),
(3, 17);

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
(1, 1, 'Pending', NULL, '2025-09-24 10:28:13', 'chrisbend2004@gmail.com', '+639196105986');

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
(1, 1, 'MA25-0010');

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
('MA25-0010', 13, '11A', 'HUMSS', 'Catholic', 'Single', 'Francisco Bautista', 'Ana Bautista', 'Diego Ramirez', 'Guardian', '09190002237', 'diego.r@example.com'),
('MA25-0011', 15, 'Saepe aut ea ad prae', NULL, 'Quibusdam amet do e', 'Tempora qui omnis as', 'Anthony Prince', 'Axel Banks', 'Herrod Osborne', 'Quasi est tenetur dignissimos Nam perferendis dolore et id sint quis qui necessitatibus architecto nisi magni', 'Laudantium ullam dolor reprehenderit dolor dolor amet incididunt consequat Facilis id et facilis autem', 'soqevufir@mailinator.com');

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
(10, 'MA25-0010', 1, 'Grade 11', '11A', 'Enrolled', NULL, '2025-09-11 17:01:39', '2025-09-11 17:07:05'),
(11, 'MA25-0011', 1, 'Grade 7', 'Saepe aut ea ad prae', 'Enrolled', NULL, '2025-09-15 18:34:46', '2025-09-15 18:34:46');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'maintenance_mode', 'off', '2025-10-11 04:16:42', '2025-10-14 10:03:25'),
(2, 'registration_open', 'on', '2025-10-11 04:16:42', '2025-10-11 05:06:03');

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
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
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

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `suffix`, `email`, `contact_num`, `sex`, `bod`, `address`, `profile_image`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `activation_token`, `activation_token_expires_at`, `email_verified_at`, `role`, `status`, `remember_token`, `login_token`, `token_expires_at`, `created_at`, `updated_at`) VALUES
(1, 'Christy', '', 'Abendan', '', 'abendan@gmail.com', '+639123456789', 'Female', NULL, NULL, 'christine.png', '$2y$12$4qszi98KSvD9Szf8IU5fnu2W132eqPHcbjE6Y28cY0Ttc4YNaoe1u', 'eyJpdiI6IlRuczBxcjFaMEFTZWN4Y29acy85eHc9PSIsInZhbHVlIjoiMEZwTEJsL3BaL1pOT1NVRlhqZlhNb3plVmx1Z3pwOFd1cFdOV25iK3pWaz0iLCJtYWMiOiJiYzcwNmFkZGRlYzk0ODM3MTFmNTcxZGE4YzAwZDg3M2VkNzI1ZTIyMmQxZGRlNjA1NjhmZWMxMjhhZjBmYTIxIiwidGFnIjoiIn0=', 'eyJpdiI6IkdIM2lvVnBla0dCRDN6cGJmazlmdVE9PSIsInZhbHVlIjoibll1V2lidEVZMmhrUEEyL3NhM04wbmh0aXFDMkJTV2VsV1oyVEJodm1MWEtlWXUzbTlzejgrakVFekQvRURWMXMwbHpQWnVteUtnMkN3TUJhYzRGckZuRmo1SEN6aHhtUGNSYmVqVVVkeEFwY2h2dFVSWkp6bHNSSGZpNkpOMGtHZHdLQ2I5cWFaK2tEdWlyK2IrNDVMbDhLbVVYR0pnZnl5TzRWYzhDL3lBPSIsIm1hYyI6ImI4ZTFkYmUyZDI4MzJmM2JjNGI3YWNkMjI1MTdiYWFhMTdlOTE1ZjY4Zjg1N2U2YTA4MjViNzEwOGEyNzM2NTEiLCJ0YWciOiIifQ==', NULL, NULL, NULL, NULL, 'admin', 'active', NULL, NULL, NULL, '2025-08-07 13:36:51', '2025-10-14 02:34:44'),
(2, 'Johanna', 'Decena', 'Plameran', '', 'jb@gmail.com', '09123456789', 'Female', NULL, NULL, 'johanna.png', '$2y$12$4qszi98KSvD9Szf8IU5fnu2W132eqPHcbjE6Y28cY0Ttc4YNaoe1u', NULL, NULL, NULL, NULL, NULL, NULL, 'counselor', 'active', NULL, NULL, NULL, '2025-06-10 18:42:22', '2025-06-10 18:42:22'),
(3, 'Divine', 'Villondo', 'Romano', '', 'dasai@gmail.com', '123454678', 'Female', '2024-11-14', 'Adadspadk0', 'divine.png', '$2y$12$4qszi98KSvD9Szf8IU5fnu2W132eqPHcbjE6Y28cY0Ttc4YNaoe1u', NULL, NULL, NULL, NULL, NULL, NULL, 'counselor', 'active', NULL, NULL, NULL, '2025-08-08 16:09:50', '2025-08-08 16:09:50'),
(4, 'David Sailas', 'Villondo', 'Romano', NULL, 'david@gmail.com', '09817970638', 'Male', '2003-02-12', 'Lipata, Minglanilla, Cebu.', 'Defined Body Without Losing Strength.jpg', '$2y$12$z0jcQ8MU6hDAJHyUlmk8G.bzCvoszYeBY9utAnUn13FK6WOXeayt.', NULL, NULL, NULL, NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-11 08:13:07', '2025-09-11 08:51:55'),
(5, 'Mercilyn', 'Simgulang', 'Batol', NULL, 'ashlyn@gmail.com', '09457000308', 'Female', '2004-12-04', 'Lipata, Minglanilla, Cebu.', 'download (5).jpg', '$2y$12$6is/Etbr/lnPnHDOjNxM.evUGXI5LblGtj6cNzkgotqUIhYAJhaA6', NULL, NULL, NULL, NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-11 08:40:17', '2025-09-11 08:48:51'),
(6, 'Liam', 'Andres', 'Domingo', '', 'liam.domingo@example.com', '09190000003', 'Male', '2017-02-11', '45 Narra St', 'male.png', '$2y$12$EsrOITSnabR3n.W53jeH9.HRdfyzsHAzQEfO1TzRTWJxZdSiFdzVe', NULL, NULL, NULL, NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-11 17:01:37', '2025-09-11 17:01:37'),
(7, 'Chloe', 'Ramirez', 'Lopez', '', 'chloe.lopez@example.com', '09190000004', 'Female', '2016-09-23', '78 Acacia St', 'female.png', '$2y$12$mB7Qu1gGUdu8hjbTPIEase1kO09smMXKdLxj/XZ7AivkwpQbdDRhO', NULL, NULL, NULL, NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-11 17:01:37', '2025-09-11 17:01:37'),
(8, 'Ethan', 'Reyes', 'Valdez', '', 'ethan.valdez@example.com', '09190000005', 'Male', '2013-03-19', '89 Sampaguita St', 'male.png', '$2y$12$TDCWJWjJ.eiVeJLRyOgurOKEGhVAjocOiQTAAXBmEtvw2pFnADhBy', NULL, NULL, NULL, NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-11 17:01:37', '2025-09-11 17:01:37'),
(9, 'Isabella', 'Cruz', 'Garcia', '', 'isabella.garcia@example.com', '09190000006', 'Female', '2012-07-08', '99 Santan St', 'female.png', '$2y$12$aCaZRz0RZT/t/OINDsOp3.RMYgde4.TLjVzF50BgoSPVH2OEp86JK', NULL, NULL, NULL, NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-11 17:01:38', '2025-09-11 17:01:38'),
(10, 'Miguel', 'Domingo', 'Santos', '', 'miguel.santos@example.com', '09190000007', 'Male', '2010-11-15', '12 Banaba St', 'male.png', '$2y$12$YR7dWKGmROZjDpHMaEnKduu8YEiTYpaqd8aIKMGimm54EKKfJt89e', NULL, NULL, NULL, NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-11 17:01:38', '2025-09-11 17:01:38'),
(11, 'Samantha', 'Reyes', 'Mendoza', '', 'samantha.mendoza@example.com', '09190000008', 'Female', '2009-06-25', '34 Ipil St', 'default.png', '$2y$12$BRNFcGYXZ/RIIyU3uUv.deC2fQfHgR/LO/5utRRnsucQaSAT2UKlm', NULL, NULL, NULL, NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-11 17:01:38', '2025-09-11 17:01:38'),
(12, 'Adrian', 'Ramirez', 'Castillo', '', 'adrian.castillo@example.com', '09190000009', 'Male', '2008-04-30', '56 Camia St', 'male.png', '$2y$12$.C73Uzvret4A9UbG6hfey.3zp8xaLDjPPbGtshydtQi0nsFJl9Lcq', NULL, NULL, NULL, NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-11 17:01:39', '2025-09-11 17:01:39'),
(13, 'Sofia', 'Andres', 'Bautista', '', 'sofia.bautista@example.com', '09190000010', 'Female', '2007-01-19', '101 Gumamela St', 'download.jpg', '$2y$12$gYpkPP965DYSJXtmrDQu3OCuWa58MBhCq9mXc6/8mfG1A1tnBS6le', NULL, NULL, NULL, NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-11 17:01:39', '2025-09-11 17:07:05'),
(14, 'Christian James', NULL, 'Abendan', NULL, 'chrisbend2004@gmail.com', '+639196105987', 'Male', NULL, NULL, 'male.png', '$2y$12$Az5.VpBiiabyJdHoXpvRxuWseJdCb41kvS7R86oTTRt9z6cmgM.we', NULL, NULL, NULL, NULL, NULL, '2025-09-15 00:04:20', 'parent', 'active', NULL, NULL, NULL, '2025-09-15 00:03:54', '2025-10-12 08:37:17'),
(15, 'Clementine', 'Kadeem Weiss', 'Valenzuela', NULL, 'midim@mailinator.com', '136', 'Male', '1994-07-21', 'Nemo omnis obcaecati', 'male.png', '$2y$12$MrW4HeSrd1BDWxmAeveFteq675e6v0Jb6zp9jTQ6xQkD8XC.Q60pm', NULL, NULL, NULL, NULL, NULL, NULL, 'student', 'active', NULL, NULL, NULL, '2025-09-15 18:34:46', '2025-09-15 18:34:46'),
(16, 'Erin', NULL, 'Garrett', NULL, 'channity.wr@gmail.com', '+631111111111', 'Female', NULL, NULL, 'female.png', '$2y$12$xQRzGfkq3A.Z1z8P/feD4O6rE6Ux01aVs8SEmslV.z8udIrxWUxzS', NULL, NULL, NULL, NULL, NULL, '2025-09-18 02:59:57', 'parent', 'active', NULL, NULL, NULL, '2025-09-18 02:59:11', '2025-09-18 02:59:57'),
(17, 'Doris', NULL, 'Espinoza', NULL, 'doderafa@mailinator.com', '+631651116156', 'Male', NULL, NULL, 'male.png', '$2y$12$VyzMqGffvQUmLM8Pjgjl/.Gt2f3PxNvderi/uosNC4ILXdyIX7g/W', NULL, NULL, NULL, 'c8hLDjYCcmyIVAB54lRVbZ9s1YNZhCNQJcJ1kNJiKXHiSyY668KHfWNkDEFwUbHn', '2025-10-08 05:40:11', NULL, 'parent', 'pending', NULL, NULL, NULL, '2025-10-08 03:40:11', '2025-10-08 03:40:11');

-- --------------------------------------------------------

--
-- Table structure for table `user_blocks`
--

CREATE TABLE `user_blocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `blocker_id` bigint(20) UNSIGNED NOT NULL,
  `blocked_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Indexes for table `chatbot_sessions`
--
ALTER TABLE `chatbot_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chatbot_sessions_user_id_foreign` (`user_id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `features`
--
ALTER TABLE `features`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `features_key_role_unique` (`key`,`role`);

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
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `system_settings_key_unique` (`key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_email_index` (`email`),
  ADD KEY `users_status_index` (`status`);

--
-- Indexes for table `user_blocks`
--
ALTER TABLE `user_blocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_blocks_blocker_id_blocked_id_unique` (`blocker_id`,`blocked_id`),
  ADD KEY `user_blocks_blocked_id_foreign` (`blocked_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `appointment_students`
--
ALTER TABLE `appointment_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `appointment_types`
--
ALTER TABLE `appointment_types`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cases`
--
ALTER TABLE `cases`
  MODIFY `case_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `case_types`
--
ALTER TABLE `case_types`
  MODIFY `type_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chatbot_sessions`
--
ALTER TABLE `chatbot_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

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
-- AUTO_INCREMENT for table `features`
--
ALTER TABLE `features`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `p_id` bigint(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user_blocks`
--
ALTER TABLE `user_blocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
  ADD CONSTRAINT `appointment_students_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`) ON DELETE CASCADE;

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
-- Constraints for table `chatbot_sessions`
--
ALTER TABLE `chatbot_sessions`
  ADD CONSTRAINT `chatbot_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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

--
-- Constraints for table `user_blocks`
--
ALTER TABLE `user_blocks`
  ADD CONSTRAINT `user_blocks_blocked_id_foreign` FOREIGN KEY (`blocked_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_blocks_blocker_id_foreign` FOREIGN KEY (`blocker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
