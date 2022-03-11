<?php
class RecordModel extends RelationModel {
	
	protected $_auto = array(
		array('record_time','time',3,'function'),
	);
	
	// 获取记录json格式
	public function ff_json($data){
		//已登录用户从数据库读取 未登录从COOKIE
		if($data['record_uid']){
			$cache_name = 'ff_record_json';
			$cache_time = C('cache_foreach');
			//优先从缓存获取
			if($cache_time && $cache_name){
				$cache_info = S($cache_name);
			}
			//未命中缓存数据库获取
			if(empty($cache_info)){
				$where = array();
				$where['record_uid'] = array('eq',$data['record_uid']);
				$where['record_sid'] = array('eq',$data['record_sid']);
				$where['record_type']	= array('eq',$data['record_type']);
				$cache_info = $this->where($where)->limit(intval(C('ui_record')))->order('record_time desc')->select();
				krsort($cache_info);//与cookie记录的顺序保持一致 先看的在前面 由前台模板处理
				//写入缓存
				if( $cache_info && $cache_time && $cache_name ){
					S($cache_name, $cache_info , $cache_time);
				}
			}
			//格式化
			foreach($cache_info as $key=>$value){
				$info[$value['record_sid']][$value['record_did']]=array('type'=>$value['record_type'],'sid'=>$value['record_did_sid'],'pid'=>$value['record_did_pid']);
			}
		}else{
			$cookie_old = unserialize(cookie('ff-record'));//反解cookie数组
			$cookie_old = array_slice($cookie_old,-intval(C('ui_record')));//取最后多少条
			$info = array();
			foreach($cookie_old as $key=>$value){
				list($model_id,$detail_id) = explode('-',$key);
				list($sid,$pid) = explode('-',$value);
				$info[$model_id][$detail_id] = array('type'=>1,'sid'=>$sid,'pid'=>$pid);
			}
		}
		//json数组格式
		$array['vod'] = $info[1];
		$array['news'] = $info[2];
		return json_encode($array);
	}
	
	// 写入记录
	public function ff_insert($data){
		//已登录用户写入数据库 未登录写入COOKIE
		if($data['record_uid']){
			$data = $this->create($data);
			if(false === $data){
				$this->error = $this->getError();
				return false;
			}
			$where = array();
			$where['record_uid'] = array('eq',$data['record_uid']);
			$where['record_sid'] = array('eq',$data['record_sid']);
			$where['record_type']	= array('eq',$data['record_type']);
			$where['record_did']	= array('eq',$data['record_did']);
			//更新记录
			$info = $this->where($where)->save($data);
			//无记录则新增
			if(!$info){
				$info = $this->add($data);
			}
			return $info;
		}else{//cookie['1-134']='1-31';modelid-detailid=sid-pid
			$cookie_old = unserialize(cookie('ff-record'));
			$cookie_new = array();
			$cookie_new[$data['record_sid'].'-'.$data['record_did']] = intval($data['record_did_sid']).'-'.intval($data['record_did_pid']);
			if( $cookie_old ){
				$cookie_new = array_merge($cookie_old,$cookie_new);
			}
			cookie('ff-record',serialize(array_slice($cookie_new,-intval(C('ui_record')))), 2592000);
			return 'cookie';
		}
	}
	
	public function ff_delete($array_ids){
		if(is_array($array_ids)){
			$array_ids = implode(',', $array_ids);
		}
		$this->where(array('record_id'=>array('in',$array_ids)))->delete();
	}
	
	// 通过ID查询详情数据
	public function ff_find($field = '*', $where, $cache_name=false, $cache_time=0, $relation=true, $order=false){
		//md5处理KEY
		if($cache_name){
			$cache_name = md5(C('cache_foreach_prefix').$cache_name);
		}
		//优先缓存读取数据
		if( $cache_time && $cache_name){
			$cache_info = S($cache_name);
			if($cache_info){
				return $cache_info;
			}
		}
		//数据库获取数据
		$info = $this->field($field)->where($where)->order($order)->find();
		//dump($this->getLastSql());
		if($info){
			if( $cache_time && $cache_name ){
				S($cache_name, $info, $cache_time);
			}
    	return $info;
    }
		$this->error = '数据不存在！';
		return false;
	}
}
?>