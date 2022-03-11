<php>
if($vod_series){
	if(count(explode(',',$vod_series)) > 1 ){
		$item_series = ff_mysql_vod('ids:'.$vod_series.';limit:6;cache_name:default;cache_time:default;order:vod_hits_lasttime;sort:desc');
	}else{
		$item_series = ff_mysql_vod('series:'.$vod_series.';ids_not:'.$vod_id.';limit:6;cache_name:default;cache_time:default;order:vod_hits_lasttime;sort:desc');
	}
}
</php>
<notempty name="item_series">
<div class="clearfix"></div>
<div class="page-header">
  <h2><span class="glyphicon glyphicon-link text-green"></span> 影片系列</h2>
</div>
<ul class="list-unstyled vod-item-img ff-img-215">
<volist name="item_series" id="feifei">
<include file="BlockTheme:item_img_vod" />
</volist>
</ul>
</notempty>