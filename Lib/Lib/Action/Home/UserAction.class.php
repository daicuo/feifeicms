<?php
class UserAction extends HomeAction{
	// ff_user ff_user_sign ff_register_time ff_register_pid
	// 用户首页
	public function index(){
		$user_id = intval($_GET['id']);
		if($user_id){
			$detail = D("User")->ff_find('*', array('user_status'=>1, 'user_id'=>array('eq',$user_id)), false, false, false);
		}
		if(!$detail){
			$this->assign("jumpUrl", ff_url('user/login'));
			$this->error('未查询到相关用户!');
			exit();
		}
		$detail['user_page'] = !empty($_GET['p']) ? intval($_GET['p']) : 1;
		$detail['user_ajax'] = intval($_GET['ajax']);
		$detail['user_type'] = intval($_GET['type']);
		$detail['user_sid'] = intval($_GET['sid']);
		$this->assign($detail);
		if($detail['user_ajax']){
			$this->display('User:index_ajax');
		}else{
			$this->display('User:index');
		}
	}
	
	//用户中心
	public function center(){
		$user_id = $this->islogin();
		$detail = D("User")->ff_find('*', array('user_id'=>array('eq',$user_id)), false, false, false);
		if(!$detail){
			$this->assign("jumpUrl", ff_url('user/login'));
			$this->error('获取用户资料出错，请重新登录!');
			exit();
		}
		$detail['user_action'] = !empty($_GET['action']) ? $_GET['action'] : 'index';
		$detail['user_page'] = !empty($_GET['p']) ? intval($_GET['p']) : 1;
		$this->assign($detail);
		$this->display('User:center_'.$detail['user_action']);
	}

	//json返回用户ID与用户名(通过cookie)
	public function info(){
		$user = ff_user_cookie();
		if($user){
			$this->ajaxReturn($user, "success", 200);
		}else{
			$this->ajaxReturn('', "error", 404);
		}
	}
	
	//VIP续费
	public function deadtime(){
		$user = D('User')->ff_info_db('user_id,user_score,user_deadtime');
		if(!$user){
			$this->ajaxReturn($user, "无此用户", 404);
		}
		//VIP时间操作
		if($_POST['score_ext'] > 0){
			$info = D('Score')->ff_user_deadtime($user['user_id'], $user['user_deadtime'], $user['user_score'], intval($_POST['score_ext']));
			if($info == 200){
				$this->ajaxReturn(1, "ok", 200);
			}else{
				$this->ajaxReturn(0, D('Score')->getError(), $info);
			}
		}else{
			$this->ajaxReturn('', "升级天数不得小于0", 500);
		}
	}
	
	//修改邮箱 return json(data info status)
	public function email(){
		$user = D('User')->ff_info_db('user_id,user_pwd');
		if(!$user){
			$this->ajaxReturn($user, "无此用户", 404);
		}
		if(md5(trim($_POST['user_pwd'])) != $user['user_pwd']){
			$this->ajaxReturn('', "密码不正确", 500);
		}else{
			$info = D('User')->ff_update(array('user_email'=>htmlspecialchars($_POST['user_email']),'user_id'=>$user['user_id']));
			if($info){
				$this->ajaxReturn('', "ok", 200);
			}else{
				$this->ajaxReturn('', D('User')->getError(), 501);
			}
		}
	}
	
	//修改密码
	public function repwd(){
		$user = D('User')->ff_info_db('user_id,user_pwd');
		if(!$user){
			$this->ajaxReturn($user, "无此用户", 404);
		}
		if(md5(trim($_POST['user_pwd_old'])) != $user['user_pwd']){
			$this->ajaxReturn('', "密码不正确", 500);
		}else{
			$info = D('User')->ff_update(array('user_pwd'=>trim($_POST['user_pwd']),'user_pwd_re'=>trim($_POST['user_pwd_re']),'user_id'=>$user['user_id']));
			if($info){
				D('User')->ff_logout();
				$this->ajaxReturn('', "ok", 200);
			}else{
				$this->ajaxReturn('', D('User')->getError(), 501);
			}
		}
	}
	
	//忘记密码
	public function forgetpost(){
		session_start();
		if($_SESSION['verify'] != md5($_POST['user_vcode'])){
			$this->ajaxReturn('', "验证码错误", 500);
		}
		$where = array();
		$where['user_email'] = array('eq',htmlspecialchars($_POST['user_email']));
		$info = D('User')->field('user_id,user_name,user_email')->where($where)->find();
		if(!$info){
			$this->ajaxReturn('', "无此邮箱.", 404);
		}
		//发送邮件
		$time = time();
		$key = md5($info['user_email'].C("email_password").$time);
		$link  = "http://".C("site_domain")."/index.php?g=home&m=user&a=forgetemail&id=".$info['user_id']."&time=".$time."&key=".$key;
		$content = $info['user_name'].'：您好，您在《'. C("site_name").'》申请重置密码，请在1小时内点击以下链接（系统将随机生成一个新密码，请用新密码登录后及时修改为您的常用密码）：<a href="'.$link.'" target=_blank">'.$link.'</a>；如非您本人操作，请忽略此邮件，您的密码将不会被修改!';
		if(D("Email")->send($info['user_email'], $info['user_name'], C("site_name").'密码重置邮件', $content, true)){
			$this->ajaxReturn('', "ok", 200);
		}else{
			$this->ajaxReturn('', D('Email')->getError(), 502);
		}
	}
	
