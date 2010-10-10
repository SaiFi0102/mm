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
/*Table structure for table `configs` */

DROP TABLE IF EXISTS `configs`;

CREATE TABLE `configs` (
  `name` varchar(600) NOT NULL,
  `content` longtext,
  `type` enum('string','integer','boolean','array','float','double') NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `configs` */

insert  into `configs`(`name`,`content`,`type`) values ('websitename','Test Site','string'),('userminlen','5','integer'),('usermaxlen','22','integer'),('captchapubkey','6LeXNroSAAAAAN8J5lJW4oKOdo96uTJzcyz9EK5Y','string'),('captchaprivkey','6LeXNroSAAAAAPmJG4iDmaZbHLqSOTVp5-Ro6SYQ','string'),('donationemail','saif.rehman123@live.com','string'),('websiteurl','http://115.186.113.218','string'),('metakeyw','Keywords,Keywords','string'),('metadesc','This is my server\'s description for google and other shitty search enjunz','string'),('metaextra','<!-- Google Code or Javascript or anything here -->','string');

/*Table structure for table `log_donatereward_delivery` */

DROP TABLE IF EXISTS `log_donatereward_delivery`;

CREATE TABLE `log_donatereward_delivery` (
  `session` int(50) NOT NULL,
  `command` longtext,
  `message` longtext,
  `characterid` int(15) DEFAULT NULL,
  `realmid` int(5) DEFAULT NULL,
  `rewardid` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sent` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `log_donatereward_delivery` */

/*Table structure for table `log_invalidpayments_paypal` */

DROP TABLE IF EXISTS `log_invalidpayments_paypal`;

CREATE TABLE `log_invalidpayments_paypal` (
  `status` tinyint(1) DEFAULT NULL COMMENT '0=success, 1=failure, 2=pending, 3=reversed',
  `transaction_id` varchar(255) DEFAULT NULL,
  `real_transaction_id` varchar(255) DEFAULT NULL,
  `sender_email` varchar(255) DEFAULT NULL,
  `payment_status` varchar(255) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `account_id` int(50) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `details` text,
  `extra_information` longtext,
  `post_data` longtext,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `log_invalidpayments_paypal` */

/*Table structure for table `log_payments_paypal` */

DROP TABLE IF EXISTS `log_payments_paypal`;

CREATE TABLE `log_payments_paypal` (
  `status` tinyint(1) DEFAULT NULL COMMENT '0=success, 1=failure, 2=pending, 3=reversed',
  `transaction_id` varchar(255) DEFAULT NULL,
  `real_transaction_id` varchar(255) DEFAULT NULL,
  `sender_email` varchar(255) DEFAULT NULL,
  `payment_status` varchar(255) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `account_id` int(50) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `details` text,
  `extra_information` longtext,
  `post_data` longtext,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='SUM(amount WHERE(status=0)) - SUM(amount WHERE(status=3))';

/*Data for the table `log_payments_paypal` */

/*Table structure for table `log_votereward_delivery` */

DROP TABLE IF EXISTS `log_votereward_delivery`;

CREATE TABLE `log_votereward_delivery` (
  `session` int(50) NOT NULL,
  `command` longtext,
  `message` longtext,
  `characterid` int(15) DEFAULT NULL,
  `realmid` int(5) DEFAULT NULL,
  `rewardid` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sent` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `log_votereward_delivery` */

/*Table structure for table `log_votes` */

DROP TABLE IF EXISTS `log_votes`;

CREATE TABLE `log_votes` (
  `gateway` int(11) NOT NULL,
  `ip` varchar(16) DEFAULT NULL,
  `accountid` int(15) DEFAULT NULL,
  `time` int(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `log_votes` */

/*Table structure for table `news` */

DROP TABLE IF EXISTS `news`;

CREATE TABLE `news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` text,
  `body` longtext,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `by` varchar(50) DEFAULT 'Unknown',
  `sticky` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `news` */

/*Table structure for table `news_comments` */

DROP TABLE IF EXISTS `news_comments`;

CREATE TABLE `news_comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `newsid` int(11) NOT NULL,
  `title` text,
  `body` longtext,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `by` varchar(50) DEFAULT 'Unknown',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `news_comments` */

/*Table structure for table `online` */

DROP TABLE IF EXISTS `online`;

CREATE TABLE `online` (
  `uid` int(11) unsigned NOT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `lastvisit` bigint(40) NOT NULL,
  `online` tinyint(1) NOT NULL,
  UNIQUE KEY `id` (`uid`,`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `online` */

/*Table structure for table `rewards_donation` */

DROP TABLE IF EXISTS `rewards_donation`;

CREATE TABLE `rewards_donation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `realm` int(5) unsigned NOT NULL DEFAULT '1',
  `description` longtext,
  `items` longtext NOT NULL,
  `gold` int(11) unsigned NOT NULL DEFAULT '0',
  `points` int(11) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `rewards_donation` */

/*Table structure for table `rewards_voting` */

DROP TABLE IF EXISTS `rewards_voting`;

CREATE TABLE `rewards_voting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `realm` int(5) unsigned NOT NULL DEFAULT '1',
  `description` longtext,
  `items` longtext NOT NULL,
  `gold` int(11) unsigned NOT NULL DEFAULT '0',
  `points` int(11) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `rewards_voting` */

/*Table structure for table `vote_gateways` */

DROP TABLE IF EXISTS `vote_gateways`;

CREATE TABLE `vote_gateways` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL,
  `image` varchar(128) DEFAULT NULL,
  `url` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `vote_gateways` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
