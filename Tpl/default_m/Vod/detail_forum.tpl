<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/vod_yingping" />
</head>
<body>
<include file="BlockTheme:header" />
<include file="BlockTheme:vod_inc_info" />
<include file="./Tpl/base/bootstrap3/vod_playurl" />
<!-- -->
<php>$page_p = ff_default(intval($_GET['p']),1);
$item_list = ff_mysql_forum('cid:'.$vod_id.';sid:1;pid:0;limit:20;page_is:true;page_id:forum;page_p:'.$page_p.';cache_name:default;cache_time:default;order:forum_addtime;sort:desc');
$page_array = $_GET['ff_page_forum'];
</php>
<div class="container ff-bg ff-forum" data-type="{$Think.config.forum_type}">
<div class="page-header">
 	<h2 class="text-ellipsis">
	<span class="glyphicon glyphicon-comment text-green"></span>
	<a href="{:ff_url('vod/forum',array('id'=>$vod_id),true)}">{$vod_name} 评论</a>
	</h2>
</div>
<div class="ff-forum-reload">
	<form class="form-forum ff-forum-post" role="form" action="{$root}index.php?s=forum-update" method="post">
		<input name="forum_cid" type="hidden" value="{$vod_id}" />
		<input name="forum_sid" type="hidden" value="1" />
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
<div class="ff-forum-item">
	<include file="./Tpl/base/bootstrap3/forum_item" />
</div>
<gt name="page_array.totalpages" value="1">
<div class="text-center">
  <ul class="pager">
    <gt name="page_p" value="1">
      <li><a id="ff-prev" href="{:ff_url('vod/forum', array('id'=>$vod_id,'p'=>($page_p-1)), true)}">上一页</a></li>
    </gt>
    <lt name="page_p" value="$_GET['ff_page_forum']['totalpages']">
      <li><a id="ff-next" href="{:ff_url('vod/forum', array('id'=>$vod_id,'p'=>($page_p+1)), true)}">下一页</a></li>
    </lt>
  </ul>
</div>
</gt>
</div><!--container end -->
<!-- -->
<div class="clearfix mb-1"></div>
<include file="BlockTheme:footer" />
</body>
</html>