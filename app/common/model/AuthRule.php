<?php
/**
 * 菜单规则模型
 */
namespace app\common\model;

// 引入框架内置类
use think\facade\Request;


class AuthRule extends Base
{
	// 开启自动写入时间戳字段
	protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    

    // 获取父ID选项信息
    public static function getPidOptions($order = ['weigh', 'id' => 'desc'])
    {
        $list = self::order($order)
            ->select()
            ->toArray();
        $list = tree($list);
        $result = [];
        foreach ($list as $k => $v) {
            $result[$v['id']] = $v['title'];
        }
        return $result;
    }
    public static function onAfterWrite($user) {
    	\think\facade\Cache::delete('__menu__');
    }
    public function getTitleAttr($value, $data)
    {
    	return lang($value);
    }
}