<?php
//  *+-----------------------------------------------------------------------
//  *                      .::::.
//  *                    .::::::::.            | Author: 丶长情
//  *                    :::::::::::           | Email: zeng1144318071@gmail.com
//  *                 ..:::::::::::'           | Datetime: 2020/09/24
//  *             '::::::::::::'               | Remarks: 11
//  *                .::::::::::
//  *           '::::::::::::::..
//  *                ..::::::::::::.
//  *              ``::::::::::::::::
//  *               ::::``:::::::::'        .:::.
//  *              ::::'   ':::::'       .::::::::.
//  *            .::::'      ::::     .:::::::'::::.
//  *           .:::'       :::::  .:::::::::' ':::::.
//  *          .::'        :::::.:::::::::'      ':::::.
//  *         .::'         ::::::::::::::'         ``::::.
//  *     ...:::           ::::::::::::'              ``::.
//  *   ```` ':.          ':::::::::'                  ::::..
//  *                      '.:::::'                    ':'````..
//  * +-----------------------------------------------------------------------
namespace app\admin\controller;

use app\common\controller\AddonBase;
use think\facade\View;
use think\facade\Db;

class Hostloc extends AdminBase
{

    public function initialize(){
		parent::initialize();
        $this->model = new \app\admin\model\Hostloc();
    }
    public function index(){
		if(!$this->request->isAjax()){
        	return View::fetch();
		}else{
			return $this->getList();
		}
    }

   public function getList(){
   		$page = $this->request->param('page',1,'intval');
   		$limit = $this->request->param('limit',10,'intval');
   		$count = $this->model->count();
   		$data = $this->model->with([])
		   ->where(function($query){
            $query->dateRange('uptime',$this->request->param('uptime',null));
            
                    $id = $this->request->param('id',null);
                    if($id){
                        $query->whereLike('id',"%{$id}%");
                    }
                    

                    $name = $this->request->param('name',null);
                    if($name){
                        $query->whereLike('name',"%{$name}%");
                    }
                    

                    $pass = $this->request->param('pass',null);
                    if($pass){
                        $query->whereLike('pass',"%{$pass}%");
                    }
                    

                    $grade = $this->request->param('grade',null);
                    if($grade){
                        $query->whereLike('grade',"%{$grade}%");
                    }
                    

                    $integral = $this->request->param('integral',null);
                    if($integral){
                        $query->whereLike('integral',"%{$integral}%");
                    }
                    

                    $money = $this->request->param('money',null);
                    if($money){
                        $query->whereLike('money',"%{$money}%");
                    }
                    

                    $switch = $this->request->param('switch',null);
                    if($switch){
                        $query->whereLike('switch',"%{$switch}%");
                    }
                    
        })
		   ->page($page,$limit)->select();
   		return json([
				'code'=> 0,
				'count'=> $count,
   				'data'=>$data,
   				'msg'=>__('Search successful')
   		]);
   }
   

   public function add(){
	   	if($this->request->isPost()){
	   		$data = $this->request->post();
	   		$site = config('site');
	   		$HttpRequests = new \HttpRequests();
	   		$userAgent = $HttpRequests->userAgent();
	   		$headers = $HttpRequests->httpHeaders($userAgent,$this->rand_ip());
	   		$suburl = $site['hostloc']."/member.php?mod=logging&action=login";
            $loginInfo = array(
                "username" => $data['name'],
                "password" => $data['pass'],
                "fastloginfield" => "username",
                "quickforward" => "yes",
                "handlekey" => "ls",
                "loginsubmit" => true
            );
            $cookiefile = root_path().'/HttpRequests.cookie';
            if(file_exists($cookiefile)){
                unlink($cookiefile);
            }
            $login = $HttpRequests->httpPost($suburl,$loginInfo,$headers);
            if(strpos($login, $data['name']) !== FALSE){
                preg_match("/>用户组: (.*?)<\/a>/", $login, $preg);
                $data['grade'] = $preg[1];
            }else{
                $this->error("登陆失败");
            }
            $html = $HttpRequests->httpPost($site['hostloc'].'/home.php?mod=spacecp&ac=credit&op=base',[],$headers);
            preg_match("/积分: (\d+)<\/a>/", $html, $preg);
            if(!empty($preg[1])){
                $data['integral'] = $preg[1];
            }else{
                $data['integral'] = 0;
            }
            preg_match("/金钱: <\/em>(\d+)/", $html, $preg);
            if(!empty($preg[1])){
                $data['money'] = $preg[1];
            }else{
                $data['money'] = 0;
            }
            $data['uptime'] = 1;
            $data['switch'] = 'on';
	   		if( $this->model->save($data,false)){
	   			$this->success(__('Add successful'));
	   		}else{
	   			$this->error(__('Add failed'));
	   		}
	   	}
		
	   	return View::fetch('edit');
   }


