<?php
if(!empty($_GET)){
    $code = $_GET['code'];
    $appId = $_GET['appid'];
    $secret = '1cd88dc2eb0edadd8b091afcd14f5218';
    $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appId}&secret={$secret}&code={$code}&grant_type=authorization_code";
    require './http.php';
    $str =  http_request($url);
    $json = json_decode($str);
    $access_token = $json->access_token;
    $openid = $json->$openid;

    $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";

    $str = http_request($url);
    $userJson = json_decode($str);
    $nickName = $userJson->nickname;
    echo $nickName;
}
 ?>
