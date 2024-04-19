<?php /*a:1:{s:59:"/www/wwwroot/hostloc.site/app/admin/view/account/index.html";i:1713506198;}*/ ?>
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

    <title><?php echo __('List'); ?></title>


<div class="ok-body">
    <form class="layui-form" lay-filter="search-form">  

        <div class="layui-card layui-form" style="border:1px solid #e6e6e6;padding-top:10px" >
        <div class="layui-form-item" >
                
        <div class="layui-inline">
            <label class="layui-form-label"><?php echo __('Id'); ?></label>
            <div class="layui-input-inline">
            <input type="text" name="id" id="id"  autocomplete="off" class="layui-input" placeholder="<?php echo __('Id'); ?>">
            </div>
        </div>
      

        <div class="layui-inline">
            <label class="layui-form-label"><?php echo __('Name'); ?></label>
            <div class="layui-input-inline">
            <input type="text" name="name" id="name"  autocomplete="off" class="layui-input" placeholder="<?php echo __('Name'); ?>">
            </div>
        </div>
      

        <div class="layui-inline">
            <label class="layui-form-label"><?php echo __('Pass'); ?></label>
            <div class="layui-input-inline">
            <input type="text" name="pass" id="pass"  autocomplete="off" class="layui-input" placeholder="<?php echo __('Pass'); ?>">
            </div>
        </div>
      

        <div class="layui-inline">
            <label class="layui-form-label"><?php echo __('Grade'); ?></label>
            <div class="layui-input-inline">
            <input type="text" name="grade" id="grade"  autocomplete="off" class="layui-input" placeholder="<?php echo __('Grade'); ?>">
            </div>
        </div>
      

        <div class="layui-inline">
            <label class="layui-form-label"><?php echo __('Integral'); ?></label>
            <div class="layui-input-inline">
            <input type="text" name="integral" id="integral"  autocomplete="off" class="layui-input" placeholder="<?php echo __('Integral'); ?>">
            </div>
        </div>
      

        <div class="layui-inline">
            <label class="layui-form-label"><?php echo __('Money'); ?></label>
            <div class="layui-input-inline">
            <input type="text" name="money" id="money"  autocomplete="off" class="layui-input" placeholder="<?php echo __('Money'); ?>">
            </div>
        </div>
      

        <div class="layui-inline">
            <label class="layui-form-label"><?php echo __('Address_ids'); ?></label>
            <div class="layui-input-inline">
            
                <select name="address_ids"  id="address_ids">
                    <option value=""><?php echo __('Select'); ?></option>
                </select>
                
            </div>
        </div>
      

        <div class="layui-inline">
            <label class="layui-form-label"><?php echo __('User_agent_id'); ?></label>
            <div class="layui-input-inline">
            
                <select name="user_agent_id"  id="user_agent_id">
                    <option value=""><?php echo __('Select'); ?></option>
                </select>
                
            </div>
        </div>
      

        <div class="layui-inline">
            <label class="layui-form-label"><?php echo __('Uptime'); ?></label>
            <div class="layui-input-inline">
            <input type="text" id="uptime" name="uptime"  autocomplete="off" class="layui-input" placeholder="<?php echo __('Uptime'); ?>">
            </div>
        </div>
      

        <div class="layui-inline">
            <label class="layui-form-label"><?php echo __('Switch'); ?></label>
            <div class="layui-input-inline">
            
                <select name="switch"  id="switch">
                    <option value=""><?php echo __('Select'); ?></option>
                    <option value="on"><?php echo __('Open'); ?></option>
                    <option value="off"><?php echo __('Close'); ?></option>
                </select>
                
            </div>
        </div>
      
                <div class="layui-inline">
                    <button class="layui-btn layuiadmin-btn-useradmin" lay-submit="" lay-filter="search">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i><?php echo __('Search'); ?>
                    </button>
                    <button type="reset" class="layui-btn layui-btn-primary icon-btn" type="reset">
                                <i class="layui-icon layui-icon-refresh-3"></i><?php echo __('Reset'); ?>
                            </button>
                </div>
            </div>
        </div>
    </form>
     <!--数据表格-->
    <table class="layui-hide" id="tableId" lay-filter="tableFilter"></table>
