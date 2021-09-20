<?php

function get_jf($hostloc){
        $data = array();
        $html = curl_get($hostloc.'/home.php?mod=spacecp&ac=credit&op=base');
        preg_match("/积分: (\d+)<\/a>/", $html, $preg);
        if(!empty($preg[1])){
                $data['credit'] = $preg[1];
        }else{
                $data['credit'] = 0;
        }
        preg_match("/金钱: <\/em>(\d+)/", $html, $preg);
        if(!empty($preg[1])){
                $data['money'] = $preg[1];
        }else{
                $data['money'] = 0;
        }

        return $data;
}

function curl_post($url, $post_data,$hostloc){
    $ch = curl_init ();
    curl_setopt($ch, CURLOPT_POST , 1);
    curl_setopt($ch, CURLOPT_HEADER , 0);
    curl_setopt($ch, CURLOPT_URL , $url);
    curl_setopt($ch, CURLOPT_COOKIEJAR , '/tmp/hostloc.cookie');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.rand_ip())); 
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible;Baiduspider-render/2.0; +http://www.baidu.com/search/spider.html)');
    curl_setopt($ch, CURLOPT_POSTFIELDS , $post_data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT,600);
    curl_setopt($ch, CURLOPT_REFERER, $hostloc.'/');
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


function curl_get($url){
    $ch = curl_init ();
    curl_setopt($ch, CURLOPT_HEADER , 0);
    curl_setopt($ch, CURLOPT_URL , $url);
    curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/hostloc.cookie');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.rand_ip())); 
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible;Baiduspider-render/2.0; +http://www.baidu.com/search/spider.html)');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT,600);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function rand_ip(){
    return rand(1,255).'.'.rand(1,255).'.'.rand(1,255).'.'.rand(1,255);
}