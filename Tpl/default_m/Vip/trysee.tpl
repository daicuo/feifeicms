<div class="jumbotron text-center vod-ispay">
  <eq name="play_ispay" value="1">
    <p>试看{$play_trysee}分钟结束，请升级到VIP用户组看全集。</p>
    <small class="text-muted">本站VIP会员按天计算，买多少您说了算，售价为<span class="text-green">{:C("user_pay_vip_ext")}</span>影币/天，最低购买<span class="text-green">{:C("user_pay_vip_small")}</span>天。</small>
    <p><a href="javascript:;" class="btn btn-success btn-lg user-score-upvip">升级VIP</a></p>
    <php>exit();</php>
  </eq>
  <p>{$play_trysee}分钟试看结束，观看全集需要花费<span class="text-green">{$play_price}</span>影币。</p>
  <small class="text-muted user-score">一次支付，永久观看。影币充值请点击<a class="text-green user-score-payment" href="javascript:;">这里</a>，1元等于<span class="text-green">{:C("user_pay_scale")}</span>个影币。</small>
  <p><a href="javascript:;" class="btn btn-success btn-lg vod-price">确定</a></p>
</div>