	//重设随机密码
	public function forgetemail(){
		//地址栏参数
		$id = intval($_GET['id']);
		$time = intval($_GET['time']);
		$key = trim($_GET['key']);
		//超时验证
		if( (time()-$time) > 3600 ){
			$this->assign("jumpUrl",U('user/forget'));
			$this->error('密钥过期!');
		}
		//密钥验证
		$info = D('User')->field('user_id,user_name,user_email')->where('user_id='.$id)->find();
		if(!$info){
			$this->assign("jumpUrl",U('user/register'));
			$this->error('未查询到相关用户，请注册!');
		}
		$key_db = md5($info['user_email'].C("email_password").$time);
		if($key != $key_db){
			$this->assign("jumpUrl",U('user/forget'));
			$this->error('密钥错误!');
		}
		//生成随机密码并修改数据库
		$this->assign("jumpUrl",U('user/login'));
		$this->assign("waitSecond", 600);
		$pwd_rand = rand(100000,999999);
		$data = array();
		$data['user_id'] = $info['user_id'];
		$data['user_pwd'] = md5($pwd_rand);
		if(D("User")->save($data)){
			$this->success($content = '您的密码已修改为'.$pwd_rand.'，请登录后修改为您的常用密码。');
		}else{
			$this->error('重置密码出错，请联系管理员!');
		}
	}
		
	public function login(){
		$this->display('User:login');
	}
	
	public function loginpost(){
		$user_id = D("User")->ff_login($_POST);
		if($user_id){
			$this->ajaxReturn($user_id, "登录成功", 200);
		}else{
			$this->ajaxReturn(0, D("User")->getError(), 500);
		}
	}
	
  public function register(){
		if(!C('user_register')){
			$this->assign("jumpUrl",C('site_path'));
			$this->error('SORRY，未开放注册功能！');
		}
		if($_GET['id']){
			cookie('ff_register_pid', intval($_GET['id']), time()+86400);
		}
		$referer = $_SERVER["HTTP_REFERER"];
		if($referer){
			$parse = parse_url($_SERVER["HTTP_REFERER"]);
			if($parse['host'] == C('site_domain') || $parse['host'] == C('site_domain_m')){
				cookie('ff_register_referer',$referer, 0); 
			}
		}
		$this->display('User:register');
  }
	
	public function post(){
		$post = array();
		$post['user_name'] = htmlspecialchars(trim($_POST['user_name']));
		$post['user_email'] = trim($_POST['user_email']);
		$post['user_pwd'] = trim($_POST['user_pwd']);
		$post['user_pwd_re'] = trim($_POST['user_pwd_re']);
		$info = D("User")->ff_update($post);
		if($info){
			//注册积分
			if(C('user_register_score')){
				D('Score')->ff_user_score($info['user_id'], 2, intval(C('user_register_score')));
			}
			//推广积分
			if($info['user_pid'] && C('user_register_score_pid')){
				D('Score')->ff_user_score($info['user_pid'], 4, intval(C('user_register_score_pid')));
			}
			//json返回
			$data = array('id'=>$info['user_id'],'referer'=>cookie('ff_register_referer'));
			//欢迎邮件信息
			if( C('user_register_welcome') ){
				$content = str_replace(array('{username}','{sitename}','{time}'), array($info['user_name'],C('site_name'),time()), C('user_register_welcome'));
				D("Email")->send($info['user_email'], $info['user_name'], $info['user_name'].'您好，感谢您的注册', $content);
			}
			//返回注册结果
			if (C('user_register_check')) {
				$this->ajaxReturn($data, "我们会尽快审核你的注册！", 201);
			}else{
				$this->ajaxReturn($data, "感谢你的注册！", 200);
			}
		}else{
			$this->ajaxReturn(0, D("User")->getError(), 500);
		}
	}
	
	public function logout(){
		D('User')->ff_logout();
		$this->assign("jumpUrl", ff_url('user/login'));
		$this->success('注销成功!');
	}
	
	public function face(){
		if(C("upload_face_width") && C("upload_face_height")){
			C('upload_water', true);
			C('upload_thumb', 1);
			C('upload_thumb_w',128);
			C('upload_thumb_h',128);
			$file_url = D('Img')->ff_upload('user', 'thumb');
			if($file_url){
				$data = array();
				$data['user_id'] = D('User')->ff_islogin();
				$data['user_face'] = $file_url;
				D('User')->ff_update($data);
				echo('http:'.C('upload_path').'/'.$file_url);
			}else{
				echo(D('Img')->getError());
			}
		}else{
			echo('未开启头像上传功能，请联系管理员。');
		}
	}
	
	public function _empty($action){
	 $this->display('User:'.$action);
	}
	
	private function islogin(){
		$user_id = D('User')->ff_islogin();
		if($user_id){
			return $user_id;
		}
		$this->assign("jumpUrl", ff_url('user/login'));
		$this->error('请先登录!');
		exit();
	}
}
?>