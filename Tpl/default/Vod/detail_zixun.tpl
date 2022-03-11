<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/vod_zixun" />
</head>
<body class="vod-detail-tabs">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
  <h2 class="text-ellipsis">
  <span class="glyphicon glyphicon-film text-green"></span>
	<a href="{:ff_url_vod_show($list_id,$list_dir,1)}">{$list_name}</a>
  <a href="{:ff_url('vod/zixun',array('id'=>$vod_id),true)}">{$vod_name}</a> 新闻资讯
	<label class="pull-right hidden-xs hidden-sm"><include file="./Tpl/base/bootstrap3/inc_share" /></label>
  </h2>
</div>
<include file="BlockTheme:vod_inc_detail_tabs" />
<php>
if($vod_series){
	$item_news = ff_mysql_news('series:'.$vod_series.';tag_cid:4;limit:20;cache_name:default;cache_time:default;order:news_addtime;sort:desc');
}
if(!$item_news && $vod_name){
	$item_news = ff_mysql_news('name:'.$vod_name.';limit:20;cache_name:default;cache_time:default;order:news_addtime;sort:desc');
}
if(!$item_news && $vod_keywords){
	$item_news = ff_mysql_news('tag_name:'.$vod_keywords.';tag_cid:4;limit:20;cache_name:default;cache_time:default;order:news_addtime;sort:desc');
}
if(!$item_news && $vod_actor){
	$item_news = ff_mysql_news('names:'.ff_xml_vodactor($vod_actor).';limit:20;cache_name:default;cache_time:default;order:news_addtime;sort:desc');
}
</php>
<notempty name="item_news">
<volist name="item_news" id="feifei">
	<include file="BlockTheme:item_medial_news" />
</volist>
<else/>
	<h5 class="content">暂未添加影讯</h5>
</notempty>
</div><!--container end -->
<div class="clearfix mb-2"></div>
<div class="container ff-bg">
  <include file="BlockTheme:footer" />
</div>
</body>
</html>