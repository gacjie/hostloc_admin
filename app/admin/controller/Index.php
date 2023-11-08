<?php
namespace app\admin\controller;

use think\facade\View;
use think\facade\Db;
use think\facade\Lang;

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
}
