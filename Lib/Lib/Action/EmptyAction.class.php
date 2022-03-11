<?php
class EmptyAction extends HomeAction{
	
	public function _empty(){ 
		header("HTTP/1.0 404 Not Found");
		$this->display('./Public/error/404.html');
	}
	
}
?>