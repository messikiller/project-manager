/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : project_manager

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-08-02 23:18:08
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pm_auth`
-- ----------------------------
DROP TABLE IF EXISTS `pm_auth`;
CREATE TABLE `pm_auth` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `password` varchar(50) NOT NULL,
  `level` tinyint(3) unsigned NOT NULL COMMENT '0-管理员，1-组长，2-普通组员',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_auth
-- ----------------------------
INSERT INTO `pm_auth` VALUES ('1', '1', '21232f297a57a5a743894a0e4a801fc3', '0');
INSERT INTO `pm_auth` VALUES ('2', '2', '202cb962ac59075b964b07152d234b70', '1');
INSERT INTO `pm_auth` VALUES ('6', '6', '202cb962ac59075b964b07152d234b70', '1');
INSERT INTO `pm_auth` VALUES ('10', '10', '202cb962ac59075b964b07152d234b70', '2');
INSERT INTO `pm_auth` VALUES ('11', '11', '202cb962ac59075b964b07152d234b70', '2');

-- ----------------------------
-- Table structure for `pm_evaluation_records`
-- ----------------------------
DROP TABLE IF EXISTS `pm_evaluation_records`;
CREATE TABLE `pm_evaluation_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `work_id` int(11) unsigned NOT NULL,
  `operator_uid` int(11) unsigned NOT NULL,
  `project_id` int(11) unsigned NOT NULL,
  `overall_accuracy_mark` int(11) unsigned DEFAULT '0' COMMENT '总体精准度',
  `sampling_inspection_mark` int(11) unsigned DEFAULT '0' COMMENT '抽量检查',
  `summary_mark` int(11) unsigned DEFAULT '0' COMMENT '组长总结得分',
  `c_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_evaluation_records
-- ----------------------------

-- ----------------------------
-- Table structure for `pm_position`
-- ----------------------------
DROP TABLE IF EXISTS `pm_position`;
CREATE TABLE `pm_position` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `position` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_position
-- ----------------------------
INSERT INTO `pm_position` VALUES ('1', '商务经理');
INSERT INTO `pm_position` VALUES ('2', '商务助理');
INSERT INTO `pm_position` VALUES ('3', '预算组长');
INSERT INTO `pm_position` VALUES ('7', '预算员');

-- ----------------------------
-- Table structure for `pm_project`
-- ----------------------------
DROP TABLE IF EXISTS `pm_project`;
CREATE TABLE `pm_project` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_name` varchar(50) NOT NULL,
  `remark` text,
  `leader_uid` int(11) unsigned NOT NULL,
  `s_time` int(11) unsigned NOT NULL DEFAULT '0',
  `e_time` int(11) unsigned NOT NULL DEFAULT '0',
  `f_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '完成项目实际时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0-正常未启动，1-已启动，2-已结束，3-禁用，4-已打分',
  `c_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_project
-- ----------------------------
INSERT INTO `pm_project` VALUES ('2', 'project1', 'testtest1234', '2', '1467302400', '1468771200', '0', '1', '1469895728');
INSERT INTO `pm_project` VALUES ('3', 'project2', 'test2', '6', '1467734400', '1469462400', '0', '3', '1469895793');

-- ----------------------------
-- Table structure for `pm_sign_records`
-- ----------------------------
DROP TABLE IF EXISTS `pm_sign_records`;
CREATE TABLE `pm_sign_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `ip` int(50) unsigned NOT NULL,
  `c_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_sign_records
-- ----------------------------
INSERT INTO `pm_sign_records` VALUES ('4', '2', '2130706433', '1469773209');
INSERT INTO `pm_sign_records` VALUES ('5', '1', '2130706433', '1479886598');
INSERT INTO `pm_sign_records` VALUES ('6', '1', '0', '1469797096');

-- ----------------------------
-- Table structure for `pm_station`
-- ----------------------------
DROP TABLE IF EXISTS `pm_station`;
CREATE TABLE `pm_station` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `station` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_station
-- ----------------------------
INSERT INTO `pm_station` VALUES ('1', '部门经理');
INSERT INTO `pm_station` VALUES ('2', '部门副经理');
INSERT INTO `pm_station` VALUES ('3', '部门主管');
INSERT INTO `pm_station` VALUES ('4', '部门助理');
INSERT INTO `pm_station` VALUES ('5', '项目商务人员');

-- ----------------------------
-- Table structure for `pm_task`
-- ----------------------------
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
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0-正常未启动，1-已启动，2-已结束，3-禁用',
  `completion` int(11) unsigned NOT NULL DEFAULT '0',
  `c_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_task
-- ----------------------------
INSERT INTO `pm_task` VALUES ('4', 't1', '11', '2', '9', '2', 'taks1', '1468339200', '1468771200', '0', '0', '0', '1470106091');
INSERT INTO `pm_task` VALUES ('5', 't2', '11', '2', '9', '2', 'taks2', '1468252800', '1468512000', '0', '0', '0', '1470106091');
INSERT INTO `pm_task` VALUES ('6', 't3', '11', '2', '9', '2', 'taks3', '1468252800', '1468684800', '0', '0', '0', '1470106091');

-- ----------------------------
-- Table structure for `pm_user`
-- ----------------------------
DROP TABLE IF EXISTS `pm_user`;
CREATE TABLE `pm_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `truename` varchar(50) NOT NULL,
  `phone` int(11) unsigned NOT NULL,
  `position_id` int(11) unsigned NOT NULL COMMENT '职务',
  `station_id` int(11) unsigned NOT NULL COMMENT '岗位',
  `work_place_id` int(11) unsigned NOT NULL COMMENT '工作地点',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_user
-- ----------------------------
INSERT INTO `pm_user` VALUES ('1', 'admin', '超级管理员', '0', '0', '0', '0');
INSERT INTO `pm_user` VALUES ('2', 'messi', '梅西', '123123', '7', '5', '4');
INSERT INTO `pm_user` VALUES ('6', 'messikiller', 'messi', '123123', '7', '5', '4');
INSERT INTO `pm_user` VALUES ('10', 'hello', '你好', '123123', '7', '2', '3');
INSERT INTO `pm_user` VALUES ('11', 'zhangsan', '张三', '1234456', '3', '4', '3');

-- ----------------------------
-- Table structure for `pm_work`
-- ----------------------------
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
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0-正常未启动，1-已启动，2-已结束',
  `c_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_work
-- ----------------------------
INSERT INTO `pm_work` VALUES ('7', 'w1', '10', '2', '2', 'work1', '1467302400', '1467734400', '0', '0', '1470023432');
INSERT INTO `pm_work` VALUES ('8', 'w2', '11', '2', '2', 'work2', '1467648000', '1468425600', '0', '0', '1470023432');
INSERT INTO `pm_work` VALUES ('9', 'w3', '11', '2', '2', 'work3', '1468080000', '1468771200', '0', '1', '1470023432');

-- ----------------------------
-- Table structure for `pm_work_place`
-- ----------------------------
DROP TABLE IF EXISTS `pm_work_place`;
CREATE TABLE `pm_work_place` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `work_place` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_work_place
-- ----------------------------
INSERT INTO `pm_work_place` VALUES ('2', '商务中心');
INSERT INTO `pm_work_place` VALUES ('3', '商务部');
INSERT INTO `pm_work_place` VALUES ('4', '项目');
