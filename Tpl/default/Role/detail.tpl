<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/role_detail" />
</head>
<body class="role-detail">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<h2 class="text-center">
	{$person_name}
</h2>
<div class="page-header">
  <h5 class="text-muted text-center visible-md visible-lg">
		扮演者：<a href="{:ff_url('star/read',array('id'=>$person_father_id),true)}">{$person_father_name}</a>
    来源：<a href="{:ff_url('vod/yanyuan',array('id'=>$person_object_id),true)}">{$person_object_name}</a>
    更新：{$person_addtime|date='Y-m-d',###}
		人气：{$person_hits}
  </h5>
	<div class="row hidden-xs hidden-sm">
	<div class="col-md-4 col-md-offset-5">
		<include file="./Tpl/base/bootstrap3/inc_share" />
	</div> 
	</div>
</div>
<div class="row ff-row">
<div class="col-xs-12 ff-col">
	<p class="text-center">
		<a href="{$person_pic|ff_url_img}" target="_blank">
		<img class="img-responsive img-thumbnail ff-img" data-original="{$person_pic|ff_url_img}" alt="{$person_name}剧照">
		</a>
	</p>
  <div class="content">
    {$person_content}
  </div>  
  <p class="tags text-center">
    <a class="btn btn-default btn-lg ff-updown-set" href="javascript:;" data-id="{$person_id}" data-module="person" data-type="up" data-toggle="tooltip" data-placement="top" title="支持">
      <span class="glyphicon glyphicon-thumbs-up"></span> 演技点赞 (<span class="ff-updown-val">{$person_up}</span>)
    </a>  
  </p>	
</div>
</div><!--row end -->
<!-- -->
<gt name="person_object_id" value="0">
<div class="page-header">
  <h2><span class="glyphicon glyphicon-signal text-green"></span> {$person_object_name}其它角色</h2>
</div>
<php>$item_hot = ff_mysql_role('object_id:'.$person_object_id.';ids_not:'.$person_id.';limit:6;cache_name:default;cache_time:default;order:person_hits;sort:desc');</php>
<ul class="list-unstyled ff-item">
	<volist name="item_hot" id="feifei">
	<include file="BlockTheme:item_img_role" />
	</volist>
</ul>
</gt>
<!-- -->
<include file="./Tpl/base/bootstrap3/forum_ajax_role" />
{$person_hits_insert}
</div><!--container end -->
<div class="clearfix mb-2"></div>
<div class="container ff-bg">
  <include file="BlockTheme:footer" />
</div>
</body>
</html>