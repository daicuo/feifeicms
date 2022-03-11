<?php
class PaymentAction extends HomeAction{
	
	// 在线充值
	public function index(){
		$this->display('Payment:index');
	}
	
	// 卡密充值
	public function card(){
		$this->display('Payment:card');
	}
	
	//影币在线充值 生成订单 并跳转支付
	public function post(){
		$user_id = $this->islogin();
		if($_POST['pay_type']=='alipay'){
			exit( D('Alipay')->submit($user_id, $_POST) );
		}elseif($_POST['pay_type']=='paypal'){
			exit( D('Paypal')->submit($user_id, $_POST) );
		}elseif($_POST['pay_type']=='rj'){
			exit( D('PayRj')->submit($user_id, $_POST) );
		}elseif($_POST['pay_type']=='wxpay'){
			$order = D('Wxpay')->submit($user_id, $_POST);
			if($order){
				$this->assign($order);
				$this->display('Payment:wxpay');
			}else{
				$this->assign("jumpUrl", ff_url('user/center'));
				$this->error('请检查参数是否设置正确!');
			}
		}elseif( in_array($_POST['pay_type'], array('code_ali', 'code_qq' ,'code_wxpay')) ){
			D('Codepay')->md5url($user_id, $_POST);
		}else{
			$this->error('支付平台出错!');
		}
	}
	
	//影币卡密充值
	public function post_card(){
		$user_id = $this->islogin();
		$card_number = htmlspecialchars($_POST['card_number']);
		if($card_number){
			if(D('Card')->ff_recharge($card_number, $user_id)){
				$this->ajaxReturn($user, "success", 200);
			}else{
				$this->ajaxReturn('', D('Card')->getError(), 404);
			}
		}
		$this->ajaxReturn('', '请输入充值卡密！', 404);
	}
	
	//订单查询
	public function query(){
		$type = $_GET['type'];
		$order = $_GET['order'];
		if($type == 'wxpay'){
			if(D('Wxpay')->query($order)){
				exit('SUCCESS');
			}
		}
		echo('FAIL');
	}
	
	//获取用户ID
	private function islogin(){
		$user_id = D('User')->ff_islogin();
		if($user_id){
			return $user_id;
		}
		if($this->isAjax()){
			$this->ajaxReturn('', "请先登录", 404);
		}
		$this->assign("jumpUrl", ff_url('user/login'));
		$this->error('请先登录!');
	}
}
?>