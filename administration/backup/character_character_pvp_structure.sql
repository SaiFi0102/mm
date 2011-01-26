CREATE TABLE `character_pvp` (
  `guid` int(10) unsigned NOT NULL,
  `totalkills` int(10) unsigned NOT NULL,
  `currentkills` int(10) unsigned NOT NULL,
  `totaldeaths` int(10) unsigned NOT NULL,
  `currentdeaths` int(10) unsigned NOT NULL,
  `groupkills` int(10) unsigned NOT NULL,
  `killstreak` int(10) unsigned NOT NULL,
  `lastkillguid` int(10) unsigned NOT NULL,
  `lastkillcount` int(10) unsigned NOT NULL,
  PRIMARY KEY (`guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;