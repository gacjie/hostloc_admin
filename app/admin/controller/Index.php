<?php
namespace app\admin\controller;

use think\facade\View;
use think\facade\Db;
use think\facade\Lang;
use think\facade\Cache;
use think\facade\Session;

class Index extends AdminBase
{
    public function lang($lang)
    {
        Lang::setLangSet($lang);
    }
    public function index()
    {
        // 模板输出
        // 模板变量赋值
        $admin = session('admin');
        $admininfo = Db::name('admin')->where('id', $admin['id'])->find();
        View::assign('admininfo', $admininfo);
        return View::fetch();
    }
    public function welcome()
    {
        // View::assign('site', config('site'));
        return View::fetch();
    }
    //清理缓存
    public function clear()
    {
        Cache::clear(); 
        return redirect('index');
    }
    //退出登陆
    public function logout()
    {
        Session::delete('admin');
        return redirect('index');
    }
    //用户信息
    public function user()
    {
        $user=session('admin');
        $data = input('post.');
        $userinfo=Db::name('admin')->where('id', $user['id'])->find();
        if($userinfo['id']==$user['id']){
            if(request()->isPost()){
                $save=Db::name('admin')->where('id',$user['id'])->strict(false)->update(['avatar' => $data['avatar'],'name' => $data['name'],'mobile' => $data['mobile']]);
                if($save!==false){
                    $this->success("操作成功");
                }else{
                    $this->error("操作失败");
                }
                return;
            }
            return view('user',[
                'userinfo'=>$userinfo,
                ]);
        }else{
            $this->error("没有权限操作");
        }
    }
    //修改密码
    public function modify_pass()
    {
        $user=session('admin');
        $data = input('post.');
        $userinfo=Db::name('admin')->where('id', $user['id'])->find();
        if($userinfo['id']==$user['id']){
            if(request()->isPost()){
                if($data['password'] != $data['password2']){
                    $this->error("两次输入的新密码不一致");
                }
                if($userinfo['password']!=md5(md5($data['oldpassword']).$userinfo['salt'])){
    		        $this->error('旧密码输入错误');
    	        }
    	        $password = md5(md5($data['password']).$userinfo['salt']);
                $save=Db::name('user')->where('id',$user['id'])->strict(false)->update(['password' => $password]);
                if($save!==false){
                    $this->success("操作成功");
                }else{
                    $this->error("操作失败");
                }
                return;
            }
            return view('modify_pass',[
                'userinfo'=>$userinfo,
                ]);
        }else{
            $this->error("没有权限操作");
        }
    }
}
