<?php
class VodAction extends HomeAction{
	private $sid = 1;
	// TAG话题
	public function tags(){
		$params = ff_param_url();
		$info = $this->Lable_Tags($params, $this->sid);
		$this->assign($info);
		$this->display($info['tag_skin']);
	}
	// 影视搜索 get方式
	public function search(){
		$params = ff_param_url();
		$info = $this->Lable_Search($params, $this->sid);
		$this->assign($info);
		$this->display($info['search_skin']);
  }
	// 按ID读取影片
  public function read(){
		$detail = $this->get_cache_detail('id');
		$this->assign($detail);
		$this->display($detail['vod_skin_detail']);
  }
	// 按别名读取影片
	public function ename(){
		$detail = $this->get_cache_detail('ename');
		$this->assign($detail);
		$this->display($detail['vod_skin_detail']);
	}	
	// 影片播放页
  public function play(){
		$detail = $this->Lable_Vod_Play($this->get_cache_detail('id'), array('id'=>intval($_GET['id']), 'sid'=>intval($_GET['sid']), 'pid'=>intval($_GET['pid'])));
		$this->assign($detail);
		$this->display($detail['vod_skin_play']);
  }
	// 影片播放页拼音
  public function eplay(){
		$detail = $this->get_cache_detail('ename');
		$detail = $this->Lable_Vod_Play($detail, array('id'=>$detail['vod_id'],'sid'=>intval($_GET['sid']),'pid'=>intval($_GET['pid'])));
		$this->assign($detail);
		$this->display($detail['vod_skin_play']);
  }
	// 单个影片RSS
  public function rss(){
		$detail = $this->get_cache_detail('id');
		$this->assign($detail);
		$this->display('Vod:rss','utf-8','text/xml');
  }
	// VIP播放器
  public function vip(){
		if(!$_SERVER['HTTP_REFERER']){exit('not vip');}
		$detail = $this->Lable_Vod_Play_Vip($this->get_cache_detail('id'), 
		array('id'=>intval($_GET['id']), 'sid'=>intval($_GET['sid']), 'pid'=>intval($_GET['pid'])));
		//试看结束提示 action= trysee|ispay|play
		if($_GET['action'] == 'trysee'){
			$this->assign($detail);
			$this->display('Vip:trysee');
			exit();
		}
		if($detail['play_ispay'] || $detail['play_price']){
			//用户登录验证
			$detail['user_id'] = D('User')->ff_islogin();
			if($detail['user_id'] < 1){
				$detail['play_status'] = 500;//未登录
			}else{
				$user = D("User")->ff_find('user_score,user_deadtime', array('user_status'=>1, 'user_id'=>array('eq',$detail['user_id'])), false, false, false);
				if(!$user){
					$detail['play_status'] = 501;//用户未找到
				}else{
					$detail['user_score'] = $user['user_score'];
					$detail['user_deadtime'] = $user['user_deadtime'];
				}
			}
			//VIP包月权限
			if($user && $detail['play_ispay']){
				if(time() > $user['user_deadtime']){
					$detail['play_status'] = 502;//vip到期 提示
				}
			}
			//单片点播权限
			if($user && $detail['play_price']){
				if(!D('Score')->ff_count_score($detail['user_id'], 22, 1, $detail['play_id'])){
					$detail['play_status'] = 503;//未查询到购买记录
				}
			}
		}
		//状态提示
		if($detail['play_status']==500 || $detail['play_status']==501){
			$detail['play_tips'] = $this->fetch('Vip:login');
		}elseif($detail['play_status']==502){
			$detail['play_tips'] = $this->fetch('Vip:ispay');
		}elseif($detail['play_status']==503){
			$detail['play_tips'] = str_replace('{vod_price}',$detail['play_price'],$this->fetch('Vip:price'));
		}else{
			$detail['play_tips'] = '播放正常';
		}
		//单片付费点播扣点处理
		if($_GET['action'] == "ispay" && $detail['play_status']==503){
			if($user['user_score'] < $detail['play_price']){
				$detail['play_status'] = 504;//用户影币不足提示充值
				$detail['play_tips'] = $this->fetch('Vip:short');
			}else{
				D('Score')->ff_user_score($detail['user_id'], 22, -abs($detail['play_price']), 1, $detail['play_id']);
				$detail['play_status'] = 200;//扣除用户影币成功就改为可播放状态
				$detail['play_tips'] = '播放正常';
			}
		}
		//直接输出或ajax返回
		if($_GET['action'] == "ispay"){
			$this->ajaxReturn($detail['play_price'], $detail['play_tips'], $detail['play_status']);
		}else{
			$this->assign($detail);
			$this->display('Vod:play_vip');
		}
  }	
	// more
	public function _empty($action){
		if(is_numeric($_GET['id'])){
	 		$detail = $this->get_cache_detail('id');
		}else{
			$detail = $this->get_cache_detail('ename');
		}
		$this->assign($detail);
		$this->display('Vod:detail_'.$action);
	}
	// 从数据库获取内容数据
	private function get_cache_detail($action='id'){
		//参数
		$params = array();
		//条件
		$where = array();
		$where['vod_status'] = array('eq', 1);
		if($action=='ename'){
			$params['id'] = htmlspecialchars($_GET['id']);
			$where['vod_ename'] = array('eq', $params['id']);
		}else{
			$params['id'] = intval($_GET['id']);
			$where['vod_id'] = array('eq', $params['id']);
		}
		//查库
		$info = D('Vod')->ff_find('*', $where, 'cache_page_vod_'.$params['id'], true);
		if(!$info){
			$this->assign("jumpUrl",C('site_path'));
			$this->error('此影片已经删除，请选择观看其它节目！');
		}
		//解析标签
		return $this->Lable_Vod_Read($info);
	}
}
?>