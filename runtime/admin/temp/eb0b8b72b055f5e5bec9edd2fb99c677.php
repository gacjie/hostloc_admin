<?php /*a:2:{s:57:"/www/wwwroot/hostloc.site/app/admin/view/index/index.html";i:1689425847;s:60:"/www/wwwroot/hostloc.site/app/admin/view/layout/default.html";i:1682566592;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="keywords" content="宝塔小栈" />
    <meta name="description" content="宝塔小栈" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="renderer" content="webkit">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="/static/css/oksub.css">
    <link rel="stylesheet" href="/static/css/font-awesome/css/font-awesome.min.css">
    <link rel="shortcut icon" href="/static/images/favicon.ico" type="image/x-icon" />
    <script src="<?php echo url('ajax/lang',['controllername'=>1,'lang'=>'zh-cn']); ?>"></script>

    <script>
        window.urls = {
            getUploadFiles: "<?php echo url('upload/getUploadFiles'); ?>",
            uploadEditor: "<?php echo url('upload/uploadEditor'); ?>",
            uploadfile: "<?php echo url('upload/uploadfile'); ?>",
        }
    </script>
    <style>
        .layui-upload-list img {
            height: 100px;
            width: 100px;
        }
    </style>

    <link rel="stylesheet" href="/static/lib/loading/okLoading.css" />
    <script type="text/javascript" src="/static/js/okconfig.js"></script>
    
<title><?php echo htmlentities($site['name']); ?></title>
<link rel="stylesheet" href="/static/css/okadmin.css">
<style>
   div.layui-table-header>table>thead>tr {
      background-color: #00F7DE;
   }
</style>

    <script type="text/javascript" src="/static/lib/loading/okLoading.js"></script>

</head>

<body>
    
<!-- 更换主体 Eg:orange_theme|blue_theme -->
<div class="layui-layout layui-layout-admin okadmin blue_theme">
   <!--头部导航-->
   <div class="layui-header okadmin-header">
      <ul class="layui-nav layui-layout-left">
         <li class="layui-nav-item">
            <a class="ok-menu ok-show-menu" href="javascript:" title="<?php echo __('Menu switch'); ?>">
               <i class="layui-icon layui-icon-shrink-right"></i>
            </a>
         </li>

      </ul>
      <ul class="layui-nav layui-layout-right">
         <li class="layui-nav-item ok-input-search">
            <input type="text" placeholder="<?php echo __('Search'); ?>" class="layui-input layui-input-search" />
         </li>
         <li class="layui-nav-item">
            <a class="ok-refresh pr10 pl10" href="/" title="主页" target="_blank">
               <i class="layui-icon layui-icon-home"></i>
            </a>
         </li>
         <li class="layui-nav-item">
            <a class="ok-refresh pr10 pl10" href="javascript:" title="<?php echo __('Refresh'); ?>">
               <i class="layui-icon layui-icon-refresh-3"></i>
            </a>
         </li>
         <!-- 全屏 -->
         <li class="layui-nav-item layui-hide-xs">
            <a id="fullScreen" class="pr10 pl10" href="javascript:;">
               <i class="layui-icon layui-icon-screen-full"></i>
            </a>
         </li>
         <li class="no-line layui-nav-item layui-hide-xs">
            <a id="notice" class="flex-vc pr10 pl10" href="javascript:">
               <i class="ok-icon ok-icon-notice icon-head-i" title="<?php echo __('System notify'); ?>"></i>
               <span class="layui-badge-dot"></span>
               <cite></cite>
            </a>
         </li>


         <!--<li class="layui-nav-item">-->
         <!--   <a class="pr20 pl10" href="javascript:;">-->
         <!--      <i class="layui-icon layui-icon-website"></i>-->
         <!--   </a>-->
            
         <!--   <dl class="layui-nav-child">-->
         <!--       <dd><a lay-id="u-1">简体中文</a></dd>-->
         <!--   </dl>-->
         <!--</li>-->
         <li class="no-line layui-nav-item">
            <a href="javascript:">
               <img src="<?php echo htmlentities($admininfo['avatar']); ?>" class="layui-nav-img">
               <?php echo htmlentities($admininfo['username']); ?>
            </a>
            <dl id="userInfo" class="layui-nav-child">
               <!-- <dd><a lay-id="u-1" href="javascript:" data-url="pages/member/user.html">个人中心<span
                class="layui-badge-dot"></span></a></dd> -->
               <!-- <dd><a lay-id="u-2" href="javascript:" data-url="pages/member/user-info.html">基本资料</a></dd> -->
               <!-- <dd><a lay-id="u-3" href="javascript:" data-url="pages/member/user-pwd.html">安全设置</a></dd> -->
               <!-- <dd><a lay-id="u-4" href="javascript:" id="alertSkin">皮肤动画</a></dd>
            <dd>
               <hr/>
            </dd> -->
                <dd><a lay-id="u-1"><?php echo htmlentities($site['version']); ?></a></dd>
                <dd><a href="javascript:" id="user" url="/admin/index/user">账号信息</a></dd>
                <dd><a href="javascript:" id="modify_pass" url="/admin/index/modify_pass">修改密码</a></dd>
                <dd><a href="javascript:" id="clear" url="/admin/index/clear">清理缓存</a></dd>
                <dd><a href="javascript:" id="logout" url="/admin/index/logout"><?php echo __('Logout'); ?></a></dd>
            </dl>
         </li>

      </ul>
   </div>
   <!--遮罩层-->
   <div class="ok-make"></div>
   <!--左侧导航区域-->
   <div class="layui-side layui-side-menu okadmin-bg-20222A ok-left">
      <div class="layui-side-scroll okadmin-side">
         <div class="okadmin-logo"><?php echo htmlentities($site['name']); ?></div>
         <div class="user-photo">
            <a class="img" title="<?php echo __('My avatar'); ?>">
               <img src="<?php echo htmlentities($admininfo['avatar']); ?>" class="userAvatar">
            </a>
            <p><?php echo __('Welcome login',['username'=>"<span class='userName'>".$admininfo['username']."</span>"]); ?></p>
         </div>
         <!--左侧导航菜单-->
         <ul id="navBar" class="layui-nav okadmin-nav okadmin-bg-20222A layui-nav-tree">

            <?php if(is_array($menus) || $menus instanceof \think\Collection || $menus instanceof \think\Paginator): $i = 0; $__LIST__ = $menus;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <li class="layui-nav-item">
               <a href="javascript:" lay-id="<?php echo htmlentities($vo['id']); ?>" data-url="<?php echo htmlentities($vo['href']); ?>" is-close="true">
                  <i class="fa <?php echo htmlentities($vo['icon']); ?>"></i>
                  <cite><?php echo htmlentities($vo['title']); ?></cite>
               </a>
               <?php if(count($vo['children'])): ?>
               <dl class="layui-nav-child">
                  <?php if(is_array($vo['children']) || $vo['children'] instanceof \think\Collection || $vo['children'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?>
                  <dd>
                     <a lay-id="<?php echo htmlentities($voo['pid']); ?>-<?php echo htmlentities($voo['id']); ?>" data-url="<?php echo htmlentities($voo['href']); ?>" is-close="true"><i
                           class="fa <?php echo htmlentities($voo['icon']); ?>"></i><cite><?php echo htmlentities($voo['title']); ?></cite>
                     </a>

                     <?php if(count($voo['children'])): ?>
                     <dl class="layui-nav-child">
                        <?php if(is_array($voo['children']) || $voo['children'] instanceof \think\Collection || $voo['children'] instanceof \think\Paginator): $i = 0; $__LIST__ = $voo['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vooo): $mod = ($i % 2 );++$i;?>
                        <dd>
                           <a lay-id="<?php echo htmlentities($vooo['pid']); ?>-<?php echo htmlentities($vooo['id']); ?>" data-url="<?php echo htmlentities($vooo['href']); ?>" is-close="true"><i
                                 class="fa <?php echo htmlentities($vooo['icon']); ?>"></i><cite><?php echo htmlentities($vooo['title']); ?></cite>
                           </a>
                        </dd>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                     </dl>
                     <?php endif; ?>

                  </dd>
                  <?php endforeach; endif; else: echo "" ;endif; ?>
               </dl>
               <?php endif; ?>

            </li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
         </ul>
      </div>
   </div>

   <!-- 内容主体区域 -->
   <div class="content-body">
      <div class="layui-tab ok-tab" lay-filter="ok-tab" lay-allowClose="true" lay-unauto>
         <div data-id="left" id="okLeftMove" class="ok-icon ok-icon-back okadmin-tabs-control move-left okNavMove">
         </div>
         <div data-id="right" id="okRightMove" class="ok-icon ok-icon-right okadmin-tabs-control move-right okNavMove">
         </div>
         <div class="layui-icon okadmin-tabs-control ok-right-nav-menu" style="right: 0;">
            <ul class="okadmin-tab">
               <li class="no-line okadmin-tab-item">
                  <div class="okadmin-link layui-icon-down" href="javascript:;"></div>
                  <dl id="tabAction" class="okadmin-tab-child layui-anim-upbit layui-anim">
                     <dd><a data-num="1" href="javascript:"><?php echo __('Close current'); ?></a></dd>
                     <dd><a data-num="2" href="javascript:"><?php echo __('Close other'); ?></a></dd>
                     <dd><a data-num="3" href="javascript:"><?php echo __('Close all'); ?></a></dd>
                  </dl>
               </li>
            </ul>
         </div>

         <ul id="tabTitle" class="layui-tab-title ok-tab-title not-scroll">
            <li class="layui-this" lay-id="1" tab="index">
               <i class="ok-icon">&#xe654;</i>
               <cite is-close=false>控制台</cite>
            </li>
         </ul>

         <div id="tabContent" class="layui-tab-content ok-tab-content">
            <div class="layui-tab-item layui-show">
               <iframe src='<?php echo url("welcome"); ?>' frameborder="0" scrolling="yes" width="100%" height="100%"></iframe>
            </div>
         </div>
      </div>
   </div>

   <!--底部信息-->
   <div class="layui-footer okadmin-text-center">
      Copyright ©2018-©2023 <?php echo htmlentities($site['name']); ?> All Rights Reserved
   </div>
</div>

    <!--js逻辑-->
    <script src="/static/lib/layui/layui.js"></script>
    
<!--<script src="/static/js/snowflake.js?snowflake=雪花"></script>-->
<script src="/static/js/okadmin.js"></script>

</body>

</html>