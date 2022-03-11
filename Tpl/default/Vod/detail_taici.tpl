<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/vod_taici" />
</head>
<body class="vod-detail-tabs">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
  <h2 class="text-ellipsis">
  <span class="glyphicon glyphicon-film text-green"></span>
	<a href="{:ff_url_vod_show($list_id,$list_dir,1)}">{$list_name}</a>
  <a href="{:ff_url('vod/taici',array('id'=>$vod_id),true)}">{$vod_name}</a> 经典台词
	<label class="pull-right hidden-xs hidden-sm"><include file="./Tpl/base/bootstrap3/inc_share" /></label>
  </h2>
</div>
<include file="BlockTheme:vod_inc_detail_tabs" />
<div class="lead">{$vod_lines|default=$vod_content}</div>
</div><!--container end -->
<div class="clearfix mb-2"></div>
<div class="container ff-bg">
  <include file="BlockTheme:footer" />
</div>
</body>
</html>