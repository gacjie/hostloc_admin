-- MySQL dump 10.13  Distrib 5.6.50, for Linux (aarch64)
--
-- Host: localhost    Database: hostloc_site
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
-- Table structure for table `cm_account`
--

DROP TABLE IF EXISTS `cm_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cm_account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '账户编号',
  `name` varchar(20) NOT NULL COMMENT '账户名称',
  `pass` varchar(30) NOT NULL COMMENT '账户密码',
  `grade` varchar(20) NOT NULL COMMENT '账户等级',
  `integral` int(11) NOT NULL COMMENT '账户积分',
  `money` int(11) NOT NULL COMMENT '账户金钱',
  `address_ids` varchar(200) NOT NULL COMMENT '虚拟地址',
  `user_agent_id` int(11) NOT NULL COMMENT '浏览器UA',
  `uptime` int(11) NOT NULL COMMENT '更新时间',
  `switch` varchar(3) NOT NULL COMMENT '账号开关',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='账户管理';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cm_account`
--

LOCK TABLES `cm_account` WRITE;
/*!40000 ALTER TABLE `cm_account` DISABLE KEYS */;
/*!40000 ALTER TABLE `cm_account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cm_address`
--

DROP TABLE IF EXISTS `cm_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cm_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '地址编号',
  `name` varchar(30) NOT NULL COMMENT '地址名称',
  `start` varchar(30) NOT NULL COMMENT '起始地址',
  `end` varchar(30) NOT NULL COMMENT '结束地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='虚拟地址';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cm_address`
--

LOCK TABLES `cm_address` WRITE;
/*!40000 ALTER TABLE `cm_address` DISABLE KEYS */;
INSERT INTO `cm_address` VALUES (1,'北京移动','111.13.0.0','111.13.255.255');
/*!40000 ALTER TABLE `cm_address` ENABLE KEYS */;
UNLOCK TABLES;

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
  `group_id` int(11) NOT NULL COMMENT '用户组',
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='管理员表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cm_admin`
--

