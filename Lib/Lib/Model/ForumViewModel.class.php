<?php
class ForumViewModel extends ViewModel {
	
	protected $viewFields = array (
		 'Forum'=>array('*'),
		 'User'=>array('user_id','user_name','user_face','_on'=>'Forum.forum_uid = User.user_id'),
	);
	
	// 查询多个数据
	public function ff_select_page($params, $where, $viewFields){
		//更新关联条件
		if($viewFields){
			$this->viewFields = $viewFields;
		}
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
			$page['records'] = $this->ff_select_count($where);
			$page['totalpages'] = ceil($page['records']/$params['limit']);
			$page['currentpage'] = ff_page_max($params['page_p'], $page['totalpages']);
			// 使用GET全局变量传递分页参数 gx_page_default
			$_GET['ff_page_'.$params['page_id']] = $page;
		}else{
			$page['currentpage'] = NULL;
		}	
		$infos = $this->field($params['field'])->where($where)->limit($params['limit'])->page($page['currentpage'])->order('forum_istop desc,'.trim($params['order'].' '.$params['sort']))->select();
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
	public function ff_select_count($where){
		return $this->where($where)->count('forum_id');
	}
}
?>