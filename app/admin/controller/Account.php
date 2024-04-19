<?php
namespace app\admin\controller;

use app\common\controller\AddonBase;
use think\facade\View;
use think\facade\Db;
use think\facade\Cache;

class Account extends AdminBase
{

    public function initialize(){
		parent::initialize();
        $this->model = new \app\admin\model\Account();
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
                    

                    $address_ids = $this->request->param('address_ids',null);
                    if($address_ids){
                        $query->whereFindInSet('address_ids',$address_ids.'');
                    }
                    

                    $user_agent_id = $this->request->param('user_agent_id',null);
                    if($user_agent_id){
                        $query->where('user_agent_id',$user_agent_id);
                    }
                    

                    $switch = $this->request->param('switch',null);
                    if($switch){
                        $query->whereLike('switch',"%{$switch}%");
                    }
                    
        })
           ->order('id','desc')
		   ->page($page,$limit)->select();
   		return json([
				'code'=> 0,
				'count'=> $count,
   				'data'=>$data,
   				'msg'=>__('Search successful')
   		]);
   }
   public function getAddressList(){
    $data =  \think\facade\Db::name('address')->field('id,name')->select();
    return json([
            'code'=> 0,
            'count'=> count($data),
            'data'=>$data,
            'msg'=>__('Search successful')
   	]);
}
public function getUserAgentList(){
    $data =  \think\facade\Db::name('user_agent')->field('id,name')->select();
    return json([
            'code'=> 0,
            'count'=> count($data),
            'data'=>$data,
            'msg'=>__('Search successful')
   	]);
}

   public function add(){
	   	if($this->request->isPost()){
	   		$data = $this->request->post();
            if (!isset($data['switch']))
                $data['switch'] = 'off';
            $data['grade'] = "";
            $data['integral'] = 0;
            $data['money'] = 0;
            $user_agent = Db::name('user_agent')->where('id', $data['user_agent_id'])->find();
            $client = Cache::get(md5($data['name']."_ip"));
            if(empty($client)){
                $address = Db::name('address')->where('id', $data['address_ids'])->find();
                $client = long2ip(rand(ip2long($address['start']),ip2long($address['end'])));
                Cache::set(md5($data['name']."_ip"), $client, 3600);
            }
            $Hostloc = new \Hostloc($data['name'],$data['pass']);
            $Hostloc->userAgent = $user_agent['info'];
            $Hostloc->cookieName = md5($data['name']).".cookie";
            $Hostloc->virtualAddress($client);
            $Hostloc->clientAPI = config('site.bbs_url');
            $Hostloc->uidRange = config('site.userid');
            $loginAccount = $Hostloc->loginAccount();
            // var_dump($loginAccount);exit;
            if(!$loginAccount['status']){
                $this->error("登陆失败");
            }
            $data['grade'] = $loginAccount['data']['grade'];
            $getIntegral = $Hostloc->getIntegral();
            if($getIntegral['status']){
                $data['integral'] = $getIntegral['data']['integral'];
                $data['money'] = $getIntegral['data']['money'];
            }
            $data['uptime'] = '2000-01-01 00:00:01';
            // var_dump($data);exit;
	   		if( $this->model->save($data,false)){
	   			$this->success(__('Add successful'));
	   		}else{
	   			$this->error(__('Add failed'));
	   		}
	   	}
		$addresss = \think\facade\Db::name('address')->field('id,name')->select();
        View::assign('addresss',$addresss);
        $userAgents = \think\facade\Db::name('user_agent')->field('id,name')->select();
        View::assign('userAgents',$userAgents);
	   	return View::fetch('edit');
   }
   
   public function update(){
   		$idsStr = $this->request->param('idsStr');
   		if(!$idsStr){
   			$this->success(__('Parameter error'));
   		}
   		$data =  Db::name('account')->where('id', $idsStr)->find();
        $user_agent = Db::name('user_agent')->where('id', $data['user_agent_id'])->find();
        $client = Cache::get(md5($data['name']."_ip"));
        if(empty($client)){
            $address = Db::name('address')->where('id', $data['address_ids'])->find();
            $client = long2ip(rand(ip2long($address['start']),ip2long($address['end'])));
            Cache::set(md5($data['name']."_ip"), $client, 3600);
        }
        $Hostloc = new \Hostloc($data['name'],$data['pass']);
        $Hostloc->userAgent = $user_agent['info'];
        $Hostloc->cookieName = md5($data['name']).".cookie";
        $Hostloc->virtualAddress($client);
        $Hostloc->clientAPI = config('site.bbs_url');
        $Hostloc->uidRange = config('site.userid');
        $loginAccount = $Hostloc->loginAccount();
        // var_dump($loginAccount);exit;
        if(!$loginAccount['status']){
            $this->error("登陆失败");
        }
        $data['grade'] = $loginAccount['data']['grade'];
        $getIntegral = $Hostloc->getIntegral();
        if($getIntegral['status']){
            $data['integral'] = $getIntegral['data']['integral'];
            $data['money'] = $getIntegral['data']['money'];
        }
        unset($data['uptime'],$data['switch']);
   		if( $this->model->find($data['id'])->update($data)){
   			$this->success('数据更新成功');
   		}else{
   			$this->error('数据更新失败');
   		}
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
$insert_data[$k]['address_ids'] = isset($v[5]) ? $v[5] : '';
$insert_data[$k]['user_agent_id'] = isset($v[6]) ? $v[6] : '';
$insert_data[$k]['switch'] = isset($v[8]) ? $v[8] : '';
}
            }


   	   		if( $this->model->saveAll($insert_data,false)){
   	   			$this->success(__('Add successful'));
   	   		}else{
   	   			$this->error(__('Add failed'));
   	   		}
   	   	}
   		$addresss = \think\facade\Db::name('address')->field('id,name')->select();
View::assign('addresss',$addresss);$userAgents = \think\facade\Db::name('user_agent')->field('id,name')->select();
View::assign('userAgents',$userAgents);
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
		$addresss = \think\facade\Db::name('address')->field('id,name')->select();
View::assign('addresss',$addresss);$userAgents = \think\facade\Db::name('user_agent')->field('id,name')->select();
View::assign('userAgents',$userAgents);
	   	View::assign('account',$info);
        return View::fetch('edit');
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

}
