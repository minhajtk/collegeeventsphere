-- EventSphere MySQL Database Dump for InfinityFree
-- Generated: 2026-08-27 07:30:44
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `announcements`;
CREATE TABLE `announcements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `target_role` enum('all','student','organizer') NOT NULL DEFAULT 'all',
  `event_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `announcements_event_id_foreign` (`event_id`),
  KEY `announcements_created_by_foreign` (`created_by`),
  CONSTRAINT `announcements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `announcements_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `attendances`;
CREATE TABLE `attendances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `checked_in_by` bigint(20) unsigned NOT NULL,
  `checked_in_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attendances_event_id_foreign` (`event_id`),
  KEY `attendances_user_id_foreign` (`user_id`),
  KEY `attendances_checked_in_by_foreign` (`checked_in_by`),
  CONSTRAINT `attendances_checked_in_by_foreign` FOREIGN KEY (`checked_in_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendances_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `bookmarks`;
CREATE TABLE `bookmarks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `event_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bookmarks_user_id_foreign` (`user_id`),
  KEY `bookmarks_event_id_foreign` (`event_id`),
  CONSTRAINT `bookmarks_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookmarks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `bookmarks` (`id`, `user_id`, `event_id`, `created_at`, `updated_at`) VALUES
('1', '5', '1', '2026-08-27 05:10:07', '2026-08-27 05:10:07');

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `icon`, `created_at`, `updated_at`) VALUES
('1', 'Cultural Events', 'cultural-events', 'Dance, music, drama, fashion shows, and artistic performance competitions.', 'palette', '2026-08-27 04:30:18', '2026-08-27 04:30:18'),
('2', 'Technical Fests', 'technical-fests', 'Hackathons, coding challenges, robotics competitions, and tech symposiums.', 'code', '2026-08-27 04:30:18', '2026-08-27 04:30:18'),
('3', 'Sports Meets', 'sports-meets', 'Track & field events, football, basketball, cricket, and indoor sports tournaments.', 'trophy', '2026-08-27 04:30:18', '2026-08-27 04:30:18'),
('4', 'Annual Day Functions', 'annual-day-functions', 'College anniversary, prize distribution, and annual celebration functions.', 'star', '2026-08-27 04:30:18', '2026-08-27 04:30:18'),
('5', 'Workshops & Seminars', 'workshops-seminars', 'Academic lectures, industry expert talks, skill-building workshops, and webinars.', 'book', '2026-08-27 04:30:18', '2026-08-27 04:30:18'),
('6', 'Intercollegiate Competitions', 'intercollegiate-competitions', 'Multi-college tournaments, debates, quizzes, and inter-university fests.', 'users', '2026-08-27 04:30:18', '2026-08-27 04:30:18');

DROP TABLE IF EXISTS `certificates`;
CREATE TABLE `certificates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `certificate_number` varchar(255) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `issued_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `certificates_certificate_number_unique` (`certificate_number`),
  KEY `certificates_event_id_foreign` (`event_id`),
  KEY `certificates_user_id_foreign` (`user_id`),
  CONSTRAINT `certificates_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `certificates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `venue` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL,
  `available_slots` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `registration_deadline` datetime NOT NULL,
  `organizer_id` bigint(20) unsigned NOT NULL,
  `organizing_department` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected','cancelled','completed') NOT NULL DEFAULT 'pending',
  `banner_image` varchar(255) DEFAULT NULL,
  `rulebook_file` varchar(255) DEFAULT NULL,
  `hashtags` varchar(255) DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `events_slug_unique` (`slug`),
  KEY `events_category_id_foreign` (`category_id`),
  KEY `events_organizer_id_foreign` (`organizer_id`),
  CONSTRAINT `events_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `events_organizer_id_foreign` FOREIGN KEY (`organizer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `events` (`id`, `title`, `slug`, `description`, `category_id`, `venue`, `capacity`, `available_slots`, `start_date`, `end_date`, `registration_deadline`, `organizer_id`, `organizing_department`, `status`, `banner_image`, `rulebook_file`, `hashtags`, `rejection_reason`, `created_at`, `updated_at`) VALUES
