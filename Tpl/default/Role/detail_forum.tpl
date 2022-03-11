<php>$params = array();
$params['cid'] = $person_id;
$params['pid'] = '0';
$params['sid'] = '9';
$params['status'] = '1';
$params['limit'] = '20';
$params['order'] = 'forum_addtime';
$params['sort'] = 'desc';
$params['page_p'] = ff_default(intval($_GET['p']),1);
$params['page_is'] = 'true';
$params['page_id'] = 'forum';
$params['cache_name'] = 'default';
$params['cache_time'] = 'default';
$jump = array('id'=>$person_id,'p'=>'FFLINK');
$item_list = ff_mysql_forum($params);
$page_info = ff_url_page('role/forum', $jump, true, $params['page_id'], 4);
$page_total = ff_page_count($params['page_id'], 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/role_forum" />
</head>
<body class="special-detail">
<include file="BlockTheme:header" />
<div class="container ff-bg ff-forum" data-type="{$Think.config.forum_type}">
<div class="page-header">
  <h2>
  <span class="glyphicon glyphicon-calendar text-green"></span>
  <a href="{:ff_url('role/forum',array('id'=>$person_id),true)}">{$person_object_name} {$person_name}的话题讨论</a>
	<label class="pull-right hidden-xs hidden-sm"><include file="./Tpl/base/bootstrap3/inc_share" /></label>
  </h2> 
</div>
<div class="text-gray">{$special_content}</div>
<!-- -->
<div class="ff-forum-reload">
	<form class="form-forum ff-forum-post" role="form" action="{$root}index.php?s=forum-update" method="post">
		<input name="forum_cid" type="hidden" value="{$person_id}" />
		<input name="forum_sid" type="hidden" value="9" />
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
<div class="clearfix mb-2"></div>
<gt name="page_total" value="1">
	<div class="text-center">
  <ul class="pagination pagination-lg hidden-xs hidden-sm">
    {$page_info}
  </ul>
  <ul class="pager visible-xs visible-sm">
    <gt name="params.page_p" value="1">
      <li><a id="ff-prev" href="{:ff_url('role/forum', array('id'=>$person_id,'p'=>($params['page_p']-1)), true)}">上一页</a></li>
    </gt>
    <lt name="params.page_p" value="$page_total">
      <li><a id="ff-next" href="{:ff_url('role/forum', array('id'=>$person_id,'p'=>($params['page_p']+1)), true)}">下一页</a></li>
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