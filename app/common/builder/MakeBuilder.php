<?php
/**
 * +----------------------------------------------------------------------
 * | 生成代码、处理表和字段相关数据
 */
namespace app\common\builder;

use app\common\model\Field;
use app\common\model\Module;
use think\facade\Request;

class MakeBuilder
{
    /**
     * 获取某个表的主键
     */
    public function getPrimarykey(string $tableName = '')
    {
        $pk = Module::where('table_name', $tableName)->value('pk') ?? 'id';
        return $pk;
    }

    /**
     * 获取某个表中所有的字段信息
     * @param string $tableName 表名称
     * @return array
     */
    public function getFields(string $tableName = '')
    {
        // 根据表名称查询出当前模块的id
        $module = Module::where('table_name', $tableName)
            ->find();
        // 非空判断
        if (!$module) {
            return [];
        }
        // 根据模块ID获取所有字段
        $fields = Field::where('module_id', $module['id'])
            ->order(['sort', 'id' => 'desc'])
            ->select()
            ->toArray();

        foreach ($fields as &$field) {
            // 给每个字段增加一个属性：是否主键
            $field['is_pk'] = $field['field'] == $module['pk'] ? 1 : 0;
            // 格式化字段的其他设置
            $field['setup'] = string2array($field['setup']);
        }
        return $fields;
    }

    /**
     * 获取列表页面可展示的字段信息
     * @param string $tableName 表名称
     * @return array
     */
    public function getListColumns(string $tableName = '')
    {
        $columns = [];
        $fields = $this->getFields($tableName);

        foreach ($fields as &$field) {
            // 获取字典列表
            $dicts = [];
            if ($field['data_source'] == 1) {
                $dicts = \app\common\model\Dictionary::where('dict_type', $field['dict_code'])
                    ->field('dict_value,dict_label')
                    ->order('sort ASC,id DESC')
                    ->select()
                    ->toArray();
                $dicts = $this->changeSelect($dicts);
            }

            // 筛选可搜索且状态不为0的字段
            if ($field['is_list'] != 1 || $field['status'] == 0) {
                continue;
            }

            // select等需要获取数据的字段需设置好 param 或考虑是否变更字段(字典类型的在这里获取，关联的在模型里重构该字段)
            $param = $dicts;
            // 默认值
            $default = $field['setup']['default'] ?? '';
            // 额外CSS
            $class = $field['setup']['extra_class'] ?? '';
            // 排序
            $sortable = $field['is_sort'] ? 'true' : 'false';
            // 添加到返回数组中
            $columns[] = [$field['field'], $field['name'], $field['type'], $default, $param, $class, $sortable];
        }
        return $columns;
    }