('1', 'marathon', 'marathon-1Yd7x', 'dfkhgbvckmnbvkjihgfdskjh', '3', 'pc hotel', '100', '100', '2026-09-05 00:00:00', '2026-09-05 13:03:00', '2026-08-25 02:07:00', '6', 'Student Affairs & Clubs', 'approved', 'uploads/events/1787807152_banner_6a8fc5b07bf28.jfif', 'uploads/rulebooks/1787807152_rulebook_6a8fc5b07cb30.docx', '#cultural#event', NULL, '2026-08-27 05:05:52', '2026-08-27 05:06:34');

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `feedbacks`;
CREATE TABLE `feedbacks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `user_role_title` varchar(255) NOT NULL DEFAULT 'Student Participant',
  `overall_rating` int(11) NOT NULL DEFAULT 5,
  `venue_rating` int(11) NOT NULL DEFAULT 5,
  `coordination_rating` int(11) NOT NULL DEFAULT 5,
  `tech_rating` int(11) NOT NULL DEFAULT 5,
  `hospitality_rating` int(11) NOT NULL DEFAULT 5,
  `comments` text DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `feedbacks_event_id_foreign` (`event_id`),
  KEY `feedbacks_user_id_foreign` (`user_id`),
  CONSTRAINT `feedbacks_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `feedbacks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `job_batches`;
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
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `media_galleries`;
CREATE TABLE `media_galleries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `media_type` enum('image','video') NOT NULL DEFAULT 'image',
  `file_path` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `department` varchar(255) DEFAULT NULL,
  `year` int(11) NOT NULL DEFAULT 2026,
  `uploaded_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_galleries_event_id_foreign` (`event_id`),
  KEY `media_galleries_uploaded_by_foreign` (`uploaded_by`),
  CONSTRAINT `media_galleries_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE SET NULL,
  CONSTRAINT `media_galleries_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
