<?php
class NewsAction extends HomeAction{
	private $sid = 2;
	// TAG话题
	public function tags(){
		$params = ff_param_url();
		$info = $this->Lable_Tags($params, $this->sid);
		$this->assign($info);
		$this->display($info['tag_skin']);
	}
  // 资讯搜索
  public function search(){
		$params = ff_param_url();
		$info = $this->Lable_Search($params, $this->sid);
		$this->assign($info);
		$this->display($info['search_skin']);
  }
	// 资讯内容页 ID
  public function read(){
		$detail = $this->get_cache_detail('id');
		$this->assign($detail);
		$this->display($detail['news_skin_detail']);
  }
	// 资讯内容页 别名
  public function ename(){
		$detail = $this->get_cache_detail('ename');
		$this->assign($detail);
		$this->display($detail['news_skin_detail']);
  }
	// rss
  public function rss(){
		$detail = $this->get_cache_detail('id');
		$this->assign($detail);
		$this->display('News:rss','utf-8','text/xml');
  }
	// more
	public function _empty($action){
		if(is_numeric($_GET['id'])){
	 		$detail = $this->get_cache_detail('id');
		}else{
			$detail = $this->get_cache_detail('ename');
		}
		$this->assign($detail);
		$this->display('News:detail_'.$action);
	}
	// 从数据库获取数据
	private function get_cache_detail($action){
		//参数
		$params = array();
		$params['page'] = !empty($_GET['p']) ? intval($_GET['p']) : 1;
		$params['ajax'] = intval($_GET['ajax']);
		//条件
		$where = array();
		$where['news_status'] = array('eq', 1);
		if($action=='ename'){
			$params['id'] = htmlspecialchars($_GET['id']);
			$where['news_ename'] = array('eq', $params['id']);
		}else{
			$params['id'] = intval($_GET['id']);
			$where['news_id'] = array('eq', $params['id']);
		}
		//查库
		$info = D('News')->ff_find('*', $where, 'cache_page_news_'.$params['id'], true);
		if(!$info){
			$this->assign("jumpUrl",C('site_path'));
			$this->error('此文章已经删除！');
		}
		//解析标签
		return $this->Lable_News_Read($params, $info);
	}	
}
?>