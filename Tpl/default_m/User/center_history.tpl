<php>$item_vod = ff_mysql_record('sid:1;uid:'.$user_id.';type:1;group:record_did;limit:30;page_is:true;page_id:record;page_p:'.$user_page.';order:record_id;sort:desc');
$page = ff_url_page('user/center',array('action'=>'history','p'=>'FFLINK'),true,'record',4);
$totalpages = ff_page_count('record', 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="User:header" />
<title>观看记录_{$site_name}</title>
<meta name="keywords" content="{$site_name}用户中心">
<meta name="description" content="欢迎回到{$site_name}用户中心">
</head>
<body class="user-center">
<include file="User:center_nav" />
<div class="container ff-bg">
<div class="page-header">
  <h2><span class="glyphicon glyphicon-menu-right text-green"></span> 我的观看记录</h2>
</div>
<include file="User:inc_item_record" />
<gt name="totalpages" value="1">
  <div class="clearfix"></div>
  <div class="text-center">
    <ul class="pager">
      <gt name="user_page" value="1">
        <li><a id="ff-prev" href="{:ff_url('user/center', array('action'=>'history','p'=>($user_page-1)), true)}">上一页</a></li>
      </gt>
      <lt name="user_page" value="$totalpages">
        <li><a id="ff-next" href="{:ff_url('user/center', array('action'=>'history','p'=>($user_page+1)), true)}">下一页</a></li>
      </lt>
     </ul> 
  </div>
</gt>
</div>
<include file="User:footer" />
</body>
</html>