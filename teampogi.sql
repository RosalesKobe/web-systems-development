-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 14, 2023 at 06:20 AM
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admindetails`
--

INSERT INTO `admindetails` (`administrator_id`, `user_id`, `company_id`, `firstName`, `lastName`, `email`, `address`, `other_administrator_details`) VALUES
(2, 15, 1, 'Saul', 'Goodman', 'sgoodman@gmail.com', 'Bucay, Abra', 'Company Admin');

-- --------------------------------------------------------

--
-- Table structure for table `adviserdetails`
--

DROP TABLE IF EXISTS `adviserdetails`;
CREATE TABLE IF NOT EXISTS `adviserdetails` (
  `adviser_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `administrator_id` int NOT NULL,
  `firstName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `lastName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `School` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `other_adviser_details` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`adviser_id`),
  KEY `user_id` (`user_id`),
  KEY `administrator_id` (`administrator_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adviserdetails`
--

INSERT INTO `adviserdetails` (`adviser_id`, `user_id`, `administrator_id`, `firstName`, `lastName`, `email`, `School`, `address`, `other_adviser_details`) VALUES
(2, 14, 2, 'Walter ', 'White', 'wwhite@slu.edu.ph', 'Saint Louis University', 'Baguio City', 'Good Teacher');

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
CREATE TABLE IF NOT EXISTS `company` (
  `company_id` int NOT NULL AUTO_INCREMENT,
  `companyName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `companyAddress` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `companyDetails` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`company_id`, `companyName`, `companyAddress`, `companyDetails`) VALUES
(1, 'QuantumForge Technologies', 'Barking, United Kingdom', 'A cutting-edge technology company at the forefront of the digital revolution.');

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
  PRIMARY KEY (`feedback_id`),
  KEY `record_id` (`record_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `record_id`, `feedback_text`, `feedback_date`) VALUES
(1, 1, 'The intern completed his work and performed well in regards to the tasks that is given to him.', '2023-09-05'),
(2, 2, 'The intern is currently performing well. She complies to the given tasks and do it on time.', '2023-11-21'),
(12, 1, 'Very Bad!', '2023-12-11'),
(14, 1, 'Very Good!', '2023-12-13'),
(15, 3, 'Tuco, you are so bad!!', '2023-12-13'),
(20, 5, 'Jane, you did a great job with Jesse. Keep it up with that Meath!', '2023-12-14');

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
  `address` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `School` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `other_intern_details` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`intern_id`),
  KEY `user_id` (`user_id`),
  KEY `adviser_id` (`adviser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `interndetails`
--

INSERT INTO `interndetails` (`intern_id`, `user_id`, `adviser_id`, `firstName`, `lastName`, `email`, `address`, `School`, `other_intern_details`) VALUES
(4, 4, 2, 'Jesse', 'Pinkman', 'jpinkman@slu.edu.ph', 'Quezon City', 'Saint Louis University', 'Computer Science Intern Student'),
(5, 5, 2, 'Gus', 'Fring', 'gfring@slu.edu.ph', 'Quezon City', 'Saint Louis University', 'Computer Science Intern Student'),
(6, 6, 2, 'Tuco', 'Salamanca', 'tsalamanca@slu.edu.ph', 'Quezon Province', 'Saint Louis University', 'Computer Science Intern Student'),
(7, 7, 2, 'Mike', 'Ehrmantraut', 'mehrmantraut@slu.edu.ph', 'Baguio City', 'Saint Louis University', 'Computer Science Intern Student'),
(8, 8, 2, 'Jane', 'Margolis', 'jmargolis@slu.edu.ph', 'Baguio City', 'Saint Louis University', 'Computer Science Intern Student'),
(9, 9, 2, 'Hank', 'Schrader', 'hschrader@slu.edu.ph', 'Baguio City', 'Saint Louis University', 'Computer Science Intern Student'),
(10, 10, 2, 'Skinny', 'Pete', 'spete@slu.edu.ph', 'Baguio City', 'Saint Louis University', 'Computer Science Intern Student'),
(11, 11, 2, 'Juan', 'Bolsa', 'jbolsa@slu.edu.ph', 'Bakakeng Sur, Baguio City', 'Saint Louis University', 'Computer Science Intern Student'),
(12, 12, 2, 'Chuck', 'McGill', 'cmcgill@slu.edu.ph', 'Bakakeng Sur, Baguio City', 'Saint Louis University', 'Computer Science Intern Student'),
(13, 13, 2, 'Kim', 'Wexler', 'kwexler@slu.edu.ph', 'Bakakeng Norte, Baguio City', 'Saint Louis University', 'Computer Science Intern Student'),
(19, 21, 2, 'Skyler', 'White', 'swhite@slu.edu.ph', 'Zambales', 'Saint Louis University', 'Computer Science Intern Student'),
(20, 22, 2, 'Hector', 'Salamanca', 'hsalamanca@slu.edu.ph', 'Olongapo City', 'Saint Louis University', 'Computer Science Intern Student');

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
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `internshiprecords`
--

INSERT INTO `internshiprecords` (`record_id`, `intern_id`, `adviser_id`, `program_id`, `administrator_id`, `hours_completed`, `hours_remaining`, `start_date`, `end_date`, `record_status`) VALUES
(1, 4, 2, 36, 2, 100, 0, '2023-07-01', '2023-08-31', 'Completed'),
(2, 5, 2, 37, 2, 95, 5, '2023-11-01', '2023-12-01', 'In Progress'),
(3, 6, 2, 39, 2, 70, 30, '2023-10-31', '2023-11-30', 'In Progress'),
(4, 7, 2, 38, 2, 0, 100, '2023-10-31', '2023-11-30', 'In Progress'),
(5, 8, 2, 38, 2, 0, 100, '2023-10-31', '2023-11-30', 'In Progress'),
(6, 9, 2, 38, 2, 0, 100, '2023-10-31', '2023-11-30', 'In Progress'),
(8, 10, 2, 38, 2, 0, 100, '2023-10-31', '2023-11-30', 'In Progress'),
(9, 11, 2, 38, 2, 0, 100, '2023-10-31', '2023-11-30', 'In Progress'),
(10, 12, 2, 38, 2, 0, 100, '2023-10-31', '2023-11-30', 'In Progress'),
(11, 13, 2, 38, 2, 0, 100, '2023-10-31', '2023-11-30', 'In Progress'),
(12, 19, 2, 39, 2, 0, 100, '2023-10-31', '2023-11-30', 'In Progress'),
(44, 20, 2, NULL, 2, 0, 100, '2023-10-31', '2023-12-30', 'In Progress');

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
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ojtprograms`
--

INSERT INTO `ojtprograms` (`program_id`, `administrator_id`, `program_name`, `start_datee`, `end_date`) VALUES
(36, 2, 'Product Development Program. Objective: To design, develop, and launch new products in the market. Key Initiatives: Research and Development Prototyping Testing and Quality Assurance Launch Strategy', '2023-12-20', '2023-12-23'),
(37, 2, 'Marketing and Branding Program. Objective: To improve customer satisfaction and service delivery. Key Initiatives: Customer Support Training Service Quality Monitoring Feedback and Improvement', '2024-01-02', '2024-01-03'),
(38, 2, 'Customer Service Enhancement Program. Objective: To enhance employee skills and foster professional growth. Key Initiatives: Training Workshops Leadership Development Skill Enhancement Programs', '2024-01-04', '2023-12-06'),
(39, 2, 'Employee Training and Development Program. Objective: To stay at the forefront of technological advancements. Key Initiatives: Research on Emerging Technologies System Upgrades Innovation Labs', '2024-01-08', '2024-01-12');

-- --------------------------------------------------------

--
-- Table structure for table `timetrack`
--

DROP TABLE IF EXISTS `timetrack`;
CREATE TABLE IF NOT EXISTS `timetrack` (
  `timetrack_id` int NOT NULL AUTO_INCREMENT,
  `record_id` int NOT NULL,
  `date` date NOT NULL,
  `timein` time NOT NULL,
  `timeout` time NOT NULL,
  `hours_rendered` decimal(5,2) NOT NULL,
  PRIMARY KEY (`timetrack_id`),
  KEY `record_id` (`record_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timetrack`
--

INSERT INTO `timetrack` (`timetrack_id`, `record_id`, `date`, `timein`, `timeout`, `hours_rendered`) VALUES
(4, 2, '2023-12-14', '07:30:00', '15:30:00', '8.00'),
(5, 2, '2023-12-15', '08:00:00', '13:00:00', '5.00'),
(7, 2, '2023-12-16', '08:53:00', '13:53:00', '5.00'),
(8, 2, '2023-12-17', '08:53:00', '17:55:00', '9.03'),
(9, 2, '2023-12-19', '12:53:00', '18:08:00', '5.25'),
(11, 2, '2023-12-20', '09:05:00', '11:05:00', '2.00'),
(14, 2, '2023-12-28', '11:29:00', '13:29:00', '2.00'),
(15, 2, '2023-12-27', '09:44:00', '14:44:00', '5.00'),
(16, 2, '2023-12-28', '09:48:00', '17:50:00', '8.03');

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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `user_type`) VALUES
(4, 'intern1', '$2b$10$m7AqDDAQMqtNFeJLiK.zNeLdDoMNAeHVpjL2tnK6aBBkQVE3vnZMK', 'Intern'),
(5, 'intern2', '$2b$10$vYDNYbAsVZNU/f9hhaIms.Q1DOmQ1BFWyIpj/E037WUfiQbdygu8G', 'Intern'),
(6, 'intern3', '$2b$10$cxB9xavNh5q4WW42vSvT/u8d88SLYr8d9Yn8evrIlYBp.jYWahywO', 'Intern'),
(7, 'intern4', '$2b$10$JQLy/uxcXZjQkJaTnIbjcOeDxTzlOtZKGMCqJVtv7oh3ZQ1RLdCEy', 'Intern'),
(8, 'intern5', '$2b$10$dRznRkpb3CGc297io0swLOA2y6/rTuf2Z/54/V4Fjz16u1if/rTnG', 'Intern'),
(9, 'intern6', '$2b$10$ZpBJftxlR3MPLyf8QWldPeaH71ehs2FVOpzOmSdlTQ82X4oyaCgSO', 'Intern'),
(10, 'intern7', '$2b$10$3SXEcBTYpzQnsmumWJfvz.I2rjZasdUgm.ClQF8iPfx9eQwpxr1Wi', 'Intern'),
(11, 'intern8', '$2b$10$ZLdT8nfob2tzv.ikzuqtp.PyYXxrR9fMTqvhJV05b08HjpKgm6.7y', 'Intern'),
(12, 'intern9', '$2b$10$WiuWR0hzSGP15pcu1/5AG.cbs93rGaZxM7AEpOHdMbbI5vIX8/yk6', 'Intern'),
(13, 'intern10', '$2b$10$5yy0Aro5A4RqxxqHaZR2Q.5VRDx6JL5sZDpBH3970S1PaA4JscRxK', 'Intern'),
(14, 'adviser1', '$2b$10$pgI5Kl3fYGmY.EWffNfvpegncG82YzW.ZY6zJVrFjgGNrhODz2ASu', 'Adviser'),
(15, 'admin1', '$2y$10$G2UQJaBZ5hSSejbA9DUWVe6QgifCRQrY/oEE6T7M9hnmEyvEFgKn2', 'Administrator'),
(21, 'intern11', '$2y$10$Ywa0iev0VdQpw3oi3pxxbOhhl6rHO6tZqPHxC0xDBI8rw1Kr3dKqC', 'Intern'),
(22, 'intern12', '$2y$10$Y/bAWiiibpOWWFVC3poE0.yfWyEYFuulEkXmfsFPYcHUlFDR8OAia', 'Intern');

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
  ADD CONSTRAINT `interndetails_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `interndetails_ibfk_2` FOREIGN KEY (`adviser_id`) REFERENCES `adviserdetails` (`adviser_id`);

--
-- Constraints for table `internshiprecords`
--
ALTER TABLE `internshiprecords`
  ADD CONSTRAINT `internshiprecords_ibfk_1` FOREIGN KEY (`intern_id`) REFERENCES `interndetails` (`intern_id`),
  ADD CONSTRAINT `internshiprecords_ibfk_2` FOREIGN KEY (`adviser_id`) REFERENCES `adviserdetails` (`adviser_id`),
  ADD CONSTRAINT `internshiprecords_ibfk_3` FOREIGN KEY (`program_id`) REFERENCES `ojtprograms` (`program_id`),
  ADD CONSTRAINT `internshiprecords_ibfk_4` FOREIGN KEY (`administrator_id`) REFERENCES `admindetails` (`administrator_id`);

--
-- Constraints for table `ojtprograms`
--
ALTER TABLE `ojtprograms`
  ADD CONSTRAINT `ojtprograms_ibfk_1` FOREIGN KEY (`administrator_id`) REFERENCES `admindetails` (`administrator_id`);

--
-- Constraints for table `timetrack`
--
ALTER TABLE `timetrack`
  ADD CONSTRAINT `timetrack_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `internshiprecords` (`record_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
