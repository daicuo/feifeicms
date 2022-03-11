<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/scenario_detail" />
</head>
<body class="scenario-detail">
<include file="BlockTheme:header" />
<div class="container ff-bg">
	<div class="page-header">
		<h2>
		<span class="glyphicon glyphicon-film text-green"></span>
		<a href="{:ff_url('scenario/read',array('id'=>$vod_id),true)}">{$vod_name}剧情介绍</a>
		<small><a href="{:ff_url_vod_show($list_id,$list_dir,1)}">{$list_name}</a></small>
		</h2>
	</div>
</div>
<include file="BlockTheme:vod_inc_info" />
<div class="container ff-bg">
<notempty name="vod_scenario.info">
<include file="./Tpl/base/bootstrap3/vod_playurl" />
<div class="page-header">
  <h2>
    <span class="glyphicon glyphicon-th-list text-green"></span> 
    <a href="{:ff_url('vod/scenario', array('id'=>$vod_id), true)}" title="{$vod_name}分集剧情">{$vod_name}分集剧情</a>
  </h2>
</div>
<volist name="vod_scenario.info" id="feifei">
<dl>
  <dt class="text-green"><a href="{:ff_url('scenario/read', array('id'=>$vod_id, pid=>$i), true)}">{$vod_name} 第{$i}集 剧情介绍</a></dt>
  <dd>{$feifei} <a href="{:ff_url('scenario/read', array('id'=>$vod_id, pid=>$i), true)}" class="text-green">详情...</a></dd>
</dl>
</volist>
</notempty>
</div>
<div class="clearfix mb-1"></div>
<!--container end -->
<include file="BlockTheme:footer" />
</body>
</html>