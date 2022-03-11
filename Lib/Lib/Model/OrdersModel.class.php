<?php
class OrdersModel extends RelationModel {
	
	protected $_validate = array(
		array('order_sign', 'require', '订单编号错误，请重新下单！', 1)
	);
	
	protected $_auto = array(
		array('order_addtime','time',1,'function'),
		//array('order_paytime','time',2,'function'),
		//array('order_confirmtime','time',2,'function'),
	);
	
	// 新增或更新
	public function ff_update($data){
		// 创建安全数据对象TP
		$data = $this->create($data);
		if(false === $data){
			$this->error = $this->getError();
			return false;
		}
		/* 添加或修改行为 */
		if(empty($data['order_id'])){
			$data['order_id'] = $this->add();
			if(!$data['order_id']){
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
	
	//删除订单
	public function ff_delete($array_ids){
		if(is_array($array_ids)){
			$array_ids = implode(',', $array_ids);
		}
		$this->where(array('order_id'=>array('in',$array_ids)))->delete();
	}
	
	//根据订单号修改订单状态及更新用户影币
	public function ff_update_order($ordersign, $total_fee){
		$where = array();
		$where['order_sign'] = array("eq", $ordersign);
		//查询订单
		$info = $this->field('order_uid,order_ispay,order_money')->where($where)->find();
		//未付款状态用金额相同或金额大于
		if( ($info['order_ispay'] < 2) && ($info['order_money'] >= $total_fee) ){
			//更新订单状态
			$this->where($where)->save( array('order_status'=>1,'order_ispay'=>2,'order_shipping'=>1,'order_paytime'=>time(),'order_confirmtime'=>time()) );
			//更新用户积分
			D("Score")->ff_user_score($info['order_uid'], 1, intval($info['order_money']*C("user_pay_scale")), 0, 0);
		}
	}
}
?>