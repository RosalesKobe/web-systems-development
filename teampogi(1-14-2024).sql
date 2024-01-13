-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 13, 2024 at 07:03 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `other_adviser_details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`adviser_id`),
  KEY `user_id` (`user_id`),
  KEY `administrator_id` (`administrator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `firstName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lastName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `classCode` int NOT NULL,
  `companyAddress` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `requirements` tinyint(1) NOT NULL,
  PRIMARY KEY (`intern_id`),
  KEY `user_id` (`user_id`),
  KEY `adviser_id` (`adviser_id`),
  KEY `supervisor_id` (`supervisor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  PRIMARY KEY (`record_id`),
  KEY `intern_id` (`intern_id`),
  KEY `adviser_id` (`adviser_id`),
  KEY `program_id` (`program_id`),
  KEY `administrator_id` (`administrator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supervisordetails`
--

DROP TABLE IF EXISTS `supervisordetails`;
CREATE TABLE IF NOT EXISTS `supervisordetails` (
  `supervisor_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  PRIMARY KEY (`supervisor_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timetrack`
--

DROP TABLE IF EXISTS `timetrack`;
CREATE TABLE IF NOT EXISTS `timetrack` (
  `timetrack_id` int NOT NULL AUTO_INCREMENT,
  `record_id` int NOT NULL,
  `date` date NOT NULL,
  `hours_submit` time NOT NULL,
  `hours_rendered` decimal(5,2) NOT NULL,
  PRIMARY KEY (`timetrack_id`),
  KEY `record_id` (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_type` enum('Intern','Adviser','Supervisor','Administrator') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  ADD CONSTRAINT `interndetails_ibfk_1` FOREIGN KEY (`supervisor_id`) REFERENCES `supervisordetails` (`supervisor_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `supervisordetails`
--
ALTER TABLE `supervisordetails`
  ADD CONSTRAINT `supervisordetails_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
