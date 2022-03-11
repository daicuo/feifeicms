<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/vod_shoubo" />
</head>
<body class="vod-detail vod-detail-shoubo">
<include file="BlockTheme:vod_inc_info" />
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
  <h2 class="text-ellipsis">
  <span class="glyphicon glyphicon-film text-green"></span>
  <a href="{:ff_url('vod/shoubo',array('id'=>$vod_id),true)}">{$vod_name} 播出时间</a>
  </h2>
</div>
<div class="content">
{$list_name} {$vod_name}的首播时间（上映时间）为{$vod_filmtime|date="Y年m月d日",###}，每集（每部）时长为{$vod_length|ff_Second2Length}、小编最后一次更新时间为{$vod_addtime|date="Y-m-d H:i:s",###}<gt name="vod_total" value="0">，{$list_name} {$vod_name}共有{$vod_total}集，连载集数为{$vod_continu}......</gt>
</div>
</div><!--container end -->
<div class="clearfix mb-1"></div>
<include file="BlockTheme:footer" />
</body>
</html>