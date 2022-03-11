<?php
class NotifyAction extends HomeAction{
	//F('_feifeicms/order', $_POST);
	
	// 瑞捷支付定单通知处理
	public function payrj(){
		if($this->isPost()){
			echo(D("PayRj")->notify($_POST));
		}else{
			$this->assign("jumpUrl", './index.php?g=home&m=user&a=center&action=orders');
			$this->success("支付完成！");
		}
	}
	
	// 支付宝通知处理
	public function alipay(){
		if($this->isPost()){
			echo(D("Alipay")->notify($_POST));
		}else{
			$this->assign("jumpUrl", './index.php?g=home&m=user&a=center&action=orders');
			$this->success("支付完成！");
		}
	}
	
	// 微信定单通知处理
	public function wxpay(){
		if($this->isPost()){
			$xml = file_get_contents('php://input');
			echo(D("Wxpay")->notify($xml));
		}else{
			$this->assign("jumpUrl", './index.php?g=home&m=user&a=center&action=orders');
			$this->success("支付完成！");
		}
	}
	
	// paypal定单异步通知处理
	public function paypal(){
		if($this->isPost()){
			echo(D("Paypal")->notify($_POST));
		}else{
			$this->assign("jumpUrl", './index.php?g=home&m=user&a=center&action=orders');
			$this->success("支付完成！");
		}
	}	
	
	// 码支付处理
	public function codepay(){
		if($this->isPost()){
			echo(D("Codepay")->notify($_POST));
		}else{
			$this->assign("jumpUrl", './index.php?g=home&m=user&a=center&action=orders');
			$this->success("支付完成！");
		}
	}
}
?>