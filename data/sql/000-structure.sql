
SET foreign_key_checks=0;

DROP TABLE IF EXISTS `routes`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `pages`;
DROP TABLE IF EXISTS `pagemetas`;
DROP TABLE IF EXISTS `attachments`;
DROP TABLE IF EXISTS `menu_links`;
DROP TABLE IF EXISTS `menus`;

CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`username`)
) ENGINE=InnoDB;

CREATE TABLE `attachments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) unsigned NOT NULL,
  `mime` varchar(50) NOT NULL,
  `path` varchar(300) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`path`)
) ENGINE=InnoDB;

CREATE TABLE `pages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `content` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `menus` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `site` int unsigned NOT NULL,
  `locale` int unsigned NOT NULL,
  `sid` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`sid`)
) ENGINE=InnoDB;

CREATE TABLE `pagemetas` (
  `model_id` int unsigned NOT NULL,
  `model_class` varchar(100) NOT NULL,
  `locale` smallint unsigned,
  `title` varchar(200) DEFAULT NULL,
  `keywords` varchar(200) DEFAULT NULL,
  `description` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`model_id`,`model_class`,`locale`)
) ENGINE=InnoDB;

CREATE TABLE `routes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `site` smallint unsigned,
  `locale` smallint unsigned,
  `url` varchar(500) NOT NULL,
  `mvc_path` varchar(100) NOT NULL,

  PRIMARY KEY (`id`),
  UNIQUE KEY (`site`, `url`)
) ENGINE=InnoDB DEFAULT CHARACTER SET ascii;

CREATE TABLE `menu_links` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `menu` int unsigned NOT NULL,
  `route` int unsigned NOT NULL,
  `parent` int unsigned NOT NULL DEFAULT 0,
  `weight` int unsigned NOT NULL DEFAULT 0,
  `visible` enum('1','0') NOT NULL,
  `url` varchar(500) NOT NULL,
  `label` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  KEY (`menu`, `parent`,`weight`),
  CONSTRAINT FOREIGN KEY (`menu`)
	  REFERENCES `menus` (`id`)
	  ON DELETE CASCADE
	  ON UPDATE CASCADE
) ENGINE=InnoDB;
