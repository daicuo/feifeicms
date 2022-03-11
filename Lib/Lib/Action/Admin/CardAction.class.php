<?php
class CardAction extends BaseAction{
	//卡密管理
  public function show(){
		$admin = array();
		$admin['face'] = $_GET['face'];
		$admin['status'] = $_GET['status'];
		$admin['uid'] = $_GET['uid'];
		$admin['wd'] = trim($_REQUEST['wd']);
		$admin['limit'] = 50;
		$admin['page_is'] = true;
		$admin['page_id'] = 'card';
		$admin['page_p'] = !empty($_GET['p'])?intval($_GET['p']):1;
		$admin['cache_time'] = 0;
		$admin['order'] = !empty($_GET['order'])?$_GET['order']:'card_id';
		$admin['sort'] = !empty($_GET['sort'])?$_GET['sort']:'desc';
		$list = ff_mysql_card($admin);
		//
		$page = $_GET['ff_page_card'];
		$params = array('wd'=>$admin['wd'], 'face'=>$admin['face'], 'uid'=>$admin['uid'], 'status'=>$admin['status'], 'istheme'=>$admin['istheme'], 'status'=>$admin['status'], 'wd'=>urlencode($admin['wd']), 'order'=>$admin['order'], 'sort'=>$admin['sort'], 'p'=>'FFLINK');
		$pageurl = U('Card/Show', $params, false, true);
		$admin['pages'] = '共'.$page['records'].'张卡密&nbsp;当前:'.$page['currentpage'].'/'.$page['totalpages'].'页&nbsp;'.getpage($page['currentpage'],$page['totalpages'],8,$pageurl,'pagego(\''.$pageurl.'\','.$page['totalpages'].')');
		$admin['list'] = $list;
		//总额统计
		$admin['count_total'] = D('Card')->sum('card_face');
		$admin['count_use'] = D('Card')->where('card_status=1')->sum('card_face');
		$admin['count_unuse'] = D('Card')->where('card_status=0')->sum('card_face');
		$this->assign($admin);
		//回跳URL
		$params['p'] = $admin['page_p'];
		$_SESSION['card_jumpurl'] = U('Admin-Card/Show', $params);
    $this->display('./Public/system/card_show.html');
  }
	//生成卡密
	public function create(){
		$data = array();
		for($i=0;$i<intval($_POST['card_num']);$i++){
			$data[$i]['card_face'] = intval($_POST['card_face']);
			$data[$i]['card_number'] = strtoupper($data[$i]['card_face'].'#'.md5(uniqid().rand(1000,9999)));
			$data[$i]['card_addtime'] = time();
		}
		if(D('Card')->addAll($data)){
			$this->success('卡密生成成功！');
		}else{
			$this->error(D('Card')->getError());
		}
	}
	// 删除
  public function del(){
		D('Card')->ff_delete($_REQUEST['ids']);
		$this->success('删除卡密完成！');
  }		
}
?>