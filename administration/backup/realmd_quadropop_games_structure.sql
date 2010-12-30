/*Table structure for table `quadropop_games` */

DROP TABLE IF EXISTS `quadropop_games`;

CREATE TABLE `quadropop_games` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `players` text,
  `data` text,
  `length_y` int(11) DEFAULT NULL,
  `length_x` int(11) DEFAULT NULL,
  `turn` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;