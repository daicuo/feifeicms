<?php
class UserModel extends RelationModel {
	
	protected $_validate = array(
		// 防刷新注册
		array('user_register','validate_user_register','注册速度过快!',1,'callback',1),
		// 验证呢称
		array('user_name','require','用户呢称必须填写！',0,'',3),
		array('user_name', '', '用户呢称被占用，请重新填写', 2, 'unique',3),
		/* 验证邮箱 */
		array('user_email', 'email', '邮箱格式不正确', 0,'',3),
		array('user_email', '', '邮箱被占用，请重新填写', 2, 'unique',3),
		/* 验证密码 */
		array('user_pwd_re', 'user_pwd', '两次密码输入不一样', 2, 'confirm'),
	);
	
	protected $_auto = array(
		array('user_joinip','get_client_ip',1,'function'),//写入
		array('user_jointime','time',1,'function'),
		array('user_deadtime','auto_user_deadtime',1,'callback'),
		array('user_pwd','md5',1,'function'),
		array('user_pid','auto_user_pid',1,'callback'),
		array('user_status','auto_user_status',1,'callback'),
		array('user_logip','get_client_ip',2,'function'),//更新
		array('user_logtime','time',2,'function'),
		array('user_pwd','auto_user_pwd',2,'callback'),
		array('user_name','auto_user_name',3,'callback'),//写入与更新
	);
	
	//防刷新
	public function validate_user_register(){
		if(time()-intval(cookie('ff_register_time')) < intval(C('user_register_second'))){
			return false;
		}
		return true;
	}
	
	//用户推广ID获取
	public function auto_user_pid(){
		return intval(cookie('ff_register_pid'));
	}
	
	//用户注册是否需要审核
	public function auto_user_status(){
		if(C('user_register_check')){
			return 0;
		}
		return 1;
	}
	
	//注册赠送VIP
	public function auto_user_deadtime(){
		if(C('user_register_vipday')){
			return strtotime('+'.intval(C('user_register_vipday')).' day', time());
		}else{
			return time();
		}
	}
	
	//过滤脏话与安全
	public function auto_user_name($str){
		if($str){
			$array = explode('|',C('user_replace'));
			return str_replace($array, '***', remove_xss(h($str)) );
		}
		return false;
	}
	
	//密码处理
	public function auto_user_pwd($pwd){
		if (empty($pwd)) {
		  return false;
		}else{
		  return md5($pwd);
		}
	}
	
	//登录成功返回用户ID
	public function ff_login($post){
		$where = array();
		//用户名与邮箱登录
		if(filter_var($post['user_email'], FILTER_VALIDATE_EMAIL)){
			$where['user_email'] = array('eq', htmlspecialchars(trim($post['user_email'])));
		}else{
			$where['user_name'] = array('eq', htmlspecialchars(trim($post['user_email'])));
		}
		//查库
		$info = $this->field('user_id,user_name,user_pwd,user_email,user_status')->where($where)->find();
		if(!$info){
			$this->error = '用户资料不存在。';
			return false;
		}
		if( $info['user_pwd'] != md5($post['user_pwd']) ){
			$this->error = '用户密码不正确。';
      return false;
		}
		if(1 != $info['user_status']){
			$this->error = '用户未审核。';
      return false;
		}
		// 去除不需要写入cookie的字段
		unset($info['user_email']);
		unset($info['user_status']);
		// cookie有效期 默认1月
		if($post['user_remember']){
			$this->ff_login_write($info, 2592000);
		}else{
			$this->ff_login_write($info);
		}
    return $info['user_id'];
	}
	
	//注销
	public function ff_logout(){
		cookie('ff_user', NULL);
		session_start();
		$_SESSION['ff_user_sign'] = NULL;
	}
	
	//写入登录信息
	public function ff_login_write($user, $expire=0){
		// 更新登录信息
		$data = array(
			'user_id'      => $user['user_id'],
			'user_lognum'  => array('exp', '`user_lognum`+1'),
			'user_logtime' => time(),
			'user_logip'   => get_client_ip(),
		);
		$this->save($data);
		// 加密USER信息
		$encrypt = ff_encrypt(implode('$feifeicms$', $user));
		// 写入客户端，不要记录本次登录的IP
		cookie('ff_user', $encrypt, $expire);
		// 写入服务端，需加上本次登录的IP
		session_start();
		$_SESSION['ff_user_sign'] = sha1( $encrypt.$data['user_logip'] );
	}
	
