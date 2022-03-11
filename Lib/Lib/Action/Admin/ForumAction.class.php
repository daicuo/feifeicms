<?php
class ForumAction extends BaseAction{
	// 用户评论管理
  public function show(){
		//URL参数
		$admin = array();
		$admin['cid'] = $_GET['cid'];
		$admin['sid'] = $_GET['sid'];
		$admin['uid'] = $_GET['uid'];
		$admin['wd'] = urldecode(trim($_REQUEST['wd']));
		$admin['status'] = $_GET['status'];
		$admin['istheme'] = $_GET['istheme'];
		if(isset($admin['istheme'])){
			if($admin['istheme'] == 1){
				$admin['pid'] = '0';
			}else if($admin['istheme'] == 0){
				$admin['pid_not'] = '0';
			}
		}else{
			$admin['pid'] = $_GET['pid'];
		}
		//排序参数
		$admin['order'] = !empty($_GET['order'])?$_GET['order']:C('admin_order_type');
		$admin['sort'] = !empty($_GET['sort'])?$_GET['sort']:'desc';
		//跳转参数
		$urls = $admin;
		$urls['g'] = 'admin';
		$urls['m'] = 'forum';
		$urls['a'] = 'show';
		$this->assign('urls',$urls);
		//基本参数
		$admin['field'] = '*';
		$admin['limit'] = 30;
		//分页参数
		$admin['page_is'] = true;
		$admin['page_id'] = 'forum';
		$admin['page_p'] = !empty($_GET['p'])?intval($_GET['p']):1;
		//缓存参数
		$admin['cache_name'] = false;
		$admin['cache_time'] = false;
		//数据查询
		$list = ff_mysql_forum(array_merge($admin,array('order'=>'forum_'.$admin['order'])));
		// 拼装翻页参数
		$page = $_GET['ff_page_forum'];
		$page['jump'] = './index.php?'.http_build_query(array_merge($urls,array('p'=>'FFLINK')));
		$page['pages'] = '共'.$page['records'].'个评论&nbsp;当前:'.$page['currentpage'].'/'.$page['totalpages'].'页&nbsp;'.getpage($page['currentpage'],$page['totalpages'],8,$page['jump'],'pagego(\''.$page['jump'].'\','.$page['totalpages'].')');
		//变量附值
		$this->assign($urls);
		$this->assign($page);
		$this->assign('list',$list);
		//回跳URL
		session_start();
		$_SESSION['jumpurl'] = './index.php?'.http_build_query(array_merge($urls,array('p'=>$admin['page_p'])));
		//加载模板
    $this->display('./Public/system/forum_show.html');
  }
	// 编辑界面
  public function add(){
		$array =  D('Forum')->ff_find('*', array('forum_id'=>array('eq',$_GET['id'])),false,false);
		$array['isrepost'] = intval($_GET['isrepost']);//是否回复评论
		$this->assign($array);
    $this->display('./Public/system/forum_add.html');
  }
	// 更新
	public function update(){
		$data = $_REQUEST;
		//管理员回复评论删除自动验证规则
		if($data['forum_pid']){
			D("Forum") -> setProperty("_validate", array(
				array('forum_content','require','请填写回复内容！',1)
			));
		}
		//自动验证
		$info = D("Forum")->ff_update($data);
		if($info){
			$this->assign("jumpUrl",$_SESSION['jumpurl']);
			$this->success('更新评论信息成功！');
		}else{
			$this->error(D("Forum")->getError());
			
		}
	}
	//置顶
	public function istop(){
		$where = array();
		$where['forum_id'] = array('eq', $_REQUEST['ids']);
		D('Forum')->where( $where )->setField('forum_istop', intval($_REQUEST['value']));
		$this->success('置顶状态修改完成！');
		//redirect($_SERVER['HTTP_REFERER']);
	}
	//状态
	public function status(){
		$where = array();
		if(is_array($_REQUEST['ids'])){
			$where['forum_id'] = array('in',implode(',', $_REQUEST['ids']));
		}else{
			$where['forum_id'] = array('eq', $_REQUEST['ids']);
		}
		D('Forum')->where( $where )->setField('forum_status', intval($_REQUEST['value']));
		$this->success('显示状态修改完成！');
		//redirect($_SERVER['HTTP_REFERER']);
	}
	// 删除
  public function del(){
		$ids = $_REQUEST['ids'];
		$where = array();
		if(is_array($ids)){
			$where['forum_id'] = array('in',implode(',', $ids));
			$where['forum_pid'] = array('in',implode(',', $ids));
		}else{
			$where['forum_id'] = array('eq', $ids);
			$where['forum_pid'] = array('eq', $ids);
		}
		$where['_logic'] = 'or';
		D('Forum')->where($where)->delete();
		$this->success('删除评论完成！');
  }
	// 清空
  public function clear(){
		$sid = $_REQUEST['sid'];
		$where = array();
		$where['forum_id'] = array('gt', 0);
		if(isset($sid)){
			$where['forum_sid'] = array('eq', $sid);
		}
		D('Forum')->where($where)->delete();
		$this->success('对应的评论已清空！');
  }				
}
?>