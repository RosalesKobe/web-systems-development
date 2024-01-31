-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 31, 2024 at 03:50 PM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `teampogi`
--

-- --------------------------------------------------------

--
-- Table structure for table `admindetails`
--

DROP TABLE IF EXISTS `admindetails`;
CREATE TABLE IF NOT EXISTS `admindetails` (
  `administrator_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `company_id` int NOT NULL,
  `firstName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lastName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `other_administrator_details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`administrator_id`),
  KEY `user_id` (`user_id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admindetails`
--

INSERT INTO `admindetails` (`administrator_id`, `user_id`, `company_id`, `firstName`, `lastName`, `email`, `address`, `other_administrator_details`) VALUES
(1, 1, 1, 'Gus ', 'Fring', 'gfring@slu.edu.ph', 'Baguio City', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `adviserdetails`
--

DROP TABLE IF EXISTS `adviserdetails`;
CREATE TABLE IF NOT EXISTS `adviserdetails` (
  `adviser_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `administrator_id` int NOT NULL,
  `firstName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lastName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `School` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`adviser_id`),
  KEY `user_id` (`user_id`),
  KEY `administrator_id` (`administrator_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adviserdetails`
--

INSERT INTO `adviserdetails` (`adviser_id`, `user_id`, `administrator_id`, `firstName`, `lastName`, `email`, `School`, `address`) VALUES
(1, 3, 1, 'Jesse', 'Pinkman', 'jpinkman@slu.edu.ph', 'Saint Louis University', 'Quezon');

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
CREATE TABLE IF NOT EXISTS `company` (
  `company_id` int NOT NULL AUTO_INCREMENT,
  `companyName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `companyAddress` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `companyDetails` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`company_id`, `companyName`, `companyAddress`, `companyDetails`) VALUES
(1, 'Amazon', 'Baguio City', 'Yes yes'),
(2, 'Google', 'Quezon City', 'QC');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE IF NOT EXISTS `feedback` (
  `feedback_id` int NOT NULL AUTO_INCREMENT,
  `record_id` int DEFAULT NULL,
  `feedback_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `feedback_date` date NOT NULL,
  PRIMARY KEY (`feedback_id`),
  KEY `record_id` (`record_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `record_id`, `feedback_text`, `feedback_date`) VALUES
(3, 23, 'Very Good!', '2024-01-19');

-- --------------------------------------------------------

--
-- Table structure for table `interndetails`
--

