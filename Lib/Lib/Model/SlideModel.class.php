<?php
class SlideModel extends AdvModel {
	
	//自动验证
	protected $_validate=array(
		array('slide_name','require','名称必须填写！',0),
	);
	
	/* 自动完成 */
  protected $_auto = array(
		array('slide_name','trim',3,'function'),
	);
	
	// 通过ID查询对应的导航
	public function ff_find($id){
		$info = $this->field('*')->where( array('slide_id'=>array('eq', $id)) )->find();
		if($info){
			return $info;
		}
		$this->error = '没有查询到数据！';
		return false;
	}
	
	// 查询多个数据 不需要分页 则删除相关的判断
	public function ff_select_page($params, $where){
		//优先从缓存调用
		if($params['cache_name'] && $params['cache_time']){
			$infos = S($params['cache_name']);
			if($infos){
				return $infos;
			}
		}		
		$infos = $this->field($params['field'])->where($where)->limit($params['limit'])->order(trim($params['order'].' '.$params['sort']))->select();
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
		if( empty($data['slide_id']) ){
			$data['slide_id'] = $this->add();
			if(!$data['slide_id']){
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