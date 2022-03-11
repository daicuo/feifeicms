<php>$item_vod=ff_mysql_vod('cid:'.$vod_cid.';limit:6;cache_name:default;cache_time:default;order:vod_hits_lasttime;sort:desc');</php>
<notempty name="item_vod">
<div class="container ff-bg">
	<div class="page-header">
		<h2><span class="glyphicon glyphicon-fire text-green"></span> 热播{$list_name}</h2>
	</div>
	<ul class="list-unstyled vod-item-img ff-img-140">
	<volist name="item_vod" id="feifei">
	<include file="BlockTheme:item_img_vod" />
	</volist>
	</ul>
</div>
<div class="clearfix mb-1"></div>
</notempty>