<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/scenario_detail" />
</head>
<body class="scenario-detail">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
  <h2 class="text-ellipsis">
  <span class="glyphicon glyphicon-film text-green"></span>
  <a href="{:ff_url('scenario/read',array('id'=>$vod_id),true)}">{$vod_name} 剧情介绍</a>
	<label class="pull-right hidden-xs hidden-sm"><include file="./Tpl/base/bootstrap3/inc_share" /></label>
  </h2>
</div>
<p class="lead">
	{$vod_content|strip_tags}
	<small><a class="text-green" href="{:ff_url_read_vod($list_id,$list_dir,$vod_id,$vod_ename,$vod_jumpurl)}">@影片详情</a></small>
</p>
<div class="clearfix"></div>
<notempty name="vod_scenario.info">
<include file="./Tpl/base/bootstrap3/vod_playurl" />
<div class="page-header">
  <h2>
    <span class="glyphicon glyphicon-th-list text-green"></span> 
    <a href="{:ff_url('scenario/read', array('id'=>$vod_id), true)}" title="{$vod_name}分集剧情">{$vod_name} 分集剧情</a>
  </h2>
</div>
<volist name="vod_scenario.info" id="feifei">
<dl>
  <dt><a href="{:ff_url('scenario/read', array('id'=>$vod_id, pid=>$i), true)}">{$vod_name} 第{$i}集 剧情介绍</a></dt>
  <dd>{$feifei|msubstr=0,140,true} <a class="text-green" href="{:ff_url('scenario/read', array('id'=>$vod_id, pid=>$i), true)}">详情>></a></dd>
</dl>
</volist>
</notempty>
</div><!--container end -->
<div class="clearfix mb-2"></div>
<div class="container ff-bg">
  <include file="BlockTheme:footer" />
</div>
</body>
</html>