<?php
class RecordAction extends HomeAction{
	
	//观看记录?s=record-vod-sid-1 ?s=record-news-sid-2
	public function _empty($action){//vod news
	 	$record = array();
		$record['record_uid'] = D('User')->ff_islogin();
		$record['record_type']	= 1;
		$record['record_sid'] = !empty($_GET['sid']) ? intval($_GET['sid']) : 1;
		$json = json_decode(D('Record')->ff_json($record), true);
		$array = array();
		foreach($json['vod'] as $key=>$value){
			$array[] = $key;
		}
		$this->assign('vod_ids', implode(',',$array));
		$this->assign('vod_json', $json);
		$this->display('Record:'.$action);
	}	
	
	
	//获取记录json record-json-sid-1-type-1
  public function json(){
		$record = array();
		$record['record_uid'] = D('User')->ff_islogin();
		$record['record_sid'] = intval($_GET['sid']);
		$record['record_type']	= intval($_GET['type']);
		echo(D('Record')->ff_json($record));
  }
	
	//写入记录 
	public function post(){
		$record = array();
		$record['record_uid'] = D('User')->ff_islogin();
		$record['record_sid'] = intval($_GET['sid']);
		$record['record_type']	= intval($_GET['type']);
		$record['record_did']	= intval($_GET['did']);
		$record['record_did_sid']	= intval($_GET['did_sid']);
		$record['record_did_pid']	= intval($_GET['did_pid']);
		if($record['record_sid'] && $record['record_type'] && $record['record_did'] ){
			if($record['record_type'] > 1 && $record['record_uid'] < 1 && C("user_forum")){
				$this->ajaxReturn(0, '未登录', 5001);
				exit();
			}
			$status = D('Record')->ff_insert($record);
			$this->ajaxReturn($status, 'ok', 200);
		}else{
			$this->ajaxReturn('', 'fail', 5002);
		}
	}
	
	//删除记录
	public function delete(){
		$user_id = D('User')->ff_islogin();
		if($user_id){
			$where = array();
			$where['record_id'] = array('eq', intval($_GET['id']));
			$where['record_uid'] = array('eq', $user_id);
			if($info = D('Record')->where($where)->delete()){
				$this->ajaxReturn($info, "删除成功！", 200);
			}else{
				$this->ajaxReturn(0, D('Record')->getError(), 0);
			}
		}
		$this->ajaxReturn(0, '请先登录。', 0);
	}
}
?>