    /**
     * 获取添加页面可展示的字段信息
     * @param string $tableName 表名称
     * @return array
     */
    public function getAddColumns(string $tableName = '', array $info = [])
    {
        // 查询模型的主键
        $module = \app\common\model\Module::where('table_name', $tableName)->find();
        $model = '\app\common\model\\' . $module->model_name;
        $user = new $model;
        $pk = $user->getPk();

        $fields = $this->getFields($tableName);
        $columns = [];
        foreach ($fields as &$field) {
            // 主键不可新增，当方法名中包含add时系统认为是新增页面
            if (strpos(strtolower(Request::action()), 'add') !== false && $field['field'] == $pk) {
                continue;
            }

            // 非自增字段判断是否可添加
            if ($field['is_pk'] != 1 && strpos(strtolower(Request::action()), 'add') !== false && $field['is_add'] != 1) {
                continue;
            }

            // 非自增字段判断是否可修改
            if ($field['is_pk'] != 1 && strpos(strtolower(Request::action()), 'edit') !== false && $field['is_edit'] != 1) {
                continue;
            }

            // 状态为0的字段不可新增或修改
            if ($field['status'] == 0) {
                continue;
            }

            // 补全修改页面字段的默认值
            if ($info) {
                if (isset($info[$field['field']])) {
                    $field['setup']['default'] = $info[$field['field']] ?? $field['setup']['default'];
                    // 禁止修改
                    if ($field['is_edit'] != 1) {
                        $field['setup']['extra_attr'] = isset($field['setup']['extra_attr']) ? $field['setup']['extra_attr'] . ' readonly = "readonly"' : '';
                    }
                }
            }

            // 参数传递
            if (Request::param($field['field']) || Request::param($field['field']) === "0") {
                $field['setup']['default'] = Request::param($field['field']) ?? $field['setup']['default'];
            }

            // select等需要获取数据的字段需设置好$field['param']
            $options = $this->getFieldOptions($field);

            $field['options'] = $options ?? [];
            // text
            $field['group'] = isset($field['setup']['group']) && !empty($field['setup']['group']) ? explode('|', $field['setup']['group']) : [];

            // 必填项转换
            $field['required'] = $field['required'] == 1 ? true : false;
            // 添加到返回数组中,注意form构建器和table构建器的不一致
            if ($field['type'] == 'text') {
                $columns[] = [
                    $field['type'],                // 类型
                    $field['field'],               // 字段名称
                    $field['name'],                // 字段别名
                    $field['tips'],                // 提示信息
                    $field['setup']['default'],    // 默认值
                    $field['group'],               // 标签组，可以在文本框前后添加按钮或者文字
                    $field['setup']['extra_attr'], // 额外属性
                    $field['setup']['extra_class'],// 额外CSS
                    $field['setup']['placeholder'],// 占位符
                    $field['required'],            // 是否必填
                ];
            }
            elseif ($field['type'] == 'textarea' || $field['type'] == 'password') {
                $columns[] = [
                    $field['type'],                       // 类型
                    $field['field'],                      // 字段名称
                    $field['name'],                       // 字段别名
                    $field['tips'],                       // 提示信息
                    $field['setup']['default'],           // 默认值
                    $field['setup']['extra_attr'],        // 额外属性
                    $field['setup']['extra_class'] ?? '', // 额外CSS
                    $field['setup']['placeholder'] ?? '', // 占位符
                    $field['required'],                   // 是否必填
                ];
            }
            elseif ($field['type'] == 'radio' || $field['type'] == 'checkbox') {
                $columns[] = [
                    $field['type'],                // 类型
                    $field['field'],               // 字段名称
                    $field['name'],                // 字段别名
                    $field['tips'],                // 提示信息
                    $field['options'],             // 选项（数组）
                    $field['setup']['default'],    // 默认值
                    $field['setup']['extra_attr'], // 额外属性 extra_attr
                    '',                            // 额外CSS extra_class
                    $field['required'],            // 是否必填
                ];
            }
            elseif ($field['type'] == 'select' || $field['type'] == 'select2' ) {
                $columns[] = [
                    $field['type'],                       // 类型
                    $field['field'],                      // 字段名称
                    $field['name'],                       // 字段别名
                    $field['tips'],                       // 提示信息
                    $field['options'],                    // 选项（数组）
                    $field['setup']['default'],           // 默认值
                    $field['setup']['extra_attr'],        // 额外属性
                    $field['setup']['extra_class'] ?? '', // 额外CSS
                    $field['setup']['placeholder'] ?? '', // 占位符
                    $field['required'],                   // 是否必填
                ];
            }
            elseif ($field['type'] == 'number') {
                $columns[] = [
                    $field['type'],                       // 类型
                    $field['field'],                      // 字段名称
                    $field['name'],                       // 字段别名
                    $field['tips'],                       // 提示信息
                    $field['setup']['default'],           // 默认值
                    '',                                   // 最小值
                    '',                                   // 最大值
                    $field['setup']['step'],              // 步进值
                    $field['setup']['extra_attr'],        // 额外属性
                    $field['setup']['extra_class'],       // 额外CSS
                    $field['setup']['placeholder'] ?? '', // 占位符
                    $field['required'],                   // 是否必填
                ];
            }
            elseif ($field['type'] == 'hidden') {
                $columns[] = [
                    $field['type'],                      // 类型
                    $field['field'],                     // 字段名称
                    $field['setup']['default'] ?? '',    // 默认值
                    $field['setup']['extra_attr'] ?? '', // 额外属性 extra_attr
                ];
            }
            elseif ($field['type'] == 'date' || $field['type'] == 'time' || $field['type'] == 'datetime') {
                // 使用每个字段设定的格式
                if ($field['type'] == 'time') {
                    $format = $field['setup']['format'];
                    $format = str_replace("HH", "h", $format);
                    $format = str_replace("mm", "i", $format);
                    $format = str_replace("ss", "s", $format);
                    $format = $format ?? 'H:i:s';
                } else {
                    $format = $field['setup']['format'];

                    $format = str_replace("yyyy", "Y", $format);
                    $format = str_replace("mm", "m", $format);
                    $format = str_replace("dd", "d", $format);
                    $format = str_replace("hh", "h", $format);
                    $format = str_replace("ii", "i", $format);
                    $format = str_replace("ss", "s", $format);
                    $format = $format ?? 'Y-m-d H:i:s';
                }
                $field['setup']['default'] = (int)$field['setup']['default'] > 0 && is_int($field['setup']['default']) ? date($format, $field['setup']['default']) : $field['setup']['default'];
                $columns[] = [
                    $field['type'],                // 类型
                    $field['field'],               // 字段名称
                    $field['name'],                // 字段别名
                    $field['tips'],                // 提示信息
                    $field['setup']['default'],    // 默认值
                    $field['setup']['format'],     // 日期格式
                    $field['setup']['extra_attr'], // 额外属性 extra_attr
                    '',                            // 额外CSS extra_class
                    $field['setup']['placeholder'],// 占位符
                    $field['required'],            // 是否必填
                ];
            }
            elseif ($field['type'] == 'daterange') {
                $columns[] = [
                    $field['type'],                       // 类型
                    $field['field'],                      // 字段名称
                    $field['name'],                       // 字段别名
                    $field['tips'],                       // 提示信息
                    $field['setup']['default'],           // 默认值
                    $field['setup']['format'],            // 日期格式
                    $field['setup']['extra_attr'] ?? '',  // 额外属性
                    $field['setup']['extra_class'] ?? '', // 额外CSS
                    $field['required'],                   // 是否必填
                ];
            }
            elseif ($field['type'] == 'tag') {
                $columns[] = [
                    $field['type'],                       // 类型
                    $field['field'],                      // 字段名称
                    $field['name'],                       // 字段别名
                    $field['tips'],                       // 提示信息
                    $field['setup']['default'],           // 默认值
                    $field['setup']['extra_attr'] ?? '',  // 额外属性
                    $field['setup']['extra_class'] ?? '', // 额外CSS
                    $field['required'],                   // 是否必填
                ];
            }
            elseif ($field['type'] == 'image' || $field['type'] == 'images' || $field['type'] == 'file' || $field['type'] == 'files') {

                // 多(图/文件)上传执行解析操作
                if ($field['type'] == 'images' || $field['type'] == 'files') {
                    $field['setup']['default'] = json_decode($field['setup']['default'], true);
                }

                $columns[] = [
                    $field['type'],                       // 类型
                    $field['field'],                      // 字段名称
                    $field['name'],                       // 字段别名
                    $field['tips'],                       // 提示信息
                    $field['setup']['default'],           // 默认值
                    $field['setup']['extra_attr'] ?? '',  // 额外属性
                    $field['setup']['extra_class'] ?? '', // 额外CSS
                    $field['setup']['placeholder'] ?? '', // 占位符
                    $field['required'],                   // 是否必填
                ];
            }
            elseif ($field['type'] == 'editor') {
                $columns[] = [
                    $field['type'],                       // 类型
                    $field['field'],                      // 字段名称
                    $field['name'],                       // 字段别名
                    $field['tips'],                       // 提示信息
                    $field['setup']['default'],           // 默认值
                    $field['setup']['heidht'] ?? 0,       // 高度
                    $field['setup']['extra_attr'] ?? '',  // 额外属性
                    $field['setup']['extra_class'] ?? '', // 额外CSS
                    $field['required'],                   // 是否必填
                ];
            }
            elseif ($field['type'] == 'color') {
                $columns[] = [
                    $field['type'],                       // 类型
                    $field['field'],                      // 字段名称
                    $field['name'],                       // 字段别名
                    $field['tips'],                       // 提示信息
                    $field['setup']['default'],           // 默认值
                    $field['setup']['extra_attr'] ?? '',  // 额外属性
                    $field['setup']['extra_class'] ?? '', // 额外CSS
                    $field['setup']['placeholder'] ?? '', // 占位符
                    $field['required'],                   // 是否必填
                ];
            }
            // Button
        }

        return $columns;
    }

