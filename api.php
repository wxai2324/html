<?php
/**
 * wechat php test
 */
require 'common.php';
require 'Wechat.class.php';

//define your token
define("TOKEN", "yuling");

class wechatCallbackapiTest extends Wechat
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)){
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
            //防xxe攻击
            libxml_disable_entity_loader(true);
            //对XMl数据进行解析生成simplexml对象
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            //微信的客户端openid
            $fromUsername = $postObj->FromUserName;
            //微信公众平台
            $toUsername = $postObj->ToUserName;
            // 微信客户端向公众平台发送的关键词
            $keyword = trim($postObj->Content);
            //时间戳
            $time = time();
            global $tmpArr;
            switch ($postObj->MsgType) {
                case 'image':
                    $msgType = "text";
                    $contentStr = "你发送的是图片";
                    $resultStr = sprintf($tmpArr['text'], $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                    break;
                case 'event':
                    if ( $postObj->Event == 'subscribe' ) {
                        $msgType = "text";
                        $contentStr = "感谢您关注羽零摔不死！";
                        $resultStr = sprintf($tmpArr['text'], $fromUsername, $toUsername, $time, $msgType, $contentStr);
                        echo $resultStr;
                        break;
                    }
                    if ( $postObj->Event == 'CLICK' && $postObj->EventKey == 'V1001_TODAY_MUSIC') {
                        //定义相关变量
                        $msgType= 'music';
                        $title = '冰雪奇缘';
                        $description = '冰雪奇缘原声大碟';
                        $musicUrl = 'http://www.featherzero.me/music/music.mp3';
                        $thumbMediaId='auAE-uRWaAnVf1bcypf-oWQzUyKC61nwjPCJFM1Hg5N38-VyW-vdJrkSlTmJF6Ln';
                        $resultStr = sprintf($tmpArr['music'], $fromUsername, $toUsername, $time, $msgType, $title, $description, $musicUrl, $musicUrl, $thumbMediaId);
                        file_put_contents('./wx.log', $resultStr, FILE_APPEND);
                        echo $resultStr;
                    }
                case 'text':
                    if ($keyword == '图片') {
                        $msgType='image';
                        $media_id = 'YAjCPiWxsTbs7fCg_y3_CsxHueTv6iX-9t6uu1RwKAwS6F-Xu8GqGP3aSpfdEMcz';
                        $resultStr = sprintf($tmpArr['image'], $fromUsername, $toUsername, $time, $msgType, $media_id);
                        echo $resultStr;
                    }elseif($keyword == '音乐'){
                        $msgType='music';
                        $title = '色は匂へど散りぬるを';
                        $description= '东方系列音乐';
                        $musicUrl = 'http://www.featherzero.me/music/2.mp3';
                        $thumbMediaId = 'auAE-uRWaAnVf1bcypf-oWQzUyKC61nwjPCJFM1Hg5N38-VyW-vdJrkSlTmJF6Ln';
                        $resultStr = sprintf($tmpArr['music'], $fromUsername, $toUsername, $time, $msgType, $title, $description, $musicUrl, $musicUrl, $thumbMediaId);
                        file_put_contents('./wx.log', $resultStr, FILE_APPEND);
                        echo $resultStr;
                    }elseif($keyword == '单图文'){
                        $msgType = "news";
                        $contentStr = '
                                <item>
                                <Title><![CDATA[最实用的47个让你拍照好看的方法]]></Title>
                                <Description><![CDATA[怎样拍照好看?有个会拍照的男朋友是怎么样的体验?怎么样把女朋友拍得漂亮...]]></Description>
                                <PicUrl><![CDATA[http://www.featherzero.me/img/1.jpg]]></PicUrl>
                                <Url><![CDATA[http://www.featherzero.me/]]></Url>
                                </item>';
                        $count = 1;
                        $resultStr = sprintf($tmpArr['news'], $fromUsername, $toUsername, $time, $msgType, $count, $contentStr);
                        echo $resultStr;
                        file_put_contents('./wx.log' ,$resultStr,FILE_APPEND);
                    }elseif($keyword == '多图文'){
                        $msgType = "news";
                        $contentStr = '
                                <item>
                                <Title><![CDATA[最实用的47个让你拍照好看的方法]]></Title>
                                <Description><![CDATA[怎样拍照好看?有个会拍照的男朋友是怎么样的体验?怎么样把女朋友拍得漂亮...]]></Description>
                                <PicUrl><![CDATA[http://www.featherzero.me/img/1.jpg]]></PicUrl>
                                <Url><![CDATA[http://www.featherzero.me/]]></Url>
                                </item>
                                <item>
                                <Title><![CDATA[台湾水果种类大全有哪些是你不容错过的？]]></Title>
                                <Description><![CDATA[台湾一直被称为“水果王国”，这里冬季温暖，夏季炎热，光照充足...]]></Description>
                                <PicUrl><![CDATA[http://www.featherzero.me/img/2.jpg]]></PicUrl>
                                <Url><![CDATA[http://www.featherzero.me/]]></Url>
                                </item>';
                        $count = 2;
                        $resultStr = sprintf($tmpArr['news'], $fromUsername, $toUsername, $time, $msgType, $count, $contentStr);
                        echo $resultStr;
                        file_put_contents('./wx.log',$resultStr,FILE_APPEND);
                    }elseif($keyword == '客服'){
                        //1.获取access_token
                        $accessToken = $this->getToken();
                        //2.定义请求的url链接
                        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$accessToken}";
                        //3.定义要回复的内容
                        $contentStr = '客户消息接口';
                        //4.使用urlencode函数进行编码，防止出现中文乱码
                        $contentStr = urlencode($contentStr);
                        //5.组装数组
                        $contentArr = array('content'=> $contentStr);
                        //6.
                        $replyArr = array('touser' => "$fromUsername", 'msgtype'=> 'text', 'text'=> $contentArr);
                        //7.
                        $data = json_encode($replyArr);
                        //8.
                        $data = urldecode($data);
                        file_put_contents('wx.log', $data, FILE_APPEND);
                        file_put_contents('wx.log', $url, FILE_APPEND);

                        //9.
                        $this->httpRequest($url, $data);
                    }elseif ($keyword == '图文') {
                        $accessToken = $this->getToken();
                        //2.定义请求的url链接
                        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$accessToken}";
                        $contentArr1 = array(
                            'title' =>urlencode('最实用的47个让你拍照好看的方法') ,
                            'url'   =>'http://www.featherzero.me/',
                            'picurl'=>'http://www.featherzero.me/music/1.jpg'
                        );
                        $contentArr2 = array(
                            'title' =>urlencode('台湾水果种类大全有哪些是你不容错过的？'),
                            'url'   =>'http://www.featherzero.me/',
                            'picurl'=>'http://www.featherzero.me/music/2.jpg'
                        );
                        $contentArr = array($contentArr1, $contentArr2);
                        $contentArr = array('articles'=>$contentArr);
                        $replyArr = array('touser'=>"{$fromUsername}", 'msgtype'=>'news', 'news'=>$contentArr);
                        $data = json_encode($replyArr);
                        $data = urldecode($data);
                        file_put_contents('wx.log', $data, FILE_APPEND);
                        file_put_contents('wx.log', $url, FILE_APPEND);
                        $this->httpRequest($url, $data);

                    }elseif($keyword == '授权'){
                        $msgType = 'news';
                        $contentStr = '
                        <item>
                                <Title><![CDATA[点击授权]]></Title>
                                <Description><![CDATA[点击授权登录第三方网站]]></Description>
                                <PicUrl><![CDATA[http://www.featherzero.me/img/3.jpg]]></PicUrl>
                                <Url><![CDATA[https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx5a6d7968808a917f&redirect_uri=http://www.featherzero.me/userInfo.php&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect]]></Url>
                        </item>';
                        $count=1;
                        $resultStr = sprintf($tmpArr['news'], $fromUsername, $toUsername, $time, $msgType, $count, $contentStr);
                        echo $resultStr;
                    }else{
                        $url = "http://www.tuling123.com/openapi/api";
                        $APIkey = '32a83612a27b4c36abdad47ad972a138';

                        $ch  = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        $data = array(
                            'key'    => $APIkey,
                            'info'   => $keyword,
                            'userid' => $fromUsername,
                        );
                        $data = json_encode($data);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type:application/json',
                            'Content-Length:'.strlen($data)
                            )
                        );
                        $str = curl_exec($ch);
                        curl_close($ch);
                        $json = json_decode($str);

                        $msgType = "text";
                        $contentStr = $json->text;
                        $resultStr = sprintf($tmpArr['text'], $fromUsername, $toUsername, $time, $msgType, $contentStr);
                        echo $resultStr;
                    }
                    break;
                case 'voice':
                    $rec =  $postObj->Recognition;
                    $msgType = "text";
                    $contentStr = "你发送的是语音信息!,内容识别为:".$rec;
                    $resultStr = sprintf($tmpArr['text'], $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                    break;
                case 'location' :
                    $msgType = 'text';
                    $longitude = $postObj->Location_Y;
                    $latitude = $postObj->Location_X;
                    $contentStr = '您发送的是地址位置消息，经度:'.$longitude.'，纬度:'.$latitude;
                    $resultStr = sprintf($tmpArr['text'], $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                    break;
            }
        }else {
            echo "";
            exit;
        }
    }
}
$wechatObj = new wechatCallbackapiTest();
//$wechatObj->valid();
//开启了自动回复功能
$wechatObj->responseMsg();
//
