<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="User:header" />
<title>用户登录_{$site_name}</title>
<meta name="keywords" content="{$site_name}用户登录">
<meta name="description" content="欢迎回到{$site_name}">
<script>
$(document).ready(function(){
	$(".form-user-login").on('submit',function(e){
		$.ajax({
			url: $(this).attr('action'),
			type: 'POST',
			dataType: 'json',
			timeout: 3000,
			data: $(this).serialize(),
			beforeSend: function(xhr){
				$('#user-submit').html('正在登录...');
			},
			error : function(){
				feifei.alert.warning('.ff-alert','请求失败，请刷新网页。');
			},
			success: function(json){
				if(json.status == 200){
					location = '{:ff_url("user/center",array("action"=>"index"))}';
				}else{
					$('#user-submit').html('登录');
					feifei.alert.warning('.ff-alert',json.info);
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
<body class="user-login">
<div class="container ff-bg">
<h2 class="text-center">
  <a href="{:ff_url('user/register')}">欢迎回到{$site_name}</a>
</h2>
<h5 class="text-center">
  <a class="text-green" href="{$root}">返回首页</a>
  <a class="text-green" href="{:ff_url('user/forget')}">忘记密码</a>
  <a class="text-green" href="{:ff_url('user/register')}">没有帐号注册</a>
</h5>
<div class="clearfix mb-1"></div>
<h4 class="text-muted">
  用户登录
</h4>
<form class="form-horizontal form-user-login" action="{:ff_url('user/loginpost')}" method="post" role="form" target="_blank">
  <div class="form-group">
    <label for="user_email" class="col-md-3 control-label">邮箱</label>
    <div class="col-md-8">
      <input class="form-control" name="user_email" id="user_email" type="text" placeholder="请输入邮箱" required>
    </div>
  </div>
  <div class="form-group">
    <label for="user_pwd" class="col-md-3 control-label">密码</label>
    <div class="col-md-8">
      <input class="form-control" name="user_pwd" id="user_pwd" type="password" placeholder="请输入密码" required>
    </div>
  </div>
  <div class="form-group">
    <div class="col-xs-7 checkbox text-right">
      <label><input name="user_remember" type="checkbox" value="1" checked> 下次自动登录</label>
    </div>
    <div class="col-xs-4 text-right">
      <button type="submit" class="btn btn-success" id="user-submit">登录</button>
    </div>
  </div>
</form>
<h6 class="form-group ff-alert clearfix">
</h6>
</div>
<include file="User:footer" />
</body>
</html>