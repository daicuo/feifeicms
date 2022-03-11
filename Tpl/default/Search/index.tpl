<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/search_index" />
</head>
<body class="search-index">
<include file="BlockTheme:header" />
<div class="container ff-bg pt-2">
  <include file="./Tpl/base/bootstrap3/search_select" />
  <dl>
    <dt>热门搜索：</dt>
    <dd><volist name=":ff_mysql_vod('limit:50;cache_name:default;cache_time:default;order:vod_stars desc,vod_hits_lasttime;sort:desc')" id="feifei">
		<php>$playurl_end = ff_url_play_end($feifei['vod_url']);</php>
    <a class="btn btn-default btn-sm" href="{:ff_url_play($feifei['list_id'],$feifei['list_dir'],$feifei['vod_id'],$feifei['vod_ename'],ff_array($playurl_end,0),ff_array($playurl_end,1))}">{$feifei.vod_name|msubstr=0,15,'utf-8',true}</a>
    </volist></dd>
  </dl>
</div>
<div class="clearfix mb-2"></div>
<div class="container ff-bg">
<include file="BlockTheme:footer" />
</div>
</body>
</html>