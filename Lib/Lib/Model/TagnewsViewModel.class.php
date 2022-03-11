<?php
//用于视图查询
class TagnewsViewModel extends ViewModel {
	//视图定义
	protected $viewFields = array (
		 'Tag'=>array('*'),
		 'News'=>array('*', '_on'=>'Tag.tag_id = News.news_id'),
		 'List'=>array('list_id','list_name','list_dir','list_skin', '_on'=>'News.news_cid = List.list_id'),
	);
	// 查询多个数据
	public function ff_select_page($params, $where){
		//优先从缓存调用数据及分页变量
		if($params['cache_name'] && $params['cache_time']){
			$infos = S($params['cache_name']);
			if($infos){
				if($params['page_id'] && $params['page_is']){
					$_GET['ff_page_'.$params['page_id']] = S($params['cache_name'].'_page');
				}
				return $infos;
			}
		}
		// 分页变量动态定义
		if($params['page_id'] && $params['page_is']){
			$page = array();
			$page['records'] = $this->ff_select_count($where,$params);
			$page['totalpages'] = ceil($page['records']/$params['limit']);
			$page['currentpage'] = ff_page_max($params['page_p'], $page['totalpages']);
			// 使用GET全局变量传递分页参数 gx_page_default
			$_GET['ff_page_'.$params['page_id']] = $page;
		}else{
			$page['currentpage'] = NULL;
		}	
		$infos = $this->field($params['field'])->where($where)->limit($params['limit'])->page($page['currentpage'])->order(trim($params['order'].' '.$params['sort']))->group('news_id')->select();
		//dump($this->getLastSql());
		// 是否写入数据缓存
		if($params['cache_name'] && $params['cache_time']){
			S($params['cache_name'], $infos, intval($params['cache_time']) );
			if($params['page_id'] && $params['page_is']){
				S($params['cache_name'].'_page', $page, intval($params['cache_time'])+1 );
			}
		}
		return $infos;
	}
	// 符合条件的统计
	public function ff_select_count($where,$params){
		if($params['group']){
			return $this->where($where)->count('distinct('.$params['group'].')');//'distinct(record_did)'
		}else{
			return $this->where($where)->count('news_id');
		}
	}
}
?>