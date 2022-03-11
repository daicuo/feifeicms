<php>
$item_vod = ff_mysql_vod('cid:'.$list_id.';limit:30;page_is:true;page_id:list;page_p:'.$list_page.';cache_name:default;cache_time:default;order:vod_stars desc,vod_addtime;sort:desc');
if($action == 'ename'){
	$page = ff_url_page('list/ename',array('id'=>$list_dir,'p'=>'FFLINK'),true,'list',4);
}else{
  $page = ff_url_page('list/read',array('id'=>$list_id,'list_dir'=>$list_dir,'p'=>'FFLINK'),true,'list',4);
}
$totalpages = ff_page_count('list', 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/vod_list" />
</head>
<body class="vod-list">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
  <h2>
  <span class="glyphicon glyphicon-film text-green"></span>
  <a href="{:ff_url_vod_show($list_id,$list_dir,1)}">{$list_name}</a>
  <small>共有<span class="text-green">{:ff_page_count('list', 'records')}</span>个影片 第<span class="text-green">{$list_page}</span>页</small>
  <a class="pull-right text-green" href="{:ff_url('list/select',array('id'=>$list_id,'type'=>'','area'=>'','year'=>'','star'=>'','state'=>'','order'=>'hits','p'=>1),true)}"><span class="glyphicon glyphicon-filter"></span> 筛选</a>
  </h2>
</div>
<ul class="list-unstyled vod-item-img ff-img-140">
  <volist name="item_vod" id="feifei">
  <include file="BlockTheme:item_img_vod" />
  </volist>
</ul>
<gt name="totalpages" value="1">
<div class="clearfix"></div>
<div class="text-center">
  <ul class="pager">
    <gt name="list_page" value="1">
      <li><a id="ff-prev" href="{:ff_url_vod_show($list_id,$list_dir,$list_page-1)}">上一页</a></li>
    </gt>
    <lt name="list_page" value="$totalpages">
      <li><a id="ff-next" href="{:ff_url_vod_show($list_id,$list_dir,$list_page+1)}">下一页</a></li>
    </lt>
   </ul> 
</div>
</gt>
</div>
<!--container end -->
<include file="BlockTheme:footer" />
</body>
</html>