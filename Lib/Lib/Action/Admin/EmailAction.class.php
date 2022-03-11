<?php
class EmailAction extends BaseAction{
  public function test(){
		if(D("Email")->send(C("email_usertest"), 'test用户', '邮件配置测试', '您的邮件配置正常，可以正常发送邮件，请勿回复。')){
			dump('邮件发送成功');
		}else{
			dump('邮件发送失败，'.D('Email')->getError());
		}
  }
}
?>