<?php
namespace app\common\controller;

use think\App;
use think\helper\Str;
use think\facade\Config;
use think\facade\View;
use app\admin\controller\AdminBase;
use app\admin\model\Plugin;

class AddonBase extends AdminBase
{
    // app 容器
    protected $app;
    // 请求对象
    protected $request;
    // 当前插件标识
    protected $name;
    // 插件路径
    protected $addon_path;
    // 视图模型
    protected $view;
    // 插件配置
    protected $addon_config;
    // 插件信息
    protected $addon_info;
    /**
     * 插件构造函数
     * Addons constructor.
     * @param \think\App $app
     */
    public function __construct(App $app)
    {
        $this->view = clone View::engine('Think');
        
        parent::__construct($app);
        $this->app = $app;
        $this->request = $app->request;
        $this->name = $this->getName();
        $this->addon_path = $app->addons->getAddonsPath() . $this->name . DIRECTORY_SEPARATOR;
        $this->addon_config = "addon_{$this->name}_config";
        $this->addon_info = "addon_{$this->name}_info";
        $info = $this->getInfo();
        if ($info['install']!=1||$info['status']!=1) {
            echo "插件不可用";
            exit();
        }
        //var_dump($this->addon_path);
        $this->view->config([
            'view_path' => $this->addon_path . 'view' . DIRECTORY_SEPARATOR
        ]);
    }
    /**
     * 获取插件标识
     * @return mixed|null
     */
    final protected function getName()
    {
        $name = $this->request->param('addon');
        $this->request->addon = $name;
    
        return $name;
    }
    /**
     * 渲染内容输出
     * @access protected
     * @param  string $content 模板内容
     * @param  array  $vars    模板输出变量
     * @return mixed
     */
    protected function display($content = '', $vars = [])
    {
        return $this->view->display($content, $vars);
    }
    
    
    
    /**
     * 加载模板输出
     * @param string $template
     * @param array $vars           模板文件名
     * @return false|mixed|string   模板输出变量
     * @throws \think\Exception
     */
    protected function fetch($template = '', $vars = [])
    {
        return $this->view->fetch($template, $vars);
    }
    
    /**
     * 模板变量赋值
     * @access protected
     * @param  mixed $name  要显示的模板变量
     * @param  mixed $value 变量的值
     * @return $this
     */
    protected function assign($name, $value = '')
    {
        $this->view->assign([$name => $value]);
    
        return $this;
    }
    
    /**
     * 初始化模板引擎
     * @access protected
     * @param  array|string $engine 引擎参数
     * @return $this
     */
    protected function engine($engine)
    {
        $this->view->engine($engine);
    
        return $this;
    }
    
    /**
     * 插件基础信息
     * @return array
     */
    final public function getInfo()
    {
        $addon_info = "addon_{$this->name}_info";
        
        /* $info = Config::get($addon_info, []);
    	if ($info) {
    		return $info;
    	} */
        $object = $this->getInstance($this->name);
        // 文件属性
        $info = $object->info ?? [];
        // 文件配置
        $info_file = $this->addon_path . 'info.ini';
    
        if (is_file($info_file)) {
            $_info = parse_ini_file($info_file, true, INI_SCANNER_TYPED) ?: [];
    
            $_info['url'] = addons_url();
            $info = array_merge($info, $_info);
        }
        
        /* $tableinfo = Plugin::where('name', $this->name)->field('name,title,description,status,author,version,install')->find();
        if($tableinfo) {
            $info = array_merge( $info,$tableinfo->toArray());
        }else{
            $info['install'] = 0;
            $info['status'] = 0;
        } */
        Config::set($info, $addon_info);
    
        return isset($info) ? $info : [];
    }
    // 获取插件实例
    private function getInstance(string $file)
    {
        $class = "\\addons\\{$file}\\Plugin";
        if (class_exists($class)) {
            // 容器类的工作由think\Container类完成，但大多数情况我们只需要通过app助手函数或者think\App类即可容器操作
            return app($class);
        }
        return false;
    }
    /**
     * 获取配置信息
     * @param bool $type 是否获取完整配置
     * @return array|mixed
     */
    final public function getConfig($type = false)
    {
        $config = Config::get($this->addon_config, []);
        if ($config) {
            return $config;
        }
        $config_file = $this->addon_path . 'config.php';
        if (is_file($config_file)) {
            $temp_arr = (array)include $config_file;
            if ($type) {
                return $temp_arr;
            }
            foreach ($temp_arr as $key => $value) {
                $config[$key] = $value['value'];
            }
            unset($temp_arr);
        }
        Config::set($config, $this->addon_config);
    
        return $config;
    }
}
