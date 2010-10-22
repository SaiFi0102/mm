/*
SQLyog Community Edition- MySQL GUI v8.13 
MySQL - 5.1.41 : Database - website
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `mmopro_slidingarea` */

DROP TABLE IF EXISTS `mmopro_slidingarea`;

CREATE TABLE `mmopro_slidingarea` (
  `order` smallint(3) unsigned NOT NULL,
  `text_left` longtext,
  `text_right` longtext,
  PRIMARY KEY (`order`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
