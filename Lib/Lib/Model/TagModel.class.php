<?php
class TagModel extends AdvModel {
	// 更新Tag 不采用关联模式(用于手动更新关联,删除之前的数据后重新写入)
	public function tag_update($id, $tag, $list){
		if($id && $tag){
			$tag_cid = array('vod_type'=>1,'vod_tag'=>2,'news_type'=>3,'news_tag'=>4,'special_type'=>5,'special_tag'=>6,'star_type'=>7,'star_tag'=>8,'role_type'=>9,'role_tag'=>10);
			$data = array();
			$data['tag_id'] = $id;
			$data['tag_list'] = $list;
			$this->where($data)->delete();
			$tags = explode(',',trim($tag));
			$tags = array_unique($tags);
			foreach($tags as $key=>$val){
				if($val){
					$data['tag_name'] = $val;
					$data['tag_cid'] = $tag_cid[$list];
					$data['tag_ename'] = ff_pinyin(trim($data['tag_name']));
					$this->data($data)->add();
				}
			}
		}
	}
	// 查询多个数据
	public function ff_select_page($params, $where){
		//优先从缓存调用数据及分页变量
		if($params['cache_name'] && $params['cache_time']){
			$infos = S($params['cache_name']);
			if($infos){
				return $infos;
			}
		}
		$infos = $this->field($params['field'].',count(tag_name) as tag_count')->where($where)->limit($params['limit'])->group($params['group'])->order(trim($params['order'].' '.$params['sort']))->select();
		// 是否写入数据缓存
		if($params['cache_name'] && $params['cache_time']){
			S($params['cache_name'], $infos, intval($params['cache_time']) );
		}
		//dump($this->getLastSql());
		return $infos;
	}
}
?>