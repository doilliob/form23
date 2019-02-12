-- MySQL dump 10.13  Distrib 5.5.43, for Linux (i686)
--
-- Host: localhost    Database: ucheb14-15
-- ------------------------------------------------------
-- Server version	5.5.43

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
-- Temporary table structure for view `grouplist`
--

DROP TABLE IF EXISTS `grouplist`;
/*!50001 DROP VIEW IF EXISTS `grouplist`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `grouplist` (
  `name` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `hours`
--

DROP TABLE IF EXISTS `hours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hours` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `view` enum('Теория','Практика') NOT NULL DEFAULT 'Теория',
  `groupnum` text NOT NULL,
  `brignum` enum('Вся группа','1','2','3','4','5','6') NOT NULL DEFAULT 'Вся группа',
  `teacher` text NOT NULL,
  `lesson` text NOT NULL,
  `day` date NOT NULL,
  `hours` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=161470 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `lessons`
--

DROP TABLE IF EXISTS `lessons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lessons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `groupnum` text NOT NULL,
  `course` int(11) NOT NULL,
  `polugodie` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=928 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lessons`
--

--
-- Temporary table structure for view `lessonslist`
--

DROP TABLE IF EXISTS `lessonslist`;
/*!50001 DROP VIEW IF EXISTS `lessonslist`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `lessonslist` (
  `name` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `number` text NOT NULL,
  `spec` text NOT NULL,
  `spec_id` text NOT NULL,
  `course` int(11) NOT NULL,
  `budg` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--


--
-- Table structure for table `substitutions`
--

DROP TABLE IF EXISTS `substitutions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `substitutions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `view` enum('Теория','Практика') NOT NULL DEFAULT 'Теория',
  `groupnum` text NOT NULL,
  `brignum` enum('Вся группа','1','2','3','4','5','6') NOT NULL DEFAULT 'Вся группа',
  `teacher` text NOT NULL,
  `subster` text NOT NULL,
  `lesson` text NOT NULL,
  `day` date NOT NULL,
  `hours` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1814 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `substitutions`
--


--
-- Table structure for table `tarif`
--

DROP TABLE IF EXISTS `tarif`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tarif` (
  `fio` varchar(255) NOT NULL,
  `ekz_budg` int(10) unsigned DEFAULT '0' COMMENT 'Экзамен бюджет',
  `ekz_nbudg` int(10) unsigned DEFAULT '0' COMMENT 'Экзамен внебюджет',
  `tarif_budg` int(10) unsigned DEFAULT '0' COMMENT 'Тарификация бюджет',
  `tarif_nbudg` int(10) unsigned DEFAULT '0' COMMENT 'Тарификация внебюджет',
  `pred_budg` int(10) unsigned DEFAULT '0' COMMENT 'Изменение преднагрузки бюджет',
  `pred_nbudg` int(10) unsigned DEFAULT '0' COMMENT 'Изменение преднагрузки внебюджет',
  `perc_budg` int(10) unsigned DEFAULT '0' COMMENT '5% праздничных бюджет',
  `perc_nbudg` int(10) unsigned DEFAULT '0' COMMENT '5%праздничных внебюджет',
  `not_budg` int(10) unsigned DEFAULT '0' COMMENT 'Не выполнено бюджет',
  `not_nbudg` int(10) unsigned DEFAULT '0' COMMENT 'Не выполнено внебюджет'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teachers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fio` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=172 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

