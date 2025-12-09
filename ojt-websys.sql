-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: localhost    Database: ojt-websys
-- ------------------------------------------------------
-- Server version	9.1.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admindetails`
--

DROP TABLE IF EXISTS `admindetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admindetails` (
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
  KEY `company_id` (`company_id`),
  CONSTRAINT `admindetails_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `admindetails_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admindetails`
--

LOCK TABLES `admindetails` WRITE;
/*!40000 ALTER TABLE `admindetails` DISABLE KEYS */;
INSERT INTO `admindetails` VALUES (1,1,1,'Gus ','Fring','gfring@slu.edu.ph','Baguio City',NULL);
/*!40000 ALTER TABLE `admindetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `adviserdetails`
--

DROP TABLE IF EXISTS `adviserdetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `adviserdetails` (
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
  KEY `administrator_id` (`administrator_id`),
  CONSTRAINT `adviserdetails_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `adviserdetails_ibfk_2` FOREIGN KEY (`administrator_id`) REFERENCES `admindetails` (`administrator_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adviserdetails`
--

LOCK TABLES `adviserdetails` WRITE;
/*!40000 ALTER TABLE `adviserdetails` DISABLE KEYS */;
INSERT INTO `adviserdetails` VALUES (1,3,1,'Jesse','Pinkman','jpinkman@slu.edu.ph','Saint Louis University','Quezon');
/*!40000 ALTER TABLE `adviserdetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `company` (
  `company_id` int NOT NULL AUTO_INCREMENT,
  `companyName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `companyAddress` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `companyDetails` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company`
--

LOCK TABLES `company` WRITE;
/*!40000 ALTER TABLE `company` DISABLE KEYS */;
INSERT INTO `company` VALUES (1,'Amazon','Baguio City','Test 1'),(2,'Google','Quezon City','Test 2');
/*!40000 ALTER TABLE `company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feedback` (
  `feedback_id` int NOT NULL AUTO_INCREMENT,
  `record_id` int DEFAULT NULL,
  `feedback_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `feedback_date` date NOT NULL,
  PRIMARY KEY (`feedback_id`),
  KEY `record_id` (`record_id`),
  CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `internshiprecords` (`record_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback`
--

LOCK TABLES `feedback` WRITE;
/*!40000 ALTER TABLE `feedback` DISABLE KEYS */;
INSERT INTO `feedback` VALUES (4,35,'Try','2025-07-22'),(5,36,'Good Job','2025-07-22'),(6,39,'Good luck','2025-07-23');
/*!40000 ALTER TABLE `feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `interndetails`
--

DROP TABLE IF EXISTS `interndetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `interndetails` (
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
  KEY `company_id` (`company_id`),
  CONSTRAINT `interndetails_ibfk_1` FOREIGN KEY (`supervisor_id`) REFERENCES `supervisordetails` (`supervisor_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `interndetails_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `interndetails_ibfk_3` FOREIGN KEY (`adviser_id`) REFERENCES `adviserdetails` (`adviser_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `interndetails_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `interndetails`
--

LOCK TABLES `interndetails` WRITE;
/*!40000 ALTER TABLE `interndetails` DISABLE KEYS */;
INSERT INTO `interndetails` VALUES (14,14,1,1,1,'Brown','Lee','blee@gmail.com',9300),(15,15,1,1,1,'First','Last','flast@gmail.com',9300),(16,16,1,1,1,'Internet','Three','ithree@gmail.com',9500);
/*!40000 ALTER TABLE `interndetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `internshiprecords`
--

DROP TABLE IF EXISTS `internshiprecords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `internshiprecords` (
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
  KEY `fk_supervisor` (`supervisor_id`),
  CONSTRAINT `fk_supervisor` FOREIGN KEY (`supervisor_id`) REFERENCES `supervisordetails` (`supervisor_id`),
  CONSTRAINT `internshiprecords_ibfk_1` FOREIGN KEY (`administrator_id`) REFERENCES `admindetails` (`administrator_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `internshiprecords_ibfk_2` FOREIGN KEY (`adviser_id`) REFERENCES `adviserdetails` (`adviser_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `internshiprecords_ibfk_3` FOREIGN KEY (`intern_id`) REFERENCES `interndetails` (`intern_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `internshiprecords_ibfk_4` FOREIGN KEY (`program_id`) REFERENCES `ojtprograms` (`program_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `internshiprecords`
--

LOCK TABLES `internshiprecords` WRITE;
/*!40000 ALTER TABLE `internshiprecords` DISABLE KEYS */;
INSERT INTO `internshiprecords` VALUES (35,14,1,NULL,1,0,100,'2025-07-22','2025-09-27','In Progress',0,1),(36,15,1,NULL,1,0,100,'2025-07-22','2025-09-27','In Progress',1,1),(39,16,1,NULL,1,0,100,'2025-07-23','2025-09-27','In Progress',0,1);
/*!40000 ALTER TABLE `internshiprecords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ojtprograms`
--

DROP TABLE IF EXISTS `ojtprograms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ojtprograms` (
  `program_id` int NOT NULL AUTO_INCREMENT,
  `administrator_id` int DEFAULT NULL,
  `program_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `start_datee` date NOT NULL,
  `end_date` date NOT NULL,
  PRIMARY KEY (`program_id`),
  KEY `administrator_id` (`administrator_id`),
  CONSTRAINT `ojtprograms_ibfk_1` FOREIGN KEY (`administrator_id`) REFERENCES `admindetails` (`administrator_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ojtprograms`
--

LOCK TABLES `ojtprograms` WRITE;
/*!40000 ALTER TABLE `ojtprograms` DISABLE KEYS */;
INSERT INTO `ojtprograms` VALUES (3,1,'Com Sci','2025-07-22','2025-07-31'),(4,1,'IT','2025-07-22','2025-08-01');
/*!40000 ALTER TABLE `ojtprograms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supervisordetails`
--

DROP TABLE IF EXISTS `supervisordetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `supervisordetails` (
  `supervisor_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `administrator_id` int NOT NULL,
  `firstName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lastName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`supervisor_id`),
  KEY `user_id` (`user_id`),
  KEY `administrator_id` (`administrator_id`),
  CONSTRAINT `supervisordetails_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `supervisordetails_ibfk_2` FOREIGN KEY (`administrator_id`) REFERENCES `admindetails` (`administrator_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supervisordetails`
--

LOCK TABLES `supervisordetails` WRITE;
/*!40000 ALTER TABLE `supervisordetails` DISABLE KEYS */;
INSERT INTO `supervisordetails` VALUES (1,2,1,'Mike','Estes','mestes@slu.edu.ph');
/*!40000 ALTER TABLE `supervisordetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timetrack`
--

DROP TABLE IF EXISTS `timetrack`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `timetrack` (
  `timetrack_id` int NOT NULL AUTO_INCREMENT,
  `record_id` int NOT NULL,
  `date` date NOT NULL,
  `hours_submit` decimal(5,2) NOT NULL,
  PRIMARY KEY (`timetrack_id`),
  KEY `record_id` (`record_id`),
  CONSTRAINT `timetrack_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `internshiprecords` (`record_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timetrack`
--

LOCK TABLES `timetrack` WRITE;
/*!40000 ALTER TABLE `timetrack` DISABLE KEYS */;
/*!40000 ALTER TABLE `timetrack` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_type` enum('Intern','Adviser','Supervisor','Administrator') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2a$08$aTjRX91Jl4xLOLAAMpCdAua1El1S5/KA0nBF5SD5mkiIAM/PTcd6y','Administrator'),(2,'supervisor','$2a$08$TVWFq9K/y8CgHXqwdwH1VeNVqIXftaao3Y21nT9i8lIx2HHrMyz0G','Supervisor'),(3,'adviser','$2a$08$e7..AGJcKW8UZokOKLBMo.FzgrQYNUAU41/ChyxhHECu7pAB6X8dS','Adviser'),(14,'intern1','$2y$10$xIfLHs8s.PhM5anTBxgNTehB8k4FIMqnVXcBUfAOpIkN8PhXrNM0m','Intern'),(15,'intern2','$2y$10$CkpMgI/0Nb2IuakWQaATWeEfl2HWFcoi3HSL8Q1N9xEroSsWWBxjK','Intern'),(16,'intern3','$2y$10$Pg1ndLzMnWNxMewthn/MW.9jhtnCJZ6E7uUM6RyxWk2ed.5Atb8/C','Intern');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-11 17:00:55
