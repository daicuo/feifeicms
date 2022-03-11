<?php
class SpecialAction extends BaseAction{	
	private $id;
  public function show(){
		//URL参数
		$admin = array();
		$admin['cid']= $_REQUEST['cid'];
		if( is_numeric($admin['cid']) ){
			$admin['cid']= ff_list_ids($admin['cid']);
		}		
		$admin['status'] = $_GET['status'];
		$admin['order'] = !empty($_GET['order'])?$_GET['order']:C('admin_order_type');
		$admin['sort'] = !empty($_GET['sort'])?$_GET['sort']:'desc';
		//跳转参数
		$urls = $admin;
		$urls['g'] = 'admin';
		$urls['m'] = 'special';
		$urls['a'] = 'show';
		$this->assign('urls',$urls);
		//查询参数
		$admin['field'] = '*';
		$admin['limit'] = 30;
		//分页参数
		$admin['page_is'] = true;
		$admin['page_id'] = 'special';
		$admin['page_p'] = !empty($_GET['p'])?intval($_GET['p']):1;
		//缓存参数
		$admin['cache_name'] = false;
		$admin['cache_time'] = false;
		//数据查询
		$list = ff_mysql_special(array_merge($admin,array('order'=>'special_'.$admin['order'])));
		foreach($list as $key=>$val){
			$list[$key]['list_url'] = '?s=Admin-Special-Show-cid-'.$list[$key]['special_cid'];
			$list[$key]['special_url'] = ff_url('special/read', array('id'=>$list[$key]['special_id']), true);
			$list[$key]['special_starsarr'] = admin_star_arr($list[$key]['special_stars']);
		}
		//拼装翻页参数
		$page = $_GET['ff_page_special'];
		$page['jump'] = './index.php?'.http_build_query(array_merge($urls,array('p'=>'FFLINK')));
		$page['pages'] = '共'.$page['records'].'篇专题&nbsp;当前:'.$page['currentpage'].'/'.$page['totalpages'].'页&nbsp;'.getpage($page['currentpage'],$page['totalpages'],8,$page['jump'],'pagego(\''.$page['jump'].'\','.$page['totalpages'].')');
		//变量附值
		$this->assign($urls);
		$this->assign($page);
		$this->assign('list',$list);
		//回跳URL
		session_start();
		$_SESSION['jumpurl'] = './index.php?'.http_build_query(array_merge($urls,array('p'=>$admin['page_p'])));
		//加载模板
		$this->display('./Public/system/special_show.html');
  }
	// 添加与编辑专题
  public function add(){
		$where = array();
		$where['special_id'] = intval($_GET['id']);
		if ($where['special_id']) {
			$array = D("Special")->ff_find('*', $where);
			$array['special_starsarr'] = admin_star_arr($array['special_stars']);
			foreach($array['Tag'] as $key=>$value){
				$tag[$value['tag_list']][$key] = $value['tag_name'];
			}
			$array['special_type'] = implode(',',$tag['special_type']);
			$array['tpltitle'] = '编辑';
		}else{
			$array['special_starsarr'] = admin_star_arr(1);
			$array['special_addtime'] = time();
			$array['tpltitle'] = '添加';
			$array['countvod'] = 0;
			$array['countnews'] = 0;
			$array["list_extend"]['type'] = C('special_type');		
		}
		$this->assign($array);
		$this->display('./Public/system/special_add.html');
  }
	//新增与更新数据
	public function update(){
		$rs = D('Special');
		$data = $rs->update($_POST);
		if(!$data){
			$this->error($rs->getError());
		}
		$this->id = $data['special_id'];
	}
	//更新数据缓存
	public function _after_update(){
		if(C('cache_page_special')){
			S(md5(C('cache_foreach_prefix').'cache_page_special_'.intval($_POST['special_id'])),NULL);
		}
		$this->assign("jumpUrl",'?s=Admin-Special-Show');
		$this->success('恭喜您，数据库、缓存、静态所有操作已完成！');
	}	
	// 隐藏与显示专题
  public function status(){
		$where = array();
		if(is_array($_REQUEST['ids'])){
			$where['special_id'] = array('in',implode(',', $_REQUEST['ids']));
		}else{
			$where['special_id'] = array('eq', $_REQUEST['id']);
		}
		D('Special')->where( $where )->setField('special_status', intval($_REQUEST['value']));
		$this->success('状态修改完成！');
  }
	// 删除专题
  public function del(){
		$this->delfile(intval($_GET['id']));
		$this->redirect('?s=Admin-Special-Show');
  }
	// 删除专题all
  public function delall(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要删除的专题！');
		}	
		$array = $_POST['ids'];
		foreach($array as $val){
			$this->delfile($val);
		}
		redirect($_SERVER['HTTP_REFERER']);
  }
	// 删除静态文件与图片
  public function delfile($id){
		//删除评论
		unset($where);
		$where['cm_cid'] = $id;
		$where['cm_sid'] = 3;
		D("Cm")->where($where)->delete();	
		unset($where);		
		//删除TAG
		$where['tag_id'] = $id;
		$where['tag_sid'] = 3;
		D("Tag")->where($where)->delete();
		unset($where);
		//删除图片
		$where['special_id'] = $id;
		$array = D("Special")->field('special_id,special_cid,special_pic,special_pic_bg,special_pic_slide,special_name')->where($where)->find();
		@unlink(ff_url_img($arr['special_pic']));
		@unlink(ff_url_img($arr['special_pic_bg']));
		@unlink(ff_url_img($arr['special_pic_slide']));
		unset($where);
		//删除内容ID
		$where['special_id'] = $id;
		D("Special")->where($where)->delete();
  }
	// Ajax设置星级
  public function ajaxstars(){
		$where['special_id'] = intval($_GET['id']);
		$data['special_stars'] = intval($_GET['stars']);
		$rs = D("Special");
		$rs->where($where)->save($data);
		echo('ok');
  }
	// 批量转移专题
  public function pestcid(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要转移的专题！');
		}	
		$cid = intval($_POST['pestcid']);
		if (ff_list_isson($cid)) {
			$data['special_cid'] = $cid;
			$where['special_id'] = array('in',$_POST['ids']);
			D("Special")->where($where)->save($data);
			redirect($_SESSION['jumpurl']);
		}else{
			$this->error('请选择当前大类下面的子分类！');		
		}
  }
	//批量处理拼音路径
	public function ename(){
		$minid = intval($_REQUEST['minid']);
		$maxlen = ff_default($_REQUEST['maxlen'],20);
		//参数
		$params = array();
		$params['field'] = 'special_id,special_name,special_ename';
		$params['limit'] = 50;
		$params['id_min'] = $minid;
		$params['order'] = 'special_id';
		$params['sort'] = 'asc';
		$params['cache_name'] = 'default';
		$params['cache_time'] = 0;
		$data = ff_mysql_special($params);
		if(!$data){
			$this->assign("jumpUrl","?s=Admin-Tool-Batch");
			$this->success('操作完成！');
		}
		foreach($data as $key=>$value){
			$special_id = $value['special_id'];
			$special_ename = ff_pinyin($value['special_name']);
			if(strlen($special_ename) > $maxlen){
				$special_ename = ff_pinyin($value['special_name'],true);
			}
			//唯一值处理
			$where = array();
			$where['special_id'] = array(array('lt',$special_id), array('gt',$special_id), 'and');
			$where['special_ename'] = array('eq',"".$special_ename."");
			$find = M('Vod')->field('special_id')->where($where)->find();
			if($find){
				$special_ename = $special_ename.$special_id;
			}
			//更新链接别名
			$vod = array();
			$vod['special_id'] = $special_id;
			$vod['special_ename'] = $special_ename;
			M('Special')->save($vod);
			echo('<p>'.$special_id.'('.$value['special_name'].')->'.$special_ename.'</p>');
			ob_flush();flush();
		}
		F('_feifeicms/enamespecial',$special_id);
		$this->redirect('Special/Ename', array('minid'=>$special_id,'maxlen'=>$maxlen), C('collect_time'),'<p>页面跳转中~</p>');
	}	
}
?>