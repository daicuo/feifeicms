<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/news_channel" />
</head>
<body class="news-list">
<include file="BlockTheme:header" />
<volist name=":explode(',',$list_extend['type'])" id="feifei" offset="0" length="12">
<php>$item_news = ff_mysql_news('cid:'.$list_id.';tag_name:'.$feifei.';tag_list:news_type;limit:20;cache_name:default;cache_time:default;order:news_addtime;sort:desc');</php>
<notempty name="item_news">
<div class="container ff-bg">
<div class="page-header">
  <h2 class="text-ellipsis">
    <span class="glyphicon glyphicon-list-alt text-green"></span>
    <a href="{:ff_url('list/select',array('id'=>$list_id,'type'=>urlencode($feifei),'p'=>1),true)}">{$feifei}</a>
		<a class="pull-right text-green" href="{:ff_url('list/select',array('id'=>$list_id,'type'=>urlencode($feifei),'p'=>1),true)}">更多>></a>
  </h2>
</div>
<ul class="news-item-ul ff-row">
  <volist name="item_news" id="feifei">
    <include file="BlockTheme:item_txt_news_hits" />
  </volist>
</ul>
</div><!--container end -->
<div class="clearfix mb-2"></div>
</notempty>
</volist>
<div class="clearfix mb-2"></div>
<div class="container ff-bg">
  <include file="BlockTheme:footer" />
</div>
</body>
</html>