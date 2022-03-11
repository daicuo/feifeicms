<form class="form-forum ff-forum-post" role="form" action="{$root}index.php?s=forum-update" method="post">
  <input name="forum_cid" type="hidden" value="{$forum_cid|default=0}" />
  <input name="forum_sid" type="hidden" value="{$forum_sid|default=5}" />
  <input name="forum_pid" type="hidden" value="{$forum_pid|default=0}" />
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