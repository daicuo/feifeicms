<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="User:header" />
<title>用户中心_{$site_name}</title>
<meta name="keywords" content="{$site_name}用户中心">
<meta name="description" content="欢迎回到{$site_name}用户中心">
</head>
<body class="user-center">
<include file="User:center_nav" />
<div class="container ff-bg">
<div class="row">
  <div class="col-xs-12 ff-col">
    <div class="page-header">
      <h4><span class="glyphicon glyphicon-menu-right text-green"></span> 帐号管理</h4>
    </div>
    <dl class="safe">
      <dt>我的影币</dt>
      <dd>影币可用来支付付费点播影片或购买VIP权限 您现在拥有（{$user_score|default=0}）个影币
			<if condition="ff_PaymentItem()"><a class="btn btn-success btn-sm user-score-payment" href="javascript:;">在线充值</a></if>
			<if condition="C('pay_card_sell')"><a class="btn btn-success btn-sm user-score-card" href="javascript:;">卡密充值</a></if>
			</dd>  
      <dt>VIP权限</dt>
      <dd>VIP权限到期后将不能观看付费影片，您的VIP到期时间为（{$user_deadtime|date='Y/m/d',###}）<a href="javascript:;" class="btn btn-success btn-sm user-score-upvip">升级VIP</a></dd>
      <dt>登录邮箱</dt>
      <dd>{$user_email|remove_xss} <a href="javascript:;" class="btn btn-success btn-sm user-change-email">修改邮箱</a></dd>
      <dt>用户密码</dt>
      <dd>建议使用字母、数字与标点的组合，可以大幅提升帐号安全 <a href="javascript:;" class="btn btn-success btn-sm user-change-pwd">修改密码</a></dd>    
      <dt>邀请奖励 <small>每邀请一个用户注册后将获得（{:C("user_register_score_pid")}）影币奖励</small></dt>
      <dd>推广链接：{$site_url}{:ff_url('user/register',array('id'=>$user_id))}</dd>
      <dt>最近登录IP</dt>
      <dd>您最近一次登录本站的IP为（{$user_logip|htmlspecialchars}）</dd>
      <dt>最近登录时间</dt>
      <dd>您最近一次登录本站的时间为（{$user_logtime|date='Y/m/d H:i:s',###}）</dd>      
    </dl>
  </div>  
</div><!--row end -->
</div>
<script src="//lib.baomitu.com/jquery-image-upload/1.2.0/jQuery-image-upload.min.js"></script>
<script>
	$(".face").imageUpload({
		formAction: "{:ff_url('user/face')}",
		inputFileName:'file',
		browseButtonValue: '修改头像',
		browseButtonClass:'btn btn-default btn-xs',
		automaticUpload: true,
		hideDeleteButton: true,
		hover:true
	})
	$(".face").on("imageUpload.uploadFailed", function (ev, err) {
		alert(err);
	});
</script>
<include file="User:footer" />
</body>
</html>