<php>$item_vod=ff_mysql_vod('cid:'.$vod_cid.';limit:6;cache_name:default;cache_time:default;order:vod_hits_lasttime;sort:desc');</php>
<div class="clearfix"></div>
<div class="page-header">
  <h2><span class="glyphicon glyphicon-fire text-green"></span> 热播{$list_name}</h2>
</div>
<ul class="list-unstyled vod-item-img ff-img-215">
<volist name="item_vod" id="feifei">
<include file="BlockTheme:item_img_vod" />
</volist>
</ul>