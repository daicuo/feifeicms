<php>$item_vod = ff_mysql_vod('cid:1,2,3;scenario:true;limit:20;page_is:true;page_id:list;page_p:'.$list_page.';cache_name:default;cache_time:default;order:vod_addtime;sort:desc');
$page_total = ff_page_count('list', 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/scenario_list" />
</head>
<body class="scenario-list">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
  <h2>
  <span class="glyphicon glyphicon-list-alt text-green"></span>
  <a href="{:ff_url('list/read',array('id'=>$list_id,'p'=>1),true)}">{$list_name}</a>
  <small>共有<span class="text-green">{:ff_page_count('list', 'records')}</span>篇 第<span class="text-green">{$list_page}</span>页</small>
  </h2>
</div>
<volist name="item_vod" id="feifei">
<div class="panel panel-default">
  <div class="panel-heading">
    <h2 class="panel-title">
    <a href="{:ff_url('scenario/read', array('id'=>$feifei['vod_id']), true)}" title="《{$feifei.vod_name}》剧情介绍">《{$feifei.vod_name}》剧情介绍</a>
    <small>{$feifei.vod_addtime|date='Y/m/d',###}</small>
    </h2>
  </div>
  <div class="panel-body">
    <a class="vod-pic" href="{:ff_url('scenario/read', array('id'=>$feifei['vod_id']), true)}">
      <img class="img-responsive img-thumbnail ff-img" data-original="{$feifei['vod_pic']|ff_url_img=$vod_content}" alt="{$feifei.vod_name}剧情介绍">
    </a>
    <p>{$feifei.vod_content|msubstr=0,160,true}</p>
  </div>
  <div class="panel-footer text-right text-green">
    <a href="{:ff_url_read_vod($feifei['list_id'],$feifei['list_dir'],$feifei['vod_id'],$feifei['vod_ename'],$feifei['vod_jumpurl'])}">在线观看</a>
    <a href="{:ff_url('vod/forum',array('id'=>$feifei['vod_id'],'p'=>1),true)}">相关影评</a>
    <a href="{:ff_url('scenario/read', array('id'=>$feifei['vod_id']), true)}">全部剧情</a>
  </div>
</div>
</volist>
</div>
<div class="clearfix mb-1"></div>
<!-- -->
<gt name="page_total" value="1">
<div class="container ff-bg">
  <div class="clearfix"></div>
  <div class="text-center">
    <ul class="pager">
      <gt name="list_page" value="1">
        <li><a id="ff-prev" href="{:ff_url('list/'.$action, array('id'=>$list_id,'p'=>$list_page-1), true)}">上一页</a></li>
      </gt>
      <lt name="scenario_page" value="$page_total">
        <li><a id="ff-next" href="{:ff_url('list/'.$action, array('id'=>$list_id,'p'=>$list_page+1), true)}">下一页</a></li>
      </lt>
     </ul> 
  </div>
</div>
<div class="clearfix mb-1"></div>	
</gt>
<!-- -->
<include file="BlockTheme:footer" />
</body>
</html>