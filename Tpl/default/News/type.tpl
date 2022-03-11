<php>
$item_news = ff_mysql_news('cid:'.$list_id.';limit:10;tag_name:'.$select_type.';tag_list:news_type;page_is:true;page_id:list;page_p:'.$select_page.';cache_name:default;cache_time:default;order:news_addtime;sort:desc');
$page = ff_url_page('list/select',array('type'=>urlencode($select_type),'id'=>$list_id,'p'=>'FFLINK'), true, 'list', 4);
$totalpages = ff_page_count('list', 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/news_type" />
</head>
<body class="news-type">
<include file="BlockTheme:header" />
<div class="container ff-bg">
	<ul class="list-unstyled ff-select">
	<li class="col-xs-6 col-md-1"><a class="btn btn-default btn-block" href="{:ff_url('list/read',array('id'=>$list_id,'p'=>1),true)}">{$list_name}</a></li>
	<volist name=":explode(',',$list_extend['type'])" id="feifei">
	<li class="col-xs-6 col-md-1 <eq name="feifei" value="$select_type">active</eq>"><a class="btn btn-default btn-block" href="{:ff_url('list/select',array('id'=>$list_id,'type'=>urlencode($feifei),'p'=>1),true)}">{$feifei|msubstr=0,4}</a></li>
	</volist>
	</ul>
</div>
<div class="clearfix mb-2"></div>
<!-- -->
<div class="container ff-bg">
<div class="page-header">
  <h2 class="text-ellipsis">
  <span class="glyphicon glyphicon-pencil text-green"></span>
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
    <gt name="type_pag" value="1">
      <li><a id="ff-prev" href="{:ff_url('list/select',array('type'=>$select_type,'id'=>$list_id,'p'=>$select_page-1))}">上一页</a></li>
    </gt>
    <lt name="type_pag" value="$totalpages">
      <li><a id="ff-next" href="{:ff_url('list/select',array('type'=>$select_type,'id'=>$list_id,'p'=>$select_page+1))}">下一页</a></li>
    </lt>
   </ul>
</div> 
</gt>
<!-- -->
<div class="page-header">
  <h2><span class="glyphicon glyphicon-signal text-green"></span> 热门{$select_type}</h2>
</div>
<php>$item_news = ff_mysql_news('cid:'.$list_id.';tag_name:'.$select_type.';tag_type:news_type;limit:20;cache_name:default;cache_time:default;order:news_hits;sort:desc');</php>
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