<?php
namespace app\api\controller;

use app\BaseController;
use think\facade\Db;

class Crontab extends BaseController
{   
    function index(){
        $lines = Db::name('hostloc')->select()->toArray();
        $site = config('site');
        $hostloc = $site['hostloc'];
        $sleep = $site['sleep'];
        foreach($lines as $line){
            $username = $line['name'];
            $password = $line['pass'];
            if(empty($username) || empty($password)){
                    continue;
            }
            $suburl = $hostloc."/member.php?mod=logging&action=login";
            $loginInfo = array(
                "username" => $username,
                "password" => $password,
                "fastloginfield" => "username",
                "quickforward" => "yes",
                "handlekey" => "ls",
                "loginsubmit" => true
            );

            echo "login($username) ... ";
            $login = curl_post($suburl,$loginInfo,$hostloc);

            if(strpos($login, $username) !== FALSE){

                preg_match("/>用户组: (.*?)<\/a>/", $login, $preg);
                $group = $preg[1];
                echo "Success!($group)\n";
            }else{
                echo "Failed!\n\n";
                continue;
            }

            extract(get_jf($hostloc));
            echo "Credit: $credit; Money: $money\n";

            echo "Visting user space ";
            for($i=0;$i<20;$i++){
                $uid = rand(0,50000);
                curl_get($spaceUrl = $hostloc."/space-uid-{$uid}.html");
                echo ".";
                sleep($sleep);
            }
            echo " done!\n";
            extract(get_jf($hostloc));
            echo "Credit: $credit; Money: $money\n\n";
            $data = ['grade' => $group,'integral' => $credit,'money' => $money,'uptime' => time()];
            Db::name('hostloc')->where('id', $line['id'])->update($data);
            $data['hostloc_id'] = $line['id'];
            Db::name('hanglog')->insert($data);
        }
    }
}   
?>

