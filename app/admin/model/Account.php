<?php

namespace app\admin\model;

use think\Model;

class Account extends Model
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    //protected $updateTime = 'updatetime';


    
public function getAddressIdsAttr($value,$data)
{
    $this->set('addressIdsList',\think\facade\Db::name('address')->field('id,name')->where('id','in',explode(',',$value))->select()) ;
    $this->append(array_merge($this->append,['addressIdsList']));
    return $value;
}

public function getUserAgentIdAttr($value,$data)
{
    $this->set('userAgentIdList',\think\facade\Db::name('user_agent')->field('id,name')->where('id',$value)->find()) ;
    $this->append(array_merge($this->append,['userAgentIdList']));
    return $value;
}
public function getUptimeAttr($value)
{
    if($value){
        return date('Y-m-d H:i:s',$value);
    }else{
        return null;
    }
   
}
public function setUptimeAttr($value)
{
    return strtotime($value);
}

    
public function scopeDateRange($query,$field,$data)
{
    if(is_string($data)){
        $arr  =explode(' - ',$data);
        if(count($arr)==2){
            $query->whereTime($field, 'between', $arr) ;
        }
    }
}
}