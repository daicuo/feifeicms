<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/list_live" />
</head>
<body class="list-live">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
  <h2>
  <span class="glyphicon glyphicon-film text-green"></span>
  <a href="{:ff_url('list/read',array('id'=>$list_id),true)}">{$list_name}</a>
  </h2>
</div>
<div class="embed-responsive embed-responsive-16by9" id="cms_player">
	<iframe class="embed-responsive-item" src="{$Think.config.play_live|default='//cdn.feifeicms.co/live/?4.1'}" width="100%" height="600" frameborder="0" scrolling="no" allowfullscreen="true" allowtransparency="true"></iframe>
</div>
</div><!--container end -->
<div class="clearfix mb-1"></div>
<include file="BlockTheme:footer" />
</body>
</html>