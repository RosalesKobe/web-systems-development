-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 07, 2023 at 08:18 PM
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
  `firstName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `lastName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `other_administrator_details` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`administrator_id`),
  KEY `user_id` (`user_id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admindetails`
--

INSERT INTO `admindetails` (`administrator_id`, `user_id`, `company_id`, `firstName`, `lastName`, `email`, `address`, `other_administrator_details`) VALUES
(1, 3, 1, 'Admin', 'Ito', 'admin@slu.edu.ph', 'Abra', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `adviserdetails`
--

DROP TABLE IF EXISTS `adviserdetails`;
CREATE TABLE IF NOT EXISTS `adviserdetails` (
  `adviser_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `firstName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `lastName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `School` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `other_adviser_details` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`adviser_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adviserdetails`
--

INSERT INTO `adviserdetails` (`adviser_id`, `user_id`, `firstName`, `lastName`, `email`, `School`, `address`, `other_adviser_details`) VALUES
(1, 2, 'Adviser', 'AdviserR', 'adviser@slu.edu.ph', 'slu', 'baguio', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
CREATE TABLE IF NOT EXISTS `company` (
  `company_id` int NOT NULL AUTO_INCREMENT,
  `companyName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `companyAddress` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `companyDetails` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`company_id`, `companyName`, `companyAddress`, `companyDetails`) VALUES
(1, 'Amazon', 'Abra', 'ASDASD');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE IF NOT EXISTS `documents` (
  `document_id` int NOT NULL AUTO_INCREMENT,
  `record_id` int DEFAULT NULL,
  `document_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `document_path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `other_document_details` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`document_id`),
  KEY `record_id` (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE IF NOT EXISTS `feedback` (
  `feedback_id` int NOT NULL AUTO_INCREMENT,
  `record_id` int DEFAULT NULL,
  `feedback_text` text COLLATE utf8mb4_general_ci NOT NULL,
  `feedback_date` date NOT NULL,
  `other_feedback_details` text COLLATE utf8mb4_general_ci,
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
  `firstName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `lastName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `School` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `other_intern_details` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`intern_id`),
  KEY `user_id` (`user_id`),
  KEY `adviser_id` (`adviser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `interndetails`
--

INSERT INTO `interndetails` (`intern_id`, `user_id`, `adviser_id`, `firstName`, `lastName`, `email`, `address`, `School`, `other_intern_details`) VALUES
(2, 1, 1, 'Intern', 'Ito', 'intern@slu.edu.ph', 'Baguio', 'SLU', NULL);

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
  `record_status` enum('In Progress','Completed') COLLATE utf8mb4_general_ci NOT NULL,
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
  `program_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `start_datee` date NOT NULL,
  `end_date` date NOT NULL,
  PRIMARY KEY (`program_id`),
  KEY `administrator_id` (`administrator_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ojtprograms`
--

INSERT INTO `ojtprograms` (`program_id`, `administrator_id`, `program_name`, `start_datee`, `end_date`) VALUES
(1, 1, 'Ednis the great Birthday', '2023-12-10', '2023-12-11'),
(2, 1, '1', '0000-00-00', '2023-12-21'),
(3, 1, '1', '2023-12-20', '2023-12-21'),
(4, 1, '1', '2023-12-20', '2023-12-21'),
(12, 1, 'denden', '2023-12-06', '2023-11-30'),
(13, 1, 'denden', '2023-12-06', '2023-11-30'),
(14, 1, 'dennis', '2023-12-19', '2023-12-21'),
(15, 1, 'dennis', '2023-12-19', '2023-12-21'),
(16, 1, 'ednis', '2023-12-19', '2023-12-20'),
(17, 1, 'ednis', '2023-12-19', '2023-12-20'),
(18, 1, 'dendennis', '2023-12-06', '2023-12-16'),
(19, 1, 'dendennis', '2023-12-06', '2023-12-16'),
(20, 1, '1', '2023-12-06', '2023-12-07'),
(21, 1, '1', '2023-12-06', '2023-12-07'),
(22, 1, '1', '2023-12-06', '2023-12-07'),
(23, 1, 'BDAY BDAY', '2023-12-25', '2023-12-28'),
(24, 1, 'BDAY BDAY', '2023-12-25', '2023-12-28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_type` enum('Intern','Adviser','Administrator') COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `user_type`) VALUES
(1, 'internK', 'internK', 'Intern'),
(2, 'adviserK', 'adviserK', 'Adviser'),
(3, 'adminK', 'adminK', 'Administrator');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admindetails`
--
ALTER TABLE `admindetails`
  ADD CONSTRAINT `admindetails_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `admindetails_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `adviserdetails`
--
ALTER TABLE `adviserdetails`
  ADD CONSTRAINT `adviserdetails_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `internshiprecords` (`record_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `internshiprecords` (`record_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `interndetails`
--
ALTER TABLE `interndetails`
  ADD CONSTRAINT `interndetails_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `interndetails_ibfk_2` FOREIGN KEY (`adviser_id`) REFERENCES `adviserdetails` (`adviser_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `internshiprecords`
--
ALTER TABLE `internshiprecords`
  ADD CONSTRAINT `internshiprecords_ibfk_1` FOREIGN KEY (`intern_id`) REFERENCES `interndetails` (`intern_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `internshiprecords_ibfk_2` FOREIGN KEY (`adviser_id`) REFERENCES `adviserdetails` (`adviser_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `internshiprecords_ibfk_3` FOREIGN KEY (`program_id`) REFERENCES `ojtprograms` (`program_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `internshiprecords_ibfk_4` FOREIGN KEY (`administrator_id`) REFERENCES `admindetails` (`administrator_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `ojtprograms`
--
ALTER TABLE `ojtprograms`
  ADD CONSTRAINT `ojtprograms_ibfk_1` FOREIGN KEY (`administrator_id`) REFERENCES `admindetails` (`administrator_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