    /**
     * 获取列表需要的搜索字段
     * @param string $tableName 表名称
     */
    public function getListSearch(string $tableName = '')
    {
        $fields = $this->getFields($tableName);
        $items = [];
        foreach ($fields as &$field) {
            // 筛选可搜索字段
            if ($field['is_search'] != 1) {
                continue;
            }
            // select等需要获取数据的字段需设置好$field['param']
            $field['param'] = $this->getFieldOptions($field);
            // 默认参数传递
            if (Request::param($field['field']) || Request::param($field['field']) === "0") {
                $field['default_value'] = Request::param($field['field']);
            }

            // 添加到返回数组中(注意顺序不可变)
            $items[] = [
                $field['type'],                // 字段类型
                $field['field'],               // 字段名称
                $field['name'],                // 字段别名
                $field['search_type'] ?? '=',  // 匹配方式
                $field['default_value'] ?? '', // 默认值
                $field['param'] ?? [],         // 额外参数
                $field['data_source'] ?? 0,    // 数据源 [0 字段本身, 1 系统字典, 2 模型数据]
                $field['relation_model'] ?? '',// 模型关联
                $field['relation_field'] ?? '',// 关联字段
            ];
        }
        return $items;
    }

    /**
     * 获取筛选的条件
     * @param string $tableName 表名称
     */
    public function getListWhere(string $tableName = '')
    {
        $search = $this->getListSearch($tableName);
        //全局查询条件
        $where = [];
        // 循环所有搜索字段，看是否有传递
        foreach ($search as $k => $v) {
            if (Request::param($v[1]) || Request::param($v[1]) === "0") {
                $searhKeywords = Request::param($v[1]);
                // 判断字段类型，默认为=
                if (isset($v[3]) && !empty($v[3])) {
                    $option = $v[3];
                } else {
                    $option = '=';
                }

                // 模型关联的数据需要考虑转化
                if ($v[6] == 2) {
                    // 需要转化的字段类型
                    $arr = ['text', 'textarea', 'number', 'hidden'];
                    if (in_array($v[0], $arr)) {
                        // 查找关联主键
                        $pk = $this->getPrimarykey($tableName);
                        // 尝试查找关联的值
                        $model = '\app\common\model\\' . $v[7];
                        if (strtoupper($option) == 'LIKE') {
                            $relationFieldValue = $model::where($v[8], $option, '%' . $searhKeywords . '%')->value($pk);
                            // 重定义查询表达式
                            $option = '=';
                        } else {
                            $relationFieldValue = $model::where($v[8], $option, $searhKeywords)->value($pk);
                        }
                        // 重新定义搜索词
                        $searhKeywords = $relationFieldValue ?: '-1';
                    }
                }

                // text / select / daterange / default
                switch ($v[0]) {
                    case 'text':
                        if (strtoupper($option) == 'LIKE') {
                            $where[] = [$v[1], $option, '%' . $searhKeywords . '%'];
                        } else {
                            $where[] = [$v[1], $option, $searhKeywords];
                        }
                        break;
                    case 'select':
                        if (strtoupper($option) == 'LIKE') {
                            $where[] = [$v[1], $option, '%' . $searhKeywords . '%'];
                        } else {
                            $where[] = [$v[1], $option, $searhKeywords];
                        }
                        break;
                    case 'date':
                        $getDateran = get_dateran($searhKeywords);
                        $where[] = [$v[1], 'between', $getDateran];
                        break;
                    case 'time':
                        $getDateran = get_dateran($searhKeywords);
                        $where[] = [$v[1], 'between', $getDateran];
                        break;
                    case 'datetime':
                        $getDateran = get_dateran($searhKeywords);
                        $where[] = [$v[1], 'between', $getDateran];
                        break;
                    // 默认都当作文本框
                    default:
                        if (strtoupper($option) == 'LIKE') {
                            $where[] = [$v[1], $option, '%' . $searhKeywords . '%'];
                        } else {
                            $where[] = [$v[1], $option, $searhKeywords];
                        }

                }
            }
        }

        return $where;
    }

