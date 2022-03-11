<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="renderer" content="webkit">
<link rel="shortcut icon" href="{$root}favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="{$public_path}bootstrap/3.3.5/css/bootstrap.min.css">
<script type="text/javascript" src="{$public_path}jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="{$public_path}bootstrap/3.3.5/js/bootstrap.min.js"></script>
<title>积分充值_{$site_name}</title>
</head>
<body>
<div class="container ">
<div class="row">
  <div class="col-xs-12 text-center">
    <h2>积分充值</h2>
    <h6>订单号：{$out_trade_no} 金额：<strong>{$total_fee}</strong> 元</h6>
    <div style="margin: 0 auto; padding:20px 0; width:250px; border:1px solid #efefef">
      <img src="http://paysdk.weixin.qq.com/example/qrcode.php?data={$code_url|urlencode}" width="150" height="150"/>
      <p>打开微信，扫码支付</p>
    </div>
  </div>
</div><!--row end -->
</div>
<script>
function check(){
	$.get('{$root}index.php?g=home&m=payment&a=query&type=wxpay&order={$out_trade_no}', function(data){
		if(data == 'SUCCESS'){
			window.location.href = "{$root}index.php?g=home&m=user&a=center&action=orders";
		}
	});
}
$(function(){
	 setInterval(function(){check()}, 5000);  //5秒查询一次支付是否成功
})
</script>
</body>
</html>