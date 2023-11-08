<?php
declare (strict_types = 1);

namespace app\command;
use think\facade\Db;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class Cron extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('cron')
            ->setDescription('the cron command');
    }

    protected function execute(Input $input, Output $output)
    {   $ritorn = $this->task();
        // 指令输出
        $output->writeln($ritorn);
    }
    function task(){
        $site = config('site');
        $lines = Db::name('hostloc')->where('uptime','<', time()-(int)$site['Interval'])->where('switch', 'on')->limit((int)$site['limit'])->select()->toArray();
        if(empty($lines)){
            echo "没有可执行的挂机任务\n";
            exit;
        }
        $userid = explode(",", $site['userid']);
        $HttpRequests = new \HttpRequests();
        $userAgent = $HttpRequests->userAgent();
        $number = ['sum' => count($lines),'no' => 0];
        foreach($lines as $line){
            $data = ['hostloc_id' => $line['id'],'grade' => '0','integral' => 0,'money' => 0,'uptime' => time(),'status' => 'no'];
            if(empty($line['name']) || empty($line['pass'])){
                    continue;
            }
            $suburl = $site['hostloc']."/member.php?mod=logging&action=login";
            $loginInfo = ["username" => $line['name'],"password" => $line['pass'],"fastloginfield" => "username","quickforward" => "yes","handlekey" => "ls","loginsubmit" => true];
            $cookiefile = root_path().'/HttpRequests.cookie';
            if(file_exists($cookiefile)){
                unlink($cookiefile);
            }
            $client = $this->rand_ip();
            $useragent = $HttpRequests->userAgent();
            $headers = $HttpRequests->httpHeaders($useragent,$client);
            $login = $HttpRequests->httpPost($suburl,$loginInfo,$headers);
            if(strpos($login, $line['name']) !== FALSE){
                preg_match("/>用户组: (.*?)<\/a>/", $login, $preg);
                $data['grade'] = $preg[1];
            }else{
                Db::name('hostloc')->where('id', $line['id'])->update(['switch' => 'off']);
                Db::name('hanglog')->insert($data);
                $number['no'] += 1;
                continue;
            }
            $html = $HttpRequests->httpGet($site['hostloc'].'/home.php?mod=spacecp&ac=credit&op=base',$headers);
            $old_jf = $this->get_jf($html);
            for($i=0;$i<20;$i++){
                $uid = rand((int)$userid[0],(int)$userid[1]);
                $HttpRequests->httpGet($spaceUrl = $site['hostloc']."/space-uid-{$uid}.html",$headers);
                sleep((int)$site['sleep']);
            }
            $html = $HttpRequests->httpGet($site['hostloc'].'/home.php?mod=spacecp&ac=credit&op=base',$headers);
            $new_jf = $this->get_jf($html);
            Db::name('hostloc')->where('id', $line['id'])->update(['grade' => $data['grade'],'integral' => $new_jf['integral'],'money' => $new_jf['money'],'uptime' => time()]);
            $data['integral'] = $new_jf['integral']-$old_jf['integral'];
            $data['money'] = $new_jf['money']-$old_jf['money'];
            $data['status'] = 'ok';
            Db::name('hanglog')->insert($data);
        }
        $number['yes'] = count($lines)-$number['no'];
        return "本次共执行".$number['sum']."个刷分任务\n成功".$number['yes']."个任务\n失败".$number['no']."个任务\n";
    }
    function get_jf($html){
        $data = ['integral' => 0,'money' => 0];
        preg_match("/积分: (\d+)<\/a>/", $html, $preg);
        if(!empty($preg[1])){
            $data['integral'] = (int)$preg[1];
        }
        preg_match("/金钱: <\/em>(\d+)/", $html, $preg);
        if(!empty($preg[1])){
            $data['money'] = (int)$preg[1];
        }
        return $data;
    }
    function rand_ip(){
        return rand(1,255).'.'.rand(1,255).'.'.rand(1,255).'.'.rand(1,255);
    }
}
