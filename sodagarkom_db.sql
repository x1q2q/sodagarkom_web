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
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (46,9,4,1,'completed','2024-07-01 17:13:27'),(47,9,111,1,'completed','2024-07-01 17:13:33'),(48,9,116,1,'completed','2024-07-01 17:13:36'),(50,9,4,2,'completed','2024-07-01 17:19:58'),(52,9,3,1,'completed','2024-07-01 18:11:40'),(53,9,113,2,'completed','2024-07-01 18:11:48'),(54,9,4,2,'completed','2024-07-01 19:18:30'),(55,9,3,2,'completed','2024-07-01 19:18:33'),(56,9,5,2,'completed','2024-07-01 19:18:35'),(57,9,115,2,'completed','2024-07-01 19:18:38'),(58,9,3,2,'completed','2024-07-01 19:34:26'),(59,9,4,2,'completed','2024-07-01 19:34:29'),(60,9,5,2,'completed','2024-07-01 19:34:32'),(61,9,111,2,'completed','2024-07-01 19:34:37'),(62,9,4,2,'active','2024-07-01 19:38:20'),(63,9,3,2,'active','2024-07-01 19:38:22'),(64,9,5,2,'active','2024-07-01 19:38:26'),(65,9,111,2,'active','2024-07-01 19:38:28'),(66,9,113,2,'active','2024-07-02 07:38:04');
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (3,'Ragam Komputer','Komputer adalah perangkat elektronik yang dapat mengolah data dan menghasilkan informasi dalam berbagai bentuk, seperti teks, gambar, audio, video, atau audio-visual.','computer.png'),(4,'Ragam Laptop','Laptop, atau komputer jinjing, adalah komputer pribadi yang berukuran relatif kecil dan ringan, sehingga sifatnya portabel. Laptop merupakan evolusi dari jenis komputer desktop, di mana semua ukurannya menjadi lebih kecil, mulai dari keyboard, CPU, dan juga monitornya.','laptop.png'),(5,'Ragam CPU','CPU, atau Central Processing Unit, adalah perangkat keras komputer yang menerima dan menjalankan perintah dan data dari perangkat lunak. CPU melakukan semua pekerjaan dari setiap tindakan yang dilakukan komputer, serta menjalankan program.','cpu.png'),(6,'Ragam Monitor','Monitor adalah perangkat keras keluaran (output device system) yang menampilkan informasi dalam bentuk gambar atau tekstual yang telah diproses oleh CPU pada komputer. Monitor juga disebut sebagai layar tampilan komputer.','monitor.png'),(21,'Ragam Keyboard','Keyboard adalah perangkat input yang digunakan untuk memasukkan data ke dalam komputer atau perangkat elektronik lainnya. Keyboard komputer modern biasanya terdiri dari tombol-tombol yang mewakili karakter, angka, simbol, dan fungsi khusus.','keyboard.png');
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
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'user123','user123@gmail.com','6ad14ba9986e3615423dfca256d04e3f','User Biasa123','karangtengah, pengadegan','08457854354323',1,'2024-06-11 05:28:25'),(2,'bagas123','bagas123@gmail.com','5ffd9bb73b00bce4feeb77e2d12722da','Bagas Adi Saputra','bojongsari, purbalingga','085324329231',1,'2024-06-11 05:29:31'),(3,'user5678910','user567@gmail.com','user567','User Biasa567','Karang Moncol','08324798324',1,'2024-06-11 09:07:21'),(4,'user789','user789@gmail.com','user789','User Biasa789','Karang Joho Jawa Tengah','0849832041',1,'2024-06-11 09:11:34'),(5,'aditbojes','aditbojes@gmail.com','aditbojes123','Aditya Nur Sodiq','Pengadegan, Karang tengah','0874324932',1,'2024-06-11 09:11:34'),(6,'hasangento','hasangento@gmail.com','user1011','User Biasa1011','Babatsari, Purbalingga','083249234',1,'2024-06-11 09:11:34'),(7,'lukmanbojes','lukmanbojes@gmail.com','lukmanbojes123','Luqman Nur khakim','Bojong Serang, Karang tengah','083409234',1,'2024-06-11 09:11:34'),(8,'satya','satyabojes@gmail.com','satyabojes123','Satya Nur Hikmah','Pengadegan, Purbalingga','0823498234',1,'2024-06-11 09:11:34'),(9,'mahesa','mahesa123@gmail.com','$2y$10$10oaeGD1oVEIggg2KeOKPODNlxIohOoc6/dUFQSS.CHE2CM8CFpxi','Mahesa Jenars','Jl Desa Karangtengah, bojong serang 53393','089779796142',1,'2024-06-11 09:11:34'),(63,'piknikcoffee','piknikcoffee@gmail.com','$2y$10$i0n07seNgv86woOQxp8oqeBUzA1LJm/UlKjeXeuoQ593oe0JrJQC2','Piknik Coffee','Jl. Letkol Isdiman Purbalinga Regency','08238942312',0,'2024-07-02 04:59:53'),(64,'coba1','coba1@gmail.com','$2y$10$6uaacTuH6F/FhBH.vPvRBOn3fhDOO7xpqgGfdJTUET90X5nHzaOSe','Coba Aja','Piknik Coffee','088888888',1,'2024-07-02 06:18:00'),(66,'rafiknurf','rbojjes@gmail.com','$2y$10$VJ89t4zAkSluI3pIJc84ZuA9ZHBqmyAANEDsPW8nWKeMZRnkcs/y2','arafik nur fadliansah','karang tengah','085714186920',1,'2024-07-02 08:05:57');
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
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (3,4,'Laptop Lenovo','deskripsi laptop lenovo',2500000,90,94,'lenovo.png'),(4,4,'Acer Aspire','deskripsi laptop Acer',2800000,100,104,'acer.jpg'),(5,4,'Laptop ASUS','deskripsi produk laptop asus',3000000,80,84,'asus.jpg'),(111,4,'ASUS A416 (11th Gen Intel)','deskripsi asus1',15000000,15,17,'asus-laptop.png'),(113,4,'Macbook M1','macbook orisinil asli bos',20000000,28,28,'macbook.jpg'),(115,4,'Lenovo Thinkpad T440S','laptop lenovo tahan banting banget',5000000,10,12,'lenovo-thiknpad.jpg'),(116,4,'Laptop Macbook 16','coba deskripsi macbook',18000000,12,12,'macbook16.jpg');
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
  `total_price` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_details_ibfk_1` (`transaction_id`),
  KEY `transaction_details_ibfk_2` (`product_id`),
  CONSTRAINT `transaction_details_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaction_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_details`
--

LOCK TABLES `transaction_details` WRITE;
/*!40000 ALTER TABLE `transaction_details` DISABLE KEYS */;
INSERT INTO `transaction_details` VALUES (4,2,4,1,2800000),(5,11,113,2,40000000),(6,11,5,1,3000000),(7,3,4,1,2800000),(8,3,3,2,5000000),(9,12,3,3,7500000),(10,12,4,2,5600000),(11,12,113,1,20000000),(24,19,3,2,5000000),(25,19,4,3,8400000),(26,19,5,2,6000000),(33,22,116,1,18000000),(34,22,111,1,15000000),(35,22,4,1,2800000),(36,23,113,2,40000000),(37,23,3,1,2500000),(38,23,4,2,5600000);
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
  `total_shipping` int(11) NOT NULL DEFAULT 35000,
  `payment_method` varchar(255) NOT NULL,
  `payment_proof` varchar(255) DEFAULT 'transfer manual',
  `status` enum('reserved','pending','accepted','rejected') DEFAULT 'reserved',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `transactions_ibfk_1` (`customer_id`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (2,7,4925000,35000,'transfer manual','bukti2.png','accepted','2024-06-14 06:10:03'),(3,9,7835000,35000,'transfer manual','1000179722.jpg','pending','2024-06-13 06:10:11'),(5,8,190000,35000,'transfer manual',NULL,'reserved','2024-06-13 15:59:52'),(6,6,350000,35000,'transfer manual','bukti3.png','pending','2024-06-15 06:39:24'),(7,5,4000000,35000,'transfer manual','','reserved','2024-06-14 07:10:11'),(8,4,4500000,35000,'transfer manual','','reserved','2024-06-12 06:41:40'),(9,3,150000,35000,'transfer manual','','reserved','2024-05-14 07:10:11'),(10,2,150000,35000,'transfer manual','','reserved','2024-05-14 07:10:11'),(11,1,43000000,35000,'transfer manual','bukti2.png','accepted','2023-06-14 07:10:11'),(12,9,33135000,35000,'transfer manual','1000182305.jpg','pending','2024-06-28 01:54:29'),(19,9,19435000,35000,'transfer manual','','rejected','2024-07-01 06:29:00'),(22,9,35835000,35000,'transfer manual','','rejected','2024-07-01 17:13:42'),(23,9,48135000,35000,'transfer manual','1000182409.png','pending','2024-07-01 19:04:24');
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

-- Dump completed on 2024-07-02 15:14:27
