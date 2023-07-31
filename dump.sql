-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: quiz
-- ------------------------------------------------------
-- Server version	8.0.19

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `value` text,
  `date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Site logs';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `offline_answers`
--

DROP TABLE IF EXISTS `offline_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `offline_answers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quiz_id` int NOT NULL,
  `answers` json NOT NULL,
  `questions` json NOT NULL,
  `quiz_sign` varchar(32) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offline_answers`
--

LOCK TABLES `offline_answers` WRITE;
/*!40000 ALTER TABLE `offline_answers` DISABLE KEYS */;
/*!40000 ALTER TABLE `offline_answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `online_sessions`
--

DROP TABLE IF EXISTS `online_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `online_sessions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quiz_id` int NOT NULL,
  `socket` varchar(32) NOT NULL,
  `pincode` int NOT NULL,
  `start_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `answers` json NOT NULL,
  `questions` json DEFAULT NULL,
  `expire_on` int NOT NULL DEFAULT '0',
  `status` int DEFAULT '1' COMMENT '0 - deactivated\n1 - active',
  `token` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `online_sessions`
--

LOCK TABLES `online_sessions` WRITE;
/*!40000 ALTER TABLE `online_sessions` DISABLE KEYS */;
INSERT INTO `online_sessions` VALUES (88,4,'1025',552464,'2021-07-08 21:50:01','[]',NULL,1625773831,1,'41944a783bad41d95836be8297bb74f2'),(89,4,'1025',166320,'2021-07-08 21:55:35','[]',NULL,1625774166,1,'41944a783bad41d95836be8297bb74f2'),(90,4,'1025',434580,'2021-07-08 21:56:34','[]',NULL,1625774225,1,'41944a783bad41d95836be8297bb74f2'),(91,4,'1025',266500,'2021-07-08 22:01:09','[]',NULL,1625774499,1,'41944a783bad41d95836be8297bb74f2'),(92,4,'1025',159569,'2021-07-08 22:23:17','[]',NULL,1625775827,1,'41944a783bad41d95836be8297bb74f2'),(93,4,'1025',310193,'2021-07-08 22:52:47','[]',NULL,1625777598,1,'41944a783bad41d95836be8297bb74f2'),(94,4,'1025',867330,'2021-07-08 22:54:32','[]',NULL,1625777695,1,'4ae09ec714bb7efc4de7b55d900abd07'),(95,4,'1025',910324,'2021-07-08 22:55:03','[]',NULL,1625777734,0,'41384a70b220eaee5e8c134d051325b5'),(96,4,'1025',838903,'2021-07-10 22:43:17','[]',NULL,1625949827,1,'60ae2ab67489dc292bd007103b05e0ef'),(97,4,'1025',409469,'2021-07-10 22:45:11','[]',NULL,1625949942,1,'d954eab14453114ef413e592d6708496'),(98,4,'1025',506100,'2021-07-10 22:46:08','[]',NULL,1625949998,1,'518fd90d89b600bbcd9768787c803a67'),(99,4,'1025',367059,'2021-07-11 00:03:29','[]',NULL,1625954639,1,'6398d0a102f1471a59e35f5fd463890f'),(100,4,'1025',432215,'2021-07-11 00:04:23','[]',NULL,1625954693,1,'ddf3c48bdb2eec927e71d96c6ac0d94a'),(101,4,'1025',259789,'2021-07-11 00:04:47','[]',NULL,1625954717,1,'3ffb9cd4750c2382961e6e7c87b31d23'),(102,4,'1025',565411,'2021-07-11 00:05:13','[]',NULL,1625954743,1,'ecc2302dbdee44d7abaf9b627657fb13'),(103,4,'1025',454665,'2021-07-11 00:05:39','[]',NULL,1625954769,1,'374491005856cedac862170fb2d80fea'),(104,4,'1025',578667,'2021-07-11 00:06:41','[]',NULL,1625954832,1,'f68e364c0eecaf643f5b7dd7418ec6c2'),(105,4,'1025',827549,'2021-07-11 00:12:32','[]',NULL,1625955282,1,'8826807d004bb49373c5f25620536049'),(106,4,'1025',735750,'2021-07-11 03:13:32','[]',NULL,1625966143,1,'2aff56afa798dbdfa5f836a59a137c0c'),(107,4,'1025',384343,'2021-07-11 14:01:49','[]',NULL,1626005040,1,'b0002ad3c8f09733ec4f58d8cd016bb3'),(108,4,'1025',838759,'2021-07-12 00:40:53','[]',NULL,1626043383,1,'7b77755cfa717fac0970530603385373'),(109,4,'1025',980428,'2021-07-12 00:47:01','[]',NULL,1626043752,1,'79ae5bc168896bfaf4453730276bdbe0'),(110,4,'1025',817982,'2021-07-12 00:48:20','[]',NULL,1626043831,1,'53fb8f64f67d4e7f462348f2e2da4ef1'),(111,4,'1025',649847,'2021-07-12 00:50:46','[[0], [4], []]',NULL,1626043977,1,'9451fc5557f26c76696c90e352664af6'),(112,4,'1025',847309,'2021-07-12 01:04:40','[[0], [3], []]',NULL,1626044810,1,'9842fc63ebbaf95fc5077f2388eed034'),(113,4,'1025',783577,'2021-07-12 01:13:49','[[0], [2], []]',NULL,1626045359,1,'6b56d75410398e6445c2a2443419d8b6'),(114,4,'1025',225830,'2021-07-12 01:19:55','[[1, 0], [2, 4], []]',NULL,1626045725,1,'38b8c889b70fd939671562321f57ecfc'),(115,4,'1025',366210,'2021-07-12 01:25:12','[[0, 1], [3, 1], []]',NULL,1626046042,1,'f0131e937d05d2edff49496579c1c534'),(116,4,'1025',908218,'2021-07-12 01:27:11','[[0, 1], [0, 4], []]',NULL,1626046162,1,'43b72aabc82331da91c367c54001320d'),(117,4,'1025',992234,'2021-07-12 01:33:37','[[0, 1], [2, 1], []]',NULL,1626046548,1,'1abb3667548d4c5a7d14e2e9865b40bf'),(118,4,'1025',988716,'2021-07-12 01:38:29','[[0, 1], [3, 1], []]',NULL,1626046840,1,'e11e15c6638cbabeda7413b1bc15a09a'),(119,4,'1025',595998,'2021-07-12 11:28:40','[[0], [3], []]',NULL,1626082251,1,'bc1b3e5b96e82ff15edab7912b65755c'),(120,4,'1025',278158,'2021-07-12 15:48:02','[[], [2], []]',NULL,1626097812,1,'861dbcfc4bd9d018c57c79e2a9e1e319'),(121,4,'1025',332016,'2021-07-12 15:50:33','[[], [], []]',NULL,1626097963,1,'d686015504cb6f4a8f04681ad45c1afd'),(122,4,'1025',735193,'2021-07-13 03:21:17','[]',NULL,1626139407,1,'15d09da20c57414a7b8177ac01fb6106'),(124,4,'1025',374219,'2021-07-15 22:56:00','[[1, 0], [3, 0], [1, 0], []]','[{\"time\": 30, \"answers\": [\"Да\", \"Нет\", \"Ответ 3\"], \"question\": \"Тестовый вопрос\"}, {\"time\": 100, \"answers\": [\"Да\", \"Нет\", \"Ответ 3\", \"Ответ 4\", \"Ответ 5\", \"Ответ 6\"], \"question\": \"Тестовый вопрос 2\"}, {\"time\": 30, \"answers\": [\"Ответ 1\", \"Ответ 440\"], \"question\": \"Вопрос номер 3\"}]',1626382720,1,'977cfebce7b9540ca4ac6ccc4692807d'),(125,4,'1025',858382,'2021-07-16 01:23:41','[]','[{\"time\": 30, \"answers\": [\"Да\", \"Нет\", \"Ответ 3\"], \"question\": \"Тестовый вопрос\"}, {\"time\": 100, \"answers\": [\"Да\", \"Нет\", \"Ответ 3\", \"Ответ 4\", \"Ответ 5\", \"Ответ 6\"], \"question\": \"Тестовый вопрос 2\"}, {\"time\": 30, \"answers\": [\"Ответ 1\", \"Ответ 440\"], \"question\": \"Вопрос номер 3\"}]',1626391581,1,'0de9b0dc233f2f2316b561d6043b94fb'),(126,7,'1025',448837,'2021-07-16 16:50:48','[]','[]',1626447049,0,'f45549583bf22b2d1ed769b9fdb2d2a4'),(127,4,'1025',831778,'2021-07-16 16:52:42','[]','[{\"time\": 30, \"answers\": [\"Да\", \"Нет\", \"Ответ 3\"], \"question\": \"Тестовый вопрос\"}, {\"time\": 100, \"answers\": [\"Да\", \"Нет\", \"Ответ 3\", \"Ответ 4\", \"Ответ 5\", \"Ответ 6\"], \"question\": \"Тестовый вопрос 2\"}, {\"time\": 30, \"answers\": [\"Ответ 1\", \"Ответ 440\"], \"question\": \"Вопрос номер 3\"}]',1626447322,0,'875c2bfb83ff3286a678874f28cf72b2'),(128,7,'1025',358648,'2021-07-16 17:11:40','[]','[{\"time\": 30, \"answers\": [\"Ответ 1\", \"Ответ 2\"], \"question\": \"Вопрос номер 1\"}]',1626448331,0,'2ee1b8667dd7ce5ff488396a17f84b47'),(131,4,'1025',905577,'2021-07-17 11:51:00','[[1], [4], [0], []]','[{\"time\": 30, \"answers\": [\"Да\", \"Нет\", \"Ответ 3\"], \"question\": \"Тестовый вопрос\"}, {\"time\": 100, \"answers\": [\"Да\", \"Нет\", \"Ответ 3\", \"Ответ 4\", \"Ответ 5\", \"Ответ 6\"], \"question\": \"Тестовый вопрос 2\"}, {\"time\": 30, \"answers\": [\"Ответ 1\", \"Ответ 440\"], \"question\": \"Вопрос номер 3\"}]',1626515620,0,'49eba2dfdb5d4fff6f949c76ebab1a42');
/*!40000 ALTER TABLE `online_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quizzes`
--

DROP TABLE IF EXISTS `quizzes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quizzes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `owner_id` int NOT NULL,
  `name` varchar(250) NOT NULL,
  `description` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `lite_mode` tinyint(1) DEFAULT '0',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `questions` json NOT NULL COMMENT 'Always must to contain:\n-> string question\n-> array answers\n-> int time in seconds for question\n\nExample: [{"question": "Test", "answers": ["Yes", "No"], "time": "30"}]',
  `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 - отключен, 1 - работает',
  `type` int NOT NULL DEFAULT '0' COMMENT '0 - онлайн, 1 - оффлайн',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quizzes`
--

LOCK TABLES `quizzes` WRITE;
/*!40000 ALTER TABLE `quizzes` DISABLE KEYS */;
INSERT INTO `quizzes` VALUES (4,1,'Тестовый опрос','Опрос для тестового подключения',1,'2021-06-18 11:47:43','[{\"time\": 30, \"answers\": [\"Да\", \"Нет\", \"Ответ 3\"], \"question\": \"Тестовый вопрос\"}, {\"time\": 100, \"answers\": [\"Да\", \"Нет\", \"Ответ 3\", \"Ответ 4\", \"Ответ 5\", \"Ответ 6\"], \"question\": \"Тестовый вопрос 2\"}, {\"time\": 30, \"answers\": [\"Ответ 1\", \"Ответ 440\"], \"question\": \"Вопрос номер 3\"}]',1,0),(7,1,'Новый опрос','123',0,'2021-07-16 01:59:33','[]',1,0);
/*!40000 ALTER TABLE `quizzes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `display_name` text NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES (1,'site_name_option','Название сайта','Quiz'),(2,'captcha_public_option','Публичный ключ ReCaptcha','6LeqU3gbAAAAAF2otl0rTgut2cA4RIRdUjYvYgYL'),(3,'captcha_private_option','Секретный ключ ReCaptcha','6LeqU3gbAAAAAHUmpScey2KOenEGorYXMXT5k432');
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscriptions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `max_quizzes` int NOT NULL,
  `max_questions` int NOT NULL,
  `max_answers` int NOT NULL,
  `max_clients` int NOT NULL,
  `special_features` tinyint(1) NOT NULL COMMENT 'Сравнение тестов, сохранение результатов и т.д.',
  `price` int NOT NULL,
  `period` int NOT NULL COMMENT '0 - безлимит\r\n1 - месяц\r\n2 - год',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriptions`
--

LOCK TABLES `subscriptions` WRITE;
/*!40000 ALTER TABLE `subscriptions` DISABLE KEYS */;
INSERT INTO `subscriptions` VALUES (0,'Без подписки',2,10,4,100,0,0,0),(1,'PRO подписка',100,30,8,-1,1,700,1),(2,'PRO подписка',100,30,8,-1,1,6500,2),(3,'Премиум',-1,256,8,-1,1,35000,2);
/*!40000 ALTER TABLE `subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(32) NOT NULL,
  `passwd` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(64) NOT NULL,
  `auth_token` varchar(32) NOT NULL,
  `activation` varchar(32) NOT NULL,
  `recovery_token` varchar(32) NOT NULL,
  `subscription_type` int NOT NULL DEFAULT '0',
  `subscription_end` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '0',
  `admin` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `auth_token` (`auth_token`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'test@mail.ru','c4ca4238a0b923820dcc509a6f75849b','Иванов Иван','a1f239f686fe226f9be91ea551c56b9f','','',1,0,0,0);
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

-- Dump completed on 2021-07-17 21:11:48
