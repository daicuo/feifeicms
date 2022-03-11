<php>$item_star = ff_mysql_star('nationality:'.$select_area.';gender:'.$select_gender.';profession:'.$select_profession.';letter:'.$select_letter.';limit:24;page_is:true;page_id:list;page_p:'.$select_page.';cache_name:default;cache_time:default;order:person_'.$select_order.';sort:desc');
$page = ff_url_page('list/select',array('id'=>$list_id,'area'=>urlencode($select_area),'gender'=>urlencode($select_gender),'profession'=>urlencode($select_profession),'letter'=>$select_letter,'order'=>$select_order,'p'=>'FFLINK'),true,'list',4);
$totalpages = ff_page_count('list', 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/star_type" />
</head>
<body class="star-type">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
	<h2><span class="glyphicon glyphicon-stats text-green"></span>
	<a href="{:ff_url('list/read',array('id'=>$list_id,'p'=>1),true)}">{$list_name}</a> 共{:ff_page_count('list', 'records')}条数据
	<small class="text-green">{$select_area} {$select_gender} {$select_profession} {$select_letter|strtoupper}</small>
	<label class="pull-right hidden-xs hidden-sm"><include file="./Tpl/base/bootstrap3/inc_share" /></label>
	</h2>
</div>
<dl class="dl-horizontal">
	<dt>按性别：</dt>
	<dd class="text-nowrap ff-gallery"><a class="gallery-cell px-1 py-1" id="gender{:md5('')}" href="{:ff_url('list/select',array('id'=>$list_id,'area'=>urlencode($select_area),'gender'=>'','profession'=>urlencode($select_profession),'letter'=>$select_letter,'order'=>$select_order,'p'=>1),true)}">全部</a><volist name=":array('男','女','组合','变性')" id="feifei"><a class="gallery-cell px-1 py-1" id="gender{:md5($feifei)}" href="{:ff_url('list/select',array('id'=>$list_id,'area'=>urlencode($select_area),'gender'=>urlencode($feifei),'profession'=>urlencode($select_profession),'letter'=>$select_letter,'order'=>$select_order,'p'=>1),true)}">{$feifei}</a></volist>
	</dd>
	<notempty name="list_extend.star">
	<dt>按职业：</dt>
	<dd class="text-nowrap ff-gallery"><a class="gallery-cell px-1 py-1" id="profession{:md5('')}" href="{:ff_url('list/select',array('id'=>$list_id,'area'=>urlencode($select_area),'gender'=>urlencode($select_gender),'profession'=>'','letter'=>$select_letter,'order'=>$select_order,'p'=>1),true)}">全部</a><volist name=":explode(',',$list_extend['star'])" id="feifei"><a class="gallery-cell px-1 py-1" id="profession{:md5($feifei)}" href="{:ff_url('list/select',array('id'=>$list_id,'area'=>urlencode($select_area),'gender'=>urlencode($select_gender),'profession'=>urlencode($feifei),'letter'=>$select_letter,'order'=>$select_order,'p'=>1),true)}">{$feifei}</a></volist></dd>
	</notempty>	
	<notempty name="list_extend.area">
	<dt>按地区：</dt>
	<dd class="text-nowrap ff-gallery"><a class="gallery-cell px-1 py-1" id="area{:md5('')}" href="{:ff_url('list/select',array('id'=>$list_id,'area'=>'','gender'=>urlencode($select_gender),'profession'=>urlencode($select_profession),'letter'=>$select_letter,'order'=>$select_order,'p'=>1),true)}">全部</a><volist name=":explode(',',$list_extend['area'])" id="feifei"><a class="gallery-cell px-1 py-1" id="area{:md5($feifei)}" href="{:ff_url('list/select',array('id'=>$list_id,'area'=>urlencode($feifei),'gender'=>urlencode($select_gender),'profession'=>urlencode($select_profession),'letter'=>$select_letter,'order'=>$select_order,'p'=>1),true)}">{$feifei}</a></volist></dd>
	</notempty>
	<dt>按字母：</dt>
	<dd class="text-nowrap ff-gallery"><a class="gallery-cell px-1 py-1" id="letter{:md5('')}" href="{:ff_url('list/select',array('id'=>$list_id,'area'=>urlencode($select_area),'gender'=>urlencode($select_gender),'profession'=>urlencode($select_profession),'letter'=>'','order'=>$select_order,'p'=>1),true)}">全部</a><volist name=":array('a','b','c','d','e','f','g','h','i','j','k','m','l','n','o','p','q','r','s','t','u','v','w','x','y','z')" id="feifei"><a class="gallery-cell px-1 py-1" id="letter{:md5($feifei)}" href="{:ff_url('list/select',array('id'=>$list_id,'area'=>urlencode($select_area),'gender'=>urlencode($select_gender),'profession'=>urlencode($select_profession),'letter'=>$feifei,'order'=>$select_order,'p'=>1),true)}">{$feifei|strtoupper}</a></volist><a class="gallery-cell px-1 py-1" id="letter{:md5('0,1,2,3,4,5,6,7,8,9')}" href="{:ff_url('list/select',array('id'=>$list_id,'area'=>urlencode($select_area),'gender'=>urlencode($select_gender),'profession'=>urlencode($select_profession),'letter'=>'0,1,2,3,4,5,6,7,8,9','order'=>$select_order,'p'=>1),true)}">0-9</a>
	</dd>	
</dl>
</div>
<div class="clearfix pb-2"></div>
<div class="container ff-bg">
<div class="btn-toolbar py-2">
	<div class="btn-group">
		<a class="btn btn-default" id="orderhits" href="{:ff_url('list/select',array('id'=>$list_id,'area'=>urlencode($select_area),'gender'=>urlencode($select_gender),'profession'=>urlencode($select_profession),'letter'=>$select_letter,'order'=>'hits','p'=>1),true)}">按人气</a>
		<a class="btn btn-default" id="orderaddtime" href="{:ff_url('list/select',array('id'=>$list_id,'area'=>urlencode($select_area),'gender'=>urlencode($select_gender),'profession'=>urlencode($select_profession),'letter'=>$select_letter,'order'=>'addtime','p'=>1),true)}">按时间</a>
		<a class="btn btn-default" id="ordergold" href="{:ff_url('list/select',array('id'=>$list_id,'area'=>urlencode($select_area),'gender'=>urlencode($select_gender),'profession'=>urlencode($select_profession),'letter'=>$select_letter,'order'=>'gold','p'=>1),true)}">按评分</a>
	</div>
</div>
<ul class="list-unstyled ff-item">
	<volist name="item_star" id="feifei">
	<include file="BlockTheme:item_img_star" />
	</volist>
</ul>
<!-- -->
<script>
$("#area{$select_area|md5}").addClass("gallery-active");
$("#gender{$select_gender|md5}").addClass("gallery-active");
$("#profession{$select_profession|md5}").addClass("gallery-active");
$("#letter{$select_letter|md5}").addClass("gallery-active");
$("#order{$select_order}").addClass("gallery-active");
</script>
<gt name="totalpages" value="1">
<div class="clearfix pb-2"></div>
<div class="text-center">
	<ul class="pagination pagination-lg hidden-xs">
		{$page}
	</ul>
	<ul class="pager visible-xs">
		<gt name="select_page" value="1">
			<li><a id="ff-prev" href="{:ff_url('list/select',array('id'=>$list_id,'area'=>urlencode($select_area),'gender'=>urlencode($select_gender),'profession'=>urlencode($select_profession),'letter'=>$select_letter,'order'=>$select_order,'p'=>$select_page-1),true)}">上一页</a></li>
		</gt>
		<lt name="select_page" value="$totalpages">
			<li><a id="ff-next" href="{:ff_url('list/select',array('id'=>$list_id,'area'=>urlencode($select_area),'gender'=>urlencode($select_gender),'profession'=>urlencode($select_profession),'letter'=>$select_letter,'order'=>$select_order,'p'=>$select_page+1),true)}">下一页</a></li>
		</lt>
	</ul>
</div>
</gt>
</div><!--container end -->
<div class="clearfix pb-2"></div>
<div class="container ff-bg">
  <include file="BlockTheme:footer" />
</div>
</body>
</html>