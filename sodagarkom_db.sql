-- MariaDB dump 10.19  Distrib 10.4.22-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: sodagarkom_db
-- ------------------------------------------------------
-- Server version	10.4.22-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `sodagarkom_db`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `sodagarkom_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `sodagarkom_db`;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'admin','admin@gmail.com','$2y$10$9Aqk1a9vNRGLm6Mv8qTwcOeZ3X/.0qw32LmWAn9DppJLEbOdQ9XkW','Hanya Admin Biasa','2024-06-11 05:26:30');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `status` enum('active','completed') NOT NULL DEFAULT 'active',
  `added_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (1,10,3,2,'active','2024-06-26 07:42:47');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_thumb` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (3,'Komputer','ini deskripsi kategori2 ini deskripsi kategori2 ini deskripsi kategori2 lorem ipsum banget nih astaga ini deskripsi kategori2 ini deskripsi kategori2 ini deskripsi kategori2 lorem ipsum banget nih astagaini','computer.png'),(4,'Laptop','Deskripsi laptop banget laptopnya','laptop.png'),(5,'CPU','ini deskripsi CPU BOS \nini deskripsi CPU BOS\nini deskripsi CPU BOS ini deskripsi CPU BOS ini deskripsi CPU BOS ini deskripsi CPU BOS ini deskripsi CPU BOS','cpu.png'),(6,'Monitor','ini monitor baru monitor merk mahal ini monitor baru monitor merk mahal banget','monitor.png');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `is_accepted` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'user123','user123@gmail.com','6ad14ba9986e3615423dfca256d04e3f','User Biasa123','karangtengah, pengadegan','08457854354323',1,'2024-06-11 05:28:25'),(2,'bagas123','bagas123@gmail.com','5ffd9bb73b00bce4feeb77e2d12722da','Bagas Adi Saputra','bojongsari, purbalingga','085324329231',1,'2024-06-11 05:29:31'),(3,'user5678910','user567@gmail.com','user567','User Biasa567','Karang Moncol','08324798324',0,'2024-06-11 09:07:21'),(4,'user789','user789@gmail.com','user789','User Biasa789','Karang Joho Jawa Tengah','0849832041',1,'2024-06-11 09:11:34'),(5,'aditbojes','aditbojes@gmail.com','aditbojes123','Aditya Nur Sodiq','Pengadegan, Karang tengah','0874324932',1,'2024-06-11 09:11:34'),(6,'hasangento','hasangento@gmail.com','user1011','User Biasa1011','Babatsari, Purbalingga','083249234',1,'2024-06-11 09:11:34'),(7,'lukmanbojes','lukmanbojes@gmail.com','lukmanbojes123','Luqman Nur khakim','Bojong Serang, Karang tengah','083409234',1,'2024-06-11 09:11:34'),(8,'satya','satyabojes@gmail.com','satyabojes123','Satya Nur Hikmah','Pengadegan, Purbalingga','0823498234',1,'2024-06-11 09:11:34'),(9,'mahesa','mahesabojes@gmail.com','mahesabojes123','Mahesa Jenar','Pengadegan, Karang Tengah RT01/RW11','0898423433',1,'2024-06-11 09:11:34'),(10,'rafiknurf','rafiknurf@gmail.com','rafiknurf123','Rafik Nurf','Kartasura','0834321988',1,'2024-06-11 09:11:34'),(29,'satyasatya','satya12312@gmail.com','f90f1acd65da9b3757da386c5202fe71','satya stya asyt','satya pertama alamat','083247238',1,'2024-06-11 17:37:36'),(58,'Customer123','rbojjes@gmail.com','3e108c54323bf218f7b8c973bdf4ecbc','Customer bojes','Alamat customer bojes','087575464',1,'2024-06-12 12:26:27');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` bigint(20) NOT NULL,
  `stock` int(11) NOT NULL,
  `stock_temporary` int(11) NOT NULL,
  `image_thumb` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `products_ibfk_1` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (3,4,'Laptop Lenovo','deskripsi laptop lenovo',2500000,90,90,'lenovo.png'),(4,4,'Acer Aspire','deskripsi laptop Acer',2800000,100,100,'acer.jpg'),(5,4,'Laptop ASUS','deskripsi produk laptop asus',3000000,80,80,'asus.jpg'),(8,6,'Monitor bekas murah pisan','monitor bekas pemakaian masih bagus 123',125000,240,240,''),(16,3,'komputer 12','deskripsi komputer12',40000,100,100,''),(17,3,'komputer 13','deskripsi komputer13',50000,120,120,''),(18,3,'komputer 14','deskripsi komputer14',60000,110,110,''),(19,3,'komputer 15','deskripsi komputer15',55000,150,150,''),(20,4,'Laptop Acer1','deskripsi acer1',25000000,10,10,''),(21,4,'Laptop Acer2','deskripsi acer2',35000000,10,10,''),(22,4,'Laptop Acer3','deskripsi acer3',30000000,10,10,''),(23,4,'Laptop Asus1','deskripsi asus1',15000000,15,15,''),(24,4,'Laptop Asus2','deskripsi asus2',27500000,20,20,''),(25,3,'komputer 13','deskripsi komputer13',50000,120,120,''),(26,3,'komputer 14','deskripsi komputer14',60000,110,110,''),(27,3,'komputer 15','deskripsi komputer15',55000,150,150,''),(28,4,'Laptop Acer1','deskripsi acer1',25000000,10,10,''),(29,4,'Laptop Acer2','deskripsi acer2',35000000,10,10,''),(30,4,'Laptop Acer3','deskripsi acer3',30000000,10,10,''),(31,4,'Laptop Asus1','deskripsi asus1',15000000,15,15,''),(32,4,'Laptop Asus2','deskripsi asus2',27500000,20,20,''),(33,3,'komputer 13','deskripsi komputer13',50000,120,120,''),(34,3,'komputer 14','deskripsi komputer14',60000,110,110,''),(35,3,'komputer 15','deskripsi komputer15',55000,150,150,''),(37,4,'Laptop Acer2','deskripsi acer2',35000000,10,10,''),(38,4,'Laptop Acer3','deskripsi acer3',30000000,10,10,''),(39,4,'Laptop Asus1','deskripsi asus1',15000000,15,15,''),(40,4,'Laptop Asus2','deskripsi asus2',27500000,20,20,''),(41,3,'komputer 13','deskripsi komputer13',50000,120,120,''),(42,3,'komputer 14','deskripsi komputer14',60000,110,110,''),(43,3,'komputer 15','deskripsi komputer15',55000,150,150,''),(44,4,'Laptop Acer1','deskripsi acer1',25000000,10,10,''),(45,4,'Laptop Acer2','deskripsi acer2',35000000,10,10,''),(46,4,'Laptop Acer3','deskripsi acer3',30000000,10,10,''),(47,4,'Laptop Asus1','deskripsi asus1',15000000,15,15,''),(48,4,'Laptop Asus2','deskripsi asus2',27500000,20,20,''),(49,3,'komputer 13','deskripsi komputer13',50000,120,120,''),(50,3,'komputer 14','deskripsi komputer14',60000,110,110,''),(51,3,'komputer 15','deskripsi komputer15',55000,150,150,''),(52,4,'Laptop Acer1','deskripsi acer1',25000000,10,10,''),(53,4,'Laptop Acer2','deskripsi acer2',35000000,10,10,''),(54,4,'Laptop Acer3','deskripsi acer3',30000000,10,10,''),(55,4,'Laptop Asus1','deskripsi asus1',15000000,15,15,''),(56,4,'Laptop Asus2','deskripsi asus2',27500000,20,20,''),(57,3,'komputer 13','deskripsi komputer13',50000,120,120,''),(58,3,'komputer 14','deskripsi komputer14',60000,110,110,''),(59,3,'komputer 15','deskripsi komputer15',55000,150,150,''),(60,4,'Laptop Acer1','deskripsi acer1',25000000,10,10,''),(61,4,'Laptop Acer2','deskripsi acer2',35000000,10,10,''),(62,4,'Laptop Acer3','deskripsi acer3',30000000,10,10,''),(63,4,'Laptop Asus1','deskripsi asus1',15000000,15,15,''),(64,4,'Laptop Asus2','deskripsi asus2',27500000,20,20,''),(65,3,'komputer 13','deskripsi komputer13',50000,120,120,''),(66,3,'komputer 14','deskripsi komputer14',60000,110,110,''),(67,3,'komputer 15','deskripsi komputer15',55000,150,150,''),(68,4,'Laptop Acer1','deskripsi acer1',25000000,10,10,''),(69,4,'Laptop Acer2','deskripsi acer2',35000000,10,10,''),(70,4,'Laptop Acer3','deskripsi acer3',30000000,10,10,''),(71,4,'Laptop Asus1','deskripsi asus1',15000000,15,15,''),(72,4,'Laptop Asus2','deskripsi asus2',27500000,20,20,''),(73,3,'komputer 13','deskripsi komputer13',50000,120,120,''),(74,3,'komputer 14','deskripsi komputer14',60000,110,110,''),(75,3,'komputer 15','deskripsi komputer15',55000,150,150,''),(76,4,'Laptop Acer1','deskripsi acer1',25000000,10,10,''),(77,4,'Laptop Acer2','deskripsi acer2',35000000,10,10,''),(78,4,'Laptop Acer3','deskripsi acer3',30000000,10,10,''),(79,4,'Laptop Asus1','deskripsi asus1',15000000,15,15,''),(80,4,'Laptop Asus2','deskripsi asus2',27500000,20,20,''),(81,3,'komputer 13','deskripsi komputer13',50000,120,120,''),(82,3,'komputer 14','deskripsi komputer14',60000,110,110,''),(83,3,'komputer 15','deskripsi komputer15',55000,150,150,''),(84,4,'Laptop Acer1','deskripsi acer1',25000000,10,10,''),(85,4,'Laptop Acer2','deskripsi acer2',35000000,10,10,''),(86,4,'Laptop Acer3','deskripsi acer3',30000000,10,10,''),(87,4,'Laptop Asus1','deskripsi asus1',15000000,15,15,''),(88,4,'Laptop Asus2','deskripsi asus2',27500000,20,20,''),(89,3,'komputer 13','deskripsi komputer13',50000,120,120,''),(90,3,'komputer 14','deskripsi komputer14',60000,110,110,''),(91,3,'komputer 15','deskripsi komputer15',55000,150,150,''),(92,4,'Laptop Acer1','deskripsi acer1',25000000,10,10,''),(93,4,'Laptop Acer2','deskripsi acer2',35000000,10,10,''),(94,4,'Laptop Acer3','deskripsi acer3',30000000,10,10,''),(95,4,'Laptop Asus1','deskripsi asus1',15000000,15,15,''),(96,4,'Laptop Asus2','deskripsi asus2',27500000,20,20,''),(97,3,'komputer 13','deskripsi komputer13',50000,120,120,''),(98,3,'komputer 14','deskripsi komputer14',60000,110,110,''),(99,3,'komputer 15','deskripsi komputer15',55000,150,150,''),(100,4,'Laptop Acer1','deskripsi acer1',25000000,10,10,''),(101,4,'Laptop Acer2','deskripsi acer2',35000000,10,10,''),(102,4,'Laptop Acer3','deskripsi acer3',30000000,10,10,''),(103,4,'Laptop Asus1','deskripsi asus1',15000000,15,15,''),(104,4,'Laptop Asus2','deskripsi asus2',27500000,20,20,''),(105,3,'komputer 13','deskripsi komputer13',50000,120,120,''),(106,3,'komputer 14','deskripsi komputer14',60000,110,110,''),(107,3,'komputer 15','deskripsi komputer15',55000,150,150,''),(108,4,'Laptop Acer1','deskripsi acer1',25000000,10,10,''),(109,4,'Laptop Acer2','deskripsi acer2',35000000,10,10,''),(110,4,'Laptop Acer3','deskripsi acer3',30000000,10,10,''),(111,4,'Laptop Asus1','deskripsi asus1',15000000,15,15,''),(112,4,'Laptop Asus2','deskripsi asus2',27500000,20,20,''),(113,4,'Macbook M1','macbook orisinil asli bos',20000000,28,28,'macbook.jpg');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_details`
--

DROP TABLE IF EXISTS `transaction_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_details_ibfk_1` (`transaction_id`),
  KEY `transaction_details_ibfk_2` (`product_id`),
  CONSTRAINT `transaction_details_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaction_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_details`
--

LOCK TABLES `transaction_details` WRITE;
/*!40000 ALTER TABLE `transaction_details` DISABLE KEYS */;
INSERT INTO `transaction_details` VALUES (1,1,8,2,250000),(3,2,8,1,125000),(4,2,4,1,2800000),(5,11,113,2,40000000),(6,11,5,1,3000000),(7,3,4,1,2800000),(8,3,3,2,5000000);
/*!40000 ALTER TABLE `transaction_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `total_amount` bigint(20) NOT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `status` enum('reserved','pending','accepted','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `transactions_ibfk_1` (`customer_id`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (1,10,250000,'bukti1.jpg','accepted','2024-06-13 06:09:39'),(2,7,4925000,'bukti2.png','accepted','2024-06-14 06:10:03'),(3,9,7800000,'bukti3.png','rejected','2024-06-13 06:10:11'),(5,8,190000,'','rejected','2024-06-13 15:59:52'),(6,6,350000,'','reserved','2024-06-15 06:39:24'),(7,5,4000000,'','reserved','2024-06-14 07:10:11'),(8,4,4500000,'','reserved','2024-06-12 06:41:40'),(9,3,150000,'','reserved','2024-05-14 07:10:11'),(10,2,150000,'','reserved','2024-05-14 07:10:11'),(11,1,43000000,'bukti2.png','accepted','2023-06-14 07:10:11');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `transactions_per_day`
--

DROP TABLE IF EXISTS `transactions_per_day`;
/*!50001 DROP VIEW IF EXISTS `transactions_per_day`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `transactions_per_day` (
  `date` tinyint NOT NULL,
  `transaction_count` tinyint NOT NULL,
  `total_amount` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `transactions_per_month`
--

DROP TABLE IF EXISTS `transactions_per_month`;
/*!50001 DROP VIEW IF EXISTS `transactions_per_month`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `transactions_per_month` (
  `year` tinyint NOT NULL,
  `month` tinyint NOT NULL,
  `transaction_count` tinyint NOT NULL,
  `total_amount` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `transactions_per_year`
--

DROP TABLE IF EXISTS `transactions_per_year`;
/*!50001 DROP VIEW IF EXISTS `transactions_per_year`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `transactions_per_year` (
  `year` tinyint NOT NULL,
  `transaction_count` tinyint NOT NULL,
  `total_amount` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Current Database: `sodagarkom_db`
--

USE `sodagarkom_db`;

--
-- Final view structure for view `transactions_per_day`
--

/*!50001 DROP TABLE IF EXISTS `transactions_per_day`*/;
/*!50001 DROP VIEW IF EXISTS `transactions_per_day`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `transactions_per_day` AS select cast(`transactions`.`created_at` as date) AS `date`,count(0) AS `transaction_count`,sum(`transactions`.`total_amount`) AS `total_amount` from `transactions` where `transactions`.`status` = 'accepted' group by cast(`transactions`.`created_at` as date) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `transactions_per_month`
--

/*!50001 DROP TABLE IF EXISTS `transactions_per_month`*/;
/*!50001 DROP VIEW IF EXISTS `transactions_per_month`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `transactions_per_month` AS select year(`transactions`.`created_at`) AS `year`,month(`transactions`.`created_at`) AS `month`,count(0) AS `transaction_count`,sum(`transactions`.`total_amount`) AS `total_amount` from `transactions` where `transactions`.`status` = 'accepted' group by year(`transactions`.`created_at`),month(`transactions`.`created_at`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `transactions_per_year`
--

/*!50001 DROP TABLE IF EXISTS `transactions_per_year`*/;
/*!50001 DROP VIEW IF EXISTS `transactions_per_year`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `transactions_per_year` AS select year(`transactions`.`created_at`) AS `year`,count(0) AS `transaction_count`,sum(`transactions`.`total_amount`) AS `total_amount` from `transactions` where `transactions`.`status` = 'accepted' group by year(`transactions`.`created_at`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-06-26 14:45:37
