<?php
// +----------------------------------------------------------------------
// | Hostloc 操作类 2024/04/19
// +----------------------------------------------------------------------
// | Copyright (c) 2020-2030 https://www.gacjie.cn
// +----------------------------------------------------------------------
// | gacjie.cn High Speed Development Framework
// +----------------------------------------------------------------------
// | Author: gacjie <gacjie@qq.com>  Apache 2.0 License Code
// +----------------------------------------------------------------------

class Hostloc
{
    //默认邮箱账号
    public $Account="";
    //默认账户密码
    public $Password="";
    //默认论坛官网
    public $clientAPI="https://hostloc.com/";
	//默认浏览器UA
  	public $userAgent = "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1";
  	//COOKIE文件目录
  	public $cookiePath = "";
  	//COOKIE文件名称
  	public $cookieName = "1e7f9a9db2522a4afdeb18681e224859.cookie";
  	//默认用户编号范围
  	public $uidRange = '1,72800';
    //默认设置选项
    public $setoptArray=[
            CURLOPT_HEADER => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => 'gzip',
            CURLOPT_TIMEOUT => 30,
        ];

    /**
     * 初始化操作
     */
    public function __construct($account=null,$password=null){
        //检查初始化是否传入对接信息
		if($account) $this->Account = $account;
		if($password) $this->Password = $password;
        //检查cookie存放目录是否存在 不存在则创建
        $this->cookiePath = dirname(__DIR__).'/runtime/cookie/';
        if(!file_exists($this->cookiePath)){
            mkdir($this->cookiePath, 0777, true);
        }
    }
    /**
     * 登录LOC账号
     * @return string
     */
    function loginAccount(){
        $data = [
            "username" => $this->Account,
            "password" => $this->Password,
            "fastloginfield" => "username",
            "quickforward" => "yes",
            "handlekey" => "ls",
            "loginsubmit" => true
        ];
        $this->setoptArray[CURLOPT_POST] = true;
        $this->setoptArray[CURLOPT_POSTFIELDS] = http_build_query($data);
        $result = $this->requestsAPI("/member.php?mod=logging&action=login");
        if(strpos($result, $this->Account) !== FALSE){
            preg_match("/>用户组: (.*?)<\/a>/", $result, $preg);
            return ['status'=>true,'message'=>'登陆成功','data'=>['grade'=>$preg[1]]];
        }else{
            return ['status'=>false,'message'=>'登陆失败','data'=>[]];
        }
        
    }
    /**
     * 获取账号积分
     * @return string
     */
    function getIntegral(){
        $this->setoptArray[CURLOPT_CUSTOMREQUEST] = "GET";
        $result = $this->requestsAPI("/home.php?mod=spacecp&ac=credit&op=base");
        $data = ['integral' => 0,'money' => 0];
        preg_match("/积分: (\d+)<\/a>/", $result, $preg);
        if(!empty($preg[1])){
            $data['integral'] = (int)$preg[1];
        }
        preg_match("/金钱: <\/em>(\d+)/", $result, $preg);
        if(!empty($preg[1])){
            $data['money'] = (int)$preg[1];
        }
        return ['status'=>true,'message'=>'获取成功','data'=>$data];
    }
    /**
     * 访问空间
     * @return string
     */
    function accessSpace(){
        $uidRange = explode(",", $this->uidRange);
        $uid = rand($uidRange[0],$uidRange[1]);
        $this->setoptArray[CURLOPT_CUSTOMREQUEST] = "GET";
        $result = $this->requestsAPI("/space-uid-{$uid}.html");
        return ['status'=>true,'message'=>'访问成功','data'=>[]];
    }
    /**
     * 设置虚拟地址
     * @return string
     */
    function virtualAddress($address){
        $this->setoptArray[CURLOPT_CUSTOMREQUEST] = "GET";
        $this->setoptArray[CURLOPT_HTTPHEADER] = [
                "X-FORWARDED-FOR: $address",
                "HTTP_X_FORWARDED_FOR: $address"
        ];
        return ['status'=>true,'message'=>'执行成功','data'=>[]];
    }
    /**
     * 请求API地址
     * @param string $url 网址链接
     * @return string
     */
    function requestsAPI($url){
        $cookiefile = $this->cookiePath . $this->cookieName;
        //检查cookie文件是否存在 不存在则创建
        if(!file_exists($cookiefile)){
            touch($cookiefile);
        }
        $curl = curl_init();
        $this->setoptArray[CURLOPT_URL] = $this->clientAPI.$url;
        $this->setoptArray[CURLOPT_USERAGENT] = $this->userAgent;
        $this->setoptArray[CURLOPT_COOKIEFILE] = $cookiefile;
        $this->setoptArray[CURLOPT_COOKIEJAR] = $cookiefile;
        curl_setopt_array($curl, $this->setoptArray);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        return $response;
    }


}