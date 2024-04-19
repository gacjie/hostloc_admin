<?php /*a:2:{s:57:"/www/wwwroot/hostloc.site/app/admin/view/login/index.html";i:1635004836;s:60:"/www/wwwroot/hostloc.site/app/admin/view/layout/default.html";i:1682566592;}*/ ?>
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
    
<title><?php echo __('Login'); ?></title>
<style>
    #captchaImg2 img {
        width: 100%;
        height: 100%;
    }
</style>

    <script type="text/javascript" src="/static/lib/loading/okLoading.js"></script>

</head>

<body>
    
<div id="login">
    <form class="layui-form">
        <div class="login_face"><img src="/static/images/logo.png?v=1.0"></div>
        <div class="layui-form-item input-item">

            <input type="text" lay-verify="required" name="username" placeholder="<?php echo __('Please input username'); ?>"
                autocomplete="off" id="username" class="layui-input">
        </div>
        <div class="layui-form-item input-item">

            <input type="password" lay-verify="required|password" name="password"
                placeholder="<?php echo __('Please input password'); ?>" autocomplete="off" id="password" class="layui-input">
        </div>
        <!--            <div class="layui-form-item input-item captcha-box">-->
        <!--                <input type="text" lay-verify="required|captcha" name="captcha" placeholder="<?php echo __('Please input code'); ?>" autocomplete="off" id="captcha" maxlength="4" class="layui-input">-->
        <!--                <div class="img ok-none-select" id="captchaImg2"><?php echo captcha_img(); ?></div>-->
        <!--            </div>-->
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block" style="margin-left: 0px!important;">
                <div id="slider"></div>
            </div>
        </div>
        <div class="layui-form-item">
            <button class="layui-btn layui-block" lay-filter="login" lay-submit=""><?php echo __('Login'); ?></button>
        </div>
        <div style="margin: auto;">
            <span><?php echo __('Third party login'); ?></span>
            <a class="" href="<?php echo url('Login/social_login?type=Qq'); ?>"><?php echo __('QQ login'); ?> | </a>
            <a class="" href="<?php echo url('Login/social_login?type=Weixin'); ?>"><?php echo __('Wechat login'); ?></a>
        </div>
        <div class="login-link">
            <input type="checkbox" name="keep_login" value="1" lay-skin="switch" checked="checked"
                lay-text="<?php echo __('Remember'); ?>|<?php echo __('Remember'); ?>">
        </div>
        <div class="login-link">
            <!--  <a href="register.html">注册</a>
                <a href="forget.html">忘记密码?</a> -->
        </div>
    </form>

</div>

    <!--js逻辑-->
    <script src="/static/lib/layui/layui.js"></script>
    
<script>
    layui.config({
        base: '/static/lib/layui/sliderVerify/'
    });
</script>
<script>
    layui.use(["form", "okUtils", "okLayer", "sliderVerify"], function () {
        let form = layui.form;
        let $ = layui.jquery;
        let okGVerify = layui.okGVerify;
        let okUtils = layui.okUtils;
        let okLayer = layui.okLayer;
        var sliderVerify = layui.sliderVerify;
        var slider = sliderVerify.render({
            elem: '#slider'
        })
        okLoading.close()
        /**
         * 初始化验证码
         */
        //let verifyCode = new okGVerify("#captchaImg");

        /**
         * 数据校验
         */
        form.verify({
            password: [/^[\S]{6,12}$/, "<?php echo __('Password length verify error',['min'=>6,'max'=>12]); ?>"],
            /*  captcha: function (val) {
                 if (verifyCode.validate(val) != "true") {
                     return verifyCode.validate(val)
                 }
             } */
        });

        /**
         * 表单提交
         */
        form.on("submit(login)", function (data) {
            if (slider.isOk()) {
            } else {
                layer.msg("<?php echo __('Please pass the slider verification first'); ?>");
                return;
            }

            okUtils.ajax("<?php echo Url('Signin'); ?>", "post", data.field, true).done(function (response) {
                /* okLayer.greenTickMsg(response.msg, function () {
                    window.location = "../index.html";
                }) */
                console.log(response);
                if (response.code == 1) {
                    okLayer.greenTickMsg(response.msg, function () {
                        location.href = "<?php echo Url('index/index'); ?>";
                    })

                } else {
                    okLayer.greenTickMsg(response.msg, function () {
                        $('#captchaImg2 img').click();
                        slider.reset();//重新拖动验证
                    })
                }
            }).fail(function (error) {
                console.log(error)
            });
            return false;
        });

        /**
            * 表单input组件单击时
            */
        $("#login .input-item .layui-input").click(function (e) {
            e.stopPropagation();
            $(this).addClass("layui-input-focus").find(".layui-input").focus();
        });

        /**
         * 表单input组件获取焦点时
         */
        $("#login .layui-form-item .layui-input").focus(function () {
            $(this).parent().addClass("layui-input-focus");
        });

        /**
         * 表单input组件失去焦点时
         */
        $("#login .layui-form-item .layui-input").blur(function () {
            $(this).parent().removeClass("layui-input-focus");
            if ($(this).val() != "") {
                $(this).parent().addClass("layui-input-active");
            } else {
                $(this).parent().removeClass("layui-input-active");
            }
        })
    });
</script>

</body>

</html>