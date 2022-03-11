<php>$item_news = ff_mysql_news('cid:'.$list_id.';limit:20;page_is:true;page_id:list;page_p:'.$list_page.';cache_name:default;cache_time:default;order:news_addtime;sort:desc');
$page = ff_url_page('list/'.$action,array('id'=>$list_id,'list_dir'=>$list_dir,'p'=>'FFLINK'),true,'list',4);
$totalpages = ff_page_count('list', 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/news_list" />
</head>
<body class="news-list">
<include file="BlockTheme:header" />
<div class="container ff-bg">
	<ul class="list-unstyled ff-select">
	<li class="col-xs-6 col-md-1 active"><a class="btn btn-default btn-block" href="{:ff_url('list/read',array('id'=>$list_id,'p'=>1),true)}">{$list_name}</a></li>
	<volist name=":explode(',',$list_extend['type'])" id="feifei">
	<li class="col-xs-6 col-md-1"><a class="btn btn-default btn-block" href="{:ff_url('list/select',array('id'=>$list_id,'type'=>urlencode($feifei),'p'=>1),true)}">{$feifei|msubstr=0,4}</a></li>
	</volist>
	</ul>
</div>
<div class="clearfix mb-2"></div>
<!-- -->
<div class="container ff-bg">
<div class="page-header">
  <h2 class="text-ellipsis">
  <span class="glyphicon glyphicon-list-alt text-green"></span>
  共有<span class="text-green">{:ff_page_count('list', 'records')}</span>篇内容
	<label class="pull-right hidden-xs hidden-sm"><include file="./Tpl/base/bootstrap3/inc_share" /></label>
  </h2>
</div>
<!-- -->
<volist name="item_news" id="feifei">
	<include file="BlockTheme:item_medial_news" />
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
      <li><a id="ff-prev" href="{:ff_url_show('list/'.$action,array('id'=>$list_id,'list_dir'=>$list_dir,'p'=>$list_page-1))}">上一页</a></li>
    </gt>
    <lt name="list_page" value="$totalpages">
      <li><a id="ff-next" href="{:ff_url_show('list/'.$action,array('id'=>$list_id,'list_dir'=>$list_dir,'p'=>$list_page+1))}">下一页</a></li>
    </lt>
   </ul>
</div>
</gt>
<div class="page-header">
  <h2><span class="glyphicon glyphicon-signal text-green"></span> 热门{$list_name}</h2>
</div>
<php>$item_news = ff_mysql_news('cid:'.$list_id.';limit:20;cache_name:default;cache_time:default;order:news_hits;sort:desc');</php>
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