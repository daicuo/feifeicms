<php>$item_special = ff_mysql_special('cid:'.ff_list_ids($list_id).';limit:30;page_is:true;page_id:special;page_p:'.$list_page.';cache_name:default;cache_time:default;order:special_stars;sort:desc');
$page = ff_url_page('list/'.$action,array('id'=>$list_id,'p'=>'FFLINK'),true,'special',4);
$totalpages = ff_page_count('special', 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/special_list" />
</head>
<body class="special-list">
<include file="BlockTheme:header" />
<notempty name="list_extend.type">
<div class="container ff-bg">
	<ul class="list-unstyled ff-select">
	<li class="col-xs-6 col-md-1 active">
	<a class="btn btn-default btn-block" href="{:ff_url('list/read',array('id'=>$list_id,'p'=>1),true)}">{$list_name}</a>
	</li>
	<volist name=":explode(',',$list_extend['type'])" id="feifei">
	<li class="col-xs-6 col-md-1"><a class="btn btn-default btn-block" href="{:ff_url('list/select',array('id'=>$list_id,'type'=>urlencode($feifei),'p'=>1),true)}">{$feifei|msubstr=0,6,true}</a></li>
	</volist>
	</ul>
</div>
<div class="clearfix mb-2"></div>
</notempty>
<!-- -->
<div class="container ff-bg">
<ul class="nav nav-pills">
<div class="page-header">
  <h2 class="text-ellipsis">
  	<span class="glyphicon glyphicon-calendar text-green"></span>
    共有<span class="text-green">{:ff_page_count('special', 'records')}</span>个专题
		<label class="pull-right hidden-xs hidden-sm"><include file="./Tpl/base/bootstrap3/inc_share" /></label>
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
    <ul class="pagination pagination-lg hidden-xs">
      {$page}
    </ul>
  </div>
  <ul class="pager visible-xs">
    <gt name="list_page" value="1">
      <li><a id="ff-prev" href="{:ff_url_show('list/'.$action,array('id'=>$list_id,'list_dir'=>$list_dir,'p'=>$list_page-1) )}">上一页</a></li>
    </gt>
    <lt name="list_page" value="$totalpages">
      <li><a id="ff-next" href="{:ff_url_show('list/'.$action,array('id'=>$list_id,'list_dir'=>$list_dir,'p'=>$list_page+1) )}">下一页</a></li>
    </lt>
   </ul> 
</gt>
</div><!--container end -->
<div class="clearfix mb-2"></div>
<div class="container ff-bg">
  <include file="BlockTheme:footer" />
</div>
</body>
</html>