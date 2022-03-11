<?php
/*项目入口根模块*/
class AllAction extends Action{
  // 构造函数
  public function _initialize(){
    header("Content-Type:text/html; charset=utf-8");
  }
	// 栏目分类
	public function Lable_List($params, $array){
		$array['list_page'] = $params['page'];
		$array['list_ajax'] = $params['ajax'];
		if (empty($array['list_skin'])) {
			$array['list_skin'] = 'list';
		}
		if($params['ajax']){
			$array['list_skin'] .= '_ajax';
		}
		$array['site_sid'] = $array['list_sid'];
		$array['list_skin'] = ucfirst(ff_sid2module($array['list_sid'])).':'.$array['list_skin'];
		return $array;
  }
	// 栏目筛选
	public function Lable_Select($params, $array){
		foreach($params as $key=>$value){
			if(in_array($key, array('id','cid','sid','type','ename','area','year','star','state','ispay','gender','profession','letter','language','order','limit','page','ajax')) ){
				$array['select_'.$key] = $value;
			}
		}
		if (empty($array['list_skin_type'])) {
			$array['list_skin_type'] = 'type';
		}
		if($params['ajax']){
			$array['list_skin_type'] .= '_ajax';
		}
		$array['site_sid'] = $array['list_sid'];
		$array['list_skin_type'] = ucfirst(ff_sid2module($array['list_sid'])).':'.$array['list_skin_type'];
		return $array;
  }
	// 搜索定义
	public function Lable_Search($params, $sid = 1){
		$array = array();
		foreach($params as $key=>$value){
			if(in_array($key, array('wd','name','ename','title','actor','director','writer','play','inputer','limit','page','order','ajax')) ){
				$array['search_'.$key] = $value;
			}
		}
		$array['search_skin'] = 'search';
		if($params['ajax']){
			$array['search_skin'] .= '_ajax';
		}
		$array['search_skin'] = ucfirst(ff_sid2module($sid)).':'.$array['search_skin'];
		$array['site_sid'] = $sid;
		return $array;
  }	
	// 标签话题
	public function Lable_Tags($params, $sid = 1){
		$params = ff_param_url();
		foreach($params as $key=>$value){
			if(in_array($key, array('name','id','type','tag','cid','ename','page','ajax')) ){
				$array['tag_'.$key] = $value;
			}
		}
		$array['tag_module'] = $module;
		$array['tag_skin'] = 'tags';
		if($params['ajax']){
			$array['tag_skin'] .= '_ajax';
		}
		$array['tag_skin'] = ucfirst(ff_sid2module($sid)).":".$array['tag_skin'];
		return $array;
	}
	/*****************影视内容,播放页公用变量定义******************************
	* @$array/具体的内容信息
	* @$array_play 为解析播放页
	* @返回赋值后的arrays 多维数组*/
	public function Lable_Vod_Read($array){
		$array['vod_hits_insert'] = ff_get_hits('vod','insert',$array);
		$array['vod_hits_all'] = ff_get_hits('vod','vod_hits',$array);
		$array['vod_hits_month'] = ff_get_hits('vod','vod_hits_month',$array);
		$array['vod_hits_week'] = ff_get_hits('vod','vod_hits_week',$array);
		$array['vod_hits_day'] = ff_get_hits('vod','vod_hits_day',$array);
		if($array['vod_skin']){
			$array['vod_skin_detail'] = 'Vod:'.trim($array['vod_skin']);
			$array['vod_skin_play'] = 'Vod:'.trim($array['vod_skin']).'_play';
		}else{
			$array['vod_skin_detail'] = !empty($array['list_skin_detail']) ? 'Vod:'.$array['list_skin_detail'] : 'Vod:detail';
			$array['vod_skin_play'] = !empty($array['list_skin_play']) ? 'Vod:'.$array['list_skin_play'] : 'Vod:play';
		}
		//unset($array['vod_server'], $array['vod_play'], $array['vod_url']);
		$array['site_sid'] = 1;
		return $array;
	}
	/*****************影视播放页变量定义 适用于动态与合集为一个播放页******************************<br />
	* @$array 内容页解析好后的内容页变量 arrays['read']
	* @$array_play 为播放页URL参数 array('id'=>558,'sid'=>1,'pid'=>1)
	* @返回$array 内容页重新组装的数组*/
	public function Lable_Vod_Play($array, $array_play){
		// 点击数调用
		$array['vod_hits_month'] = ff_get_hits('vod','vod_hits_month',$array,C('url_html_play'));
		$array['vod_hits_week'] = ff_get_hits('vod','vod_hits_week',$array,C('url_html_play'));
		$array['vod_hits_day'] = ff_get_hits('vod','vod_hits_day',$array,C('url_html_play'));
		// 播放器相关默认配置
		$array['play_id'] = $array_play['id'];
		$array['play_sid'] = $array_play['sid'];
		$array['play_pid'] = $array_play['pid'];
		$array['play_buffer'] = C('play_buffer');
		$array['play_pause'] = C('play_pause');
		$array['play_second'] = intval(C('play_second'));
		$array['play_jiexi'] = trim(C('play_jiexi'));
		// 通过sid定义到当前播放器组的相关变量
		$play = $array['vod_play_list'][$array_play['sid']];
		$array['play_name_en'] = $play['player_name_en'];
		$array['play_name_zh'] = $play['player_name_zh'];
		$array['play_copygiht'] = intval($play['player_copyright']);//播放器组定义的版权
		$array['play_info'] = $play['player_info'];
		$array['play_title'] = $play['son'][$array['play_pid']-1]["title"];
		$array['play_url'] = $play['son'][$array['play_pid']-1]["url"];
		$array['play_url_next'] = $play['son'][$array['play_pid']]["url"];
		$array['play_url_prev'] = $play['son'][$array['play_pid']-2]["url"];
		$array['play_count'] = count($play['son']);
		//指定播放器处理
		if($play['son'][$array['play_pid']-1]["player"]){
			$array['play_name_en'] = $play['son'][$array['play_pid']-1]["player"];
		}
		//解析服务器变量处理
		if($play['player_jiexi']){
			$array['play_jiexi'] = trim($play['player_jiexi']);
		}
		if($array['play_jiexi']){
			$array['play_jiexi'] = str_replace('{name}', $array['play_name_en'], $array['play_jiexi']);
		}
		//付费点播处理
		$array['play_ispay'] = 0;
		$array['play_price'] = 0;
		$array['play_trysee'] = 0;
		if($array['list_ispay']){
			$array['play_ispay'] = intval($array['list_ispay']);
		}
		if($array['list_price']){
			$array['play_price'] = intval($array['list_price']);
		}
		if($array['list_trysee']){
			$array['play_trysee'] = intval($array['list_trysee']);
		}
		if($array['vod_ispay']){
			$array['play_ispay'] = intval($array['vod_ispay']);
		}
		if($array['vod_price']){
			$array['play_price'] = intval($array['vod_price']);
		}
		if($array['vod_trysee']){
			$array['play_trysee'] = intval($array['vod_trysee']);
		}
		//播放器调用
		if($array['play_ispay'] || $array['vod_price']){//付费
			$array['vod_player'] = '<div id="cms-player-vip"><div class="cms-player-box">VIP影片，需验证观看权限，请稍等。</div><iframe class="embed-responsive-item cms-player-iframe" src="'.ff_url('vod/vip',array('action'=>'play','id'=>$array['vod_id'],'sid'=>$array['play_sid'],'pid'=>$array['play_pid'],true)).'" width="100%" height="100%" frameborder="0" scrolling="no" allowfullscreen="true" allowtransparency="true"></iframe></div>';
		}else{//免费
			//copyright 版权处理
			if($array['list_copyright'] > 0){
				$array['play_copygiht'] = intval($array['list_copyright']);
			}else if($array['list_copyright'] < 0){
				$array['play_copygiht'] = 0;
			}
			if($array['vod_copyright'] > 0){
				$array['play_copygiht'] = intval($array['vod_copyright']);
			}else if($array['vod_copyright'] < 0){
				$array['play_copygiht'] = 0;
			}
			$array['vod_player'] = ff_player($array);
		}
		$array['site_sid'] = 1;
		return $array;
	}
	//VIP框架播放
	public function Lable_Vod_Play_Vip($array, $array_play){
		$vip = array();
		// 播放器相关默认配置
		$vip['play_id'] = $array['vod_id'];
		$vip['play_sid'] = $array_play['sid'];
		$vip['play_pid'] = $array_play['pid'];
		$vip['play_status'] = 200;
		$vip['play_buffer'] = C('play_buffer');
		$vip['play_pause'] = C('play_pause');
		$vip['play_second'] = intval(C('play_second'));
		$vip['play_jiexi'] = trim(C('play_jiexi'));
		$vip['play_copygiht'] = 0;
		$vip['play_ispay'] = 0;
		$vip['play_price'] = 0;
		$vip['play_trysee'] = 0;
		// 通过sid定义到当前播放器组的相关变量
		$play = $array['vod_play_list'][$array_play['sid']];
		$vip['play_url'] = $play['son'][$array_play['pid']-1]["url"];
		$vip['play_url_next'] = $play['son'][$array_play['pid']]["url"];
		//指定播放器处理
		$vip['play_name_en'] = $play['player_name_en'];
		if($play['son'][$array_play['pid']-1]["player"]){
			$vip['play_name_en'] = $play['son'][$array_play['pid']-1]["player"];
		}
		//解析服务器变量处理
		if($play['player_jiexi']){
			$vip['play_jiexi'] = trim($play['player_jiexi']);
		}
		if($vip['play_jiexi']){
			$vip['play_jiexi'] = str_replace('{name}', $vip['play_name_en'], $vip['play_jiexi']);
		}
		//vip包月
		if($array['list_ispay']){
			$vip['play_ispay'] = intval($array['list_ispay']);
		}
		if($array['vod_ispay']){
			$vip['play_ispay'] = intval($array['vod_ispay']);
		}
		//影币处理
		if($array['list_price']){
			$vip['play_price'] = intval($array['list_price']);
		}
		if($array['vod_price']){
			$vip['play_price'] = intval($array['vod_price']);
		}
		//试看处理
		if($array['list_trysee']){
			$vip['play_trysee'] = intval($array['list_trysee']);
		}
		if($array['vod_trysee']){
			$vip['play_trysee'] = intval($array['vod_trysee']);
		}
		//播放器调用
		$vip['vod_player'] = ff_player($vip);
		return $vip;
	}
	// 分集变量定义
	public function Lable_Vod_Scenario($array, $params){
		//$array = array();
		foreach($params as $key=>$value){
			if(in_array($key, array('id','pid')) ){
				$array['scenario_'.$key] = $value;
			}
		}
		$array['scenario_skin'] = str_replace('Vod:detail','Scenario:detail',$array['vod_skin_detail']);
		if($params['pid']){
			$array['scenario_skin'] .= '_pid';
		}
		if($params['ajax']){
			$array['scenario_skin'] .= '_ajax';
		}
		$array['site_sid'] = 7;
		return $array;
  }
	//资讯内容页变量定义
	public function Lable_News_Read($params, $array){
		$array['news_hits_insert'] = ff_get_hits('news','insert',$array);
		$array['news_hits_all'] = ff_get_hits('news','news_hits',$array);
		$array['news_hits_month'] = ff_get_hits('news','news_hits_month',$array);
		$array['news_hits_week'] = ff_get_hits('news','news_hits_week',$array);
		$array['news_hits_day'] = ff_get_hits('news','news_hits_day',$array);
		//正则分割是否有分页
		$array_content = preg_split("/<div style=\"page-break-after([\s\S]*?)\">([\s\S]*?)<\/div>/", $array['news_content']);
		$array['news_page'] = $params['page'];
		$array['news_page_count'] = count($array_content);
		$array['news_content'] = $array_content[$params['page']-1];
		//模板路径
		if(empty($array['news_skin'])){
			$array['news_skin_detail'] = !empty($array['list_skin_detail']) ? $array['list_skin_detail'] : 'detail';
		}
		if($params['ajax']){
			$array['news_skin_detail'] .= '_ajax';
		}
		$array['news_skin_detail'] = 'News:'.$array['news_skin_detail'];
		$array['site_sid'] = 2;
		return $array;
	}
	//专题内容页变量定义
	public function Lable_Special_Read($array){
		$array_ids = array();$where = array();
		$array['special_skin'] = !empty($array['special_skin']) ? 'Special:'.$array['special_skin'] : 'Special:detail';
		$array['special_hits_insert'] = ff_get_hits('special','insert',$array);
		$array['special_hits_all'] = ff_get_hits('special','special_hits',$array);
		$array['special_hits_month'] = ff_get_hits('special','special_hits_month',$array);
		$array['special_hits_week'] = ff_get_hits('special','special_hits_week',$array);
		$array['special_hits_day'] = ff_get_hits('special','special_hits_day',$array);
		$array['site_sid'] = 3;
		return $array;
	}
	//留言详情页解析
	public function Lable_Guestbook_Read($array){
		$array['guestbook_skin'] = 'Guestbook:detail';
		$array['site_sid'] = 5;
		return $array;
  }	
	//评论详情页解析
	public function Lable_Forum($params){
		$array = array();
		foreach($params as $key=>$value){
			if(in_array($key, array('id','cid','sid','uid','pid','page')) ){
				$array['forum_'.$key] = $value;
			}
		}
		$array['site_sid'] = 6;
		return $array;
  }	
	public function Lable_Forum_Read($array){
		$array['forum_skin'] = 'Forum:detail_'.ff_sid2module($array['forum_sid']);
		$array['site_sid'] = 6;
		return $array;
  }
	//人物详情页解析
	public function Lable_Person_Read($array, $sid=8){
		$array['person_hits_insert'] = ff_get_hits('person','insert',$array);
		$array['person_hits_all'] = ff_get_hits('person','person_hits',$array);
		$array['person_hits_month'] = ff_get_hits('person','person_hits_month',$array);
		$array['person_hits_week'] = ff_get_hits('person','person_hits_week',$array);
		$array['person_hits_day'] = ff_get_hits('person','person_hits_day',$array);
		//模板路径
		if(empty($array['person_skin'])){
			$array['person_skin'] = !empty($array['list_skin_detail']) ? $array['list_skin_detail'] : 'detail';
		}
		if($params['ajax']){
			$array['person_skin'] .= '_ajax';
		}
		$array['person_skin'] = ucfirst(ff_sid2module($sid)).':'.$array['person_skin'];
		$array['site_sid'] = $sid;
		return $array;
  }
	//首页标签定义
	public function Lable_Index(){
		$array = array();
		if(!C('site_title')){
			$array['site_title'] = C('site_name').'_网站首页';
		}
		return $array;
	}
	//全局标签定义
	public function Lable_Style(){
		C('TOKEN_ON',false);//C('TOKEN_NAME','form_'.$array['model']);取消前端的表单令牌
		$array = array();
		$array['root'] = C('site_path');	
		$array['model'] = strtolower(MODULE_NAME);
		$array['action'] = strtolower(ACTION_NAME);	
		$array['public_path'] = $array['root'].'Public/';	
		$array['tpl_path'] = $array['root'].str_replace('./','',TEMPLATE_PATH).'/';	
		$array['site_name'] = C('site_name');
		$array['site_domain'] = C('site_domain');
		$array['site_domain_m'] = C('site_domain_m');
		$array['site_url'] = 'http://'.C('site_domain');
		$array['site_title'] = C('site_title');
		$array['site_keywords'] = C('site_keywords');
		$array['site_description'] = C('site_description');
		$array['site_email'] = C('site_email');
		$array['site_copyright'] = C('site_copyright');
		$array['site_tongji'] = C('site_tongji');
		$array['site_icp'] = C('site_icp');
		$array['site_hot'] = ff_site_hot();	
		$array['site_sid'] = intval(ff_module2sid($array['model']));
		$user = ff_user_cookie();
		$array['site_user_id'] = $user['user_id'];
		$array['site_user_name'] = $user['user_name'];
		unset($user);
		return $array;		
	}
	public function CreateIndex(){
		$this->assign($this->Lable_Index());
		@$this->buildHtml("index",'./','Home:index');
	}
	public function CreateCategory($nextkey=0,$page=1,$cid=0){
		$params = array();
		$params['field'] = '*';
		$params['limit'] = false;
		$params['order'] = 'list_id';
		$params['sort'] = 'asc';
		$where = array();
		$where['list_sid'] = array('in','1,2');
		if($cid){ 
			$where['list_id'] = array('eq',$cid);
		}
		$infos = D("List")->ff_select_page($params,$where);
		//过滤数据(不需要或断点记录已生成)
		for($i=0; $i<$nextkey; $i++){
			unset($infos[$i]);
		}
		//任务开始
		ff_create_statusSet('category','ing');
		foreach($infos as $key=>$value){
			F('_create/category', array('nextKey'=>$key,'nextPage'=>$page,'nextCid'=>$cid) );//断点记录
			$totalpages = $this->CreateCategoryHtml($value, $page);
			for($i=($page+1);$i<=$totalpages;$i++){
				if(ff_create_statusGet('category')=='ing'){
					F('_create/category', array('nextKey'=>$key,'nextPage'=>$i,'nextCid'=>$cid) );//断点记录
					$this->CreateCategoryHtml($value, $i);//生成网页
				}else{
					return false;//检查任务是否取消break;
				}
			}
		}
		ff_create_statusSet('category','end');//任务完成
		F('_create/category', NULL);//清除断点记录
	}
	public function CreateCategoryHtml($info, $page){
		ob_start();
		$_GET["ff_page_list"]["totalpages"] = 1;//初始化总页数
		$path = ff_url_build('list/read', array('id'=>$info['list_id'], 'list_dir'=>$info['list_dir'], 'p'=>$page));//保存目录
		$info = $this->Lable_List(array('page'=>$page), $info);
		$this->assign($info);
		@$this->buildHtml(ltrim($path,C('site_path')), './', $info['list_skin']);
		ob_end_flush();
		unset($info);
		return intval($_GET["ff_page_list"]["totalpages"]);
		//echo '分类ID（'.$info['list_id'].'）<a href="'.$path.C('html_file_suffix').'" target="_blank">'.$path.C('html_file_suffix').'</a> OK>';
		//ob_flush();flush();
	}
	public function CreateVod($nextkey=0,$page=1,$cid=0,$hour=0){
		$where = array();//条件
		$where['vod_status'] = array('eq',1);
		if($cid){ $where['vod_cid'] = array('eq',$cid); }
		if($hour){ $where['vod_addtime'] = array('gt',time()-$hour*3600); }
		$totalpages = ceil(D('Vod')->where($where)->count('vod_id')/30);
		ff_create_statusSet('vod','ing');//任务开始
		for($i=$page; $i<=$totalpages; $i++){//减小内存占用，循环查询
			$params = array();
			$params['field'] = 'vod_id';
			$params['limit'] = 30;
			$params['order'] = 'vod_id';
			$params['sort'] = 'desc';
			$params['page_is'] = true;
			$params['page_id'] = 'item';
			$params['page_p'] = $i;
			$infos = D('Vod')->ff_select_page($params, $where);
			for($n=0; $n<$nextkey; $n++){
				unset($infos[$n]);
			}
			foreach($infos as $key=>$value){
				if(ff_create_statusGet('vod') == 'ing'){
					F('_create/vod', array('nextKey'=>$key,'nextPage'=>$i,'nextCid'=>$cid,'nextHour'=>$hour,'totalPages'=>$totalpages) );//断点记录
					$this->CreateVodDb($value['vod_id']);//生成网页
				}else{
					return false;
				}
			}
		}
		unset($infos);
		ff_create_statusSet('vod','end');//任务完成
		F('_create/vod', NULL);//清除断点
	}
	public function CreateVodDb($vod_id,$return=false){
		$where = array();
		$where['vod_id'] = array('eq', $vod_id);
		$where['vod_status'] = array('eq', 1);
		if( $info = D('Vod')->ff_find('*', $where, 'cache_page_vod_'.$vod_id, true) ){
			if($return){
				return $info;
			}else{
				return $this->CreateVodHtml($info);
			}
		}else{
			return NULL;
		}
	}
	public function CreateVodHtml($info){
		ob_start();
		$path = ff_url_build('vod/read', array('list_id'=>$info['list_id'],'list_dir'=>$info['list_dir'],'pinyin'=>$info['vod_ename'],'id'=>$info['vod_id']));
		$info = $this->Lable_Vod_Read($info);
		$this->assign($info);
		@$this->buildHtml(ltrim($path,C('site_path')), './', $info['vod_skin_detail']);
		//是否生成播放页
		if(C('url_vod_play')){
			foreach($info["vod_play_list"] as $sid=>$valueS){
				foreach($valueS['son'] as $pid=>$valueJ){
					$path = ff_url_build('vod/play', array('list_id'=>$info['list_id'],'list_dir'=>$info['list_dir'],'pinyin'=>$info['vod_ename'],'id'=>$info['vod_id'],'sid'=>$sid,'pid'=>($pid+1)));
					$this->assign($this->Lable_Vod_Play($info, array('id'=>$info['vod_id'], 'sid'=>$sid, 'pid'=>($pid+1))));
					@$this->buildHtml(ltrim($path,C('site_path')), './', $info['vod_skin_play']);
				}
			}
		}
		ob_end_flush();
		unset($info);
	}
	public function CreateNews($nextkey=0,$page=1,$cid=0,$hour=0){
		$where = array();
		$where['news_status'] = array('eq',1);
		if($cid){ $where['news_cid'] = array('eq',$cid); }
		if($hour){ $where['news_addtime'] = array('gt',time()-$hour*3600); }
		$totalpages = ceil(D('News')->where($where)->count('news_id')/100);
		ff_create_statusSet('news','ing');
		for($i=$page; $i<=$totalpages; $i++){
			$params = array();
			$params['field'] = 'news_id';
			$params['limit'] = 100;
			$params['order'] = 'news_id';
			$params['sort'] = 'desc';
			$params['page_is'] = true;
			$params['page_id'] = 'item';
			$params['page_p'] = $i;
			$infos = D('NewsView')->ff_select_page($params, $where);
			for($n=0; $n<$nextkey; $n++){
				unset($infos[$n]);
			}
			foreach($infos as $key=>$value){
				if(ff_create_statusGet('news')=='ing'){
					F('_create/news', array('nextKey'=>$key,'nextPage'=>$i,'nextCid'=>$cid,'nextHour'=>$hour,'totalPages'=>$totalpages) );//断点记录
					$this->CreateNewsDb($value['news_id']);//生成网页
				}else{
					return false;
				}
			}
		}
		unset($infos);
		ff_create_statusSet('news','end');
		F('_create/news', NULL);
	}
	public function CreateNewsDb($news_id,$return=false){
		$where = array();
		$where['news_id'] = array('eq', $news_id);
		$where['news_status'] = array('eq', 1);
		if( $info = D('News')->ff_find('*', $where, 'cache_page_news_'.$news_id, true) ){
			if($return){
				return $info;
			}else{
				return $this->CreateNewsHtml($info);
			}
		}else{
			return NULL;
		}
	}
	public function CreateNewsHtml($info){
		ob_start();
		$info_first = $this->Lable_News_Read(array('page'=>1),$info);
		$this->assign($info_first);
		@$this->buildHtml(ltrim(ff_url_build('news/read',array('list_id'=>$info['list_id'],'list_dir'=>$info['list_dir'],'pinyin'=>$info['news_ename'],'id'=>$info['news_id'],'p'=>1)),C('site_path')), './', $info_first['news_skin_detail']);
		//多页生成
		for($i=2; $i<=$info_first['news_page_count']; $i++){
			$info_for = $this->Lable_News_Read(array('page'=>$i),$info);
			$this->assign($info_for);
			@$this->buildHtml(ltrim(ff_url_build('news/read',array('list_id'=>$info['list_id'],'list_dir'=>$info['list_dir'],'pinyin'=>$info['news_ename'],'id'=>$info['news_id'],'p'=>$i)),C('site_path')), './', $info_for['news_skin_detail']);
			unset($info_for);
		}
		ob_end_flush();
		unset($info_first);
		unset($info);
	}	
}
?>