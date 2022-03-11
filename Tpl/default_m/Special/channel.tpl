<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/special_channel" />
</head>
<body class="special-channel">
<include file="BlockTheme:header" />
<div class="container ff-bg">
  <include file="Slide:special" />
</div>
<div class="clearfix mb-1"></div> 
<volist name=":explode(',',$list_extend['type'])" id="feifeilist">
<php>$item_special=ff_mysql_special('cid:'.ff_list_ids($list_id).';tag_name:'.$feifeilist.';tag_list:special_type;limit:10;cache_name:default;cache_time:default;order:special_stars desc,special_id;sort:desc');if(!$item_special){continue;}</php>
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
<div class="container pl-0 pr-0">
	<a class="btn btn-block btn-lg btn-success" href="{:ff_url('list/select',array('id'=>$list_id,'type'=>'','p'=>1),true)}" style=" border-radius:0">全部分类</a>
</div>
<div class="clearfix mb-1"></div> 
<!-- -->
<include file="BlockTheme:footer" />
</body>
</html>