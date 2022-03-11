<?php
class NewsAction extends BaseAction{	
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
		$admin['inputer'] = $_REQUEST['inputer'];
		$admin['tag_name'] = urldecode(trim($_REQUEST['tag_name']));
		$admin['tag_list'] = trim($_REQUEST['tag_list']);
		$admin['wd'] = urldecode(trim($_REQUEST['wd']));		
		$admin['limit'] = !empty($_GET['limit'])?intval($_GET['limit']):30;
		$admin['order'] = !empty($_GET['order'])?$_GET['order']:C('admin_order_type');
		$admin['sort'] = !empty($_GET['sort'])?$_GET['sort']:'desc';
		// 跳转参数
		$urls = $admin;
		$urls['g'] = 'admin';
		$urls['m'] = 'news';
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
		$list = ff_mysql_news(array_merge($admin,array('order'=>'news_'.$admin['order'])));
		foreach($list as $key=>$val){
		  $list[$key]['list_url'] = '?s=Admin-News-Show-cid-'.$list[$key]['person_cid'];
			$list[$key]['list_stars'] = admin_star_arr($list[$key]['news_stars']);
			$list[$key]['news_url'] = ff_url_read_news($list[$key]['list_id'],$list[$key]['list_dir'],$list[$key]['news_id'],$list[$key]['news_ename'],$list[$key]['jumpurl'],1);
		}
		// 拼装翻页参数
		$page = $_GET['ff_page_item'];
		$page['jump'] = './index.php?'.http_build_query(array_merge($urls,array('p'=>'FFLINK')));
		$page['pages'] = '共'.$page['records'].'个文章&nbsp;当前:'.$page['currentpage'].'/'.$page['totalpages'].'页&nbsp;'.getpage($page['currentpage'],$page['totalpages'],8,$page['jump'],'pagego(\''.$page['jump'].'\','.$page['totalpages'].')');
		//变量附值
		$this->assign($urls);
		$this->assign($page);
		$this->assign('list',$list);
		//回跳URL
		session_start();
		$_SESSION['jumpurl'] = './index.php?'.http_build_query(array_merge($urls,array('p'=>$admin['page_p'])));
		//加载模板
		$this->display('./Public/system/news_show.html');
  }
	// 添加编辑
  public function add(){
		$rs = D("News");
		$news_id = intval($_GET['id']);
		if($news_id){
			$where = array();
      $where['news_id'] = $news_id;
			$array = $rs->ff_find('*', $where);
			foreach($array['Tag'] as $key=>$value){
				$tag[$value['tag_list']][$key] = $value['tag_name'];
			}
			$array['news_starsarr'] = admin_star_arr($array['news_stars']);
			$array['news_type'] = implode(',',$tag['news_type']);
			$array['news_keywords'] = implode(',',$tag['news_tag']);
			$array['news_tplname'] = '编辑';
			$_SESSION['jumpurl'] = $_SERVER['HTTP_REFERER'];
		}else{
		  $array['news_cid'] = cookie('news_cid');
		  $array['news_stars'] = 0;
			$array['news_inputer'] = $_SESSION['admin_name'];
			$array['news_addtime'] = time();
			$array['news_starsarr'] = admin_star_arr(1);
			$array['news_tplname'] = '添加';
			$array["list_extend"]['type'] = C('news_type');
		}
		$this->assign($array);
		$this->display('./Public/system/news_add.html');
  }
	// 新增与更新数据
	public function update(){
		$rs = D('News');
		$data = $rs->update($_POST);
		if(!$data){
			$this->error($rs->getError());
		}
		$this->id = $data['news_id'];
	}
	// 后置操作
	public function _after_update(){
		$news_id = $this->id;
		if($news_id){
			//记录最后的主分类ID
			cookie('news_cid', intval($_POST["news_cid"]) );
			//删除数据缓存
			if(C('cache_page_news')){
				S(md5(C('cache_foreach_prefix').'cache_page_news_'.$news_id), NULL);
			}
			//删除静态缓存
			if(C('html_cache_on')){
				$id = md5($news_id).C('html_file_suffix');
				@unlink('./Html/News_read/'.$news_id);
			}
			//生成网页
			if(C('url_html')){
				echo'<iframe src="?s=Admin-Create-Newsid-ids-'.$news_id.'" frameborder="0" style="display:none"></iframe>';
			}
			//跳转网页
			$this->assign("jumpUrl",$_SESSION['jumpurl']);
			$this->success('恭喜您，数据库、缓存、静态所有操作已完成！');
		}else{
			$this->error('数据库操作完成，附加操作不做处理！');
		}		
	}	
	// Ajax设置星级
  public function ajaxstars(){
		$where['news_id'] = $_GET['id'];
		$data['news_stars'] = intval($_GET['stars']);
		$rs = D("News");
		$rs->where($where)->save($data);		
		exit('ok');
  }
	// 设置状态
  public function status(){
		$where = array();
		if(is_array($_REQUEST['ids'])){
			$where['news_id'] = array('in',implode(',', $_REQUEST['ids']));
		}else{
			$where['news_id'] = array('eq', $_REQUEST['id']);
		}
		D('News')->where( $where )->setField('news_status', intval($_REQUEST['value']));
		$this->success('状态修改完成！');
  }
	// 删除文章
  public function del(){
		$this->delfile($_GET['id']);
		redirect($_SESSION['jumpurl']);
  }
	// 删除文章all
  public function delall(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要删除的文章！');
		}	
		$array = $_POST['ids'];
		foreach($array as $val){
			$this->delfile($val);
		}
		redirect($_SESSION['jumpurl']);
  }
	// 删除静态文件与图片
  public function delfile($id){
		//删除新闻评论
		unset($where);
		$where['cm_cid'] = $id;
		$where['cm_sid'] = 2;
		D("Cm")->where($where)->delete();			
		//删除新闻TAG
		$where['tag_id'] = $id;
		$where['tag_sid'] = 2;
		D("Tag")->where($where)->delete();
		unset($where);
		//删除静态文件与图片
		$where['news_id'] = $id;
		$array = D("News")->field('news_id,news_cid,news_pic,news_pic_bg,news_pic_slide,news_name')->where($where)->find();
		@unlink(ff_url_img($arr['news_pic']));
		@unlink(ff_url_img($arr['news_pic_bg']));
		@unlink(ff_url_img($arr['news_pic_slide']));
		if(C('url_html')){
			@unlink(ff_url_read_news($array['list_id'],$array['list_dir'],$array['news_id'],$array['news_ename'],$array['jumpurl'],1));
		}
		unset($where);
		//删除新闻ID
		$where['news_id'] = $id;
		D("News")->where($where)->delete();
		unset($where);
  }
	// 批量转移文章
  public function pestcid(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要转移的新闻！');
		}	
		$cid = intval($_POST['pestcid']);
		if (ff_list_isson($cid)) {
			$rs = D("News");
			$data['news_cid'] = $cid;
			$where['news_id'] = array('in',$_POST['ids']);
			$rs->where($where)->save($data);
			redirect($_SESSION['jumpurl']);
		}else{
			$this->error('请选择当前大类下面的子分类！');		
		}
  }	
	// 批量生成数据
  public function create(){
		if($_POST['ids']){
			foreach($_POST['ids'] as $key=>$value){
				echo('<iframe src="?s=Admin-Create-Newsid-ids-'.$value.'" width="100%" height="30" frameborder="0" scrolling="no"></iframe>');
			}
			$this->success('操作成功！');
		}else{
			$this->assign("jumpUrl",$_SESSION['jumpurl']);
			$this->error('操作错误！');
		}
  }	
	// 展开关闭筛选条件
	public function select(){
		if($_GET['id'] == 'set'){
			setcookie('news_select', 1, 0);
			echo('1');
		}else if($_GET['id'] == 'null'){
			setcookie('news_select', NULL);
			echo('0');
		}else{
			if(isset($_COOKIE['news_select'])){
				echo('1');
			}else{
				echo('0');
			}
		}
	}	
	//批量处理拼音路径
	public function ename(){
		$minid = intval($_REQUEST['minid']);
		$maxlen = ff_default($_REQUEST['maxlen'],20);
		//参数
		$params = array();
		$params['field'] = 'news_id,news_name,news_ename';
		$params['limit'] = 50; 
		$params['id_min'] = $minid;
		$params['order'] = 'news_id';
		$params['sort'] = 'asc';
		$params['cache_name'] = 'default';
		$params['cache_time'] = 0;
		$data = ff_mysql_news($params);
		if(!$data){
			$this->assign("jumpUrl","?s=Admin-Tool-Batch");
			$this->success('操作完成！');
		}
		foreach($data as $key=>$value){
			$news_id = $value['news_id'];
			$news_ename = ff_pinyin($value['news_name']);
			if(strlen($news_ename) > $maxlen){
				$news_ename = ff_pinyin($value['news_name'],true);
			}
			//唯一值处理
			$where = array();
			$where['news_id'] = array(array('lt',$news_id), array('gt',$news_id), 'and');
			$where['news_ename'] = array('eq',"".$news_ename."");
			$find = M('News')->field('news_id')->where($where)->find();
			if($find){
				$news_ename = $news_ename.$news_id;
			}
			//更新链接别名
			$data = array();
			$data['news_id'] = $news_id;
			$data['news_ename'] = $news_ename;
			M('News')->save($data);
			echo('<p>'.$news_id.'('.$value['news_name'].')->'.$news_ename.'</p>');
			ob_flush();flush();
		}
		F('_feifeicms/enamenews',$news_id);
		$this->redirect('News/Ename', array('minid'=>$news_id,'maxlen'=>$maxlen), C('collect_time'),'<p>页面跳转中~</p>');
	}			
}
?>