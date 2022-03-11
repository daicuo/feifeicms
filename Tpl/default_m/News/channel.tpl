<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/news_channel" />
</head>
<body class="news-list">
<include file="BlockTheme:header" />
<volist name=":explode(',',$list_extend['type'])" id="feifei" offset="0" length="12">
<php>$item_news=ff_mysql_news('cid:'.ff_list_ids($list_id).';tag_name:'.$feifeilist.';tag_list:news_type;limit:10;cache_name:default;cache_time:default;order:news_id;sort:desc');if(!$item_news){continue;}</php>
<div class="container ff-bg">
<div class="page-header mb-0">
  <h2>
    <span class="glyphicon glyphicon-list-alt text-green"></span>
    <a href="{:ff_url('list/select',array('id'=>$list_id,'type'=>urlencode($feifei),'p'=>1),true)}">{$feifei}</a>
		<span class="pull-right">
		<a class="btn btn-success btn-xs" href="{:ff_url('list/select',array('id'=>$list_id,'type'=>urlencode($feifei),'p'=>1),true)}">更多</a>
		</span>
  </h2>
</div>
<ul class="news-item-ul ff-row">
  <volist name="item_news" id="feifei">
    <include file="BlockTheme:item_txt_news_hits" />
  </volist>
</ul>
</div><!--container end -->
<div class="clearfix mb-1"></div>
</volist>
<include file="BlockTheme:footer" />
</body>
</html>