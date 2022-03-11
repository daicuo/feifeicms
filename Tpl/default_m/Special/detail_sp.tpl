<php>if($special_tag_name){
	$item_vod = ff_mysql_vod('limit:24;tag_name:'.$special_tag_name.';tag_list:vod_tag;cache_name:default;cache_time:default;order:vod_hits desc');
  $item_news = ff_mysql_news('limit:30;tag_name:'.$special_tag_name.';tag_list:news_tag;cache_name:default;cache_time:default;order:news_hits desc');
}else{
	$item_vod = ff_mysql_vod('limit:24;ids:'.$special_ids_vod.';cache_name:default;cache_time:default;order:vod_hits desc');
  $item_news = ff_mysql_news('limit:30;ids:'.$special_ids_news.';cache_name:default;cache_time:default;order:news_hits desc');
}
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/special_detail" />
</head>
<body class="special-detail">
<include file="BlockTheme:header" />
<div class="container ff-bg">
	<div class="page-header">
		<h2 class="text-ellipsis">
		<span class="glyphicon glyphicon-calendar text-green"></span>
		<a class="text-green" href="{:ff_url('list/read',array('id'=>$list_id),true)}">{$list_name}</a>
		{$special_name}
		</h2> 
	</div>
	<p class="special-banner"><img class="img-thumbnail img-responsive ff-img" data-original="{$special_banner|ff_url_img}"></p>
	<p class="special-content">{$special_content|strip_tags}</p>
</div>
<!-- -->
<div class="clearfix mb-1"></div>
<div class="container ff-bg">
	<div class="page-header">
		<h2><span class="glyphicon glyphicon-film text-green"></span> 相关影片</h2>
	</div>
	<ul class="list-unstyled vod-item-img ff-img-90">
	<volist name="item_vod" id="feifei">
	<include file="BlockTheme:item_img_vod_sp" />
	</volist>
</ul>
<notempty name="item_news">
	<div class="clearfix"></div>
	<div class="page-header">
		<h2><span class="glyphicon glyphicon-list-alt text-green"></span> 相关资讯</h2>
	</div>
	<ul class="news-item-ul ff-row">
		<volist name="item_news" id="feifei">
			<include file="BlockTheme:item_txt_news_hits" />
		</volist>
	</ul>
</notempty>
</div>
<div class="clearfix mb-1"></div>
<!-- -->
<div class="container ff-bg">
<include file="./Tpl/base/bootstrap3/forum_ajax_special" />
</div>
<div class="clearfix mb-1"></div>
{$special_hits_insert}
<include file="BlockTheme:footer" />
</body>
</html>