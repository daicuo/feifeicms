<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/vod_yanyuan" />
</head>
<body class="vod-detail-yanyuan">
<include file="BlockTheme:header" />
<include file="BlockTheme:vod_inc_info" />
<include file="./Tpl/base/bootstrap3/vod_playurl" />
<php>
//关联查询角色演员
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
<div class="container ff-bg pb-2">
<div class="page-header">
  <h2><span class="glyphicon glyphicon-th text-green"></span> 演员表</h2>
</div>
<volist name="item_role" id="feifei">
<div class="media media-yanyuan">
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
		<h5 class="mb-1">
			<a class="text-green" href="{:ff_url('star/read',array('id'=>$feifei['star_id']),true)}">{$feifei.star_name}</a> 饰
		</h5>
    <p class="text-dark mb-0">{$feifei.role_content|strip_tags|msubstr=0,100,true}</p>
  </div>
</div>
</volist>
<!-- -->
<volist name="item_star" id="feifei">
<div class="media media-yanyuan">
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
    <p class="text-dark mb-0">
			{$feifei.person_content|strip_tags|msubstr=0,120,true}
		</p>
  </div>
</div>
</volist>
<!-- -->
<empty name="item_role">
<empty name="item_star">
<p>请谅解，暂未添加{$vod_name}的演员资料介绍。</p>
</empty>
</empty>
<!-- -->
</div>
<div class="clearfix mb-1"></div>
<!-- -->
<include file="BlockTheme:vod_inc_hot" />
<include file="BlockTheme:footer" />
</body>
</html>