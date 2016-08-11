-- Generation time: Thu, 11 Aug 2016 22:25:44 +0800
-- Host: localhost
-- DB name: project_manager
/*!40030 SET NAMES UTF8 */;

DROP TABLE IF EXISTS `pm_auth`;
CREATE TABLE `pm_auth` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `password` varchar(50) NOT NULL,
  `level` tinyint(3) unsigned NOT NULL COMMENT '0-????1-???2-????',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

INSERT INTO `pm_auth` VALUES ('1','1','21232f297a57a5a743894a0e4a801fc3','0'),
('2','2','202cb962ac59075b964b07152d234b70','1'),
('6','6','202cb962ac59075b964b07152d234b70','1'),
('10','10','202cb962ac59075b964b07152d234b70','2'),
('11','11','202cb962ac59075b964b07152d234b70','2'),
('12','12','202cb962ac59075b964b07152d234b70','1'); 


DROP TABLE IF EXISTS `pm_evaluation_records`;
CREATE TABLE `pm_evaluation_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `leader_uid` int(11) unsigned NOT NULL,
  `member_uid` int(11) unsigned NOT NULL,
  `project_id` int(11) unsigned NOT NULL,
  `overall_accuracy_mark` int(11) unsigned DEFAULT '0' COMMENT '?????',
  `sampling_inspection_mark` int(11) unsigned DEFAULT '0' COMMENT '????',
  `summary_mark` int(11) unsigned DEFAULT '0' COMMENT '??????',
  `c_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `pm_evaluation_records` VALUES ('2','12','11','4','42','47','0','1470455463'),
('3','12','12','4','57','17','40','1470455463'); 


DROP TABLE IF EXISTS `pm_position`;
CREATE TABLE `pm_position` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `position` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

INSERT INTO `pm_position` VALUES ('1','????'),
('2','????'),
('3','????'),
('7','???'); 


DROP TABLE IF EXISTS `pm_project`;
CREATE TABLE `pm_project` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_name` varchar(50) NOT NULL,
  `remark` text,
  `leader_uid` int(11) unsigned NOT NULL,
  `s_time` int(11) unsigned NOT NULL DEFAULT '0',
  `e_time` int(11) unsigned NOT NULL DEFAULT '0',
  `f_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '????????',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0-??????1-????2-????3-???4-???',
  `c_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `pm_project` VALUES ('2','project1','testtest1234','2','1467302400','1468771200','0','1','1469895728'),
('3','project2','test2','6','1467734400','1474387200','0','1','1470296851'),
('4','testproj1','testproj1testproj1testproj1','12','1469980800','1474819200','1470387527','4','1470380953'); 


DROP TABLE IF EXISTS `pm_sign_records`;
CREATE TABLE `pm_sign_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `ip` int(50) unsigned NOT NULL,
  `c_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

INSERT INTO `pm_sign_records` VALUES ('4','2','2130706433','1469773209'),
('6','1','0','1469797096'),
('7','11','2130706433','1470274888'),
('8','6','2130706433','1470297384'),
('13','11','2130706433','1470387244'),
('14','12','2130706433','1470387527'),
('17','11','0','1470490103'),
('18','12','0','1470535601'); 


DROP TABLE IF EXISTS `pm_station`;
CREATE TABLE `pm_station` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `station` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO `pm_station` VALUES ('1','????'),
('2','?????'),
('3','????'),
('4','????'),
('5','??????'); 


DROP TABLE IF EXISTS `pm_summary`;
CREATE TABLE `pm_summary` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `member_uid` int(11) unsigned NOT NULL,
  `project_id` int(11) unsigned NOT NULL,
  `content` text NOT NULL,
  `c_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `pm_task`;
CREATE TABLE `pm_task` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `task_name` text NOT NULL,
  `member_uid` int(11) unsigned NOT NULL,
  `leader_uid` int(11) unsigned NOT NULL,
  `work_id` int(11) unsigned NOT NULL,
  `project_id` int(11) unsigned NOT NULL,
  `remark` text,
  `s_time` int(11) unsigned NOT NULL DEFAULT '0',
  `e_time` int(11) unsigned NOT NULL DEFAULT '0',
  `f_time` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0-????1-????2-??',
  `completion` int(11) unsigned NOT NULL DEFAULT '0',
  `c_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

INSERT INTO `pm_task` VALUES ('4','t1','11','2','9','2','taks1','1438531200','1475424000','0','0','18','1470106091'),
('5','t2','11','2','9','2','taks2','1438531200','1475424000','0','0','41','1470106091'),
('6','t3','11','2','9','2','taks3','1438531200','1475424000','0','0','21','1470106091'),
('7','task1','12','12','14','4','taskj1taskj1','1469980800','1474819200','1470387527','1','100','1470381048'),
('8','taskproj1','11','12','13','4','taskproj1taskproj1','1469980800','1474819200','1470387244','1','100','1470381093'); 


DROP TABLE IF EXISTS `pm_user`;
CREATE TABLE `pm_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `truename` varchar(50) NOT NULL,
  `phone` int(11) unsigned NOT NULL,
  `position_id` int(11) unsigned NOT NULL COMMENT '??',
  `station_id` int(11) unsigned NOT NULL COMMENT '??',
  `work_place_id` int(11) unsigned NOT NULL COMMENT '????',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

INSERT INTO `pm_user` VALUES ('1','admin','?????','0','0','0','0'),
('2','messi','??','123123','7','5','4'),
('6','messikiller','messi','123123','7','5','4'),
('10','hello','??','123123','7','2','3'),
('11','zhangsan','??','1234456','3','4','3'),
('12','leader1','??1','123456','1','1','2'); 


DROP TABLE IF EXISTS `pm_work`;
CREATE TABLE `pm_work` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `work_name` varchar(255) NOT NULL,
  `member_uid` int(11) unsigned NOT NULL,
  `leader_uid` int(11) unsigned NOT NULL,
  `project_id` int(11) unsigned NOT NULL,
  `remark` text,
  `s_time` int(11) unsigned NOT NULL DEFAULT '0',
  `e_time` int(11) unsigned NOT NULL DEFAULT '0',
  `f_time` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0-??????1-????2-???',
  `c_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

INSERT INTO `pm_work` VALUES ('7','w1','10','2','2','work1','1467302400','1467734400','0','0','1470023432'),
('8','w2','11','2','2','work2','1467648000','1468425600','0','0','1470023432'),
('9','w3','11','2','2','work3','1468080000','1468771200','0','1','1470023432'),
('10','mywork1','6','6','3','mywork123','1469030400','1472918400','0','0','1470299128'),
('11','mywork2','6','6','3','mywork123','1470326400','1473350400','0','0','1470299128'),
('12','mywork3','11','6','3','mywork123','1468771200','1473264000','0','0','1470299128'),
('13','w1','11','12','4','word1','1469980800','1474819200','1470387244','2','1470381019'),
('14','w2','12','12','4','work2','1469980800','1474819200','1470387527','2','1470381019'); 


DROP TABLE IF EXISTS `pm_work_place`;
CREATE TABLE `pm_work_place` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `work_place` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `pm_work_place` VALUES ('2','????'),
('3','???'),
('4','??'); 


