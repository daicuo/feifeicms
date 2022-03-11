<?php
class ScoreModel extends RelationModel {
	
	protected $_validate = array(
	
	);
	
	protected $_auto = array(
		array('score_time','time',1,'function'),
	);
	
	//封装快捷方法更新影币记录及用户影币
	public function ff_user_score($uid, $type, $ext, $sid=0, $did=0){
		$info = $this->ff_insert($uid, $type, $ext, $sid, $did);
		if($info['score_id']){
			$user_score = $this->ff_sum('score_uid='.$uid);
			return D('User')->where('user_id='.$uid)->setField('user_score',$user_score);
		}
		return false;
	}
	//新增影币日志记录
	public function ff_insert($uid, $type, $ext, $sid=0, $did=0){
		$data = array();
		$data['score_uid'] = $uid;
		$data['score_type'] = $type;
		$data['score_ext'] = $ext;
		$data['score_sid'] = $sid;
		$data['score_did'] = $did;
		$data['score_addtime'] = time();
		//ADD
		$data['score_id'] = $this->add($data);
		if(!$data['score_id']){
			$this->error = $this->getError();
			return false;
		}
		return $data;
	}
	//根据日志统计影币
	public function ff_sum($where, $field='score_ext'){
		return $this->where($where)->sum($field);
	}
	//封装快捷方法更新影币记录及VIP到期时间
	public function ff_user_deadtime($user_id, $user_deadtime, $user_score, $ext){
		if(abs($ext) < intval(C('user_pay_vip_small'))){
			$this->error = '续费时长不得低于'.intval(C('user_pay_vip_small')).'天！';
			return 503;
		}
		$ext_conf = intval(C('user_pay_vip_ext'));
		$ext_total = $ext * $ext_conf;
		if($ext < 0){
			//扣除VIP期限退还相应的影币
			$score_type = 5;
			$ext_total = abs($ext_total);
			$ext_day = '-'.abs($ext).' day';
		}else{
			//影币不足请先充值
			if($user_score < $ext_total){
				$this->error = $ext_total;
				return 501;
			}
			$score_type = 21;
			$ext_total = -$ext_total;
			$ext_day = '+'.abs($ext).' day';
		}
		//更新影币记录 $this->setDec('user_score','score_uid='.$user_id, $ext_total);
		$info = $this->ff_insert($user_id, $score_type, $ext_total, 0, 0);
		//更新用户信息表
		if($info['score_id']){
			$data = array();
			$data['user_id'] = $user_id;
			$data['user_score'] = $this->ff_sum('score_uid='.$user_id);
			$data['user_deadtime'] = strtotime($ext_day, $user_deadtime);
			//VIP过期时间小于操作时间时 以操作时间开始计算
			if($score_type == 21 && ($user_deadtime < time())){
				$data['user_deadtime'] = strtotime($ext_day, time());
			}
			if(D('User')->save($data)){
				return 200;
			}else{
				$this->error = '更新用户信息出错';
				return 502;
			}
		}
		return 500;
	}
	//统计购买记录
	public function ff_count_score($score_uid, $score_type=22, $score_sid=1, $score_did=0){
		$where = array();
		$where['score_uid'] = $score_uid;
		$where['score_type'] = $score_type;
		$where['score_sid'] = $score_sid;
		$where['score_did'] = $score_did;
		return $this->where($where)->count('score_id');
	}
}
?>