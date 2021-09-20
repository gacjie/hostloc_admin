<?php
namespace app\admin\controller;

use app\BaseController;
use think\facade\View;
use think\facade\Db;
use think\facade\Cache;
use think\facade\Event;
use think\facade\Config;

class Upload extends BaseController
{
  
    /**
     * layui 文件上传接口
     */
    public function index()
    {
        // file('文件域的字段名')
        $file = request()->file('file');
    
        // 上传到本地服务器 返回文件存储位置
        //
        // disk('磁盘配置名称') 该配置 在 config/filesystem.php中的 disks 中查看
        // disk('public') 代表使用的是 disks 中的 public 键名对应的磁盘配置
        // putFile('目录名', $file);
        //
        // $savename 执行上传 返回文件存储位置
        //
        // 当前文件存储位置：public/storage/topic/当前时间/文件名
        $savename = \think\facade\Filesystem::disk('public')->putFile('topic', $file);
    
        
    
        if ($savename) {
            $savename = str_replace(DIRECTORY_SEPARATOR, '/', $savename);
            $file_path = 'storage/'.$savename;
            $add['url'] = '/'.$file_path;
            $add['storage'] = '/'.$file_path;
            $add['filesize'] = filesize($file_path);
            $add['mimetype'] = mime_content_type($file_path);
            $add['sha1'] = sha1_file($file_path);
            $add['createtime'] = time();
            $add['updatetime'] = time();
            $add['uploadtime'] = time();
        
        
            //检测文件信息
            if (in_array($add['mimetype'], array('image/png','image/jpeg','image/gif','image/bmp'))) {
                list($width, $height, $type, $attr) = getimagesize($file_path);
                $add['imagewidth'] = $width;
                $add['imageheight'] = $height;
                $add['imagetype'] = $add['mimetype'];
            }
            Db::name('attachment')->insert($add);
            // 将上传后的文件位置返回给前端
            return json(['code' => 0, 'path' => $savename]);
        } else {
            return json(['code' => 1, 'msg' => '上传错误']);
        }
    }

    /**
     * 图片组件上传接口
     */
    public function uploadfile()
    {
        // file('文件域的字段名')
        $file = request()->file('file');
    
        // 上传到本地服务器 返回文件存储位置
        //
        // disk('磁盘配置名称') 该配置 在 config/filesystem.php中的 disks 中查看
        // disk('public') 代表使用的是 disks 中的 public 键名对应的磁盘配置
        // putFile('目录名', $file);
        //
        // $savename 执行上传 返回文件存储位置
        //
        // 当前文件存储位置：public/storage/topic/当前时间/文件名
        $savename = \think\facade\Filesystem::disk('public')->putFile('upload', $file);
        if ($savename) {
            $savename = str_replace(DIRECTORY_SEPARATOR, '/', $savename);
            $file_path = 'storage/'.$savename;
            $add['url'] = '/'.$file_path;
            $add['storage'] = '/'.$file_path;
            $add['filesize'] = filesize($file_path);
            $add['mimetype'] = mime_content_type($file_path);
            $add['sha1'] = sha1_file($file_path);
            $add['createtime'] = time();
            $add['updatetime'] = time();
            $add['uploadtime'] = time();
    
    
            //检测文件信息
            if (in_array($add['mimetype'], array('image/png','image/jpeg','image/gif','image/bmp'))) {
                list($width, $height, $type, $attr) = getimagesize($file_path);
                $add['imagewidth'] = $width;
                $add['imageheight'] = $height;
                $add['imagetype'] = $add['mimetype'];
            }
            Db::name('attachment')->insert($add);
            // 将上传后的文件位置返回给前端
            $this->success("上传成功", '', ['url' => $savename]);
        } else {
            $this->error("上传出错了");
        }
    }
    
