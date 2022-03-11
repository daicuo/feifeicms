<php>
$item_news = ff_mysql_news('cid:'.ff_list_ids($list_id).';limit:20;tag_name:'.$select_type.';tag_list:news_type;page_is:true;page_id:list;page_p:'.$select_page.';cache_name:default;cache_time:default;order:news_addtime;sort:desc');
$page_total = ff_page_count('list', 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/news_type" />
</head>
<body class="news-type">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header mb-0">
  <h2>
		<span class="glyphicon glyphicon-list-alt text-green"></span>
		<a href="{:ff_url_news_show($list_id,$list_dir,1)}">{$list_name}</a>
		<small>共有<span class="text-green">{:ff_page_count('list', 'records')}</span>篇{$list_name} 第<span class="text-green">{$select_page}</span>页</small>
		<notempty name="list_extend.type">
		<div class="btn-group pull-right">
			<button type="button" class="btn btn-success dropdown-toggle btn-xs" data-toggle="dropdown">筛选
			<span class="caret"></span>
			</button>
			<ul class="dropdown-menu" role="menu">
			<volist name=":explode(',',$list_extend['type'])" id="feifei">
			<eq name="feifei" value="$select_type">
			<li class="active"><a href="{:ff_url('list/select',array('id'=>$list_id,'type'=>urlencode($feifei),'p'=>1),true)}">{$feifei|msubstr=0,4}</a></li>
			<else/>
			<li><a href="{:ff_url('list/select',array('id'=>$list_id,'type'=>urlencode($feifei),'p'=>1),true)}">{$feifei|msubstr=0,4}</a></li>
			</eq>
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
    <gt name="select_page" value="1">
      <li><a id="ff-prev" href="{:ff_url('list/select',array('id'=>$list_id,'type'=>$select_type,'p'=>$select_page-1))}">上一页</a></li>
    </gt>
    <lt name="select_page" value="$page_total">
      <li><a id="ff-next" href="{:ff_url('list/select',array('id'=>$list_id,'type'=>$select_type,'p'=>$select_page+1))}">下一页</a></li>
    </lt>
   </ul>
</div> 
<div class="clearfix mb-1"></div>
</gt>
<!-- -->
<include file="BlockTheme:footer" />
</body>
</html>