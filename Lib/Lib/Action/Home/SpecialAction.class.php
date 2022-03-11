<?php
class SpecialAction extends HomeAction{
	private $sid = 3;
	// 影视搜索 get方式
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
		$this->display($detail['special_skin']);
  }
	// 按别名读取影片
	public function ename(){
		$detail = $this->get_cache_detail('ename');
		$this->assign($detail);
		$this->display($detail['special_skin']);
	}
	// more
	public function _empty($action){
		if(is_numeric($_GET['id'])){
	 		$detail = $this->get_cache_detail('id');
		}else{
			$detail = $this->get_cache_detail('ename');
		}
		$this->assign($detail);
		$this->display('Special:detail_'.$action);
	}	
	// 从数据库获取内容数据
	private function get_cache_detail($action='id'){
		//参数
		$params = array();
		//条件
		$where = array();
		$where['special_status'] = array('eq', 1);
		if($action=='ename'){
			$params['id'] = htmlspecialchars($_GET['id']);
			$where['special_ename'] = array('eq', $params['id']);
		}else{
			$params['id'] = intval($_REQUEST['id']);
			$where['special_id'] = array('eq', $params['id']);
		}
		//查库
		$info = D('Special')->ff_find('*', $where, 'cache_page_special_'.$params['id'], true);
		if(!$info){
			$this->assign("jumpUrl",C('site_path'));
			$this->error('此专题已经删除，请选择观看其它节目！');
		}
		//解析标签
		return $this->Lable_Special_Read($info);
	}
}
?>