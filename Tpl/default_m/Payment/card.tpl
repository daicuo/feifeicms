<div class="modal fade ff-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h5 class="modal-title">影币卡密充值</h5>
    </div>
    <div class="modal-body text-center">
      <form class="form-horizontal forum-card" action="{:ff_url('payment/post_card')}" method="post" role="form" target="_blank" style="padding:20px 0">
        <div class="form-group">
          <label for="score_ext" class="col-sm-3 control-label">充值卡号</label>
          <div class="col-sm-7">
            <input class="form-control text-center" name="card_number" id="card_number" type="text" required>
          </div>
        </div>
				<a class="btn btn-success" href="{:C('pay_card_sell')}"target="_blank">购买卡密</a>
				<button type="submit" class="btn btn-success">提交充值</button>
      </form>
    </div>
    <div class="modal-footer">
        <h5 class="alert-card text-center">
        本站卡密24小时无人自动发货，如需购买请点击<a href="{:C('pay_card_sell')}" target="_blank">这里</a>、1元等于<span class="text-green">{:C("user_pay_scale")}</span>个影币</span>
        </h5>
    </div>
  </div>
</div>
</div>