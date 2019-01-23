<?php
/*
    $host = 'https://dwz.cn';
    $path = '/admin/v2/create';
    $url = $host . $path;
    $method = 'POST';
    $content_type = 'application/json';
    
    // TODO: 设置Token
    $token = 'f4fcbaef878b2665da53f486bc9e58dc';
    
    // TODO：设置待注册长网址
    //$bodys = array('url'=>'http://www.woxiangheng.online/klyl/index.html?time=456123'); 
    $bodys = array('url'=>'http://wq-anhui.top/'); 
    // 配置headers 
    $headers = array('Content-Type:'.$content_type, 'Token:'.$token);
    
    // 创建连接
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_FAILONERROR, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($bodys));
    
    // 发送请求
    $response = curl_exec($curl);
    curl_close($curl);
    
    // 读取响应
    var_dump($response);
*/
    /**
    * @author: vfhky 20130304 20:10
    * @description: PHP调用新浪短网址API接口
    *    * @param string $type: 非零整数代表长网址转短网址,0表示短网址转长网址
    */
    function xlUrlAPI($type,$url){
    /* 这是我申请的APPKEY，大家可以测试使用 */
    $key = '1562966081';
    if($type)
    	$baseurl = 'http://api.t.sina.com.cn/short_url/shorten.json?source='.$key.'&url_long='.$url;
    else
    	$baseurl = 'http://api.t.sina.com.cn/short_url/expand.json?source='.$key.'&url_short='.$url;
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL,$baseurl);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $strRes=curl_exec($ch);
    curl_close($ch);
    $arrResponse=json_decode($strRes,true);
    if (isset($arrResponse->error) || !isset($arrResponse[0]['url_long']) || $arrResponse[0]['url_long'] == '')
    	return $strRes;
    if($type)
    	return $arrResponse[0]['url_short'];
    else
    	return $arrResponse[0]['url_long'];
    }
    echo '<br/><br/>----------新浪短网址API----------<br/><br/>'.PHP_EOL;
    echo 'Long to Short: '.xlUrlAPI(1,'http://www.woxiangheng.online/klyl/index.html?time=456123').'<br/>'.PHP_EOL;
    echo 'Short to Long: '.xlUrlAPI(0,'http://t.cn/8FdW1rm').'<br/><br/>'.PHP_EOL;
    