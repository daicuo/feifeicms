<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/star_channel" />
</head>
<body class="star-channel">
<include file="BlockTheme:header" />
<!-- -->
<php>$item_star = ff_mysql_star('limit:12;cache_name:default;cache_time:default;order:person_hits;sort:desc');</php>
<notempty name="item_star">
<div class="container ff-bg hidden-xs hidden-sm">
	<div class="page-header">
		<h2 class="text-ellipsis">
		<strong><span class="glyphicon glyphicon-fire text-green"></span> 荧幕热星</strong>
		<a class="pull-right text-green" href="{:ff_url('list/select',array('id'=>$list_id,'area'=>'','gender'=>'','profession'=>'','letter'=>'','order'=>'addtime','p'=>1),true)}">更多<span class="glyphicon glyphicon-chevron-right"></span></a>
		</h2>
	</div>
	<ul class="list-unstyled">
		<volist name="item_star" id="feifei">
		<li class="col-xs-1 px-0 py-0 text-center text-gray">
		<a href="{:ff_url_read_star($feifei['list_id'],$feifei['list_dir'],$feifei['person_id'],$feifei['person_ename'])}">
			<img class="img-circle ff-img" data-original="{:ff_url_img($feifei['person_pic'])}" alt="{$feifei.person_name}">
			<h6 class="mb-1">{$feifei.person_name|msubstr=0,4}</h6>
		</a>
		</li>
		</volist>
	</ul>
	<div class="clearfix mb-2"></div>
	<!-- -->
	<ul class="list-unstyled vod-item-img ff-img-215">
		<volist name=":ff_mysql_vod('cid:1,2;limit:6;cache_name:default;cache_time:default;order:vod_hits_lasttime;sort:desc');" id="feifei">
		<include file="BlockTheme:item_img_vod" />
		</volist>
	</ul>
</div>
<div class="clearix pb-2 hidden-xs hidden-sm"></div>
</notempty>
<!-- -->
<php>$item_star = ff_mysql_star('nationality:中国,内地,大陆;limit:6;cache_name:default;cache_time:default;order:person_hits;sort:desc');</php>
<notempty name="item_star">
<div class="container ff-bg">
	<div class="page-header">
		<h2 class="text-ellipsis">
		<strong><span class="glyphicon glyphicon-fire text-green"></span> 内地热星</strong>
		<a class="pull-right text-green" href="{:ff_url('list/select',array('id'=>$list_id,'area'=>urlencode('中国,内地,大陆'),'gender'=>'','profession'=>'','letter'=>'','order'=>'hits','p'=>1),true)}">更多<span class="glyphicon glyphicon-chevron-right"></span></a>
		</h2>
	</div>
	<ul class="list-unstyled ff-item">
		<volist name="item_star" id="feifei">
		<include file="BlockTheme:item_img_star" />
		</volist>
	</ul>
</div>
<div class="clearix pb-2"></div>
</notempty>
<!-- -->
<php>$item_star = ff_mysql_star('nationality:香港,台湾;limit:6;cache_name:default;cache_time:default;order:person_hits;sort:desc');</php>
<notempty name="item_star">
<div class="container ff-bg">
	<div class="page-header">
		<h2 class="text-ellipsis">
		<strong><span class="glyphicon glyphicon-fire text-green"></span> 港台热星</strong>
		<a class="pull-right text-green" href="{:ff_url('list/select',array('id'=>$list_id,'area'=>urlencode('香港,台湾'),'gender'=>'','profession'=>'','letter'=>'','order'=>'hits','p'=>1),true)}">更多<span class="glyphicon glyphicon-chevron-right"></span></a>
		</h2>
	</div>
	<ul class="list-unstyled ff-item">
		<volist name="item_star" id="feifei">
		<include file="BlockTheme:item_img_star" />
		</volist>
	</ul>
</div>
<div class="clearix pb-2"></div>
</notempty>
<!-- -->
<php>$item_star = ff_mysql_star('nationality:日本,韩国;limit:6;cache_name:default;cache_time:default;order:person_hits;sort:desc');</php>
<notempty name="item_star">
<div class="container ff-bg">
	<div class="page-header">
		<h2 class="text-ellipsis">
		<strong><span class="glyphicon glyphicon-fire text-green"></span> 日韩热星</strong>
		<a class="pull-right text-green" href="{:ff_url('list/select',array('id'=>$list_id,'area'=>urlencode('日本,韩国'),'gender'=>'','profession'=>'','letter'=>'','order'=>'hits','p'=>1),true)}">更多<span class="glyphicon glyphicon-chevron-right"></span></a>
		</h2>
	</div>
	<ul class="list-unstyled ff-item">
		<volist name="item_star" id="feifei">
		<include file="BlockTheme:item_img_star" />
		</volist>
	</ul>
</div>
<div class="clearix pb-2"></div>
</notempty>
<!-- -->
<php>$item_star = ff_mysql_star('nationality:美国,加拿大,德国,法国,英国,西班牙,瑞典,瑞士,挪威,奥地利,意大利,芬兰;limit:6;cache_name:default;cache_time:default;order:person_hits;sort:desc');</php>
<notempty name="item_star">
<div class="container ff-bg">
	<div class="page-header">
		<h2 class="text-ellipsis">
		<strong><span class="glyphicon glyphicon-fire text-green"></span> 欧美热星</strong>
		<a class="pull-right text-green" href="{:ff_url('list/select',array('id'=>$list_id,'area'=>urlencode('美国,加拿大,德国,法国,英国,西班牙,瑞典,瑞士,挪威,奥地利,意大利,芬兰'),'gender'=>'','profession'=>'','letter'=>'','order'=>'hits','p'=>1),true)}">更多<span class="glyphicon glyphicon-chevron-right"></span></a>
		</h2>
	</div>
	<ul class="list-unstyled ff-item">
		<volist name="item_star" id="feifei">
		<include file="BlockTheme:item_img_star" />
		</volist>
	</ul>
</div>
<div class="clearix pb-2"></div>
</notempty>
<!-- -->
</div><!--container end -->
<div class="clearfix mb-2"></div>
<div class="container ff-bg">
  <include file="BlockTheme:footer" />
</div>
</body>
</html>