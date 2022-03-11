<?php
class ForumModel extends RelationModel {
	
	protected $_validate = array(
		array('forum_vcode','require','请输入验证码!',1,'',1),
		array('forum_vcode','validate_vcode','验证码错误！',1,'callback',1),
		array('forum_uid','validate_uid','您还没有登录!',1,'callback',1),
		array('forum_cookie','validate_cookie','您已经评论过了!',2,'callback',1),
		array('forum_sid','require','您没有指定模型ID！',1,'',1),
		array('forum_content','require','请填写评论内容！',1),
	);
	
	protected $_auto = array(
		array('forum_cid','intval',1,'function'),
		array('forum_sid','intval',1,'function'),
		array('forum_pid','intval',1,'function'),
		array('forum_uid','get_userid',1,'callback'),
		array('forum_ip','get_client_ip',1,'function'),
		array('forum_content','get_forum_content',1,'callback'),
		array('forum_status','get_status',1,'callback'),
		array('forum_addtime','time',3,'function'),
	);
	
	//关联定义
	protected $_link = array(
		'User'=>array(
			'mapping_type' => BELONGS_TO,
			'class_name'=> 'User',
			'mapping_name'=>'User',//数据对像映射名称
			'foreign_key' => 'forum_uid',
			'parent_key' => 'user_id',
			//'condition' => 'user_status = 1',//用户中心的时候再添加
			'as_fields' =>'user_id,user_name,user_face,user_email',
		)
	);
	
	//检测验证码
	public function validate_vcode($vcode){
		session_start();
		if($_SESSION['verify'] != md5($vcode)){
			return false;
		}
		return true;
	}
	
	//检测指定时间内重复评论
	public function validate_cookie($cookie){
		if(C('user_second')){
			if(isset($_COOKIE[$cookie])){
				return false;
			}
		}
	}
	
	//检测是否需要登录才能发表
	public function validate_uid(){
		if( C('user_forum') ){
			if(!D('User')->ff_islogin()){
				return false;
			}
		}
	}
	
	//过滤脏话与安全	
	public function get_forum_content($str){
		$array = explode('|',C('user_replace'));
		return str_replace($array, '***', remove_xss(h($str)) );
	}	
	
	// 自动填充
	public function get_userid(){
		$userid = intval(D('User')->ff_islogin());
		if ($userid) {
			return $userid;
		}
		return 1;
	}
	
	// 评论状态
	public function get_status(){
		if(C('user_check')){
			return 0;
		}
		return 1;
	}
	
	// 通过ID查询详情数据
	public function ff_find($field = '*', $where, $cache_name=false, $relation=true, $order=false){
		//md5处理KEY
		if($cache_name){
			$cache_name = md5(C('cache_foreach_prefix').$cache_name);
		}
		//优先缓存读取数据
		if( C('cache_page_forum') && $cache_name){
			$cache_info = S($cache_name);
			if($cache_info){
				return $cache_info;
			}
		}
		//数据库获取数据
		$info = $this->field($field)->where($where)->relation($relation)->order($order)->find();
		//dump($this->getLastSql());
		if($info){
			if( C('cache_page_forum') && $cache_name ){
				S($cache_name, $info, $cache_time);
			}
    	return $info;
    }
		$this->error = '数据不存在！';
		return false;
	}
	
	// 新增或更新
	public function ff_update($data){
		// 创建安全数据对象TP
		$data = $this->create($data);
		if(false === $data){
			$this->error = $this->getError();
			return false;
		}
		/* 添加或修改行为 */
		if(empty($data['forum_id'])){
			$data['forum_id'] = $this->add();
			if(!$data['forum_id']){
				$this->error = $this->getError();
				return false;
			}
			//cookie防刷新
			if($data['forum_cookie'] && C('user_second')){
				setcookie($data['forum_cookie'], 'true', time()+intval(C('user_second')));
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
}
?>