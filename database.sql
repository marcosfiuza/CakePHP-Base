DROP TABLE IF EXISTS `acos`;

CREATE TABLE `acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_acos_lft_rght` (`lft`,`rght`),
  KEY `idx_acos_alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `acos` WRITE;

INSERT INTO `acos` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`)
VALUES
	(1,NULL,NULL,NULL,'controllers',1,8),
	(2,1,NULL,NULL,'admin',2,7),
	(3,2,NULL,NULL,'dashboard',3,6),
	(4,3,NULL,NULL,'index',4,5);

UNLOCK TABLES;


DROP TABLE IF EXISTS `aros`;

CREATE TABLE `aros` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_aros_lft_rght` (`lft`,`rght`),
  KEY `idx_aros_alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `aros` WRITE;

INSERT INTO `aros` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`)
VALUES
	(1,NULL,'Group',1,'Visitor',1,8),
	(2,1,'Group',2,'Administrator',2,5),
	(3,1,'Group',3,'User',6,7),
	(4,2,'User',1,NULL,3,4);

UNLOCK TABLES;


DROP TABLE IF EXISTS `aros_acos`;

CREATE TABLE `aros_acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `aro_id` int(10) NOT NULL,
  `aco_id` int(10) NOT NULL,
  `_create` varchar(2) NOT NULL DEFAULT '0',
  `_read` varchar(2) NOT NULL DEFAULT '0',
  `_update` varchar(2) NOT NULL DEFAULT '0',
  `_delete` varchar(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ARO_ACO_KEY` (`aro_id`,`aco_id`),
  KEY `idx_aco_id` (`aco_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `aros_acos` WRITE;

INSERT INTO `aros_acos` (`id`, `aro_id`, `aco_id`, `_create`, `_read`, `_update`, `_delete`)
VALUES
	(1,1,4,'1','1','1','1');

UNLOCK TABLES;


DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `hash` varchar(40) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `status` varchar(30) DEFAULT 'enabled',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `groups` WRITE;

INSERT INTO `groups` (`id`, `parent_id`, `title`, `lft`, `rght`, `is_default`, `hash`, `created_at`, `updated_at`, `status`)
VALUES
	(1,NULL,'Visitor',1,6,0,'57c47d12-c1d8-44b5-b7ac-0eaa053cbbac','2016-08-29 00:00:00','2016-08-29 00:00:00','enabled'),
	(2,1,'Administrator',2,3,0,'57c47d12-3b04-454b-9c8f-0eaa053cbbac','2016-08-29 00:00:00','2016-08-29 00:00:00','enabled'),
	(3,1,'User',4,5,1,'57c47d12-59bc-42b9-a291-0eaa053cbbac','2016-08-29 00:00:00','2016-08-29 00:00:00','enabled');

UNLOCK TABLES;


DROP TABLE IF EXISTS `system_events`;

CREATE TABLE `system_events` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `model` varchar(50) DEFAULT '',
  `foreign_key` int(11) DEFAULT NULL,
  `event` varchar(255) NOT NULL DEFAULT '',
  `data` text,
  `hash` varchar(50) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `status` varchar(30) DEFAULT 'enabled',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `password` text NOT NULL,
  `timezone` varchar(20) DEFAULT 'UTC',
  `hash` varchar(40) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `status` varchar(30) DEFAULT 'enabled',
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;

INSERT INTO `users` (`id`, `group_id`, `name`, `email`, `password`, `timezone`, `hash`, `created_at`, `updated_at`, `status`)
VALUES
	(1,2,'Administrator','administrator@localhost.com','703ccd26dfc2d9451c0a462705856ece69e7128f','UTC','57c47d12-1c9c-4ae8-8eda-0eaa053cbbac','2016-08-29 00:00:00','2016-08-29 00:00:00','enabled');

UNLOCK TABLES;
