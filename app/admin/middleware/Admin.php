<?php
/**
 * +----------------------------------------------------------------------
 * | 后台中间件
 * +----------------------------------------------------------------------
 */
namespace app\admin\middleware;

use think\facade\Session;
use think\facade\Request;
use think\Response;
use think\exception\HttpResponseException;

class Admin
{
    public function handle($request, \Closure $next)
    {
    	
        // 获取当前用户
        $admin_id = Session::get('admin.id');
        if (empty($admin_id)) {
            return redirect((string)url('login/index'));
        }

        // 定义方法白名单
        $allow = [
            'Index/index',      // 首页
            'Index/clear',      // 清除缓存
            'Upload/index',     // 上传文件
            'Upload/attachment',    //附件上传
            'Login/index',      // 登录页面
            'Login/signin', // 校验登录
            'Login/logout',     // 退出登录
        ];

        
        $authRole = \app\common\model\AuthRule::select();
        

        // 查找当前控制器和方法，控制器首字母大写，方法名首字母小写 如：Index/index
        
        $route = app('http')->getName()."/".Request::controller() . '/' . lcfirst(Request::action());
		
        $flag = false;
		foreach ($allow as $auth){
			if(strtolower($auth)==strtolower($route)){
				$flag = true;
			}
		}
		
		if ($admin_id != 1&&!$flag) {
			//开始认证
			$auth = new \Auth();
		
			$result = $auth->check($route, $admin_id);
			if (!$result) {
				$this->error('您无此操作权限!');
			}
		}
        // 权限认证
        /* if (!in_array($route, $allow)) {
            if ($admin_id != 1) {
                //开始认证
                $auth = new \Auth();

                $result = $auth->check($route, $admin_id);
                if (!$result) {
                    $this->error('您无此操作权限!');
                }
            }
        } */

        // 中间件handle方法的返回值必须是一个Response对象。
        return $next($request);
    }

    /**
     * 操作错误跳转
     * @param  mixed $msg 提示信息
     * @param  string $url 跳转的URL地址
     * @param  mixed $data 返回的数据
     * @param  integer $wait 跳转等待时间
     * @param  array $header 发送的Header信息
     * @return void
     */
    protected function error($msg = '', string $url = null, $data = '', int $wait = 3, array $header = []): Response
    {
    	if (is_null($url)) {
            $url = request()->isAjax() ? '' : 'javascript:history.back(-1);';
        } elseif ($url) {
            $url = (strpos($url, '://') || 0 === strpos($url, '/')) ? $url : app('route')->buildUrl($url);
        }
    
    	$result = [
	    	'code' => 0,
	    	'msg'  => $msg,
	    	'data' => $data,
	    	'url'  => $url,
	    	'wait' => $wait,
    	];
    
    	$type = (request()->isJson() || request()->isAjax()) ? 'json' : 'html';

        // 所有form返回的都必须是json，所有A链接返回的都必须是Html
        //$type = request()->isGet() ? 'html' : $type;
        //var_dump($type);exit();
    	if ($type == 'html'){
    		$response = view(app('config')->get('app.dispatch_error_tmpl'), $result);
    	} else if ($type == 'json') {
    		$response = json($result);
    	}
    	
    	throw new HttpResponseException($response);
    }
}
