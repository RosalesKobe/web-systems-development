-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 11, 2023 at 07:39 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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

CREATE TABLE `admindetails` (
  `administrator_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(100) NOT NULL,
  `other_administrator_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admindetails`
--

INSERT INTO `admindetails` (`administrator_id`, `user_id`, `company_id`, `firstName`, `lastName`, `email`, `address`, `other_administrator_details`) VALUES
(1, 3, 1, 'Admin', 'Ito', 'admin@slu.edu.ph', 'Abra', NULL),
(2, 15, 1, 'Saul', 'Goodman', 'sgoodman@gmail.com', 'Bucay, Abra', 'Company Admin');

-- --------------------------------------------------------

--
-- Table structure for table `adviserdetails`
--

CREATE TABLE `adviserdetails` (
  `adviser_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `School` varchar(255) NOT NULL,
  `address` varchar(100) NOT NULL,
  `other_adviser_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adviserdetails`
--

INSERT INTO `adviserdetails` (`adviser_id`, `user_id`, `firstName`, `lastName`, `email`, `School`, `address`, `other_adviser_details`) VALUES
(1, 2, 'Adviser', 'AdviserR', 'adviser@slu.edu.ph', 'slu', 'baguio', NULL),
(2, 14, 'Walter ', 'White', 'wwhite@slu.edu.ph', 'Saint Louis University', 'Baguio City', 'Good Teacher');

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `company_id` int(11) NOT NULL,
  `companyName` varchar(50) NOT NULL,
  `companyAddress` varchar(100) NOT NULL,
  `companyDetails` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`company_id`, `companyName`, `companyAddress`, `companyDetails`) VALUES
(1, 'Amazon', 'Abra', 'ASDASD');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `document_id` int(11) NOT NULL,
  `record_id` int(11) DEFAULT NULL,
  `document_name` varchar(255) NOT NULL,
  `document_path` varchar(255) NOT NULL,
  `other_document_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `record_id` int(11) DEFAULT NULL,
  `feedback_text` text NOT NULL,
  `feedback_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `record_id`, `feedback_text`, `feedback_date`) VALUES
(1, 1, 'The intern completed his work and performed well in regards to the tasks that is given to him.', '2023-09-05'),
(2, 2, 'The intern is currently performing well. She complies to the given tasks and do it on time.', '2023-11-21'),
(12, 1, 'this intern is very good cuh.', '2023-12-11'),
(13, 2, 'Tangina mo cuh, drop kana!', '2023-12-11');

-- --------------------------------------------------------

--
-- Table structure for table `interndetails`
--

