<?php
namespace app\admin\controller;

use think\facade\View;
use think\facade\Db;
use app\admin\validate\Admin as adminValidate;
use think\exception\ValidateException;

class Admin extends AdminBase
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
        $authgroup = $this->getAuthGroupOptions();
        $page = $this->request->param('page', 1, 'intval');
        $limit = $this->request->param('limit', 10, 'intval');
        $count = Db::name('admin')->count();
        $data = Db::name('admin')
        ->alias('as a')
        ->join('auth_group_access c', 'a.id = c.uid', 'left')
        ->field('a.id,a.username,a.nickname,a.avatar,a.email,a.status,a.createtime,c.group_id')
        ->page($page, $limit)->select()->each(function ($item, $k) use ($authgroup) {
            $item['createtime_text'] = date('Y-m-d H:i', $item['createtime']);
            $item['authgroup'] = key_exists($item['group_id'], $authgroup)?$authgroup[$item['group_id']]:'';
            return $item;
        });
        
        return json([
                'code'=> 0,
                'count'=> $count,
                'data'=>$data,
                'msg'=>'查询用户成功'
        ]);
    }
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            //var_dump($data);exit();
            try {
                validate(adminValidate::class)->check($data);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                $this->error($e->getError());
            }
            
            // 单独校验并去除角色组
            if (empty($data['group_id'])) {
                $this->error('请选择角色组');
            } else {
                $groupId = $data['group_id'];
                unset($data['group_id']);
            }
            
            unset($data['file']);
            $data['status'] = (isset($data['status'])&&$data['status']==1)?'normal':'stop';
            $data['avatar'] = $data['avatar']?("/storage/".$data['avatar']):'';
            $data['createtime'] = time();
            $data['salt'] = GetRandStr(6);
            $data['password'] = md5(md5($data['password']).$data['salt']);
            $result = \app\common\model\Admin::create($data);
            if ($result!==false) {
                \app\common\model\AuthGroupAccess::create([
                        'uid'      =>  $result->id,
                        'group_id' =>  $groupId
                ]);
                $this->success("添加成功");
            } else {
                $this->error("添加失败");
            }
        }
        View::assign('authgroup', $this->getAuthGroupOptions());
        return View::fetch();
    }
    public function edit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            //var_dump($data);exit();
            try {
                validate(adminValidate::class) ->scene('edit')->check($data);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                $this->error($e->getError());
            }
            
            if (!$data['group_id']) {
                $this->error('请选择角色组!');
            }
            $group_id = $data['group_id'];
            unset($data['group_id']);
            
            
            unset($data['file']);
            $data['status'] = (isset($data['status'])&&$data['status']==1)?'normal':'stop';
            
            $data['updatetime'] = time();
            if ($data['avatar']) {
                $data['avatar'] = ("/storage/".$data['avatar']);
            } else {
                unset($data['avatar']);
            }
            if ($data['password']) {
                if (strlen($data['password'])<6) {
                    $this->error("密码至少6位");
                }
                $data['salt'] = GetRandStr(6);
                $data['password'] = md5(md5($data['password']).$data['salt']);
            } else {
                unset($data['password']);
            }
            if (Db::name('admin')->where('id', $data['id'])->update($data)!==false) {
                $oldgroupId = \app\common\model\AuthGroupAccess::where('uid', $data['id'])
                ->value('group_id');
                if ($oldgroupId) {
                    \app\common\model\AuthGroupAccess::update([
                            'group_id' =>  $group_id
                            ], ['uid'=>$data['id']]);
                } else {
                    \app\common\model\AuthGroupAccess::create([
                            'uid'      =>  $data['id'],
                            'group_id' =>  $group_id
                            ]);
                }
                
                
                $this->success("编辑成功");
            } else {
                $this->error("编辑失败");
            }
        }
        $id = $this->request->param('id');
        if (!$id) {
            $this->error("参数错误");
        }
        $admininfo = Db::name('admin')->where('id', $id)->find();
        if (!$admininfo) {
            $this->error("参数错误");
        }
        // 获取当前管理员的分组
        $groupId = \app\common\model\AuthGroupAccess::where('uid', $admininfo['id'])
        ->value('group_id');
        View::assign('groupId', $groupId);
        View::assign('admininfo', $admininfo);
        View::assign('authgroup', $this->getAuthGroupOptions());
        return View::fetch();
    }
    public function delete()
    {
        $idsStr = $id = $this->request->param('idsStr');
        if (!$idsStr) {
            $this->error("参数错误");
        }
        if (Db::name('admin')->where('id', 'in', $idsStr)->delete()) {
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
        if (Db::name('admin')->where('id', 'in', $idsStr)->update(['status'=>'normal'])) {
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
        if (Db::name('admin')->where('id', 'in', $idsStr)->update(['status'=>'stop'])) {
            $this->success("设置成功");
        } else {
            $this->error("设置失败");
        }
    }
   
    // 获取角色分组信息
    private function getAuthGroupOptions()
    {
        // 获取角色组
        $authGroup = \app\common\model\AuthGroup::where('status', '=', 'normal')
        ->select()
        ->toArray();
        $groupOptions = [];
        foreach ($authGroup as $k => $v) {
            $groupOptions[$v['id']] = $v['title'];
        }
        return $groupOptions;
    }
}
