<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/special_channel" />
</head>
<body class="special-channel">
<include file="BlockTheme:header" />
<div class="container ff-bg pt-1 pb-1">
<div class="row ff-row">
	<div class="col-md-8 ff-col ff-slide-pic">
		<include file="Slide:special" />
	</div>
	<div class="col-md-4 ff-col hidden-xs">
		<dl class="hot pb-0 mb-0">
		<volist name=":ff_mysql_special('cid:'.ff_list_ids($list_id).';limit:10;cache_name:default;cache_time:default;order:special_hits;sort:desc')" id="feifei">
		<dd class="text-ellipsis">
		<span class="text-green">{$i}、</span>
		<a href="{:ff_url('special/read', array('id'=>$feifei['special_id']), true)}">{$feifei.special_name}</a>
		</dd>
		</volist>
		</dl>
	</div>
</div>
</div>
<div class="clearfix mb-2"></div>
<!-- -->
<volist name=":explode(',',$list_extend['type'])" id="feifeilist" offset="0" length="8">
<php>$item_special=ff_mysql_special('cid:'.ff_list_ids($list_id).';tag_name:'.$feifeilist.';tag_list:special_type;limit:6;cache_name:default;cache_time:default;order:special_stars desc,special_id;sort:desc');if(!$item_special){continue;}</php>
<div class="container ff-bg">
	<div class="page-header">
		<h2>
		<span class="glyphicon glyphicon-th text-green"></span>
		{$feifeilist}
		<span class="pull-right">
		<a class="btn btn-success btn-xs" href="{:ff_url('list/select',array('id'=>$list_id,'type'=>urlencode($feifeilist),'p'=>1),true)}">更多</a>
		</span>
		</h2>
	</div>
	<ul class="list-unstyled vod-item-img ff-img-90">
		<volist name="item_special" id="feifei">
			<include file="BlockTheme:item_img_special" />
		</volist>
	</ul>
</div>
<div class="clearfix mb-1"></div> 
</volist>
<!-- -->
<div class="container ff-bg">
<include file="BlockTheme:footer" />
</div>
</body>
</html>