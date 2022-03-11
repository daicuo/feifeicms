<?php 
class NewsModel extends RelationModel {
	private $news_id;
	// 自动验证
	protected $_validate=array(
	  array('news_cid','number','请选择分类！',1,'',3),
		array('news_cid','ff_list_isson','请选择当前分类下面的子栏目！',1,'function',3),
		array('news_name','require','文章标题必须填写！',1,'',3),
		array('news_ename','','链接别名标识重复，请重新填写',2,'unique',3),
	);
	// 自动完成
	protected $_auto=array(
		array('news_name','trim',3,'function'),
		array('news_remark','get_remark',3,'callback'),
		array('news_ename','a_ename',3,'callback'),
		array('news_letter','a_letter',3,'callback'),
		array('news_addtime','a_addtime',3,'callback'),
		array('news_pic','a_pic',3,'callback'),
		array('news_content','a_content',3,'callback'),
	);
	// 关联定义
	protected $_link = array(
		'List'=>array(
			'mapping_type' => BELONGS_TO,
			'class_name'=> 'List',
			'mapping_name'=>'List',//数据对像映射名称
			'foreign_key' => 'news_cid',
			'parent_key' => 'list_id',
			'condition' => 'list_status = 1',
			'as_fields' =>'list_id,list_pid,list_name,list_dir,list_title,list_keywords,list_description,list_copyright,list_skin_detail,list_extend',
		),
		'Tag'=>array(
			'mapping_type' => HAS_MANY,
			'class_name'=> 'Tag',
			'mapping_name'=>'Tag',//数据对像映射名称
			'foreign_key' => 'tag_id',
			'parent_key' => 'news_id',
			'mapping_fields' => 'tag_id,tag_cid,tag_name,tag_ename,tag_list',
			'condition' => "tag_cid in(3,4)",
			'mapping_order' => 'tag_cid asc',
			//'mapping_limit' => 5,
		),
	);	
	// 自动添加简介
	public function get_remark(){
		if(empty($_POST['news_remark'])){
			return msubstr(trim($_POST['news_content']),0,100,'utf-8',false);
		}else{
			return trim($_POST['news_remark']);
		}
	}
	// 别名
	public function a_ename(){
		if (!$_POST['news_ename']) {
			return ff_pinyin(trim($_POST["news_name"]));
		}else{
			return trim($_POST["news_ename"]);
		}
	}
	// 取首字母
	public function a_letter(){
		if (!$_POST['news_letter']) {
			return ff_url_letter(trim($_POST["news_name"]));
		}else{
			return trim($_POST['news_letter']);
		}
	}
	// 更新时间
	public function a_addtime(){
		if ($_POST['checktime']) {
			return time();
		}else{
			return strtotime($_POST['addtime']);
		}
	}
	//图片处理
	public function a_pic(){
		return D('Img')->down_load(trim($_POST["news_pic"]), 'news');
	}
	//内容处理
	public function a_content($content){
		return ff_content_img($content,'news');
	}
	
	// 通过ID查询详情数据
	public function ff_find($field = '*', $where, $cache_name=false, $relation=true, $order=false){
		//md5处理KEY
		if($cache_name){
			$cache_name = md5(C('cache_foreach_prefix').$cache_name);
		}
		//优先缓存读取数据
		if( C('cache_page_news') && $cache_name){
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
			if( C('cache_page_news') && $cache_name ){
				S($cache_name, $info, intval(C('cache_page_news')));
			}
    	return $info;
    }
		$this->error = '数据不存在！';
		return false;
	}
	
	// 新增或更新
	public function update($data){
		//自动获取关键词tag
		if(empty($data["news_keywords"]) && C('collect_tags')){
			$data["news_keywords"] = ff_tag_auto($data["news_name"],$data["news_content"]);
		}
		// 创建安全数据对象TP
		$data = $this->create($data);
		if(false === $data){
			$this->error = $this->getError();
			return false;
		}
		/* 添加或新增行为 */
		if(empty($data['news_id'])){
			$data['news_id'] = $this->add();
			if(!$data['news_id']){
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
		// 多分类处理
		D('Tag')->tag_update($data['news_id'],$data["news_type"],'news_type');
		// TAG关系处理
		D('Tag')->tag_update($data['news_id'],$data["news_keywords"],'news_tag');
		return $data;
	}					
}
?>