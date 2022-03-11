<div class="modal fade ff-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h5 class="modal-title">影币在线充值</h5>
    </div>
    <div class="modal-body text-center">
      <form class="form-horizontal forum-payment" action="{:ff_url('payment/post')}" method="post" role="form" target="_blank" style="padding:20px 0" data-small="{:C('user_pay_small')}">
        <div class="form-group">
          <label for="score_ext" class="col-sm-3 control-label">充值金额</label>
          <div class="col-sm-6">
            <input class="form-control text-center" name="score_ext" id="score_ext" value="{:(C('user_pay_small'))}" type="text" required>
          </div>
        </div>
        <div class="form-group">
          <label for="pay_type" class="col-sm-3 control-label">支付方式</label>
          <div class="col-sm-9 text-left">
						<volist name=":ff_PaymentItem()" id="paytype" mod="4">
						<label class="radio-inline">
              <input type="radio" name="pay_type" id="pay_type" value="{$paytype}" checked>
							<switch name="paytype">
							<case value="rj">云支付</case>
							<case value="code_ali">支付宝</case>
							<case value="code_wxpay">微 信</case>
							<case value="code_qq">QQ钱包</case>
							<case value="alipay">支付宝</case>
							<case value="wxpay">微 信</case>
							<case value="paypal">PayPal</case>
							</switch>
            </label>
						<eq name="mod" value="0"><br /></eq>
						</volist>
          </div>
        </div>
        <button type="submit" class="btn btn-success">提交</button>
      </form>
    </div>
    <div class="modal-footer">
        <h6 class="user-pay-alert text-center">
        1元等于<span class="text-green">{:C("user_pay_scale")}</span>个影币，最低<span class="text-green">{:C("user_pay_small")}</span>元起充，支持微信、支付宝、网银等在线充值
        </h6>
    </div>
  </div>
</div>
</div>