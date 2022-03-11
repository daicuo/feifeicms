<php>$item_special = ff_mysql_special('cid:'.ff_list_ids($list_id).';limit:20;page_is:true;page_id:special;page_p:'.$list_page.';cache_name:default;cache_time:default;order:special_stars;sort:desc');
$page = ff_url_page('list/'.$action,array('id'=>$list_id,'p'=>'FFLINK'),true,'special',4);
$totalpages = ff_page_count('special', 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/special_list" />
</head>
<body class="special-list">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
  <h2>
  	<span class="glyphicon glyphicon-calendar text-green"></span>
    <span class="text-green"><a href="{:ff_url('list/read',array('id'=>$list_id),true)}">{$list_name}</a></span>
    <small>共有<span class="text-green">{:ff_page_count('special', 'records')}</span>个 第<span class="text-green">{$list_page}</span>页</small>
		<notempty name="list_extend.type">
		<div class="btn-group pull-right">
			<button type="button" class="btn btn-success dropdown-toggle btn-xs" data-toggle="dropdown">筛选
			<span class="caret"></span>
			</button>
			<ul class="dropdown-menu" role="menu">
			<volist name=":explode(',',$list_extend['type'])" id="feifei" offset="0" length="12">
			<li><a href="{:ff_url('list/select',array('id'=>$list_id,'type'=>urlencode($feifei),'p'=>1),true)}">{$feifei|msubstr=0,6,true}</a></li>
			</volist>
			</ul>
		</div>
		</notempty>
  </h2>
</div>
<ul class="list-unstyled vod-item-img ff-img-90">
<volist name="item_special" id="feifei">
  <include file="BlockTheme:item_img_special" />
</volist>
</ul>
<gt name="totalpages" value="1">
<div class="clearfix"></div>
<div class="text-center">
	<ul class="pager">
		<gt name="list_page" value="1">
			<li><a id="ff-prev" href="{:ff_url_show('list/'.$action,array('id'=>$list_id,'list_dir'=>$list_dir,'p'=>$list_page-1) )}">上一页</a></li>
		</gt>
		<lt name="list_page" value="$totalpages">
			<li><a id="ff-next" href="{:ff_url_show('list/'.$action,array('id'=>$list_id,'list_dir'=>$list_dir,'p'=>$list_page+1) )}">下一页</a></li>
		</lt>
	 </ul> 
</div>
</gt>
</div>
<!--container end -->
<div class="clearfix mb-1"></div>
<include file="BlockTheme:footer" />
</body>
</html>