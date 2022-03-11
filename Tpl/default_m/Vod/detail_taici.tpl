<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/vod_taici" />
</head>
<body class="vod-detail vod-detail-taici">
<include file="BlockTheme:header" />
<include file="BlockTheme:vod_inc_info" />
<include file="./Tpl/base/bootstrap3/vod_playurl" />
<div class="container ff-bg">
<div class="page-header">
  <h2 class="text-ellipsis">
  <span class="glyphicon glyphicon-film text-green"></span>
  <a href="{:ff_url('vod/taici',array('id'=>$vod_id),true)}">{$vod_name} 经典台词</a>
  </h2>
</div>
<div class="content">{$vod_lines|default=$vod_content}</div>
</div><!--container end -->
<div class="clearfix mb-1"></div>
<include file="BlockTheme:footer" />
</body>
</html>