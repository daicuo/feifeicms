 <?php
class SlideAction extends BaseAction{	

	// 显示幻灯
  public function show(){
		$params = array();
		$params['field'] = '*';
		$params['limit'] = false;
		$params['order'] = 'slide_cid asc,slide_oid';
		$params['sort'] = 'asc';
		$infos = D("Slide")->ff_select_page($params);
		$this->assign('list_slide',$infos);
		$this->display('./Public/system/slide_show.html');
  }
	
	// 添加与编辑幻灯
  public function add(){
		$rs = D("Slide");
		$id = intval($_GET['id']);
		if ($id) {
			$info = $rs->ff_find($_GET['id']);
			if(!$info){
				$this->error($rs->getError());
			}
			$info['tpltitle'] = '编辑';
		}else{
		  $info['slide_oid'] = $rs->max('slide_oid')+1;
			$info['slide_status'] = 1;
			$info['tpltitle'] = '添加';
		}
		$this->assign($info);
		$this->display('./Public/system/slide_add.html');
  }
	
	// 数据库操作
	public function update(){
		$data = D('Slide')->ff_update($_POST);
		if(!$data['slide_id']){
			$this->error(D('Slide')->getError());
		}
		$this->assign("jumpUrl",'?s=Admin-Slide-Show');
		$this->success('恭喜您，所有操作已完成！');
	}
	
	// 隐藏与显示幻灯
  public function status(){
		C('TOKEN_ON',false);
		$data = array();
		$data['slide_id'] = intval($_GET['id']);
		$data['slide_status'] = 1;
		if (intval($_GET['sid']) == 2) {
			$data['slide_status'] = 0;
		}
		D('Slide')->data($data)->save();
		$this->redirect('Admin-Slide/Show');
  }
	
	// 删除幻灯片
  public function del(){
		$where = array();
		$where['slide_id'] = array('eq',intval($_GET['id']));
		D("Slide")->ff_delete($where);
		$this->redirect('Admin-Slide/Show');
  }								
}
?>