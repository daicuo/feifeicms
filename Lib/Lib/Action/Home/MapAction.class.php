<?php
class MapAction extends HomeAction{
	private $sid = 29;	
	
	public function search(){
		$params = ff_param_url();
		$info = $this->Lable_Search($params, $this->sid);
		$this->assign($info);
		$this->display($info['search_skin']);
  }
		
	public function _empty($action){
		$params = array();
		$params['id'] = !empty($_GET['id']) ? trim($_GET['id']):'rss';
		$params['page'] = !empty($_GET['p']) ? intval($_GET['p']) : 1;
		$params['limit'] = !empty($_GET['limit']) ? intval($_GET['limit']):30;
		$this->assign($params);
		$this->display('Map:'.str_replace('show','vod',$action).'_'.$params['id'],'utf-8','text/xml');
	}
}
?>