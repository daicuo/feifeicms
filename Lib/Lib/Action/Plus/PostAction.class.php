<?php
class PostAction extends HomeAction{
	
	public function _initialize(){
		if(C('collect_passwd')){
			if(trim(C('collect_passwd')) != htmlspecialchars($_POST['collect_passwd'])){
				exit( '密码不正确。' );
			}
		}else{
			exit( '请先在后台设置密码。' );
		}
  }

	public function vod(){
		$data = D('Cj')->vod_db($_POST);
		if(!$data){
			exit(D('Cj')->getError());
		}
		echo $data;
  }
	
	public function news(){
		$data = D('Cj')->news_db($_POST);
		if(!$data){
			exit(D('Cj')->getError());
		}
		echo $data;
  }
	
	public function scenario(){
		$data = D('Cj')->scenario_db($_POST);
		if(!$data){
			exit(D('Cj')->getError());
		}
		echo $data;
  }	
	
	public function star(){
		$data = D('Cj')->star_db($_POST);
		if(!$data){
			exit(D('Cj')->getError());
		}
		echo $data;
  }	
	
	public function role(){
		$data = D('Cj')->role_db($_POST);
		if(!$data){
			exit(D('Cj')->getError());
		}
		echo $data;
  }				
}
?>