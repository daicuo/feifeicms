<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/star_zixun" />
</head>
<body class="star-detail">
<include file="BlockTheme:header" />
<include file="BlockTheme:star_inc_detail" />
<div class="container ff-bg">
<php>
if(!$item_news && $person_name){
	$item_news = ff_mysql_news('name:'.$person_name.';limit:30;cache_name:default;cache_time:default;order:news_id;sort:desc');
}
if(!$item_news && $person_keywords){
	$item_news = ff_mysql_news('tag_name:'.$person_keywords.';tag_cid:4;limit:20;cache_name:default;cache_time:default;order:news_id;sort:desc');
}
</php>
<notempty name="item_news">
<volist name="item_news" id="feifei">
	<include file="BlockTheme:item_medial_news" />
</volist>
<else/>
	<h5 class="content">暂未添加影人资讯</h5>
</notempty>
</div><!--container end -->
<div class="clearfix mb-1"></div>
<include file="BlockTheme:footer" />
</body>
</html>