<?php
namespace app\common\http;

class Json{

    public function __construct(){

    }

    public static function success($msg='获取成功',$data=null,$count=0,$code=200){
        $data = [
            'status'=> $code,
            'data'=>$data,
            'msg'=>$msg,
            'count'=>$count
        ];
        return json($data);
    }

    public static function error($msg='请求错误',$data=null,$code=400){
        $data = [
            'status'=> $code,
            'data'=>$data,
            'msg'=>$msg,
        ];
        return json($data);
    }

}