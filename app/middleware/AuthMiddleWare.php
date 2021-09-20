<?php

declare(strict_types=1);

namespace app\middleware;

use app\Request;
use think\facade\Config;
use think\facade\Cache;
use app\Tools;
use sent\jwt\facade\JWTAuth;

class AuthMiddleWare
{
    protected $header = [
        "Access-Control-Allow-Origin: *",
        'Access-Control-Allow-Credentials' => 'true',
        'Access-Control-Max-Age'           => 1800,
        'Access-Control-Allow-Methods'     => 'GET, POST, PATCH, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers'     => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-CSRF-TOKEN, X-Requested-With',
    ];
    //允许前端发送options请求
    public function handle(Request $request, \Closure $next)
    {
        $user = JWTAuth::auth(); //token验证
        if ($user) {
            return $next($request);
        } else {
            throw new \think\exception\HttpException(401, json_encode($user), null, [], 401);
        }
        // if ($access_token) {
        //     $result = checkToken($access_token);
        //     if ($result['code'] == 200) {
        //         if ($result['data']->userinfo) {
        //             //判断redis是否存在登录用户的token，并且redis存在的token要和请求头的token保持一致，作为单点登录
        //             $token = Cache::store('redis')->get($prefix .  $result['data']->userinfo->id);
        //             if (!is_null($token) && $access_token == $token) {
        //                 return $next($request);
        //             } else {
        //                 throw new \think\exception\HttpException(Tools::CODE401, 'Token已失效！', null, [], Tools::CODE401);
        //             }
        //         }
        //     } else {
        //         throw new \think\exception\HttpException(Tools::CODE401, $result['msg'], null, [], Tools::CODE401);
        //     }
        // } else {
        //     throw new \think\exception\HttpException(Tools::CODE401, 'Token不能为NULL！', null, [], Tools::CODE401);
        // }
    }
}
