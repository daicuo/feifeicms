<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/vod_yanyuan" />
</head>
<body class="vod-detail-tabs vod-detail-yugao">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
  <h2 class="text-ellipsis">
  <span class="glyphicon glyphicon-film text-green"></span>
	<a href="{:ff_url_vod_show($list_id,$list_dir,1)}">{$list_name}</a>
  <a href="{:ff_url('vod/yanyuan',array('id'=>$vod_id),true)}">{$vod_name}</a> 演员表
	<label class="pull-right hidden-xs hidden-sm"><include file="./Tpl/base/bootstrap3/inc_share" /></label>
  </h2>
</div>
<include file="BlockTheme:vod_inc_detail_tabs" />
<php>
//关联查询
$item_role = ff_mysql_vod_person(array(
	'status'=>'1',
	'ids'=>$vod_id,
	'order'=>'role_id',
	'sort'=>'asc',
	'cache_name'=>'default',	
	'cache_time'=>'default',	
));
//未录入角色
if(!$item_role && $vod_actor){
	$item_star = ff_mysql_person(array(
		'status'=>'1',
		'names'=>ff_xml_vodactor($vod_actor),
		'order'=>'person_up',
		'sort'=>'desc',
		'cache_name'=>'default',	
		'cache_time'=>'default',	
	));
}
</php>
<volist name="item_role" id="feifei">
<div class="media media-person">
  <div class="media-left">
    <a href="{:ff_url('role/read',array('id'=>$feifei['role_id']),true)}">
      <img class="media-object img-thumbnail ff-img" data-original="{$feifei.role_pic|ff_url_img}" alt="{$role_name}海报">
    </a>
  </div>
	<div class="media-body">
    <h4 class="media-heading">
		<a href="{:ff_url('role/read',array('id'=>$feifei['role_id']),true)}">{$feifei.role_name}</a>
		<small class="glyphicon glyphicon-heart text-green"></small>
		<small class="text-gray">{$feifei.role_up}</small>
		</h4>
		<h5 class="text-gray"><a href="{:ff_url('star/read',array('id'=>$feifei['star_id']),true)}">{$feifei.star_name}</a> 饰</h5>
    <p class="mb-0">{$feifei.role_content|strip_tags|msubstr=0,210,true}</p>
  </div>
</div>
</volist>
<!-- -->
<volist name="item_star" id="feifei">
<div class="media media-person">
  <div class="media-left">
    <a href="{:ff_url('star/read',array('id'=>$feifei['person_id']),true)}">
      <img class="media-object img-thumbnail ff-img" data-original="{$feifei.person_pic|ff_url_img}" alt="{$person_name}海报">
    </a>
  </div>
	<div class="media-body">
    <h4 class="media-heading">
		<a href="{:ff_url('star/read',array('id'=>$feifei['person_id']),true)}">{$feifei.person_name}</a>
		<small class="glyphicon glyphicon-heart text-green"></small>
		<small class="text-gray">{$feifei.person_up}</small>
		</h4>
    <p class="mb-0">{$feifei.person_content|strip_tags|msubstr=0,260,true}</p>
  </div>
</div>
</volist>
<!-- -->
<empty name="item_role"><empty name="item_star">
<p class="pt-2">请谅解，暂未添加{$vod_name}的演员介绍。</p>
</empty></empty>
</div><!--container end -->
<div class="clearfix mb-2"></div>
<div class="container ff-bg">
  <include file="BlockTheme:footer" />
</div>
</body>
</html>