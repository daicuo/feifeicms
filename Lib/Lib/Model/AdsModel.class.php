<?php 
class AdsModel extends AdvModel {
	protected $_validate=array(
		array('ads_name','require','广告标识必须填写！',1,'',1),
		array('ads_name','','该广告标识已经存在,请重新填写一个广告标识！',1,'unique',1),
	);
	
	protected $_auto=array(
		array('ads_name','trim',3,'function'),
		array('ads_content','trim',3,'function'),
		array('ads_content','stripslashes',3,'function'),
	);
	
	// 通过ID查询
	public function ff_find($id){
		$info = $this->field('*')->where( array('ads_id'=>array('eq', $id)) )->find();
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
		if( empty($data['ads_id']) ){
			$data['ads_id'] = $this->add();
			if(!$data['ads_id']){
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