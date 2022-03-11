<?php
class NavAction extends BaseAction{
	
	public function show(){
		$params = array();
		$params['field'] = '*';
		$params['limit'] = false;
		$params['order'] = 'nav_pid asc,nav_oid';
		$params['sort'] = 'asc';
		$infos = D("Nav")->ff_select_page($params);
		$this->assign('nav_tree', $infos );
		$this->display('./Public/system/nav_show.html');
	}
	
	public function add(){
		$rs = D('Nav');
		if($_GET['id']){
			$info = $rs->ff_find($_GET['id']);
			if(!$info){
				$this->error($rs->getError());
			}
		}else{
			$info['nav_oid'] = $rs->max('nav_oid')+1;
		}
		$this->assign('nav_pid',intval($_GET['pid']));
		$this->assign($info);
		$this->display('./Public/system/nav_add.html');
	}
	
	public function update(){
		$data = D('Nav')->ff_update($_POST);
		if(!$data['nav_id']){
			$this->error(D('Nav')->getError());
		}
		$this->assign("jumpUrl",'?s=Admin-Nav-Show');
		$this->success('恭喜您，所有操作已完成！');
	}
	
	public function all(){
		C('TOKEN_ON',false);
		$rs = D('Nav');
		$post = $_POST;
		// 删除与编辑
		foreach($post['nav_oid'] as $key=>$value){
			if($post['nav_delid'][$key]){
				$rs->ff_delete('nav_id='.intval($post['nav_delid'][$key]));
			}else{
				$data = array();
				$data['nav_id'] = $key;
				$data['nav_oid'] = $value;
				$data['nav_title'] = $post['nav_title'][$key];
				$data['nav_link'] = $post['nav_link'][$key];
				if($post['nav_status'][$key]){
					$data['nav_status'] = 1;
				}else{
					$data['nav_status'] = 0;
				}
				$rs->ff_update($data);
			}
		}
		//redirect('?s=Admin-Nav-Show');
		$this->assign("jumpUrl",'?s=Admin-Nav-Show');
		$this->success('恭喜您，所有操作已完成！');
	}
}
?>