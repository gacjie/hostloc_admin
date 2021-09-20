<?php
namespace app\admin\controller;

use think\facade\View;
use think\facade\Db;
use app\admin\validate\AuthGroup as AuthGroupValidate;
use think\exception\ValidateException;

class AuthGroup extends AdminBase
{
    public function index()
    {
        if (!$this->request->isAjax()) {
            return View::fetch();
        } else {
            return $this->getList();
        }
    }
    public function getList()
    {
        $page = $this->request->param('page', 1, 'intval');
        $limit = $this->request->param('limit', 10, 'intval');
        $count = Db::name('AuthGroup')->count();
        $data = Db::name('AuthGroup')->page($page, $limit)->select()->each(function ($item, $k) {
            $item['createtime_text'] = date('Y-m-d H:i', $item['createtime']);
            return $item;
        });
        
        return json([
                'code'=> 0,
                'count'=> 20,
                'data'=>$data,
                'msg'=>'查询成功'
        ]);
    }
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            //var_dump($data);exit();
            try {
                validate(AuthGroupValidate::class)->check($data);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                $this->error($e->getError());
            }
            unset($data['node']);
            $data['status'] = (isset($data['status'])&&$data['status']==1)?'normal':'hidden';
            $data['createtime'] = time();
            if (Db::name('AuthGroup')->insert($data)) {
                $this->success("添加成功");
            } else {
                $this->error("添加失败");
            }
        }
        $getauth = $this->request->param('getauth', 0);
        if ($getauth==1) {
            $list = $this->getAuthList(0);
            $this->success('获取成功', '', $list);
        }
        return View::fetch();
    }
    public function edit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            //var_dump($data);exit();
            try {
                validate(AuthGroupValidate::class) ->scene('edit')->check($data);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                $this->error($e->getError());
            }
            unset($data['node']);
            $data['status'] = (isset($data['status'])&&$data['status']==1)?'normal':'hidden';
            $data['updatetime'] = time();
            if (Db::name('AuthGroup')->where('id', $data['id'])->update($data)) {
                $this->success("编辑成功");
            } else {
                $this->error("编辑失败");
            }
        }
        $id = $this->request->param('id');
        if (!$id) {
            $this->error("参数错误");
        }
        $getauth = $this->request->param('getauth', 0);
        if ($getauth==1) {
            $list = $this->getAuthList($id);
            $this->success('获取成功', '', $list);
        }
        $AuthGroupinfo = Db::name('AuthGroup')->where('id', $id)->find();
        if (!$AuthGroupinfo) {
            $this->error("参数错误");
        }
        View::assign('AuthGroupinfo', $AuthGroupinfo);
        return View::fetch();
    }
    public function delete()
    {
        $idsStr = $id = $this->request->param('idsStr');
        if (!$idsStr) {
            $this->error("参数错误");
        }
        if (Db::name('AuthGroup')->where('id', 'in', $idsStr)->delete()) {
            $this->success("删除成功");
        } else {
            $this->error("删除失败");
        }
    }
    public function setNormal()
    {
        $idsStr = $id = $this->request->param('idsStr');
        if (!$idsStr) {
            $this->error("参数错误");
        }
        if (Db::name('AuthGroup')->where('id', 'in', $idsStr)->update(['status'=>'normal'])) {
            $this->success("设置成功");
        } else {
            $this->error("设置失败");
        }
    }
    public function setStop()
    {
        $idsStr = $id = $this->request->param('idsStr');
        if (!$idsStr) {
            $this->error("参数错误");
        }
        if (Db::name('AuthGroup')->where('id', 'in', $idsStr)->update(['status'=>'hidden'])) {
            $this->success("设置成功");
        } else {
            $this->error("设置失败");
        }
    }
    // 获取权限列表
    protected function getAuthList($id=0)
    {

        // 获取菜单规则
        $authRule = \app\common\model\AuthRule::field('id, pid, title')
        ->order('weigh asc')
        ->select()
        ->toArray();
        // 获取当前组权限并格式化
        $rules = '';
        if ($id) {
            $rules = \app\common\model\AuthGroup::where('id', $id)->value('rules');
        }
        $list = authNew($authRule, 0, $rules);
        
        //print_r($list);exit();
        $list = [[
            "id"    => 0,
            //"pid"   => 0,
            "title" => "全部",
            //"open"  => true,
            'field' => 'node',
            'spread' => true,
            'children'=>$list,
            'checked'=>false
        ]];
        return $list;
    }
    // 权限设置
    public function auth(string $id)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (empty($data['rules'])) {
                $this->error("请选择权限");
            }
            $data['updatetime'] = time();
            if (Db::name('AuthGroup')->where('id', $data['id'])->update($data)) {
                $this->success("授权成功");
            } else {
                $this->error("授权失败");
            }
        }
        $getauth = $this->request->param('getauth', 0);
        if ($getauth==1) {
            $list = $this->getAuthList($id);
            $this->success('获取成功', '', $list);
        }
        
        View::assign("id", $id);
        return View::fetch();
    }
}
