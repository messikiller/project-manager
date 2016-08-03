-- phpMyAdmin SQL Dump
-- version 4.2.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2016-08-03 18:41:11
-- 服务器版本： 5.5.50-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `project_manager`
--

-- --------------------------------------------------------

--
-- 表的结构 `pm_auth`
--

CREATE TABLE IF NOT EXISTS `pm_auth` (
`id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `password` varchar(50) NOT NULL,
  `level` tinyint(3) unsigned NOT NULL COMMENT '0-管理员，1-组长，2-普通组员'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `pm_auth`
--

INSERT INTO `pm_auth` (`id`, `user_id`, `password`, `level`) VALUES
(1, 1, '21232f297a57a5a743894a0e4a801fc3', 0),
(2, 2, '202cb962ac59075b964b07152d234b70', 1),
(6, 6, '202cb962ac59075b964b07152d234b70', 1),
(10, 10, '202cb962ac59075b964b07152d234b70', 2),
(11, 11, '202cb962ac59075b964b07152d234b70', 2);

-- --------------------------------------------------------

--
-- 表的结构 `pm_evaluation_records`
--

CREATE TABLE IF NOT EXISTS `pm_evaluation_records` (
`id` int(11) unsigned NOT NULL,
  `work_id` int(11) unsigned NOT NULL,
  `operator_uid` int(11) unsigned NOT NULL,
  `project_id` int(11) unsigned NOT NULL,
  `overall_accuracy_mark` int(11) unsigned DEFAULT '0' COMMENT '总体精准度',
  `sampling_inspection_mark` int(11) unsigned DEFAULT '0' COMMENT '抽量检查',
  `summary_mark` int(11) unsigned DEFAULT '0' COMMENT '组长总结得分',
  `c_time` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pm_position`
--

CREATE TABLE IF NOT EXISTS `pm_position` (
`id` int(11) unsigned NOT NULL,
  `position` varchar(50) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `pm_position`
--

INSERT INTO `pm_position` (`id`, `position`) VALUES
(1, '商务经理'),
(2, '商务助理'),
(3, '预算组长'),
(7, '预算员');

-- --------------------------------------------------------

--
-- 表的结构 `pm_project`
--

CREATE TABLE IF NOT EXISTS `pm_project` (
`id` int(11) unsigned NOT NULL,
  `project_name` varchar(50) NOT NULL,
  `remark` text,
  `leader_uid` int(11) unsigned NOT NULL,
  `s_time` int(11) unsigned NOT NULL DEFAULT '0',
  `e_time` int(11) unsigned NOT NULL DEFAULT '0',
  `f_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '完成项目实际时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0-正常未启动，1-已启动，2-已结束，3-禁用，4-已打分',
  `c_time` int(11) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `pm_project`
--

INSERT INTO `pm_project` (`id`, `project_name`, `remark`, `leader_uid`, `s_time`, `e_time`, `f_time`, `status`, `c_time`) VALUES
(2, 'project1', 'testtest1234', 2, 1467302400, 1468771200, 0, 1, 1469895728),
(3, 'project2', 'test2', 6, 1467734400, 1469462400, 0, 3, 1469895793);

-- --------------------------------------------------------

--
-- 表的结构 `pm_sign_records`
--

CREATE TABLE IF NOT EXISTS `pm_sign_records` (
`id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `ip` int(50) unsigned NOT NULL,
  `c_time` int(11) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `pm_sign_records`
--

INSERT INTO `pm_sign_records` (`id`, `user_id`, `ip`, `c_time`) VALUES
(4, 2, 2130706433, 1469773209),
(5, 1, 2130706433, 1479886598),
(6, 1, 0, 1469797096);

-- --------------------------------------------------------

--
-- 表的结构 `pm_station`
--

CREATE TABLE IF NOT EXISTS `pm_station` (
`id` int(11) unsigned NOT NULL,
  `station` varchar(50) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `pm_station`
--

INSERT INTO `pm_station` (`id`, `station`) VALUES
(1, '部门经理'),
(2, '部门副经理'),
(3, '部门主管'),
(4, '部门助理'),
(5, '项目商务人员');

-- --------------------------------------------------------

--
-- 表的结构 `pm_task`
--

CREATE TABLE IF NOT EXISTS `pm_task` (
`id` int(11) unsigned NOT NULL,
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
  `c_time` int(11) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `pm_task`
--

INSERT INTO `pm_task` (`id`, `task_name`, `member_uid`, `leader_uid`, `work_id`, `project_id`, `remark`, `s_time`, `e_time`, `f_time`, `status`, `completion`, `c_time`) VALUES
(4, 't1', 11, 2, 9, 2, 'taks1', 1438531200, 1475424000, 0, 0, 0, 1470106091),
(5, 't2', 11, 2, 9, 2, 'taks2', 1438531200, 1475424000, 0, 0, 0, 1470106091),
(6, 't3', 11, 2, 9, 2, 'taks3', 1438531200, 1475424000, 0, 0, 0, 1470106091);

-- --------------------------------------------------------

--
-- 表的结构 `pm_user`
--

CREATE TABLE IF NOT EXISTS `pm_user` (
`id` int(11) unsigned NOT NULL,
  `username` varchar(50) NOT NULL,
  `truename` varchar(50) NOT NULL,
  `phone` int(11) unsigned NOT NULL,
  `position_id` int(11) unsigned NOT NULL COMMENT '职务',
  `station_id` int(11) unsigned NOT NULL COMMENT '岗位',
  `work_place_id` int(11) unsigned NOT NULL COMMENT '工作地点'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `pm_user`
--

INSERT INTO `pm_user` (`id`, `username`, `truename`, `phone`, `position_id`, `station_id`, `work_place_id`) VALUES
(1, 'admin', '超级管理员', 0, 0, 0, 0),
(2, 'messi', '梅西', 123123, 7, 5, 4),
(6, 'messikiller', 'messi', 123123, 7, 5, 4),
(10, 'hello', '你好', 123123, 7, 2, 3),
(11, 'zhangsan', '张三', 1234456, 3, 4, 3);

-- --------------------------------------------------------

--
-- 表的结构 `pm_work`
--

CREATE TABLE IF NOT EXISTS `pm_work` (
`id` int(11) unsigned NOT NULL,
  `work_name` varchar(255) NOT NULL,
  `member_uid` int(11) unsigned NOT NULL,
  `leader_uid` int(11) unsigned NOT NULL,
  `project_id` int(11) unsigned NOT NULL,
  `remark` text,
  `s_time` int(11) unsigned NOT NULL DEFAULT '0',
  `e_time` int(11) unsigned NOT NULL DEFAULT '0',
  `f_time` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0-正常未启动，1-已启动，2-已结束',
  `c_time` int(11) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- 转存表中的数据 `pm_work`
--

INSERT INTO `pm_work` (`id`, `work_name`, `member_uid`, `leader_uid`, `project_id`, `remark`, `s_time`, `e_time`, `f_time`, `status`, `c_time`) VALUES
(7, 'w1', 10, 2, 2, 'work1', 1467302400, 1467734400, 0, 0, 1470023432),
(8, 'w2', 11, 2, 2, 'work2', 1467648000, 1468425600, 0, 0, 1470023432),
(9, 'w3', 11, 2, 2, 'work3', 1468080000, 1468771200, 0, 1, 1470023432);

-- --------------------------------------------------------

--
-- 表的结构 `pm_work_place`
--

CREATE TABLE IF NOT EXISTS `pm_work_place` (
`id` int(11) unsigned NOT NULL,
  `work_place` varchar(50) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `pm_work_place`
--

INSERT INTO `pm_work_place` (`id`, `work_place`) VALUES
(2, '商务中心'),
(3, '商务部'),
(4, '项目');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pm_auth`
--
ALTER TABLE `pm_auth`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_evaluation_records`
--
ALTER TABLE `pm_evaluation_records`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_position`
--
ALTER TABLE `pm_position`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_project`
--
ALTER TABLE `pm_project`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_sign_records`
--
ALTER TABLE `pm_sign_records`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_station`
--
ALTER TABLE `pm_station`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_task`
--
ALTER TABLE `pm_task`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_user`
--
ALTER TABLE `pm_user`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_work`
--
ALTER TABLE `pm_work`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_work_place`
--
ALTER TABLE `pm_work_place`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pm_auth`
--
ALTER TABLE `pm_auth`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `pm_evaluation_records`
--
ALTER TABLE `pm_evaluation_records`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pm_position`
--
ALTER TABLE `pm_position`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `pm_project`
--
ALTER TABLE `pm_project`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `pm_sign_records`
--
ALTER TABLE `pm_sign_records`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `pm_station`
--
ALTER TABLE `pm_station`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `pm_task`
--
ALTER TABLE `pm_task`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `pm_user`
--
ALTER TABLE `pm_user`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `pm_work`
--
ALTER TABLE `pm_work`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `pm_work_place`
--
ALTER TABLE `pm_work_place`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
