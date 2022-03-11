<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/vod_juqing" />
</head>
<body class="vod-detail vod-detail-tabs">
<include file="BlockTheme:header" />
<include file="BlockTheme:vod_inc_info" />
<include file="./Tpl/base/bootstrap3/vod_playurl" />
<div class="container ff-bg">
<div class="page-header">
  <h2 class="text-ellipsis">
  <span class="glyphicon glyphicon-film text-green"></span>
  <a href="{:ff_url('vod/juqing',array('id'=>$vod_id),true)}">{$vod_name} 剧情介绍</a> 
  </h2>
</div>
<div class="content ff-content">{$vod_content|strip_tags}</div>
<include file="./Tpl/base/bootstrap3/vod_scenario" />
</div><!--container end -->
<div class="clearfix mb-1"></div>
<include file="BlockTheme:footer" />
</body>
</html>