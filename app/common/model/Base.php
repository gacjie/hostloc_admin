<?php
/**
 * +----------------------------------------------------------------------
 * | 公共模型基类
 * +----------------------------------------------------------------------
 */
namespace app\common\model;
use think\Model;


class Base extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 通用修改数据
    public static function edit($id){
        $info = self::find($id);
        return $info;
    }

    // 通用修改保存
    public static function editPost($data)
    {
        if ($data) {
            foreach ($data as $k => $v) {
                if ($v && is_array($v)) {
                    $data[$k] = implode(',', $v);
                }
            }
        }

        $result = self::update($data);
        if ($result) {
            return ['error' => 0, 'msg' => '修改成功'];
        } else {
            return ['error' => 1, 'msg' => '修改失败'];
        }
    }

    // 通用添加保存
    public static function addPost($data){
        if ($data) {
            foreach ($data as $k => $v) {
                if ($v && is_array($v)) {
                    $data[$k] = implode(',', $v);
                }
            }
        }
        $result = self::create($data);
        if ($result) {
            return ['error' => 0, 'msg' => '添加成功'];
        } else {
            return ['error' => 1, 'msg' => '添加失败'];
        }
    }

    // 删除
    public static function del($id){
        self::destroy($id);
        return json(['error'=>0,'msg'=>'删除成功!']);
    }

    // 批量删除
    public static function selectDel($id){
        if ($id) {
            $ids = explode(',',$id);
            self::destroy($ids);
            return json(['error'=>0, 'msg'=>'删除成功!']);
        }else{
            return ['error' => 1, 'msg' => '删除失败'];
        }
    }

    // 排序修改
    public static function sort($data)
    {
        $info = self::find($data['id']);
        if ($info->weigh != $data['weigh']) {
            $info->weigh = $data['weigh'];
            $info->save();
            return json(['error' => 0, 'msg' => '修改成功!']);
        }
    }

    // 状态修改
    public static function state($id){
        $info = self::find($id);
        $info->status = $info['status'] == 'normal' ? 'hidden' : 'normal';
        $info->save();
        return json(['error'=>0, 'msg'=>'修改成功!']);
    }

    
}