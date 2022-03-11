<?php
class AjaxAction extends HomeAction{
  public function _empty($action){
		$this->display('Ajax:'.$action);
	}
}
?>