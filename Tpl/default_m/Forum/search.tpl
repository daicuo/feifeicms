<php>$params = array();
$params['limit'] = 30;
$params['order'] = 'forum_addtime';
$params['sort'] = 'desc';
$params['page_p'] = $search_page;
$params['page_is'] = true;
$params['page_id'] = 'forum';
$params['cache_name'] = 'default';
$params['cache_time'] = 'default';
$params['wd'] = $search_wd;
if(C('user_check')){
	$params['status'] = 1;
}
$jump = array('wd'=>urlencode($search_wd),'p'=>'FFLINK');
$item_forum = ff_mysql_forum($params);
$page_total = ff_page_count('forum', 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/forum_search" />
</head>
<body class="forum-list">
<include file="BlockTheme:header" />
<!-- -->
<div class="container ff-bg ff-forum">
<div class="page-header">
  <h2 class="text-ellipsis">
  <span class="glyphicon glyphicon-comment text-green"></span>
	搜索 》{$search_name}{$search_wd}
	<small>共有<span class="text-green">{:ff_page_count('forum', 'records')}</span>个 第<span class="text-green">{$search_page}</span>页</small>
  </h2>
</div>
<div class="ff-forum-item">
<volist name="item_forum" id="feifei">
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
		<gt name="search_page" value="1">
			<li><a id="ff-prev" href="{:ff_url('forum/search', array_merge($jump,array('p'=>$search_page-1)), true)}">上一页</a></li>
		</gt>
		<lt name="search_page" value="$page_total">
			<li><a id="ff-next" href="{:ff_url('forum/search', array_merge($jump,array('p'=>$search_page+1)), true)}">下一页</a></li>
		</lt>
	 </ul>
</div>
</gt>
</div><!--container end -->
<div class="clearfix mb-1"></div>
<include file="BlockTheme:footer" />
</body>
</html>