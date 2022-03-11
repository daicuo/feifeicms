<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/news_detail" />
</head>
<body class="news-detail">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<h2 class="text-center">
	{$news_name}
</h2>
<div class="page-header">
  <h5 class="text-muted text-center visible-md visible-lg">
    来源：{$news_inputer|default='佚名'}
    人气：{$news_hits}
    更新：{$news_addtime|date='Y-m-d H:i:s',###}
  </h5>
	<div class="row hidden-xs hidden-sm">
	<div class="col-md-4 col-md-offset-5">
		<include file="./Tpl/base/bootstrap3/inc_share" />
	</div> 
	</div>    
</div>
<div class="row ff-row">
<div class="col-xs-12 ff-col">
	<blockquote class="text-muted hidden-xs">
    {$news_remark}
  </blockquote> 
  <div class="content news-content ff-content">
    {$news_content}
  </div>
  <p class="tags text-right">  
    <volist name="Tag" id="feifei">
    <eq name="feifei.tag_list" value="news_type">
      <a class="btn btn-default btn-xs" href="{:ff_url('list/select',array('type'=>urlencode($feifei['tag_name']),'id'=>$list_id),true)}">
      <span class="glyphicon glyphicon-tag"></span> {$feifei.tag_name|msubstr=0,4}
      </a>
    <else/>
      <a class="btn btn-default btn-xs" href="{:ff_url_tags($feifei['tag_name'],$feifei['tag_list'])}">
      <span class="glyphicon glyphicon-tag"></span> {$feifei.tag_name|msubstr=0,4}
      </a>
    </eq>
    </volist>
  </p>   
  <!-- -->
	<include file="./Tpl/base/bootstrap3/news_detail_next" />
  <p class="tags text-center">
    <a class="btn btn-default btn-lg ff-updown-set" href="javascript:;" data-id="{$news_id}" data-module="news" data-type="up" data-toggle="tooltip" data-placement="top" title="支持">
      <span class="glyphicon glyphicon-thumbs-up"></span> 有用 (<span class="ff-updown-val">{$news_up}</span>)
    </a>
 		<gt name="news_page_count" value="1">
    <gt name="news_page" value="1">
      <a class="btn btn-default btn-lg" href="{:ff_url_read_news($list_id,$list_dir,$news_id,$news_ename,$news_jumpurl,$news_page-1)}">
      <span class="glyphicon glyphicon-chevron-left"></span> 上一页</a>
    </gt>
    <lt name="news_page" value="$news_page_count">
      <a class="btn btn-default btn-lg" href="{:ff_url_read_news($list_id,$list_dir,$news_id,$news_ename,$news_jumpurl,$news_page+1)}">
      <span class="glyphicon glyphicon-chevron-right"></span> 下一页</a>
    </lt>
    </gt>    
  </p>
</div>
</div><!--row end -->
<!-- -->
<div class="page-header">
  <h2><span class="glyphicon glyphicon-signal text-green"></span> 热门{$list_name}</h2>
</div>
<php>$item_news = ff_mysql_news('cid:'.$list_id.';limit:20;cache_name:default;cache_time:default;order:news_hits;sort:desc');</php>
<ul class="news-item-ul ff-row">
  <volist name="item_news" id="feifei">
    <include file="BlockTheme:item_txt_news_hits" />
  </volist>
</ul>
<!-- -->
<include file="./Tpl/base/bootstrap3/forum_ajax_news" />
{$news_hits_insert}
</div><!--container end -->
<div class="clearfix mb-2"></div>
<div class="container ff-bg">
  <include file="BlockTheme:footer" />
</div>
</body>
</html>