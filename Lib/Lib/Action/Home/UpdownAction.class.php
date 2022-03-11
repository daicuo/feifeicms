<?php
//顶踩模块
class UpdownAction extends HomeAction{
	
  public function vod(){
		$id = intval($_GET['id']);
		if ($id < 1) {
			$this->ajaxReturn(-1,'数据非法！',-1);
		}
		$this->show($id,trim($_GET['type']),'vod');
  }
	
  public function news(){
		$id = intval($_GET['id']);
		if ($id < 1) {
			$this->ajaxReturn(-1,'数据非法！',-1);
		}
		$this->show($id,trim($_GET['type']),'news');
  }	
	
	public function cm(){
		$id = intval($_GET['id']);
		if ($id < 1) {
			$this->ajaxReturn(-1,'数据非法！',-1);
		}
		$this->show($id,trim($_GET['type']),'cm');
  }
	
	public function forum(){
		$id = intval($_GET['id']);
		if ($id < 1) {
			$this->ajaxReturn(-1,'数据非法！',-1);
		}
		$this->show($id,trim($_GET['type']),'forum');
  }
	
	public function person(){
		$id = intval($_GET['id']);
		if ($id < 1) {
			$this->ajaxReturn(-1,'数据非法！',-1);
		}
		$this->show($id,trim($_GET['type']),'person');
  }
	
	public function show($id, $type, $model='vod'){
		$rs = D(ucfirst($model));
		if($type){
			$cookie = $model.'-updown-'.$id;
			if(isset($_COOKIE[$cookie])){
				$this->ajaxReturn('', '您已经参与过了！', 0);
			}
			if ('up' == $type){
				$rs->setInc($model.'_up',$model.'_id = '.$id);
				setcookie($cookie, 'true', time()+intval(C('user_second')));
			}elseif( 'down' == $type){
				$rs->setInc($model.'_down',$model.'_id = '.$id);
				setcookie($cookie, 'true', time()+intval(C('user_second')));
			}
		}
		$array = $rs->field(''.$model.'_up,'.$model.'_down')->find($id);
		if (!$array) {
			$array[$model.'_up'] = 0;
			$array[$model.'_down'] = 0;
		}
		$this->ajaxReturn(array('up'=>$array[$model.'_up'],'down'=>$array[$model.'_down']), "操作成功！", 1);		
	}	
}
?>