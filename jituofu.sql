-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2014 年 08 月 07 日 10:46
-- 服务器版本: 5.5.32
-- PHP 版本: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `jituofu`
--
CREATE DATABASE IF NOT EXISTS `jituofu` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `jituofu`;

-- --------------------------------------------------------

--
-- 表的结构 `cashier`
--

CREATE TABLE IF NOT EXISTS `cashier` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '交易ID',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `pid` int(11) NOT NULL COMMENT '商品ID',
  `selling_count` float NOT NULL COMMENT '销售数量',
  `selling_price` float NOT NULL COMMENT '销售单价',
  `who` varchar(40) DEFAULT NULL COMMENT '销售员',
  `date` datetime NOT NULL COMMENT '销售日期',
  `remark` text COMMENT '备注',
  `merge_id` int(11) DEFAULT NULL COMMENT '合并记账的id,如果有这个id,表示该条记录是属于某条合并记账流水',
  `price` float NOT NULL COMMENT '商品成本价',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='记账台' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `check_code`
--

CREATE TABLE IF NOT EXISTS `check_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `check_code` varchar(255) NOT NULL COMMENT '校验码',
  `time` int(11) NOT NULL COMMENT '时间戳',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='已下发的校验码' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `device`
--

CREATE TABLE IF NOT EXISTS `device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `uuid` varchar(128) NOT NULL COMMENT '设备uuid',
  `token` varchar(64) DEFAULT NULL COMMENT '设备的push token',
  `name` varchar(50) DEFAULT NULL COMMENT '设备名称',
  `cookie` varchar(255) DEFAULT NULL COMMENT '设备上的cookie',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `feedback`
--

CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL COMMENT '反馈内容',
  `pics` text COMMENT '截图',
  `time` datetime NOT NULL COMMENT '创建时间',
  `author` varchar(10) DEFAULT NULL COMMENT '反馈内容创建者',
  `email` varchar(50) DEFAULT NULL COMMENT '反馈者的邮箱',
  `phone` varchar(50) DEFAULT NULL COMMENT '反馈者手机',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dir` varchar(50) NOT NULL COMMENT '目录',
  `name` varchar(255) NOT NULL COMMENT '资源名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `help`
--

CREATE TABLE IF NOT EXISTS `help` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL COMMENT '标题',
  `yes` int(11) NOT NULL COMMENT '觉得这条帮助有用',
  `no` int(11) NOT NULL COMMENT '觉得这条帮助无用',
  `content` text NOT NULL COMMENT '帮助的内容',
  `alias` varchar(50) NOT NULL COMMENT '别名',
  `createtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='帮助内容' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `marketing`
--

CREATE TABLE IF NOT EXISTS `marketing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `productVersion` varchar(10) DEFAULT NULL COMMENT '产品版本',
  `productId` varchar(10) DEFAULT NULL COMMENT '产品ID',
  `channelId` varchar(10) DEFAULT NULL COMMENT '产品渠道',
  `network` varchar(10) DEFAULT NULL COMMENT '网络类型',
  `display` varchar(10) DEFAULT NULL COMMENT '设备分辨率',
  `model` varchar(255) DEFAULT NULL COMMENT '手机型号',
  `os` varchar(255) DEFAULT NULL COMMENT '操作系统',
  `imsi` varchar(255) DEFAULT NULL COMMENT '手机卡',
  `imei` varchar(255) DEFAULT NULL COMMENT '手机imei',
  `mac` varchar(255) DEFAULT NULL COMMENT '手机mac地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='市场/运营/数据分析' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `merge_cashier`
--

CREATE TABLE IF NOT EXISTS `merge_cashier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '产品ID',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `name` varchar(40) NOT NULL COMMENT '产品名称',
  `count` float NOT NULL DEFAULT '0' COMMENT '产品数量',
  `from` varchar(50) DEFAULT NULL COMMENT '产品采购源',
  `man` varchar(40) DEFAULT NULL COMMENT '采购人',
  `price` double NOT NULL COMMENT '产品价格',
  `pic` varchar(200) DEFAULT NULL COMMENT '产品图片',
  `date` datetime NOT NULL COMMENT '采购日期',
  `type` int(11) NOT NULL COMMENT '产品类型',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '1表示产品正常显示； 0表示产品不在页面显示或删除；',
  `remark` text COMMENT '备注信息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `rent`
--

CREATE TABLE IF NOT EXISTS `rent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `price` double NOT NULL COMMENT '租金价格',
  `date` int(11) NOT NULL COMMENT '录入租金的日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='租金' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `return_sale`
--

CREATE TABLE IF NOT EXISTS `return_sale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL COMMENT '销售记录id',
  `reason` text NOT NULL COMMENT '退货原因',
  `remark` text COMMENT '备注',
  `who` varchar(40) DEFAULT NULL COMMENT '谁退的货',
  `date` datetime NOT NULL,
  `count` float NOT NULL COMMENT '退货数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- 表的结构 `rib_users`
--

CREATE TABLE IF NOT EXISTS `rib_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `password` varchar(225) NOT NULL,
  `email` varchar(150) NOT NULL,
  `from` varchar(10) DEFAULT NULL COMMENT '用户来源，各个社交平台',
  `location` varchar(50) DEFAULT NULL COMMENT '用户的注册经玮坐标值',
  `last_sign_in_date` datetime DEFAULT NULL,
  `registered_date` datetime NOT NULL COMMENT '注册时间',
  `role_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `software_version`
--

CREATE TABLE IF NOT EXISTS `software_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(15) NOT NULL COMMENT '产品版本',
  `update_log` text NOT NULL COMMENT '更新日志',
  `is_last` tinyint(1) NOT NULL COMMENT '是否是最新版本',
  `url` text NOT NULL COMMENT '下载地址',
  `date` datetime NOT NULL,
  `platform` int(1) NOT NULL COMMENT '1是Android；0是iOS',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `store_settings`
--

CREATE TABLE IF NOT EXISTS `store_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `tip_rent` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启每日录入租金',
  `name` varchar(50) DEFAULT NULL COMMENT '商户名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='商店设置' AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- 表的结构 `types`
--

CREATE TABLE IF NOT EXISTS `types` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品分类ID',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `name` varchar(50) NOT NULL COMMENT '商品分类名称',
  `parent_id` int(11) DEFAULT NULL,
  `child_id` int(11) DEFAULT NULL,
  `time` datetime NOT NULL COMMENT '分类创建时间 ',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用,1为启用,为不停用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='商品分类' AUTO_INCREMENT=172 ;

-- --------------------------------------------------------

--
-- 表的结构 `user_role`
--

CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '角色的名称',
  `desc` varchar(150) DEFAULT NULL COMMENT '导航配置',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态为1表示正常',
  PRIMARY KEY (`name`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户角色表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `user_role`
--

INSERT INTO `user_role` (`id`, `name`, `desc`, `status`) VALUES
(1, 'normal', '普通用户', 1);

-- --------------------------------------------------------

--
-- 表的结构 `wse`
--

CREATE TABLE IF NOT EXISTS `wse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address` text NOT NULL COMMENT '邮件地址',
  `subject` text NOT NULL COMMENT '邮件主题',
  `content` text NOT NULL COMMENT '邮件内容',
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='wait send email' AUTO_INCREMENT=10 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
