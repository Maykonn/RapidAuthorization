SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for operation
-- ----------------------------
CREATE TABLE IF NOT EXISTS `operation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for role
-- ----------------------------
CREATE TABLE IF NOT EXISTS `role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for role_has_task
-- ----------------------------
CREATE TABLE IF NOT EXISTS `role_has_task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_role` int(10) unsigned NOT NULL,
  `id_task` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_role_has_task` (`id_role`,`id_task`),
  KEY `id_role` (`id_role`) USING BTREE,
  KEY `id_task` (`id_task`) USING BTREE,
  CONSTRAINT `role_has_task_role` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `role_has_task_task` FOREIGN KEY (`id_task`) REFERENCES `task` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for task
-- ----------------------------
CREATE TABLE IF NOT EXISTS `task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for task_has_operation
-- ----------------------------
CREATE TABLE IF NOT EXISTS `task_has_operation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_task` int(10) unsigned NOT NULL,
  `id_operation` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_task_has_operation` (`id_operation`,`id_task`),
  KEY `id_task` (`id_task`) USING BTREE,
  KEY `id_operation` (`id_operation`) USING BTREE,
  CONSTRAINT `task_has_operation_task` FOREIGN KEY (`id_task`) REFERENCES `task` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `task_has_operation_operation` FOREIGN KEY (`id_operation`) REFERENCES `operation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user
-- ----------------------------
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_has_role
-- ----------------------------
CREATE TABLE IF NOT EXISTS `user_has_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_role` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_user_has_role` (`id_user`,`id_role`),
  KEY `id_user` (`id_user`),
  KEY `id_role` (`id_role`),
  CONSTRAINT `user_has_role_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_has_role_role` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;