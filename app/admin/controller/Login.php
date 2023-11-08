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
        // 模板输出
        return View::fetch();
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
        $rules = Db::name('auth_group_access')
        ->alias('a')
        ->leftJoin('auth_group ag', 'a.group_id = ag.id')
        ->field('a.group_id,ag.rules,ag.title')
        ->where('uid', $admininfo['id'])
        ->find();
        $admininfo['expire_time'] = $keep_login == 1 ? true : time() + 7200;
        Session::set('admin', $admininfo);
        
        Session::set('admin.group_id', $rules['group_id']);
        Session::set('admin.rules', explode(',', $rules['rules']));
        Session::set('admin.title', $rules['title']);
         
        
        $this->success(__('Login successful'));
    }
    public function logout()
    {
        Session::delete('admin');
        return redirect('index');
    }
}
