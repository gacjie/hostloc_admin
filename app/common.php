<?php

use think\facade\App;



function get_addon_config($addon)
{
    $obejct = get_addons_instance($addon);
    // 获取token token永不过期
    return  $obejct->getConfig();
}
function app_url($app, $url, $arr = [])
{
    $maps = config('app.app_map');
    $appMap = $app;
    foreach ($maps as $map => $appName) {
        if ($appName == $app) {
            $appMap = $map;
        }
    }
    return url($appMap . '/' . $url, $arr)->build();
}
function get_addon_allconfig(string $file)
{
    $config = include(App::instance()->addons->getAddonsPath() . $file . DIRECTORY_SEPARATOR . 'config.php');
    return $config;
}
// 应用公共文件
/**
 * 生成随机数
 * @param $len
 * @return string
 */
function GetRandStr($len)
{
    $chars = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
    $charsLen = count($chars) - 1;
    shuffle($chars);
    $output = "";
    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}
function startWith($str, $needle)
{
    return strpos($str, $needle) === 0;
}

if (!function_exists('rmdirs')) {

    /**
     * 删除文件夹
     * @param string $dirname  目录
     * @param bool   $withself 是否删除自身
     * @return boolean
     */
    function rmdirs($dirname, $withself = true)
    {
        if (!is_dir($dirname)) {
            return false;
        }
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dirname, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }
        if ($withself) {
            @rmdir($dirname);
        }
        return true;
    }
}


function endWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }
    return (substr($haystack, -$length) === $needle);
}

/**
 * HTTP请求
 * @param string $url			请求地址
 * @param mixed $params			请求参数
 * @param int $requestType		请求类型
 * @param array $headers		请求头
 * @param int $timeout			请求超时
 * @return mixed
 */
function do_curl_request($url, $params = "", $requestType = 'post', $headers = ['Content-type:application/x-www-form-urlencoded;charset=UTF-8'], $timeout = 30, $options = [])
{
    if ($url == '' || $timeout <= 0) {
        return false;
    }

    //判断请求类型
    $requestHttp = array('post', 'get');
    if (false === in_array($requestType, $requestHttp)) {
        return false;
    }

    $curl = curl_init();

    $requestString = $params;
    if (true === is_array($params) || true === is_object($params)) {
        $requestString = http_build_query($params);
    }

    if ($options && is_array($options)) {
        foreach ($options as $key => $value) {
            curl_setopt($curl, $key, $value);
        }
    }
    //请求类型
    switch ($requestType) {
        case 'post': {
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $requestString);
            }
            break;
        case 'get': {
                if (is_string($requestString) && strlen($requestString) > 0) {
                    if (false === strpos($url, '?')) {
                        $url = $url . '?' . $requestString;
                    } else {
                        $url = $url . '&' . $requestString;
                    }
                }
            }
            break;
        default:
            break;
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    // 不验证证书
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    // 不验证HOST
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSLVERSION, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, (int) $timeout);

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
}
/**
 * 过滤数组元素前后空格 (支持多维数组)
 * @param $array 要过滤的数组
 * @return array|string
 */
function trim_array_element($array)
{
    if (!is_array($array)) {
        return trim($array);
    }
    return array_map('trim_array_element', $array);
}

/**
 * 将数据库中查出的列表以指定的 值作为数组的键名，并以另一个值作为键值
 * @param $arr
 * @param $key_name
 * @return array
 */
function convert_arr_kv($arr, $key_name, $value)
{
    $arr2 = array();
    foreach ($arr as $key => $val) {
        $arr2[$val[$key_name]] = $val[$value];
    }
    return $arr2;
}

function string2array($info)
{
    if ($info == '') {
        return array();
    }
    eval("\$r = $info;");
    return $r;
}

function array2string($info)
{
    //删除空格，某些情况下字段的设置会出现换行和空格的情况
    if (is_array($info)) {
        if (array_key_exists('options', $info)) {
            $info['options'] = trim($info['options']);
        }
    }
    if ($info == '') {
        return '';
    }
    if (!is_array($info)) {
        //删除反斜杠
        $string = stripslashes($info);
    }
    foreach ($info as $key => $val) {
        $string[$key] = stripslashes($val);
    }
    $setup = var_export($string, true);
    return $setup;
}

/**
 * 文本域中换行标签输出
 * @param $info 内容
 * @return mixed
 */
function textareaBr($info)
{
    $info = str_replace("\r\n", "<br />", $info);
    $info = str_replace("\n", "<br />", $info);
    $info = str_replace("\r", "<br />", $info);
    return $info;
}

/**
 * 权限设置选中状态
 * @param $cate  列表
 * @param int $pid 父ID
 * @param $rules 规则
 * @return array
 */
