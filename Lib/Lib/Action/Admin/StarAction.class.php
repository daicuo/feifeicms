<?php
class StarAction extends BaseAction{	
	private $id;
  public function show(){
		$admin = array();
		$admin['ids']= $_REQUEST['ids'];
		$admin['cid']= $_REQUEST['cid'];
		if( is_numeric($admin['cid']) ){
			$admin['cid']= ff_list_ids($admin['cid']);
		}		
		$admin['status'] = $_REQUEST['status'];
		$admin['stars'] = $_REQUEST['stars'];
		$admin['pic'] = $_REQUEST['pic'];
		$admin['wd'] = urldecode(trim($_REQUEST['wd']));
		$admin['order'] = !empty($_GET['order'])?$_GET['order']:C('admin_order_type');
		$admin['sort'] = !empty($_GET['sort'])?$_GET['sort']:'desc';
		// 跳转参数
		$urls = $admin;
		$urls['g'] = 'admin';
		$urls['m'] = 'star';
		$urls['a'] = 'show';
		$this->assign('urls',$urls);
		// 查询参数
		$admin['field'] = '*';
		$admin['limit'] = 30;
		// 分页参数
		$admin['page_is'] = true;
		$admin['page_id'] = 'item';
		$admin['page_p'] = !empty($_GET['p'])?intval($_GET['p']):1;
		// 缓存参数
		$admin['cache_name'] = false;
		$admin['cache_time'] = false;
		// 数据查询
		$list = ff_mysql_star(array_merge($admin,array('order'=>'person_'.$admin['order'])));
		foreach($list as $key=>$val){
		  $list[$key]['list_url'] = '?s=Admin-Star-Show-cid-'.$list[$key]['person_cid'];
			$list[$key]['list_stars'] = admin_star_arr($list[$key]['person_stars']);
		}
		// 拼装翻页参数
		$page = $_GET['ff_page_item'];
		$page['jump'] = './index.php?'.http_build_query(array_merge($urls,array('p'=>'FFLINK')));
		$page['pages'] = '共'.$page['records'].'个明星&nbsp;当前:'.$page['currentpage'].'/'.$page['totalpages'].'页&nbsp;'.getpage($page['currentpage'],$page['totalpages'],8,$page['jump'],'pagego(\''.$page['jump'].'\','.$page['totalpages'].')');
		//变量附值
		$this->assign($urls);
		$this->assign($page);
		$this->assign('list',$list);
		//回跳URL
		session_start();
		$_SESSION['jumpurl'] = './index.php?'.http_build_query(array_merge($urls,array('p'=>$admin['page_p'])));
		//加载模板
		$this->display('./Public/system/star_show.html');
  }
	// 添加编辑
  public function add(){
		$id = intval($_GET['id']);
		if($id){
			$array = D("Person")->ff_find('*', array('person_id'=>array('eq',$id)) );
			$array['person_tplname'] = '编辑';
			$_SESSION['jumpurl'] = $_SERVER['HTTP_REFERER'];
		}else{
			$array["list_extend"]['type'] = C('star_type');	
		  $array['person_cid'] = cookie('person_cid');
			$array['person_addtime'] = time();
			$array['person_tplname'] = '添加';
		}
		$this->assign($array);
		$this->display('./Public/system/star_add.html');
  }
	// 新增与更新数据
	public function update(){
		$data = D('Person')->ff_update($_POST);
		if(!$data){
			$this->error(D('Person')->getError());
		}
		$this->id = $data['person_id'];
	}
	// 后置操作
	public function _after_update(){
		$person_id = $this->id;
		if($person_id){
			//记录最后的主分类ID
			cookie('person_cid', intval($_POST["person_cid"]) );
			//删除数据缓存
			if(C('cache_page_person')){
				S(md5(C('cache_foreach_prefix').'cache_page_person_'.$person_id), NULL);
			}
			//跳转网页
			$this->assign("jumpUrl",$_SESSION['jumpurl']);
			$this->success('恭喜您，操作已完成！');
		}else{
			$this->error('操作完成，附加操作不做处理！');
		}		
	}
	// 展开关闭筛选条件
	public function select(){
		if($_GET['id'] == 'set'){
			setcookie('star_select', 1, 0);
			echo('1');
		}else if($_GET['id'] == 'null'){
			setcookie('star_select', NULL);
			echo('0');
		}else{
			if(isset($_COOKIE['star_select'])){
				echo('1');
			}else{
				echo('0');
			}
		}
	}
	// Ajax设置星级
  public function ajaxstars(){
		D("Person")->where('person_id='.intval($_GET['id']))->save(array('person_stars'=>intval($_GET['stars'])));		
		exit('ok');
  }
	// 设置状态
  public function status(){
		$where = array();
		if(is_array($_REQUEST['ids'])){
			$where['person_id'] = array('in',implode(',', $_REQUEST['ids']));
		}else{
			$where['person_id'] = array('eq', $_REQUEST['id']);
		}
		D('Person')->where( $where )->setField('person_status', intval($_REQUEST['value']));
		$this->success('状态修改完成！');
		//redirect($_SESSION['jumpurl']);
  }	
	// 删除
  public function del(){
		$this->delfile($_GET['id']);
		redirect($_SESSION['jumpurl']);
  }
  public function delall(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要删除的数据！');
		}	
		$array = $_POST['ids'];
		foreach($array as $val){
			$this->delfile($val);
		}
		redirect($_SESSION['jumpurl']);
  }
	// 删除图片、评论、多分类等
  public function delfile($id){
		//删除评论
		unset($where);
		$where['cm_cid'] = $id;
		$where['cm_sid'] = 8;
		D("Cm")->where($where)->delete();	
		unset($where);
		//删除图片
		$where['person_id'] = $id;
		$array = D("Person")->field('person_id,person_cid,person_pic,person_pic_bg,person_pic_slide,person_name')->where($where)->find();
		@unlink(ff_url_img($arr['person_pic']));
		@unlink(ff_url_img($arr['person_pic_bg']));
		@unlink(ff_url_img($arr['person_pic_slide']));
		unset($where);				
		//删除内容与角色
		$search = array();
		$search['person_id'] = $id;
		$search['person_father_id'] = $id;
		$search['_logic'] = 'or';
		$where['_complex'] = $search;
		D("Person")->where($where)->delete();
		unset($where);
  }
	// 批量转移
  public function pestcid(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要转移的数据！');
		}	
		$cid = intval($_POST['pestcid']);
		if (ff_list_isson($cid)) {
			$data['person_cid'] = $cid;
			$where['person_id'] = array('in',$_POST['ids']);
			D("Person")->where($where)->save($data);
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
		$params['id_min'] = $minid;
		$params['field'] = 'person_id,person_name,person_ename';
		$params['limit'] = 50;
		$params['cache_time'] = 0;
		$params['sid'] = 8;
		$params['order'] = 'person_id';
		$params['sort'] = 'asc';
		$data = ff_mysql_star($params);
		if(!$data){
			$this->assign("jumpUrl","?s=Admin-Tool-Batch");
			$this->success('操作完成！');
		}
		foreach($data as $key=>$value){
			$person_id = $value['person_id'];
			$person_ename = ff_pinyin($value['person_name']);
			if(strlen($person_ename) > $maxlen){
				$person_ename = ff_pinyin($value['person_name'],true);
			}
			//唯一值处理
			$where = array();
			$where['person_id'] = array(array('lt',$person_id), array('gt',$person_id), 'and');
			$where['person_ename'] = array('eq',"".$person_ename."");
			$find = M('Person')->field('person_id')->where($where)->find();
			if($find){
				$person_ename = $person_ename.$person_id;
			}
			//更新链接别名
			$data = array();
			$data['person_id'] = $person_id;
			$data['person_ename'] = $person_ename;
			M('Person')->save($data);
			echo('<p>'.$person_id.'('.$value['person_name'].')->'.$person_ename.'</p>');
			ob_flush();flush();
		}
		F('_feifeicms/enamestar',$person_id);
		$this->redirect('Star/Ename', array('minid'=>$person_id,'maxlen'=>$maxlen), C('collect_time'),'<p>页面跳转中~</p>');
	}	
	//搜索联想
	public function api(){
		$params = array();
		$params['name'] = htmlspecialchars(urldecode(trim($_REQUEST['name'])));
		$params['field'] = 'person_id,person_name';
		$params['limit'] = !empty($_GET['limit'])?intval($_GET['limit']):10;
		$params['order'] = 'person_id';
		$params['sort'] = 'desc';
		$params['cache_name'] = false;
		$params['cache_time']= false;
		$infos = ff_mysql_star($params);
		if($infos){
			$this->ajaxReturn($infos,"ok",1);
		}else{
			$this->ajaxReturn($infos,"error",0);
		}
	}			
}
?>