-- MySQL dump 10.13  Distrib 5.6.50, for Linux (x86_64)
--
-- Host: localhost    Database: loc_318760367_xy
-- ------------------------------------------------------
-- Server version	5.6.50-log

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
-- Table structure for table `cm_admin`
--

DROP TABLE IF EXISTS `cm_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cm_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码盐',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `loginfailure` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '失败次数',
  `logintime` int(10) DEFAULT NULL COMMENT '登录时间',
  `loginip` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '登录IP',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `token` varchar(59) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Session标识',
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='管理员表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cm_admin`
--

LOCK TABLES `cm_admin` WRITE;
/*!40000 ALTER TABLE `cm_admin` DISABLE KEYS */;
INSERT INTO `cm_admin` VALUES (1,'admin','Admin','2f3877ff049ffacfc020956172b4ac7c','21jcmb','/static/images/avatar.png','admin@admin.com',0,1600298414,'123.196.11.216',1492186163,1601174630,'04e8afb9-646c-4a41-8452-cd6330c3232b','normal');
/*!40000 ALTER TABLE `cm_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cm_attachment`
--

DROP TABLE IF EXISTS `cm_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cm_attachment` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '物理路径',
  `imagewidth` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '宽度',
  `imageheight` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '高度',
  `imagetype` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片类型',
  `imageframes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '图片帧数',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `mimetype` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'mime类型',
  `extparam` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '透传数据',
  `createtime` int(10) DEFAULT NULL COMMENT '创建日期',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `uploadtime` int(10) DEFAULT NULL COMMENT '上传时间',
  `storage` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local' COMMENT '存储位置',
  `sha1` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='附件表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cm_attachment`
--

LOCK TABLES `cm_attachment` WRITE;
/*!40000 ALTER TABLE `cm_attachment` DISABLE KEYS */;
INSERT INTO `cm_attachment` VALUES (1,0,0,'/storage/topic/20210920/472fe4afe8b41d8f02fdca80592d08d7.png','400','400','image/png',0,12259,'image/png','',1632150297,1632150297,1632150297,'/storage/topic/20210920/472fe4afe8b41d8f02fdca80592d08d7.png','cf9b8b05cfbc48d3701aca946bbb48f566053555'),(2,0,0,'/storage/topic/20210920/9d401159145265888ff4f4068c232224.png','400','400','image/png',0,12259,'image/png','',1632150401,1632150401,1632150401,'/storage/topic/20210920/9d401159145265888ff4f4068c232224.png','cf9b8b05cfbc48d3701aca946bbb48f566053555'),(3,0,0,'/storage/topic/20210920/d47bf18f63cb3893c24c5a814583c8b3.png','400','400','image/png',0,12259,'image/png','',1632150413,1632150413,1632150413,'/storage/topic/20210920/d47bf18f63cb3893c24c5a814583c8b3.png','cf9b8b05cfbc48d3701aca946bbb48f566053555');
/*!40000 ALTER TABLE `cm_attachment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cm_auth_group`
--

DROP TABLE IF EXISTS `cm_auth_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cm_auth_group` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `createtime` int(11) NOT NULL,
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` varchar(30) DEFAULT 'normal' COMMENT '状态',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '角色组',
  `rules` text COMMENT '权限',
  `remark` text COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='角色组管理';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cm_auth_group`
--

LOCK TABLES `cm_auth_group` WRITE;
/*!40000 ALTER TABLE `cm_auth_group` DISABLE KEYS */;
INSERT INTO `cm_auth_group` VALUES (1,1601170339,1601172768,'normal','超级管理员','536,556,553,554,555,537,557,538,541,547,548,549,550,542,','超级管理员'),(4,1601170436,1601181256,'normal','插件管理员','0,536,538,541,547,548,549,550,542,','这里是备注');
/*!40000 ALTER TABLE `cm_auth_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cm_auth_group_access`
--

DROP TABLE IF EXISTS `cm_auth_group_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cm_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  `createtime` int(11) DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(11) DEFAULT '0' COMMENT '修改时间',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cm_auth_group_access`
--

LOCK TABLES `cm_auth_group_access` WRITE;
/*!40000 ALTER TABLE `cm_auth_group_access` DISABLE KEYS */;
INSERT INTO `cm_auth_group_access` VALUES (1,1,0,0),(2,4,0,0);
/*!40000 ALTER TABLE `cm_auth_group_access` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cm_auth_rule`
--

DROP TABLE IF EXISTS `cm_auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cm_auth_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) NOT NULL DEFAULT '1',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '规则名称',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '规则名称',
  `icon` varchar(50) NOT NULL DEFAULT 'fa-circle-o' COMMENT '图标',
  `condition` varchar(255) NOT NULL DEFAULT '' COMMENT '条件',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `ismenu` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为菜单',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` varchar(30) NOT NULL DEFAULT 'normal' COMMENT '状态',
  `auth_open` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE,
  KEY `pid` (`pid`),
  KEY `weigh` (`weigh`)
) ENGINE=InnoDB AUTO_INCREMENT=580 DEFAULT CHARSET=utf8 COMMENT='节点表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cm_auth_rule`
--

LOCK TABLES `cm_auth_rule` WRITE;
/*!40000 ALTER TABLE `cm_auth_rule` DISABLE KEYS */;
INSERT INTO `cm_auth_rule` VALUES (537,1,556,'admin/index','人员管理','fa-group','','',1,NULL,1601167961,2,'normal',1),(553,1,556,'AuthRule/index','菜单管理','fa-bars','','',1,NULL,1601167951,1,'normal',1),(554,1,553,'AuthRule/add','菜单添加','fa-add','','这里是备注',0,1601122750,NULL,100,'normal',1),(555,1,553,'AuthRule/edit','菜单编辑','fa-edit','','',0,1601122878,1601123339,100,'normal',1),(556,1,0,'admin/Auth','权限管理','fa-group','','',1,1601167936,NULL,1,'normal',1),(557,1,556,'AuthGroup/index','角色管理','fa-group','','',1,1601168043,NULL,100,'normal',1),(562,1,0,'general.Config/index','系统设置','fa-gear','','',1,NULL,NULL,16,'normal',1),(572,1,0,'hostloc/index','账号管理','fa-list','','',1,1632049116,1632049135,0,'normal',1),(573,1,572,'hostloc/add','添加','','','',0,1632049116,1632049116,0,'normal',1),(574,1,572,'hostloc/edit','编辑 ','','','',0,1632049116,1632049116,0,'normal',1),(575,1,572,'hostloc/del','删除','','','',0,1632049116,1632049116,0,'normal',1),(576,1,0,'hanglog/index','挂机日志','fa-list','','',1,1632051622,1632051655,0,'normal',1),(577,1,576,'hanglog/add','添加','','','',0,1632051622,1632051622,0,'normal',1),(578,1,576,'hanglog/edit','编辑 ','','','',0,1632051622,1632051622,0,'normal',1),(579,1,576,'hanglog/del','删除','','','',0,1632051622,1632051622,0,'normal',1);
/*!40000 ALTER TABLE `cm_auth_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cm_config`
--

DROP TABLE IF EXISTS `cm_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cm_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '变量名',
  `group` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '分组',
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '变量标题',
  `tip` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '变量描述',
  `type` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '类型:string,text,int,bool,array,datetime,date,file',
  `value` text COLLATE utf8mb4_unicode_ci COMMENT '变量值',
  `content` text COLLATE utf8mb4_unicode_ci COMMENT '变量字典数据',
  `rule` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '验证规则',
  `extend` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '扩展属性',
  `setting` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '配置',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统配置';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cm_config`
--

LOCK TABLES `cm_config` WRITE;
/*!40000 ALTER TABLE `cm_config` DISABLE KEYS */;
INSERT INTO `cm_config` VALUES (1,'name','basic','站点名称','请填写站点名称','string','HostLoc账号管理器','','required','class=\"layui-input\"',NULL),(2,'beian','basic','备案号','粤ICP备15000000号-1','string','','','','class=\"layui-input\"',NULL),(18,'baidumap','map','百度API','请配置百度地图API','string','api',NULL,'','class=\"layui-input\"',NULL),(20,'baidumapscrect','map','百度SECRET','请配置百度地图','string','secret',NULL,'','',NULL),(21,'hostloc','basic','hostloc官网','https://hostloc.com','string','https://hostloc.com','{\"1\":\"\"}','','class=\"layui-input\"',NULL),(22,'sleep','basic','挂机间隔(秒)','','string','10','{\"1\":\"\"}','','class=\"layui-input\"',NULL);
/*!40000 ALTER TABLE `cm_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cm_hanglog`
--

DROP TABLE IF EXISTS `cm_hanglog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cm_hanglog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志编号',
  `hostloc_id` int(11) NOT NULL COMMENT '挂机账号',
  `uptime` int(11) NOT NULL COMMENT '挂机时间',
  `grade` text NOT NULL COMMENT '当前等级',
  `integral` int(11) NOT NULL COMMENT '当前积分',
  `money` int(11) NOT NULL COMMENT '当前金钱',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='挂机日志';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cm_hanglog`
--

LOCK TABLES `cm_hanglog` WRITE;
/*!40000 ALTER TABLE `cm_hanglog` DISABLE KEYS */;
/*!40000 ALTER TABLE `cm_hanglog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cm_hostloc`
--

DROP TABLE IF EXISTS `cm_hostloc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cm_hostloc` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '账号编号',
  `name` text NOT NULL COMMENT '账号名称',
  `pass` text NOT NULL COMMENT '账号密码',
  `grade` text NOT NULL COMMENT '账号等级',
  `integral` int(11) NOT NULL COMMENT '账号积分',
  `money` int(11) NOT NULL COMMENT '账号金钱',
  `uptime` int(11) NOT NULL COMMENT '更新时间',
  `switch` text NOT NULL COMMENT '账号开关',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='账号管理';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cm_hostloc`
--

LOCK TABLES `cm_hostloc` WRITE;
/*!40000 ALTER TABLE `cm_hostloc` DISABLE KEYS */;
/*!40000 ALTER TABLE `cm_hostloc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'loc_318760367_xy'
--

--
-- Dumping routines for database 'loc_318760367_xy'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-09-20 23:35:17
