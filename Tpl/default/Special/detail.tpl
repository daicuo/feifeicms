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
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/special_detail" />
</head>
<body class="special-detail">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
  <h2>
  <span class="glyphicon glyphicon-calendar text-green"></span>
	<a class="text-green" href="{:ff_url('list/read',array('id'=>$special_cid),true)}">{$list_name}</a>
  <a href="{:ff_url('special/read',array('id'=>$special_id),true)}">{$special_name}</a>
	<label class="pull-right hidden-xs hidden-sm"><include file="./Tpl/base/bootstrap3/inc_share" /></label>
  </h2> 
</div>
<!-- -->
<empty name="special_banner">
	<div class="media">
		<div class="media-left hidden-xs hidden-sm">
			<img class="media-object img-thumbnail img-responsive ff-img" data-original="{$special_logo|ff_url_img}">
		</div>
		<div class="media-body">
			{$special_content}
		</div>
	</div>
<else/>
	<div><img class="img-thumbnail img-responsive ff-img special-banner" data-original="{$special_banner|ff_url_img}"></div>
	<div class="special-content text-gray">{$special_content}</div>
</empty>
<!-- -->
<div class="page-header">
  <h2><span class="glyphicon glyphicon-film text-green"></span> 相关影片</h2>
</div>
<ul class="list-unstyled vod-item-img ff-img-215">
<volist name="item_vod" id="feifei">
<include file="BlockTheme:item_img_vod" />
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
<!-- -->
<include file="./Tpl/base/bootstrap3/forum_ajax_special" />
</div><!--container end -->
{$special_hits_insert}
<div class="clearfix mb-2"></div>
<div class="container ff-bg">
  <include file="BlockTheme:footer" />
</div>
</body>
</html>