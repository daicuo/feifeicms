<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/star_dianying" />
</head>
<body class="star-detail">
<include file="BlockTheme:header" />
<include file="BlockTheme:star_inc_detail" />
<div class="container ff-bg">
<php>$item_vod = ff_mysql_vod('cid:1;actor:'.$person_name.';limit:0;cache_name:default;cache_time:default;order:vod_stars desc,vod_addtime;sort:desc');</php>
<ul class="list-unstyled vod-item-img ff-img-140">
	<volist name="item_vod" id="feifei">
	<include file="BlockTheme:item_img_vod" />
	</volist>
</ul>
</div><!--container end -->
<div class="clearfix mb-1"></div>
<include file="BlockTheme:footer" />
</body>
</html>