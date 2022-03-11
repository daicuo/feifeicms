<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/star_detail" />
</head>
<body class="star-detail">
<include file="BlockTheme:header" />
<include file="BlockTheme:star_inc_detail" />
<div class="container ff-bg">
<div class="ff-content lead">
	{$person_content}
</div>
<php>$item_list = ff_mysql_list('sid:1;pid:0;limit:4;cache_name:default;cache_time:default;order:list_pid asc,list_oid;sort:asc');</php>
<volist name="item_list" id="list">
<php>$item_vod = ff_mysql_vod('cid:'.ff_list_ids($list['list_id']).';actor:'.$person_name.';limit:6;cache_name:default;cache_time:default;order:vod_stars desc,vod_addtime;sort:desc'); if(!$item_vod){continue;}</php>
<div class="page-header">
  <h4 class="text-green">{$list.list_name}</h4>
</div>
<ul class="list-unstyled vod-item-img ff-img-215">
	<volist name="item_vod" id="feifei">
	<include file="BlockTheme:item_img_vod" />
	</volist>
</ul>
<div class="clearfix mb-5"></div>
</volist>
<include file="./Tpl/base/bootstrap3/forum_ajax_star" />
{$person_hits_insert}
</div><!--container end -->
<div class="clearfix mb-2"></div>
<div class="container ff-bg">
  <include file="BlockTheme:footer" />
</div>
</body>
</html>