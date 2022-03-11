<php>$item_list = ff_mysql_forum('cid_not:0;pid:0;limit:30;page_is:true;page_id:forum;page_p:'.$list_page.';cache_name:default;cache_time:default;order:forum_addtime;sort:desc');
$page_total = ff_page_count('forum', 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/forum_list" />
</head>
<body class="forum-list">
<include file="BlockTheme:header" />
<div class="container ff-bg ff-forum">
<div class="page-header">
  <h2 class="text-nowrap">
		<span class="glyphicon glyphicon-comment text-green"></span>
		共有<span class="text-green">{:ff_page_count('forum', 'records')}</span>个评论
		第<span class="text-green">{$list_page}</span>页
		<div class="btn-group pull-right">
			<button type="button" class="btn btn-success dropdown-toggle btn-xs" data-toggle="dropdown">筛选
			<span class="caret"></span>
			</button>
			<ul class="dropdown-menu" role="menu">
			<li><a href="{:ff_url('list/select',array('id'=>$list_id,'sid'=>1,'p'=>1),true)}">视频评论</a></li>
			<li><a href="{:ff_url('list/select',array('id'=>$list_id,'sid'=>2,'p'=>1),true)}">文章评论</a></li>
			<li><a href="{:ff_url('list/select',array('id'=>$list_id,'sid'=>3,'p'=>1),true)}">专题评论</a></li>
			<li><a href="{:ff_url('list/select',array('id'=>$list_id,'sid'=>5,'p'=>1),true)}">留言评论</a></li>
			<li><a href="{:ff_url('list/select',array('id'=>$list_id,'sid'=>8,'p'=>1),true)}">明星评论</a></li>
			<li><a href="{:ff_url('list/select',array('id'=>$list_id,'sid'=>9,'p'=>1),true)}">角色评论</a></li>			
			</ul>
		</div>
  </h2>
</div>
<div class="ff-forum-item">
<volist name="item_list" id="feifei">
<div class="media">
  <a class="media-left" href="{:ff_url('user/index',array('id'=>$feifei['user_id']),true)}" target="_blank">
    <img src="{$feifei.user_face|ff_url_img|default=$root.'Public/images/face/default.png'}" class="img-circle user-face">
  </a>
  <div class="media-body">
    <h5 class="media-heading user-name">
      <a href="{:ff_url('user/index',array('id'=>$feifei['user_id']),true)}" target="_blank">{$feifei.user_name|htmlspecialchars|nb}</a>
      <small>{$feifei.forum_addtime|date='Y/m/d H:i:s',###}</small>
    </h5>
    <p class="forum-content">
      {$feifei.forum_content|htmlspecialchars|nb|msubstr=0,300}
      <a class="forum-report" href="javascript:;" data-id="{$feifei.forum_id}" title="举报"><small>举报</small></a>
    </p>
    <p class="forum-btn">
      <a class="btn btn-default btn-xs ff-updown-set" href="javascript:;" data-id="{$feifei.forum_id}" data-module="forum" data-type="up" data-toggle="tooltip" data-placement="top" title="支持"><span class="glyphicon glyphicon-thumbs-up"></span> <span class="ff-updown-val">{$feifei.forum_up}</span></a>
      <a class="btn btn-default btn-xs ff-updown-set" href="javascript:;" data-id="{$feifei.forum_id}" data-module="forum" data-type="down" data-toggle="tooltip" data-placement="top" title="反对"><span class="glyphicon glyphicon-thumbs-down"></span> <span class="ff-updown-val">{$feifei.forum_down}</span></a>
      <a class="btn btn-default btn-xs forum-reply-set" href="javascript:;" data-id="{$feifei.forum_id}" data-toggle="collapse" title="回复"><span class="glyphicon glyphicon-comment"></span> <span class="forum-reply-val">{$feifei.forum_reply}</span></a>
      <a class="btn btn-default btn-xs forum-reply-get forum-reply-get-{$feifei.forum_reply}" data-id="{$feifei.forum_id}" href="{:ff_url('forum/read', array('id'=>$feifei['forum_id']), true)}" target="_blank" title="评论详情"><span class="glyphicon glyphicon-align-right"></span> 详情</a>
    </p>
    <p class="collapse forum-reply" data-id="{$feifei.forum_id}">
    </p>
  </div>
</div>
</volist>
</div>
<div class="clearfix mb-1"></div>
<gt name="page_total" value="1">
<div class="text-center">
	<ul class="pager">
		<gt name="forum_page" value="1">
			<li><a id="ff-prev" href="{:ff_url('list/read', array('id'=>$list_id,'p'=>($list_page-1)), true)}">上一页</a></li>
		</gt>
		<lt name="forum_page" value="$page_total">
			<li><a id="ff-next" href="{:ff_url('list/read', array('id'=>$list_id,'p'=>($list_page+1)), true)}">下一页</a></li>
		</lt>
	</ul>
</div>
</gt>
</div><!--container end -->
<div class="clearfix mb-1"></div>
<include file="BlockTheme:footer" />
</body>
</html>