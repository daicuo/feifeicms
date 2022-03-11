<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/role_detail" />
</head>
<body class="role-detail">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<h2 class="text-center">
	{$person_name}
</h2>
<div class="page-header">
  <h5 class="text-muted text-center">
    来源：<a href="{:ff_url('vod/yanyuan',array('id'=>$person_object_id),true)}">{$person_object_name}</a>
  </h5>
</div>
<div class="row ff-row">
	<div class="col-xs-12 ff-col">
		<p class="text-center">
			<a href="{$person_pic|ff_url_img}" target="_blank">
			<img class="img-responsive img-thumbnail ff-img" data-original="{$person_pic|ff_url_img}" alt="{$person_name}剧照">
			</a>
		</p>
		<p class="text-center">
			扮演者（<a class="text-green" href="{:ff_url('star/read',array('id'=>$person_father_id),true)}">{$person_father_name}</a>）
		</p>
		<div class="content ff-content mb-2">
			{$person_content}@{$person_addtime|date='Y-m-d',###}
		</div>  
		<p class="text-center">
			<a class="btn btn-default btn-lg ff-updown-set" href="javascript:;" data-id="{$person_id}" data-module="person" data-type="up" data-toggle="tooltip" data-placement="top" title="支持">
				<span class="glyphicon glyphicon-thumbs-up"></span> 演技点赞 (<span class="ff-updown-val">{$person_up}</span>)
			</a>  
		</p>	
	</div>
</div><!--row end -->
</div><!--container end -->
<div class="clearfix mb-1"></div>
<!-- -->
<gt name="person_object_id" value="0">
<div class="container ff-bg">
	<div class="page-header">
		<h2>
		<span class="glyphicon glyphicon-signal text-green"></span> 
		<a href="{:ff_url('vod/yanyuan',array('id'=>$person_object_id),true)}">{$person_object_name}演员表</a>
		</h2>
	</div>
	<php>$item_hot = ff_mysql_role('object_id:'.$person_object_id.';ids_not:'.$person_id.';limit:6;cache_name:default;cache_time:default;order:person_hits;sort:desc');</php>
	<ul class="list-unstyled vod-item-img ff-img-140">
		<volist name="item_hot" id="feifei">
		<include file="BlockTheme:item_img_role" />
		</volist>
	</ul>
</div><!--container end -->
<div class="clearfix mb-1"></div>	
</gt>
<!-- -->
<div class="container ff-bg">
<include file="./Tpl/base/bootstrap3/forum_ajax_role" />
</div>
<div class="clearfix mb-1"></div>
{$person_hits_insert}
<include file="BlockTheme:footer" />
</body>
</html>