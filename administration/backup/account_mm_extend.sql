-- ----------------------------
-- Table structure for `account_mm_extend`
-- ----------------------------
DROP TABLE IF EXISTS `account_mm_extend`;
CREATE TABLE `account_mm_extend` (
  `accountid` int(11) unsigned NOT NULL,
  `donationpoints` int(50) NOT NULL default '0',
  `donated` int(50) NOT NULL default '0',
  `votepoints` int(50) NOT NULL default '0',
  `voted` int(50) NOT NULL default '0',
  PRIMARY KEY  (`accountid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
-- ----------------------------