    //附件上传
    public function attachment()
    {
        //钩子事件 存储插件
        Event::listen('Storage', 'addons\qiniu_storage\event\Storage');
        $hook_res = event('Storage')[0];
        if ($hook_res) {
            $url       = $hook_res['url'];
            $storage   = $hook_res['storage'];
            $savename  = $hook_res['fileName'];
            $file_path = $hook_res['fileGetRealPath'];
            $mimetype  = $hook_res['fileGetOriginalMime'];
//            dd($hook_res); //本地存储逻辑也可以写钩子里面
        } else {
            $file      = request()->file('file');
            $savename  = \think\facade\Filesystem::disk('public')->putFile('attachment', $file);
            $url = $file_path = 'storage/' . $savename;
            $storage = 'localhost';
            $mimetype  = $file->getOriginalExtension();  //mime_content_type 5.3已经废弃
        }
        $add['url'] = '/'.$url;
        $add['storage'] = $storage;
        $add['filesize'] = filesize($file_path);
        $add['mimetype'] = $mimetype;
        $add['sha1'] = sha1_file($file_path);
        $add['createtime'] = time();
        $add['updatetime'] = time();
        $add['uploadtime'] = time();

        //检测文件信息
        if (in_array($add['mimetype'], array('image/png','image/jpeg','image/gif','image/bmp'))) {
            list($width, $height, $type, $attr) = getimagesize($file_path);
            $add['imagewidth'] = $width;
            $add['imageheight'] = $height;
            $add['imagetype'] = $add['mimetype'];
        }

        $key = md5(time().rand(1000, 9999));
        Cache::set($key, $add);
        if ($this->request->param('save')==1) {
            Db::name('attachment')->insert($add);
        }
      
        // 将上传后的文件位置返回给前端
        return json(['code' => 0, 'path' => $savename,'key'=>$key]);
    }
    /**
     * 上传图片至编辑器
     * @return \think\response\Json
     */
    public function uploadEditor()
    {
        $file = request()->file('upload');
        if (!$file) {
            $this->error("请选择文件");
        }
        $savename = \think\facade\Filesystem::disk('public')->putFile('upload', $file);
        
        if ($savename) {
            $savename = str_replace(DIRECTORY_SEPARATOR, '/', $savename);
            $file_path = 'storage/'.$savename;
            $add = [];
            $add['url'] = '/'.$file_path;
            $add['storage'] = '/'.$file_path;
            $add['filesize'] = filesize($file_path);
            $add['mimetype'] = mime_content_type($file_path);
            $add['sha1'] = sha1_file($file_path);
            $add['createtime'] = time();
            $add['updatetime'] = time();
            $add['uploadtime'] = time();
            
            
            //检测文件信息
            if (in_array($add['mimetype'], array('image/png','image/jpeg','image/gif','image/bmp'))) {
                list($width, $height, $type, $attr) = getimagesize($file_path);
                $add['imagewidth'] = $width;
                $add['imageheight'] = $height;
                $add['imagetype'] = $add['mimetype'];
            }
            Db::name('attachment')->insert($add);
            
            return json([
                    'error'    => [
                        'message' => '上传成功',
                        'number'  => 201,
                    ],
                        'fileName' => '',
                        'uploaded' => 1,
                        'url'      => '/storage/'.$savename,
                    ]);
        } else {
            $this->error("上传出错了");
        }
    }
    
    /**
     * 获取上传文件列表
     */
    public function getUploadFiles()
    {
        $get = $this->request->get();
        $page = isset($get['page']) && !empty($get['page']) ? $get['page'] : 1;
        $limit = isset($get['limit']) && !empty($get['limit']) ? $get['limit'] : 10;
        $title = isset($get['title']) && !empty($get['title']) ? $get['title'] : null;
        $this->model = Db::name('attachment');
        $count = $this->model
        ->count();
        $list = $this->model
        ->page($page, $limit)
        ->order("createtime")
        ->select()->each(function ($item) {
            $item['createtime'] = date('Y-m-d H:i', $item['createtime']);
            return $item;
        });
        $data = [
            'code'  => 0,
            'msg'   => '',
            'count' => $count,
            'data'  => $list,
        ];
        return json($data);
    }

    /**
     *
     *  分片上传文件
     *
     */
    public function multipart_upload()
    {
        Config::set(['default_return_type'=>'json'], 'config');
        //必须设定cdnurl为空,否则cdnurl函数计算错误
        Config::set(['cdnurl'=>''], 'upload');
        $chunkid = $this->request->post("chunkid")?? md5(md5($this->request->post("name"))); //文件唯一标识
        if (!Config::get('upload.chunking')) {
            $this->error(__('未开启分片上传'));
        }

        $chunkindex = $this->request->post("chunk/d");//当前分割的个数 从0开始
        $chunkcount = $this->request->post("chunks/d");//分割数量
        $filename = $this->request->post("name");//文件名字
        $method = $this->request->method(true);
        $action = $this->request->post("action");//merge 合并 clean取消清除上传 需要前端传递对应的参数判断 目前通过后台判断
        $merge = $chunkindex + 1;
        if ($merge == $chunkcount) {
            //最后一次上传分片文件 进行合并
            $file = $this->request->file('file');
            try {
                $upload = new \app\common\library\Upload($file);
                $upload->chunk($chunkid, $chunkindex, $chunkcount);
            } catch (UploadException $e) {
                $this->error($e->getMessage());
            }
            $action = 'merge';//合并
        }
        $upload = new \app\common\library\Upload();
        if ($action == 'merge') {
            $attachment = null;
            //合并分片文件
            try {
                $attachment = $upload->merge($chunkid, $chunkcount, $filename);
            } catch (UploadException $e) {
                $this->error($e->getMessage());
            }
            $this->success(__('Uploaded successful'), '', ['url' => $attachment->url, 'fullurl' => cdnurl($attachment->url, true)]);
        } elseif ($action == 'clean') {
            //删除冗余的分片文件
            try {
                $upload = new Upload();
                $upload->clean($chunkid);
            } catch (UploadException $e) {
                $this->error($e->getMessage());
            }

            return json(['code' => 0, 'path' =>'','key'=>'']);
        } else {
            //上传分片文件
            //默认普通上传文件
            $file = $this->request->file('file');
            try {
                $upload = new \app\common\library\Upload($file);
                $res = $upload->chunk($chunkid, $chunkindex, $chunkcount);
                return json(['code' => 0, 'path' => $res,'key'=>$res]);
            } catch (UploadException $e) {
                $this->error($e->getMessage());
            }
        }
    }
}