function auth($cate, $pid = 0, $rules)
{
    $arr = array();
    $rulesArr = explode(',', $rules);
    foreach ($cate as $v) {
        if ($v['pid'] == $pid) {
            if (in_array($v['id'], $rulesArr)) {
                $v['checked'] = true;
            }
            $v['open'] = true;
            $arr[] = $v;
            $arr = array_merge($arr, auth($cate, $v['id'], $rules));
        }
    }
    return $arr;
}
/**
 * 节点状态
 * 权限设置选中状态
 * @param $cate  列表
 * @param int $pid 父ID
 * @param $rules 规则
 * @return array
 */
function authNew($cate, $pid = 0, $rules)
{
    $arr = array();
    $rulesArr = explode(',', $rules);
    foreach ($cate as $v) {
        if ($v['pid'] == $pid) {
            $v = array_merge($v, ['field' => 'node', 'spread' => true]);

            //$v['open'] = true;
            $subcate = authNew($cate, $v['id'], $rules);
            if ($subcate) {
                $v['children'] =  $subcate;
            }
            //解决tree回显bug
            if (in_array($v['id'], $rulesArr) && empty($subcate)) {
                $v['checked'] = true;
            } else {
                $v['checked'] = false;
            }
            unset($v['pid']);
            $arr[] = $v;
        }
    }
    return $arr;
}

/**
 * 根据插件标识获取属性
 * @param 插件标识
 * @return:
 */
if (!function_exists('whetherToUsePlugin')) {
    function whetherToUsePlugin(string $file)
    {
        $class = "\\addons\\{$file}\\Plugin";
        if (class_exists($class)) {
            // 容器类的工作由think\Container类完成，但大多数情况我们只需要通过app助手函数或者think\App类即可容器操作
            $object = app($class);
            $info   = $object->getInfo();
            //判断是否开启插件
            if ($info && $info['status'] == 1 && $info['install'] == 1) {
                //返回配置信息
                $info = $object->getConfig();
                return $info;
            }
        }
        return false;
    }
}

if (!function_exists('cdnurl')) {

    /**
     * 获取上传资源的CDN的地址
     * @param string  $url    资源相对地址
     * @param boolean $domain 是否显示域名 或者直接传入域名
     * @return string
     */
    function cdnurl($url, $domain = false)
    {
        $regex = "/^((?:[a-z]+:)?\/\/|data:image\/)(.*)/i";
        $url = preg_match($regex, $url) ? $url : \think\Config::get('upload.cdnurl') . $url;
        if ($domain && !preg_match($regex, $url)) {
            $domain = is_bool($domain) ? request()->domain() : $domain;
            $url = $domain . $url;
        }
        return $url;
    }
}

if (!function_exists('__')) {

    /**
     * 获取语言变量值
     * @param string $name 语言变量名
     * @param array  $vars 动态变量值
     * @param string $lang 语言
     * @return mixed
     */
    function __($name, $vars = [], $lang = '')
    {
        if (is_numeric($name) || !$name) {
            return $name;
        }
        if (!is_array($vars)) {
            $vars = func_get_args();
            array_shift($vars);
            $lang = '';
        }
        //        return $name;
        return \think\facade\Lang::get($name, $vars, $lang);
    }
}

if (!function_exists('var_export_short')) {

    /**
     * 返回打印数组结构
     * @param string $var 数组
     * @return string
     */
    function var_export_short($var)
    {
        return VarExporter::export($var);
    }
}

//写入日志
if (!function_exists('write_log')) {
    function write_log($remark, $data, $path = '', $filename = '')
    {
        $path = $path ? $path : app()->getRuntimePath() . 'cm_log/';
        $filename = $filename ? $filename : date('Ymd') . '.txt';
        $data = is_array($data) ? json_encode($data, JSON_UNESCAPED_UNICODE) : $data;
        if (!is_dir($path)) {
            mkdir($path, 0777);
        }
        if ($fp = fopen($path . $filename, "a")) {
            fwrite($fp, '[' . date("Y-m-d H:i:s") . '] ' . $remark . "\r\n" . $data . "\r\n");
            fclose($fp);
        }
    }
}


// 递归方法
//获取用户的所有下级ID
//默认5级
if (!function_exists('get_downline')) {
    function get_downline($datas, $pid, $max_level = 5, $level = 0)
    {
        $arr = array();
        foreach ($datas as $key => $v) {
            if ($v['id'] == $pid) {  //pid为0的是顶级分类
                $v['level'] = $level + 1;
                if ($v['level']  > $max_level) {
                    return $arr;
                }
                $arr[] = $v;
                $arr = array_merge($arr, get_downline($datas, $v['pid'], $max_level, $level + 1));
            }
        }
        return $arr;
    }
}
