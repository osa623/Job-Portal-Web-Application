-- phpMyAdmin SQL Dump
-- Version: 5.2.1
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2024 at 10:00 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Database: `fitness_trainer`
--

CREATE DATABASE IF NOT EXISTS `fitness_master` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `fitness_master`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `gender` ENUM('Male', 'Female', 'Other') NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `phone_number` VARCHAR(20),
  `type` TINYINT(1) NOT NULL DEFAULT 0, -- 0 for customers, 1 for admins
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Example data insertion for the users table
INSERT INTO `users` (`id`, `name`, `email`, `gender`, `password`, `phone_number`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Kamal', 'kamal@gmail.com', 'Male', '202cb962ac59075b964b07152d234b70', '776655432', 1, NOW(), NOW()),  -- Admin
(2, 'Sara', 'sara@gmail.com', 'Female', '202cb962ac59075b964b07152d234b70', '987654321', 0, NOW(), NOW()); -- Customer


-- --------------------------------------------------------

--
-- Table structure for table `coaches`
--

CREATE TABLE `coaches` (
  `coach_id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `specialization` VARCHAR(100) NOT NULL,
  `experience_years` INT(11) NOT NULL,
  `bio` TEXT,
  `phone_number` VARCHAR(20),
  `availability` VARCHAR(100),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`coach_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coaches`
--

INSERT INTO `coaches` (`coach_id`, `name`, `email`, `specialization`, `experience_years`, `bio`, `phone_number`, `availability`, `created_at`, `updated_at`) VALUES
(1, 'John Doe', 'john.doe@example.com', 'Strength Training', 5, 'Experienced strength coach with a passion for helping clients achieve their goals.', '1234567890', 'Mon-Fri 9am-5pm', NOW(), NOW()),
(2, 'Jane Smith', 'jane.smith@example.com', 'Yoga & Flexibility', 8, 'Certified yoga instructor specializing in flexibility and mindfulness.', '0987654321', 'Tue-Thu 10am-6pm', NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `membership_packages`
--

CREATE TABLE `membership_packages` (
  `package_id` INT(11) NOT NULL AUTO_INCREMENT,
  `package_name` VARCHAR(100) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `duration` INT NOT NULL COMMENT 'Duration in days',
  `description` TEXT,
  `features` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`package_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `membership_packages`
--

INSERT INTO `membership_packages` (`package_id`, `package_name`, `price`, `duration`, `description`, `features`, `created_at`, `updated_at`) VALUES
(1, 'Basic Plan', '49.99', 30, 'A basic membership plan for beginners.', 'Access to gym facilities, 2 personal training sessions per month', NOW(), NOW()),
(2, 'Premium Plan', '99.99', 90, 'A premium membership plan for dedicated individuals.', 'Access to all facilities, 6 personal training sessions per month, Free nutritional consultation', NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `workout_plans`
--

CREATE TABLE `workout_plans` (
  `plan_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `package_id` INT(11) NOT NULL,
  `coach_id` INT(11) NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `status` ENUM('Active', 'Completed', 'Cancelled') DEFAULT 'Active',
  `session_count` INT DEFAULT 0,
  `notes` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`plan_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`package_id`) REFERENCES `membership_packages`(`package_id`) ON DELETE CASCADE,
  FOREIGN KEY (`coach_id`) REFERENCES `coaches`(`coach_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workout_plans`
--

INSERT INTO `workout_plans` (`plan_id`, `user_id`, `package_id`, `coach_id`, `start_date`, `end_date`, `status`, `session_count`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2024-05-01', '2024-05-31', 'Active', 2, 'Focus on building strength and endurance.', NOW(), NOW()),
(2, 1, 2, 2, '2024-06-01', '2024-08-29', 'Active', 6, 'Incorporate yoga and flexibility training.', NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `diet_plans`
--

CREATE TABLE `diet_plans` (
  `diet_id` INT(11) NOT NULL AUTO_INCREMENT,
  `plan_id` INT(11) NOT NULL,
  `meal_plan` TEXT NOT NULL,
  `description` TEXT,
  `notes` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`diet_id`),
  FOREIGN KEY (`plan_id`) REFERENCES `workout_plans`(`plan_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diet_plans`
--

INSERT INTO `diet_plans` (`diet_id`, `plan_id`, `meal_plan`, `description`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'Breakfast: Oatmeal with fruits\nLunch: Grilled chicken salad\nDinner: Steamed vegetables with quinoa', 'Balanced diet to support strength training.', 'Ensure adequate protein intake.', NOW(), NOW()),
(2, 2, 'Breakfast: Smoothie bowl\nLunch: Avocado toast with eggs\nDinner: Vegan stir-fry with tofu', 'Diet plan focusing on flexibility and mindfulness.', 'Include hydration reminders.', NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `feedback_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `coach_id` INT(11) NOT NULL,
  `rating` INT(1) NOT NULL CHECK (`rating` >= 1 AND `rating` <= 5),
  `system_rating` INT(1) NOT NULL CHECK (`system_rating` >= 1 AND `system_rating` <= 5),
  `comments` TEXT,
  `improvement_suggestions` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`feedback_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`coach_id`) REFERENCES `coaches`(`coach_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`feedback_id`, `user_id`, `coach_id`, `rating`, `system_rating`, `comments`, `improvement_suggestions`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 5, 4, 'Great coaching sessions!', 'More online resources would be helpful.', NOW(), NOW()),
(2, 1, 2, 4, 5, 'Yoga sessions were very relaxing.', 'Extend the session durations.', NOW(), NOW());

-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coaches`
--
ALTER TABLE `coaches`
  ADD PRIMARY KEY (`coach_id`);

--
-- Indexes for table `membership_packages`
--
ALTER TABLE `membership_packages`
  ADD PRIMARY KEY (`package_id`);

--
-- Indexes for table `workout_plans`
--
ALTER TABLE `workout_plans`
  ADD PRIMARY KEY (`plan_id`),
  ADD INDEX `user_id_idx` (`user_id`),
  ADD INDEX `package_id_idx` (`package_id`),
  ADD INDEX `coach_id_idx` (`coach_id`);

--
-- Indexes for table `diet_plans`
--
ALTER TABLE `diet_plans`
  ADD PRIMARY KEY (`diet_id`),
  ADD INDEX `plan_id_idx` (`plan_id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`feedback_id`),
  ADD INDEX `user_id_idx` (`user_id`),
  ADD INDEX `coach_id_idx` (`coach_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `coaches`
--
ALTER TABLE `coaches`
  MODIFY `coach_id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `membership_packages`
--
ALTER TABLE `membership_packages`
  MODIFY `package_id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `workout_plans`
--
ALTER TABLE `workout_plans`
  MODIFY `plan_id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `diet_plans`
--
ALTER TABLE `diet_plans`
  MODIFY `diet_id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `feedback_id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

COMMIT;

