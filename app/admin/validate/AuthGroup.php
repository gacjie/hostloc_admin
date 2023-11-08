<?php
/**
 * +----------------------------------------------------------------------
 * | 角色组管理验证器
 * +----------------------------------------------------------------------
 */
namespace app\admin\validate;

use think\Validate;

class AuthGroup extends Validate
{
    protected $rule = [
        /* 'status|状态' => [
            'require' => 'require',
            //'max' => '1',
        ], */
        'title|角色组' => [
            'require' => 'require',
        ]
    ];
}