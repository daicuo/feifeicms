<?php
class CardModel extends RelationModel {
	

	// 通过ID查询详情数据
	public function ff_find($field = '*', $where, $cache_name=false, $relation=true, $order=false){
		//md5处理KEY
		if($cache_name){
			$cache_name = md5(C('cache_foreach_prefix').$cache_name);
		}
		//优先缓存读取数据
		if( C('cache_page_shop') && $cache_name){
			$cache_info = S($cache_name);
			if($cache_info){
				return $cache_info;
			}
		}
		//数据库获取数据
		$info = $this->field($field)->where($where)->relation($relation)->order($order)->find();
		//dump($this->getLastSql());
		if($info){
			if( C('cache_page_shop') && $cache_name ){
				S($cache_name, $info, $cache_time);
			}
    	return $info;
    }
		$this->error = '数据不存在！';
		return false;
	}
	
	//删除
	public function ff_delete($array_ids){
		if(is_array($array_ids)){
			$array_ids = implode(',', $array_ids);
		}
		$this->where(array('card_id'=>array('in',$array_ids)))->delete();
	}
		
	//卡密充值
	public function ff_recharge($card_number, $card_uid){
		$where = array();
		$where['card_number'] = array('eq',$card_number);
		$where['card_status'] = array('eq',0);
		$info = $this->field('card_id,card_face')->where($where)->find();
		if(!$info){
			$this->error = '卡密错误或已充值！';
			return false;
		}
		//更新用户积分
		D("Score")->ff_user_score($card_uid, 6, intval($info['card_face']), 0, 0);
		//更新卡密状态
		$this->data(array('card_id'=>$info['card_id'],'card_status'=>1,'card_uid'=>$card_uid,'card_usetime'=>time()))->save();
		//正常返回卡密ID
		return $info['card_id'];
	}
}
?>