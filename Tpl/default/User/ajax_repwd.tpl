<div class="modal fade ff-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h5 class="modal-title">修改密码</h5>
    </div>
    <div class="modal-body">
      <form class="form-horizontal user-repwd-form" action="{:ff_url('user/repwd')}" method="post" role="form" target="_blank" style="padding:20px 0">
      	<div class="form-group">
          <label for="user_email" class="col-sm-4 control-label">原密码</label>
          <div class="col-sm-6">
          <input class="form-control text-center" name="user_pwd_old" id="user_pwd_old"  type="password" required>
          </div>
        </div>
        <div class="form-group">
          <label for="user_email" class="col-sm-4 control-label">新密码</label>
          <div class="col-sm-6">
          <input class="form-control text-center" name="user_pwd" id="user_pwd"  type="password" required>
          </div>
        </div>
        <div class="form-group">
          <label for="user_email_re" class="col-sm-4 control-label">新密码</label>
          <div class="col-sm-6">
          <input class="form-control text-center" name="user_pwd_re" id="user_pwd_re"  type="password" required>
          </div>
        </div>
        <div class="form-group">
          <div class="col-xs-12 text-center">
            <button type="submit" class="btn btn-success">提交</button>
          </div>
        </div>
      </form>
    </div>
    <div class="modal-footer">
        <h6 class="user-repwd-alert text-center">
        需验证用户密码才能修改。
        </h6>
    </div>
  </div>
</div>
</div>