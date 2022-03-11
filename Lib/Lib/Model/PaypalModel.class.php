<?php
class PaypalModel extends Model {
	
	//https://www.sandbox.paypal.com
	public function submit($user_id, $post){
		$data = array();
		$data['cmd'] = '_xclick';//告诉paypal该表单是立即购买
		$data['quantity'] = 1; //物品数量。大于 1 时，会与金额相乘 
		$data['business'] = trim(C("pay_paypal_appid"));//收钱的帐号
		$data['item_name'] = 'Score（UID：'.$user_id.'）'; //商品名称
		$data['amount'] = sprintf("%.2f",$post['score_ext']);//价格
		$data['currency_code'] = 'USD';//币种 
		$data['invoice'] = date("YmdHis").mt_rand(10000, 99999);//自定义订单号   paypal原样返回
		$data['return'] = 'http://'.C("site_domain").C("site_path").'notify.paypal.php';//支付成功后网页跳转地址
		$data['notify_url'] = 'http://'.C("site_domain").C("site_path").'notify.paypal.php';//支付成功后paypal后台发送订单通知地址
		$data['charset'] = 'utf-8';
		//$data['lc'] = 'CN';//支付页面语言设置 
		//$data['item_number'] = '';//货架号
		//$data['custom'] = '';// 自定义变量  paypal原样返回
		//写入订单
		D("Orders")->ff_update(array(
			'order_sign'=>$data['invoice'],
			'order_status'=>0,
			'order_ispay'=>1,
			'order_shipping'=>0,
			'order_total'=>1,
			'order_paytype'=>'paypal',
			'order_uid'=>$user_id,
			'order_gid'=>1,
			'order_money'=>$data['amount'],
			'order_info'=>$data['item_name']
		));
		return $this->buildRequestForm($data);
	}
	
	public function buildRequestForm($para, $method='POST', $button_name='正在跳转') {
		$sHtml="<form id='pay' name='pay' action='https://www.paypal.com/cgi-bin/webscr' method='".$method."' style='margin:50px auto; text-align:center'>";
		while (list ($key, $val) = each ($para)) {
			$sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
		}
    $sHtml = $sHtml."<input type='submit' value='".$button_name."'></form>";
		//$sHtml = $sHtml."<input type='image' name='submit' src='https://www.paypalobjects.com/webstatic/mktg/logo/PP_AcceptanceMarkTray-NoDiscover_243x40.png'></form>";
		$sHtml = $sHtml."<script>document.forms['pay'].submit();</script>";
		return $sHtml;
	}
	
	public function notify($post){
		$post['cmd'] = '_notify-validate';
		$json = ff_file_get_contents('https://www.paypal.com/cgi-bin/webscr', 10, '', $post);
		if ($json ==  "VERIFIED") {//已经通过认证
			if($post['payment_status'] == 'Completed'){//检查付款状态  Pending 等侍中|Failed 失败
				D("Orders")->ff_update_order($post['invoice'],$post['payment_fee']);
				return 'success';
			}else {
				return 'fail';
			}
		}else{//未通过认证，有可能是编码错误或非法的 POST 信息
			return $json;   
		}
	}
}
?>