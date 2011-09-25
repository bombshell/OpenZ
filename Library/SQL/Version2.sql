
-- USE `%oz_database_name%`;
-- MySQL dump 10.13  Distrib 5.1.44b-MariaDB, for suse-linux-gnu (x86_64)
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

--
-- Table structure for table `oz_email_data`
--

DROP TABLE IF EXISTS `oz_email_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oz_email_data` (
  `ozid` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `UseEmailFromType` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Subject` text COLLATE utf8_unicode_ci,
  `Heading` text COLLATE utf8_unicode_ci,
  `Footer` text COLLATE utf8_unicode_ci,
  `Flags` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ozid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oz_email_data`
--

LOCK TABLES `oz_email_data` WRITE;
/*!40000 ALTER TABLE `oz_email_data` DISABLE KEYS */;
INSERT INTO `oz_email_data` VALUES (1,'new_admin_pass','Admin','Your new OpenZ Administrative Credentials',NULL,NULL,'u'),(2,'new_client_pass',NULL,'Your new shell password',NULL,NULL,'up'),(3,'validate_email',NULL,'Hello, New Client','Please follow the link to validate your E-Mail','','');
/*!40000 ALTER TABLE `oz_email_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oz_account_mods`
--

DROP TABLE IF EXISTS `oz_account_mods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oz_account_mods` (
  `oz_modid` int(11) NOT NULL AUTO_INCREMENT,
  `oz_clientid` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_time_mod` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_mod_user` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_mod` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`oz_modid`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `oz_account_notes`
--

DROP TABLE IF EXISTS `oz_account_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oz_account_notes` (
  `noteid` int(11) NOT NULL AUTO_INCREMENT,
  `note_time` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note_read` varchar(45) COLLATE utf8_unicode_ci DEFAULT '0',
  `note_sender` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note_to` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note_body` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`noteid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `oz_account_clients`
--

DROP TABLE IF EXISTS `oz_account_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oz_account_clients` (
  `ozid` int(11) NOT NULL AUTO_INCREMENT,
  `oz_realname` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_uid` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_system_uid` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_pwd` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_ircnick` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_emailvalid` int(11) DEFAULT NULL,
  `oz_time_creation` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_status` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_packageid` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_admin_shellactivated` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_time_shellactivated` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_admin_locked` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_time_locked` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_lockedthreshold` int(1) DEFAULT NULL,
  `oz_lockedreason` text COLLATE utf8_unicode_ci,
  `oz_custom_admin_vouched` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_custom_time_vouched` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_shellreason` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ozid`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `oz_account_admins`
--

DROP TABLE IF EXISTS `oz_account_admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oz_account_admins` (
  `ozid` int(11) NOT NULL AUTO_INCREMENT,
  `oz_realname` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_uid` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_pwd` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_pwd_requires_reset` int(11) DEFAULT NULL,
  `oz_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_time_creation` int(11) DEFAULT NULL,
  `oz_status` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_level` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_admin_locked` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_time_locked` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_lockedreason` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_lockedthreshold` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ozid`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oz_account_admins`
--

LOCK TABLES `oz_account_admins` WRITE;
/*!40000 ALTER TABLE `oz_account_admins` DISABLE KEYS */;
INSERT INTO `oz_account_admins` VALUES (1,'Root Account','root','63a9f0ea7bb98050796b649e85481845',NULL,'root@example.com',1307394747,'1','0',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `oz_account_admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oz_login_counts`
--

DROP TABLE IF EXISTS `oz_login_counts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oz_login_counts` (
  `ozid` int(11) NOT NULL,
  `oz_uid` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_time_login` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_bool_login` int(2) DEFAULT NULL,
  PRIMARY KEY (`ozid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oz_login_counts`
--

LOCK TABLES `oz_login_counts` WRITE;
/*!40000 ALTER TABLE `oz_login_counts` DISABLE KEYS */;
/*!40000 ALTER TABLE `oz_login_counts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oz_modules_info`
--

DROP TABLE IF EXISTS `oz_modules_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oz_modules_info` (
  `ozid` int(11) NOT NULL,
  `oz_modname` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_modver` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_moddisc` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oz_modfile` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ozid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oz_modules_info`
--

LOCK TABLES `oz_modules_info` WRITE;
/*!40000 ALTER TABLE `oz_modules_info` DISABLE KEYS */;
INSERT INTO `oz_modules_info` VALUES (0,'Account Management','v0.1','Administer Accounts','userman-1.0/mod.php'),(1,'OpenZ Web Interface','v0.1','Web Interface','OpenZWeb-1.0/mod.php');
/*!40000 ALTER TABLE `oz_modules_info` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-07-12 23:46:37
