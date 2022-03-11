<?php
class OrdersAction extends BaseAction{	
  public function show(){
		$params = array();
		$params['status'] = $_REQUEST['status'];
		$params['ispay'] = $_REQUEST['ispay'];if($params['ispay']==99){$params['ispay'] = NULL;}
		$params['shipping'] = $_REQUEST['shipping'];
		$params['uid'] = $_GET['uid'];
		$params['gid'] = $_GET['gid'];
		$params['wd'] = urldecode(trim($_REQUEST['wd']));
		$params['order'] = !empty($_GET['order'])?$_GET['order']:C('admin_order_type');
		$params['sort'] = !empty($_GET['sort'])?$_GET['sort']:'desc';
		// 跳转参数
		$urls = $params;
		$urls['g'] = 'admin';
		$urls['m'] = 'orders';
		$urls['a'] = 'show';
		$this->assign('urls',$urls);
		// 查询参数
		$params['field'] = '*';
		$params['limit'] = 30;
		// 分页参数
		$params['page_is'] = true;
		$params['page_id'] = 'order';
		$params['page_p'] = !empty($_GET['p'])?intval($_GET['p']):1;
		// 缓存参数
		$params['cache_name'] = false;
		$params['cache_time'] = false;
		// 数据查询
		$array_data = ff_mysql_orders(array_merge($params,array('order'=>'order_'.$params['order'])));
		// 拼装翻页参数
		$page = $_GET['ff_page_order'];//records totalpages currentpage
		$page['jump'] = './index.php?'.http_build_query(array_merge($urls,array('p'=>'FFLINK')));
		$page['pages'] = '共'.$page['records'].'个订单&nbsp;当前:'.$page['currentpage'].'/'.$page['totalpages'].'页&nbsp;'.getpage($page['currentpage'],$page['totalpages'],8,$page['jump'],'pagego(\''.$page['jump'].'\','.$page['totalpages'].')');
		//变量附值
		$this->assign($urls);
		$this->assign($page);
		$this->assign('list',$array_data);
    $this->display('./Public/system/orders_show.html');
  }
	// 人工补单（付款成功但通知失败）
  public function pay(){
		D("Orders")->ff_update_order($_GET['sign'],$_GET['money']);
		$this->success('操作完成！');
  }
	// 删除订单
	public function del(){
		D('Orders')->ff_delete($_REQUEST['ids']);
		$this->success('删除订单成功！');
	}
}
?>