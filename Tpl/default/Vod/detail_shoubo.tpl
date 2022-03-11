<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/vod_shoubo" />
</head>
<body class="vod-detail-tabs vod-detail-shoubo">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
  <h2 class="text-ellipsis">
  <span class="glyphicon glyphicon-film text-green"></span>
	<a href="{:ff_url_vod_show($list_id,$list_dir,1)}">{$list_name}</a>
  <a href="{:ff_url('vod/shoubo',array('id'=>$vod_id),true)}">{$vod_name}</a> 播出时间
	<label class="pull-right hidden-xs hidden-sm"><include file="./Tpl/base/bootstrap3/inc_share" /></label>
  </h2>
</div>
<include file="BlockTheme:vod_inc_detail_tabs" />
<div class="lead">
{$list_name} {$vod_name}的首播时间（上映时间）为{$vod_filmtime|date="Y年m月d日",###}，每集（每部）时长为{$vod_length|ff_Second2Length}、小编最后一次更新时间为{$vod_addtime|date="Y-m-d H:i:s",###}<gt name="vod_total" value="0">，{$list_name} {$vod_name}共有{$vod_total}集，连载集数为{$vod_continu}......</gt>
</div>
</div><!--container end -->
<div class="clearfix mb-2"></div>
<div class="container ff-bg">
  <include file="BlockTheme:footer" />
</div>
</body>
</html>