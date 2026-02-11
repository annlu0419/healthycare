-- Database Schema for Colorectal Cancer Patient Tracking System

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+08:00";

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `user_profile`
--
CREATE TABLE `user_profile` (
  `user_id` int(11) NOT NULL,
  `cancer_stage` varchar(20) DEFAULT NULL COMMENT 'e.g., II, III, IV',
  `tnm_stage` varchar(20) DEFAULT NULL,
  `biomarkers` varchar(100) DEFAULT NULL COMMENT 'KRAS/BRAF/MSI',
  `regimen_name` varchar(50) DEFAULT NULL COMMENT 'FOLFOX, CAPOX, etc.',
  `has_stoma` tinyint(1) DEFAULT 0,
  `surgery_date` date DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_profile_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `chemo_cycles`
--
CREATE TABLE `chemo_cycles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `cycle_no` int(11) NOT NULL,
  `infusion_date` date NOT NULL,
  `regimen` varchar(50) DEFAULT NULL,
  `dose_notes` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_cycle_user` (`user_id`),
  CONSTRAINT `fk_cycle_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `daily_logs`
--
CREATE TABLE `daily_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `log_date` date NOT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `bp_systolic` int(11) DEFAULT NULL,
  `bp_diastolic` int(11) DEFAULT NULL,
  `pulse` int(11) DEFAULT NULL,
  `temperature` decimal(4,2) DEFAULT NULL,
  `nausea_score` int(11) DEFAULT 0 COMMENT '0-10',
  `vomit_count` int(11) DEFAULT 0,
  `diarrhea_count` int(11) DEFAULT 0,
  `is_constipated` tinyint(1) DEFAULT 0,
  `stool_type` int(11) DEFAULT NULL COMMENT 'Bristol 1-7',
  `has_blood_stool` tinyint(1) DEFAULT 0,
  `pain_score` int(11) DEFAULT 0 COMMENT '0-10',
  `bloating_score` int(11) DEFAULT 0 COMMENT '0-10',
  `numbness_score` int(11) DEFAULT 0 COMMENT '0-10',
  `fatigue_score` int(11) DEFAULT 0 COMMENT '0-10',
  `appetite_score` int(11) DEFAULT 0 COMMENT '0-10',
  `mouth_sore_score` int(11) DEFAULT 0 COMMENT '0-10',
  `sleep_score` int(11) DEFAULT 0 COMMENT '0-10',
  `mood_score` int(11) DEFAULT 0 COMMENT '0-10',
  `notes` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_daily_user_date` (`user_id`, `log_date`),
  CONSTRAINT `fk_daily_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `diet_logs`
--
CREATE TABLE `diet_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `log_date` date NOT NULL,
  `water_intake` int(11) DEFAULT 0 COMMENT 'ml',
  `meal_count` int(11) DEFAULT 0,
  `content_summary` text DEFAULT NULL,
  `risk_foods` varchar(255) DEFAULT NULL COMMENT 'dairy, fried, spicy, raw, etc.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_diet_user_date` (`user_id`, `log_date`),
  CONSTRAINT `fk_diet_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `exercise_logs`
--
CREATE TABLE `exercise_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `log_date` date NOT NULL,
  `exercise_type` varchar(50) DEFAULT NULL,
  `duration_minutes` int(11) DEFAULT 0,
  `intensity` varchar(20) DEFAULT NULL COMMENT 'Low, Medium, High',
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_exercise_user_date` (`user_id`, `log_date`),
  CONSTRAINT `fk_exercise_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `lab_results`
--
CREATE TABLE `lab_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `test_date` date NOT NULL,
  `wbc` decimal(10,2) DEFAULT NULL,
  `anc` decimal(10,2) DEFAULT NULL,
  `hb` decimal(10,2) DEFAULT NULL,
  `platelet` decimal(10,2) DEFAULT NULL,
  `ast` decimal(10,2) DEFAULT NULL,
  `alt` decimal(10,2) DEFAULT NULL,
  `creatinine` decimal(10,2) DEFAULT NULL,
  `cea` decimal(10,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_lab_user` (`user_id`),
  CONSTRAINT `fk_lab_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
