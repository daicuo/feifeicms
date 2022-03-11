<?php
class CodepayModel extends Model {
	
	//MD5签名数据创建订单 https://codepay.fateqq.com/apiword/ByG0W5cqe.html
	public function md5url($user_id, $post){
		$order = array();
		$order['order_status'] = 0;
		$order['order_ispay'] = 1;
		$order['order_shipping'] = 0;
		$order['order_total'] = 1;
		$order['order_paytype'] = $post['pay_type'];
		$order['order_uid'] = $user_id;
		$order['order_gid'] = 1;
		$order['order_info'] = 'Score（UID：'.$user_id.'）';
		$order['order_sign'] = date("YmdHis").mt_rand(10000, 99999);//订单号
		$order['order_money'] = sprintf("%.2f",$post['score_ext']);//商品总价
		//写入订单
		D("Orders")->ff_update($order);
		//支付方式
		$pay_type = 1;
		if($order['order_paytype'] == 'code_qq'){
			$pay_type = 2;
		}elseif($order['order_paytype'] == 'code_wxpay'){
			$pay_type = 3;
		}
		//组装参数	
		$data = array(
			"id" => trim(C("pay_code_appid")),//你的码支付ID
			"type" => $pay_type,//1支付宝支付 3微信支付 2QQ钱包
			"price" => $order['order_money'],//金额100元
			"pay_id" => $order['order_sign'], //唯一标识 可以是用户ID,用户名,订单ID,ip 付款后返回
			"notify_url" => 'http://'.C("site_domain").C("site_path").'notify.codepay.php',//通知地址
			"return_url" =>'http://'.C("site_domain").C("site_path").'notify.codepay.php',//跳转地址
			"debug" => 1,//软件未启动的话
			"ac" => 1,//即时到账和代收款默认1 代收款需要申请
			"param" => "",//自定义参数
		);
		
		ksort($data); //重新排序$data数组
		reset($data); //内部指针指向数组中的第一个元素
		
		$sign = ''; //初始化需要签名的字符为空
		$urls = ''; //初始化URL参数为空
		
		foreach ($data as $key => $val) { //遍历需要传递的参数
				if ($val == ''||$key == 'sign') continue; //跳过这些不参数签名
				if ($sign != '') { //后面追加&拼接URL
						$sign .= "&";
						$urls .= "&";
				}
				$sign .= "$key=$val"; //拼接为url参数形式
				$urls .= "$key=" . urlencode($val); //拼接为url参数形式并URL编码参数值
		}
		
		$query = $urls . '&sign='.md5($sign.trim(C("pay_code_appkey"))); //创建订单所需的参数
		$url = "http://api2.fateqq.com:52888/creat_order/?{$query}"; //支付页面
		redirect($url);
	}
	
	//验证异步通知
	public function notify($post){
		// $post['pay_id'] 这是付款人的唯一身份标识或订单ID
		// $post['pay_no'] 这是流水号 没有则表示没有付款成功 流水号不同则为不同订单
		// $post['money'] 这是付款金额
		// $post['param'] 这是自定义的参数
		ksort($post); //排序post参数
		reset($post); //内部指针指向数组中的第一个元素
		$sign = '';
		foreach ($post as $key => $val) {
			if ($val == '') continue; //跳过空值
			if ($key != 'sign') { //跳过sign
				$sign .= "$key=$val&"; //拼接为url参数形式
			}
		}
		if (!$post['pay_no'] || md5(substr($sign,0,-1).trim(C("pay_code_appkey"))) != $post['sign']) {
			return 'fail';
		}else{
			D("Orders")->ff_update_order($post['pay_id'], $post['money']);
			return 'success';
		}
	}
}
?>