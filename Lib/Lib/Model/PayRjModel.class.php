<?php
class PayRjModel extends Model {
	//瑞捷云支付19kf
	public function submit($user_id,$post){
		$data = array();
		$data['version'] = '1.0';
		$data['customerid'] = trim(C("pay_rj_appid"));
		$data['sdorderno'] = date("YmdHis").mt_rand(10000, 99999);
		$data['total_fee'] = sprintf("%.2f",$post['score_ext']);
		$data['notifyurl'] = 'http://'.C("site_domain").C("site_path").'notify.payrj.php';
		$data['returnurl'] = 'http://'.C("site_domain").C("site_path").'notify.payrj.php';
		$data['remark'] = '积分充值（UID：'.$user_id.'）';
		$data['sign'] = md5('version='.$data['version'].'&customerid='.$data['customerid'].'&total_fee='.$data['total_fee'].'&sdorderno='.$data['sdorderno'].'&notifyurl='.$data['notifyurl'].'&returnurl='.$data['returnurl'].'&'.C("pay_rj_appkey"));
		//写入订单
		D("Orders")->ff_update(array(
			'order_sign'=>$data['sdorderno'],
			'order_status'=>0,
			'order_ispay'=>1,
			'order_shipping'=>0,
			'order_total'=>1,
			'order_paytype'=>'rjpay',
			'order_uid'=>$user_id,
			'order_gid'=>1,
			'order_money'=>$data['total_fee'],
			'order_info'=>$data['remark']
		));
		return $this->buildRequestForm($data);
		//return $data;
	}
	
	public function buildRequestForm($para, $method='POST', $button_name='正在跳转') {
		$sHtml = "<form id='pay' name='pay' action='http://www.19fk.com/checkout' method='".$method."'>";
		while (list ($key, $val) = each ($para)) {
			$sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
		}
		//submit按钮控件请不要含有name属性
    $sHtml = $sHtml."<input type='submit' value='".$button_name."'></form>";
		$sHtml = $sHtml."<script>document.forms['pay'].submit();</script>";
		return $sHtml;
	}
	
	public function notify($data){
		$post = array();
		$post['status'] = $data['status'];
		$post['customerid'] = $data['customerid'];
		$post['sdorderno'] = $data['sdorderno'];
		//$post['total_fee'] = (float) $data['total_fee'];
		$post['total_fee'] = number_format($data['total_fee'],2);
		$post['sdpayno'] = $data['sdpayno'];//平台订单号
		$post['paytype'] = $data['paytype'];
		$post['remark'] = $data['remark'];
		$post['sign'] = $data['sign'];
		//签名
		$mysign = md5('customerid='.$post['customerid'].'&status='.$post['status'].'&sdpayno='.$post['sdpayno'].'&sdorderno='.$post['sdorderno'].'&total_fee='.$post['total_fee'].'&paytype='.$post['paytype'].'&'.C("pay_rj_appkey"));
		//验证
		if($post['sign'] == $mysign){
			if($post['status'] == '1'){
				D("Orders")->ff_update_order($post['sdorderno'],$post['total_fee']);
				return 'success';
			} else {
				return 'fail';
			}
		} else {
			return 'signerr';
		}
	}
}
?>