<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/scenario_detail_pid" />
</head>
<body class="scenario-detail-pid">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<h2 class="text-center">
	{$vod_name}第{$scenario_pid}集剧情介绍
</h2>
<div class="row hidden-xs hidden-sm">
	<div class="col-md-3 col-md-offset-5">
		<include file="./Tpl/base/bootstrap3/inc_share" />
	</div> 
</div>
<h5 class="text-center visible-md visible-lg">
	人气：{$vod_hits}
	更新：{$vod_addtime|date='Y-m-d',###}
	<include file="./Tpl/base/bootstrap3/vod_type" /><include file="./Tpl/base/bootstrap3/vod_actor" />
</h5>
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
<div class="text-center">
	<a href="{:ff_url_read_vod($list_id,$list_dir,$vod_id,$vod_ename,$vod_jumpurl)}">
	<img class="img-responsive img-thumbnail ff-img" data-original="{$vod_pic|ff_url_img=$vod_content}" alt="{$vod_name}免费观看">
	</a>
</div>
<notempty name="vod_scenario.info">
<include file="./Tpl/base/bootstrap3/vod_playurl" />
<div class="page-header">
  <h2>
	<span class="glyphicon glyphicon-list-alt text-green"></span>
	{$vod_name} 分集剧情
	</h2>
</div>
<ul class="ff-row item">
  <volist name="vod_scenario.info" id="feifei">
  <li class="col-md-4 ff-col">
  <a href="{:ff_url('scenario/read', array('id'=>$vod_id,pid=>$i), true)}" title="{$vod_name}第{$i}集剧情介绍">{$vod_name} 第{$i}集 剧情介绍</a>
  </li>
  </volist>
</ul>
<div class="clearfix mb-2"></div>
<div class="page-header">
  <h2>
	<span class="glyphicon glyphicon-play text-green"></span>
	{$vod_name} 在线观看
	</h2>
</div>
<ul class="ff-row item">
  <volist name="vod_scenario.info" id="feifei">
  <li class="col-md-4 ff-col">
  <a href="{:ff_url_play($list_id,$list_dir,$vod_id,$vod_ename,ff_array($playurl_end,0),$i)}">{$vod_name} 第{$i}集 在线观看</a>
  </li>
  </volist>
</ul>
<div class="clearfix mb-2"></div>
</notempty>
</div><!--container end -->
<div class="clearfix mb-2"></div>
{$vod_hits_insert}
<div class="container ff-bg">
  <include file="BlockTheme:footer" />
</div>
</body>
</html>