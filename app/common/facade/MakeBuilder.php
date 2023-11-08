<?php
namespace app\common\facade;

use think\Facade;

class MakeBuilder extends Facade
{
    protected static function getFacadeClass()
    {
        return 'app\common\builder\MakeBuilder';
    }
}