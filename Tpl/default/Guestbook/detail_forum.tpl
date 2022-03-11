<php>$params = array();
$params['cid'] = $forum_id;
$params['pid'] = '0';
$params['sid'] = '5';
$params['status'] = '1';
$params['limit'] = '20';
$params['order'] = 'forum_addtime';
$params['sort'] = 'desc';
$params['page_p'] = ff_default(intval($_GET['p']),1);
$params['page_is'] = 'true';
$params['page_id'] = 'forum';
$params['cache_name'] = 'default';
$params['cache_time'] = 'default';
$jump = array('id'=>$forum_id,'p'=>'FFLINK');
$item_list = ff_mysql_forum($params);
$page_info = ff_url_page('guestbook/forum', $jump, true, $params['page_id'], 4);
$page_total = ff_page_count($params['page_id'], 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/guestbook_detail" />
</head>
<body class="gusetbook-detail">
<include file="BlockTheme:header" />
<div class="container ff-bg">
<div class="page-header">
  <h2>
    <span class="glyphicon glyphicon-comment text-green"></span>
    <a href="{:ff_url('guestbook/read', array('id'=>$forum_id), true)}">留言主题</a>
  </h2>
</div>
<!-- -->
<p class="content">
  {$forum_content|htmlspecialchars|nb}<br>
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
</div>
<div class="clearfix mb-2"></div>
<!-- -->
<div class="container ff-bg ff-forum" data-type="{$Think.config.forum_type}">
<div class="page-header">
  <h2>
	<span class="glyphicon glyphicon-comment text-green"></span> 
	<a href="{:ff_url('guestbook/forum',array('id'=>$forum_id,'p'=>1),true)}">留言评论</a>
	</h2>
</div>
<!--发表评论后刷新网页 -->
<div class="ff-forum-reload">
	<form class="form-forum ff-forum-post" role="form" action="{$root}index.php?s=forum-update" method="post">
		<input name="forum_cid" type="hidden" value="{$forum_id}" />
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
<div class="ff-forum-item">
	<include file="./Tpl/base/bootstrap3/forum_item" />
</div>
<!-- -->
<gt name="page_total" value="1">
  <div class="clear"></div>
  <div class="text-center">
    <ul class="pagination pagination-lg hidden-xs hidden-sm">
      {$page_info}
    </ul>
    <ul class="pager visible-xs visible-sm">
      <gt name="params.page_p" value="1">
        <li><a id="ff-prev" href="{:ff_url('guestbook/forum', array('id'=>$forum_id,'p'=>($params['page_p']-1)), true)}">上一页</a></li>
      </gt>
      <lt name="params.page_p" value="$page_total">
        <li><a id="ff-next" href="{:ff_url('guestbook/forum', array('id'=>$forum_id,'p'=>($params['page_p']+1)), true)}">下一页</a></li>
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