<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="User:header" />
<title>{$user_name|htmlspecialchars|nb}的个人主页_{$site_name}</title>
<meta name="keywords" content="{$user_name|htmlspecialchars|nb}喜欢的电影">
<meta name="description" content="欢迎来到{$user_name|htmlspecialchars|nb}的个人主页，在这里与您一起分享{$user_name|htmlspecialchars|nb}喜欢的影片。">
</head>
<body class="user-center user-index">
<div class="container ff-bg">
<div class="row">
  <div class="col-xs-12 ff-col">
    <h2 class="text-center">
      <a href="{:ff_url('user/index',array('id'=>$user_id),true)}">
        <img class="img-circle face" src="{$user_face|ff_url_img|default=$root.'Public/images/face/default.png'} " align="用户中心">
      </a>
    </h2>
    <h4 class="text-center user-name">
      {$user_name|htmlspecialchars|nb}的个人主页
    </h4>
    <h6 class="text-center user-link">
      <a href="{:ff_url('user/index',array('id'=>$user_id))}">
        {$site_url}{:ff_url('user/index',array('id'=>$user_id))}
      </a>
    </h6>
  </div>
  <div class="clear"></div>
  <div class="col-xs-12 ff-col">
    <div class="page-header">
      <h4><span class="glyphicon glyphicon-menu-right text-green"></span> {$user_name|htmlspecialchars|nb}喜欢的影片</h4>
    </div>
    <php>
$item_vod = ff_mysql_record('sid:1;uid:'.$user_id.';type:2;group:record_did;limit:24;cache_name:default;cache_time:default;order:record_id;sort:desc');
</php>
    <include file="User:inc_item_record" />
  </div>
  <div class="clear"></div>
  <div class="col-xs-12 ff-col">
    <div class="page-header">
      <h4><span class="glyphicon glyphicon-menu-right text-green"></span> {$user_name|htmlspecialchars|nb}想看的影片</h4>
    </div>
    <php>
$item_vod = ff_mysql_record('sid:1;uid:'.$user_id.';type:3;group:record_did;limit:24;cache_name:default;cache_time:default;order:record_id;sort:desc');
</php>
    <include file="User:inc_item_record" />
  </div>
  <div class="clear"></div>
  <div class="col-xs-12 ff-col">
    <div class="page-header">
      <h4><span class="glyphicon glyphicon-menu-right text-green"></span> {$user_name|htmlspecialchars|nb}在看的影片</h4>
    </div>
    <php>
$item_vod = ff_mysql_record('sid:1;uid:'.$user_id.';type:4;group:record_did;limit:24;cache_name:default;cache_time:default;order:record_id;sort:desc');
</php>
    <include file="User:inc_item_record" />
  </div>  
  <div class="clear"></div>
  <div class="col-xs-12 ff-col">
    <div class="page-header">
      <h4><span class="glyphicon glyphicon-menu-right text-green"></span> {$user_name|htmlspecialchars|nb}看过的影片</h4>
    </div>
    <php>
$item_vod = ff_mysql_record('sid:1;uid:'.$user_id.';type:5;group:record_did;limit:24;cache_name:default;cache_time:default;order:record_id;sort:desc');
</php>
    <include file="User:inc_item_record" />
  </div> 
</div><!--row end -->
</div>
<include file="User:footer" />
</body>
</html>