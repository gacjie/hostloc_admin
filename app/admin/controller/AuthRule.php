<?php
/**
 * +----------------------------------------------------------------------
 * | 菜单规则控制器
 * +----------------------------------------------------------------------
 */
namespace app\admin\controller;

// 引入框架内置类
use think\facade\Request;
use think\facade\View;
use think\facade\Db;
use app\admin\validate\AuthRule as AuthRuleValidate;
use think\exception\ValidateException;
use app\common\library\Menu;
use app\common\model\AuthRule as AuthRuleModel;
use fast\Tree;

class AuthRule extends AdminBase
{
    
    // 列表
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
        $data = Db::name('AuthRule')->order('weigh asc')->select()->each(function ($item, $k) {
            $item['createtime_text'] = date('Y-m-d H:i', $item['createtime']);
            return $item;
        });
             
        return json([
                    'code'=> 0,
                    'count'=> 10000,
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
                validate(AuthRuleValidate::class)->check($data);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                $this->error($e->getError());
            }
            $data['ismenu'] = (isset($data['ismenu'])&&$data['ismenu']==1)?1:0;
            $data['status'] = (isset($data['status'])&&$data['status']==1)?'normal':'hidden';
            $data['createtime'] = time();
            if (Db::name('AuthRule')->insert($data)) {
                $this->success("添加成功");
            } else {
                $this->error("添加失败");
            }
        }
        // 必须将结果集转换为数组
        $ruleList = AuthRuleModel::order('weigh', 'asc')->field('id,pid,title as name')->select()->toArray();
        // 构造菜单数据
        $menus = Tree::instance()->init($ruleList)->getTree(0);
        View::assign('menus', $menus);
        return View::fetch();
    }

    

    // 修改
    public function edit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            //var_dump($data);exit();
            try {
                validate(AuthRuleValidate::class)->check($data);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                $this->error($e->getError());
            }
            $data['status'] = (isset($data['status'])&&$data['status']==1)?'normal':'stop';
            $data['ismenu'] = (isset($data['ismenu'])&&$data['ismenu']==1)?1:0;
            $data['updatetime'] = time();
            
            if (Db::name('AuthRule')->where('id', $data['id'])->update($data)) {
                $this->success("编辑成功");
            } else {
                $this->error("编辑失败");
            }
        }
        $id = $this->request->param('id');
        if (!$id) {
            $this->error("参数错误");
        }
        $info = Db::name('AuthRule')->where('id', $id)->find();
        if (!$info) {
            $this->error("参数错误");
        }
        View::assign('info', $info);
        // 必须将结果集转换为数组
        $ruleList = AuthRuleModel::order('weigh', 'asc')->field('id,pid,title as name')->select()->toArray();
        // 构造菜单数据
        $menus = Tree::instance()->init($ruleList)->getTree(0, "<option value=@id @selected @disabled>@spacer@name</option>", $info['pid'], $info['id']);
        View::assign('menus', $menus);
        return View::fetch();
    }


    public function delete()
    {
        $idsStr = $id = $this->request->param('idsStr');
        $idsStr = trim($idsStr, ',');
        if (!$idsStr) {
            $this->error("参数错误");
        }
        if (Db::name('AuthRule')->where('pid', 'in', $idsStr)->find()) {
            $this->error("有子菜单不能删除");
        }
        //return false;
        if (Db::name('AuthRule')->where('id', 'in', $idsStr)->delete()) {
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
        if (Db::name('AuthRule')->where('id', 'in', $idsStr)->update(['status'=>'normal'])) {
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
        if (Db::name('AuthRule')->where('id', 'in', $idsStr)->update(['status'=>'hidden'])) {
            $this->success("设置成功");
        } else {
            $this->error("设置失败");
        }
    }
}