    /**
     * 获取选项的列表值
     * @param array $field 字段信息
     * @return array
     */
    public function getFieldOptions(array $field)
    {
        // 0 字段本身，1 系统字典，2 模型数据
        if ($field['data_source'] == 1) {
            // 获取字典列表
            $result = \app\common\model\Dictionary::where('dict_type', $field['dict_code'])
                ->field('dict_value,dict_label')
                ->order('sort ASC,id DESC')
                ->select()
                ->toArray();
            $result = $this->changeSelect($result);
        } elseif ($field['data_source'] == 2) {
            // 取出对应模型的所有数据
            $module = '\app\common\model\\' . $field['relation_model'];
            // 根据模型名称获取select的排序
            $order = $this->getOrder($field['relation_model']);
            // 主键
            $pk = $this->getPk($field['relation_model']);

            // 当模块中包含pid/parent_id时格式化展示效果
            $fieldPid = '';
            $moduleId = \app\common\model\Module::where('model_name', $field['relation_model'])->value('id');
            if ($moduleId) {
                // 查询字段名称
                $fieldPid = \app\common\model\Field::where("field = 'pid' OR field = 'parent_id' ")->where('module_id', $moduleId)->field('field')->find();
                if ($fieldPid) {
                    $fieldPid = ',' . $fieldPid['field'];
                }
            }
            // 获取数据列表
            $result = $module::field($pk . ',' . $field['relation_field'] . $fieldPid)
                ->order($order)
                ->select()
                ->toArray();
            $result = $this->changeSelect($result);
        } else {
            $result = [];
        }

        return $result;
    }

    /**
     * 根据模型名称获取select的排序
     * @param string $modelName
     * @return string
     */
    private function getOrder(string $modelName)
    {
        // 取出对应模型信息
        $model = \app\common\model\Module::where('model_name', $modelName)->find();
        // 获取主键
        $pk = $model->pk ?? 'id';
        // 是否有排序字段
        $sortField = \app\common\model\Field::where('module_id', $model->id)->where('field', 'sort')->find();
        if ($sortField) {
            $order = 'sort ASC,' . $pk . ' DESC';
        } else {
            $order = $pk . ' DESC';
        }
        return $order;
    }

    /**
     * 根据模型名称获取主键
     * @param string $modelName
     * @return string
     */
    private function getPk(string $modelName)
    {
        // 取出对应模块信息
        $model = \app\common\model\Module::where('model_name', $modelName)->find();
        // 获取主键
        return $model->pk ?? 'id';
    }

