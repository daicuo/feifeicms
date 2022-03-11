<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="User:header" />
<title>忘记密码_{$site_name}</title>
<meta name="keywords" content="{$site_name}用户登录">
<meta name="description" content="欢迎回到{$site_name}">
<script>
$(document).ready(function(){
	$(".form-user-forget").on('submit',function(e){
		$.ajax({
			url: $(this).attr('action'),
			type: 'POST',
			dataType: 'json',
			timeout: 3000,
			data: $(this).serialize(),
			beforeSend: function(xhr){
				$('.user-forget-alert').html('Loading...');
			},
			error : function(){
				$('.user-forget-alert').html('请求失败，请刷新网页。');
			},
			success: function(json){
				if(json.status == 200){
					location = '{:ff_url("user/login","",true)}';
				}else{
					feifei.alert.warning('.user-forget-alert',json.info);
				}
			},
			complete: function(xhr){
			}
		});
		return false;
	});
});
</script>
</head>
<body class="user-forget">
<div class="container ff-bg">
<h2 class="text-center">
  <a href="{:ff_url('user/register')}">欢迎回到{$site_name}</a>
</h2>
<h5 class="text-center">
  <a class="text-green" href="{$root}">返回首页</a>
  <a class="text-green" href="{:ff_url('user/login')}">用户登录</a>
  <a class="text-green" href="{:ff_url('user/register')}">没有帐号注册</a>
</h5>
<div class="clearfix mb-1"></div>
<h4 class="text-muted">
  找回密码
</h4>
<form class="form-horizontal form-user-forget" action="{:ff_url('user/forgetpost')}" method="post" role="form" target="_blank">
  <div class="form-group">
    <label for="user_email" class="col-xs-3 control-label">邮箱</label>
    <div class="col-xs-8">
      <input class="form-control" name="user_email" id="user_email" type="text" placeholder="请输入邮箱" required>
    </div>
  </div>
  <div class="form-group">
    <label for="user_pwd" class="col-sm-3 col-xs-3 control-label">验证码</label>
    <div class="col-sm-5 col-xs-5">
      <input class="form-control" name="user_vcode" id="user_vcode" type="test" placeholder="请输入验证码" required>
    </div>
     <div class="col-sm-4 col-xs-4">
      <img class="ff-vcode-img" src="{$root}index.php?s=Vcode-Index">
    </div>
  </div>
  <div class="form-group">
    <div class="col-xs-12 text-center">
      <button type="submit" class="btn btn-success" id="user-submit">找回密码</button>
    </div>
  </div>
</form>
<h6 class="user-forget-alert text-center">
  输入正确的邮箱后新密码将通过邮件发送给您，请注意查收。
</h6>
</div>
<include file="User:footer" />
</body>
</html>