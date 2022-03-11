<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/vod_zixun" />
</head>
<body>
<include file="BlockTheme:header" />
<include file="BlockTheme:vod_inc_info" />
<include file="./Tpl/base/bootstrap3/vod_playurl" />
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
<div class="container ff-bg">
<div class="page-header mb-0">
  <h2 class="text-ellipsis">
	<span class="glyphicon glyphicon-signal text-green"></span>
	<a href="{:ff_url('vod/forum',array('id'=>$vod_id),true)}">{$vod_name} 新闻资讯</a>
	</h2>
</div>
<notempty name="item_news">
<volist name="item_news" id="feifei">
	<include file="BlockTheme:item_medial_news" />
</volist>
<else/>
	<h5 class="content">暂未添加{$vod_name}相关新闻资讯</h5>
</notempty>
</div>
<!--container end -->
<div class="clearfix mb-1"></div>
<div class="container ff-bg">
<div class="page-header">
  <h2><span class="glyphicon glyphicon-list text-green"></span> 最新资讯</h2>
</div>
<php>$item_news = ff_mysql_news('limit:15;cache_name:default;cache_time:default;order:news_id;sort:desc');</php>
<ul class="news-item-ul ff-row">
  <volist name="item_news" id="feifei">
    <include file="BlockTheme:item_txt_news_hits" />
  </volist>
</ul>
</div>
<div class="clearfix mb-1"></div>
<include file="BlockTheme:footer" />
</body>
</html>