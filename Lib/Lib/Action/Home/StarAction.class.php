<?php
class StarAction extends HomeAction{
	private $sid = 8;
	// TAG话题
	public function tags(){
		$params = ff_param_url();
		$info = $this->Lable_Tags($params, $this->sid);
		$this->assign($info);
		$this->display($info['tag_skin']);
	}
	// 搜索 get方式
	public function search(){
		$params = ff_param_url();
		$info = $this->Lable_Search($params, $this->sid);
		$this->assign($info);
		$this->display($info['search_skin']);
  }
	// 按ID读取影片
  public function read(){
		$detail = $this->get_cache_detail('id');
		$this->assign($detail);
		$this->display($detail['person_skin']);
  }
	// 按别名读取影片
	public function ename(){
		$detail = $this->get_cache_detail('ename');
		$this->assign($detail);
		$this->display($detail['person_skin']);
	}
	// 空白操作
	public function _empty($action){
		if(is_numeric($_GET['id'])){
	 		$detail = $this->get_cache_detail('id');
		}else{
			$detail = $this->get_cache_detail('ename');
		}
		$this->assign($detail);
		$this->display('Star:detail_'.$action);
	}
	// 从数据库获取内容数据
	private function get_cache_detail($action='id'){
		$params = array();
		$params['page'] = !empty($_GET['p']) ? intval($_GET['p']) : 1;
		$params['ajax'] = intval($_GET['ajax']);
		$where = array();
		$where['person_status'] = array('eq', 1);
		if($action=='ename'){
			$params['id'] = htmlspecialchars($_GET['id']);
			$where['person_ename'] = array('eq', $params['id']);
		}else{
			$params['id'] = intval($_GET['id']);
			$where['person_id'] = array('eq', $params['id']);
		}
		//查库
		$info = D('Person')->ff_find('*', $where, 'cache_page_person_'.$params['id'], true);
		if(!$info){
			$this->assign("jumpUrl",C('site_path'));
			$this->error('此人物已经删除，请选择其它！');
		}
		//解析标签
		return $this->Lable_Person_Read($info, $this->sid);
	}
}
?>