    /**
     * 转变数组内第一个元素为键值
     * @param array $array
     * @return array
     */
    private function changeSelect(array $array)
    {
        $result = [];
        // 当元素个数为3时执行tree操作
        if ($array && count(($array[0])) == 3) {
            $array = tree_three($array);
        }

        foreach ($array as &$arr) {
            if (count($arr) == 2) {
                $result[current($arr)] = end($arr);
            } else {
                $keys = array_keys($arr);
                $result[$arr[$keys[0]]] = $arr[$keys[1]];
            }

        }
        return $result;
    }

    /**
     * 检测单页模式是否跳转
     * @param string $isSingle 模型名称
     * @return url
     */
    public function checkSingle(string $modelName)
    {
        $model = '\app\common\model\\' . $modelName;
        $module = \app\common\model\Module::where('model_name', $modelName)->find();
        $where = self::getListWhere($module['table_name']);
        if ($module && $module['is_single'] == 1) {
            $info = $model::where($where)->select()->toArray();
            if (count($info) == 1) {
                //跳转编辑页
                $redirect = [
                    'id' => $info[0][$module['pk']]
                ];
                return (string)url('edit', $redirect);
            } elseif (count($info) == 0) {
                return (string)url('add');
            } else {
                return '';
            }
        }
    }

    /**
     * 添加/修改页面获取字段分组，用于FormBuilder
     * @param string $modelName 模型名称
     * @param array $coloumns   字段数据
     * @return array
     */
    public function getgetAddGroups(string $modelName, string $tableName, array $coloumns)
    {
        // 查询模块信息
        $module = \app\common\model\Module::where('model_name', $modelName)->find();

        // 查询分组情况
        $filedGroup = \app\common\model\FieldGroup::where('module_id', $module->id)->order('sort asc,id desc')->select()->toArray();

        // 查询所有字段信息
        $fields = self::getFields($tableName);

        $groups = [];
        foreach ($filedGroup as $key => $value) {
            $groups[$value['group_name']] = [];
            foreach ($fields as $k => $v) {
                if ($v['group_id'] == $value['id']) {
                    $groups[$value['group_name']][] = $v['field'];
                }
            }
        }
        $groupsNew = [];
        foreach ($groups as $key => $value) {
            foreach ($coloumns as $k => $v) {
                if (in_array($v[1], $value)) {
                    $groupsNew[$key][] = $v;
                }
            }
        }
        return $groupsNew;
    }

    /**
     * 新增、修改保存时改变提交的信息为需要的格式[日期、时间、日期时间]
     * @param array $formData
     * @param string $tableName
     * @return array
     */
    public function changeFormData(array $formData, string $tableName)
    {
        // 查询所有字段信息
        $fields = self::getFields($tableName);
        $fieldsNew = [];
        foreach ($fields as $k => $v) {
            $fieldsNew[$v['field']] = $v;
        }
        foreach ($formData as $k => $v) {
            if (array_key_exists($k, $fieldsNew)) {
                // 改变日期等格式
                if ($fieldsNew[$k]['type'] == 'date' || $fieldsNew[$k]['type'] == 'time' || $fieldsNew[$k]['type'] == 'datetime') {
                    $formData[$k] = strtotime($v);
                } else if ($fieldsNew[$k]['type'] == 'password') {
                    // 密码为空则不做修改，不为空则做md5处理
                    if (empty($v)) {
                        unset($formData[$k]);
                    } else {
                        $formData[$k] = md5($v);
                    }
                } else if ($fieldsNew[$k]['type'] == 'images' || $fieldsNew[$k]['type'] == 'files') {
                    $images = [];
                    for ($i = 0; $i < count($formData[$k]); $i++) {
                        if ($formData[$k][$i]) {
                            $images[$i]['image'] = $formData[$k][$i];
                            $images[$i]['title'] = $formData[$k . '_title'][$i];
                        }
                    }
                    $formData[$k] = json_encode($images);
                }

            } else {
                unset($formData[$k]);
            }
        }
        return $formData;
    }

