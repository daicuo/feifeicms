<php>$item_vod = ff_mysql_vod('cid:1,2,3;scenario:true;limit:10;page_is:true;page_id:list;page_p:'.$list_page.';cache_name:default;cache_time:default;order:vod_addtime;sort:desc');
if($action == 'ename'){
	$page = ff_url_page('list/ename',array('id'=>$list_dir,'p'=>'FFLINK'),true,'list',4);
}else{
  $page = ff_url_page('list/read',array('id'=>$list_id,'list_dir'=>$list_dir,'p'=>'FFLINK'),true,'list',4);
}
$totalpages = ff_page_count('list', 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/scenario_list" />
</head>
<body class="scenario-list">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
  <h2>
  <span class="glyphicon glyphicon-list-alt text-green"></span>
  <a href="{:ff_url_show('list/read',array('id'=>$list_id,'p'=>1),true)}">{$list_name}</a>
  <small>共有<span class="text-green">{:ff_page_count('list', 'records')}</span>篇剧情 第<span class="text-green">{$list_page}</span>页</small>
  </h2>
</div>
<volist name="item_vod" id="feifei">
<php>$vod_scenario = json_decode($feifei['vod_scenario'],true);</php>
<div class="panel panel-default">
  <div class="panel-heading">
    <h2 class="panel-title">
    <a href="{:ff_url('scenario/read', array('id'=>$feifei['vod_id']), true)}">《{$feifei.vod_name}》剧情介绍</a>
    <small>{$feifei.vod_addtime|date='Y/m/d',###}</small>
    </h2>
  </div>
  <div class="panel-body">
    <a class="vod-pic" href="{:ff_url('scenario/read', array('id'=>$feifei['vod_id']), true)}">
      <img class="img-responsive img-thumbnail ff-img" data-original="{$feifei['vod_pic']|ff_url_img=$vod_content}" alt="{$feifei.vod_name}剧情介绍">
    </a>
    <p class="lead">
			{$vod_scenario['info']|end|msubstr=0,290,true}
			<span class="text-muted">@{$feifei.vod_name}第{$vod_scenario['info']|count}集剧情</span>
		</p>
  </div>
</div>
<div class="clearfix"></div>
</volist>
<!-- -->
<gt name="totalpages" value="1">
  <div class="clearfix"></div>
  <div class="text-center">
    <ul class="pagination pagination-lg hidden-xs">
      {$page}
    </ul>
    <ul class="pager visible-xs">
      <gt name="list_page" value="1">
        <li><a id="ff-prev" href="{:ff_url('list/read', array('id'=>$list_id,'p'=>$list_page-1), true)}">上一页</a></li>
      </gt>
      <lt name="list_page" value="$totalpages">
        <li><a id="ff-next" href="{:ff_url('list/read', array('id'=>$list_id,'p'=>$list_page+1), true)}">下一页</a></li>
      </lt>
     </ul> 
  </div>
</gt>
</div>
<div class="clearfix mb-2"></div>
<div class="container ff-bg">
  <include file="BlockTheme:footer" />
</div>
</body>
</html>