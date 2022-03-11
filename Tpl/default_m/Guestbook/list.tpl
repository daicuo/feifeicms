<php>$item_list = ff_mysql_forum('status:1;sid:5;cid:0;pid:0;limit:20;page_is:true;page_id:forum;page_p:'.$list_page.';cache_name:default;cache_time:default;order:forum_istop desc,forum_id;sort:desc');
$page_array = $_GET['ff_page_forum'];
$page_info = ff_url_page('list/read',array('id'=>$list_id,'p'=>'FFLINK'), true, 'forum', 4);
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/guestbook_list" />
</head>
<body class="guestbook-list">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
  <h2>
  <span class="glyphicon glyphicon-book text-green"></span>
  <a href="{:ff_url('list/read', array('id'=>$list_id,'p'=>1), true)}">{$list_name}</a>
  <small>共<span class="text-green">{:ff_page_count('forum', 'records')}</span>篇、第<span class="text-green">{$list_page}</span>页</small>
  </h2>
</div>
<!--发表评论后刷新网页 -->
<div class="ff-forum-reload">
	<form class="form-forum ff-forum-post" role="form" action="{$root}index.php?s=forum-update" method="post">
		<input name="forum_cid" type="hidden" value="0" />
		<input name="forum_sid" type="hidden" value="5" />
		<input name="forum_pid" type="hidden" value="0" />
		<div class="form-group">
			<textarea name="forum_content" class="form-control" rows="5" placeholder="吐槽......"></textarea>
		</div>
		<div class="form-group text-right">
			<label>
				<input class="form-control input-sm text-center ff-vcode ff-vcode-input" name="forum_vcode" maxlength="4" type="text" placeholder="验证码">
			</label>
			<label>
				<button type="submit" class="btn btn-default btn-sm">提交</button>
			</label>
		</div>
		<div class="form-group ff-alert clearfix">
		</div>
	</form>
</div>
</div>
<div class="clearfix mb-1"></div>
<!-- -->
<div class="container ff-bg ff-forum pt-3" data-type="{$Think.config.forum_type}">
<div class="ff-forum-item">
	<include file="BlockTheme:item_media_guestbook" />
</div>
<gt name="page_array.totalpages" value="1">
	<div class="text-center">
  <ul class="pager">
    <gt name="list_page" value="1">
      <li><a id="ff-prev" href="{:ff_url('list/read', array('id'=>$list_id,'p'=>($list_page-1)), true)}">上一页</a></li>
    </gt>
    <lt name="list_page" value="$page_array['totalpages']">
      <li><a id="ff-next" href="{:ff_url('list/read', array('id'=>$list_id,'p'=>($list_page+1)), true)}">下一页</a></li>
    </lt>
  </ul> 
  </div>
</gt>
</div>
<div class="clearfix mb-1"></div>
<!-- -->
<include file="BlockTheme:footer" />
</body>
</html>