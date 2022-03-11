<php>
$item_forum = ff_mysql_forum('uid:'.$user_id.';limit:20;page_is:true;page_id:forum;page_p:'.$user_page.';order:forum_id;sort:desc');
$page = ff_url_page('user/center',array('action'=>'forum','p'=>'FFLINK'),true,'forum',4);
$totalpages = ff_page_count('forum', 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="User:header" />
<title>我的评论_{$site_name}</title>
<meta name="keywords" content="{$site_name}用户中心">
<meta name="description" content="欢迎回到{$site_name}用户中心">
</head>
<body class="user-center">
<include file="User:center_nav" />
<div class="container ff-bg">
<div class="row">
  <div class="col-xs-12 ff-col">
    <div class="page-header">
      <h4><span class="glyphicon glyphicon-menu-right text-green"></span> 我的评论与留言</h4>
    </div>
    <table class="table table-striped table-bordered table-responsive ff-forum" data-type="{$Think.config.forum_type}">
    <tbody>
     <volist name="item_forum" id="feifei">
     <tr>
      	<td>{$feifei.forum_addtime|date='Y-m-d H:i:s',###}
        <a href="{:ff_url('forum/read', array('id'=>$feifei['forum_id']), true)}" target="_blank">详情</a>
				<eq name="feifei.user_id" value="$site_user_id"><a class="text-muted forum-delete" href="javascript:;" data-id="{$feifei.forum_id}" data-toggle="tooltip" data-placement="top" title="删除">删除</a></eq>
				</td>
      </tr>
      <tr>
      	<td class="forum-detail">{$feifei.forum_content|htmlspecialchars|nb|msubstr=0,300}</td>
      </tr>
      </volist>
    </tbody>
  </table>
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
          <li><a id="ff-prev" href="{:ff_url('user/center', array('action'=>'forum','p'=>($user_page-1)), true)}">上一页</a></li>
        </gt>
        <lt name="user_page" value="$totalpages">
          <li><a id="ff-next" href="{:ff_url('user/center', array('action'=>'forum','p'=>($user_page+1)), true)}">下一页</a></li>
        </lt>
       </ul> 
    </div>
  </gt>
</div><!--row end -->
</div>
<include file="User:footer" />
</body>
</html>