<?php
require_once "jssdk.php";
$jssdk = new JSSDK("wx5a6d7968808a917f", "1cd88dc2eb0edadd8b091afcd14f5218");
$signPackage = $jssdk->GetSignPackage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <link href="wx_css/css/yinxiao.css" rel="stylesheet" type="text/css" />
  <style type="text/css">
	.logo {width:100px; height:100px; background-color:#FE8300; border-radius:50px; text-align:center; line-height:100px; color:#FFF; margin:0 auto;}
  </style>
  <script src="wx_css/js/jquery.min.js"></script>
</head>
<body>
    <link href="wx_css/index/css/head.css" rel="stylesheet" type="text/css">
    <div id="ui-header">
      <div class="fixed top-bghead1"> <a class="ui-title" id="popmenu1">微信JSSDK接口</a> <a class="ui-btn-left_pre" href="javascript:;" onclick="history.go('-1');"></a> <a class="ui-btn-right" href="javascript:;" onclick="location.reload();"></a> </div>
    </div>
    <div style="height:46px;"></div>
    <div data-role="page" >
      <div data-role="content">
        <div class="wtg">
    	  <div class="logo">JSSDK</div>
          <div class="fhtj">
            <div class="submit"><a id='btnok' href="javascript:;">获取分享到朋友圈按钮</a></div>
          </div>
        </div>
      </div>
    </div>
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
  /*
   * 注意：
   * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
   * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
   * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
   *
   * 开发中遇到问题详见文档“附录5-常见错误及解决办法”解决，如仍未能解决可通过以下渠道反馈：
   * 邮箱地址：weixin-open@qq.com
   * 邮件主题：【微信JS-SDK反馈】具体问题
   * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
   */
  wx.config({
    debug: true,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
      // 所有要调用的 API 都要加到这个列表中
      'onMenuShareTimeLine'
    ]
  });
  wx.ready(function () {
    // 在这里调用 API
    document.getElementById('btnok').onclick = function(){
        wx.onMenuShareTimeLine({
            title : '最实用的47个让你拍照好看的技术',
            link  : 'http://www.featherzero.me/',
            imgUrl : 'http://www.featherzero.me/img/1.jpg',
            success : function(){
                alert('success');
            },
            cancel : function(){
                alert('cancel');
            }
        })
    }

  });
</script>
</html>
