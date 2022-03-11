<?php
class NavModel extends AdvModel {
	
	//自动验证
	protected $_validate=array(
		array('nav_title','require','标题必须填写！'),
	);
	
	/* 自动完成 */
  protected $_auto = array(
		array('nav_title','trim',3,'function'),
		array('nav_link','trim',3,'function'),
	);
	
	// 通过ID查询对应的导航
	public function ff_find($id){
		$info = $this->field('*')->where( array('nav_id'=>array('eq', $id)) )->find();
		if($info){
			return $info;
		}
		$this->error = '没有查询到数据！';
		return false;
	}
	
	// 分页查询多个数据 不需要分页 则删除相关的判断
	public function ff_select_page($params, $where){
		//优先从缓存调用
		if($params['cache_name'] && $params['cache_time']){
			$infos = S($params['cache_name']);
			if($infos){
				return $infos;
			}
		}		
		$infos = $this->field($params['field'])->where($where)->limit($params['limit'])->order(trim($params['order'].' '.$params['sort']))->select();
		if($infos_son = list_to_tree($infos, 'nav_id', 'nav_pid', 'nav_son')){
			$infos = $infos_son;
		}
		//是否写入数据缓存
		if($params['cache_name'] && $params['cache_time']){
			S($params['cache_name'], $infos, intval($params['cache_time']) );
		}
		//dump($this->getLastSql());
		if($infos){
			return $infos;
		}
		$this->error = '无符合条件的数据';
		return false;
	}
	
	// 新增或更新
	public function ff_update($data){
		// 创建安全数据对象TP
		$data = $this->create($data);
		if(false === $data){
			$this->error = $this->getError();
			return false;
		}
		/* 添加或新增行为 */
		if(empty($data['nav_id'])){
			$data['nav_id'] = $this->add();
			if(!$data['nav_id']){
				$this->error = $this->getError();
				return false;
			}
		} else {
			$status = $this->save();
			if(false === $status){
				$this->error = $this->getError();
				return false;
			}
		}
		return $data;
	}
	
	// 删除数据
	public function ff_delete($where){
		return $this->where($where)->delete();
	}
}
?>