CREATE TABLE `interndetails` (
  `intern_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `adviser_id` int(11) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(100) NOT NULL,
  `School` varchar(255) NOT NULL,
  `other_intern_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `interndetails`
--

INSERT INTO `interndetails` (`intern_id`, `user_id`, `adviser_id`, `firstName`, `lastName`, `email`, `address`, `School`, `other_intern_details`) VALUES
(1, 1, 1, 'Intern', 'Ito', 'intern@slu.edu.ph', 'Baguio', 'SLU', NULL),
(4, 4, 2, 'Jesse', 'Pinkman', 'jpinkman@slu.edu.ph', 'Quezon City', 'Saint Louis University', 'Computer Science Intern Student'),
(5, 5, 2, 'Gus', 'Fring', 'gfring@slu.edu.ph', 'Quezon City', 'Saint Louis University', 'Computer Science Intern Student'),
(6, 6, 2, 'Tuco', 'Salamanca', 'tsalamanca@slu.edu.ph', 'Quezon Province', 'Saint Louis University', 'Computer Science Intern Student'),
(7, 7, 2, 'Mike', 'Ehrmantraut', 'mehrmantraut@slu.edu.ph', 'Baguio City', 'Saint Louis University', 'Computer Science Intern Student'),
(8, 8, 2, 'Jane', 'Margolis', 'jmargolis@slu.edu.ph', 'Baguio City', 'Saint Louis University', 'Computer Science Intern Student'),
(9, 9, 2, 'Hank', 'Schrader', 'hschrader@slu.edu.ph', 'Baguio City', 'Saint Louis University', NULL),
(10, 10, 2, 'Skinny', 'Pete', 'spete@slu.edu.ph', 'Baguio City', 'Saint Louis University', NULL),
(11, 11, 2, 'Juan', 'Bolsa', 'jbolsa@slu.edu.ph', 'Bakakeng Sur, Baguio City', 'Saint Louis University', 'Computer Science Intern Student'),
(12, 12, 2, 'Chuck', 'McGill', 'cmcgill@slu.edu.ph', 'Bakakeng Sur, Baguio City', 'Saint Louis University', NULL),
(13, 13, 2, 'Kim', 'Wexler', 'kwexler@slu.edu.ph', 'Bakakeng Norte, Baguio City', 'Saint Louis University', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `internshiprecords`
--

CREATE TABLE `internshiprecords` (
  `record_id` int(11) NOT NULL,
  `intern_id` int(11) DEFAULT NULL,
  `adviser_id` int(11) DEFAULT NULL,
  `program_id` int(11) DEFAULT NULL,
  `administrator_id` int(11) NOT NULL,
  `hours_completed` int(11) NOT NULL,
  `hours_remaining` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `record_status` enum('In Progress','Completed') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `internshiprecords`
--

INSERT INTO `internshiprecords` (`record_id`, `intern_id`, `adviser_id`, `program_id`, `administrator_id`, `hours_completed`, `hours_remaining`, `start_date`, `end_date`, `record_status`) VALUES
(1, 1, 1, 1, 1, 100, 0, '2023-07-01', '2023-08-31', 'Completed'),
(2, 4, 2, 2, 2, 70, 30, '2023-11-01', '0000-00-00', 'In Progress');

-- --------------------------------------------------------

--
-- Table structure for table `ojtprograms`
--

CREATE TABLE `ojtprograms` (
  `program_id` int(11) NOT NULL,
  `administrator_id` int(11) DEFAULT NULL,
  `program_name` varchar(255) NOT NULL,
  `start_datee` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ojtprograms`
--

INSERT INTO `ojtprograms` (`program_id`, `administrator_id`, `program_name`, `start_datee`, `end_date`) VALUES
(1, 1, 'Product Development Program.\r\n\r\nObjective: To design, develop, and launch new products in the market.\r\nKey Initiatives:\r\nResearch and Development\r\nPrototyping\r\nTesting and Quality Assurance\r\nLaunch Strategy', '2023-12-20', '2023-12-23'),
(2, 1, 'Marketing and Branding Program.\r\n\r\nObjective: To improve customer satisfaction and service delivery.\r\nKey Initiatives:\r\nCustomer Support Training\r\nService Quality Monitoring\r\nFeedback and Improvement', '2024-01-02', '2024-01-03'),
(3, 1, 'Customer Service Enhancement Program.\r\n\r\nObjective: To enhance employee skills and foster professional growth.\r\nKey Initiatives:\r\nTraining Workshops\r\nLeadership Development\r\nSkill Enhancement Programs', '2024-01-04', '2024-01-06'),
(4, 1, 'Employee Training and Development Program.\r\n\r\nObjective: To stay at the forefront of technological advancements.\r\nKey Initiatives:\r\nResearch on Emerging Technologies\r\nSystem Upgrades\r\nInnovation Labs', '2024-01-08', '2024-01-12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('Intern','Adviser','Administrator') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `user_type`) VALUES
(1, 'internK', 'internK', 'Intern'),
(2, 'adviserK', 'adviserK', 'Adviser'),
(3, 'adminK', 'adminK', 'Administrator'),
(4, 'intern1', 'intern1', 'Intern'),
(5, 'intern2', 'intern2', 'Intern'),
(6, 'intern3', 'intern3', 'Intern'),
(7, 'intern4', 'intern4', 'Intern'),
(8, 'intern5', 'intern5', 'Intern'),
(9, 'intern6', 'intern6', 'Intern'),
(10, 'intern7', 'intern7', 'Intern'),
(11, 'intern8', 'intern8', 'Intern'),
(12, 'intern9', 'intern9', 'Intern'),
(13, 'intern10', 'intern10', 'Intern'),
(14, 'adviser1', 'adviser1', 'Adviser'),
(15, 'admin1', 'admin1', 'Administrator');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admindetails`
--
ALTER TABLE `admindetails`
  ADD PRIMARY KEY (`administrator_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `adviserdetails`
--
ALTER TABLE `adviserdetails`
  ADD PRIMARY KEY (`adviser_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `record_id` (`record_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `record_id` (`record_id`);

--
-- Indexes for table `interndetails`
--
ALTER TABLE `interndetails`
  ADD PRIMARY KEY (`intern_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `adviser_id` (`adviser_id`);

--
-- Indexes for table `internshiprecords`
--
ALTER TABLE `internshiprecords`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `intern_id` (`intern_id`),
  ADD KEY `adviser_id` (`adviser_id`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `administrator_id` (`administrator_id`);

--
-- Indexes for table `ojtprograms`
--
ALTER TABLE `ojtprograms`
  ADD PRIMARY KEY (`program_id`),
  ADD KEY `administrator_id` (`administrator_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admindetails`
--
ALTER TABLE `admindetails`
  MODIFY `administrator_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `adviserdetails`
--
ALTER TABLE `adviserdetails`
  MODIFY `adviser_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `interndetails`
--
ALTER TABLE `interndetails`
  MODIFY `intern_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `internshiprecords`
--
ALTER TABLE `internshiprecords`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ojtprograms`
--
ALTER TABLE `ojtprograms`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
  ADD CONSTRAINT `adviserdetails_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `internshiprecords` (`record_id`);

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
