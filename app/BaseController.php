<?php
declare(strict_types = 1);

namespace app;

use think\App;
use think\exception\ValidateException;
use think\Validate;
use think\exception\HttpResponseException;
use think\facade\Request;
use think\Response;
use think\facade\Config;
use think\facade\View;

use think\facade\Lang;
use think\helper\Str;

/**
 * 基本常量定义
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        //获取网站基本配置
        $site = Config::get('site');
        View::assign('site', $site);
        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {
        $this->loadLang();
    }
    
    protected function loadLang()
    {
        $controller = Str::snake($this->request->controller());
        $action = Str::snake($this->request->action());
        $module = $this->app->http->getName();
        //获取当前模块名称
       


        if (!$this->request->addon) {
            $path = $this->app->getAppPath().'/lang/'.Lang::getLangSet().'/'.$controller.'/'.$action.'.php';
            Lang::load($this->app->getAppPath().'/lang/'.Lang::getLangSet().'.php');
            if ($action!=='common') {
                Lang::load($this->app->getAppPath().'/lang/'.Lang::getLangSet().'/'.$controller.'/common.php');
            }
          
            Lang::load($path);
        } else {
            Lang::load($this->app->getRootPath().'/addons/'.$this->request->addon.'/lang/'.Lang::getLangSet().'.php');
            Lang::load($this->app->getAppPath().'/lang/'.Lang::getLangSet().'/addons.php');
        }
    }

    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }
    
    
    
    //
    // 以下为新增，为了使用旧版的 success error redirect 跳转  start
    //
    
    /**
     * 操作成功跳转的快捷方法
     * @access protected
     * @param  mixed     $msg 提示信息
     * @param  string    $url 跳转的URL地址
     * @param  mixed     $data 返回的数据
     * @param  integer   $wait 跳转等待时间
     * @param  array     $header 发送的Header信息
     * @return void
     */
    protected function success($msg = '', string $url = null, $data = '', int $wait = 3, array $header = [])
    {
        if (is_null($url) && isset($_SERVER["HTTP_REFERER"])) {
            $url = $_SERVER["HTTP_REFERER"];
        } elseif ($url) {
            $url = (strpos($url, '://') || 0 === strpos($url, '/')) ? $url : app('route')->buildUrl($url);
        }
    
        $result = [
        'code' => 1,
        'msg'  => $msg,
        'data' => $data,
        'url'  => $url,
        'wait' => $wait,
        ];
    
        $type = $this->getResponseType();
        if ($type == 'html') {
            $response = view($this->app->config->get('app.dispatch_success_tmpl'), $result);
        } elseif ($type == 'json') {
            $response = json($result);
        }
        throw new HttpResponseException($response);
    }
    
    /**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param  mixed     $msg 提示信息
     * @param  string    $url 跳转的URL地址
     * @param  mixed     $data 返回的数据
     * @param  integer   $wait 跳转等待时间
     * @param  array     $header 发送的Header信息
     * @return void
     */
    protected function error($msg = '', string $url = null, $data = '', int $wait = 3, array $header = [])
    {
        if (is_null($url)) {
            $url = $this->request->isAjax() ? '' : 'javascript:history.back(-1);';
        } elseif ($url) {
            $url = (strpos($url, '://') || 0 === strpos($url, '/')) ? $url : $this->app->route->buildUrl($url);
        }
    
        $result = [
        'code' => 0,
        'msg'  => $msg,
        'data' => $data,
        'url'  => $url,
        'wait' => $wait,
        ];
    
        $type = $this->getResponseType();
        if ($type == 'html') {
            $response = view($this->app->config->get('app.dispatch_error_tmpl'), $result);
        } elseif ($type == 'json') {
            $response = json($result);
        }
        throw new HttpResponseException($response);
    }
    
    /**
     * URL重定向  自带重定向无效
     * @access protected
     * @param  string         $url 跳转的URL表达式
     * @param  array|integer  $params 其它URL参数
     * @param  integer        $code http code
     * @param  array          $with 隐式传参
     * @return void
     */
    protected function redirect($url, $params = [], $code = 302, $with = [])
    {
        redirect($url, $code)->send();
    }
    
    /**
     * 获取当前的response 输出类型
     * @access protected
     * @return string
     */
    protected function getResponseType()
    {
        return $this->request->isJson() || $this->request->isAjax() ? 'json' : 'html';
    }
    
    //
    // 以上为新增，为了使用旧版的 success error redirect 跳转  end
    //
}
