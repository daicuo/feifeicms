<php>if($search_wd){
	$item_news = ff_mysql_news('wd:'.$search_wd.';limit:10;page_is:true;page_id:search;page_p:'.$search_page.';cache_name:default;cache_time:default;order:news_'.$search_order.';sort:desc');
  $jump = array('wd'=>urlencode($search_wd),'p'=>'FFLINK');
  $page_info = ff_url_page('news/search', $jump, true, 'search', 4);
}else if($search_remark){
	$item_news = ff_mysql_vod('remark:'.$search_remark.';limit:10;page_is:true;page_id:search;page_p:'.$search_page.';cache_name:default;cache_time:default;order:news_'.$search_order.';sort:desc');
  $jump = array('remark'=>urlencode($search_remark),'p'=>'FFLINK');
  $page_info = ff_url_page('news/search', $jump, true, 'search', 4);
}else if($search_name){
	$item_news = ff_mysql_news('name:'.$search_name.';limit:10;page_is:true;page_id:search;page_p:'.$search_page.';cache_name:default;cache_time:default;order:news_'.$search_order.';sort:desc');
  $jump = array('name'=>urlencode($search_name),'p'=>'FFLINK');
  $page_info = ff_url_page('news/search', $jump, true, 'search', 4);
}else if($search_title){
	$item_news = ff_mysql_news('title:'.$search_title.';limit:10;page_is:true;page_id:search;page_p:'.$search_page.';cache_name:default;cache_time:default;order:news_'.$search_order.';sort:desc');
  $jump = array('title'=>urlencode($search_title),'p'=>'FFLINK');
  $page_info = ff_url_page('news/search', $jump, true, 'search', 4);
}
$page_total = ff_page_count('search', 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/news_search" />
</head>
<body class="news-search">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
  <h2>
   <span class="glyphicon glyphicon-list-alt text-green"></span>
	 搜索 》{$search_name}{$search_wd}
   <small>共有<span class="text-green">{:ff_page_count('search', 'records')}</span>篇
	 第<span class="text-green">{$search_page}</span>页</small>
  </h2>
</div>
<!-- -->
<volist name="item_news" id="feifei">
	<include file="BlockTheme:item_medial_news" />
</volist>
<!-- -->
<gt name="page_total" value="1">
  <div class="clearfix"></div>
  <div class="text-center">
    <ul class="pagination pagination-lg hidden-xs">
      {$page_info}
    </ul>
    <ul class="pager visible-xs">
      <gt name="search_page" value="1">
        <li><a id="ff-prev" href="{:ff_url('news/search', array_merge($jump,array('p'=>$search_page-1)), true)}">上一页</a></li>
      </gt>
      <lt name="search_page" value="$page_total">
        <li><a id="ff-next" href="{:ff_url('news/search', array_merge($jump,array('p'=>$search_page+1)), true)}">下一页</a></li>
      </lt>
     </ul>
  </div>
</gt>
<div class="page-header">
  <h2><span class="glyphicon glyphicon-signal text-green"></span> 最近更新</h2>
</div>
<php>$item_news = ff_mysql_news('limit:20;cache_name:default;cache_time:default;order:news_addtime;sort:desc');</php>
<ul class="news-item-ul ff-row">
  <volist name="item_news" id="feifei">
    <include file="BlockTheme:item_txt_news_hits" />
  </volist>
</ul>
</div><!--container end -->
<div class="clearfix mb-2"></div>
<div class="container ff-bg">
  <include file="BlockTheme:footer" />
</div>
</body>
</html>