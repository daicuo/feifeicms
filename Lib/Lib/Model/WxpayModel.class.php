<?php
class WxpayModel extends Model {
	//微信PC网站扫码支付 模式二
	public function submit($user_id, $post){
		$total_fee = sprintf("%.2f",$post['score_ext']);
		$data = array();
		$data['appid'] =  trim(C("pay_wxpay_account"));//公众号
		$data['mch_id'] =  trim(C("pay_wxpay_appid"));//商户号
		$data['nonce_str'] =  $this->getNonceStr();//随机字符串
		$data['body'] =  '积分充值（UID：'.$user_id.'）';//商品描述
		$data['fee_type'] =  'CNY';//标价币种
		$data['out_trade_no'] = date("YmdHis").mt_rand(10000, 99999);//商户订单号
		$data['total_fee'] = $total_fee*100;//金额，单位分
		$data['spbill_create_ip'] =  get_client_ip();//终端IP
		$data['notify_url'] =  'http://'.C("site_domain").C("site_path").'notify.wxpay.php';
		$data['trade_type'] =  'NATIVE';//交易类型 JSAPI，NATIVE，APP
		$data['product_id'] = '1';//商品ID
		//$data['openid'] =  'ovprvtzBZaWXnZUadwgexOLNc93M';//用户标识 trade_type=JSAPI时（即公众号支付），此参数必传
		$data['sign'] =  $this->makeSign($data);
		//获取付款二维码
		$data_xml = $this->array2xml($data);
		$res = ff_file_get_contents('https://api.mch.weixin.qq.com/pay/unifiedorder', 10, '', $data_xml);
		$res = $this->xml2array($res);
		if($res['return_code']=='SUCCESS' && $res['result_code']=='SUCCESS'){
			//写入订单
			D("Orders")->ff_update(array(
				'order_sign'=>$data['out_trade_no'],
				'order_status'=>0,
				'order_ispay'=>1,
				'order_shipping'=>0,
				'order_total'=>1,
				'order_paytype'=>'wxpay',
				'order_uid'=>$user_id,
				'order_gid'=>1,
				'order_money'=>$total_fee,
				'order_info'=>$data['body']
			));
			//返回付款信息
			return array(
				'user_id'=>$user_id,
				'total_fee'=>$total_fee,
				'out_trade_no'=>$data['out_trade_no'],
				'code_url'=>$res['code_url']
			);
		}
		return false;
	}
	
	// 订单通知处理
	public function notify($xml){
		//将服务器返回的XML数据转化为数组
		$data = $this->xml2array($xml);
		// 保存微信服务器返回的签名sign
		$data_sign = $data['sign'];
		// sign不参与签名算法
		unset($data['sign']);
		// 生成签名
		$sign = $this->makeSign($data);
		// 判断签名是否正确  判断支付状态
		if ( ($sign===$data_sign) && ($data['return_code']=='SUCCESS') && ($data['result_code']=='SUCCESS') ) {
			D("Orders")->ff_update_order($data['out_trade_no'], number_format($data['total_fee']/100,2));
			return '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
		}else{
			return '<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[签名失败]]></return_msg></xml>';
		}
	}
	
	//订单查询 是否已完成支付
	public function query($out_trade_no){
		$data = array();
		$data['appid'] =  trim(C("pay_wxpay_account"));//公众号
		$data['mch_id'] =  trim(C("pay_wxpay_appid"));//商户号
		$data['nonce_str'] =  $this->getNonceStr();//随机字符串
		$data['out_trade_no'] =  $out_trade_no;//商户订单号
		$data['sign'] =  $this->makeSign($data);
		$data_xml = $this->array2xml($data);
		$res = ff_file_get_contents('https://api.mch.weixin.qq.com/pay/orderquery', 10, '', $data_xml);
		$res = $this->xml2array($res);
		if($res['return_code']=='SUCCESS' && $res['result_code']=='SUCCESS' && $res['trade_state']=='SUCCESS'){
			return true;
		}
		return false;
	}
	
	/**
	 * 
	 * 产生随机字符串，不长于32位
	 * @param int $length
	 * @return 产生的随机字符串
	 */
	public function getNonceStr($length = 32) {
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {  
			$str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		} 
		return $str;
	}
	
	/**
	* 生成签名
	* @return 签名
	*/
	public function makeSign($data){
		//获取微信支付秘钥
		$key = trim(C("pay_wxpay_appkey"));
		// 去空
		$data=array_filter($data);
		//签名步骤一：按字典序排序参数
		ksort($data);
		$string_a=http_build_query($data);
		$string_a=urldecode($string_a);
		//签名步骤二：在string后加入KEY
		$string_sign_temp=$string_a."&key=".$key;
		//签名步骤三：MD5加密
		$sign = md5($string_sign_temp);
		// 签名步骤四：所有字符转为大写
		$result=strtoupper($sign);
		return $result;
	}
	
	/**
	 * 将一个数组转换为 XML 结构的字符串
	 * @param array $arr 要转换的数组
	 * @param int $level 节点层级, 1 为 Root.
	 * @return string XML 结构的字符串
	 */
	public function array2xml($arr, $level = 1) {
		$s = $level == 1 ? "<xml>" : '';
		foreach($arr as $tagname => $value) {
				if (is_numeric($tagname)) {
						$tagname = $value['TagName'];
						unset($value['TagName']);
				}
				if(!is_array($value)) {
						$s .= "<{$tagname}>".(!is_numeric($value) ? '<![CDATA[' : '').$value.(!is_numeric($value) ? ']]>' : '')."</{$tagname}>";
				} else {
						$s .= "<{$tagname}>" . $this->array2xml($value, $level + 1)."</{$tagname}>";
				}
		}
		$s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
		return $level == 1 ? $s."</xml>" : $s;
	}
	
	/**
	 * 将xml转为array
	 * @param  string 	$xml xml字符串
	 * @return array    转换得到的数组
	 */
	public function xml2array($xml){   
		//禁止引用外部xml实体
		libxml_disable_entity_loader(true);
		$result= json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);        
		return $result;
	}
}
?>