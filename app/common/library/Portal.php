<?php
/*
 * 微擎应用适配
 * */
namespace app\common\library;

class Portal{

    public function __construct(){
        $this->base_W();
    }

    private function base_W(){
        $_W=[];
//        $_W=array(
//            'siteroot'=>'',
//            'user'=>array(
//                'uid'=>'',
//                'name'=>'',
//                'username'=>'',
//            ),
//            'account'=>array(
//                'acid'=>'',
//                'name'=>'',
//            ),
//            'current_module'=>array(
//                'name'=>''
//            ),
//        );
        $_W['timestamp'] = time();
        $_W['clientip'] = $this->getip();

        $_W['ishttps'] = isset($_SERVER['SERVER_PORT']) && 443 == $_SERVER['SERVER_PORT'] ||
        isset($_SERVER['HTTP_FROM_HTTPS']) && 'on' == strtolower($_SERVER['HTTP_FROM_HTTPS']) ||
        (isset($_SERVER['HTTPS']) && 'off' != strtolower($_SERVER['HTTPS'])) ||
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && 'https' == strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) ||
        isset($_SERVER['HTTP_X_CLIENT_SCHEME']) && 'https' == strtolower($_SERVER['HTTP_X_CLIENT_SCHEME']) 			? true : false;


        $_W['isajax'] = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']);
        $_W['ispost'] = isset($_SERVER['REQUEST_METHOD']) && 'POST' == $_SERVER['REQUEST_METHOD'];

        $_W['sitescheme'] = $_W['ishttps'] ? 'https://' : 'http://';
        $_W['script_name'] = htmlspecialchars($this->scriptname());
        $sitepath = substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));
        $_W['siteroot'] = htmlspecialchars($_W['sitescheme'] . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $sitepath);

        if ('/' != substr($_W['siteroot'], -1)) {
            $_W['siteroot'] .= '/';
        }
        $urls = parse_url($_W['siteroot']);
        $urls['path'] = str_replace(array('/web', '/app', '/payment/wechat', '/payment/alipay', '/payment/jueqiymf', '/api'), '', $urls['path']);
        $urls['scheme'] = !empty($urls['scheme']) ? $urls['scheme'] : 'http';
        $urls['host'] = !empty($urls['host']) ? $urls['host'] : '';
        $_W['siteroot'] = $urls['scheme'] . '://' . $urls['host'] . ((!empty($urls['port']) && '80' != $urls['port']) ? ':' . $urls['port'] : '') . $urls['path'];
        $GLOBALS['_W'] =isset($GLOBALS['_W'])? $GLOBALS['_W']:$_W;
    }

    private function scriptname() {
        $script_name = basename($_SERVER['SCRIPT_FILENAME']);
        if (basename($_SERVER['SCRIPT_NAME']) === $script_name) {
            $script_name = $_SERVER['SCRIPT_NAME'];
        } else {
            if (basename($_SERVER['PHP_SELF']) === $script_name) {
                $script_name = $_SERVER['PHP_SELF'];
            } else {
                if (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $script_name) {
                    $script_name = $_SERVER['ORIG_SCRIPT_NAME'];
                } else {
                    if (false !== ($pos = strpos($_SERVER['PHP_SELF'], '/'))) {
                        $script_name = substr($_SERVER['SCRIPT_NAME'], 0, $pos) . '/' . $script_name;
                    } else {
                        if (isset($_SERVER['DOCUMENT_ROOT']) && 0 === strpos($_SERVER['SCRIPT_FILENAME'], $_SERVER['DOCUMENT_ROOT'])) {
                            $script_name = str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']));
                        } else {
                            $script_name = 'unknown';
                        }
                    }
                }
            }
        }
        return $script_name;
    }

    private function getip() {
        static $ip = '';
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        if (isset($_SERVER['HTTP_CDN_SRC_IP'])) {
            $ip = $_SERVER['HTTP_CDN_SRC_IP'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
            foreach ($matches[0] as $xip) {
                if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                    $ip = $xip;
                    break;
                }
            }
        }
        if (preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $ip)) {
            return $ip;
        } else {
            return '127.0.0.1';
        }
    }




}