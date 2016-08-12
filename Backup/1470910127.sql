-- MySQL dump 10.13  Distrib 5.5.50, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: project_manager
-- ------------------------------------------------------
-- Server version	5.5.50-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `pm_auth`
--

DROP TABLE IF EXISTS `pm_auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pm_auth` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `password` varchar(50) NOT NULL,
  `level` tinyint(3) unsigned NOT NULL COMMENT '0-管理员，1-组长，2-普通组员',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pm_auth`
--

LOCK TABLES `pm_auth` WRITE;
/*!40000 ALTER TABLE `pm_auth` DISABLE KEYS */;
INSERT INTO `pm_auth` VALUES (1,1,'21232f297a57a5a743894a0e4a801fc3',0),(2,2,'202cb962ac59075b964b07152d234b70',1),(6,6,'202cb962ac59075b964b07152d234b70',1),(10,10,'202cb962ac59075b964b07152d234b70',2),(11,11,'202cb962ac59075b964b07152d234b70',2),(12,12,'202cb962ac59075b964b07152d234b70',1);
/*!40000 ALTER TABLE `pm_auth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pm_evaluation_records`
--

DROP TABLE IF EXISTS `pm_evaluation_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pm_evaluation_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `leader_uid` int(11) unsigned NOT NULL,
  `member_uid` int(11) unsigned NOT NULL,
  `project_id` int(11) unsigned NOT NULL,
  `overall_accuracy_mark` int(11) unsigned DEFAULT '0' COMMENT '总体精准度',
  `sampling_inspection_mark` int(11) unsigned DEFAULT '0' COMMENT '抽量检查',
  `summary_mark` int(11) unsigned DEFAULT '0' COMMENT '组长总结得分',
  `c_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pm_evaluation_records`
--

