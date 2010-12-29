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
/*Table structure for table `log_invalidpayments_alertpay` */

DROP TABLE IF EXISTS `log_invalidpayments_alertpay`;

CREATE TABLE `log_invalidpayments_alertpay` (
  `status` tinyint(1) DEFAULT NULL COMMENT '0=success, 1=failure, 2=pending, 3=reversed',
  `transaction_id` varchar(255) DEFAULT NULL,
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

/*Table structure for table `log_invalidpayments_moneybookers` */

DROP TABLE IF EXISTS `log_invalidpayments_moneybookers`;

CREATE TABLE `log_invalidpayments_moneybookers` (
  `status` tinyint(1) DEFAULT NULL COMMENT '0=success, 1=failure, 2=pending, 3=reversed',
  `transaction_id` varchar(255) DEFAULT NULL,
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

/*Table structure for table `log_payments_alertpay` */

DROP TABLE IF EXISTS `log_payments_alertpay`;

CREATE TABLE `log_payments_alertpay` (
  `status` tinyint(1) DEFAULT NULL COMMENT '0=success, 1=failure, 2=pending, 3=reversed',
  `transaction_id` varchar(255) DEFAULT NULL,
  `sender_email` varchar(255) DEFAULT NULL,
  `payment_status` varchar(255) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `amount_gross` double DEFAULT NULL,
  `amount_net` double DEFAULT NULL,
  `amount_fee` double DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `account_id` int(50) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `details` text,
  `extra_information` longtext,
  `post_data` longtext,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `log_payments_moneybookers` */

DROP TABLE IF EXISTS `log_payments_moneybookers`;

CREATE TABLE `log_payments_moneybookers` (
  `status` tinyint(1) DEFAULT NULL COMMENT '0=success, 1=failure, 2=pending, 3=reversed',
  `transaction_id` varchar(255) DEFAULT NULL,
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
