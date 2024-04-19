<?php
namespace app\admin\controller;

use app\BaseController;
use think\facade\View;
use think\facade\Db;
use think\facade\Session;
use think\facade\Event;

class Login extends BaseController
{
    public function index()
    {
        //var_dump($this->request->isPost());exit();
        /* if($this->request->isPost()){
    		$this->error('111');
    	} */
        // if (Session::has('admin')) {
        //     $this->error(__('You are logged'),  url('index/index'));
        // }

        //判断是否启用社会化登入
        $social_login = whetherToUsePlugin('social_login')? true:false;
        // 模板输出
        return View::fetch('', ['socail_login'=>$social_login]);
    }
    
    public function signin()
    {
        // if (Session::has('admin')) {
        //     $this->error(__('You are logged'), 'index/index');
        // }
        $username = $this->request->param('username');
        $password = $this->request->param('password');
        $keep_login = $this->request->param('keep_login');
        //拖动验证
//      $captcha = $this->request->param('captcha');
//    	if(!captcha_check($captcha)){
//    		// 验证失败
//    		$this->error(__('Verification code error'));
//    	};
        
        $admininfo = Db::name('admin')->where('username', $username)->find();
        if (empty($admininfo)) {
            $this->error(__('Incorrect username or password'));
        }
        if ($admininfo['status']!='normal') {
            $this->error(__('Account is disabled'));
        }
        if ($admininfo['password']!=md5(md5($password).$admininfo['salt'])) {
            $this->error(__('Incorrect username or password'));
        }
        
        // 查找规则
        $rules = Db::name('auth_group')
        ->where('id', $admininfo['group_id'])
        ->field('rules,title')
        ->find();
        $admininfo['expire_time'] = $keep_login == 1 ? true : time() + 7200;
        Session::set('admin', $admininfo);
        Session::set('admin.rules', explode(',', $rules['rules']));
        Session::set('admin.title', $rules['title']);
         
        
        $this->success(__('Login successful'));
    }
    public function logout()
    {
        Session::delete('admin');
        return redirect('index');
    }
    

    /**
     * 调用方法demo 默认微信登入
     * @param string $type 登入类型 'config/social.php 配置文件'  //'Qq', 'Weixin', 'Sina', 'Baidu', 'Gitee', 'Github', 'Google', 'Facebook', 'Taobao', 'Oschina', 'Douyin', 'Xiaomi', 'Dingtalk'
     */
    public function social_login($type='Weixin')
    {
        $type = input()['type'];
        //钩子事件 短信插件
        $plugin_name = 'Aaliyun';
        Event::listen($plugin_name, 'addons\social_login\event\SocialLogin');
        $hoddok_res = event($plugin_name, $type);
//        var_dump($hoddok_res);

        //登入成功 登入注册等用户信息业务逻辑
    }
}