    /**
     * 列表展示时改变为需要的格式[日期、时间、日期时间]
     * @param $tableData
     * @param string $modelName
     * @return mixed
     */
    public function changeTableData($tableData, string $modelName)
    {
        // 转换为数组[注意是否分页]
        $tableData = is_array($tableData) ? $tableData : $tableData->toArray();
        if (array_key_exists('total', $tableData) && array_key_exists('per_page', $tableData)) {
            $page = true;
            $data = $tableData['data'];
        } else {
            $page = false;
            $data = $tableData;
        }

        // 查询模块信息
        $module = \app\common\model\Module::where('model_name', $modelName)->find();
        // 查询字段信息
        $fields = self::getFields($module->table_name);
        if (empty($fields)) {
            return $tableData;
        }
        $fieldsNew = [];
        foreach ($fields as $k => $v) {
            $fieldsNew[$v['field']] = $v;
        }
        foreach ($data as $k => $v) {
            foreach ($fieldsNew as $kk => $vv) {
                // 只对列表中出现的字段做处理，添加时间和修改时间不做处理
                if (array_key_exists($kk, $v) && $kk != 'create_time' && $kk != 'update_time') {
                    // 改变日期等格式
                    if ($vv['type'] == 'date' || $vv['type'] == 'time' || $vv['type'] == 'datetime') {
                        // 使用每个字段设定的格式
                        if ($vv['type'] == 'time') {
                            $format = str_replace("HH", "h", $vv['setup']['format']);
                            $format = str_replace("mm", "i", $format);
                            $format = str_replace("ss", "s", $format);
                            $format = $format ?: 'H:i:s';
                        } else {
                            $format = str_replace("yyyy", "Y", $vv['setup']['format']);
                            $format = str_replace("mm", "m", $format);
                            $format = str_replace("dd", "d", $format);
                            $format = str_replace("hh", "h", $format);
                            $format = str_replace("ii", "i", $format);
                            $format = str_replace("ss", "s", $format);
                            $format = $format ?? 'Y-m-d H:i:s';
                        }
                        $data[$k][$kk] = date($format, $data[$k][$kk]);
                    }
                }
            }
        }
        $page == true ? $tableData['data'] = $data : $tableData = $data;;
        return $tableData;
    }
    //==============================================

    /**
     * 获取不可生成的模块[内置模块][模型名称]
     * @return array
     */
    public function unMakeModule()
    {
        return ['Field', 'Module', 'AuthGroup', 'Admin', 'AuthRule', 'AdminLog', 'Cate'];
    }

    /**
     * 生成模块文件
     * @param string $id 模块ID
     * @return bool
     */
    public function makeModule(string $id)
    {
        // 不可生成的模块[内置模块][模型名称]
        $unMakeModule = $this->unMakeModule();

        // 查询模块信息
        $module = \app\common\model\Module::find($id);
        if (!$module) {
            return ['error' => 1, 'msg' => '模块查找有误'];
        }
        // 是否可生成
        if (in_array($module->model_name, $unMakeModule)) {
            return ['error' => 1, 'msg' => '系统内置模块不可以生成'];
        }

        // 生成控制器
        $this->makeController($module->model_name, $module->table_name);
        // 生成模型
        $this->makeModel($module->model_name, $module->table_name);
        // 生成验证器
        $this->makeValidate($module->model_name, $module->table_name);

        return ['error' => 0, 'msg' => '生成完毕'];
    }

    /**
     * 生成控制器文件
     * @param string $fileName  文件名称|模型名称
     * @param string $tableName 表名称
     */
    private function makeController(string $fileName, string $tableName)
    {
        // 取得要生成的文件
        $newFile = app_path() . 'controller' . DIRECTORY_SEPARATOR . $fileName . '.php';

        // 取得模版文件
        $fileBase = base_path() . 'common' . DIRECTORY_SEPARATOR . 'builder' . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . 'Controller.php';
        $fh = fopen($fileBase, "r");
        $contents = fread($fh, filesize($fileBase));
        $contents = $this->makeReplace($contents, $tableName);
        if ($contents) {
            $this->checkFile($newFile);
            // 写入新文件
            $myFile = fopen($newFile, "w");
            fwrite($myFile, $contents);
            fclose($myFile);
        }
        fclose($fh);
        return true;
    }

    /**
     * 生成模型文件
     * @param string $fileName  文件名称|模型名称
     * @param string $tableName 表名称
     */
    private function makeModel(string $fileName, string $tableName)
    {
        // 取得要生成的文件
        $newFile = base_path() . 'common' . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . $fileName . '.php';

        // 取得模版文件
        $fileBase = base_path() . 'common' . DIRECTORY_SEPARATOR . 'builder' . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . 'Model.php';
        $fh = fopen($fileBase, "r");
        $contents = fread($fh, filesize($fileBase));
        $contents = $this->makeModelReplace($contents, $tableName);

        if ($contents) {
            $this->checkFile($newFile);
            // 写入新文件
            $myFile = fopen($newFile, "w");
            fwrite($myFile, $contents);
            fclose($myFile);
        }
        fclose($fh);
        return true;
    }

    /**
     * 生成验证器文件
     * @param string $fileName  文件名称|模型名称
     * @param string $tableName 表名称
     */
    private function makeValidate(string $fileName, string $tableName)
    {
        // 取得要生成的文件
        $newFile = app_path() . 'validate' . DIRECTORY_SEPARATOR . $fileName . '.php';

        // 取得模版文件
        $fileBase = base_path() . 'common' . DIRECTORY_SEPARATOR . 'builder' . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . 'Validate.php';
        $fh = fopen($fileBase, "r");
        $contents = fread($fh, filesize($fileBase));
        $contents = $this->makeValidateReplace($contents, $tableName);
        if ($contents) {
            $this->checkFile($newFile);
            // 写入新文件
            $myFile = fopen($newFile, "w");
            fwrite($myFile, $contents);
            fclose($myFile);
        }
        fclose($fh);
        return true;
    }