</div>



<!--js逻辑-->
<script type="text/javascript" src="/static/js/jquery.min.js"></script>
<script>
    layui.use(["form","okLayer","upload","okUtils","table","jquery","laydate"], function () {
        let form = layui.form;
let okLayer = layui.okLayer;
let upload = layui.upload;
let okUtils = layui.okUtils;
let table = layui.table;
let $ = layui.jquery;
let laydate = layui.laydate;

        

        okLoading.close($);
        
                //=============渲染字段更新时间组件
                laydate.render({ 
                          elem: "#uptime"
                          ,trigger:'click'
                          ,type: 'datetime'
                          ,range: true
                        });
        let userTable = table.render({
            elem: '#tableId',
            url: 'index',
            limit: 20,
            page: true,
            toolbar: true,
            toolbar: "#toolbarTpl",
            height:'full',
            cellMinWidth: 120,
            cols: [[
                {type: "checkbox", fixed: "left"},
                {field: 'id', title: '<?php echo __("Id"); ?>',hide:true},
                {field: 'name', title: '<?php echo __("Name"); ?>'},
                {field: 'pass', title: '<?php echo __("Pass"); ?>',hide:true},
                {field: 'grade', title: '<?php echo __("Grade"); ?>'},
                {field: 'integral', title: '<?php echo __("Integral"); ?>'},
                {field: 'money', title: '<?php echo __("Money"); ?>'},
                {field: 'addressIdsList', title: '<?php echo __("Address_ids"); ?>',templet: function (d) {
                        var data = d.addressIdsList;
                        var arr = [];
                        for(var key in data){
                            arr.push(data[key].name);
                        }
                        return arr.join(',')
                    } },
                {field: 'userAgentId', title: '<?php echo __("User_agent_id"); ?>',templet: function (d) {return d.userAgentIdList.name} },
                {field: 'uptime', title: '<?php echo __("Uptime"); ?>'},
                {field: 'switch', title: '<?php echo __("Switch"); ?>',templet: function (d) {
                        var state = "";
                        if (d.switch == "on") {
                            state = "<input type='checkbox' value='" + d.id + "' id='switch' lay-filter='stat' checked='checked' name='switch'  lay-skin='switch' lay-text='<?php echo __("Open"); ?>|<?php echo __("Close"); ?>' >";
                        } else {
                            state = "<input type='checkbox' value='" + d.id + "' id='switch' lay-filter='stat'  name='switch'  lay-skin='switch' lay-text='<?php echo __("Open"); ?>|<?php echo __("Close"); ?>' >";
                        }
                        return state;
                    }},

                {title: "<?php echo __('Action'); ?>",  align: "center", fixed: "right", templet: "#operationTpl"}
            ]],
            done: function (res, curr, count) {
                console.info(res, curr, count);
            }
        });

        form.on("submit(search)", function (data) {
            userTable.reload({
                where: data.field,
                page: {curr: 1}
            });
            return false;
        });
        form.on("submit(reset)", function (data) {
            userTable.reload({
                where: data.field,
                page: {curr: 1}
            });
            return false;
        });
//监听开关事件
        form.on('switch(stat)', function (data) {
            var contexts;
            var sta;
            var x = data.elem.checked;//判断开关状态
            if (x==true) {
                contexts = "<?php echo __('Open'); ?>";
                sta='on';
            } else {
                contexts = "<?php echo __('Close'); ?>";
                sta='off';
            }
            //自定义弹窗
            layer.open({
                content:x==true? "<?php echo __('Are you open'); ?>":"<?php echo __('Are you close'); ?>"
                , btn: ["<?php echo __('OK'); ?>", "<?php echo __('Cancel'); ?>"]
                , yes: function (index, layero) {
                    data.elem.checked = x;
                    postData = {id:data.value};
                    postData[data.elem.id] = sta;
                    console.log(postData);
                    $.post('sw', postData, function (data) {
                    if (data == 1) {
                        layer.msg(x==true? "<?php echo __('Open successful'); ?>":"<?php echo __('Close successful'); ?>",
                            {icon: 1, time: 2000,});

                                // 数据重载
                             active.reload();
                           }
                     });

                    form.render();
                    layer.close(index);

                }
                , btn2: function (index, layero) {
                    //按钮【按钮二】的回调
                    data.elem.checked = !x;
                    form.render();
                    layer.close(index);
                    //return false 开启该代码可禁止点击该按钮关闭
                }
                , cancel: function () {
                    //右上角关闭回调
                    data.elem.checked = !x;
                    form.render();
                    // return false; //开启该代码可禁止点击该按钮关闭
                }
            });

            return false;

        });
        table.on("toolbar(tableFilter)", function (obj) {
            switch (obj.event) {
                case "batchEnabled":
                    batchEnabled();
                    break;
                case "batchDisabled":
                    batchDisabled();
                    break;
                case "batchDel":
                    batchDel();
                    break;
                case "add":
                    add();
                    break;
                case "leading":
                    leading();
                    break;
            }
        });

        table.on("tool(tableFilter)", function (obj) {
            let data = obj.data;
            switch (obj.event) {
                case "update":
                    update(data.id);
                    break;
                case "edit":
                    edit(data);
                    break;
                case "del":
                    del(data.id);
                    break;
            }
        });
        upExcel();
        function upExcel() {
                    upload.render({
                        elem: '#leading' //绑定元素
                        ,url: 'leading' //上传接口
                        ,accept:'file'
                        ,exts:'csv|xlsx'
                        ,done: function(res){
                            //上传完毕回调
                            okLayer.greenTickMsg("导入成功", function () {
                                userTable.reload();
                                upExcel();
                            });
                        }
                        ,error: function(){
                            //请求异常回调
                            console.log(error)
                        }
                    });
                }
        
function getAddressList(id) {
    okUtils.ajax("getAddressList","get").done(function (response) {
            var data = response.data;
            for(var item of data){
                $("#"+id).append("<option value="+item.id+">"+item.name+"</option>");  
            }
             form.render();
    }).fail(function (error) {
        console.log(error)
    });
}
getAddressList('address_ids');

function getUserAgentList(id) {
    okUtils.ajax("getUserAgentList","get").done(function (response) {
            var data = response.data;
            for(var item of data){
                $("#"+id).append("<option value="+item.id+">"+item.name+"</option>");  
            }
             form.render();
    }).fail(function (error) {
        console.log(error)
    });
}
getUserAgentList('user_agent_id');
        function batchEnabled() {
            okLayer.confirm("<?php echo __('Are you sure you want to enable batches?'); ?>", function (index) {
                layer.close(index);
                let idsStr = okUtils.tableBatchCheck(table);
                if (idsStr) {
                    okUtils.ajax("setNormal", "put", {idsStr: idsStr}, true).done(function (response) {
                        if(response.code==1){
                            console.log(response);
                            okUtils.tableSuccessMsg(response.msg);
                        }else{
                            okLayer.greenTickMsg(response.msg, function () {

                            })
                        }
                    }).fail(function (error) {
                        console.log(error)
                    });
                }
            });
        }

        function batchDisabled() {
            okLayer.confirm("<?php echo __('Are you sure you want to disable batches?'); ?>", function (index) {
                layer.close(index);
                let idsStr = okUtils.tableBatchCheck(table);
                if (idsStr) {
                    okUtils.ajax("setStop", "put", {idsStr: idsStr}, true).done(function (response) {

                        if(response.code==1){
                            console.log(response);
                            okUtils.tableSuccessMsg(response.msg);
                        }else{
                            okLayer.greenTickMsg(response.msg, function () {

                            })
                        }
                    }).fail(function (error) {
                        console.log(error)
                    });
                }
            });
        }

        function batchDel() {
            okLayer.confirm("<?php echo __('Are you sure you want to delete batches?'); ?>", function (index) {
                layer.close(index);
                let idsStr = okUtils.tableBatchCheck(table);
                if (idsStr) {
                    okUtils.ajax("delete", "delete", {idsStr: idsStr}, true).done(function (response) {
                        //console.log(response);
                        //okUtils.tableSuccessMsg(response.msg);
                        if(response.code==1){
                            console.log(response);
                            okUtils.tableSuccessMsg(response.msg);
                        }else{
                            okLayer.greenTickMsg(response.msg, function () {

                            })
                        }
                    }).fail(function (error) {
                        console.log(error)
                    });
                }
            });
        }

        function add() {
            okLayer.open("<?php echo __('Add'); ?>", "add", "90%", "90%", null, function () {
                userTable.reload();
            })
        }
        function update(id) {
            okLayer.confirm("你确定要更新数据？", function () {
                okUtils.ajax("update", "post", {idsStr: id}, true).done(function (response) {


                    if(response.code==1){
                        console.log(response);
                        okUtils.tableSuccessMsg(response.msg);
                    }else{
                        okLayer.greenTickMsg(response.msg, function () {

                        })
                    }

                }).fail(function (error) {
                    console.log(error)
                });
            })
        }
        function leading(){
            okLayer.open("<?php echo __('Import'); ?>", "leading", "90%", "90%", null, function () {
                userTable.reload();
            })
        }

        function edit(data) {
            okLayer.open("<?php echo __('Edit'); ?>", "edit?id="+data.id, "90%", "90%", function (layero) {
                //let iframeWin = window[layero.find("iframe")[0]["name"]];
                //iframeWin.initForm(data);
            }, function () {
                userTable.reload();
            })
        }

        function del(id) {
            okLayer.confirm("<?php echo __('You sure you want to delete it?'); ?>", function () {
                okUtils.ajax("delete", "delete", {idsStr: id}, true).done(function (response) {


                    if(response.code==1){
                        console.log(response);
                        okUtils.tableSuccessMsg(response.msg);
                    }else{
                        okLayer.greenTickMsg(response.msg, function () {

                        })
                    }

                }).fail(function (error) {
                    console.log(error)
                });
            })
        }
    })
