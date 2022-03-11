<div class="modal fade ff-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h5 class="modal-title">用户登录</h5>
      </div>
      <div class="modal-body">
        <form class="form-horizontal form-user-login" action="{:ff_url('user/loginpost')}" method="post" role="form" target="_blank">
          <div class="form-group">
            <label for="user_email" class="col-md-3 control-label">邮箱</label>
            <div class="col-md-8">
              <input class="form-control" name="user_email" id="user_email" type="text" placeholder="请输入邮箱" required>
            </div>
          </div>
          <div class="form-group">
            <label for="user_pwd" class="col-md-3 control-label">密码</label>
            <div class="col-md-8">
              <input class="form-control" name="user_pwd" id="user_pwd" type="password" placeholder="请输入密码" required>
            </div>
          </div>
          <div class="form-group">
            <div class="col-xs-6 checkbox text-right">
              <label><input name="user_remember" type="checkbox" value="1" checked> 下次自动登录</label>
            </div>
            <div class="col-xs-6">
              <button type="submit" class="btn btn-success" id="user-submit">登录</button>
              <a class="btn btn-success" href="{:ff_url('user/register')}">注册</a>
            </div>
          </div>
        </form>
        <h6 class="user-login-alert text-center">
        </h6>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal -->
</div>