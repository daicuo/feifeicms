<php>$params = array();
$params['limit'] = '24';
$params['order'] = 'person_addtime';
$params['sort'] = 'desc';
$params['page_p'] = $search_page;
$params['page_is'] = 'true';
$params['page_id'] = 'search';
$params['cache_name'] = 'default';
$params['cache_time'] = 'default';
if($search_wd){
	$params['wd'] = $search_wd;
	$jump = array('wd'=>urlencode($search_wd),'p'=>'FFLINK');
}else if($search_name){
	$params['name'] = $search_name;
	$jump = array('name'=>urlencode($search_name),'p'=>'FFLINK');
}
$item = ff_mysql_role($params);
$page_info = ff_url_page('role/search', $jump, true, $params['page_id'], 4);
$page_total = ff_page_count($params['page_id'], 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/role_search" />
</head>
<body class="role-search">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
  <h2 class="text-ellipsis">
  <span class="glyphicon glyphicon-search text-green"></span>
	搜索 》{$search_name}{$search_wd}
	<small>共有<span class="text-green">{:ff_page_count('search', 'records')}</span>个 第<span class="text-green">{$search_page}</span>页</small>
  </h2>
</div>
<!-- -->
<ul class="list-unstyled vod-item-img ff-img-140">
	<volist name="item" id="feifei">
	<include file="BlockTheme:item_img_role" />
	</volist>
</ul>
<!-- -->
<gt name="page_total" value="1">
  <div class="text-center">
    <ul class="pager">
      <gt name="search_page" value="1">
        <li><a id="ff-prev" href="{:ff_url('role/search', array_merge($jump,array('p'=>$search_page-1)), true)}">上一页</a></li>
      </gt>
      <lt name="search_page" value="$page_total">
        <li><a id="ff-next" href="{:ff_url('role/search', array_merge($jump,array('p'=>$search_page+1)), true)}">下一页</a></li>
      </lt>
     </ul>
  </div>
</gt>
</div><!--container end -->
<div class="clearfix mb-1"></div>
<div class="container ff-bg">
  <include file="BlockTheme:footer" />
</div>
</body>
</html>