<?php /*a:1:{s:58:"/www/wwwroot/hostloc.site/app/admin/view/account/edit.html";i:1713501576;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="keywords" content="草莓快速开发框架" />
    <meta name="description" content="草莓快速开发框架 thinkphp6开源版" />
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

    <script type="text/javascript" src="/static/lib/loading/okLoading.js"></script>
    <script src="/static/lib/layui/layui.js"></script>
</head>

</html>

	<?php if(isset($account)): ?>
    <title><?php echo __('Edit'); ?></title>
    <?php else: ?>
    <title><?php echo __('Add'); ?></title>
    <?php endif; ?>


<div class="ok-body">
	<!--form表单-->
	<form class="layui-form layui-form-pane ok-form">
	    
                    <?php if($account['id']??null): ?>
                    <input type="hidden" name="id" placeholder="" autocomplete="off" class="layui-input" value="<?php echo isset($account['id']) ? htmlentities($account['id']) : ''; ?>">
                    <?php endif; ?>
                    <div class="layui-form-item">
                    <label class="layui-form-label">账户名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" placeholder="<?php echo __("Name"); ?>" autocomplete="off" class="layui-input"
                               lay-verify="required" value="<?php echo isset($account['name']) ? htmlentities($account['name']) : ''; ?>">
                    </div>
                </div><div class="layui-form-item">
                    <label class="layui-form-label">账户密码</label>
                    <div class="layui-input-block">
                        <input type="text" name="pass" placeholder="<?php echo __("Pass"); ?>" autocomplete="off" class="layui-input"
                               lay-verify="required" value="<?php echo isset($account['pass']) ? htmlentities($account['pass']) : ''; ?>">
                    </div>
                </div>
                <?php if(isset($account)): ?>
                <div class="layui-form-item">
                    <label class="layui-form-label">账户等级</label>
                    <div class="layui-input-block">
                        <input type="text" name="grade" placeholder="<?php echo __("Grade"); ?>" autocomplete="off" class="layui-input"
                               lay-verify="required" value="<?php echo isset($account['grade']) ? htmlentities($account['grade']) : ''; ?>">
                    </div>
                </div><div class="layui-form-item">
                    <label class="layui-form-label">账户积分</label>
                    <div class="layui-input-block">
                        <input type="text" name="integral" placeholder="<?php echo __("Integral"); ?>" autocomplete="off" class="layui-input"
                               lay-verify="required" value="<?php echo isset($account['integral']) ? htmlentities($account['integral']) : ''; ?>">
                    </div>
                </div><div class="layui-form-item">
                    <label class="layui-form-label">账户金钱</label>
                    <div class="layui-input-block">
                        <input type="text" name="money" placeholder="<?php echo __("Money"); ?>" autocomplete="off" class="layui-input"
                               lay-verify="required" value="<?php echo isset($account['money']) ? htmlentities($account['money']) : ''; ?>">
                    </div>
                </div> 
                <?php endif; ?>
                <div class="layui-form-item">
                    <label class="layui-form-label"><?php echo __("Address_ids"); ?></label>
                    <div class="layui-input-block">
                    <div id="address_ids" name="address_ids"  ></div>
                    </div>
                </div>  
                <div class="layui-form-item">
                    <label class="layui-form-label"><?php echo __("User_agent_id"); ?></label>
                    <div class="layui-input-block">
                        <select name="user_agent_id" lay-verify="required">
                            <option value=""><?php echo __('Select'); ?></option>
                            <?php foreach($userAgents as $key=>$vo): if(isset($account['user_agent_id']) && $account['user_agent_id']==$vo['id']): ?>}
                                <option value="<?php echo htmlentities($vo['id']); ?>" selected><?php echo htmlentities($vo['name']); ?></option>
                                <?php else: ?>
                                <option value="<?php echo htmlentities($vo['id']); ?>" ><?php echo htmlentities($vo['name']); ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div> 
                <?php if(isset($account)): ?>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label"><?php echo __("Uptime"); ?></label>
                        <div class="layui-input-block">
                            <input type="text" name="uptime" id="uptime" autocomplete="off" class="layui-input" placeholder='yy-mm-dd HH:ii:ss' value="<?php echo isset($account['uptime']) ? htmlentities($account['uptime']) : date('Y-m-d H:i:s'); ?>">
                        </div>
                    </div>
                </div>    
                <?php endif; ?>
  <div class="layui-form-item">
    <label class="layui-form-label"><?php echo __("Switch"); ?></label>
    <div class="layui-input-block">
      <input type="checkbox" name="switch" lay-skin="switch" lay-text="<?php echo __('Open'); ?>|<?php echo __('Close'); ?>" <?php if(isset($account) && $account['switch'] == 'on'): ?>checked<?php endif; ?>>
    </div>
  </div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" lay-submit lay-filter="add"><?php echo __('Submit'); ?></button>
				<button type="reset" class="layui-btn layui-btn-primary"><?php echo __('Reset'); ?></button>
			</div>
		</div>
	</form>
</div>


<script src="/static/js/upload.js" charset="utf-8"></script>

<script>

	//设置插件路径
	layui.config({
            base: "../../static/lib/"
    })
	//集成插件
	.extend({"xmSelect":"xm-select"})
	//使用插件
	.use(["form","okLayer","okUtils","xmSelect","laydate"], function () {
		let form = layui.form;
let okLayer = layui.okLayer;
let okUtils = layui.okUtils;
let xmSelect = layui.xmSelect;
let laydate = layui.laydate;

		
  		let editorArr = {};
		okLoading.close();
        
                //==================渲染字段虚拟地址组件
                var data = JSON.parse('<?php echo json_encode($addresss); ?>');
                var ids = JSON.parse('[<?php echo isset($account)?$account["address_ids"]:null; ?>]');
                var arr = [];
                for(var key in data){
                    arr[key] = {
                        'name':data[key].name,
                        'value':data[key].id,
                    }
                    if(ids.indexOf(data[key].id)!=-1){
                        arr[key]['selected'] = true;
                    }
                }
                var address_ids = xmSelect.render({
                    el: '#address_ids',
                    name: 'address_ids',
                    data:arr
                });
                

                //=============渲染字段更新时间组件
                laydate.render({ 
                          elem: "#uptime"
                          ,trigger:'click'
                          ,type: 'datetime'
                        });

		form.on("submit(add)", function (data) {
			okUtils.ajax("", "post", data.field, true).done(function (response) {
				//console.log(response);
				if(response.code==1){
					okLayer.greenTickMsg("编辑成功", function () {
						parent.layer.close(parent.layer.getFrameIndex(window.name));
					});
				}else{
					okLayer.greenTickMsg(response.msg, function () {
				        
				    })
				}
				
			}).fail(function (error) {
				console.log(error)
			});
			return false;
		});
	});
</script>


<script type="text/javascript" src="/static/lib/require.js"></script>
<script type="text/javascript" src="/static/js/addons.js"></script>

