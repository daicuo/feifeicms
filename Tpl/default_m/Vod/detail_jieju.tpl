<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/vod_jieju" />
</head>
<body class="vod-detail vod-detail-jieju">
<include file="BlockTheme:header" />
<include file="BlockTheme:vod_inc_info" />
<div class="container ff-bg">
<div class="page-header">
  <h2 class="text-ellipsis">
  <span class="glyphicon glyphicon-film text-green"></span>
  <a href="{:ff_url('vod/jieju',array('id'=>$vod_id),true)}">{$vod_name} 大结局</a>
  </h2>
</div>
<div class="content ff-content">
	{$vod_ending|default=$vod_content}
</div>
</div><!--container end -->
<div class="clearfix mb-1"></div>
<include file="BlockTheme:footer" />
</body>
</html>