   public function leading(){
   	   	if($this->request->isPost()){
   	   		$file = $_FILES['file'];
   	   		$inputFileName = $file['tmp_name'];
            try {
                ob_end_clean();//清除缓冲区,避免乱码
                $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);

                $objReader  = \PHPExcel_IOFactory::createReader($inputFileType);

                $objPHPExcel = $objReader->load($inputFileName);
            } catch(\Exception $e) {
                 die('加载文件发生错误：”'.pathinfo($inputFileName,PATHINFO_BASENAME).'”: '.$e->getMessage());
            }
            //形成数组
             $excel_data = $objPHPExcel->getSheet(0)->toArray();


            $insert_data = array();
            foreach($excel_data as $k=>$v){
        
                
        if($k>0){
           
        $insert_data[$k]['name'] = isset($v[0]) ? $v[0] : '';
$insert_data[$k]['pass'] = isset($v[1]) ? $v[1] : '';
$insert_data[$k]['grade'] = isset($v[2]) ? $v[2] : '';
$insert_data[$k]['integral'] = isset($v[3]) ? $v[3] : '';
$insert_data[$k]['money'] = isset($v[4]) ? $v[4] : '';
$insert_data[$k]['switch'] = isset($v[6]) ? $v[6] : '';
}
            }


   	   		if( $this->model->saveAll($insert_data,false)){
   	   			$this->success(__('Add successful'));
   	   		}else{
   	   			$this->error(__('Add failed'));
   	   		}
   	   	}
   		
   	   	return View::fetch('leading');
     }

   public function edit(){
	   	if($this->request->isPost()){
	   		$data = $this->request->post();
            if (!isset($data['switch']))
                            $data['switch'] = 'off';
                            
            if( $this->model->find($data['id'])->save($data)){
	   			$this->success(__('Editor successful'));
	   		}else{
	   			$this->error(__('Editor failed'));
	   		}
	   	}
	   	$id = $this->request->param('id');
	   	if(!$id){
	   		$this->success(__('Parameter error'));
	   	}
	   	$info =  $this->model->where('id',$id)->find();
   		if(!$info){
	   		$this->success(__('Parameter error'));
	   	}
		
	   	View::assign('hostloc',$info);
        return View::fetch('edit');
   }
   public function update(){
   		$idsStr = $this->request->param('idsStr');
   		if(!$idsStr){
   			$this->success(__('Parameter error'));
   		}
   		$data =  Db::name('hostloc')->where('id', $idsStr)->find();
   		$site = config('site');
   		$HttpRequests = new \HttpRequests();
   		$userAgent = $HttpRequests->userAgent();
   		$headers = $HttpRequests->httpHeaders($userAgent,$this->rand_ip());
   		$suburl = $site['hostloc']."/member.php?mod=logging&action=login";
        $loginInfo = array(
            "username" => $data['name'],
            "password" => $data['pass'],
            "fastloginfield" => "username",
            "quickforward" => "yes",
            "handlekey" => "ls",
            "loginsubmit" => true
        );
        $cookiefile = root_path().'/HttpRequests.cookie';
        if(file_exists($cookiefile)){
            unlink($cookiefile);
        }
        $login = $HttpRequests->httpPost($suburl,$loginInfo,$headers);
        // echo($login);var_dump($headers);var_dump($loginInfo);exit;
        if(strpos($login, $data['name']) !== FALSE){
            preg_match("/>用户组: (.*?)<\/a>/", $login, $preg);
            $data['grade'] = $preg[1];
        }else{
            $this->error("登陆失败");
        }
        $html = $HttpRequests->httpPost($site['hostloc'].'/home.php?mod=spacecp&ac=credit&op=base',[],$headers);
        preg_match("/积分: (\d+)<\/a>/", $html, $preg);
        if(!empty($preg[1])){
            $data['integral'] = (int)$preg[1];
        }
        preg_match("/金钱: <\/em>(\d+)/", $html, $preg);
        if(!empty($preg[1])){
            $data['money'] = (int)$preg[1];
        }
        unset($data['uptime'],$data['switch']);
   		if( $this->model->find($data['id'])->update($data)){
   			$this->success('数据更新成功');
   		}else{
   			$this->error('数据更新失败');
   		}
   }
   public function delete(){
   		$idsStr = $this->request->param('idsStr');
   		if(!$idsStr){
   			$this->success(__('Parameter error'));
   		}
   		if( $this->model->where('id','in',$idsStr)->delete()){
   			$this->success(__('Delete successful'));
   		}else{
   			$this->error(__('Delete error'));
   		}
   }

   public function sw(){
      	$data = $this->request->param();
            if( $this->model->where('id',$data['id'])->update($data)){
                 $this->success(__('Editor successful'));
            }else{
                 $this->error(__('Editor failed'));
            }
      }
    function rand_ip(){
        return rand(1,255).'.'.rand(1,255).'.'.rand(1,255).'.'.rand(1,255);
    }
}