('1', '0001_01_01_000000_create_users_table', '1'),
('2', '0001_01_01_000001_create_cache_table', '1'),
('3', '0001_01_01_000002_create_jobs_table', '1'),
('4', '2026_08_26_000001_create_eventsphere_tables', '1');

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'general',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `is_read`, `created_at`, `updated_at`) VALUES
('1', '6', 'Event Proposal Approved!', 'Congratulations! Your event proposal for \'marathon\' has been approved by the Admin and is now live.', 'event_approved', '0', '2026-08-27 05:06:34', '2026-08-27 05:06:34');

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `registrations`;
CREATE TABLE `registrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `status` enum('registered','waitlisted','cancelled','attended') NOT NULL DEFAULT 'registered',
  `qr_code_token` varchar(255) NOT NULL,
  `certificate_fee_paid` tinyint(1) NOT NULL DEFAULT 0,
  `certificate_fee_txn` varchar(255) DEFAULT NULL,
  `registered_at` datetime NOT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `registrations_qr_code_token_unique` (`qr_code_token`),
  KEY `registrations_event_id_foreign` (`event_id`),
  KEY `registrations_user_id_foreign` (`user_id`),
  CONSTRAINT `registrations_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `registrations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `saved_media`;
CREATE TABLE `saved_media` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `media_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `saved_media_user_id_foreign` (`user_id`),
  KEY `saved_media_media_id_foreign` (`media_id`),
  CONSTRAINT `saved_media_media_id_foreign` FOREIGN KEY (`media_id`) REFERENCES `media_galleries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `saved_media_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('arYEVn2XW9DaJYLyBCez7kAMHHypj7sDdO0QXiY1', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibU1qdkFMV3RSd2tiUEtoTTZHYmVqcFF5ZlBOZEJtSGNHT1BMTDM1aCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjE6e2k6MDtzOjc6InN1Y2Nlc3MiO31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo3OiJzdWNjZXNzIjtzOjM1OiJXZWxjb21lIGJhY2ssIFN5c3RlbSBBZG1pbmlzdHJhdG9yISI7fQ==', '1787805353'),
('qPau2IiVNdKlOH2w4LmSR8hPzZtNA8rh2TvdNf1u', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNVFwNzZvemo2SnFPVDBnYlgyVkZNN2U3RlZRQ21RV09YU3hTd2pEciI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hYm91dCI7fX0=', '1787808033');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `role` enum('student','organizer','admin') NOT NULL DEFAULT 'student',
  `department` varchar(255) DEFAULT NULL,
  `enrolment_number` varchar(255) DEFAULT NULL,
  `status` enum('active','suspended') NOT NULL DEFAULT 'active',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `username`, `password`, `phone`, `role`, `department`, `enrolment_number`, `status`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
('1', 'System Administrator', 'admin@eventsphere.edu', 'admin', '$2y$10$H3AKN3bv/VDdYW.ODRxZ0.A58xTQCwp/WIbqTj1lBtQt9GBWZBVSe', '+1234567890', 'admin', 'Administration', 'ADM-2026-01', 'active', NULL, NULL, '2026-08-27 04:30:19', '2026-08-27 04:30:19'),
('2', 'Prof. Sarah Jenkins', 'organizer@eventsphere.edu', 'organizer', '$2y$12$StXb4tM5n8h7IoS8k/Le6ubh5v2up3no2yseEZy1NejmGddNCNDRO', '+1987654321', 'organizer', 'Computer Science & Engineering', 'FAC-CS-0042', 'active', NULL, NULL, '2026-08-27 04:30:19', '2026-08-27 04:30:19'),
('3', 'Alex Rivera', 'student@eventsphere.edu', 'alexstudent', '$2y$12$R8JF46eZI0MNnRc3bqPoDeod69FgGxds0e5t.pt8H9AHLMQDOVtam', '+1555019283', 'student', 'Information Technology', 'EN20269981', 'suspended', NULL, NULL, '2026-08-27 04:30:19', '2026-08-27 05:06:57'),
('4', 'aliyan', 'aliyan@gmail.com', 'aliyan', '$2y$12$uYP1/i0dfIkVckE6npei/Oc.0ffZvmDwnOdE25XVIKy/MyyKb4PzS', '924563896576', 'student', 'Computer Science & Engineering', 'EN202627', 'active', NULL, NULL, '2026-08-27 04:53:51', '2026-08-27 04:53:51'),
('5', 'owais', 'owais@gmail.com', 'owais', '$2y$12$N/DnjsNN5yTZ7sDfk9vQDuqd8g4I4ScOfH6eBG0nZiglTyoZ..vRa', '92678899075', 'student', 'Electronics & Communication', 'EN202654', 'active', NULL, NULL, '2026-08-27 04:55:09', '2026-08-27 04:55:09'),
('6', 'yusra', 'yusra@gmail.com', 'yusra', '$2y$12$om5a5sU/98Q.f4ahCczpquSWZcLI9lX5D8NdJIqiPaZ9GKiEGzsE6', '92546677889', 'organizer', 'Student Affairs & Clubs', 'EN202648', 'active', NULL, NULL, '2026-08-27 04:56:07', '2026-08-27 04:56:07'),
('7', 'maham', 'maham@gmail.com', 'maham', '$2y$12$LGw1ZOWx5DVZAKKYIH3uIe2/iIGpBlY4kN/kScjuiDyeQEmJwW0yi', '92956879809', 'organizer', 'Information Technology', 'EN202623', 'active', NULL, NULL, '2026-08-27 04:57:31', '2026-08-27 04:57:31');

DROP TABLE IF EXISTS `waitlists`;
CREATE TABLE `waitlists` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `position` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `waitlists_event_id_foreign` (`event_id`),
  KEY `waitlists_user_id_foreign` (`user_id`),
  CONSTRAINT `waitlists_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `waitlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
