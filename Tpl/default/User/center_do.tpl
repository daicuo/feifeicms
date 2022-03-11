<php>
$item_vod = ff_mysql_record('sid:1;uid:'.$user_id.';type:4;group:record_did;limit:30;page_is:true;page_id:record;page_p:'.$user_page.';order:record_id;sort:desc');
$page = ff_url_page('user/center',array('action'=>'do','p'=>'FFLINK'),true,'record',4);
$totalpages = ff_page_count('record', 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="User:header" />
<title>我正在看的_{$site_name}</title>
<meta name="keywords" content="{$site_name}用户中心">
<meta name="description" content="欢迎回到{$site_name}用户中心">
</head>
<body class="user-center">
<include file="User:center_nav" />
<div class="container ff-bg">
<div class="row">
  <div class="col-xs-12 ff-col">
    <div class="page-header">
      <h4><span class="glyphicon glyphicon-menu-right text-green"></span> 我正在看的影片</h4>
    </div>
    <include file="User:inc_item_record" />
  </div>
  <!-- -->
  <gt name="totalpages" value="1">
    <div class="clearfix"></div>
    <div class="col-xs-12 ff-col text-center">
      <ul class="pagination pagination-lg hidden-xs">
        {$page}
      </ul>
      <ul class="pager visible-xs">
      	<gt name="user_page" value="1">
          <li><a id="ff-prev" href="{:ff_url('user/center', array('action'=>'do','p'=>($user_page-1)), true)}">上一页</a></li>
        </gt>
        <lt name="user_page" value="$totalpages">
          <li><a id="ff-next" href="{:ff_url('user/center', array('action'=>'do','p'=>($user_page+1)), true)}">下一页</a></li>
        </lt>
       </ul> 
    </div>
  </gt>
</div><!--row end -->
</div>
<include file="User:footer" />
</body>
</html>