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

/*Data for the table `mmopro_slidingarea` */

insert  into `mmopro_slidingarea`(`order`,`text_left`,`text_right`) values (1,'<div class=\"sliding_text_text\">\r\n	<h1>Welcome</h1>\r\n	<h2>to MMOProWoW</h2>\r\n	We have Multiple Realms.<br />\r\n	<b>Realm One:</b> Instant 80 Funserver<br />\r\n	<b>Realm Two:</b> Blizzlike Highrate 15x xp 30x quest<br />\r\n	to play on our realms , <b>set realmlist logon.mmoprowow.com</b>\r\n</div>','<div class=\"featured_file\">\r\n	<img src=\"images/mmopro/slide_1.png\" alt=\"\" class=\"png\" height=\"194\" width=\"464\" />\r\n</div>');
insert  into `mmopro_slidingarea`(`order`,`text_left`,`text_right`) values (2,'<div class=\"sliding_text_text\">\r\n	<h1>Warground Funserver</h1>\r\n	<h2>Custom Contents</h2>\r\n	Warground Funserver is now fully customised realm with working battlegrounds, custom instances, custom events &amp; Much more. If you want more new voting rewards, keep voting. Donor and Non-Donor Limited Edition items are now be available on weekends.\r\n</div>','<div class=\"featured_file\">\r\n	<img src=\"images/mmopro/slide_2.png\" alt=\"\" height=\"194\" width=\"464\" class=\"png\" />\r\n</div>');
insert  into `mmopro_slidingarea`(`order`,`text_left`,`text_right`) values (3,'<div class=\"sliding_text_text\">\r\n	<h1>MMOPro.net\'s</h1> \r\n	<h2>Official Server</h2>\r\n	MMOPro WoW is an Official Private server of MMOPro.net! So be active on both communities.\r\n</div>','<div class=\"featured_file\">\r\n	<img src=\"images/mmopro/slide_4.png\" alt=\"\" height=\"194\" width=\"464\" class=\"png\" />\r\n</div>');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
