<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/scenario_detail_pid" />
</head>
<body class="scenario-detail-pid">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
  <h2>
  	<span class="glyphicon glyphicon-list-alt text-green"></span>
    {$vod_name}<strong>第{$scenario_pid}集</strong>剧情介绍
		<small><a href="{:ff_url_vod_show($list_id,$list_dir,1)}">{$list_name}</a></small>
  </h2>
</div>
<php>$scenario_count = count($vod_scenario['info']);</php>
<p class="lead">
	{:ff_url_tags_content(strip_tags($vod_scenario['info'][$scenario_pid-1],'<a>'),$Tag)}
	<gt name="scenario_pid" value="1">
	<a class="text-green" id="ff-prev" href="{:ff_url('scenario/read', array('id'=>$vod_id,'pid'=>$scenario_pid-1), true)}">上一集</a>
	</gt>
	<lt name="scenario_pid" value="$scenario_count">
	<a class="text-green" id="ff-next" href="{:ff_url('scenario/read', array('id'=>$vod_id,'pid'=>$scenario_pid+1), true)}">下一集</a>
	</lt>
</p>
</div>
<div class="clearfix mb-1"></div>
<!-- -->
<include file="./Tpl/base/bootstrap3/vod_playurl" />
<notempty name="vod_scenario.info">
<div class="container ff-bg">
	<div class="page-header">
		<h2 class="text-ellipsis"><span class="glyphicon glyphicon-list-alt text-green"></span> {$vod_name}剧情列表</h2>
	</div>
	<ul class="ff-row item">
		<volist name="vod_scenario.info" id="feifei">
		<li class="ff-col">
		<a href="{:ff_url('scenario/read', array('id'=>$vod_id,pid=>$i), true)}" title="{$vod_name}第{$i}集剧情介绍">{$vod_name} 第{$i}集 剧情介绍</a>
		</li>
		</volist>
	</ul>
</div>
<div class="clearfix mb-1"></div>
<!-- -->
<div class="container ff-bg">
	<div class="page-header">
		<h2 class="text-ellipsis"><span class="glyphicon glyphicon-film text-green"></span> {$vod_name}在线观看</h2>
	</div>
	<ul class="ff-row item">
		<volist name="vod_scenario.info" id="feifei">
		<li class="ff-col">
		<a href="{:ff_url_play($list_id,$list_dir,$vod_id,$vod_ename,$playurl_line[0]['player_sid'],$i)}">{$vod_name} 第{$i}集 在线观看</a>
		</li>
		</volist>
	</ul>
</div>
<div class="clearfix mb-1"></div>
</notempty>
<!-- -->
<include file="BlockTheme:footer" />
</body>
</html>