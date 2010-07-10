SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `configs`
-- ----------------------------
DROP TABLE IF EXISTS `configs`;
CREATE TABLE `configs` (
  `name` varchar(600) NOT NULL,
  `content` longtext,
  `type` enum('string','integer','boolean','array','float','double') NOT NULL,
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of configs
-- ----------------------------
INSERT INTO `configs` VALUES ('websitename', 'Test Site', 'string');
INSERT INTO `configs` VALUES ('userminlen', '5', 'integer');
INSERT INTO `configs` VALUES ('usermaxlen', '22', 'integer');
INSERT INTO `configs` VALUES ('captchapubkey', '6LeXNroSAAAAAN8J5lJW4oKOdo96uTJzcyz9EK5Y', 'string');
INSERT INTO `configs` VALUES ('captchaprivkey', '6LeXNroSAAAAAPmJG4iDmaZbHLqSOTVp5-Ro6SYQ', 'string');
INSERT INTO `configs` VALUES ('donationemail', 'saif.rehman123@live.com', 'string');
INSERT INTO `configs` VALUES ('websiteurl', 'http://115.186.113.218', 'string');

-- ----------------------------
-- Table structure for `log_donatereward_delivery`
-- ----------------------------
DROP TABLE IF EXISTS `log_donatereward_delivery`;
CREATE TABLE `log_donatereward_delivery` (
  `session` int(50) NOT NULL,
  `command` longtext,
  `message` longtext,
  `characterid` int(15) default NULL,
  `realmid` int(5) default NULL,
  `rewardid` int(11) default NULL,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `sent` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of log_donatereward_delivery
-- ----------------------------
INSERT INTO `log_donatereward_delivery` VALUES ('1', '.send money Msoul &quot;Thank you!&quot; &quot;Dear Msoul,\r\n\r\nThank you for supporting our server. We hope you enjoy your play!\r\n\r\nRegards,\r\nCataclysmic Domination Staff&quot;345', 'Mail sent to Msoul\r\n', '28', '1', '3', '2010-06-25 14:53:08', '1');

-- ----------------------------
-- Table structure for `log_invalidpayments_paypal`
-- ----------------------------
DROP TABLE IF EXISTS `log_invalidpayments_paypal`;
CREATE TABLE `log_invalidpayments_paypal` (
  `status` tinyint(1) default NULL COMMENT '0=success, 1=failure, 2=pending, 3=reversed',
  `transaction_id` varchar(255) default NULL,
  `real_transaction_id` varchar(255) default NULL,
  `sender_email` varchar(255) default NULL,
  `payment_status` varchar(255) default NULL,
  `item_name` varchar(255) default NULL,
  `amount` double default NULL,
  `currency` varchar(255) default NULL,
  `account_id` int(50) default NULL,
  `first_name` varchar(255) default NULL,
  `last_name` varchar(255) default NULL,
  `details` text,
  `extra_information` longtext,
  `post_data` longtext,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of log_invalidpayments_paypal
-- ----------------------------

-- ----------------------------
-- Table structure for `log_payments_paypal`
-- ----------------------------
DROP TABLE IF EXISTS `log_payments_paypal`;
CREATE TABLE `log_payments_paypal` (
  `status` tinyint(1) default NULL COMMENT '0=success, 1=failure, 2=pending, 3=reversed',
  `transaction_id` varchar(255) default NULL,
  `real_transaction_id` varchar(255) default NULL,
  `sender_email` varchar(255) default NULL,
  `payment_status` varchar(255) default NULL,
  `item_name` varchar(255) default NULL,
  `amount` double default NULL,
  `currency` varchar(255) default NULL,
  `account_id` int(50) default NULL,
  `first_name` varchar(255) default NULL,
  `last_name` varchar(255) default NULL,
  `details` text,
  `extra_information` longtext,
  `post_data` longtext,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='SUM(amount WHERE(status=0)) - SUM(amount WHERE(status=3))';

-- ----------------------------
-- Records of log_payments_paypal
-- ----------------------------
INSERT INTO `log_payments_paypal` VALUES ('0', '59L36402PH922781C', '', 'saif_1275507749_per@live.com', 'Completed', 'Donation from chmun to Test Site', '100', 'USD', '2', 'Test', 'User', 'Transaction was successful, 100 donation points were added to your account!', 'SUCCESSFUL PAYMENT!', '[mc_gross] = 100.00\r\n[protection_eligibility] = Ineligible\r\n[payer_id] = S3UDS6H5A9F46\r\n[tax] = 0.00\r\n[payment_date] = 17:25:58 Jun 05, 2010 PDT\r\n[payment_status] = Completed\r\n[charset] = windows-1252\r\n[first_name] = Test\r\n[mc_fee] = 3.20\r\n[notify_version] = 3.0\r\n[custom] = 2\r\n[payer_status] = verified\r\n[business] = saif.rehman123@live.com\r\n[quantity] = 1\r\n[verify_sign] = AnySKGKt984wTah2ymSmaQiTsZkgA4wN-jB9eJJ7CyNcwrLGzxKEkRy9\r\n[payer_email] = saif_1275507749_per@live.com\r\n[txn_id] = 59L36402PH922781C\r\n[payment_type] = instant\r\n[last_name] = User\r\n[receiver_email] = saif.rehman123@live.com\r\n[payment_fee] = 3.20\r\n[receiver_id] = C5A2HJMASVLMY\r\n[txn_type] = web_accept\r\n[item_name] = Donation from chmun to Test Site\r\n[mc_currency] = USD\r\n[item_number] = \r\n[residence_country] = US\r\n[test_ipn] = 1\r\n[handling_amount] = 0.00\r\n[transaction_subject] = 2\r\n[payment_gross] = 100.00\r\n[shipping] = 0.00\r\n', '2010-06-06 05:26:10');
INSERT INTO `log_payments_paypal` VALUES ('0', '76V658295T518094D', '', 'saif_1275507749_per@live.com', 'Completed', 'Donation from chmun to Test Site', '53', 'USD', '2', 'Test', 'User', 'Transaction was successful, 53 donation points were added to your account!', 'SUCCESSFUL PAYMENT!', '[mc_gross] = 53.00\r\n[protection_eligibility] = Ineligible\r\n[payer_id] = S3UDS6H5A9F46\r\n[tax] = 0.00\r\n[payment_date] = 17:34:12 Jun 05, 2010 PDT\r\n[payment_status] = Completed\r\n[charset] = windows-1252\r\n[first_name] = Test\r\n[mc_fee] = 1.84\r\n[notify_version] = 3.0\r\n[custom] = 2\r\n[payer_status] = verified\r\n[business] = saif.rehman123@live.com\r\n[quantity] = 1\r\n[verify_sign] = AFcWxV21C7fd0v3bYYYRCpSSRl31Ae3noizDo0te7gqLFPM9flAflVm0\r\n[payer_email] = saif_1275507749_per@live.com\r\n[txn_id] = 76V658295T518094D\r\n[payment_type] = instant\r\n[last_name] = User\r\n[receiver_email] = saif.rehman123@live.com\r\n[payment_fee] = 1.84\r\n[receiver_id] = C5A2HJMASVLMY\r\n[txn_type] = web_accept\r\n[item_name] = Donation from chmun to Test Site\r\n[mc_currency] = USD\r\n[item_number] = \r\n[residence_country] = US\r\n[test_ipn] = 1\r\n[handling_amount] = 0.00\r\n[transaction_subject] = 2\r\n[payment_gross] = 53.00\r\n[shipping] = 0.00\r\n', '2010-06-06 05:34:15');
INSERT INTO `log_payments_paypal` VALUES ('3', '59L36402PH922781C', '83GSD34457ASD2GA', 'saif_1275507749_per@live.com', 'Reversed', 'Donation from ___ to ___', '100', 'USD', '2', 'Test', 'User', 'Transaction was reversed or refunded, 100 donation points was deducted from your account!', 'REVERSED PAYMENT!', '[test_ipn] = 1\r\n[payment_type] = instant\r\n[payment_date] = 17:34:00 Jun 05, 2010 PDT\r\n[payment_status] = Reversed\r\n[address_status] = confirmed\r\n[payer_status] = verified\r\n[first_name] = Test\r\n[last_name] = User\r\n[payer_email] = saif_1275507749_per@live.com\r\n[payer_id] = TESTBUYERID01\r\n[address_name] = John Smith\r\n[address_country] = United States\r\n[address_country_code] = US\r\n[address_zip] = 95131\r\n[address_state] = CA\r\n[address_city] = San Jose\r\n[address_street] = 123, any street\r\n[business] = saif.rehman123@live.com\r\n[receiver_email] = saif.rehman123@live.com\r\n[receiver_id] = TESTSELLERID1\r\n[residence_country] = US\r\n[item_name] = Donation from ___ to ___\r\n[shipping] = 3.04\r\n[tax] = 2.02\r\n[mc_currency] = USD\r\n[mc_fee] = 0.44\r\n[mc_gross] = 100.00\r\n[txn_type] = web_accept\r\n[txn_id] = 83GSD34457ASD2GA\r\n[parent_txn_id] = 59L36402PH922781C\r\n[notify_version] = 2.1\r\n[custom] = 2\r\n[charset] = windows-1252\r\n[verify_sign] = AkjT-VZsCEvL4fELWdLmXT8MLPCRAYrM.x8fEcE41CoIpyHJkStuAuCQ\r\n', '2010-06-06 05:40:04');
INSERT INTO `log_payments_paypal` VALUES ('0', '59L36402PH922781C', '83GSD34457ASD2GA', 'saif_1275507749_per@live.com', 'Canceled_Reversal', 'Donation from ___ to ___', '100', 'USD', '2', 'Test', 'User', 'Reversal was cancelled, 100 donation points were added to your account!', 'SUCCESSFUL PAYMENT!', '[test_ipn] = 1\r\n[payment_type] = instant\r\n[payment_date] = 17:34:00 Jun 05, 2010 PDT\r\n[payment_status] = Canceled_Reversal\r\n[address_status] = confirmed\r\n[payer_status] = verified\r\n[first_name] = Test\r\n[last_name] = User\r\n[payer_email] = saif_1275507749_per@live.com\r\n[payer_id] = TESTBUYERID01\r\n[address_name] = John Smith\r\n[address_country] = United States\r\n[address_country_code] = US\r\n[address_zip] = 95131\r\n[address_state] = CA\r\n[address_city] = San Jose\r\n[address_street] = 123, any street\r\n[business] = saif.rehman123@live.com\r\n[receiver_email] = saif.rehman123@live.com\r\n[receiver_id] = TESTSELLERID1\r\n[residence_country] = US\r\n[item_name] = Donation from ___ to ___\r\n[shipping] = 3.04\r\n[tax] = 2.02\r\n[mc_currency] = USD\r\n[mc_fee] = 0.44\r\n[mc_gross] = 100.00\r\n[txn_type] = web_accept\r\n[txn_id] = 83GSD34457ASD2GA\r\n[notify_version] = 2.1\r\n[custom] = 2\r\n[charset] = windows-1252\r\n[verify_sign] = ARiKAu7w4q1rRUKAb58Q2YMtK7IRAC6dYeFjF905Jfeoq9F3qPkSfeIn\r\n', '2010-06-06 05:53:20');
INSERT INTO `log_payments_paypal` VALUES ('3', '76V658295T518094D', '457SADLKJ0345LK23', 'saif_1275507749_per@live.com', 'Refunded', 'Donation from ___ to ___', '53', 'USD', '2', 'Test', 'User', 'Transaction was reversed or refunded, 53 donation points was deducted from your account!', 'REVERSED PAYMENT!', '[test_ipn] = 1\r\n[payment_type] = instant\r\n[payment_date] = 17:34:00 Jun 05, 2010 PDT\r\n[payment_status] = Refunded\r\n[address_status] = confirmed\r\n[payer_status] = verified\r\n[first_name] = Test\r\n[last_name] = User\r\n[payer_email] = saif_1275507749_per@live.com\r\n[payer_id] = TESTBUYERID01\r\n[address_name] = John Smith\r\n[address_country] = United States\r\n[address_country_code] = US\r\n[address_zip] = 95131\r\n[address_state] = CA\r\n[address_city] = San Jose\r\n[address_street] = 123, any street\r\n[business] = saif.rehman123@live.com\r\n[receiver_email] = saif.rehman123@live.com\r\n[receiver_id] = TESTSELLERID1\r\n[residence_country] = US\r\n[item_name] = Donation from ___ to ___\r\n[shipping] = 3.04\r\n[tax] = 2.02\r\n[mc_currency] = USD\r\n[mc_fee] = 0.44\r\n[mc_gross] = 53.00\r\n[txn_type] = web_accept\r\n[txn_id] = 457SADLKJ0345LK23\r\n[parent_txn_id] = 76V658295T518094D\r\n[notify_version] = 2.1\r\n[custom] = 2\r\n[charset] = windows-1252\r\n[verify_sign] = AFcWxV21C7fd0v3bYYYRCpSSRl31AjkUKeScQj8QqyvOTRC10rcgDPWF\r\n', '2010-06-06 05:48:55');
INSERT INTO `log_payments_paypal` VALUES ('0', '457SADLKJ0345LK23', '', 'saif_1275507749_per@live.com', 'Completed', 'Donation from ___ to ___', '33', 'USD', '2', 'Test', 'User', 'Transaction was successful, 33 donation points were added to your account!', 'SUCCESSFUL PAYMENT!', '[test_ipn] = 1\r\n[payment_type] = instant\r\n[payment_date] = 17:34:00 Jun 05, 2010 PDT\r\n[payment_status] = Completed\r\n[address_status] = confirmed\r\n[payer_status] = verified\r\n[first_name] = Test\r\n[last_name] = User\r\n[payer_email] = saif_1275507749_per@live.com\r\n[payer_id] = TESTBUYERID01\r\n[address_name] = John Smith\r\n[address_country] = United States\r\n[address_country_code] = US\r\n[address_zip] = 95131\r\n[address_state] = CA\r\n[address_city] = San Jose\r\n[address_street] = 123, any street\r\n[business] = saif.rehman123@live.com\r\n[receiver_email] = saif.rehman123@live.com\r\n[receiver_id] = TESTSELLERID1\r\n[residence_country] = US\r\n[item_name] = Donation from ___ to ___\r\n[shipping] = 3.04\r\n[tax] = 2.02\r\n[mc_currency] = USD\r\n[mc_fee] = 0.44\r\n[mc_gross] = 33.00\r\n[txn_type] = web_accept\r\n[txn_id] = 457SADLKJ0345LK23\r\n[notify_version] = 2.1\r\n[custom] = 2\r\n[charset] = windows-1252\r\n[verify_sign] = AFcWxV21C7fd0v3bYYYRCpSSRl31AQ5xikZEeNgRqCXgt.Wui9I95T3Y\r\n', '2010-06-06 05:56:56');
INSERT INTO `log_payments_paypal` VALUES ('1', '457SADLKJ0345LK23', null, 'saif_1275507749_per@live.com', 'Failed', 'Donation from ___ to ___', '33', 'AUD', '2', 'Test', 'User', 'The payment currency was not in $(USD), please contact an administrator to convert the currency to $(USD)', 'WRONG CURRENCY: AUD', '[test_ipn] = 1\r\n[payment_type] = instant\r\n[payment_date] = 17:34:00 Jun 05, 2010 PDT\r\n[payment_status] = Completed\r\n[address_status] = confirmed\r\n[payer_status] = verified\r\n[first_name] = Test\r\n[last_name] = User\r\n[payer_email] = saif_1275507749_per@live.com\r\n[payer_id] = TESTBUYERID01\r\n[address_name] = John Smith\r\n[address_country] = United States\r\n[address_country_code] = US\r\n[address_zip] = 95131\r\n[address_state] = CA\r\n[address_city] = San Jose\r\n[address_street] = 123, any street\r\n[business] = saif.rehman123@live.com\r\n[receiver_email] = saif.rehman123@live.com\r\n[receiver_id] = TESTSELLERID1\r\n[residence_country] = US\r\n[item_name] = Donation from ___ to ___\r\n[shipping] = 3.04\r\n[tax] = 2.02\r\n[mc_currency] = AUD\r\n[mc_fee] = 0.44\r\n[mc_gross] = 33.00\r\n[txn_type] = web_accept\r\n[txn_id] = 457SADLKJ0345LK23\r\n[notify_version] = 2.1\r\n[custom] = 2\r\n[charset] = windows-1252\r\n[verify_sign] = AzTKkWLQKGEHnSwM9MlQyiCyQpjOAPRoV3.waqOfTN8ps4tfZh5LzUrT\r\n', '2010-06-06 06:00:29');

-- ----------------------------
-- Table structure for `log_votereward_delivery`
-- ----------------------------
DROP TABLE IF EXISTS `log_votereward_delivery`;
CREATE TABLE `log_votereward_delivery` (
  `session` int(50) NOT NULL,
  `command` longtext,
  `message` longtext,
  `characterid` int(15) default NULL,
  `realmid` int(5) default NULL,
  `rewardid` int(11) default NULL,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `sent` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of log_votereward_delivery
-- ----------------------------
INSERT INTO `log_votereward_delivery` VALUES ('1', '.send money Msoul &quot;Thank you for donating, Here is your reward!&quot; &quot;Dear Msoul,\r\n\r\nThank you for donating, our servers are run on very high speed servers which costs us alot but thanks to your support we can pay for our servers. Your donations will keep our server more stable and lagless.\r\n\r\nHere are the items of the reward!\r\nRegards, Cataclysmic Domination Staff&quot; 457562', 'Mail sent to Msoul\r\n', '28', '1', '1', '2010-06-25 14:35:09', '1');
INSERT INTO `log_votereward_delivery` VALUES ('1', '.send items Msoul &quot;Thank you!&quot; &quot;Dear Msoul,\r\n\r\nThank you for supporting our server. We hope you enjoy your play!\r\n\r\nRegards,\r\nCataclysmic Domination Staff&quot; 4645:2 34721:6 12445:1 4777:1', 'Invaid item count (2) for item 4645\r\n', '28', '1', '1', '2010-06-25 14:35:07', '0');
INSERT INTO `log_votereward_delivery` VALUES ('2', '.send items Msoul &quot;Thank you!&quot; &quot;Dear Msoul,\r\n\r\nThank you for supporting our server. We hope you enjoy your play!\r\n\r\nRegards,\r\nCataclysmic Domination Staff&quot; 19166:1', 'Mail sent to Msoul\r\n', '28', '1', '2', '2010-06-25 14:53:11', '1');
INSERT INTO `log_votereward_delivery` VALUES ('2', '.send money Msoul &quot;Thank you!&quot; &quot;Dear Msoul,\r\n\r\nThank you for supporting our server. We hope you enjoy your play!\r\n\r\nRegards,\r\nCataclysmic Domination Staff&quot;4294967295', 'Mail sent to Msoul\r\n', '28', '1', '2', '2010-06-25 14:53:15', '1');
INSERT INTO `log_votereward_delivery` VALUES ('3', '.send items Msoul &quot;Thank you!&quot; &quot;Dear Msoul,\r\n\r\nThank you for supporting our server. We hope you enjoy your play!\r\n\r\nRegards,\r\nCataclysmic Domination Staff&quot; 19166:1', 'Mail sent to Msoul\r\n', '28', '1', '2', '2010-06-28 02:04:49', '1');
INSERT INTO `log_votereward_delivery` VALUES ('3', '.send money Msoul &quot;Thank you!&quot; &quot;Dear Msoul,\r\n\r\nThank you for supporting our server. We hope you enjoy your play!\r\n\r\nRegards,\r\nCataclysmic Domination Staff&quot;4294967295', 'Mail sent to Msoul\r\n', '28', '1', '2', '2010-06-28 02:04:50', '1');

-- ----------------------------
-- Table structure for `log_votes`
-- ----------------------------
DROP TABLE IF EXISTS `log_votes`;
CREATE TABLE `log_votes` (
  `gateway` int(11) NOT NULL,
  `ip` varchar(16) default NULL,
  `accountid` int(15) default NULL,
  `time` int(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of log_votes
-- ----------------------------

-- ----------------------------
-- Table structure for `news`
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` text,
  `body` longtext,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `by` varchar(50) default 'Unknown',
  `sticky` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of news
-- ----------------------------
INSERT INTO `news` VALUES ('1', 'asdasdsada', 'asdasdadfzsdfgzdfgzf\r\nzsdf\r\ns\r\ndfg\r\nsdfg\r\nsf\r\ndg\r\nssfghsfghsdghsfghsgh\r\nsd\r\nf\r\nsdfshg\r\ns\r\nhgs\r\nf\r\nhshgssfghsdghsfghsghsfhsfghsfhsfhsfh\r\nshgsfhsfghshshgssfghsdghsfghsghsfhsfghsfhsfhsfh\r\nshgsfhsfghshshgssfghsdghsfghsghsfhsfghsfhsfhsfh\r\nshgsfhsfghshshgssfghsdghsfghsghsfhsfghsfhsfhsfh\r\nshgsfhsfghs\r\nasdasdadfzsdfgzdfgzf\r\nzsdf\r\ns\r\ndfg\r\nsdfg\r\nsf\r\ndg\r\nssfghsfghsdghsfghsgh\r\nsd\r\nf\r\nsdfshg\r\ns\r\nhgs\r\nf\r\nhshgssfghsdghsfghsghsfhsfghsfhsfhsfh\r\nshgsfhsfghshshgssfghsdghsfghsghsfhsfghsfhsfhsfh\r\nshgsfhsfghshshgssfghsdghsfghsghsfhsfghsfhsfhsfh\r\nshgsfhsfghshshgssfghsdghsfghsghsfhsfghsfhsfhsfh\r\nshgsfhsfghsasdasdadfzsdfgzdfgzf\r\nzsdf\r\ns\r\ndfg\r\nsdfg\r\nsf\r\ndg\r\nssfghsfghsdghsfghsgh\r\nsd\r\nf\r\nsdfshg\r\ns\r\nhgs\r\nf\r\nhshgssfghsdghsfghsghsfhsfghsfhsfhsfh\r\nshgsfhsfghshshgssfghsdghsfghsghsfhsfghsfhsfhsfh\r\nshgsfhsfghshshgssfghsdghsfghsghsfhsfghsfhsfhsfh\r\nshgsfhsfghshshgssfghsdghsfghsghsfhsfghsfhsfhsfh\r\nshgsfhsfghs', '2010-05-11 14:25:34', 'Chmun', '0');
INSERT INTO `news` VALUES ('3', 'TExtTEst', 'Hi yaa', '2010-05-11 14:25:34', 'Unknown', '0');

-- ----------------------------
-- Table structure for `news_comments`
-- ----------------------------
DROP TABLE IF EXISTS `news_comments`;
CREATE TABLE `news_comments` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `newsid` int(11) NOT NULL,
  `title` text,
  `body` longtext,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `by` varchar(50) default 'Unknown',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of news_comments
-- ----------------------------

-- ----------------------------
-- Table structure for `online`
-- ----------------------------
DROP TABLE IF EXISTS `online`;
CREATE TABLE `online` (
  `uid` int(11) unsigned NOT NULL,
  `ip` varchar(15) default NULL,
  `lastvisit` bigint(40) NOT NULL,
  `online` tinyint(1) NOT NULL,
  UNIQUE KEY `id` (`uid`,`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of online
-- ----------------------------
INSERT INTO `online` VALUES ('0', '127.0.0.1', '1277672560', '1');
INSERT INTO `online` VALUES ('1', '127.0.0.1', '1275385136', '0');
INSERT INTO `online` VALUES ('5', '127.0.0.1', '1275385020', '0');
INSERT INTO `online` VALUES ('68', '127.0.0.1', '1275385053', '0');
INSERT INTO `online` VALUES ('2', '127.0.0.1', '1277672846', '1');
INSERT INTO `online` VALUES ('3', '127.0.0.1', '1275385193', '0');
INSERT INTO `online` VALUES ('4', '127.0.0.1', '1275385199', '0');
INSERT INTO `online` VALUES ('0', '216.113.191.33', '1275786027', '0');

-- ----------------------------
-- Table structure for `rewards_donation`
-- ----------------------------
DROP TABLE IF EXISTS `rewards_donation`;
CREATE TABLE `rewards_donation` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `realm` int(5) unsigned NOT NULL default '1',
  `description` longtext,
  `items` longtext NOT NULL,
  `gold` int(11) unsigned NOT NULL default '0',
  `points` int(11) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of rewards_donation
-- ----------------------------
INSERT INTO `rewards_donation` VALUES ('1', '1', 'Test', '4645:2,34721:6,12445:1,4777', '457562', '50');
INSERT INTO `rewards_donation` VALUES ('2', '1', 'Test 2', '19166:1', '4294967295', '30');
INSERT INTO `rewards_donation` VALUES ('3', '1', 'asd', '0', '345', '10');
INSERT INTO `rewards_donation` VALUES ('4', '1', 'lla', '5684', '0', '20');
INSERT INTO `rewards_donation` VALUES ('5', '1', 'sfgfh', '523:1,523:1,523:1,523:1,523:1,523:1,523:1,523:1,523:1,523:1,523:1,523:1,523:1,523:1,523:1,523:1,523:1,523:1', '0', '1');

-- ----------------------------
-- Table structure for `rewards_voting`
-- ----------------------------
DROP TABLE IF EXISTS `rewards_voting`;
CREATE TABLE `rewards_voting` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `realm` int(5) unsigned NOT NULL default '1',
  `description` longtext,
  `items` longtext NOT NULL,
  `gold` int(11) unsigned NOT NULL default '0',
  `points` int(11) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of rewards_voting
-- ----------------------------
INSERT INTO `rewards_voting` VALUES ('1', '1', 'Test', '4645:2,34721:6,12445:1,4777', '457562', '50');
INSERT INTO `rewards_voting` VALUES ('2', '1', 'Test 2', '19166:1', '4294967295', '30');
INSERT INTO `rewards_voting` VALUES ('3', '1', 'asd', '0', '345', '10');
INSERT INTO `rewards_voting` VALUES ('4', '1', 'lla', '5684', '0', '20');
INSERT INTO `rewards_voting` VALUES ('5', '1', 'sfgfh', '523:1,523:1,523:1,523:1,523:1,523:1,523:1,523:1,523:1,523:1,523:1,523:1,523:1,523:1,523:1,523:1,523:1,523:1', '0', '1');

-- ----------------------------
-- Table structure for `vote_gateways`
-- ----------------------------
DROP TABLE IF EXISTS `vote_gateways`;
CREATE TABLE `vote_gateways` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(32) default NULL,
  `image` varchar(128) default NULL,
  `url` varchar(128) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of vote_gateways
-- ----------------------------
INSERT INTO `vote_gateways` VALUES ('1', 'XtremeTop100', 'http://www.xtremetop100.com/votenew.jpg', 'http://www.xtremetop100.com/in.php?site=1132290175');
INSERT INTO `vote_gateways` VALUES ('2', 'TopGameSites', 'http://www.topgamesites.net/images/21.jpg', 'http://www.topgamesites.net/worldofwarcraft');
