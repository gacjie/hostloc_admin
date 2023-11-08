<?php
namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{
	protected $rule = [
		'username'  =>  'require|max:20|unique:admin',
		'password' =>  'require|min:6',
		'email' =>  'email|unique:admin',
	];
	protected $message  =   [
		'username.require' => '用户名必须',
		'username.unique' => '用户名必须唯一',
		'username.max'     => '用户名最多不能超过20个字符',
		'password.require'   => '密码必须',
		'password.min'  => '密码最少6位',
	];
	protected $scene = [
		'edit'  =>  ['username','email'],
	];
}