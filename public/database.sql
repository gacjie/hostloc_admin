-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2022-04-24 12:41:19
-- 服务器版本： 5.5.62-log
-- PHP 版本： 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `hostloc_co`
--

-- --------------------------------------------------------

--
-- 表的结构 `cm_admin`
--

CREATE TABLE `cm_admin` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '管理编号',
  `username` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码盐',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `loginfailure` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '失败次数',
  `logintime` int(10) DEFAULT NULL COMMENT '登录时间',
  `loginip` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '登录IP',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `token` varchar(59) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Session标识',
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='管理员表';

--
-- 转存表中的数据 `cm_admin`
--

INSERT INTO `cm_admin` (`id`, `username`, `nickname`, `password`, `salt`, `avatar`, `email`, `loginfailure`, `logintime`, `loginip`, `createtime`, `updatetime`, `token`, `status`) VALUES
(1, 'admin', 'Admin', '672fb3504ac0824e51a2768c776d684e', 'kt54st', '/static/images/avatar.png', 'admin@admin.com', 0, 1600298414, '123.196.11.216', 1492186163, 1601174630, '04e8afb9-646c-4a41-8452-cd6330c3232b', 'normal');

-- --------------------------------------------------------

--
-- 表的结构 `cm_attachment`
--

CREATE TABLE `cm_attachment` (
  `id` int(20) UNSIGNED NOT NULL COMMENT 'ID',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '会员ID',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '物理路径',
  `imagewidth` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '宽度',
  `imageheight` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '高度',
  `imagetype` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片类型',
  `imageframes` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '图片帧数',
  `filesize` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文件大小',
  `mimetype` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'mime类型',
  `extparam` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '透传数据',
  `createtime` int(10) DEFAULT NULL COMMENT '创建日期',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `uploadtime` int(10) DEFAULT NULL COMMENT '上传时间',
  `storage` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local' COMMENT '存储位置',
  `sha1` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件 sha1编码'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='附件表';

-- --------------------------------------------------------

--
-- 表的结构 `cm_auth_group`
--

CREATE TABLE `cm_auth_group` (
  `id` int(8) UNSIGNED NOT NULL,
  `createtime` int(11) NOT NULL,
  `updatetime` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` varchar(30) DEFAULT 'normal' COMMENT '状态',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '角色组',
  `rules` text COMMENT '权限',
  `remark` text COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色组管理';

--
-- 转存表中的数据 `cm_auth_group`
--

INSERT INTO `cm_auth_group` (`id`, `createtime`, `updatetime`, `status`, `title`, `rules`, `remark`) VALUES
(1, 1601170339, 1601172768, 'normal', '超级管理员', '536,556,553,554,555,537,557,538,541,547,548,549,550,542,', '超级管理员'),
(4, 1601170436, 1601181256, 'normal', '插件管理员', '0,536,538,541,547,548,549,550,542,', '这里是备注');

-- --------------------------------------------------------

--
-- 表的结构 `cm_auth_group_access`
--

CREATE TABLE `cm_auth_group_access` (
  `uid` mediumint(8) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL,
  `createtime` int(11) DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(11) DEFAULT '0' COMMENT '修改时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cm_auth_group_access`
--

INSERT INTO `cm_auth_group_access` (`uid`, `group_id`, `createtime`, `updatetime`) VALUES
(1, 1, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `cm_auth_rule`
--

CREATE TABLE `cm_auth_rule` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '规则名称',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '规则名称',
  `icon` varchar(50) NOT NULL DEFAULT 'fa-circle-o' COMMENT '图标',
  `condition` varchar(255) NOT NULL DEFAULT '' COMMENT '条件',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `ismenu` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否为菜单',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` varchar(30) NOT NULL DEFAULT 'normal' COMMENT '状态',
  `auth_open` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='节点表';

--
-- 转存表中的数据 `cm_auth_rule`
--

INSERT INTO `cm_auth_rule` (`id`, `type`, `pid`, `name`, `title`, `icon`, `condition`, `remark`, `ismenu`, `createtime`, `updatetime`, `weigh`, `status`, `auth_open`) VALUES
(537, 1, 556, 'admin/index', '人员管理', 'fa-group', '', '', 1, NULL, 1601167961, 2, 'normal', 1),
(553, 1, 556, 'AuthRule/index', '菜单管理', 'fa-bars', '', '', 1, NULL, 1601167951, 1, 'normal', 1),
(554, 1, 553, 'AuthRule/add', '菜单添加', 'fa-add', '', '这里是备注', 0, 1601122750, NULL, 100, 'normal', 1),
(555, 1, 553, 'AuthRule/edit', '菜单编辑', 'fa-edit', '', '', 0, 1601122878, 1601123339, 100, 'normal', 1),
(556, 1, 0, 'admin/Auth', '权限管理', 'fa-group', '', '', 1, 1601167936, NULL, 1, 'normal', 1),
(557, 1, 556, 'AuthGroup/index', '角色管理', 'fa-group', '', '', 1, 1601168043, NULL, 100, 'normal', 1),
(562, 1, 0, 'general.Config/index', '系统设置', 'fa-gear', '', '', 1, NULL, NULL, 16, 'normal', 1),
(572, 1, 0, 'hostloc/index', '账号管理', 'fa-list', '', '', 1, 1632049116, 1632049135, 0, 'normal', 1),
(573, 1, 572, 'hostloc/add', '添加', '', '', '', 0, 1632049116, 1632049116, 0, 'normal', 1),
(574, 1, 572, 'hostloc/edit', '编辑 ', '', '', '', 0, 1632049116, 1632049116, 0, 'normal', 1),
(575, 1, 572, 'hostloc/del', '删除', '', '', '', 0, 1632049116, 1632049116, 0, 'normal', 1),
(576, 1, 0, 'hanglog/index', '挂机日志', 'fa-list', '', '', 1, 1632051622, 1632051655, 0, 'normal', 1),
(577, 1, 576, 'hanglog/add', '添加', '', '', '', 0, 1632051622, 1632051622, 0, 'normal', 1),
(578, 1, 576, 'hanglog/edit', '编辑 ', '', '', '', 0, 1632051622, 1632051622, 0, 'normal', 1),
(579, 1, 576, 'hanglog/del', '删除', '', '', '', 0, 1632051622, 1632051622, 0, 'normal', 1);

-- --------------------------------------------------------

--
-- 表的结构 `cm_config`
--

CREATE TABLE `cm_config` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '配置编号',
  `name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '变量名',
  `group` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '分组',
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '变量标题',
  `tip` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '变量描述',
  `type` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '类型:string,text,int,bool,array,datetime,date,file',
  `value` text COLLATE utf8mb4_unicode_ci COMMENT '变量值',
  `content` text COLLATE utf8mb4_unicode_ci COMMENT '变量字典数据',
  `rule` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '验证规则',
  `extend` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '扩展属性',
  `setting` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '配置'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统配置';

--
-- 转存表中的数据 `cm_config`
--

INSERT INTO `cm_config` (`id`, `name`, `group`, `title`, `tip`, `type`, `value`, `content`, `rule`, `extend`, `setting`) VALUES
(1, 'name', 'basic', '站点名称', '请填写站点名称', 'string', 'HostLoc账号管理器', '', 'required', 'class=\"layui-input\"', NULL),
(2, 'beian', 'basic', '备案号', '粤ICP备15000000号-1', 'string', '', '', '', 'class=\"layui-input\"', NULL),
(3, 'hostloc', 'basic', 'hostloc官网', 'https://hostloc.com', 'string', 'https://hostloc.com', '{\"1\":\"\"}', '', 'class=\"layui-input\"', NULL),
(4, 'sleep', 'basic', '刷分间隔(秒)', '', 'string', '10', '{\"1\":\"\"}', '', 'class=\"layui-input\"', NULL),
(5, 'limit', 'basic', '执行限额', '', 'string', '1', '{\"1\":\"\"}', '', 'class=\"layui-input\"', NULL),
(6, 'Interval', 'basic', '执行间隔(秒)', '', 'string', '86400', '{\"1\":\"\"}', '', 'class=\"layui-input\"', NULL),
(7, 'userid', 'basic', '会员编号(范围)', '', 'string', '0,50000', '{\"1\":\"\"}', '', 'class=\"layui-input\"', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `cm_hanglog`
--

CREATE TABLE `cm_hanglog` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '日志编号',
  `hostloc_id` int(11) NOT NULL COMMENT '挂机账号',
  `uptime` int(11) NOT NULL COMMENT '挂机时间',
  `grade` text NOT NULL COMMENT '当前等级',
  `integral` int(11) NOT NULL COMMENT '获得积分',
  `money` int(11) NOT NULL COMMENT '获得金钱',
  `status` text NOT NULL COMMENT '挂机状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='挂机日志';

-- --------------------------------------------------------

--
-- 表的结构 `cm_hostloc`
--

CREATE TABLE `cm_hostloc` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '账号编号',
  `name` text NOT NULL COMMENT '账号名称',
  `pass` text NOT NULL COMMENT '账号密码',
  `grade` text NOT NULL COMMENT '账号等级',
  `integral` int(11) NOT NULL COMMENT '账号积分',
  `money` int(11) NOT NULL COMMENT '账号金钱',
  `uptime` int(11) NOT NULL COMMENT '更新时间',
  `switch` text NOT NULL COMMENT '账号开关'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='账号管理';

--
-- 转储表的索引
--

--
-- 表的索引 `cm_admin`
--
ALTER TABLE `cm_admin`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cm_attachment`
--
ALTER TABLE `cm_attachment`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cm_auth_group`
--
ALTER TABLE `cm_auth_group`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cm_auth_group_access`
--
ALTER TABLE `cm_auth_group_access`
  ADD UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `group_id` (`group_id`);

--
-- 表的索引 `cm_auth_rule`
--
ALTER TABLE `cm_auth_rule`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`) USING BTREE,
  ADD KEY `pid` (`pid`),
  ADD KEY `weigh` (`weigh`);

--
-- 表的索引 `cm_config`
--
ALTER TABLE `cm_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- 表的索引 `cm_hanglog`
--
ALTER TABLE `cm_hanglog`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cm_hostloc`
--
ALTER TABLE `cm_hostloc`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `cm_admin`
--
ALTER TABLE `cm_admin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '管理编号', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `cm_attachment`
--
ALTER TABLE `cm_attachment`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- 使用表AUTO_INCREMENT `cm_auth_group`
--
ALTER TABLE `cm_auth_group`
  MODIFY `id` int(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `cm_auth_rule`
--
ALTER TABLE `cm_auth_rule`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=580;

--
-- 使用表AUTO_INCREMENT `cm_config`
--
ALTER TABLE `cm_config`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '配置编号', AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `cm_hanglog`
--
ALTER TABLE `cm_hanglog`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '日志编号';

--
-- 使用表AUTO_INCREMENT `cm_hostloc`
--
ALTER TABLE `cm_hostloc`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '账号编号';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
