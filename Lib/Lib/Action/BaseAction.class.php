<?php
/*****************后台公用类库 继承全站公用类库*******************************/
class BaseAction extends AllAction{
  public function _initialize(){
	  parent::_initialize();
		session_start();session_write_close();
		if (!$_SESSION[C('USER_AUTH_KEY')]) {
			$this->assign('jumpUrl',C('cms_admin').'?s=Admin-Login');
			$this->error('对不起，您还没有登录，请先登录！');
		}
  }
}
?>