	//验证是否已登录（已登录返回用户ID）
	public function ff_islogin(){
		// 获取加密USER信息字符串
		$encrypt = cookie('ff_user');
		if($encrypt){
			// cookie user信息
			$user_cookie = explode('$feifeicms$', ff_decrypt($encrypt));
			// session 对比
			session_start();
			if(sha1($encrypt.get_client_ip()) == $_SESSION['ff_user_sign']){
				return intval($user_cookie[0]);
			}
			// session已过期 数据库验证
			$user_db = $this->field('user_id,user_name,user_pwd,user_status')->where('user_id='.intval($user_cookie[0]))->find();
			if($user_db){
				if($user_db['user_status'] == 1 && $user_db['user_name' ] == $user_cookie[1] && $user_db['user_pwd'] == $user_cookie[2]){
					$_SESSION['ff_user_sign'] = sha1( $encrypt.get_client_ip() );
					return intval($user_cookie[0]);
				}
			}
			// 验证不通过则删除cookie与session
			$this->ff_logout();
		}
		return 0;
	}
	
	//从数据库获取用户完整信息
	public function ff_info_db($field='*'){
		$user_id = $this->ff_islogin();
		if($user_id){
			return $this->ff_find($field, array('user_id'=>array('eq',$user_id)), false, false, false);
		}
		return false;
	}
	
	// 新增或更新
	public function ff_update($data, $group='home'){
		// 创建安全数据对象TP
		$data = $this->create($data);
		if(false === $data){
			$this->error = $this->getError();
			return false;
		}
		/* 添加或修改行为 */
		if(empty($data['user_id'])){
			$data['user_id'] = $this->add();
			if(!$data['user_id']){
				$this->error = $this->getError();
				return false;
			}
			if($group == 'home'){
				//写入注册时间防刷新注册
				cookie('ff_register_time', time());
				//写入登录信息
				$this->ff_login_write(array('user_id'=>$data['user_id'],'user_name'=>$data['user_name'],'user_pwd'=>$data['user_pwd']));
			}
		} else {
			$status = $this->save();
			if(false === $status){
				$this->error = $this->getError();
				return false;
			}
		}
		return $data;
	}
	
	// 通过ID查询详情数据
	public function ff_find($field = '*', $where, $cache_name=false, $relation=true, $order=false){
		//md5处理KEY
		if($cache_name){
			$cache_name = md5(C('cache_foreach_prefix').$cache_name);
		}
		//优先缓存读取数据
		if( C('cache_page_user') && $cache_name){
			$cache_info = S($cache_name);
			if($cache_info){
				return $cache_info;
			}
		}
		//数据库获取数据
		$info = $this->field($field)->where($where)->relation($relation)->order($order)->find();
		//dump($this->getLastSql());
		if($info){
			if( C('cache_page_user') && $cache_name ){
				S($cache_name, $info, intval(C('cache_page_user')));
			}
    	return $info;
    }
		$this->error = '数据不存在！';
		return false;
	}
	
	//删除用户将删除用户评论与用户信息
	public function ff_status($array_ids,$value){
		if(is_array($array_ids)){
			$array_ids = implode(',', $array_ids);
		}
		D("Forum")->where( array('forum_uid'=>array('in',$array_ids)) )->setField('forum_status', $value);
		D("User")->where( array('user_id'=>array('in',$array_ids)) )->setField('user_status', $value);
	}
	
	//删除用户将删除用户评论与用户信息
	public function ff_delete($array_ids){
		if(is_array($array_ids)){
			$array_ids = implode(',', $array_ids);
		}
		D("Forum")->where(array('forum_uid'=>array('in',$array_ids)))->delete();
		D("Score")->where(array('score_uid'=>array('in',$array_ids)))->delete();
		D("Orders")->where(array('order_uid'=>array('in',$array_ids)))->delete();
		$this->where(array('user_id'=>array('in',$array_ids)))->delete();
	}
	
	// 查询多个数据
	public function ff_select_page($params, $where){
		//优先从缓存调用数据及分页变量
		if($params['cache_name'] && $params['cache_time']){
			$infos = S($params['cache_name']);
			if($infos){
				if($params['page_id'] && $params['page_is']){
					$_GET['ff_page_'.$params['page_id']] = S($params['cache_name'].'_page');
				}
				return $infos;
			}
		}
		// 分页变量动态定义
		if($params['page_id'] && $params['page_is']){
			$page = array();
			$page['records'] = $this->ff_select_count($where);
			$page['totalpages'] = ceil($page['records']/$params['limit']);
			$page['currentpage'] = ff_page_max($params['page_p'], $page['totalpages']);
			// 使用GET全局变量传递分页参数 gx_page_default
			$_GET['ff_page_'.$params['page_id']] = $page;
		}else{
			$page['currentpage'] = NULL;
		}	
		$infos = $this->field($params['field'])->where($where)->limit($params['limit'])->page($page['currentpage'])->order(trim($params['order'].' '.$params['sort']))->select();
		//dump($this->getLastSql());
		// 是否写入数据缓存
		if($params['cache_name'] && $params['cache_time']){
			S($params['cache_name'], $infos, intval($params['cache_time']) );
			if($params['page_id'] && $params['page_is']){
				S($params['cache_name'].'_page', $page, intval($params['cache_time'])+1 );
			}
		}
		return $infos;
	}
	// 符合条件的统计
	public function ff_select_count($where){
		return $this->where($where)->count('user_id');
	}
}
?>