<?php
class ListAction extends HomeAction{
	
	// 按ID获取
	public function read(){
		$info = $this->get_cache_list('id','list');
		$this->assign($info);
		$this->display($info['list_skin']);
	}
	
	// 按别名获取
  public function ename(){
		$info = $this->get_cache_list('ename','list');
		$this->assign($info);
		$this->display($info['list_skin']);
	}
	
	// 多条件筛选
	public function select(){
		$info = $this->get_cache_list('id','select');
		$this->assign($info);
		$this->display($info['list_skin_type']);
	}
		
	// 从数据库获取分类数据
	private function get_cache_list($action='id',$lable='list'){
		//参数
		$params = array();
		$params['page'] = !empty($_GET['p']) ? intval($_GET['p']) : 1;
		$params['ajax'] = intval($_GET['ajax']);
		//条件
		$where = array();
		$where['list_status'] = array('eq', 1);
		if($action=='ename'){
			$params['id'] = htmlspecialchars($_GET['id']);
			$where['list_dir'] = array('eq', $params['id']);
		}else{
			$params['id'] = intval($_GET['id']);
			$where['list_id'] = array('eq', $params['id']);
		}
		$info = D('List')->ff_find('*', $where, 'cache_page_list_'.$params['id']);
		if(!$info){
			$this->assign("jumpUrl",C('site_path'));
			$this->error('该分类已删除，请选择其它分类！');
		}
		//解析标签
		if($lable == 'select'){
			return $this->Lable_Select(array_merge($params,ff_param_url()), $info);
		}else{
			return $this->Lable_List($params, $info);
		}
	}
	
	// 空操作
	public function _empty($action){
		$info = $this->Lable_List(ff_param_url());
		$this->assign($info);
		$this->display('List:'.$action);
	}
}
?>