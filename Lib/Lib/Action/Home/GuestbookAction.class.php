<?php
class GuestbookAction extends HomeAction{
	private $sid = 5;	
	public function tags(){
		echo('not tags');
	}	
	public function search(){
		$params = ff_param_url();
		$info = $this->Lable_Search($params, $this->sid);
		$this->assign($info);
		$this->display($info['search_skin']);
  }
  public function read(){
		$detail = $this->get_cache_detail('id');
		$this->assign($detail);
		$this->display($detail['guestbook_skin']);
  }
  public function ename(){
		$info = $this->label();
		$detail = $this->get_cache_detail('ename');
		$this->assign($info);
		$this->assign($detail);
		$this->display($detail['guestbook_skin']);
  }
	public function _empty($action){
		if(is_numeric($_GET['id'])){
	 		$detail = $this->get_cache_detail('id');
		}else{
			$detail = $this->get_cache_detail('ename');
		}
		$this->assign($detail);
		$this->display('Guestbook:detail_'.$action);
	}			
	private function get_cache_detail($action='id'){
		$params = array();
		$where = array();
		$where['forum_cid'] = array('eq', 0);//必要条件
		if(C('user_check')){
			$where['forum_status'] = array('eq', 1);
		}
		if($action=='ename'){
			$params['id'] = htmlspecialchars($_GET['id']);
			$where['forum_ename'] = array('eq', $params['id']);
		}else{
			$params['id'] = intval($_REQUEST['id']);
			$where['forum_id'] = array('eq', $params['id']);
		}
		//查库
		$info = D('Forum')->ff_find('*', $where, 'cache_page_forum_'.$params['id'], 'User');
		if(!$info){
			$this->assign("jumpUrl",C('site_path'));
			$this->error('留言已删除！');
		}
		//解析标签
		return $this->Lable_Guestbook_Read($info);
	}
}
?>