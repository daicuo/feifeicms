<?php
class PersonModel extends RelationModel {
	
	protected $_validate = array(
		array('person_sid','require','您没有指定模型ID！',1),
		array('person_name','require','请填写人物名称！',1),
		//array('person_ename','','链接标识重复',2,'unique',3),
	);
	protected $_auto = array(
		array('person_name','trim',3,'function'),
		array('person_addtime','time',3,'function'),
		array('person_gold','person_gold',3,'callback'),
	);
	protected $_link = array(
		'List'=>array(
			'mapping_type' => BELONGS_TO,
			'class_name'=> 'List',
			'mapping_name'=>'List',
			'foreign_key' => 'person_cid',
			'parent_key' => 'list_id',
			'condition' => 'list_status = 1',
			'as_fields' =>'list_id,list_pid,list_name,list_dir,list_ispay,list_price,list_trysee,list_copyright,list_skin_detail,list_skin_play,list_extend',
		)
	);	
	public function person_ename($value){
		if (!$value) {
			return ff_pinyin(trim($value));
		}else{
			return trim($value);
		}
	}
	public function person_addtime($value){
		if ($_POST['checktime']) {
			return time();
		}else{
			return strtotime($value);
		}
	}
	public function person_gold($value){
		if(!$value){
			return 0;
		}
		if($value > 10){
			return 10;
		}
		return $value;
	}
	
	// 通过ID查询详情数据
	public function ff_find($field = '*', $where, $cache_name=false, $relation=true, $order=false){
		//md5处理KEY
		if($cache_name){
			$cache_name = md5(C('cache_foreach_prefix').$cache_name);
		}
		//优先缓存读取数据
		if( C('cache_page_person') && $cache_name){
			$cache_info = S($cache_name);
			if($cache_info){
				return $cache_info;
			}
		}
		//数据库获取数据
		$info = $this->field($field)->where($where)->relation($relation)->order($order)->find();
		//dump($this->getLastSql());
		if($info){
			if($info['list_extend']){
				$info['list_extend'] = json_decode($info['list_extend'], true);
			}
			if( C('cache_page_person') && $cache_name ){
				S($cache_name, $info, $cache_time);
			}
    	return $info;
    }
		$this->error = '数据不存在！';
		return false;
	}
	
	// 新增或更新
	public function ff_update($data){
		// 自动完成需要其它字段填充
		$data['person_content'] = trim($data['person_content']);
		if(!$data['person_ename']){
			$data['person_ename'] = ff_pinyin(trim($data['person_name']));
		}
		if(!$data['person_letter']){
			$data['person_letter'] = ff_url_letter(trim($data['person_name']));
		}
		// 创建安全数据对象TP
		$data = $this->create($data);
		if(false === $data){
			$this->error = $this->getError();
			return false;
		}
		/* 添加或修改行为 */
		if(empty($data['person_id'])){
			$data['person_id'] = $this->add();
			if(!$data['person_id']){
				$this->error = $this->getError();
				return false;
			}
		} else {
			$status = $this->save();
			if(false === $status){
				$this->error = $this->getError();
				return false;
			}
		}
		D('Tag')->tag_update($data['person_id'],$data["person_type"],ff_sid2module($data['person_sid']).'_type');
		D('Tag')->tag_update($data['person_id'],$data["person_keywords"],ff_sid2module($data['person_sid']).'_tag');
		return $data;
	}
	
	//视频对应人物关系（视频->角色->明星）
	public function ff_select_join($params, $where){
		//优先从缓存调用数据及分页变量
		if($params['cache_name'] && $params['cache_time']){
			$infos = S($params['cache_name']);
			if($infos){
				return $infos;
			}
		}
		if( $params['field'] == '*' ){
			$field = array();
			$field[0] = 'role.person_id as role_id,role.person_cid as role_cid,role.person_name as role_name,role.person_ename as role_ename,role.person_pic as role_pic,role.person_intro as role_intro,role.person_content as role_content,role.person_up as role_up,role.person_down as role_down,role.person_gold as role_gold,role.person_golder as role_golder,role.person_hits as role_hits,role.person_addtime as role_addtime';
			$field[1] = 'star.person_id as star_id,star.person_name as star_name,star.person_ename as star_ename,star.person_pic as star_pic';
			$params['field'] = implode(',',$field);
		}
		$join = array();
		$join[0] = 'as role Left JOIN ff_person as star ON role.person_father_id = star.person_id';
		$infos = $this->field($params['field'])->where($where)->order(trim($params['order'].' '.$params['sort']))->join($join)->select();
		//dump($this->getLastSql());
		// 是否写入数据缓存
		if($params['cache_name'] && $params['cache_time']){
			S($params['cache_name'], $infos, intval($params['cache_time']) );
		}
		return $infos;
	}
}
?>