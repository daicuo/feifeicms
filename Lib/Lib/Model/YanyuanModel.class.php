<?php
class YanyuanModel extends Model {
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
		//join查询同表需要映射字段
		if( $params['field'] == '*' ){
			$field = array();
			$field[0] = 'role.person_id as role_id,role.person_cid as role_cid,role.person_name as role_name,role.person_ename as role_ename,role.person_pic as role_pic,role.person_intro as role_intro,role.person_content as role_content,role.person_up as role_up,role.person_down as role_down,role.person_gold as role_gold,role.person_golder as role_golder,role.person_hits as role_hits,role.person_addtime as role_addtime';
			$field[1] = 'star.person_id as star_id,star.person_name as star_name,star.person_ename as star_ename,star.person_pic as star_pic';
			$field[2] = 'vod.vod_id,vod.vod_cid,vod.vod_name,vod.vod_ename,vod.vod_pic,vod.vod_actor,vod.vod_director';
			$field[3] = 'list.list_dir as vod_list_dir,list.list_name as vod_list_name';
			$params['field'] = implode(',',$field);
		}
		$join = array();
		$join[0] = 'as role Left JOIN ff_person as star ON role.person_father_id = star.person_id';
		$join[1] = 'Left JOIN ff_vod as vod ON role.person_object_id = vod.vod_id';
		$join[2] = 'Left JOIN ff_list as list ON vod.vod_cid = list.list_id';
		//$where = array();
		//$where['role.person_status'] = array('eq', 1);
		//$where['role.person_id'] = array('eq', $params['id']);
		// 分页变量动态定义
		if($params['page_id'] && $params['page_is']){
			$page = array();
			$page['records'] = $this->ff_select_count($where,$join);
			$page['totalpages'] = ceil($page['records']/$params['limit']);
			$page['currentpage'] = ff_page_max($params['page_p'], $page['totalpages']);
			// 使用GET全局变量传递分页参数 gx_page_default
			$_GET['ff_page_'.$params['page_id']] = $page;
		}else{
			$page['currentpage'] = NULL;
		}	
		$infos = D('Person')->field($params['field'])->where($where)->limit($params['limit'])->page($page['currentpage'])->order(trim($params['order'].' '.$params['sort']))->join($join)->select();
		//$infos = D('Person')->query('SELECT t1.person_name as role_name,t2.*,ff_vod.* FROM ff_person as t1 INNER JOIN ff_person AS t2 ON t1.person_id=t2.person_father_id Left JOIN ff_vod ON t1.person_object_id=ff_vod.vod_id');
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
	public function ff_select_count($where,$join){
		return D('Person')->where($where)->join($join)->count('role.person_id');
	}
}
?>