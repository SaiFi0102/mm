CREATE TABLE `character_mm_extend` (
`guid`  int(15) UNSIGNED NOT NULL ,
`donated`  int(5) UNSIGNED NOT NULL DEFAULT 0 ,
PRIMARY KEY (`guid`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
ROW_FORMAT=COMPACT
;

