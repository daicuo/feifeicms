<php>$items = ff_mysql_list(array(
'status'=>'1',
'limit'=>'0',
'order'=>'list_pid asc,list_oid',
'sort'=>'asc',
'cache_name'=>'default',
'cache_time'=>'default'
));</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/map_list" />
</head>
<body class="map-list">
<include file="BlockTheme:header" />
<volist name="items" id="feifei">
<if condition="$feifei['list_sid'] eq 1">
<div class="container ff-bg">
	<div class="page-header">
		<h4>
		<a class="text-green" href="{:ff_url('list/read',array('id'=>$feifei['list_id'],'p'=>1),true)}" target="_blank">{$feifei.list_name}</a>
		<volist name="feifei.list_son" id="feifeison" key="k_son">
		<a class="text-green ml-1" href="{:ff_url('list/read',array('id'=>$feifeison['list_id'],'p'=>1),true)}" target="_blank">{$feifeison.list_name}</a>
		</volist>
		</h4>
	</div>
	<ul class="list-inline pb-1">
	<volist name=":explode(',',$feifei['list_extend']['type'])" id="list_type">
		<li class="mb-1"><a href="{:ff_url('list/select',array('id'=>$feifei['list_id'],'type'=>urlencode($list_type),'area'=>'','year'=>'','star'=>'','state'=>'','order'=>'addtime','p'=>1),true)}" target="_blank">{$list_type}</a></li>
	</volist>
	</ul>
</div>
<div class="clearifx mb-1"></div>
<!-- -->
<elseif condition="$feifei['list_sid'] eq 2" />
<div class="container ff-bg">
	<div class="page-header">
		<h4>
		<a class="text-green" href="{:ff_url('list/read',array('id'=>$feifei['list_id'],'p'=>1),true)}" target="_blank">{$feifei.list_name}</a>
		<volist name="feifei.list_son" id="feifeison" key="k_son">
		<a class="text-green ml-1" href="{:ff_url('list/read',array('id'=>$feifeison['list_id'],'p'=>1),true)}" target="_blank">{$feifeison.list_name}</a>
		</volist>
		</h4>
	</div>
	<ul class="list-inline pb-1">
	<volist name=":explode(',',$feifei['list_extend']['type'])" id="list_type">
		<li class="mb-1"><a href="{:ff_url('list/select',array('id'=>$feifei['list_id'],'type'=>urlencode($list_type),'p'=>1),true)}" target="_blank">{$list_type}</a></li>
	</volist>
	</ul>
</div>
<div class="clearifx mb-1"></div>
<!-- -->
<elseif condition="$feifei['list_sid'] eq 29" />
<div class="container ff-bg">
	<div class="page-header">
		<h4 class="text-green">SiteMap</h4>
	</div>
	<ul class="list-inline pb-1">
	<volist name=":explode(',',$feifei['list_extend']['type'])" id="list_type">
		<li class="mb-1"><a href="{:ff_url('map/vod',array('id'=>$list_type,'limit'=>100,'p'=>1),false)}" target="_blank">{$list_type}</a></li>
	</volist>
	</ul>
</div>
<div class="clearifx mb-2"></div>
</if>
</volist>
<!-- -->
<div class="container ff-bg">
<div class="page-header">
  <h4 class="text-green">其它栏目</h4>
</div>
<ul class="list-inline pb-1">
<volist name="items" id="feifei">
<notin name="feifei.list_sid" value="1,2,29">
	<li class="mb-1"><a href="{:ff_url('list/read',array('id'=>$feifei['list_id'],'p'=>1),true)}" target="_blank">{$feifei.list_name}</a></li>
</notin>
</volist>
</ul>
</div>
<div class="clearifx mb-1"></div>
 <include file="BlockTheme:footer" />
</body>
</html>