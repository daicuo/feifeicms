<php>$item_star = ff_mysql_star('cid:'.ff_list_ids($list_id).';limit:24;page_is:true;page_id:list;page_p:'.$list_page.';cache_name:default;cache_time:default;order:person_addtime;sort:asc');
$page_total = ff_page_count('list', 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/star_list" />
</head>
<body class="star-list">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
  <h2 class="text-nowrap">
  <span class="glyphicon glyphicon-list-alt text-green"></span>
	<a href="{:ff_url('list/read',array('id'=>$list_id,'p'=>1),true)}">{$list_name}</a>
  <small>共有<span class="text-green">{:ff_page_count('list', 'records')}</span>个 第<span class="text-green">{$list_page}</span>页</small>
	<notempty name="list_extend.type">
	<div class="btn-group pull-right">
		<button type="button" class="btn btn-success dropdown-toggle btn-xs" data-toggle="dropdown">筛选
		<span class="caret"></span>
		</button>
		<ul class="dropdown-menu" role="menu">
		<volist name=":explode(',',$list_extend['area'])" id="feifei" offset="0" length="12">
		<li><a href="{:ff_url('list/select',array('id'=>$list_id,'area'=>urlencode($feifei),'gender'=>'','profession'=>'','letter'=>'','order'=>'hits','p'=>1),true)}">{$feifei}</a></li>
		</volist>
		</ul>
	</div>
	</notempty>
  </h2>
</div>
<!-- -->
<ul class="list-unstyled vod-item-img ff-img-140">
	<volist name="item_star" id="feifei">
	<include file="BlockTheme:item_img_star" />
	</volist>
</ul>
<!-- -->
<gt name="page_total" value="1">
<div class="clearfix mb-1"></div>
<div class="text-center">
  <ul class="pager">
    <gt name="list_page" value="1">
      <li><a id="ff-prev" href="{:ff_url_show('list/'.$action,array('id'=>$list_id,'list_dir'=>$list_dir,'p'=>$list_page-1))}">上一页</a></li>
    </gt>
    <lt name="list_page" value="$page_total">
      <li><a id="ff-next" href="{:ff_url_show('list/'.$action,array('id'=>$list_id,'list_dir'=>$list_dir,'p'=>$list_page+1))}">下一页</a></li>
    </lt>
   </ul>
</div>
</gt>
</div><!--container end -->
<div class="clearfix mb-1"></div>
<include file="BlockTheme:footer" />
</body>
</html>