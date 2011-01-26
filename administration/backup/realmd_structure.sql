/*Table structure for table `configs` */

DROP TABLE IF EXISTS `configs`;

CREATE TABLE `configs` (
  `name` varchar(600) NOT NULL,
  `content` longtext,
  `type` enum('string','integer','boolean','array','float','double') NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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

/*Table structure for table `log_payments_paypal` */

DROP TABLE IF EXISTS `log_payments_paypal`;

CREATE TABLE `log_payments_paypal` (
  `status` tinyint(1) DEFAULT NULL COMMENT '0=success, 1=failure, 2=pending, 3=reversed',
  `transaction_id` varchar(255) DEFAULT NULL,
  `real_transaction_id` varchar(255) DEFAULT NULL,
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='SUM(amount WHERE(status=0)) - SUM(amount WHERE(status=3))';

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

/*Table structure for table `log_votes` */

DROP TABLE IF EXISTS `log_votes`;

CREATE TABLE `log_votes` (
  `gateway` int(11) NOT NULL,
  `ip` varchar(16) DEFAULT NULL,
  `accountid` int(15) DEFAULT NULL,
  `time` int(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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

/*Table structure for table `online` */

DROP TABLE IF EXISTS `online`;

CREATE TABLE `online` (
  `uid` int(11) unsigned NOT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `lastvisit` bigint(40) NOT NULL,
  `online` tinyint(1) NOT NULL,
  UNIQUE KEY `id` (`uid`,`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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

/*Table structure for table `vote_gateways` */

DROP TABLE IF EXISTS `vote_gateways`;

CREATE TABLE `vote_gateways` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL,
  `image` varchar(128) DEFAULT NULL,
  `url` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;