DROP TABLE IF EXISTS `interndetails`;
CREATE TABLE IF NOT EXISTS `interndetails` (
  `intern_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `adviser_id` int NOT NULL,
  `supervisor_id` int NOT NULL,
  `company_id` int NOT NULL,
  `firstName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lastName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `classCode` int NOT NULL,
  PRIMARY KEY (`intern_id`),
  KEY `user_id` (`user_id`),
  KEY `adviser_id` (`adviser_id`),
  KEY `supervisor_id` (`supervisor_id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `interndetails`
--

INSERT INTO `interndetails` (`intern_id`, `user_id`, `adviser_id`, `supervisor_id`, `company_id`, `firstName`, `lastName`, `email`, `classCode`) VALUES
(4, 4, 1, 1, 1, 'Hev', 'Abi', 'habi@slu.edu.ph', 8888),
(5, 5, 1, 1, 1, 'Skusta', 'Clee', 'sclee@slu.edu.ph', 8888),
(6, 6, 1, 1, 2, 'Flow', 'Gee', 'fgee@slu.edu.ph', 7777),
(8, 8, 1, 1, 2, 'Bur', 'Ger', 'bger@slu.edu.ph', 7777);

-- --------------------------------------------------------

--
-- Table structure for table `internshiprecords`
--

DROP TABLE IF EXISTS `internshiprecords`;
CREATE TABLE IF NOT EXISTS `internshiprecords` (
  `record_id` int NOT NULL AUTO_INCREMENT,
  `intern_id` int DEFAULT NULL,
  `adviser_id` int DEFAULT NULL,
  `program_id` int DEFAULT NULL,
  `administrator_id` int NOT NULL,
  `hours_completed` int NOT NULL,
  `hours_remaining` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `record_status` enum('In Progress','Completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `checklist_completed` tinyint(1) NOT NULL DEFAULT '0',
  `supervisor_id` int DEFAULT NULL,
  PRIMARY KEY (`record_id`),
  KEY `intern_id` (`intern_id`),
  KEY `adviser_id` (`adviser_id`),
  KEY `program_id` (`program_id`),
  KEY `administrator_id` (`administrator_id`),
  KEY `fk_supervisor` (`supervisor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `internshiprecords`
--

INSERT INTO `internshiprecords` (`record_id`, `intern_id`, `adviser_id`, `program_id`, `administrator_id`, `hours_completed`, `hours_remaining`, `start_date`, `end_date`, `record_status`, `checklist_completed`, `supervisor_id`) VALUES
(23, 4, 1, 1, 1, 15, 85, '2024-01-01', '2024-02-01', 'In Progress', 1, 1),
(26, 8, 1, 1, 1, 24, 76, '2024-01-01', '2024-01-31', 'In Progress', 1, 1),
(34, 5, 1, 1, 1, 14, 86, '2024-01-31', '2024-02-10', 'In Progress', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ojtprograms`
--

DROP TABLE IF EXISTS `ojtprograms`;
CREATE TABLE IF NOT EXISTS `ojtprograms` (
  `program_id` int NOT NULL AUTO_INCREMENT,
  `administrator_id` int DEFAULT NULL,
  `program_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `start_datee` date NOT NULL,
  `end_date` date NOT NULL,
  PRIMARY KEY (`program_id`),
  KEY `administrator_id` (`administrator_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ojtprograms`
--

INSERT INTO `ojtprograms` (`program_id`, `administrator_id`, `program_name`, `start_datee`, `end_date`) VALUES
(1, 1, 'Computer Science', '2024-01-16', '2024-01-17');

-- --------------------------------------------------------

--
-- Table structure for table `supervisordetails`
--

DROP TABLE IF EXISTS `supervisordetails`;
CREATE TABLE IF NOT EXISTS `supervisordetails` (
  `supervisor_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `administrator_id` int NOT NULL,
  `firstName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lastName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`supervisor_id`),
  KEY `user_id` (`user_id`),
  KEY `administrator_id` (`administrator_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supervisordetails`
--

INSERT INTO `supervisordetails` (`supervisor_id`, `user_id`, `administrator_id`, `firstName`, `lastName`, `email`) VALUES
(1, 2, 1, 'Mike', 'Estes', 'mestes@slu.edu.ph');

-- --------------------------------------------------------

--
-- Table structure for table `timetrack`
--

DROP TABLE IF EXISTS `timetrack`;
CREATE TABLE IF NOT EXISTS `timetrack` (
  `timetrack_id` int NOT NULL AUTO_INCREMENT,
  `record_id` int NOT NULL,
  `date` date NOT NULL,
  `hours_submit` decimal(5,2) NOT NULL,
  PRIMARY KEY (`timetrack_id`),
  KEY `record_id` (`record_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timetrack`
--

INSERT INTO `timetrack` (`timetrack_id`, `record_id`, `date`, `hours_submit`) VALUES
(9, 26, '2024-01-21', 8.00),
(10, 26, '2024-01-22', 6.00),
(11, 26, '2024-01-23', 4.00),
(12, 23, '2024-01-25', 2.00),
(13, 26, '2024-01-31', 6.00),
(18, 34, '2024-01-31', 8.00),
(19, 34, '2024-02-01', 6.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS users;

CREATE TABLE IF NOT EXISTS users (
  user_id int NOT NULL AUTO_INCREMENT,
  username varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, -- Adjusted length
  password varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  user_type enum('Intern','Adviser','Supervisor','Administrator') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (user_id),
  UNIQUE KEY username (username)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `user_type`) VALUES
(1, 'admin', '$2a$08$aTjRX91Jl4xLOLAAMpCdAua1El1S5/KA0nBF5SD5mkiIAM/PTcd6y', 'Administrator'),
(2, 'supervisor', '$2a$08$TVWFq9K/y8CgHXqwdwH1VeNVqIXftaao3Y21nT9i8lIx2HHrMyz0G', 'Supervisor'),
(3, 'adviser', '$2a$08$e7..AGJcKW8UZokOKLBMo.FzgrQYNUAU41/ChyxhHECu7pAB6X8dS', 'Adviser'),
(4, 'intern1', '$2a$08$wHzNa3NJ6dJnCvdxvt8pTOd2z.KvAHufd2Da2Qs4AbQyIAbpO4/rK', 'Intern'),
(5, 'intern2', '$2a$08$Xfj3xXMqMfNlaFNg.8KccubOHRwUfyIO7KlHcpKaFmqNnEuTZoAuW', 'Intern'),
(6, 'intern3', '$2a$08$2jEU.KDI6Tig8asmS3ni6.03LOcbNQbPW3NEQ7bM/T1aE5zJY/gRW', 'Intern'),
(8, 'intern4', '$2y$10$mY.nzqcYiRATSvHnXCzTy.eH6OPWwN/qIHkmjwRfLYqydmMFpSd.W', 'Intern');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admindetails`
--
ALTER TABLE `admindetails`
  ADD CONSTRAINT `admindetails_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `admindetails_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`);

--
-- Constraints for table `adviserdetails`
--
ALTER TABLE `adviserdetails`
  ADD CONSTRAINT `adviserdetails_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `adviserdetails_ibfk_2` FOREIGN KEY (`administrator_id`) REFERENCES `admindetails` (`administrator_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `internshiprecords` (`record_id`);

--
-- Constraints for table `interndetails`
--
ALTER TABLE `interndetails`
  ADD CONSTRAINT `interndetails_ibfk_1` FOREIGN KEY (`supervisor_id`) REFERENCES `supervisordetails` (`supervisor_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `interndetails_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `interndetails_ibfk_3` FOREIGN KEY (`adviser_id`) REFERENCES `adviserdetails` (`adviser_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `interndetails_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `internshiprecords`
--
ALTER TABLE `internshiprecords`
  ADD CONSTRAINT `fk_supervisor` FOREIGN KEY (`supervisor_id`) REFERENCES `supervisordetails` (`supervisor_id`),
  ADD CONSTRAINT `internshiprecords_ibfk_1` FOREIGN KEY (`administrator_id`) REFERENCES `admindetails` (`administrator_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `internshiprecords_ibfk_2` FOREIGN KEY (`adviser_id`) REFERENCES `adviserdetails` (`adviser_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `internshiprecords_ibfk_3` FOREIGN KEY (`intern_id`) REFERENCES `interndetails` (`intern_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `internshiprecords_ibfk_4` FOREIGN KEY (`program_id`) REFERENCES `ojtprograms` (`program_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `ojtprograms`
--
ALTER TABLE `ojtprograms`
  ADD CONSTRAINT `ojtprograms_ibfk_1` FOREIGN KEY (`administrator_id`) REFERENCES `admindetails` (`administrator_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `supervisordetails`
--
ALTER TABLE `supervisordetails`
  ADD CONSTRAINT `supervisordetails_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `supervisordetails_ibfk_2` FOREIGN KEY (`administrator_id`) REFERENCES `admindetails` (`administrator_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `timetrack`
--
ALTER TABLE `timetrack`
  ADD CONSTRAINT `timetrack_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `internshiprecords` (`record_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
