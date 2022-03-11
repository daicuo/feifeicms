<?php
class VodAction extends BaseAction{
	private $id;
	// 影片列表
  public function show(){
		$admin = array();
		$admin['ids']= $_REQUEST['ids'];
		$admin['cid']= $_REQUEST['cid'];
		if( is_numeric($admin['cid']) ){
			$admin['cid']= ff_list_ids($admin['cid']);
		}
		$admin['isend'] = $_REQUEST['isend'];
		$admin['status'] = $_REQUEST['status'];
		$admin['ispay'] = $_REQUEST['ispay'];
		$admin['price'] = $_REQUEST['price'];
		$admin['trysee'] = $_REQUEST['trysee'];
		$admin['stars'] = $_REQUEST['stars'];
		$admin['state'] = $_REQUEST['state'];
		$admin['area'] = $_REQUEST['area'];
		$admin['series'] = $_REQUEST['series'];
		$admin['version'] = $_REQUEST['version'];
		$admin['weekday'] = $_REQUEST['weekday'];
		$admin['pic'] = $_REQUEST['pic'];
		$admin['play'] = $_REQUEST['play'];
		$admin['url'] = $_REQUEST['url'];
		$admin['tag_list'] = $_REQUEST['tag_list'];
		$admin['tag_name'] = urldecode($_REQUEST['tag_name']);
		$admin['wd'] = urldecode(trim($_REQUEST['wd']));
		$admin['inputer'] = $_REQUEST['inputer'];
		$admin['scenario'] = $_REQUEST['scenario'];
		$admin['like_length'] = $_REQUEST['like_length'];//3.9同名参数 空|0|2
		$admin['lines'] = $_REQUEST['lines'];//4.1
		$admin['douban'] = $_REQUEST['douban'];//4.1
		$admin['limit'] = !empty($_GET['limit'])?intval($_GET['limit']):30;
		$admin['order'] = !empty($_GET['order'])?$_GET['order']:C('admin_order_type');
		$admin['sort'] = !empty($_GET['sort'])?$_GET['sort']:'desc';
		// 跳转参数
		$urls = $admin;
		$urls['g'] = 'admin';
		$urls['m'] = 'vod';
		$urls['a'] = 'show';
		$this->assign('urls',$urls);
		// 查询参数
		$admin['field'] = '*';
		// 分页参数
		$admin['page_is'] = true;
		$admin['page_id'] = 'item';
		$admin['page_p'] = !empty($_GET['p'])?intval($_GET['p']):1;
		// 缓存参数
		$admin['cache_name'] = false;
		$admin['cache_time'] = false;
		// 数据查询
		$list = ff_mysql_vod(array_merge($admin,array('order'=>'vod_'.$admin['order'])));
		foreach($list as $key=>$val){
		  $list[$key]['list_url'] = '?s=Admin-Vod-Show-cid-'.$list[$key]['vod_cid'];
			$list[$key]['list_stars'] = admin_star_arr($list[$key]['vod_stars']);
			$list[$key]['vod_url'] = ff_url_read_vod($list[$key]['list_id'],$list[$key]['list_dir'],$list[$key]['vod_id'],$list[$key]['vod_ename'],$list[$key]['jumpurl']);
		}
		// 拼装翻页参数
		$page = $_GET['ff_page_item'];
		$page['jump'] = './index.php?'.http_build_query(array_merge($urls,array('p'=>'FFLINK')));
		$page['pages'] = '共'.$page['records'].'个视频&nbsp;当前:'.$page['currentpage'].'/'.$page['totalpages'].'页&nbsp;'.getpage($page['currentpage'],$page['totalpages'],8,$page['jump'],'pagego(\''.$page['jump'].'\','.$page['totalpages'].')');
		//变量附值
		$this->assign($urls);
		$this->assign($page);
		$this->assign('list',$list);
		//回跳URL
		session_start();
		$_SESSION['jumpurl'] = './index.php?'.http_build_query(array_merge($urls,array('p'=>$admin['page_p'])));
		//加载模板
		$this->display('./Public/system/vod_show.html');
  }
	// 添加编辑影片
  public function add(){
		$vod_id = intval($_GET['id']);
		if($vod_id){
			$where = array();
      $where['vod_id'] = $vod_id;
			$array = D('Vod')->ff_find('*', $where);
			foreach($array['Tag'] as $key=>$value){
				$tag[$value['tag_list']][$key] = $value['tag_name'];
			}
			$array['vod_type'] = implode(',',$tag['vod_type']);
			$array['vod_keywords'] = implode(',',$tag['vod_tag']);
			$array['vod_tplname'] = '编辑';
			$_SESSION['jumpurl'] = $_SERVER['HTTP_REFERER'];
		}else{
		  $array['vod_cid'] = cookie('vod_cid');
			$array['vod_continu'] = 0;
			$array['vod_addtime'] = time();
			$array['vod_inputer'] = $_SESSION['admin_name'];
			$array['vod_tplname'] = '添加';
			//默认配置
			$array["list_extend"]['type'] = C('play_type');
			$array["list_extend"]['area'] = C('play_area');
			$array["list_extend"]['year'] = C('play_year');
			$array["list_extend"]['state'] = C('play_state');
			$array["list_extend"]['language'] = C('play_language');
			$array["list_extend"]['version'] = C('play_version');
		}
		//模板相关赋值
		$this->assign($array);
		$this->assign("jumpUrl",$_SESSION['jumpurl']);
		$this->display('./Public/system/vod_add.html');
  }
	//新增与编辑前置操作
  public function _before_update(){
		//播放器组与地址组
		$play = $_POST["vod_play"];
		$server = $_POST["vod_server"];
		foreach($_POST["vod_url"] as $key=>$val){
			$val = trim($val);
			if($val){
			  $vod_play[] = $play[$key];
				$vod_server[] = $server[$key];
				$vod_url[] = $val;
			};
		}
		$_POST["vod_play"] = strval(implode('$$$',$vod_play));
		$_POST["vod_server"] = strval(implode('$$$',$vod_server));
		$_POST["vod_url"] = strval(implode('$$$',$vod_url));
	}
	//新增与更新数据
	public function update(){
		$rs = D('Vod');
		$data = $rs->update($_POST);
		if(!$data){
			$this->error($rs->getError());
		}
		$this->id = $data['vod_id'];
	}
	// 后置操作
	public function _after_update(){
		$vod_id = $this->id;
		if($vod_id){
			//记录最后的主分类ID
			cookie('vod_cid', intval($_POST["vod_cid"]));
			//删除数据缓存
			if(C('cache_page_vod')){
				S(md5(C('cache_foreach_prefix').'cache_page_vod_'.$vod_id),NULL);
			}
			//删除静态缓存
			if(C('html_cache_on')){
				$id = md5($vod_id).C('html_file_suffix');
				@unlink('./Html/Vod_read/'.$vod_id);
				@unlink('./Html/Vod_play/'.$vod_id);
			}
			//生成网页
			if(C('url_html')){
				echo'<iframe src="?s=Admin-Create-Vodid-ids-'.$vod_id.'" frameborder="0" style="display:none"></iframe>';
			}
			//最后跳转
			$this->assign("jumpUrl",$_SESSION['jumpurl']);
			$this->success('恭喜您，数据库、缓存、静态所有操作已完成！');
		}else{
			$this->error('数据库操作完成，附加操作不做处理！');
		}		
	}
	// 删除影片
  public function del(){
		$this->delfile($_GET['id']);
		redirect($_SESSION['jumpurl']);
  }
	// 删除影片all
  public function delall(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要删除的影片！');
		}	
		$array = $_POST['ids'];
		foreach($array as $val){
			$this->delfile($val);
		}
		redirect($_SESSION['jumpurl']);
  }
	// 删除静态文件与图片
  public function delfile($id){
		$where = array();
		//删除影片观看记录
		//$rs = D("View");
		//$where['did'] = $id;
		//$rs->where($where)->delete();
		//删除影片角色
		$where['person_object_id'] = $id;
		$where['person_sid'] = 9;
		D("Person")->where($where)->delete();	
		unset($where);
		//删除影片评论
		$where['cm_cid'] = $id;
		$where['cm_sid'] = 1;
		D("Cm")->where($where)->delete();
		unset($where);	
		//删除影片TAG
		$where['tag_id'] = $id;
		$where['tag_sid'] = 1;
		D("Tag")->where($where)->delete();
		unset($where);
		//删除静态文件与图片
		$where['vod_id'] = $id;
		$array = D('Vod')->field('vod_id,vod_cid,vod_pic,vod_pic_bg,vod_pic_slide,vod_name')->where($where)->find();
		@unlink(ff_url_img($arr['vod_pic']));
		@unlink(ff_url_img($arr['vod_pic_bg']));
		@unlink(ff_url_img($arr['vod_pic_slide']));
		if(C('url_html')){
			@unlink(ff_url_read_vod($array['list_id'],$array['list_dir'],$array['vod_id'],$array['vod_ename'],$array['jumpurl']));
		}
		unset($where);
		//删除影片ID
		$where['vod_id'] = $id;
		D('Vod')->where($where)->delete();
		unset($where);
  }
	// 批量生成数据
  public function create(){
		if($_POST['ids']){
			foreach($_POST['ids'] as $key=>$value){
				echo('<iframe src="?s=Admin-Create-Vodid-ids-'.$value.'" width="100%" height="30" frameborder="0" scrolling="no"></iframe>');
			}
			$this->success('操作成功！');
		}else{
			$this->assign("jumpUrl",$_SESSION['jumpurl']);
			$this->error('操作错误！');
		}
  }	
	// 批量转移影片
  public function pestcid(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要转移的影片！');
		}	
		$cid = intval($_POST['pestcid']);
		if (ff_list_isson($cid)) {
			$rs = D('Vod');
			$data['vod_cid'] = $cid;
			$where['vod_id'] = array('in',$_POST['ids']);
			$rs->where($where)->save($data);
			redirect($_SESSION['jumpurl']);
		}else{
			$this->error('请选择当前大类下面的子分类！');		
		}
  }
	// 批量设置系列
  public function series(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要操作的影片！');
		}
		$cid = trim($_POST['vod_series']);
		$where['vod_id'] = array('in',$_POST['ids']);
		D('Vod')->where($where)->save( array('vod_series'=>$_POST['vod_series']) );
		redirect($_SESSION['jumpurl']);
  }	
	// 设置状态
  public function status(){
		$where = array();
		if(is_array($_REQUEST['ids'])){
			$where['vod_id'] = array('in',implode(',', $_REQUEST['ids']));
		}else{
			$where['vod_id'] = array('eq', $_REQUEST['id']);
		}
		D('Vod')->where( $where )->setField('vod_status', intval($_REQUEST['value']));
		$this->success('状态修改完成！');
		//redirect($_SESSION['jumpurl']);
  }	
	// Ajax设置星级
  public function ajaxstars(){
		$where['vod_id'] = $_GET['id'];
		$data['vod_stars'] = intval($_GET['stars']);
		D('Vod')->where($where)->save($data);		
		echo('ok');
  }	
	// Ajax设置连载
  public function ajaxcontinu(){
		$where['vod_id'] = $_GET['id'];
		$data['vod_continu'] = trim($_GET['continu']);
		D('Vod')->where($where)->save($data);		
		echo('ok');
  }	
	// 锁定采集更新
	public function inputer(){
		$data = array();
		$data['vod_id'] = intval($_GET['id']);
		$data['vod_inputer'] = $_GET['value'];
		D('Vod')->save($data);
		redirect($_SESSION['jumpurl']);
	}
	// 展开关闭筛选条件
	public function select(){
		if($_GET['id'] == 'set'){
			setcookie('vod_select', 1, 0);
			echo('1');
		}else if($_GET['id'] == 'null'){
			setcookie('vod_select', NULL);
			echo('0');
		}else{
			if(isset($_COOKIE['vod_select'])){
				echo('1');
			}else{
				echo('0');
			}
		}
	}
	//批量删除播放地址
	public function delplay(){
		$player = $_REQUEST['player'];
		if(!$player){
			$this->error('请选择需要删除的播放器！');
		}
		//参数
		$params = array();
		$params['field'] = 'vod_id,vod_play,vod_server,vod_url';
		$params['limit'] = 50; 
		$params['play'] = $player;
		$params['order'] = 'vod_id';
		$params['sort'] = 'asc';
		$params['page_is'] = true;
		$params['page_id'] = 'vod';
		$params['page_p'] = !empty($_GET['p'])?intval($_GET['p']):1;
		$params['cache_name'] = 'default';
		$params['cache_time'] = 0;
		$data = ff_mysql_vod($params);
		if(!$data){
			$this->assign("jumpUrl","?s=Admin-Tool-Batch");
			$this->success('所有（'.$player.'）播放地址已清理完成！');
		}
		foreach($data as $key=>$value){
			$array_play = explode('$$$',$value['vod_play']);
			$array_server = explode('$$$',$value['vod_server']);
			$array_url = explode('$$$',$value['vod_url']);
			$search_key = array_search($player, $array_play);
			unset($array_play[$search_key]);
			unset($array_server[$search_key]);
			unset($array_url[$search_key]);
			$vod = array();
			$vod['vod_id'] = $value['vod_id'];
			$vod['vod_play'] = strval(implode('$$$',$array_play));
			$vod['vod_server'] = strval(implode('$$$',$array_server));
			$vod['vod_url'] = strval(implode('$$$',$array_url));
			$vod['vod_addtime'] = time();
			M('Vod')->save($vod);
			echo('<p>'.$vod['vod_id'].'含有('.$player.')的播放地址已被清除。</p>');
			ob_flush();flush();
		}
		$this->redirect('Vod/Delplay', array('player'=>$player), C('collect_time'),'页面跳转中~');
	}
	//批量处理拼音路径
	public function ename(){
		$minid = intval($_REQUEST['minid']);
		$maxlen = ff_default($_REQUEST['maxlen'],20);
		//参数
		$params = array();
		$params['field'] = 'vod_id,vod_name,vod_ename';
		$params['limit'] = 50; 
		$params['id_min'] = $minid;
		$params['order'] = 'vod_id';
		$params['sort'] = 'asc';
		$params['cache_name'] = 'default';
		$params['cache_time'] = 0;
		$data = ff_mysql_vod($params);
		if(!$data){
			$this->assign("jumpUrl","?s=Admin-Tool-Batch");
			$this->success('操作完成！');
		}
		foreach($data as $key=>$value){
			$vod_id = $value['vod_id'];
			$vod_ename = ff_pinyin($value['vod_name']);
			if(strlen($vod_ename) > $maxlen){
				$vod_ename = ff_pinyin($value['vod_name'],true);
			}
			//唯一值处理
			$where = array();
			$where['vod_id'] = array(array('lt',$vod_id), array('gt',$vod_id), 'and');
			$where['vod_ename'] = array('eq',"".$vod_ename."");
			$find = M('Vod')->field('vod_id')->where($where)->find();
			if($find){
				$vod_ename = $vod_ename.$vod_id;
			}
			//更新链接别名
			$vod = array();
			$vod['vod_id'] = $vod_id;
			$vod['vod_ename'] = $vod_ename;
			M('Vod')->save($vod);
			echo('<p>'.$vod_id.'('.$value['vod_name'].')->'.$vod_ename.'</p>');
			ob_flush();flush();
		}
		F('_feifeicms/enamevod',$vod_id);
		$this->redirect('Vod/Ename', array('minid'=>$vod_id,'maxlen'=>$maxlen), C('collect_time'),'<p>页面跳转中~</p>');
	}
	//搜索联想
	public function api(){
		$params = array();
		$params['name'] = htmlspecialchars(urldecode(trim($_REQUEST['name'])));
		$params['field'] = 'vod_id,vod_name';
		$params['limit'] = !empty($_GET['limit'])?intval($_GET['limit']):10;
		$params['order'] = 'vod_id';
		$params['sort'] = 'desc';
		$params['cache_name'] = false;
		$params['cache_time']= false;
		$infos = ff_mysql_vod($params);
		if($infos){
			$this->ajaxReturn($infos,"ok",1);
		}else{
			$this->ajaxReturn($infos,"error",0);
		}
	}
}
?>