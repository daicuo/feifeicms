 <?php
class PlayerAction extends BaseAction{	
	// 显示分类
  public function show(){
		$maxOrder = D("Player")->max('player_order');
		$list= D("Player")->order('player_order asc,player_id asc')->select();
		$player = array();
		foreach($list as $key=>$value){
			if($value['player_status']){
				$player[$value['player_name_en']] = array(''.$value['player_name_zh'].'',''.$value['player_info'].'',''.$value['player_copyright'].'',''.$value['player_jiexi'].'');
			}
		}
		F('_feifeicms/player',$player);//缓存播放列表
		$this->assign('max_order',$maxOrder);	
		$this->assign('list_player',$list);	
		$this->display('./Public/system/player_show.html');
  }
	// 添加播放器
	public function insert(){
		$rs = D("Player");
		if ($rs->create()) {
			if ( false !==  $rs->add() ) {
				//$code = read_file('./Public/player/iframe.js');
				//write_file('./Public/player/'.$_POST['player_name_en'].'.js', $code);
				$this->assign("jumpUrl",'?s=Admin-Player-Show');
				$this->success('添加播放器来源成功！');
			}else{
				$this->error('添加播放器来源错误');
			}
		}else{
		   $this->error($rs->getError());
		}
	}	
	// 批量更新数据
  public function updateall(){
		$rs = D("Player");
		$array = $_POST;
		foreach($array['ids'] as $key=>$value){
			$data = array();
		  $data['player_order'] = intval($array['player_order'][$value]);
			$data['player_copyright'] = intval($array['player_copyright'][$value]);
			$data['player_name_zh'] = $array['player_name_zh'][$value];
			$data['player_name_en'] = $array['player_name_en'][$value];
			$data['player_info'] = $array['player_info'][$value];
			$data['player_jiexi'] = $array['player_jiexi'][$value];
			$rs->where('player_id = '.intval($value))->save($data);
		}
		$this->redirect('Admin-Player/Show');
  }
	// 隐藏与显示
  public function hide(){
		$where['player_id'] = intval($_GET['id']);
		$rs = D("Player");
		if (intval($_GET['status'])==2) {
			$rs->where($where)->setField('player_status',0);
		}else{
			$rs->where($where)->setField('player_status',1);
		}
		$this->redirect('Admin-Player/Show');
  }
	// 删除数据
  public function del(){
		$rs = D("Player");
		$where['player_id'] = $_GET['id'];
		$rs->where($where)->delete();
		$this->success('删除播放器成功！');
  }			
}
?>