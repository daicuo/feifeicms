<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/list_new" />
</head>
<body class="list-new">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
  <h2>
  <span class="glyphicon glyphicon-film text-green"></span>
  <a href="{:ff_url('list/read',array('id'=>$list_id,'p'=>1),true)}">{$list_name}</a>
  </h2>
</div>
<div class="table-responsive">
  <table class="table table-striped table-bordered table-responsive">  
    <tbody>
    	<volist name=":ff_mysql_vod('field:list_id,list_dir,list_name,vod_id,vod_actor,vod_type,vod_area,vod_ename,vod_name,vod_jumpurl,vod_url,vod_pic,vod_content,vod_addtime;limit:100;cache_name:default;cache_time:default;order:vod_addtime;sort:desc')" id="feifei">
			<php>$playurl_end = ff_url_play_end($feifei['vod_url']);</php>
      <tr>
        <td class="col-xs-6">
				[<a href="{:ff_url_vod_show($feifei['list_id'],$feifei['list_dir'],1)}" target="_blank">{$feifei.list_name}</a>]
				<a href="{:ff_url_read_vod($feifei['list_id'],$feifei['list_dir'],$feifei['vod_id'],$feifei['vod_ename'],$feifei['vod_jumpurl'])}" title="{$feifei.vod_name}" target="_blank">{$feifei.vod_name|msubstr=0,25}</a>
				<span class="text-muted">{$playurl_end|ff_array=2}</span>
				</td>
        <td class="col-xs-1 text-center">
					<a class="text-success" href="{:ff_url_play($feifei['list_id'],$feifei['list_dir'],$feifei['vod_id'],$feifei['vod_ename'],ff_array($playurl_end,0),ff_array($playurl_end,1))}" target="_blank">免费观看</a>
				</td>				
        <td class="col-xs-1 text-center">
					<a href="{:ff_url('vod/juqing', array('id'=>$feifei['vod_id']), true)}" target="_blank">剧情介绍</a>
				</td>
				<td class="col-xs-1 text-center">
					<a href="{:ff_url('vod/zixun', array('id'=>$feifei['vod_id']), true)}" target="_blank">新闻资讯</a>
				</td>				
				<td class="col-xs-1 text-center">
					<a href="{:ff_url('vod/yanyuan', array('id'=>$feifei['vod_id']), true)}" target="_blank">演员表</a>
				</td>
				<td class="col-xs-1 text-center">
					<a href="{:ff_url('vod/forum', array('id'=>$feifei['vod_id']), true)}" target="_blank">精彩影评</a>
				</td>
				<td class="col-xs-1 text-center">
					<small>{$feifei.vod_addtime|date='Y-m-d',###}</small>
				</td>
      </tr>
      </volist>
    </tbody>
  </table>
</div>
</div><!--container end -->
<div class="clearfix mb-1"></div>
<include file="BlockTheme:footer" />
</body>
</html>