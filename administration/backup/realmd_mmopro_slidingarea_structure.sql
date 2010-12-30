DROP TABLE IF EXISTS `mmopro_slidingarea`;

CREATE TABLE `mmopro_slidingarea` (
  `order` smallint(3) unsigned NOT NULL,
  `text_left` longtext,
  `text_right` longtext,
  PRIMARY KEY (`order`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;