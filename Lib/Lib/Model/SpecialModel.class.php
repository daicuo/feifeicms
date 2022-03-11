<?php 
class SpecialModel extends RelationModel {
	protected $_validate=array(
		array('special_name','require','专题名称必须填写！',1),
		array('special_ename','','别名标识重复，请重新填写',2,'unique',3),
	);
	protected $_auto=array(
		array('special_ename','special_ename',3,'callback'),
		array('special_addtime','m_addtime',3,'callback'),
		array('special_content','special_content',3,'callback'),
	);
	//关联定义
	protected $_link = array(
		'List'=>array(
			'mapping_type' => BELONGS_TO,
			'class_name'=> 'List',
			'mapping_name'=>'List',
			'foreign_key' => 'special_cid',
			'parent_key' => 'list_id',
			'condition' => 'list_status = 1',
			'as_fields' =>'list_id,list_pid,list_name,list_dir,list_title,list_keywords,list_description,list_copyright,list_skin_detail,list_extend',
		),	
		'Tag'=>array(
			'mapping_type' => HAS_MANY,
			'class_name'=> 'Tag',
			'mapping_name'=>'Tag',//数据对像映射名称
			'foreign_key' => 'tag_id',
			'parent_key' => 'special_id',
			'mapping_fields' => 'tag_id,tag_cid,tag_name,tag_ename,tag_list',
			'condition' => "tag_cid in(5,6)",
			'mapping_order' => 'tag_cid asc',
			//'mapping_limit' => 5,
		)
	);
	//别名处理
	public function special_ename(){
		if (!$_POST['special_ename']) {
			return ff_pinyin(trim($_POST["special_name"]));
		}else{
			return trim($_POST["special_ename"]);
		}
	}
	public function m_addtime(){
		if ($_POST['checktime']) {
			return time();
		}else{
			return strtotime($_POST['special_addtime']);
		}
	}
	public function special_content($content){
		return ff_content_img($content,'special');
	}
	
	// 通过ID查询详情数据
	public function ff_find($field = '*', $where, $cache_name=false, $relation=true, $order=false){
		//md5处理KEY
		if($cache_name){
			$cache_name = md5(C('cache_foreach_prefix').$cache_name);
		}
		//优先缓存读取数据
		if( C('cache_page_special') && $cache_name){
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
			if( C('cache_page_special') && $cache_name ){
				S($cache_name, $info, intval(C('cache_page_special')));
			}
    	return $info;
    }
		$this->error = '数据不存在！';
		return false;
	}
	
	// 新增或更新
	public function update($data){
		// 创建安全数据对象TP
		$data = $this->create($data);
		if(false === $data){
			$this->error = $this->getError();
			return false;
		}
		/* 添加或新增行为 */
		if(empty($data['special_id'])){
			$data['special_id'] = $this->add();
			if(!$data['special_id']){
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
		//多分类
		D('Tag')->tag_update($data['special_id'], $data["special_type"], 'special_type');
		return $data;
	}	
}
?>