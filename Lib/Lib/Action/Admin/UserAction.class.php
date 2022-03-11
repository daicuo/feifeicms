<?php
class UserAction extends BaseAction{	
	// 后台用户管理
  public function show(){
		//默认定义
		$params = array();
		$params['status'] = $_GET['status'];
		$params['pid'] = $_GET['pid'];
		$params['wd'] = urldecode(trim($_REQUEST['wd']));
		$params['order'] = !empty($_GET['order'])?$_GET['order']:C('admin_order_type');
		$params['order'] = str_replace('addtime','logtime',$params['order']);
		$params['sort'] = !empty($_GET['sort'])?$_GET['sort']:'desc';
		//跳转参数
		$urls = $params;
		$urls['g'] = 'admin';
		$urls['m'] = 'user';
		$urls['a'] = 'show';
		$this->assign('urls',$urls);
		//基本参数
		$params['field'] = '*';
		$params['limit'] = 30;
		//分页参数
		$params['page_is'] = true;
		$params['page_id'] = 'user';
		$params['page_p'] = !empty($_GET['p'])?intval($_GET['p']):1;
		//缓存参数
		$params['cache_name'] = false;
		$params['cache_time'] = false;
		//数据查询
		$array_data = ff_mysql_user(array_merge($params,array('order'=>'user_'.$params['order'])));
		//拼装翻页参数
		$page = $_GET['ff_page_user'];//records totalpages currentpage
		$page['jump'] = './index.php?'.http_build_query(array_merge($urls,array('p'=>'FFLINK')));
		$page['pages'] = '共'.$page['records'].'个用户&nbsp;当前:'.$page['currentpage'].'/'.$page['totalpages'].'页&nbsp;'.getpage($page['currentpage'],$page['totalpages'], 8, $page['jump'], 'pagego(\''.$page['jump'].'\','.$page['totalpages'].')');
		//模板定义
		$this->assign($urls);
		$this->assign($page);
		$this->assign('list',$array_data);
		//回跳URL
		session_start();
		$_SESSION['jumpurl'] = './index.php?'.http_build_query(array_merge($urls,array('p'=>$admin['page_p'])));
		//加载模板
    $this->display('./Public/system/user_show.html');
  }
	// 用户添加与编辑表单
  public function add(){
		$user_id = intval($_GET['id']);
		if ($user_id > 0) {
      $where['user_id'] = $user_id;
			$info = D("User")->ff_find('*', array('user_id'=>array('eq',$user_id)), false, false, false);
			if(!$info['user_deadtime']){
				$info['user_deadtime'] = time();
			}
		}else{
			$info['user_id']=0;
			$info['user_score']=0;
			$info['user_jointime'] = time();
			$info['user_logtime'] = time();
			$info['user_deadtime'] = time();
		}
		$this->assign($info);
		$this->display('./Public/system/user_add.html');
  }
	// 更新用户
	public function update(){
		$user = D("User")->ff_update($_POST,'admin');
		if ($user) {
			$this->assign("jumpUrl",'?s=Admin-User-Show');
			$this->success('用户信息操作成功！');
		}else{
			$this->error("用户信息操作失败，".D("User")->getError()."！");
		}
	}
	// 删除用户
	public function del(){
		D('User')->ff_delete($_REQUEST['ids']);
		$this->success('删除用户成功！');
	}
	//状态
	public function status(){
		D('User')->ff_status($_REQUEST['ids'],intval($_REQUEST['value']));
		$this->success('用户、状态修改完成！');
		//redirect($_SERVER['HTTP_REFERER']);
	}
	//影币处理
	public function score(){
		if($_POST['score_uid'] && $_POST['score_ext']){
			$info = D('Score')->ff_user_score($_POST['score_uid'], 3, intval($_POST['score_ext']));
			if($info){
				$this->ajaxReturn(1, "ok", 200);
			}else{
				$this->ajaxReturn(0, D('Score')->getError(), 500);
			}
		}
	}
	//VIP到期时间
	public function deadtime(){
		if($_POST['user_id'] && $_POST['score_ext']){
			$info = D('Score')->ff_user_deadtime($_POST['user_id'], intval($_POST['user_deadtime']), intval($_POST['user_score']), intval($_POST['score_ext']));
			if($info == 200){
				$this->ajaxReturn(1, "ok", 200);
			}else{
				$this->ajaxReturn(0, D('Score')->getError(), $info);
			}
		}
	}
}
?>