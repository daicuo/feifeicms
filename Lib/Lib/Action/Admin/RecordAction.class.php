<?php
class RecordAction extends BaseAction{	

  public function show(){
		$params = array();
		$params['status'] = $_REQUEST['status'];
		$params['sid'] = $_REQUEST['sid'];if($params['sid']==99){$params['sid'] = NULL;}
		$params['type'] = $_REQUEST['type'];if($params['type']==99){$params['type'] = NULL;}
		$params['uid'] = $_GET['uid'];
		$params['did'] = $_GET['did'];
		$params['order'] = !empty($_GET['order'])?$_GET['order']:C('admin_order_type');
		$params['order'] = str_replace('addtime','time',$params['order']);
		$params['sort'] = !empty($_GET['sort'])?$_GET['sort']:'desc';
		// 跳转参数
		$urls = $params;
		$urls['g'] = 'admin';
		$urls['m'] = 'record';
		$urls['a'] = 'show';
		$this->assign('urls',$urls);
		// 查询参数
		$params['field'] = '*';
		$params['limit'] = 30;
		// 分页参数
		$params['page_is'] = true;
		$params['page_id'] = 'record';
		$params['page_p'] = !empty($_GET['p'])?intval($_GET['p']):1;
		// 缓存参数
		$params['cache_name'] = false;
		$params['cache_time'] = false;
		// 根据查询条件查询数据库
		$array_data = ff_mysql_record(array_merge($params,array('order'=>'record_'.$params['order'])));
		// 拼装翻页参数
		$page = $_GET['ff_page_record'];//records totalpages currentpage
		$page['jump'] = './index.php?'.http_build_query(array_merge($urls,array('p'=>'FFLINK')));
		$page['pages'] = '共'.$page['records'].'个日志&nbsp;当前:'.$page['currentpage'].'/'.$page['totalpages'].'页&nbsp;'.getpage($page['currentpage'],$page['totalpages'],8,$page['jump'],'pagego(\''.$page['jump'].'\','.$page['totalpages'].')');
		// 模板定义
		$this->assign($urls);
		$this->assign($page);
		$this->assign('list',$array_data);
		// 加载模板
    $this->display('./Public/system/record_show.html');
  }
	
	public function del(){
		D("Record")->ff_delete($_REQUEST['ids']);
		$this->success('操作完成！');
	}
}
?>