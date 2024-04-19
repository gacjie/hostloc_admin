<?php
/**
 * +----------------------------------------------------------------------
 * | 菜单规则验证器
 * +----------------------------------------------------------------------
 */
namespace app\admin\validate;

use think\Validate;

class AuthRule extends Validate
{
    protected $rule = [
        /* 'status|菜单状态' => [
            'require' => 'require',
        ], */
        'name|控制器/方法' => [
            'require' => 'require',
            'unique'  => 'auth_rule', // 唯一
        ],
        'title|权限名称' => [
            'require' => 'require',
        ]
    ];
}