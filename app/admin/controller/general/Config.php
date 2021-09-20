<?php

namespace app\admin\controller\general;

use app\BaseController;
use app\common\model\Config as ConfigModel;
use think\facade\Db;
use think\facade\View;
use think\facade\Config as ConfigFile;

/**
 * 系统配置
 *
 * @icon   fa fa-cogs
 * @remark 可以在此增改系统的变量和分组,也可以自定义分组和变量,如果需要删除请从数据库中删除
 */
class Config extends BaseController
{

    /**
     * @var \app\common\model\Config
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = (new ConfigModel());
    }

    /**
     * 查看
     */
    public function index()
    {
        $siteList = [];
        $groupList = ConfigModel::getGroupList();
        foreach ($groupList as $k => $v) {
            $siteList[$k]['name'] = $k;
            $siteList[$k]['title'] = $v;
            $siteList[$k]['list'] = [];
        }
        foreach ((new \app\common\model\Config())->select() as $k => $v) {
            if (!isset($siteList[$v['group']])) {
                continue;
            }
            $value = $v->toArray();
            $value['title'] = __($value['title']);
            if (in_array($value['type'], ['select', 'selects', 'checkbox', 'radio'])) {
                $value['value'] = explode(',', $value['value']);
            }
            $value['content'] = json_decode($value['content'], true);
            $value['tip'] = htmlspecialchars($value['tip']);
            $siteList[$v['group']]['list'][] = $value;
        }
        $index = 0;
        foreach ($siteList as $k => &$v) {
            $v['active'] = !$index ? true : false;
            $index++;
        }
        
        return view('', [
            'siteList'  => $siteList
        ]);
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            //$this->token();
            $params = $this->request->post(["group", "title", "name", "type", "content"]);
            if ($params) {
                // foreach ($params as $k => &$v) {
                //     $v = is_array($v) && $k !== 'setting' ? implode(',', $v) : $v;
                // }
                // if (in_array($params['type'], ['select', 'selects', 'checkbox', 'radio', 'array'])) {
                //     $params['content'] = json_encode(ConfigModel::decode($params['content']), JSON_UNESCAPED_UNICODE);
                // } else {
                //     $params['content'] = '';
                // }
                try {
                    if(empty($params['group']) || empty($params['title']) || empty($params['name']) || empty($params['type'])) {
                        throw new \Exception(__('Parameter can not be empty'));
                    }

                    $tmp = (new \app\common\model\Config())->where("name", $params["name"])->field('id')->find();
                    if($tmp && $tmp->id && $tmp->id > 0){
                        throw new \Exception(__('name is already exists!'));
                    }
                    $params["content"] = str_replace(["\n", "\r"], "", $params["content"]);
                    $contentArr = explode(",",  $params["content"]);
                    $newContentArr = [];
                    $i = 1;
                    foreach ($contentArr as $val) {
                        $newContentArr[$i] = $val;
                        $i++;
                    }
                    $params["content"] = json_encode($newContentArr);

                    //判断类型
                    if($params["type"] == "string"){
                        $params["extend"] = "class=\"layui-input\"";
                    }
                    
                    (new \app\common\model\Config())->save($params);
                    //$result = (new ConfigModel())->create($params);
                    echo json_encode([
                        "status" => 1,
                        "msg" => __("add success!"),
                    ]);
                    exit;
                } catch (\Exception $e) {
                    echo json_encode([
                        "status" => 0,
                        "msg" => $e->getMessage(),
                    ]);
                    exit;
                }
                /*
                if ($result !== false) {
                    try {
                        $this->refreshFile();
                    } catch (Exception $e) {
                        $this->error($e->getMessage());
                    }
                    $this->success();
                } else {
                    $this->error((new ConfigModel())->getError());
                }*/
            }
            //$this->error(__('Parameter %s can not be empty', ''));
        }
        echo json_encode([
            "status" => 0,
            "msg" => __('Parameter %s can not be empty', ''),
        ]);
        exit;
        //return $this->view->fetch();
    }

    /**
     * 编辑
     * @param null $ids
     */
    public function edit($ids = null)
    {
        if ($this->request->isPost()) {
            $row = $this->request->post("row/a", [], 'trim');
            if ($row) {
                $groupList = ConfigModel::getGroupList();
                foreach ($groupList as $k => $v) {
                    $siteList[$k]['name'] = $k;
                    $siteList[$k]['title'] = $v;
                    $siteList[$k]['list'] = [];
                }
                $configList = [];
                foreach ((new \app\common\model\Config())->all() as $v) {
                    if (!isset($siteList[$v['group']])) {
                        continue;
                    }
                    if (isset($row[$v['name']])) {
                        $value = $row[$v['name']];
                        if (is_array($value) && isset($value['field'])) {
                            $value = json_encode(ConfigModel::getArrayData($value), JSON_UNESCAPED_UNICODE);
                        } else {
                            $value = is_array($value) ? implode(',', $value) : $value;
                        }
                        $v['value'] = $value;
                        $configList[] = $v->toArray();
                    }else{
                        $v['value'] = "";
                        $configList[] = $v->toArray();
                    }
                }
                try {
                    (new ConfigModel())->saveAll($configList);
                } catch (Exception $e) {
                    $this->error($e->getMessage());
                }
                try {
                    $this->refreshFile();
                } catch (Exception $e) {
                    $this->error($e->getMessage());
                }
                $this->success();
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
    }

    /**
     * 删除
     * @param string $ids
     */
    public function del($ids = "")
    {
        $name = $this->request->post('name');
        $config = ConfigModel::getByName($name);
        if ($name && $config) {
            try {
                $config->delete();
                $this->refreshFile();
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
            $this->success();
        } else {
            $this->error(__('Invalid parameters'));
        }
    }

    /**
     * 刷新配置文件
     */
    protected function refreshFile()
    {
        $config = [];
        foreach ((new ConfigModel())->all() as $k => $v) {
            $value = $v->toArray();
            if (in_array($value['type'], ['selects', 'checkbox', 'images', 'files'])) {
                $value['value'] = explode(',', $value['value']);
            }
            if ($value['type'] == 'array') {
                $value['value'] = (array)json_decode($value['value'], true);
            }
            $config[$value['name']] = $value['value'];
        }

//        \think\Config::set('site', array_merge(\think\Config::get('site'), $row));

        $base_config = ConfigFile::get('site');
        $config = array_merge($base_config,$config);
        //追加配置文件 file_put_contents
        file_put_contents(
            root_path() . 'config' . DS . 'site.php',//TP6 config目录变更
            '<?php' . "\n\nreturn " . var_export($config, FILE_APPEND) . ";\n"
        );
    }

    /**
     * 检测配置项是否存在
     * @internal
     */
    public function check()
    {
        $params = $this->request->post("row/a");
        if ($params) {
            $config = (new ConfigModel())->get($params);
            if (!$config) {
                $this->success();
            } else {
                $this->error(__('Name already exist'));
            }
        } else {
            $this->error(__('Invalid parameters'));
        }
    }

    /**
     * 规则列表
     * @internal
     */
    public function rulelist()
    {
        //主键
        $primarykey = $this->request->request("keyField");
        //主键值
        $keyValue = $this->request->request("keyValue", "");

        $keyValueArr = array_filter(explode(',', $keyValue));
        $regexList = \app\common\model\Config::getRegexList();
        $list = [];
        foreach ($regexList as $k => $v) {
            if ($keyValueArr) {
                if (in_array($k, $keyValueArr)) {
                    $list[] = ['id' => $k, 'name' => $v];
                }
            } else {
                $list[] = ['id' => $k, 'name' => $v];
            }
        }
        return json(['list' => $list]);
    }

    /**
     * 发送测试邮件
     * @internal
     */
    public function emailtest()
    {
        $row = $this->request->post('row/a');
        $receiver = $this->request->post("receiver");
        if ($receiver) {
            if (!Validate::is($receiver, "email")) {
                $this->error(__('Please input correct email'));
            }
            \think\Config::set('site', array_merge(\think\Config::get('site'), $row));
            $email = new Email;
            $result = $email
                ->to($receiver)
                ->subject(__("This is a test mail"))
                ->message('<div style="min-height:550px; padding: 100px 55px 200px;">' . __('This is a test mail content') . '</div>')
                ->send();
            if ($result) {
                $this->success();
            } else {
                $this->error($email->getError());
            }
        } else {
            $this->error(__('Invalid parameters'));
        }
    }

    public function selectpage()
    {
        $id = $this->request->get("id/d");
        $config = \app\common\model\Config::get($id);
        if (!$config) {
            $this->error(__('Invalid parameters'));
        }
        $setting = $config['setting'];
        //自定义条件
        $custom = isset($setting['conditions']) ? (array)json_decode($setting['conditions'], true) : [];
        $custom = array_filter($custom);

        $this->request->request(['showField' => $setting['field'], 'keyField' => $setting['primarykey'], 'custom' => $custom, 'searchField' => [$setting['field'], $setting['primarykey']]]);
        $this->model = \think\Db::connect()->setTable($setting['table']);
        return parent::selectpage();
    }

    /**
     * 获取表列表
     * @internal
     */
    public function get_table_list()
    {
        $tableList = [];
        $dbname = \think\Config::get('database.database');
        $tableList = \think\Db::query("SELECT `TABLE_NAME` AS `name`,`TABLE_COMMENT` AS `title` FROM `information_schema`.`TABLES` where `TABLE_SCHEMA` = '{$dbname}';");
        $this->success('', null, ['tableList' => $tableList]);
    }

    /**
     * 获取表字段列表
     * @internal
     */
    public function get_fields_list()
    {
        $table = $this->request->request('table');
        $dbname = \think\Config::get('database.database');
        //从数据库中获取表字段信息
        $sql = "SELECT `COLUMN_NAME` AS `name`,`COLUMN_COMMENT` AS `title`,`DATA_TYPE` AS `type` FROM `information_schema`.`columns` WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? ORDER BY ORDINAL_POSITION";
        //加载主表的列
        $fieldList = Db::query($sql, [$dbname, $table]);
        $this->success("", null, ['fieldList' => $fieldList]);
    }
}
