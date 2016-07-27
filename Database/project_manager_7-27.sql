-- phpMyAdmin SQL Dump
-- version 4.2.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2016-07-27 18:56:00
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `pm_auth`
--

INSERT INTO `pm_auth` (`id`, `user_id`, `password`, `level`) VALUES
(1, 1, '21232f297a57a5a743894a0e4a801fc3', 0);

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
-- 表的结构 `pm_project`
--

CREATE TABLE IF NOT EXISTS `pm_project` (
`id` int(11) unsigned NOT NULL,
  `project_name` varchar(50) NOT NULL,
  `leader_uid` int(11) unsigned NOT NULL,
  `s_time` int(11) unsigned NOT NULL,
  `e_time` int(11) unsigned NOT NULL,
  `finish_time` int(11) unsigned NOT NULL COMMENT '完成项目实际消耗的时间',
  `remark` text,
  `c_time` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pm_project_work`
--

CREATE TABLE IF NOT EXISTS `pm_project_work` (
`id` int(11) unsigned NOT NULL,
  `project_id` int(11) unsigned NOT NULL,
  `work_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pm_sign_records`
--

CREATE TABLE IF NOT EXISTS `pm_sign_records` (
`id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `ip` int(20) unsigned NOT NULL,
  `c_time` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pm_user`
--

CREATE TABLE IF NOT EXISTS `pm_user` (
`id` int(11) unsigned NOT NULL,
  `username` varchar(50) NOT NULL,
  `phone` int(11) unsigned NOT NULL,
  `position` varchar(50) NOT NULL COMMENT '职务',
  `station` varchar(50) NOT NULL COMMENT '岗位',
  `work_place` varchar(50) NOT NULL COMMENT '工作地点'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `pm_user`
--

INSERT INTO `pm_user` (`id`, `username`, `phone`, `position`, `station`, `work_place`) VALUES
(1, 'admin', 0, '0', '0', '0');

-- --------------------------------------------------------

--
-- 表的结构 `pm_work`
--

CREATE TABLE IF NOT EXISTS `pm_work` (
`id` int(11) unsigned NOT NULL,
  `work_name` varchar(50) NOT NULL,
  `content` varchar(50) NOT NULL,
  `s_time` int(11) unsigned NOT NULL,
  `e_time` int(11) unsigned NOT NULL,
  `c_time` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pm_work_member`
--

CREATE TABLE IF NOT EXISTS `pm_work_member` (
`id` int(11) unsigned NOT NULL,
  `work_id` int(11) unsigned NOT NULL,
  `member_uid` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
-- Indexes for table `pm_project`
--
ALTER TABLE `pm_project`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_project_work`
--
ALTER TABLE `pm_project_work`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_sign_records`
--
ALTER TABLE `pm_sign_records`
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
-- Indexes for table `pm_work_member`
--
ALTER TABLE `pm_work_member`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pm_auth`
--
ALTER TABLE `pm_auth`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `pm_evaluation_records`
--
ALTER TABLE `pm_evaluation_records`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pm_project`
--
ALTER TABLE `pm_project`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pm_project_work`
--
ALTER TABLE `pm_project_work`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pm_sign_records`
--
ALTER TABLE `pm_sign_records`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pm_user`
--
ALTER TABLE `pm_user`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `pm_work`
--
ALTER TABLE `pm_work`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pm_work_member`
--
ALTER TABLE `pm_work_member`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