LOCK TABLES `cm_admin` WRITE;
/*!40000 ALTER TABLE `cm_admin` DISABLE KEYS */;
INSERT INTO `cm_admin` VALUES (1,'admin','Admin','7948ed5e90386ae03163b0dba2b40571','9r9qar','/static/images/avatar.png','admin@admin.com',0,1600298414,'123.196.11.216',1492186163,1684905704,'04e8afb9-646c-4a41-8452-cd6330c3232b',1,'normal');
/*!40000 ALTER TABLE `cm_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cm_attachment`
--

DROP TABLE IF EXISTS `cm_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cm_attachment` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='附件表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cm_attachment`
--

LOCK TABLES `cm_attachment` WRITE;
/*!40000 ALTER TABLE `cm_attachment` DISABLE KEYS */;
/*!40000 ALTER TABLE `cm_attachment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cm_auth_group`
--

DROP TABLE IF EXISTS `cm_auth_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cm_auth_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色编号',
  `createtime` int(11) NOT NULL,
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` varchar(30) DEFAULT 'normal' COMMENT '状态',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '角色组',
  `rules` text COMMENT '权限',
  `remark` text COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='角色组管理';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cm_auth_group`
--

LOCK TABLES `cm_auth_group` WRITE;
/*!40000 ALTER TABLE `cm_auth_group` DISABLE KEYS */;
INSERT INTO `cm_auth_group` VALUES (1,1601170339,1684425167,'normal','超级管理员','0,8,1,6,4,5,2,3,7','超级管理员');
/*!40000 ALTER TABLE `cm_auth_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cm_auth_rule`
--

DROP TABLE IF EXISTS `cm_auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cm_auth_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '规则编号',
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
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COMMENT='节点表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cm_auth_rule`
--

LOCK TABLES `cm_auth_rule` WRITE;
/*!40000 ALTER TABLE `cm_auth_rule` DISABLE KEYS */;
INSERT INTO `cm_auth_rule` VALUES (1,1,0,'index/index','后台首页','fa-arrows','','',0,1684917660,1698029584,0,'normal',1),(2,1,1,'index/welcome','仪表盘','fa-arrows','','',0,1684917711,1684919550,1,'normal',1),(3,1,1,'index/clear','清理缓存','fa-arrows','','',0,1684917781,1684919561,1,'normal',1),(4,1,1,'index/user','账号信息','fa-arrows','','',0,1684917809,1684919572,1,'normal',1),(5,1,1,'index/modify_pass','修改密码','fa-arrows','','',0,1684917842,1684919582,1,'normal',1),(6,1,1,'index/logout','退出登陆','fa-arrows','','',0,1684917876,1684919593,1,'normal',1),(7,1,1,'agax/lang','语言','fa-arrows','','',0,1684917984,1684919605,1,'normal',1),(8,1,0,'Admin/auth','后台管理','fa-users','','',1,1684918064,NULL,999,'normal',1),(9,1,8,'AuthRule/index','菜单管理','fa-bars','','',1,1684918138,NULL,999,'normal',1),(10,1,9,'AuthRule/getList','菜单列表','fa-arrows','','',0,1684918219,NULL,999,'normal',1),(11,1,9,'AuthRule/add','添加菜单','fa-arrows','','',0,1684918248,NULL,999,'normal',1),(12,1,9,'AuthRule/edit','编辑菜单','fa-arrows','','',0,1684918276,NULL,999,'normal',1),(13,1,9,'AuthRule/delete','删除菜单','fa-arrows','','',0,1684918304,NULL,999,'normal',1),(14,1,9,'AuthRule/setNormal','设置正常','fa-arrows','','',0,1684918404,1684918807,999,'normal',1),(15,1,9,'AuthRule/setStop','设置停止','fa-arrows','','',0,1684918452,NULL,999,'normal',1),(16,1,8,'AuthGroup/index','角色管理','fa-users','','',1,1684918527,NULL,999,'normal',1),(17,1,16,'AuthGroup/getList','角色列表','fa-arrows','','',0,1684918564,NULL,999,'normal',1),(18,1,16,'AuthGroup/add','添加角色','fa-arrows','','',0,1684918603,NULL,999,'normal',1),(19,1,16,'AuthGroup/edit','编辑角色','fa-arrows','','',0,1684918633,NULL,999,'normal',1),(20,1,16,'AuthGroup/delete','删除角色','fa-arrows','','',0,1684918665,NULL,999,'normal',1),(21,1,16,'AuthGroup/setNormal','设置正常','fa-arrows','','',0,1684918751,NULL,999,'normal',1),(22,1,16,'AuthGroup/setStop','设置停止','fa-arrows','','',0,1684918787,NULL,999,'normal',1),(23,1,16,'AuthGroup/getAuthList','权限列表','fa-arrows','','',0,1684918850,1684918921,999,'normal',1),(24,1,16,'AuthGroup/auth','设置权限','fa-arrows','','',0,1684918905,NULL,999,'normal',1),(25,1,8,'Admin/index','人员管理','fa-users','','',1,1684918971,NULL,999,'normal',1),(26,1,25,'Admin/getList','人员列表','fa-arrows','','',0,1684919012,NULL,999,'normal',1),(27,1,25,'Admin/add','添加人员','fa-arrows','','',0,1684919049,1684919061,999,'normal',1),(28,1,25,'Admin/edit','人员编辑','fa-arrows','','',0,1684919082,NULL,999,'normal',1),(29,1,25,'Admin/delete','人员删除','fa-arrows','','',0,1684919106,NULL,999,'normal',1),(30,1,25,'Admin/setNormal','设置正常','fa-arrows','','',0,1684919155,NULL,999,'normal',1),(31,1,25,'Admin/setStop','设置停止','fa-arrows','','',0,1684919177,1684919191,999,'normal',1),(32,1,25,'Admin/getAuthGroupOptions','角色列表','fa-arrows','','',0,1684919230,1684919243,999,'normal',1),(33,1,0,'general.Config/index','系统设置','fa-cog','','',1,1684919406,NULL,999,'normal',1),(34,1,33,'general.Config/add','添加配置','fa-arrows','','',0,1684919456,NULL,999,'normal',1),(35,1,33,'general.Config/edit','编辑配置','fa-arrows','','',0,1684919490,NULL,999,'normal',1),(36,1,33,'general.Config/del','删除配置','fa-arrows','','',0,1684919521,1684919531,999,'normal',1),(40,1,0,'account/index','账户管理','fa-book','','',1,1713499519,1713499723,0,'normal',1),(41,1,40,'account/add','添加','','','',0,1713499519,1713499519,0,'normal',1),(42,1,40,'account/edit','编辑','','','',0,1713499519,1713499519,0,'normal',1),(43,1,40,'account/delete','删除','','','',0,1713499519,1713499519,0,'normal',1),(44,1,40,'account/sw','开关','','','',0,1713499519,1713499519,0,'normal',1),(45,1,0,'address/index','虚拟地址','fa-address-card-o','','',1,1713499522,1713499692,0,'normal',1),(46,1,45,'address/add','添加','','','',0,1713499522,1713499522,0,'normal',1),(47,1,45,'address/edit','编辑','','','',0,1713499522,1713499522,0,'normal',1),(48,1,45,'address/delete','删除','','','',0,1713499522,1713499522,0,'normal',1),(49,1,45,'address/sw','开关','','','',0,1713499522,1713499522,0,'normal',1),(50,1,0,'user_agent/index','浏览器UA','fa-chrome','','',1,1713499524,1713499640,0,'normal',1),(51,1,50,'user_agent/add','添加','','','',0,1713499524,1713499524,0,'normal',1),(52,1,50,'user_agent/edit','编辑','','','',0,1713499524,1713499524,0,'normal',1),(53,1,50,'user_agent/delete','删除','','','',0,1713499524,1713499524,0,'normal',1),(54,1,50,'user_agent/sw','开关','','','',0,1713499524,1713499524,0,'normal',1);
/*!40000 ALTER TABLE `cm_auth_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cm_config`
--

DROP TABLE IF EXISTS `cm_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cm_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置编号',
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统配置';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cm_config`
--

LOCK TABLES `cm_config` WRITE;
/*!40000 ALTER TABLE `cm_config` DISABLE KEYS */;
INSERT INTO `cm_config` VALUES (1,'name','basic','后台名称','请填写站点名称','string','Hostloc账号管理器','','required','class=\"layui-input\"',NULL),(2,'version','basic','系统版本','','string','Build 240419','{\"1\":\"\"}','','class=\"layui-input\"',NULL),(3,'bbs_url','basic','论坛官网','','string','https://hostloc.com','{\"1\":\"\"}','','class=\"layui-input\"',NULL),(4,'userid','basic','会员编号(范围)','','string','1,72000','{\"1\":\"\"}','','class=\"layui-input\"',NULL),(5,'hour','basic','整点执行(0-23)','','string','6','{\"1\":\"\"}','','class=\"layui-input\"',NULL);
/*!40000 ALTER TABLE `cm_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cm_user_agent`
--

DROP TABLE IF EXISTS `cm_user_agent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cm_user_agent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'UA编号',
  `name` varchar(20) NOT NULL COMMENT '浏览器',
  `system` varchar(20) NOT NULL COMMENT '系统平台',
  `info` varchar(200) NOT NULL COMMENT 'UA信息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='浏览器UA';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cm_user_agent`
--

LOCK TABLES `cm_user_agent` WRITE;
/*!40000 ALTER TABLE `cm_user_agent` DISABLE KEYS */;
INSERT INTO `cm_user_agent` VALUES (1,'360EE 21','Windows','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36'),(2,'Edge','Windows','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET4.0C; .NET4.0E; rv:11.0) like Gecko'),(3,'Chrome 58','Winodws','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36'),(4,'Firefox 53','Windows','Mozilla/5.0 (Windows NT 6.1; WOW64; rv:53.0) Gecko/20100101 Firefox/53.0'),(5,'QQBrowser','Windows','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.104 Safari/537.36 Core/1.53.2669.400 QQBrowser/9.6.10990.400');
/*!40000 ALTER TABLE `cm_user_agent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'hostloc_site'
--

--
-- Dumping routines for database 'hostloc_site'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-04-19 14:51:55
