<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/vod_detail" />
</head>
<body class="vod-detail-sp">
<include file="BlockTheme:header" />
<div class="container ff-bg">
  <ul class="list-unstyled ff-row">
    <include file="./Tpl/base/bootstrap3/vod_player_vip" />
  </ul>
  <ul class="list-unstyled ff-row">
    <li class="col-xs-7 ff-col mb-1"><include file="./Tpl/base/bootstrap3/vod_updown" /></li>
    <li class="col-xs-5 ff-col mb-1 text-right"><include file="./Tpl/base/bootstrap3/vod_detail_next" /></li>
  </ul>
</div>
<!-- -->
<div class="clearfix mb-1"></div>
<div class="container ff-bg pt-0 pr-0 pb-0 pl-0">	
	{:ff_url_ads('300_15m')}
</div>
<!-- -->
<div class="clearfix mb-1"></div>
<div class="container ff-bg">
  <div class="page-header">
    <h2><span class="glyphicon glyphicon-heart-empty text-green"></span> 大家都在看</h2>
  </div>
  <ul class="list-unstyled vod-item-img ff-img-90">
    <volist name=":ff_mysql_vod('cid:'.$vod_cid.';limit:10;cache_name:default;cache_time:default;order:vod_hits_lasttime;sort:desc')" id="feifei">
    <include file="BlockTheme:item_img_vod_sp" />
    </volist>
  </ul>
</div>
<!-- -->
<div class="clearfix mb-1"></div>
<div class="container ff-bg">
	<include file="./Tpl/base/bootstrap3/forum_ajax_vod" />
</div>
{$vod_hits_insert}
<include file="BlockTheme:footer" />
</body>
</html>