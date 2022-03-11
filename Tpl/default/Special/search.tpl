<php>$params = array();
$params['status'] = 1;
$params['limit'] = 24;
$params['order'] = 'special_addtime';
$params['sort'] = 'desc';
$params['page_p'] = $search_page;
$params['page_is'] = true;
$params['page_id'] = 'search';
$params['cache_name'] = 'default';
$params['cache_time'] = 'default';
$params['name'] = $search_wd;
$jump = array('wd'=>urlencode($search_wd),'p'=>'FFLINK');
$item_special = ff_mysql_special($params);
$page_info = ff_url_page('special/search', $jump, true, $params['page_id'], 4);
$page_total = ff_page_count($params['page_id'], 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/special_search" />
</head>
<body class="special-search">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
  <h2 class="text-ellipsis">
  <span class="glyphicon glyphicon-th text-green"></span>
	搜索 》{$search_name}{$search_wd}
	<small>共有<span class="text-green">{:ff_page_count('search', 'records')}</span>个 第<span class="text-green">{$search_page}</span>页</small>
	<label class="pull-right hidden-xs hidden-sm"><include file="./Tpl/base/bootstrap3/inc_share" /></label>
  </h2>
</div>
<!-- -->
<ul class="list-unstyled vod-item-img ff-img-90">
<volist name="item_special" id="feifei">
  <include file="BlockTheme:item_img_special" />
</volist>
</ul>
<!-- -->
<gt name="page_total" value="1">
  <div class="clearfix"></div>
  <div class="text-center">
    <ul class="pagination pagination-lg hidden-xs">
      {$page_info}
    </ul>
    <ul class="pager visible-xs">
      <gt name="search_page" value="1">
        <li><a id="ff-prev" href="{:ff_url('special/search', array_merge($jump,array('p'=>$search_page-1)), true)}">上一页</a></li>
      </gt>
      <lt name="search_page" value="$page_total">
        <li><a id="ff-next" href="{:ff_url('special/search', array_merge($jump,array('p'=>$search_page+1)), true)}">下一页</a></li>
      </lt>
     </ul>
  </div>
</gt>
</div><!--container end -->
<div class="clearfix mb-2"></div>
<div class="container ff-bg">
  <include file="BlockTheme:footer" />
</div>
</body>
</html>