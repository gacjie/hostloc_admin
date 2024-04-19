<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;
use think\facade\Cache;

class Task extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('task')
            ->setDescription('the task command');
    }

    protected function execute(Input $input, Output $output)
    {
        $this->task();
        // 指令输出
        $output->writeln(date('H:i:s')." 任务结束");
    }
    function task(){
        $daytime = strtotime(date('Y-m-d'));
        if(($daytime +(config('site.hour') * 60 * 60)) > time()){
            echo date('H:i:s')." 没到执行时间\n";
            exit;
        }
        
        $account = Db::name('account')->where('uptime','<', $daytime)->where('switch', 'on')->orderRaw('rand()')->find();
        if(empty($account)){
            echo date('H:i:s')." 没有需要执行的任务\n";
            exit;
        }
        
        $user_agent = Db::name('user_agent')->where('id', $account['user_agent_id'])->find();
        $client = Cache::get(md5($account['name']."_ip"));
        if(empty($client)){
            $address = Db::name('address')->where('id', $account['address_ids'])->find();
            $client = long2ip(rand(ip2long($address['start']),ip2long($address['end'])));
            Cache::set(md5($account['name']."_ip"), $client, 3600);
        }
        $Hostloc = new \Hostloc($account['name'],$account['pass']);
        $Hostloc->userAgent = $user_agent['info'];
        $Hostloc->cookieName = md5($account['name']).".cookie";
        $Hostloc->virtualAddress($client);
        $Hostloc->clientAPI = config('site.bbs_url');
        $Hostloc->uidRange = config('site.userid');
        echo date('H:i:s').' 登陆账号 '.$account['name']."\n";
        $loginAccount = $Hostloc->loginAccount();
        // var_dump($loginAccount);exit;
        if(!$loginAccount['status']){
            echo date('H:i:s')." 登陆失败，本次执行停止。\n";
            exit;
        }
        $account['grade'] = $loginAccount['data']['grade'];
        echo date('H:i:s').' 账号等级 '.$account['grade']."\n";
        echo date('H:i:s').' 访问空间 ';
        for($i=0;$i<12;$i++){
            $Hostloc->accessSpace();
            echo ".";
            sleep(1);
        }
        echo "\n";
        $getIntegral = $Hostloc->getIntegral();
        if($getIntegral['status']){
            $account['integral'] = $getIntegral['data']['integral'];
            $account['money'] = $getIntegral['data']['money'];
        }
        echo date('H:i:s').' 积分 '.$account['integral'].'  金钱 '.$account['money']."\n";
        Db::name('account')->where('id', $account['id'])->update(['grade' => $account['grade'],'integral' => $account['integral'],'money' => $account['money'],'uptime' => time()]);
    }

}
