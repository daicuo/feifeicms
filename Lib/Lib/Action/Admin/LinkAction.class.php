 <?php
class LinkAction extends BaseAction{	
	// 显示友情链接
  public function show(){
		$params = array();
		$params['field'] = '*';
		$params['limit'] = false;
		$params['order'] = 'link_type asc,link_order';
		$params['sort'] = 'asc';
		$infos = D("Link")->ff_select_page($params);
		$this->assign('list_link',$infos);
		$this->display('./Public/system/link_show.html');
  }
	// 添加友情链接
  public function add(){
		$id = intval($_GET['id']);
	  $rs = D("Link");
		if ($id) {
			$info = $rs->ff_find($id);
			$info['tpltitle'] = '编辑';
		}else{
		  $info['link_order'] = $rs->max('link_order')+1;
			$info['tpltitle'] = '添加';
		}
		$this->assign($info);	
		$this->display('./Public/system/link_add.html');
  }
	// 数据库操作
	public function update(){
		$data = D('Link')->ff_update($_POST);
		if(!$data['link_id']){
			$this->error(D('Link')->getError());
		}
		$this->assign("jumpUrl",'?s=Admin-Link-Show');
		$this->success('恭喜您，所有操作已完成！');
	}			
	// 批量更新
	public function updateall(){
	  $array = $_POST;
		$rs = D("Link");
		foreach($array['link_id'] as $value){
		  $data['link_id'] = $array['link_id'][$value];
			$data['link_name'] = trim($array['link_name'][$value]);
			$data['link_url'] = trim($array['link_url'][$value]);
			$data['link_logo'] = trim($array['link_logo'][$value]);
			$data['link_order'] = $array['link_order'][$value];
			$data['link_type'] = $array['link_type'][$value];
			if(empty($data['link_name'])){
			  $rs->where('link_id = '.intval($data['link_id']))->delete();
			}else{
			  $rs->save($data);
			}
		}
		$this->success('友情链接数据更新成功！');
	}
	// 删除友情链接
  public function del(){
		$where = array();
		$where['link_id'] = array('eq',intval($_GET['id']));
		D("Link")->ff_delete($where);
		redirect('?s=Admin-Link-Show');
  }					
}
?>