<?php
namespace app\admin\model;

use think\facade\Session;
use think\Model;
use think\facade\Route;

class Base extends Model
{
    // 获取左侧主菜单
    public static function getMenus()
    {
        $authRule = \app\common\model\AuthRule::where('status', 'normal')
            ->where('ismenu', 1)
            ->order('weigh asc')
            ->select()
            ->toArray();

        $menus = [];
        // 查找一级
        foreach ($authRule as $key => $val) {
        	if(strpos($val['name'], "addons")===0){
            	$authRule[$key]['href'] = Route::buildUrl("@".$val['name']);
        	}else{
            	$authRule[$key]['href'] = (string)url($val['name']);
        	}
            $val['href'] = (string)url($val['name']);
            if ($val['pid'] == 0) {
                if (Session::get('admin.id') != 1) {
                    if (in_array($val['id'], Session::get('admin.rules', []))) {
                        $menus[] = $val;
                    }
                } else {
                    $menus[] = $val;
                }
            }
        }
        // 查找二级
        foreach ($menus as $k => $v) {
            $menus[$k]['children'] = [];
            foreach ($authRule as $kk => $vv) {
                if ($v['id'] == $vv['pid']) {
                    if (Session::get('admin.id') != 1) {
                        if (in_array($vv['id'], Session::get('admin.rules'))) {
                            $menus[$k]['children'][] = $vv;
                        }
                    } else {
                        $menus[$k]['children'][] = $vv;
                    }
                }
            }
        }
        // 查找三级
        foreach ($menus as $k => $v) {
            if ($v['children']) {
                // 循环二级
                foreach ($v['children'] as $kk => $vv) {
                    $menus[$k]['children'][$kk]['children'] = [];
                    foreach ($authRule as $kkk => $vvv) {
                        if ($vv['id'] == $vvv['pid']) {
                            if (Session::get('admin.id') != 1) {
                                if (in_array($vvv['id'], Session::get('admin.rules'))) {
                                    $menus[$k]['children'][$kk]['children'][] = $vvv;
                                }
                            } else {
                                $menus[$k]['children'][$kk]['children'][] = $vvv;
                            }
                        }
                    }
                }
            }
        }
        return $menus;
    }
}