<?php

class HttpRequests{
    function userAgent(){
        $useragent = 'Mozilla/5.0 (compatible;Baiduspider-render/2.0; +http://www.baidu.com/search/spider.html)';
        return $useragent;
    }
    function httpHeaders($useragent = '',$client = '',$host = ''){
        $headers = [];
        if(empty($useragent)){
            $useragent = $this->userAgent();
        }
        $headers = ["user-agent: $useragent"];
        if(!empty($client)){
            $headers[] = "X-FORWARDED-FOR: $client";
            $headers[] = "HTTP_X_FORWARDED_FOR: $client";
        }
        if(!empty($host)){
            $headers[] = "host: $host";
        }
        return $headers;
    }    
    function httpGet($url,$headers = [],$proxy = [],$timeout = 600){
        return $this->httpCurl($url,$mode='GET',$data=[],$headers,$proxy,$timeout);
    }
    function httpPost($url,$data,$headers = [],$proxy = [],$timeout = 600){
        return $this->httpCurl($url,$mode='POST',$data,$headers,$proxy,$timeout);
    }
    function httpCurl($url,$mode='GET',$data=[],$headers = [],$proxy = [],$timeout = 600){
        $ch = curl_init ();
        if($mode == 'POST'){
            curl_setopt($ch, CURLOPT_POST , 1);
        }
        curl_setopt($ch, CURLOPT_HEADER , 0);
        curl_setopt($ch, CURLOPT_URL , $url);
        curl_setopt($ch, CURLOPT_COOKIEFILE, root_path().'/HttpRequests.cookie');
        curl_setopt($ch, CURLOPT_COOKIEJAR , root_path().'/HttpRequests.cookie');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt($ch, CURLOPT_POSTFIELDS , $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT,$timeout);
        curl_setopt($ch, CURLOPT_REFERER, 'https://'.parse_url($url)['host'].'/');
        $result = curl_exec($ch);
        curl_close($ch);
        if ($result === FALSE) {
            echo "cURL Error:".curl_error($ch);
        }
        return $result;
    }
}