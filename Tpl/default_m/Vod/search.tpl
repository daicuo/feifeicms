<php>
if($search_wd){
	$item_vod = ff_mysql_vod('wd:'.$search_wd.';limit:30;page_is:true;page_id:search;page_p:'.$search_page.';cache_name:default;cache_time:default;order:vod_'.$search_order.';sort:desc');
  $params = array('wd'=>urlencode($search_wd),'p'=>'FFLINK');
  $page = ff_url_page('vod/search', $params, true, 'search', 4);
}else if($search_actor){
	$item_vod = ff_mysql_vod('actor:'.$search_actor.';limit:30;page_is:true;page_id:search;page_p:'.$search_page.';cache_name:default;cache_time:default;order:vod_'.$search_order.';sort:desc');
  $params = array('actor'=>urlencode($search_actor),'p'=>'FFLINK');
  $page = ff_url_page('vod/search', $params, true, 'search', 4);
}else if($search_director){
	$item_vod = ff_mysql_vod('director:'.$search_director.';limit:30;page_is:true;page_id:search;page_p:'.$search_page.';cache_name:default;cache_time:default;order:vod_'.$search_order.';sort:desc');
  $params = array('director'=>urlencode($search_director),'p'=>'FFLINK');
  $page = ff_url_page('vod/search', $params, true, 'search', 4);
}else if($search_writer){
	$item_vod = ff_mysql_vod('writer:'.$search_writer.';limit:30;page_is:true;page_id:search;page_p:'.$search_page.';cache_name:default;cache_time:default;order:vod_'.$search_order.';sort:desc');
  $jump = array('writer'=>urlencode($search_writer),'p'=>'FFLINK');
  $page_info = ff_url_page('vod/search', $jump, true, 'search', 4);
}else if($search_name){
	$item_vod = ff_mysql_vod('name:'.$search_name.';limit:30;page_is:true;page_id:search;page_p:'.$search_page.';cache_name:default;cache_time:default;order:vod_'.$search_order.';sort:desc');
  $params = array('name'=>urlencode($search_name),'p'=>'FFLINK');
  $page = ff_url_page('vod/search', $params, true, 'search', 4);
}else if($search_title){
	$item_vod = ff_mysql_vod('title:'.$search_title.';limit:30;page_is:true;page_id:search;page_p:'.$search_page.';cache_name:default;cache_time:default;order:vod_'.$search_order.';sort:desc');
  $params = array('title'=>urlencode($search_title),'p'=>'FFLINK');
  $page = ff_url_page('vod/search', $params, true, 'search', 4);
}
$totalpages = ff_page_count('search', 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/vod_search" />
</head>
<body class="vod-search">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
  <h2>
  <span class="glyphicon glyphicon-search text-green"></span> 
	搜索 》{$search_name}{$search_actor}{$search_director}{$search_writer}{$search_wd}
  <small>共有<span class="text-green">{:ff_page_count('search', 'records')}</span>个 第<span class="text-green">{$search_page}</span>页</small>
  </h2>
</div>
<ul class="list-unstyled vod-item-img ff-img-140">
  <volist name="item_vod" id="feifei">
  <include file="BlockTheme:item_img_vod" />
  </volist>
</ul>
<gt name="totalpages" value="1">
<div class="clearfix mb-1"></div>
<div class="text-center">
  <ul class="pager">
    <gt name="search_page" value="1">
      <php>$params['p'] = $search_page-1</php>
      <li><a id="ff-prev" href="{:ff_url('vod/search', $params, true)}">上一页</a></li>
    </gt>
    <lt name="search_page" value="$totalpages">
      <php>$params['p'] = $search_page+1</php>
      <li><a id="ff-next" href="{:ff_url('vod/search', $params, true)}">下一页</a></li>
    </lt>
 </ul>
</div>
</gt>
</div><!--container end -->
<div class="clearfix mb-1"></div>
<include file="BlockTheme:footer" />
</body>
</html>