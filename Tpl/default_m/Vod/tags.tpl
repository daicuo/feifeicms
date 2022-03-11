<php>
$item_vod = ff_mysql_vod('limit:24;tag_name:'.$tag_name.';tag_list:vod_tag;page_is:true;page_id:vodtags;page_p:'.$tag_page.';cache_name:default;cache_time:default;order:vod_addtime;sort:desc');
$page = ff_url_page('vod/tags',array('name'=>urlencode($tag_name),'p'=>'FFLINK'), true, 'vodtags', 4);
$totalpages = ff_page_count('vodtags', 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/vod_tags" />
</head>
<body class="vod-tags">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
  <h2>
    <span class="text-green">话题：{$tag_type}{$tag_tag}{$tag_name}</span>
    <small>共有<span class="text-green">{:ff_page_count('vodtags', 'records')}</span>个影片 第<span class="text-green">{$tag_page}</span>页</small>
  </h2>
</div>
<ul class="list-unstyled vod-item-img ff-img-215">
  <volist name="item_vod" id="feifei">
  <include file="BlockTheme:item_img_vod" />
  </volist>
</ul>
<gt name="totalpages" value="1">
<div class="clearfix"></div>
<div class="text-center">
  <ul class="pager">
    <gt name="tag_page" value="1">
      <li><a id="ff-prev" href="{:ff_url('vod/tags', array('name'=>urlencode($tag_name),'p'=>$tag_page-1), true)}">上一页</a></li>
    </gt>
    <lt name="tag_page" value="$totalpages">
      <li><a id="ff-next" href="{:ff_url('vod/tags', array('name'=>urlencode($tag_name),'p'=>$tag_page+1), true)}">下一页</a></li>
    </lt>
  </ul>
</div>
</gt>
</div><!--container end -->
<include file="BlockTheme:footer" />
</body>
</html>