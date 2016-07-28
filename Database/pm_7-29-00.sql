/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : project_manager

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-07-29 00:27:53
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_auth
-- ----------------------------
INSERT INTO `pm_auth` VALUES ('1', '1', '21232f297a57a5a743894a0e4a801fc3', '0');
INSERT INTO `pm_auth` VALUES ('2', '2', '3b593cffbd70aefca6fbdc2b1563bf1e', '1');

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
  `leader_uid` int(11) unsigned NOT NULL,
  `s_time` int(11) unsigned NOT NULL,
  `e_time` int(11) unsigned NOT NULL,
  `finish_time` int(11) unsigned NOT NULL COMMENT '完成项目实际消耗的时间',
  `remark` text,
  `c_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_project
-- ----------------------------

-- ----------------------------
-- Table structure for `pm_project_work`
-- ----------------------------
DROP TABLE IF EXISTS `pm_project_work`;
CREATE TABLE `pm_project_work` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) unsigned NOT NULL,
  `work_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_project_work
-- ----------------------------

-- ----------------------------
-- Table structure for `pm_sign_records`
-- ----------------------------
DROP TABLE IF EXISTS `pm_sign_records`;
CREATE TABLE `pm_sign_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `ip` int(20) unsigned NOT NULL,
  `c_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_sign_records
-- ----------------------------

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_user
-- ----------------------------
INSERT INTO `pm_user` VALUES ('1', 'admin', '0', '0', '0', '0', '0');
INSERT INTO `pm_user` VALUES ('2', 'messi', '梅西', '123123', '7', '5', '4');

-- ----------------------------
-- Table structure for `pm_work`
-- ----------------------------
DROP TABLE IF EXISTS `pm_work`;
CREATE TABLE `pm_work` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `work_name` varchar(50) NOT NULL,
  `content` varchar(50) NOT NULL,
  `s_time` int(11) unsigned NOT NULL,
  `e_time` int(11) unsigned NOT NULL,
  `c_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_work
-- ----------------------------

-- ----------------------------
-- Table structure for `pm_work_member`
-- ----------------------------
DROP TABLE IF EXISTS `pm_work_member`;
CREATE TABLE `pm_work_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `work_id` int(11) unsigned NOT NULL,
  `member_uid` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pm_work_member
-- ----------------------------

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