    /**
     * 替换模版中指定的公共字符
     * @param string $content 内容
     * @param string $tableName 表名称
     * @return mixed|string
     */
    private function makeReplace(string $content, string $tableName)
    {
        // 查询模块信息
        $module = \app\common\model\Module::where('table_name', $tableName)->find();
        // 替换
        $content = str_replace('{$comment}'   , $module->table_comment , $content);
        $content = str_replace('{$author}'    , 'siyu'                 , $content);
        $content = str_replace('{$email}'     , '407593529@qq.com'     , $content);
        $content = str_replace('{$dateTime}'  , date("Y/m/d")          , $content);
        $content = str_replace('{$tableName}' , $module->table_name    , $content);
        $content = str_replace('{$modelName}' , $module->model_name    , $content);
        $content = str_replace('{$validate}'  , $module->model_name    , $content);
        // 替换控制器添加和修改时`显示全部`按钮
        if ($module->show_all == 0) {
            $content = str_replace('{$showAll}', '$builder->hideShowAll();
        ', $content);
        } else {
            $content = str_replace('{$showAll}', '', $content);
        }
        // 替换控制器列表页面顶部按钮组中添加按钮的参数，如 cate_id,多个用`,`分割
        if ($module->add_param) {
            $arrUrl = explode(",", $module->add_param);
            $arrUrlStr = '';
            foreach ($arrUrl as $k => $v) {
                $arrUrlStr .= '\'' . $v . '\'=>Request::param(\'' . $v . '\'),';
            }
            $arrUrlStr = rtrim($arrUrlStr, ',');
            $arrUrlStr = "url('add',[{$arrUrlStr}])->__toString()";
            $content = str_replace('{$setAddUrl}', '->setAddUrl(' . $arrUrlStr . ')
            ', $content);
        } else {
            $content = str_replace('{$setAddUrl}', '', $content);
        }

        return $content;
    }

    /**
     * 替换模版中的字符
     * @param string $content 内容
     * @param string $tableName 表名称
     * @return mixed|string
     */
    private function makeModelReplace(string $content, string $tableName)
    {
        $content = $this->makeReplace($content, $tableName);

        // 查询模块信息
        $module = \app\common\model\Module::where('table_name', $tableName)->find();
        if (empty($module)) {
            return false;
        }
        // 查询该表是否存在关联的字段
        $fileds = \app\common\model\Field::where('module_id', $module->id)
            ->where('data_source', 2)
            ->select()
            ->toArray();
        // 初始化模型关联信息
        $relations = '';
        $listInfo  = '';
        foreach ($fileds as &$filed) {
            $relations .= 'public function ' . lcfirst($filed['relation_model']) . '()
    {
        return $this->belongsTo(\'' . $filed['relation_model'] . '\', \'' . $filed['field'] . '\');
    }';
            $listInfo .= 'if ($list[$k][\'' . $filed['field'] . '\']) {
            ';
            $listInfo .= '    $v[\'' . $filed['field'] . '\'] = $v->' . lcfirst($filed['relation_model']) . '->getData(\'' . $filed['relation_field'] . '\');';
            $listInfo .= '
            }';
        }

        $content = str_replace('{$relations}'   , $relations , $content);
        $content = str_replace('{$listInfo}'    , $listInfo  , $content);
        $content = str_replace('{$moduleTable}' , $this->checkModuleTable($module->table_name, $module->model_name), $content);
        return $content;
    }

    /**
     * 替换验证器中的字符
     * @param string $content 内容
     * @param string $tableName 表名称
     * @return mixed|string
     */
    private function makeValidateReplace(string $content, string $tableName)
    {
        $content = $this->makeReplace($content, $tableName);
        // 查询模块信息
        $module = \app\common\model\Module::where('table_name', $tableName)->find();
        if (empty($module)) {
            return false;
        }
        // 查询该表是否存在关联的字段
        $fileds = \app\common\model\Field::where('module_id', $module->id)
            ->select()
            ->toArray();
        $rules = [];
        foreach ($fileds as &$filed) {
            if (in_array($filed['field'], ['create_time', 'update_time'])) {
                continue;
            }
            $rule = [];
            if ($filed['required'] == 1) {
                $rule['require'] = 'require';
            }
            if ($filed['maxlength'] > 0) {
                $rule['max'] = $filed['maxlength'];
            }
            if ($filed['minlength'] > 0) {
                $rule['min'] = $filed['minlength'];
            }
            if ($filed['type'] == 'number') {
                $rule['number'] = 'number';
            }
            if (!empty($rule)) {
                $rules[$filed['field'] . '|' . $filed['name']] = $rule;
            }
        }
        // 转换为需要的字符串
        $rulesStr = '';
        foreach ($rules as $k => $v) {
            $rulesStr .= '\'' . $k . '\' => [
        ';
            foreach ($v as $kk => $vv) {
                $rulesStr .= '    \'' . $kk . '\' => \'' . $vv . '\',
        ';
            }

            $rulesStr .= '],
        ';
        }
        if (!empty($rulesStr)) {
            $rulesStr = rtrim($rulesStr, ',
        ');
            $rulesStr = 'protected $rule = [
        ' . $rulesStr . '
    ];';
        }
        $content = str_replace('{$rules}'   , $rulesStr , $content);
        return $content;
    }

    /**
     * 判断是否存在某个文件，存在则更改文件名称做备份
     * @param string $file
     */
    private function checkFile(string $file)
    {
        if (file_exists($file)) {
            rename($file, $file . '_' . time() . '_back');
        }
    }

    /**
     * 生成模型时替换模型文件中的table属性
     * @param string $tableName
     * @param string $modelName
     * @return string
     */
    public function checkModuleTable(string $tableName, string $modelName)
    {
        // ThinkPHP规范的模型名称
        $moduleNameNew = '';
        $tableNameArr = explode('_', $tableName);
        foreach ($tableNameArr as $k => $v) {
            $moduleNameNew .= ucfirst($v);
        }
        if ($modelName == $moduleNameNew) {
            return '';
        } else {
            $tableName = \think\facade\Config::get('database.connections.mysql.prefix') . $tableName;
            return 'protected $table = \'' . $tableName . '\';';
        }
    }

    /**
     * 生成模块的菜单规则
     * @param string $id
     */
    public function makeRule(string $id)
    {
        $module = \app\common\model\Module::find($id);
        if ($module) {
            if ($module->table_type == 1) {
                $pid = \app\common\model\AuthRule::where('title', '=', '内容管理')->value('id');
            }
            $data = [
                'pid'    => $pid ?? 0,
                'name'   => $module->model_name . '/index',
                'title'  => $module->module_name,
                'sort'   => 50,
                'status' => isset($pid) && !empty($pid) ? 1 : 0,
            ];
            // 查询是否已存在，存在的不再处理
            $rule = \app\common\model\AuthRule::where('name', $module->model_name . '/index')->find();
            if ($rule) {
                return ['error' => 1, 'msg' => '当前规则已存在,无法生成，请先删除'];
            }
            // 列表规则
            $rule = \app\common\model\AuthRule::create($data);
            if ($rule) {
                $data = [];
                // 添加规则
                if (strpos($module->top_button, 'add') !== false) {
                    $data[] = [
                        'pid'   => $rule->id,
                        'name'  => $module->model_name . '/add',
                        'title' => '操作-添加',
                        'sort'  => 1,
                        'status'=> 0,
                    ];
                    $data[] = [
                        'pid'   => $rule->id,
                        'name'  => $module->model_name . '/addPost',
                        'title' => '操作-添加保存',
                        'sort'  => 2,
                        'status'=> 0,
                    ];
                }
                // 修改规则
                if (strpos($module->top_button, 'edit') !== false) {
                    $data[] = [
                        'pid'   => $rule->id,
                        'name'  => $module->model_name . '/edit',
                        'title' => '操作-修改',
                        'sort'  => 3,
                        'status'=> 0,
                    ];
                    $data[] = [
                        'pid'   => $rule->id,
                        'name'  => $module->model_name . '/editPost',
                        'title' => '操作-修改保存',
                        'sort'  => 4,
                        'status'=> 0,
                    ];
                }
                // 删除规则
                if (strpos($module->top_button, 'del') !== false) {
                    $data[] = [
                        'pid'   => $rule->id,
                        'name'  => $module->model_name . '/del',
                        'title' => '操作-删除',
                        'sort'  => 5,
                        'status'=> 0,
                    ];
                    $data[] = [
                        'pid'   => $rule->id,
                        'name'  => $module->model_name . '/selectDel',
                        'title' => '操作-批量删除',
                        'sort'  => 6,
                        'status'=> 0,
                    ];
                }
                // 导出规则
                if (strpos($module->top_button, 'export') !== false) {
                    $data[] = [
                        'pid'   => $rule->id,
                        'name'  => $module->model_name . '/export',
                        'title' => '操作-导出',
                        'sort'  => 7,
                        'status'=> 0,
                    ];
                }
                // 排序规则
                if ($module->is_sort) {
                    $data[] = [
                        'pid'   => $rule->id,
                        'name'  => $module->model_name . '/sort',
                        'title' => '操作-排序',
                        'sort'  => 8,
                        'status'=> 0,
                    ];
                }
                // 排序规则
                if ($module->is_status) {
                    $data[] = [
                        'pid'   => $rule->id,
                        'name'  => $module->model_name . '/state',
                        'title' => '操作-状态',
                        'sort'  => 9,
                        'status'=> 0,
                    ];
                }
                $authRule = new \app\common\model\AuthRule();
                $authRule->saveAll($data);
                return ['error' => 0, 'msg' => '生成完毕'];
            }
        }
        echo $id;
    }


}
