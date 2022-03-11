<?php
class ForumAction extends HomeAction{
	private $sid = 6;	
	
	//评论搜索
	public function search(){
		$params = ff_param_url();
		$info = $this->Lable_Search($params, $this->sid);
		$this->assign($info);
		$this->display($info['search_skin']);
  }
		
	// 按ID读取
  public function read(){
		$info = $this->label();
		$detail = $this->get_cache_detail('id');
		$this->assign($info);
		$this->assign($detail);
		$this->display($detail['forum_skin']);
  }
	
	// 按别名读取
	public function ename(){
		$info = $this->label();
		$detail = $this->get_cache_detail('ename');
		$this->assign($info);
		$this->assign($detail);
		$this->display($detail['forum_skin']);
	}
	
	//获取评论配置
	public function config(){
		$config = array();
		$config['forum_cid'] = intval($_GET['cid']);
		$config['forum_sid'] = intval($_GET['sid']);
		$config['forum_pid'] = intval($_GET['pid']);
		$config['forum_uid'] = intval($_GET['uid']);
		$config['forum_type'] = C('forum_type');
		$config['forum_module'] = ff_sid2module($config['forum_sid']);
		$config['uyan_uid'] = C('forum_type_uyan_uid');
		$config['changyan_appid'] = C('forum_type_changyan_appid');
		$config['changyan_conf'] = C('forum_type_changyan_conf');
		$this->ajaxReturn($config, 'ok', 200);
	}
	
	//更新回复数并返回值
	public function reply(){
		$forum_id = intval($_GET['id']);
		if($forum_id){
			$count = D('Forum')->where('forum_pid = '.$forum_id)->count("forum_id");
			if($count){
				D('Forum')-> where('forum_id='.$forum_id)->setField('forum_reply', $count);
				$this->ajaxReturn($count, "回复数", 200);
			}
		}
		$this->ajaxReturn(0, "回复数", 0);
	}
	
	//写入举报次数
	public function report(){
		$forum_id = intval($_GET['id']);
		if($forum_id){
			D("Forum")->setInc('forum_report', 'forum_id = '.$forum_id, 1);
			$this->ajaxReturn($count, "举报成功！", 200);
		}else{
			$this->ajaxReturn($count, "举报失败！", 0);
		}
	}
	
	// 发布讨论
  public function update(){
		$post = $_POST;
		$post['forum_cookie'] = md5('forum_'.intval($post['forum_sid']).'_'.intval($post['forum_pid']).intval($post['forum_uid']).'_'.intval($post['forum_cid']));
		//报错取消验证
		if($post['forum_sid'] == 24){
			D("Forum") -> setProperty("_validate", '');
		}
		//写入数据库
		$info = D("Forum")->ff_update($post);
		if($info){
			$email_type = '';
			if( $info['forum_sid'] == 5 ){
				if( C('user_email_guestbook') ){
					$email_type = '留言';
				}
			}elseif( $info['forum_sid'] == 24 ){
				if( C('user_email_error') ){
					$email_type = '报错';
				}
			}else{
				if( C('user_email_forum') ){
					$email_type = '评论';
				}
			}
			if( $email_type ){
				D("Email")->send(C('site_email'), '站长您好', '收到用户（'.$info['forum_uid'].'）的'.$email_type, $info['forum_content']);
			}
			//返回状态
			if (C('user_check')) {
				$this->ajaxReturn($info, "谢谢，我们会尽快审核你的发言！", 201);
			}else{
				$this->ajaxReturn($info, "感谢你的参与！", 200);
			}
		}else{
			$this->ajaxReturn('', D("Forum")->getError(), 0);
		}
  }
	
	// 删除评论
	public function delete(){
		$user_id = D('User')->ff_islogin();
		if($user_id){
			$where = array();
			$where['forum_id'] = array('eq', intval($_GET['id']));
			$where['forum_uid'] = array('eq', $user_id);
			if($info = D('Forum')->where($where)->delete()){
				$this->ajaxReturn($info, "删除评论成功！", 200);
			}else{
				$this->ajaxReturn(0, D("Forum")->getError(), 0);
			}
		}
		$this->ajaxReturn(0, '请先登录。', 0);
	}
	
	// 空操作
	public function _empty($action){
	 	/* channel vod news special ajax post
		$tag = 'sid:1;limit:2;cache_name:default;cache_time:0;page_is:true;page_id:comment;page_p:2;order:forum_addtime;sort:desc';
		$data = ff_mysql_forum($tag);
		*/
		$info = $this->label();
		$this->assign($info);
		$this->display('Forum:'.$action);
	}
	
	// 评论普通标签
	private function label(){
		$get = array();
		$get['id'] = intval($_GET['id']);
		$get['cid'] = intval($_GET['cid']);
		$get['sid'] = intval($_GET['sid']);
		$get['uid'] = intval($_GET['uid']);
		$get['pid'] = intval($_GET['pid']);
		$get['page'] = !empty($_GET['p']) ? intval($_GET['p']) : 1;
		return $this->Lable_Forum($get);
	}
	
	// 从数据库获取内容数据
	private function get_cache_detail($action='id'){
		$params = array();
		$where = array();
		$where['forum_cid'] = array('gt', 0);//必要条件
		if(C('user_check')){
			$where['forum_status'] = array('eq', 1);
		}
		if($action=='ename'){
			$params['id'] = htmlspecialchars($_GET['id']);
			$where['forum_ename'] = array('eq', $params['id']);
		}else{
			$params['id'] = intval($_REQUEST['id']);
			$where['forum_id'] = array('eq', $params['id']);
		}
		//查库
		$info = D('Forum')->ff_find('*', $where, 'cache_page_forum_'.$params['id'], 'User');
		if(!$info){
			$this->assign("jumpUrl",C('site_path'));
			$this->error('评论已删除！');
		}
		//解析标签
		return $this->Lable_Forum_Read($info);
	}
}
?>