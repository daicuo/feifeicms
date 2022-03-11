<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/vod_detail" />
</head>
<body class="vod-detail">
<include file="BlockTheme:header" />
<include file="./Tpl/base/bootstrap3/vod_playurl" />
<div class="container ff-bg">
<h4 class="h4 text-nowrap">
	<include file="BlockTheme:vod_inc_label" />
</h4>
<div class="row">
<div class="col-md-8 col-xs-12">
<div class="media">
	<div class="media-left">
		<a href="{:ff_url_read_vod($list_id,$list_dir,$vod_id,$vod_ename,$vod_jumpurl)}">
			<img class="media-object img-thumbnail ff-img" data-original="{$vod_pic|ff_url_img=$vod_content}" alt="{$vod_name}免费观看">
		</a>
		<div class="hidden-xs hidden-sm">
			<include file="./Tpl/base/bootstrap3/vod_record" />
		</div>
	</div>
	<div class="media-body">
		<h4 class="text-nowrap">
			<a class="text-green" href="{:ff_url_read_vod($list_id,$list_dir,$vod_id,$vod_ename,$vod_jumpurl)}" title="{$vod_name}">{$vod_name|msubstr=0,20,true}</a>
			<small><include file="./Tpl/base/bootstrap3/vod_continu" /></small>
		</h4>
		<dl class="dl-horizontal">
			<dt>主演：</dt>
			<dd class="text-mr-1"><include file="./Tpl/base/bootstrap3/vod_actor" /></dd>
			<dt>导演：</dt>
			<dd class="text-mr-1"><include file="./Tpl/base/bootstrap3/vod_director" /></dd>
			<dt class="hidden-xs hidden-sm">编剧：</dt>
			<dd class="text-mr-1 hidden-xs hidden-sm"><include file="./Tpl/base/bootstrap3/vod_writer" /></dd>
			<dt>类型：</dt>
			<dd class="text-mr-1"><include file="./Tpl/base/bootstrap3/vod_type" /></dd>
			<dt class="text-mr-1 hidden-xs hidden-sm">地区：</dt>
			<dd class="text-mr-1 hidden-xs hidden-sm"><include file="./Tpl/base/bootstrap3/vod_area" /></dd>
			<dt>年份：</dt>
			<dd class="text-mr-1"><include file="./Tpl/base/bootstrap3/vod_year" /></dd>				
		</dl>		
		<div class="vod-score">
			<include file="./Tpl/base/bootstrap3/vod_score" />
		</div>
		<div class="hidden-xs hidden-sm">
			<include file="./Tpl/base/bootstrap3/vod_content" />
		</div>
	</div>
</div>
</div>
<div class="col-md-4 ff-col hidden-xs hidden-sm">
  <div class="text-center ff-ads ff-ads-250">
  	{:ff_url_ads('300_250')}
  </div>
	<div class="clearfix mb-1"></div>
	<p class="ff-ads-btn">
		{:ff_url_ads('300_15')}
	</p>
</div>
</div><!--row end -->
<!-- -->
<div class="clearfix mb-3"></div>
<include file="./Tpl/base/bootstrap3/vod_playurl_line_tab" />
<!-- -->
<include file="BlockTheme:vod_inc_hot" />
<!-- -->
<include file="BlockTheme:vod_inc_series" />
<!-- -->
<include file="./Tpl/base/bootstrap3/forum_ajax_vod" />
</div><!--container end -->
<div class="clearfix mb-2"></div>
<div class="container ff-bg">
  <include file="BlockTheme:footer" />
</div>
</body>
</html>