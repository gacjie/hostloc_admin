<?php
declare(strict_types = 1);

namespace app;

use think\facade\Lang;
use think\Service;

/**
 * 应用服务类
 */
class AppService extends Service
{
    public function register()
    {
        // 服务注册
        // 加载系统语言包
        Lang::load([
            $this->app->getRootPath() .'/lang/'.Lang::getLangSet().'.php'
        ]);
    }

    public function boot()
    {
        
        // 服务启动
    }
}
