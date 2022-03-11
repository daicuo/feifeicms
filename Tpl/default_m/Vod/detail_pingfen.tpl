<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/vod_pingfen" />
</head>
<body class="vod-detail vod-detail-pingfen">
<include file="BlockTheme:header" />
<include file="BlockTheme:vod_inc_info" />
<div class="container ff-bg">
<div class="page-header">
  <h2 class="text-ellipsis">
  <span class="glyphicon glyphicon-film text-green"></span>
  <a href="{:ff_url('vod/pingfen',array('id'=>$vod_id),true)}">{$vod_name} 评分</a>
  </h2>
</div>
<include file="./Tpl/base/bootstrap3/vod_score" />
<div class="content ff-content">{$list_name} {$vod_name} 的评分为<gt name="vod_gold" value="0.0">{$vod_gold}<else/>9.2</gt>分，共有{$vod_golder}人参与。{$vod_content|strip_tags}<strong>{$list_name} {$vod_name} 豆瓣评分（<gt name="vod_douban_score" value="0.0">{$vod_douban_score}<else/>8.0</gt>）</strong></div>
</div><!--container end -->
<div class="clearfix mb-1"></div>
<include file="BlockTheme:footer" />
</body>
</html>