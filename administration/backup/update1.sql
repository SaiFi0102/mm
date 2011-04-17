ALTER TABLE `realmd`.`online`
ADD COLUMN `firstvisit` TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER `lastvisit`,
ADD COLUMN `visits` INT(11) UNSIGNED DEFAULT '0' NOT NULL AFTER `online`,
ADD COLUMN `request_uri` VARCHAR(225) NOT NULL AFTER `visits`,
ADD COLUMN `header_host` VARCHAR(225) NULL AFTER `request_uri`,
ADD COLUMN `header_connection` VARCHAR(225) NULL AFTER `header_host`,
ADD COLUMN `header_user_agent` VARCHAR(225) NULL AFTER `header_connection`,
ADD COLUMN `header_cache_control` VARCHAR(225) NULL AFTER `header_user_agent`,
ADD COLUMN `header_accept` VARCHAR(225) NULL AFTER `header_cache_control`,
ADD COLUMN `header_accept_encoding` VARCHAR(225) NULL AFTER `header_accept`,
ADD COLUMN `header_accept_language` VARCHAR(225) NULL AFTER `header_accept_encoding`,
ADD COLUMN `header_accept_charset` VARCHAR(225) NULL AFTER `header_accept_language`;
ALTER TABLE `realmd`.`news`     ADD COLUMN `link` VARCHAR(100) NULL AFTER `sticky`;