<php>$item_news = ff_mysql_news('cid:'.ff_list_ids($list_id).';limit:20;page_is:true;page_id:list;page_p:'.$list_page.';cache_name:default;cache_time:default;order:news_id;sort:desc');
$page_total = ff_page_count('list', 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/news_list" />
</head>
<body class="news-list">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header mb-0">
  <h2>
		<span class="glyphicon glyphicon-list-alt text-green"></span>
		<a href="{:ff_url_news_show($list_id,$list_dir,1)}">{$list_name}</a>
		<small>共有<span class="text-green">{:ff_page_count('list', 'records')}</span>篇{$list_name} 第<span class="text-green">{$list_page}</span>页</small>
		<notempty name="list_extend.type">
		<div class="btn-group pull-right">
			<button type="button" class="btn btn-success dropdown-toggle btn-xs" data-toggle="dropdown">筛选
			<span class="caret"></span>
			</button>
			<ul class="dropdown-menu" role="menu">
			<volist name=":explode(',',$list_extend['type'])" id="feifei">
			<li><a href="{:ff_url('list/select',array('id'=>$list_id,'type'=>urlencode($feifei),'p'=>1),true)}">{$feifei|msubstr=0,6,true}</a></li>
			</volist>
			</ul>
		</div>
		</notempty>	
  </h2>
</div>
<volist name="item_news" id="feifei">
	<include file="BlockTheme:item_medial_news" />
</volist>
</div>
<!-- -->
<gt name="page_total" value="1">
<div class="container ff-bg text-center">
  <ul class="pager">
    <gt name="list_page" value="1">
      <li><a id="ff-prev" href="{:ff_url_news_show($list_id,$list_dir,$list_page-1)}">上一页</a></li>
    </gt>
    <lt name="list_page" value="$page_total">
      <li><a id="ff-next" href="{:ff_url_news_show($list_id,$list_dir,$list_page+1)}">下一页</a></li>
    </lt>
   </ul>
</div>
<div class="clearfix mb-1"></div>
</gt>
<!-- -->
<div class="container ff-bg">
<div class="page-header">
  <h2><span class="glyphicon glyphicon-signal text-green"></span> 热门{$list_name}</h2>
</div>
<php>$item_news = ff_mysql_news('cid:'.$list_id.';limit:10;cache_name:default;cache_time:default;order:news_hits;sort:desc');</php>
<ul class="news-item-ul ff-row">
  <volist name="item_news" id="feifei">
    <include file="BlockTheme:item_txt_news_hits" />
  </volist>
</ul>
</div>
<div class="clearfix mb-1"></div>
<!--container end -->
<include file="BlockTheme:footer" />
</body>
</html>