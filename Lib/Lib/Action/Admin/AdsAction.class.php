<?php
class AdsAction extends BaseAction{
	// 广告列表
  public function show(){
		$params = array();
		$params['field'] = '*';
		$params['limit'] = false;
		$params['order'] = 'ads_id';
		$params['sort'] = 'desc';
		$infos = D("Ads")->ff_select_page($params);
		$this->assign('list_ads',$infos);
    $this->display('./Public/system/ads_show.html');
  }
	
	// 添加广告
  public function add(){
		$this->display('./Public/system/ads_add.html');
  }
	
	// 数据库操作
	public function update(){
		$data = D('Ads')->ff_update($_POST);
		if(!$data['ads_id']){
			$this->error(D('Ads')->getError());
		}
		write_file('./'.C('admin_ads_file').'/'.$data['ads_name'].'.js', t2js(stripslashes(trim($data['ads_content']))) );
		$this->assign("jumpUrl",'?s=Admin-Ads-Show');
		$this->success('恭喜您，所有操作已完成！');
	}
	
	// 批量操作
	public function all(){
	  $array = $_POST;
		$rs = D("Ads");			
		$data = array();
		foreach($array['ads_id'] as $value){
		  $data['ads_id'] = $array['ads_id'][$value];
			$data['ads_name'] = trim($array['ads_name'][$value]);
			$data['ads_content'] = stripslashes(trim($array['ads_content'][$value]));
			if(empty($data['ads_name'])){
			  $rs->where('ads_id='.$data['ads_id'])->delete();
			}else{
			  write_file('./'.C('admin_ads_file').'/'.$data['ads_name'].'.js',t2js($data['ads_content']));
			  $rs->save($data);
			}
		}				
		$this->success('广告数据更新成功！');
	}
	
	// 预览广告
  public function view(){
		$id = $_GET['id'];
		if ($id) {
			$ads = D("Ads")->ff_find($id);
			echo(ff_url_ads($ads['ads_name']));
		}
  }
		
	// 删除广告
  public function del(){
		$adsid = intval($_GET['id']);
		//先删除JS文件
		$ads = D("Ads")->ff_find($adsid);
		@unlink('./'.C('admin_ads_file').'/'.$ads['ads_name'].'.js');
		//再删除数据
		$where = array();
		$where['ads_id'] = array('eq',$adsid);
		D("Ads")->ff_delete($where);
		//
		$this->success('删除广告成功！');
  }					
}
?>