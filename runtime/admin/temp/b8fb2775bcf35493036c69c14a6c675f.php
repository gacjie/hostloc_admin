<?php /*a:2:{s:59:"/www/wwwroot/hostloc.site/app/admin/view/index/welcome.html";i:1698029923;s:60:"/www/wwwroot/hostloc.site/app/admin/view/layout/default.html";i:1682566592;}*/ ?>
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
    
<title>欢迎</title>

    <script type="text/javascript" src="/static/lib/loading/okLoading.js"></script>

</head>

<body>
    
<style>
.layui-admin-content .h4 {
    font-size: 20px;
    top: 10px;
    clear: both;
    display: block;
}

.layui-admin-content span {
    clear: both;
    margin-left: 10px;
    position: absolute;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.layui-admin-content {
    display: inline-block;
}
.layui-admin-workplace {
    display: inline-block;
    margin: 0 8px 0px 1px;
}
.clear {
    clear: both;
}
</style>
<div class="ok-body console">
	<div class="home">
	    <div class="layui-row layui-col-space15">
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        访问量<span class="layui-badge layui-badge-green pull-right">日</span>
                    </div>
                    <div class="layui-card-body">
                        <p class="lay-big-font"><span style="font-size: 24px;line-height: 1;">68,888</span></p>
                        <p>总访问量<span class="pull-right">290 万</span></p>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        销售额<span class="layui-badge layui-badge-blue pull-right">月</span>
                    </div>
                    <div class="layui-card-body">
                        <p class="lay-big-font"><span style="font-size: 24px;line-height: 1;">¥160,000</span></p>
                        <p>总销售额<span class="pull-right">880 万</span></p>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        订单量<span class="layui-badge layui-badge-red pull-right">周</span>
                    </div>
                    <div class="layui-card-body">
                        <p class="lay-big-font"><span style="font-size: 24px;line-height: 1;">1,680</span></p>
                        <p>转化率<span class="pull-right">20%</span></p>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        新增用户
                        <span class="icon-text pull-right" lay-tips="指标说明" lay-direction="4" lay-offset="5px,5px">
                        <i class="layui-icon layui-icon-tips"></i>
                    </span>
                    </div>
                    <div class="layui-card-body">
                        <p class="lay-big-font"> <span style="font-size: 24px;line-height: 1;">128 位</span></p>
                        <p>总用户<span class="pull-right">868,000 人</span></p>
                    </div>
                </div>
            </div>
        </div>
       <div class="layui-row layui-col-space15">
			<div class="layui-col-md8">
                <div class="layui-card">
                    <div class="layui-card-header">个人信息</div>
                    <div class="layui-card-body">
                        <table class="layui-table" lay-skin="nob">
                              <colgroup>
                                <col width="100">
                                <col width="100">
                                <col width="100">
                                <col width="100">
                              </colgroup>
                            <tbody>
                                <tr>
                                    <td><div class="contract-title">手机</div><div class="contract-detail">+86 15100030002                <i class="layui-icon fa-edit" title="编辑" lay-open="" data-title="修改手机" data-url="/user/mobile" data-area="550px,300px"></i></div></td>
                                    <td><div class="contract-title">微信号</div><div class="contract-detail">Tony</div></td>
                                    <td><div class="contract-title">办公室邮箱</div><div class="contract-detail">test@swiftadmin.net<i class="layui-icon fa-edit" title="编辑" lay-open="" data-title="修改邮箱" data-url="/user/email" data-area="550px,300px"></i></div></td>
                                    <td><div class="contract-title">登录IP</div><div class="contract-detail">111.111.111.111</div></td>
                                </tr>
                                <tr>
                                    <td><div class="contract-title">登陆次数</div><div class="contract-detail">298</div></td>
                                    <td><div class="contract-title">用户组</div><div class="contract-detail">注册用户</div></td>
                                    <td><div class="contract-title">加入时间</div><div class="contract-detail">2020-08-11 13:56:31</div></td>
                                    <td><div class="contract-title">所在住址</div><div class="contract-detail">未知</div></td>
                                </tr>
                             </tbody>
                        </table>
                    </div>
                </div>
                <div class="layui-card">
                    <div class="layui-card-header">项目动态</div>
                    <div class="layui-card-body">
                        <table class="layui-table">
                            <tbody>
                                <tr>
                                    <td>admin  于 05月19日 17:10 新增测试项目 </td>
                                </tr>
                                <tr>
                                    <td>admin  于 05月19日 17:10 新增测试项目 </td>
                                </tr>
                                <tr>
                                    <td>admin  于 05月19日 17:10 新增测试项目 </td>
                                </tr>
                                <tr>
                                    <td>admin  于 05月19日 17:10 新增测试项目 </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="layui-card">
                    <div class="layui-card-header">操作记录</div>
                    <div class="layui-card-body">
                        <table class="layui-table">
                            <tbody>
                                <tr>
                                    <td>admin  于 05月19日 17:10 访问了 【GET】/admin/index/index.html，终端Google Chrome 95.0.4638.69，IP地址111.111.111.111 </td>
                                </tr>
                                <tr>
                                    <td>admin  于 05月19日 17:10 访问了 【GET】/admin/index/index.html，终端Google Chrome 95.0.4638.69，IP地址111.111.111.111 </td>
                                </tr>
                                <tr>
                                    <td>admin  于 05月19日 17:10 访问了 【GET】/admin/index/index.html，终端Google Chrome 95.0.4638.69，IP地址111.111.111.111 </td>
                                </tr>
                                <tr>
                                    <td>admin  于 05月19日 17:10 访问了 【GET】/admin/index/index.html，终端Google Chrome 95.0.4638.69，IP地址111.111.111.111 </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
			<div class="layui-col-md4">
	            <div class="layui-card">
                    <div class="layui-card-header">版本信息</div>
                    <div class="layui-card-body">
                        <table class="layui-table layui-text">
                            <colgroup>
                                <col width="100">
                                <col>
                            </colgroup>
                            <tbody>
                            <tr>
                                <td>系统名称</td>
                                <td><?php echo htmlentities($site['name']); ?></td>
                            </tr>
                            <tr>
                                <td>系统版本</td>
                                <td><?php echo htmlentities($site['version']); ?></td>
                            </tr>
                            <tr>
                                <td>后端框架</td>
                                <td>thinkphp6</td>
                            </tr>
                            <tr>
                                <td>后端版本</td>
                                <td>6.1.1</td>
                            </tr>
                            <tr>
                                <td>前端框架</td>
                                <td>ok-admin</td>
                            </tr>
                            <tr>
                                <td>前端版本</td>
                                <td>v2.0</td>
                            </tr>
                            <tr>
                                <td>主要特色</td>
                                <td>开源免费 / 响应式 / CURD / 低代码 /易二开</td>
                            </tr>
                            <tr>
                                <td>获取渠道</td>
                                <td>
                                    <a href="https://gitee.com/gacjie" target="_blank" class="layui-btn">立即下载</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="layui-card">
                    <div class="layui-card-header">APP KEY</div>
                    <div id="appkey" class="layui-card-body">
                        <div class="layui-form">
                            <div class="layui-form-item">
                                <label class="layui-form-label">app_id：</label>
                                <div class="layui-input-block">
                                    <div class="layui-form-mid layui-word-aux">10001</div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">app_secret：</label>
                                <div class="layui-input-block">
                                    <div class="layui-form-mid layui-word-aux">gKdxOwETJDoamncSWQLuVy</div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <button type="submit" class="layui-btn layui-btn-normal" lay-ajax="" data-url="/user/appid" data-reload="self">更换APPKEY
                            </button>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
			
		</div>
	</div>
</div>

    <!--js逻辑-->
    <script src="/static/lib/layui/layui.js"></script>
    
<script>
	layui.use([], function () {
		okLoading.close();
	});
</script>

</body>

</html>