LOCK TABLES `pm_evaluation_records` WRITE;
/*!40000 ALTER TABLE `pm_evaluation_records` DISABLE KEYS */;
INSERT INTO `pm_evaluation_records` VALUES (2,12,11,4,42,47,0,1470455463),(3,12,12,4,57,17,40,1470455463);
/*!40000 ALTER TABLE `pm_evaluation_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pm_position`
--

DROP TABLE IF EXISTS `pm_position`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pm_position` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `position` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pm_position`
--

LOCK TABLES `pm_position` WRITE;
/*!40000 ALTER TABLE `pm_position` DISABLE KEYS */;
INSERT INTO `pm_position` VALUES (1,'商务经理'),(2,'商务助理'),(3,'预算组长'),(7,'预算员');
/*!40000 ALTER TABLE `pm_position` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pm_project`
--

DROP TABLE IF EXISTS `pm_project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pm_project`
--

LOCK TABLES `pm_project` WRITE;
/*!40000 ALTER TABLE `pm_project` DISABLE KEYS */;
INSERT INTO `pm_project` VALUES (2,'project1','testtest1234',2,1467302400,1468771200,0,1,1469895728),(3,'project2','test2',6,1467734400,1474387200,0,1,1470296851),(4,'testproj1','testproj1testproj1testproj1',12,1469980800,1474819200,1470387527,4,1470380953);
/*!40000 ALTER TABLE `pm_project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pm_sign_records`
--

DROP TABLE IF EXISTS `pm_sign_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pm_sign_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `ip` int(50) unsigned NOT NULL,
  `c_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pm_sign_records`
--

LOCK TABLES `pm_sign_records` WRITE;
/*!40000 ALTER TABLE `pm_sign_records` DISABLE KEYS */;
INSERT INTO `pm_sign_records` VALUES (4,2,2130706433,1469773209),(6,1,0,1469797096),(7,11,2130706433,1470274888),(8,6,2130706433,1470297384),(13,11,2130706433,1470387244),(14,12,2130706433,1470387527),(17,11,0,1470490103),(18,12,0,1470535601);
/*!40000 ALTER TABLE `pm_sign_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pm_station`
--

DROP TABLE IF EXISTS `pm_station`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pm_station` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `station` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pm_station`
--

LOCK TABLES `pm_station` WRITE;
/*!40000 ALTER TABLE `pm_station` DISABLE KEYS */;
INSERT INTO `pm_station` VALUES (1,'部门经理'),(2,'部门副经理'),(3,'部门主管'),(4,'部门助理'),(5,'项目商务人员');
/*!40000 ALTER TABLE `pm_station` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pm_summary`
--

DROP TABLE IF EXISTS `pm_summary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pm_summary` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `member_uid` int(11) unsigned NOT NULL,
  `project_id` int(11) unsigned NOT NULL,
  `content` text NOT NULL,
  `c_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pm_summary`
--

LOCK TABLES `pm_summary` WRITE;
/*!40000 ALTER TABLE `pm_summary` DISABLE KEYS */;
/*!40000 ALTER TABLE `pm_summary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pm_task`
--

DROP TABLE IF EXISTS `pm_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0-已启动，1-已结束，2-禁用',
  `completion` int(11) unsigned NOT NULL DEFAULT '0',
  `c_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pm_task`
--

LOCK TABLES `pm_task` WRITE;
/*!40000 ALTER TABLE `pm_task` DISABLE KEYS */;
INSERT INTO `pm_task` VALUES (4,'t1',11,2,9,2,'taks1',1438531200,1475424000,0,0,18,1470106091),(5,'t2',11,2,9,2,'taks2',1438531200,1475424000,0,0,41,1470106091),(6,'t3',11,2,9,2,'taks3',1438531200,1475424000,0,0,21,1470106091),(7,'task1',12,12,14,4,'taskj1taskj1',1469980800,1474819200,1470387527,1,100,1470381048),(8,'taskproj1',11,12,13,4,'taskproj1taskproj1',1469980800,1474819200,1470387244,1,100,1470381093);
/*!40000 ALTER TABLE `pm_task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pm_user`
--

DROP TABLE IF EXISTS `pm_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pm_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `truename` varchar(50) NOT NULL,
  `phone` int(11) unsigned NOT NULL,
  `position_id` int(11) unsigned NOT NULL COMMENT '职务',
  `station_id` int(11) unsigned NOT NULL COMMENT '岗位',
  `work_place_id` int(11) unsigned NOT NULL COMMENT '工作地点',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pm_user`
--

LOCK TABLES `pm_user` WRITE;
/*!40000 ALTER TABLE `pm_user` DISABLE KEYS */;
INSERT INTO `pm_user` VALUES (1,'admin','超级管理员',0,0,0,0),(2,'messi','梅西',123123,7,5,4),(6,'messikiller','messi',123123,7,5,4),(10,'hello','你好',123123,7,2,3),(11,'zhangsan','张三',1234456,3,4,3),(12,'leader1','组长1',123456,1,1,2);
/*!40000 ALTER TABLE `pm_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pm_work`
--

DROP TABLE IF EXISTS `pm_work`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pm_work`
--

LOCK TABLES `pm_work` WRITE;
/*!40000 ALTER TABLE `pm_work` DISABLE KEYS */;
INSERT INTO `pm_work` VALUES (7,'w1',10,2,2,'work1',1467302400,1467734400,0,0,1470023432),(8,'w2',11,2,2,'work2',1467648000,1468425600,0,0,1470023432),(9,'w3',11,2,2,'work3',1468080000,1468771200,0,1,1470023432),(10,'mywork1',6,6,3,'mywork123',1469030400,1472918400,0,0,1470299128),(11,'mywork2',6,6,3,'mywork123',1470326400,1473350400,0,0,1470299128),(12,'mywork3',11,6,3,'mywork123',1468771200,1473264000,0,0,1470299128),(13,'w1',11,12,4,'word1',1469980800,1474819200,1470387244,2,1470381019),(14,'w2',12,12,4,'work2',1469980800,1474819200,1470387527,2,1470381019);
/*!40000 ALTER TABLE `pm_work` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pm_work_place`
--

DROP TABLE IF EXISTS `pm_work_place`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pm_work_place` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `work_place` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pm_work_place`
--

LOCK TABLES `pm_work_place` WRITE;
/*!40000 ALTER TABLE `pm_work_place` DISABLE KEYS */;
INSERT INTO `pm_work_place` VALUES (2,'商务中心'),(3,'商务部'),(4,'项目');
/*!40000 ALTER TABLE `pm_work_place` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-08-11 18:08:47
