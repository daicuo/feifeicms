<?php
class AlipayModel extends Model {

	public function submit($user_id, $post){
		$data = array();
		$data['service'] = 'create_direct_pay_by_user';//使用即时到帐交易接口
		$data['payment_type'] = '1';//默认值为：1（商品购买）
		$data['quantity'] = '1';//数量
		$data['_input_charset'] = 'utf-8';
		$data['partner'] = trim(C("pay_alipay_appid"));
		$data['seller_email'] = trim(C("pay_alipay_account"));
		$data['out_trade_no'] = date("YmdHis").mt_rand(10000, 99999);
		$data['notify_url'] = 'http://'.C("site_domain").C("site_path").'notify.alipay.php';
		$data['return_url'] = 'http://'.C("site_domain").C("site_path").'notify.alipay.php';
		$data['subject'] = '积分充值（UID：'.$user_id.'）';
		$data['total_fee'] = sprintf("%.2f",$post['score_ext']);
		//写入订单
		D("Orders")->ff_update(array(
			'order_sign'=>$data['out_trade_no'],
			'order_status'=>0,
			'order_ispay'=>1,
			'order_shipping'=>0,
			'order_total'=>1,
			'order_paytype'=>'alipay',
			'order_uid'=>$user_id,
			'order_gid'=>1,
			'order_money'=>$data['total_fee'],
			'order_info'=>$data['subject']
		));
		return $this->buildRequestForm($data);
	}
	
	public function notify($post){
		$isSign = $this->getSignVeryfy($post, $post["sign"]);
		//验证成功
		if($isSign) {
			if ($post['trade_status'] == 'TRADE_SUCCESS') {
				D("Orders")->ff_update_order($post['out_trade_no'],$post['total_fee']);
			}
			return "success";
		}else{
			return "fail";
		}
	}
	
	/**
	 * 建立请求，以表单HTML形式构造（默认）
	 * @param $para_temp 请求参数数组
	 * @param $method 提交方式。两个值可选：post、get
	 * @param $button_name 确认按钮显示文字
	 * @return 提交表单HTML文本
	 */
	public function buildRequestForm($para_temp, $method='POST', $button_name='正在跳转') {
		//待请求参数数组
		$para = $this->buildRequestPara($para_temp);
		$sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='https://mapi.alipay.com/gateway.do?_input_charset=utf-8' method='".$method."'>";
		while (list ($key, $val) = each ($para)) {
			$sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
		}
		//submit按钮控件请不要含有name属性
    $sHtml = $sHtml."<input type='submit' value='".$button_name."'></form>";
		$sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";
		return $sHtml;
	}
	
	/**
	 * 生成要请求给支付宝的参数数组
	 * @param $para_temp 请求前的参数数组
	 * @return 要请求的参数数组
	 */
	public function buildRequestPara($para_temp) {
		//除去待签名参数数组中的空值和签名参数
		$para_filter = $this->paraFilter($para_temp);

		//对待签名参数数组排序
		$para_sort = $this->argSort($para_filter);

		//生成签名结果
		$mysign = $this->buildRequestMysign($para_sort);
		
		//签名结果与签名方式加入请求提交参数组中
		$para_sort['sign'] = $mysign;
		$para_sort['sign_type'] = 'MD5';
		
		return $para_sort;
	}
	
	/**
	 * 除去数组中的空值和签名参数
	 * @param $para 签名参数组
	 * return 去掉空值与签名参数后的新签名参数组
	 */
	public function paraFilter($para) {
		$para_filter = array();
		while (list ($key, $val) = each ($para)) {
			if($key == "sign" || $key == "sign_type" || $val == "")continue;
			else	$para_filter[$key] = $para[$key];
		}
		return $para_filter;
	}
	
	/**
 * 对数组排序
 * @param $para 排序前的数组
 * return 排序后的数组
 */
	public function argSort($para) {
		ksort($para);
		reset($para);
		return $para;
	}
	
	/**
	* 生成签名结果
	* @param $para_sort 已排序要签名的数组
	* return 签名结果字符串
	*/
	public function buildRequestMysign($para_sort) {
		//把数组所有元素，按照"参数=参数值"的模式用"&"字符拼接成字符串
		$prestr = $this->createLinkstring($para_sort);
		
		$mysign = $this->md5Sign($prestr, C("pay_alipay_appkey"));

		return $mysign;
	}
	
	/**
	 * 把数组所有元素，按照"参数=参数值"的模式用"&"字符拼接成字符串
	 * @param $para 需要拼接的数组
	 * return 拼接完成以后的字符串
	 */
	public function createLinkstring($para) {
		$arg  = "";
		while (list ($key, $val) = each ($para)) {
			$arg.=$key."=".$val."&";
		}
		//去掉最后一个&字符
		$arg = substr($arg,0,count($arg)-2);
		
		//如果存在转义字符，那么去掉转义
		if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
		
		return $arg;
	}
	
	/**
 * 签名字符串
 * @param $prestr 需要签名的字符串
 * @param $key 私钥
 * return 签名结果
 */
	public function md5Sign($prestr, $key) {
		$prestr = $prestr . $key;
		return md5($prestr);
	}
		
 	/**
	 * 获取返回时的签名验证结果
	 * @param $para_temp 通知返回来的参数数组
	 * @param $sign 返回的签名结果
	 * @return 签名验证结果
	 */
	public function getSignVeryfy($para_temp, $sign) {
		//除去待签名参数数组中的空值和签名参数
		$para_filter = $this->paraFilter($para_temp);
		
		//对待签名参数数组排序
		$para_sort = $this->argSort($para_filter);
		
		//把数组所有元素，按照"参数=参数值"的模式用"&"字符拼接成字符串
		$prestr = $this->createLinkstring($para_sort);
		
		$isSgin = false;
		$isSgin = $this->md5Verify($prestr, $sign, C("pay_alipay_appkey"));
		
		return $isSgin;
	}
	
		/**
 * 验证签名
 * @param $prestr 需要签名的字符串
 * @param $sign 签名结果
 * @param $key 私钥
 * return 签名结果
 */
	public function md5Verify($prestr, $sign, $key) {
		$prestr = $prestr . $key;
		$mysgin = md5($prestr);
		if($mysgin == $sign) {
			return true;
		}
		else {
			return false;
		}
	}
}
?>