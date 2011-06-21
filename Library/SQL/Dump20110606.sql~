-- MySQL dump 10.13  Distrib 5.5.9, for Win32 (x86)
--
-- Host: localhost    Database: ozdb
-- ------------------------------------------------------
-- Server version	5.1.44b-MariaDB-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

Use ozdb;
--
-- Table structure for table `oz_account_mods`
--

DROP TABLE IF EXISTS `oz_account_mods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oz_account_mods` (
  `oz_modid` int(11) NOT NULL,
  `oz_clientid` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_time_mod` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_admin_mod` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_mod` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`oz_modid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oz_account_mods`
--

LOCK TABLES `oz_account_mods` WRITE;
/*!40000 ALTER TABLE `oz_account_mods` DISABLE KEYS */;
/*!40000 ALTER TABLE `oz_account_mods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oz_accounts`
--

DROP TABLE IF EXISTS `oz_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oz_accounts` (
  `ozid` int(11) NOT NULL AUTO_INCREMENT,
  `oz_realname` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_uid` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_pwd` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_ircnick` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_emailvalid` int(11) DEFAULT NULL,
  `oz_time_creation` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_status` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_packageid` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_admin_shellactivated` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_time_shelactivated` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_custom_time_trialcompleted` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_admin_locked` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_time_locked` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_lockedthreshold` int(1) DEFAULT NULL,
  `oz_lockedreason` text COLLATE utf8_unicode_ci,
  `oz_custom_vouched` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_shellreason` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ozid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oz_accounts`
--

LOCK TABLES `oz_accounts` WRITE;
/*!40000 ALTER TABLE `oz_accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `oz_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oz_accounts_admin`
--

DROP TABLE IF EXISTS `oz_accounts_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oz_accounts_admin` (
  `ozid` int(11) NOT NULL,
  `oz_realname` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_uid` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_pwd` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_time_creation` int(11) DEFAULT NULL,
  `oz_status` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_level` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ozid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oz_accounts_admin`
--

LOCK TABLES `oz_accounts_admin` WRITE;
/*!40000 ALTER TABLE `oz_accounts_admin` DISABLE KEYS */;
INSERT INTO `oz_accounts_admin` VALUES (0,'Root Account','root','63a9f0ea7bb98050796b649e85481845','root@example.com',1307394747,'1','0');
/*!40000 ALTER TABLE `oz_accounts_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oz_login_times`
--

DROP TABLE IF EXISTS `oz_login_counts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oz_login_counts` (
  `ozid` int(11) NOT NULL,
  `oz_uid` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_time_login` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_bool_login` int(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ozid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oz_login_times`
--

LOCK TABLES `oz_login_counts` WRITE;
/*!40000 ALTER TABLE `oz_login_counts` DISABLE KEYS */;
/*!40000 ALTER TABLE `oz_login_counts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-06-06 17:32:40
