<php>$item_list = ff_mysql_forum('pid:'.$forum_id.';limit:10;page_is:true;page_id:forum;page_p:'.$forum_page.';cache_name:default;cache_time:default;order:forum_addtime;sort:desc');
$page_array = $_GET['ff_page_forum'];
$page_info = ff_url_page('forum/read',array('id'=>$forum_id,'p'=>'FFLINK'), true, 'forum', 4);
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/forum_detail_guestbook" />
</head>
<body class="forum-detail forum-detail-special">
<include file="BlockTheme:header" />
<div class="container ff-bg ff-forum" data-type="{$Think.config.forum_type}">
<div class="page-header">
  <h2 class="text-ellipsis">
    <span class="glyphicon glyphicon-comment text-green"></span> 评论详情
		<label class="pull-right hidden-xs hidden-sm"><include file="./Tpl/base/bootstrap3/inc_share" /></label>
  </h2>
</div>
<!-- -->
<p class="content">
  {$forum_content|htmlspecialchars|nb}
</p>
<p class="text-right design">
	<small class="text-muted">
  <a class="text-green" href="{:ff_url('user/index',array('id'=>$user_id),true)}" target="_blank">{$user_name|htmlspecialchars|nb}</a>
  {$forum_addtime|date='Y-m-d',###}
  </small>
</p>
<p class="text-center">
  <a class="btn btn-default ff-updown-set" href="javascript:;" data-id="{$forum_id}" data-module="forum" data-type="up" data-toggle="tooltip" data-placement="top" title="有用">
    <span class="glyphicon glyphicon-thumbs-up"></span> 赞（<span class="ff-updown-val">{$forum_up}</span>）
  </a>
  <a class="btn btn-default ff-updown-set" href="javascript:;" data-id="{$forum_id}" data-module="forum" data-type="down" data-toggle="tooltip" data-placement="top" title="反对">
    <span class="glyphicon glyphicon-thumbs-up"></span> 踩（<span class="ff-updown-val">{$forum_down}</span>）
  </a>
</p>
<!-- -->
<div class="page-header">
  <h2><span class="glyphicon glyphicon-comment text-green"></span> 发表看法</h2>
</div>
<!--发表评论后刷新网页 -->
<div class="ff-forum-reload">
	<assign name="forum_pid" value="$forum_id" />
	<include file="./Tpl/base/bootstrap3/forum_post" />
</div>
<div class="ff-forum-item">
	<include file="./Tpl/base/bootstrap3/forum_item" />
</div>
<!-- -->
<gt name="page_array.totalpages" value="1">
  <div class="clear"></div>
  <div class="text-center">
    <ul class="pagination pagination-lg hidden-xs hidden-sm">
      {$page_info}
    </ul>
    <ul class="pager visible-xs visible-sm">
      <gt name="forum_page" value="1">
        <li><a id="ff-prev" href="{:ff_url('forum/read', array('id'=>$forum_id,'p'=>($forum_page-1)), true)}">上一页</a></li>
      </gt>
      <lt name="forum_page" value="$page_array['totalpages']">
        <li><a id="ff-next" href="{:ff_url('forum/read', array('id'=>$forum_id,'p'=>($forum_page+1)), true)}">下一页</a></li>
      </lt>
    </ul> 
  </div>
</gt>
</div><!--container end -->
<div class="clearfix mb-2"></div>
<div class="container ff-bg">
  <include file="BlockTheme:footer" />
</div>
</body>
</html>