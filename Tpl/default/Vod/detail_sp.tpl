<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/vod_detail" />
</head>
<body class="vod-detail-sp">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
  <h2 class="text-ellipsis">
    <span class="glyphicon glyphicon-film text-green"></span> 
    <a href="{:ff_url_vod_show($list_id,$list_dir,1)}">{$list_name}</a>
    <small>{$vod_name|msubstr=0,24}</small>
    <label class="pull-right hidden-xs hidden-sm"><include file="./Tpl/base/bootstrap3/inc_share" /></label>
  </h2>
</div>
<div class="row ff-row">
  <div class="col-md-8 ff-col">
    <include file="./Tpl/base/bootstrap3/vod_player_vip" />
    <div class="clearfix"></div>
    <ul class="list-unstyled ff-row">
      <li class="col-xs-7 ff-col mt-1"><include file="./Tpl/base/bootstrap3/vod_updown" /></li>
      <li class="col-xs-5 ff-col mt-1 text-right"><include file="./Tpl/base/bootstrap3/vod_detail_next" /></li>
    </ul>
  </div>
  <div class="col-md-4 ff-col hidden-xs hidden-sm">
    <p class="text-center ff-ads ff-ads-250">{:ff_url_ads('300_250')}</p>
		<div class="clearfix my-2"></div>
    <p class="text-center ff-ads ff-ads-250">{:ff_url_ads('300_250')}</p>
  </div>
</div>
<div class="clearfix mb-2"></div>
<div class="page-header">
  <h2><span class="glyphicon glyphicon-heart-empty text-green"></span> 大家都在看</h2>
</div>
<ul class="list-unstyled vod-item-img ff-img-90">
  <volist name=":ff_mysql_vod('cid:'.$vod_cid.';limit:18;cache_name:default;cache_time:default;order:vod_hits_lasttime;sort:desc')" id="feifei">
  <include file="BlockTheme:item_img_vod_sp" />
  </volist>
</ul>
<!-- -->
<include file="./Tpl/base/bootstrap3/forum_ajax_vod" />
{$vod_hits_insert}
</div><!--container end -->
<div class="clearfix mb-2"></div>
<div class="container ff-bg">
  <include file="BlockTheme:footer" />
</div>
</body>
</html>