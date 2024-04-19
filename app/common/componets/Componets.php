<?php
namespace app\common\componets;

use think\template\TagLib;

class Componets extends TagLib
{
    protected $tags = [
        'uedit'=> ['attr'=>'name,content','close'=> 0]
    ];

    public function tagUedit($tag,$content){
        if(empty($tag['content'])) {
            $tag['content'] = '没有传入内容';
        }
        $html = ' <textarea name="'.$tag['name'].'" id="edit" cols="30" rows="10">'.$tag['content'] .'</textarea>';
        return $html;
    }

}