</script>
<!-- 头工具栏模板 -->
<script type="text/html" id="toolbarTpl">
    <div class="layui-btn-container">
        <!--		<button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="batchEnabled"><?php echo __('Batche enable'); ?></button>-->
        <!--		<button class="layui-btn layui-btn-sm layui-btn-warm" lay-event="batchDisabled"><?php echo __('Batche disable'); ?></button>-->
        <!--		<button class="layui-btn layui-btn-sm layui-btn-danger" lay-event="batchDel"><?php echo __('Batche delete'); ?></button>-->
        <button class="layui-btn layui-btn-sm" lay-event="add"><?php echo __('Add'); ?></button>
        <button class="layui-btn layui-btn-sm" id="leading"><?php echo __('Import'); ?></button>
    </div>
</script>
<!-- 行工具栏模板 -->
<script type="text/html" id="operationTpl">
    <a href="javascript:" title="更新数据" lay-event="update"><i class="layui-icon">&#xe9aa;</i></a>
    <a href="javascript:" title="<?php echo __('Edit'); ?>" lay-event="edit"><i class="layui-icon">&#xe642;</i></a>
    <a href="javascript:" title="<?php echo __('Delete'); ?>" lay-event="del"><i class="layui-icon">&#xe640;</i></a>
</script>

<script type="text/html" id="statusTpl">
    {{#  if(d.status == 'normal'){ }}
    <span class="layui-btn layui-btn-normal layui-btn-xs"><?php echo __('Enable'); ?></span>
    {{#  } else if(d.status != 'normal') { }}
    <span class="layui-btn layui-btn-warm layui-btn-xs"><?php echo __('Disable'); ?></span>
    {{#  } }}
</script>

