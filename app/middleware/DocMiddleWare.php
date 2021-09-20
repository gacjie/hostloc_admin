<?php


namespace app\middleware;

use app\Request;

class DocMiddleWare
{
    //修改doc返回的数据
    public function handle(Request $request, \Closure $next)
    {
        $result =  $next($request);
        if($result instanceof \think\response\Json){
            $data = $result->getData();
            foreach($data['data']['param'] as &$item){
                if($item['default']=='null'){
                    $item['default']='';
                }
            }
            unset($item);
            foreach($data['data']['return'] as &$item){
                if($item['default']=='null'){
                    $item['default']='';
                }
            }
            return json($data);
        }
        return $result;
    }
}
