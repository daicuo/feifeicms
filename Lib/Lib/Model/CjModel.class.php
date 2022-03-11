<?php
class CjModel extends Model {
	// 分页采集跳转
	public function jump($page, $pagemax, $pagelink){
		if($page < $pagemax){
			//缓存断点续采并跳转到下一页
			$jumpurl = str_replace('FFLINK',($page+1), $pagelink);
			F('_cj/xucai',$jumpurl);
			echo '<meta http-equiv="refresh" content='.C('collect_time').';url='.$jumpurl.'>';
			echo '<h5>'.C('collect_time').'秒后将自动采集下一页!</h5>';
		}else{
			//清除断点续采
			F('_cj/xucai',NULL);
			echo '<h5>恭喜您，所有采集任务已经完成。</h5>';
		}
	}
	//视频库采集
	public function vod($admin, $params){
		$params['g'] = 'plus';
		$params['m'] = 'api';
		$params['a'] = 'json';
		$params['p'] = $admin['page'];
		$params['key'] = $admin['apikey'];
		ksort($params);
		$url = base64_decode($admin['xmlurl']).'?'.http_build_query($params);
		if($admin['xmltype'] == 'xml'){
			return $this->vod_xml($admin, $params);
		}elseif($admin['xmltype'] == 'json'){
			return $this->vod_json($admin, $params);
		}else{
			$data = $this->vod_json($admin, $params);
			if($data['status'] == 200){
				return $data;
			}else{
				return $this->vod_xml($admin, $params);
			}
		}
	}
	public function vod_update($admin, $params, $json){
		echo'<style type="text/css">
			ul{margin:0 auto; width:60%;border:1px solid #666;}
			h5{text-align:center;}
			li{font-size:12px;color:#333;line-height:21px}
			li.p{color:#666;list-style:none;}
			span{font-weight:bold;color:#FF0000}
			</style><ul>
			<h5>API视频采集 共有<span>'.$json['page']['recordcount'].'</span>个数据，需要采集<span>'.$json['page']['pagecount'].'</span>次，正在执行第<span color=green>'.$admin['page'].'</span>次采集任务，每一次采集<span>'.$json['page']['pagesize'].'</span>个。</h5>';
		// 重采资料或是采集入库
		if($admin['field']){
			$field = explode(',',$admin['field']);
			foreach($json['data'] as $key=>$vod){
				$data = array();
				foreach($field as $keyfield=>$value){
					$data[$value] = $vod[$value];
				}
				$status = $this->vod_db_field($data, $vod['vod_reurl']);
				if($status){
					echo '<li>重采['.ff_list_find($vod['vod_cid']).'] '.$vod['vod_name'].' '.$array_url['field'].'更新完成。</li>';
				}else{
					echo '<li>重采['.ff_list_find($vod['vod_cid']).'] '.$vod['vod_name'].' 跳过、不做任何处理。</li>';
				}
				ob_flush();flush();
			}
		}else{
			foreach($json['data'] as $key=>$vod){
				echo '<li>第<span>'.(($admin['page']-1)*$json['page']['pagesize']+$key+1).'</span>个影片 ['.ff_list_find($vod['vod_cid']).'] '.$vod['vod_name'].'</li>';
				$this->vod_db($vod);
				ob_flush();flush();
			}
		}
		//是否分页采集
		if( in_array($admin['action'], array('days','all','post')) ){
			$admin['g'] = 'admin';
			$admin['m'] = 'cj';
			$admin['a'] = 'apis';
			$admin['page'] = 'FFLINK';
			$page_link = '?'.http_build_query(array_merge($admin, $params));
			$this->jump($json['page']["pageindex"], $json['page']['pagecount'], $page_link);
		}
		echo'</ul>';
	}
	//视频采集入库调用接口，必需要有vod_reurl字段
	public function vod_db($vod){
		//去除资源站视频ID与写入资源站编辑标识
		unset($vod["vod_id"]);
		//必填字段验证
		if(empty($vod['vod_name']) || empty($vod['vod_url']) || empty($vod['vod_play'])){
			echo '<li class="p">影片名称或播放器名称或播放地址为空，不做处理!</li>';
			return false;
		}
		//是否绑定分类验证
		if(!$vod['vod_cid']){
			echo '<li class="p">未匹配到对应栏目分类，不做处理，请先转换分类!</li>';
			return false;
		}
		//来源标识验证
		if(!$vod['vod_reurl']){
			echo '<li class="p">来源标识为空，不做处理!</li>';
			return false;
		}
		//3.5后改为一次性修改不再单独一个一个检查
		$array_vod_old = $this->vod_db_find($vod);
		if($array_vod_old['vod_id']){
			$status = '<li class="p"><strong>编辑：</strong>'.$this->vod_db_update($vod, $array_vod_old).'</li>';
		}else{
			$status = '<li class="p"><strong>新增：</strong>'.$this->vod_db_insert($vod).'</li>';
		}
		echo $status;
	}
	//检测是否已存在相同影片
	private function vod_db_find($vod){
		// 要查询检查的字段
		$field = 'vod_id,vod_cid,vod_name,vod_title,vod_actor,vod_continu,vod_isend,vod_total,vod_inputer,vod_douban_id,vod_play,vod_url';
		// 按来源检测
		$array = M('Vod')->field($field)->where('vod_reurl="'.$vod['vod_reurl'].'"')->order('vod_id desc')->find();
		if($array){
			return $array;
		}
		// 按豆瓣检测
		if($vod['vod_douban_id']){
			if( $array = M('Vod')->field($field)->where('vod_douban_id="'.$vod['vod_douban_id'].'"')->order('vod_id desc')->find() ){
				return $array;
			}
		}
		// 按标题检测
		$where = array();
		$where['vod_cid'] = array('eq', $vod['vod_cid']);
		$where['vod_name'] = array('like', $vod['vod_name'].'%');
		$array_list = M('Vod')->field($field)->where($where)->limit(20)->order('vod_id desc')->select();
		foreach($array_list as $key=>$value){
			// 有相同标题是否需再次验证主演
			if($value['vod_name'] == $vod['vod_name']){
				if( C('collect_actor') ){
					$arr_actor_1 = explode(',', ff_xml_vodactor($vod['vod_actor']));
					$arr_actor_2 = explode(',', ff_xml_vodactor($value['vod_actor']));
					if( array_intersect($arr_actor_1,$arr_actor_2) ){
						return $array_list[$key];
					}else{
						$vod['vod_status'] = -1;//标识为需人工验证审核
						return $vod;
					}
				}else{
					return $value;
				}
			}
		}
		return $vod;
	}
	//新增影片
	private function vod_db_insert($vod){
		$vod['vod_addtime'] = time();
		$vod['vod_letter'] 	= ff_url_letter($vod['vod_name']);
		$vod['vod_keywords'] 	= trim($vod['vod_keywords']);
		if(empty($vod['vod_ename'])){
			$vod['vod_ename'] = ff_pinyin($vod['vod_name']);
		}
		if(empty($vod['vod_hits'])){
			$vod['vod_hits'] = mt_rand(0, C('collect_hits'));
		}
		if(empty($vod['vod_up'])){
			$vod['vod_up'] = mt_rand(0, C('collect_updown'));
		}
		if(empty($vod['vod_down'])){
			$vod['vod_down'] = mt_rand(0, C('collect_updown'));
		}
		if( empty($vod['vod_gold']) ){
			$vod['vod_gold'] = mt_rand(0, C('collect_gold'));
		}
		if( empty($vod['vod_golder']) ){
			$vod['vod_golder'] = mt_rand(0, C('collect_golder'));
		}
		// 播放器处理（去掉未定义或隐藏的播放器）
		$play_list_db = $this->vod_array2url($this->vod_url2array($vod['vod_play'], $vod['vod_url']));
		if(!$play_list_db["vod_play"]){
			return '播放来源('.$vod['vod_play'].')未添加或隐藏，此影片跳过！';
		}
		$vod['vod_play'] = $play_list_db['vod_play'];
		$vod['vod_url'] = $play_list_db['vod_url'];
		unset($play_list_db);
		// 随机伪原创
		if(C('collect_original')){
			$vod['vod_content'] = ff_rand_str($vod['vod_content']);
		}
		// 自动下载远程图片
		$vod['vod_pic'] = D('Img')->down_load($vod['vod_pic']);
		// 视频入库
		$id = M('Vod')->data($vod)->add();
		// 关联多分类及TAG相关
		if($id){
			// 自动获取关键词tag
			if(empty($vod['vod_keywords']) && C('collect_tags')){
				$vod['vod_keywords'] = ff_tag_auto($vod["vod_name"], $vod["vod_content"]);
			}
			// 增加关联tag
			if( $vod['vod_keywords'] ){
				D('Tag')->tag_update($id, $vod["vod_keywords"], 'vod_tag');
			}
			// 增加多分类
			if( $vod['vod_type'] ){
				D('Tag')->tag_update($id, $vod["vod_type"], 'vod_type');
			}
			return '视频添加成功('.$id.')';
		}
		return '视频添加失败：'.M('Vod')->getDbError();
	}
	//根据影片ID更新数据
	private function vod_db_update($vod, $vod_old){
		// 检测是否站长手动锁定更新
		if('feifeicms' == $vod_old['vod_inputer']){
			return '站长已锁定该影片，不更新。';
		}
		// 检测播放地址是否变化
		$play_list_old = $this->vod_url2array($vod_old['vod_play'], $vod_old['vod_url']);
		$play_list_new = $this->vod_url2array($vod['vod_play'], $vod['vod_url']);
		$play_list_merge = array_merge($play_list_old, $play_list_new);//合并新旧地址
		$play_list_db = $this->vod_array2url($play_list_merge);//还原入库字段
		if($vod_old['vod_url'] == $play_list_db['vod_url']){
			return '<font color="blue">播放地址未变化，不需要更新</font>';
		}
		// 组合更新条件及内容(以最后一次更新的库为检测依据)
		$edit['vod_id'] = $vod_old['vod_id'];
		$edit['vod_addtime'] = time();
		$edit['vod_play'] = $play_list_db['vod_play'];
		$edit['vod_url'] = $play_list_db['vod_url'];
		// 存在字段才更新
		if(isset($vod['vod_continu'])){
			$edit['vod_continu'] = $vod['vod_continu'];	
		}
		if(isset($vod['vod_isend'])){
			$edit['vod_isend'] = $vod['vod_isend'];	
		}
		// 排除豆瓣ID
		if(!$vod_old['vod_douban_id']){	
			if(isset($vod['vod_name'])){
				$edit['vod_name'] = $vod['vod_name'];
			}
			if(isset($vod['vod_reurl'])){
				$edit['vod_reurl'] = $vod['vod_reurl'];
			}
			if(isset($vod['vod_inputer'])){
				$edit['vod_inputer'] = $vod['vod_inputer'];
			}
			if(isset($vod['vod_total'])){
				$edit['vod_total'] = $vod['vod_total'];	
			}
			if(isset($vod['vod_filmtime'])){
				$edit['vod_filmtime'] = $vod['vod_filmtime']; 
			}
			if(isset($vod['vod_length'])){ 
				$edit['vod_length'] = $vod['vod_length']; 
			}
			if(isset($vod['vod_state'])){ 
				$edit['vod_state'] = $vod['vod_state']; 
			}
			if(isset($vod['vod_version'])){ 
				$edit['vod_version'] = $vod['vod_version']; 
			}
			if(isset($vod['vod_tv'])){ 
				$edit['vod_tv'] = $vod['vod_tv']; 
			}		
		}
		// 更新数据
		M('Vod')->data($edit)->save();
		//删除数据缓存
		if(C('cache_page_vod')){
			S(md5('cache_page_vod_'.$vod_old['vod_id']),NULL);
		}
		return '<font color="red">旧播放地址已更新</font>';
	}
	//根据来源地址更新资料字字段
	private function vod_db_field($vod, $reurl){
		if($vod['vod_pic']){
			$vod['vod_pic'] = D('Img')->down_load($vod['vod_pic']);
		}
		return M('Vod')->where("vod_inputer !='feifeicms' and vod_reurl='".$reurl."'")->data($vod)->save();
	}
	//vod_play,vod_url格式化：所有播放地址定义为普通数组便于合并 url['youku(playname)-1(sid)-6(jid)'] = 'xxxx'
	private function vod_url2array($vod_play, $vod_url){
		$old_play = explode('$$$',$vod_play);
		$old_url = explode('$$$',$vod_url);
		$old_array = array();
		$sid = array();
		foreach($old_play as $key=>$value){
			$url_one = explode( chr(13), str_replace(array("\r\n", "\n", "\r"), chr(13), $old_url[$key]) );
			foreach($url_one as $key_son=>$value_son){
				$old_array[$value.'-'.intval($sid[$value]).'-'.$key_son] = $value_son;
			}
			$sid[$value] = +1;// 有两组PPTV的情况
		}
		return $old_array;
	}
	//vod_play,vod_url字段还原：将数组定义的所有播放地址还原
	private function vod_array2url($play_list_merge){
		foreach($play_list_merge as $key=>$value){
			list($play_name,$play_sid,$play_pid) = explode('-',$key);
			$play[$play_name.$play_sid] = $play_name;
			$array_url[$play_name.$play_sid][$play_pid] = $value;
		}
		//还原单组播放地址
		foreach($array_url as $key=>$value){
			$url[$key] = implode(chr(13),$value);
		}
		//只添加已定义的播放器
		$array_player = F('_feifeicms/player');
		foreach($play as $key=>$value){
			if(!$array_player[$value]){
				unset($play[$key]);
				unset($url[$key]);
			}
		}
		return array('vod_play'=>implode('$$$',$play),'vod_url'=>implode('$$$',$url));
	}
	//json资源库采集
	private function vod_json($admin, $params){
		$url = base64_decode($admin['xmlurl']).'?'.http_build_query($params);
		$html = ff_file_get_contents($url);
		//是否采集到数据
		if(!$html){
			return array('status'=>601, 'infos'=>'连接API资源库失败，通常为服务器网络不稳定或禁用了采集。');
		}
		//数据包验证
		$json = json_decode($html, true);
		if( is_null($json) ){
			return array('status'=>602, 'type'=>'json', 'infos'=>'JSON格式不正确，不支持采集。');
		}
		//资源库返回的状态501 502 503 3.3版本前没有status字段
		if($json['status'] && $json['status'] != 200){
			return array('status'=>$json['status'], 'type'=>'json', 'infos'=>$json['data']);
		}
		//不是feifeicms的格式
		if(!$json['list']){
			return array('status'=>602, 'type'=>'json', 'infos'=>'不是FeiFeiCms系统的接口，不支持API采集，请改用火车头或其它工具。');
		}
		//返回正确的数据集合
		$json = $this->vod_bind($admin, $json);
		return array('status'=>200, 'type'=>'json', 'infos'=>$json);
	}
	//xml资源库采集
	private function vod_xml($admin, $params){
		$url = array();
		if($admin['action']=='show' && $params['wd']){ 
			$url['ac'] = 'list'; 
		}else{
			$url['ac'] = 'videolist';
		}
		$url['wd'] = $params['wd'];
		$url['t'] = $params['cid'];
		$url['h'] = $params['h'];
		$url['rid'] = $params['play'];
		$url['ids'] = $params['vodids'];
		$url['pg'] = $admin['page'];
		$url_detail = base64_decode($admin['xmlurl']).'?'.http_build_query($url);
		$url_list   = base64_decode($admin['xmlurl']).'?ac=list&t=9999';
		$xml_detail = ff_file_get_contents($url_detail);
		if(!$xml_detail){
			return array('status'=>601, 'infos'=>'连接API资源库失败，通常为服务器网络不稳定或禁用了采集。');
		}
		$xml = simplexml_load_string($xml_detail);
		if( is_null($xml) ){
			return array('status'=>602, 'type'=>'xml', 'infos'=>'XML格式不正确，不支持采集。');
		}
		$key = 0;
		$array_vod = array();
		foreach($xml->list->video as $video){
			$array_vod[$key]['vod_id'] = (string)$video->id;
			$array_vod[$key]['vod_cid'] = (string)$video->tid;
			$array_vod[$key]['vod_name'] = (string)$video->name;
			$array_vod[$key]['vod_title'] = (string)$video->note;
			$array_vod[$key]['list_name'] = (string)$video->type;
			$array_vod[$key]['vod_pic'] = (string)$video->pic;
			$array_vod[$key]['vod_language'] = (string)$video->lang;
			$array_vod[$key]['vod_area'] = (string)$video->area;
			$array_vod[$key]['vod_year'] = (string)$video->year;
			$array_vod[$key]['vod_continu'] = (string)$video->state;
			$array_vod[$key]['vod_actor'] = (string)$video->actor;
			$array_vod[$key]['vod_director'] = (string)$video->director;
			$array_vod[$key]['vod_content'] = (string)$video->des;
			$array_vod[$key]['vod_reurl'] = base64_decode($admin['xmlurl']).'?id='.(string)$video->id;
			$array_vod[$key]['vod_status'] = 1;
			$array_vod[$key]['vod_type'] = str_replace('片','',$array_vod[$key]['list_name']);
			$array_vod[$key]['vod_addtime'] = (string)$video->last;
			$array_vod[$key]['vod_total'] = 0;
			$array_vod[$key]['vod_isend'] = 1;
			if($array_vod[$key]['vod_continu']){
				$array_vod[$key]['vod_isend'] = 0;
			}
			//格式化地址与播放器
			$array_play = array();
			$array_url = array();
			//videolist|list播放列表不同
			if($count=count($video->dl->dd)){
				for($i=0; $i<$count; $i++){
					$array_play[$i] = str_replace('qiyi','iqiyi',(string)$video->dl->dd[$i]['flag']);
					$array_url[$i] = $this->vod_xml_replace((string)$video->dl->dd[$i]);
				}
			}else{
				$array_play[]=(string)$video->dt;
			}
			$array_vod[$key]['vod_play'] = implode('$$$', $array_play);
			$array_vod[$key]['vod_url'] = implode('$$$', $array_url);
			$key++;
		}
		//分页信息
		preg_match('<list page="([0-9]+)" pagecount="([0-9]+)" pagesize="([0-9]+)" recordcount="([0-9]+)">', $xml_detail, $page_array);
		$array_page = array('pageindex'=>$page_array[1], 'pagecount'=>$page_array[2], 'pagesize'=>$page_array[3], 'recordcount'=>$page_array[4]);
		//栏目分类
		$array_list = array();
		if($admin['action'] == 'show'){
			$xml = simplexml_load_string(ff_file_get_contents($url_list));
			$key = 0;
			foreach($xml->class->ty as $list){
				$array_list[$key]['list_id'] = (int)$xml->class->ty[$key]['id'];
				$array_list[$key]['list_name'] = (string)$list;
				$key++;
			}
		}
		$json = $this->vod_bind($admin, array('status'=>200,'page'=>$array_page,'list'=>$array_list,'data'=>$array_vod));
		return array('status'=>200,'type'=>'xml', 'infos'=>$json);
	}
	//xml资源库播放地址格式化
	private function vod_xml_replace($playurl){
		$array_url = array();
		$arr_ji = explode('#',str_replace('||','//',$playurl));
		foreach($arr_ji as $key=>$value){
			$urlji = explode('$',$value);
			if( count($urlji) > 1 ){
				$array_url[$key] = $urlji[0].'$'.trim($urlji[1]);
			}else{
				$array_url[$key] = trim($urlji[0]);
			}
		}
		return implode(chr(13),$array_url);	
	}
	//资源库绑定相关
	private function vod_bind($admin, $json){
		// 读取绑定配置
		$bind = F('_cj/bind');
		// 获取到的远程栏目数据增加对应的绑定ID
		foreach($json['list'] as $key=>$value){
			$json['list'][$key]['bind_key'] = $admin['cjid'].'_'.$value['list_id'];
		}
		// 获取到的远程视频列表数据格式化处理
		foreach($json['data'] as $key=>$value){
			$json['data'][$key]['vod_cid'] = intval($bind[$admin['cjid'].'_'.$value['vod_cid']]);
			$json['data'][$key]['vod_inputer'] = 'xml_'.$admin['cjid'];
			if(!$json['data'][$key]['vod_reurl']){
				$json['data'][$key]['vod_reurl'] = base64_decode($admin['xmlurl']).$json['data'][$key]['vod_id'];
			}
		}
		return $json;
	}
	/*----------------------------------------------------------user---------------------------------------------------------------------------*/	
	public function user_json($admin, $params){
		$params['g'] = 'plus';
		$params['m'] = 'api';
		$params['a'] = 'user';
		$params['p'] = $admin['page'];
		$params['key'] = $admin['apikey'];
		ksort($params);
		$url = base64_decode($admin['xmlurl']).'?'.http_build_query($params);
		$html = ff_file_get_contents($url);
		//是否采集到数据
		if(!$html){
			return array('status'=>601, 'infos'=>'连接API资源库失败，通常为服务器网络不稳定或禁用了采集。');
		}
		//数据包验证
		$json = json_decode($html, true);
		if( is_null($json) ){
			return array('status'=>602, 'infos'=>'JSON格式不正确，不支持采集。');
		}
		//资源库返回的状态501 502 503
		if($json['status'] != 200){
			return array('status'=>$json['status'], 'infos'=>$json['data']);
		}
		//返回正确的数据集合
		return array('status'=>200, 'infos'=>$json);
	}
	public function user_update($admin, $params, $json){
		echo'<style type="text/css">
			ul{margin:0 auto; width:60%;border:1px solid #666;}
			h5{text-align:center;}
			li{font-size:12px;color:#333;line-height:21px}
			li.p{color:#666;list-style:none;}
			span{font-weight:bold;color:#FF0000}
			</style><ul>
			<h5>API用户采集 共有<span>'.$json['page']['recordcount'].'</span>个数据，需要采集<span>'.$json['page']['pagecount'].'</span>次，正在执行第<span color=green>'.$admin['page'].'</span>次采集任务，每一次采集<span>'.$json['page']['pagesize'].'</span>个。
			</h5>';
		foreach($json['data'] as $key=>$user){
			echo '<li>第<span>'.(($admin['page']-1)*$json['page']['pagesize']+$key+1).'</span>个用户 '.$user['user_name'].'</li>';
			$this->user_db($user);
			ob_flush();flush();
		}
		//是否分页采集
		$admin['g'] = 'admin';
		$admin['m'] = 'cj';
		$admin['a'] = 'apis';
		$admin['page'] = 'FFLINK';
		$page_link = '?'.http_build_query(array_merge($admin, $params));
		$this->jump($json['page']["pageindex"], $json['page']['pagecount'], $page_link);
		echo'</ul>';
	}
	private function user_db($user){
		unset($user["user_id"]);
		if(empty($user['user_name'])){
			echo('<li class="p">用户名为空，不做处理!</li>');
			return false;
		}
		$array = M('User')->field('user_id,user_name')->where('user_name="'.$user['user_name'].'"')->find();
		if(!$array){
			$user['user_pwd'] = md5(time());
			$user['user_jointime'] = time();
			$user['user_logtime'] = time();
			$user['user_logip'] = get_client_ip();
			$id = M('User')->data($user)->add();
			if($id){
				echo('<li class="p">用户添加成功，'.$user['user_name'].'（'.$id.'）</li>');
			}else{
				echo('<li class="p">用户添加失败，'.M('User')->getDbError().'</li>');
			}
		}else{
			echo('<li class="p">用户名已存在，不做处理!</li>');
		}
	}
	/*----------------------------------------------------------forum---------------------------------------------------------------------------*/	
	public function forum_json($admin, $params){
		$params['g'] = 'plus';
		$params['m'] = 'api';
		$params['a'] = 'forum';
		$params['p'] = $admin['page'];
		$params['key'] = $admin['apikey'];
		ksort($params);
		$url = base64_decode($admin['xmlurl']).'?'.http_build_query($params);
		$html = ff_file_get_contents($url);
		//是否采集到数据
		if(!$html){
			return array('status'=>601, 'infos'=>'连接API资源库失败，通常为服务器网络不稳定或禁用了采集。');
		}
		//数据包验证
		$json = json_decode($html, true);
		if( is_null($json) ){
			return array('status'=>602, 'infos'=>'JSON格式不正确，不支持采集。');
		}
		//资源库返回的状态501 502 503
		if($json['status'] != 200){
			return array('status'=>$json['status'], 'infos'=>$json['data']);
		}
		//返回正确的数据集合
		return array('status'=>200, 'infos'=>$json);
	}
	public function forum_update($admin, $params, $json){
		echo'<style type="text/css">
			ul{margin:0 auto; width:60%;border:1px solid #666;}
			h5{text-align:center;}
			li{font-size:12px;color:#333;line-height:21px}
			li.p{color:#666;list-style:none;}
			li.red{color:#FF0000}
			li.blue{color:blue}
			span{font-weight:bold;color:#FF0000}
			</style><ul>
			<h5>API评论采集 共有<span>'.$json['page']['recordcount'].'</span>个数据，需要采集<span>'.$json['page']['pagecount'].'</span>次，正在执行第<span color=green>'.$admin['page'].'</span>次采集任务，每一次采集<span>'.$json['page']['pagesize'].'</span>个。
			</h5>';
		foreach($json['data'] as $key=>$forum){
			echo '<li>第<span>'.(($admin['page']-1)*$json['page']['pagesize']+$key+1).'</span>个评论</li>';
			echo '<li class="p">'.$forum['forum_content'].'</li>';
			if(!$forum['forum_referer']){
				$forum['forum_referer'] = $admin['cjid'].'_'.$forum['forum_id'];
			}
			$this->forum_db($forum);
			ob_flush();flush();
		}
		//是否分页采集
		$admin['g'] = 'admin';
		$admin['m'] = 'cj';
		$admin['a'] = 'apis';
		$admin['page'] = 'FFLINK';
		$page_link = '?'.http_build_query(array_merge($admin, $params));
		$this->jump($json['page']["pageindex"], $json['page']['pagecount'], $page_link);
		echo'</ul>';
	}
	public function forum_db($forum){
		unset($forum["forum_id"]);
		if(empty($forum['forum_content'])){
			echo('<li class="p">评论内容为空，不做处理!</li>');
			return false;
		}
		$array = M('Forum')->field('forum_id')->where('forum_referer="'.$forum['forum_referer'].'"')->find();
		if($array){
			echo('<li class="p red">评论已存在，不做处理!</li>');
			return false;
		}
		$vod= M('Vod')->field('vod_id')->where('vod_reurl="'.$forum['vod_reurl'].'"')->find();
		if($vod){
			unset($forum['forum_name']);
			unset($forum['forum_reurl']);
			$forum['forum_status'] = 1;
			$forum['forum_cid'] = $vod['vod_id'];
			$forum['forum_sid'] = 1;
			$forum['forum_uid'] = mt_rand(1, C('collect_forum'));
			$forum['forum_addtime'] = time()-rand(100000,999999);
			$forum['forum_ip'] = get_client_ip();
			$id = M('Forum')->data($forum)->add();
			if($id){
				echo('<li class="p blue">评论添加成功，（'.$id.'）</li>');
			}else{
				echo('<li class="p red">评论添加失败，'.M('Forum')->getDbError().'</li>');
			}
		}else{
			echo('<li class="p red">无对应的视频内容ID，不做处理（'.$forum['vod_reurl'].'）</li>');
		}
	}
	
	/*----------------------------------------------------------scenario---------------------------------------------------------------------------*/	
	public function scenario_json($admin, $params){
		$params['g'] = 'plus';
		$params['m'] = 'api';
		$params['a'] = 'scenario';
		$params['p'] = $admin['page'];
		$params['key'] = $admin['apikey'];
		ksort($params);
		$url = base64_decode($admin['xmlurl']).'?'.http_build_query($params);
		$html = ff_file_get_contents($url);
		//是否采集到数据
		if(!$html){
			return array('status'=>601, 'infos'=>'连接API资源库失败，通常为服务器网络不稳定或禁用了采集。');
		}
		//数据包验证
		$json = json_decode($html, true);
		if( is_null($json) ){
			return array('status'=>602, 'infos'=>'JSON格式不正确，不支持采集。');
		}
		//资源库返回的状态501 502 503
		if($json['status'] != 200){
			return array('status'=>$json['status'], 'infos'=>$json['data']);
		}
		//返回正确的数据集合
		return array('status'=>200, 'infos'=>$json);
	}
	public function scenario_update($admin, $params, $json){
		echo'<style type="text/css">
			ul{margin:0 auto; width:60%;border:1px solid #666;}
			h5{text-align:center;}
			li{font-size:12px;color:#333;line-height:21px}
			li.p{color:#666;list-style:none;}
			span{font-weight:bold;color:#FF0000}
			</style><ul>
			<h5>API剧情采集 共有<span>'.$json['page']['recordcount'].'</span>个数据，需要采集<span>'.$json['page']['pagecount'].'</span>次，正在执行第<span color=green>'.$admin['page'].'</span>次采集任务，每一次采集<span>'.$json['page']['pagesize'].'</span>个。
			</h5>';
		foreach($json['data'] as $key=>$scenario){
			echo '<li>第<span>'.(($admin['page']-1)*$json['page']['pagesize']+$key+1).'</span>个剧情 '.$scenario['vod_name'].'</li>';
			$this->scenario_db($scenario);
			ob_flush();flush();
		}
		//是否分页采集
		$admin['g'] = 'admin';
		$admin['m'] = 'cj';
		$admin['a'] = 'apis';
		$admin['page'] = 'FFLINK';
		$page_link = '?'.http_build_query(array_merge($admin, $params));
		$this->jump($json['page']["pageindex"], $json['page']['pagecount'], $page_link);
		echo'</ul>';
	}
	private function scenario_db($scenario){
		if(empty($scenario['vod_scenario'])){
			echo('<li class="p">剧情为空，不做处理!</li>');
			return false;
		}
		//优先从来源标识更新
		if($scenario['vod_reurl']){
			$array = M('Vod')->field('vod_id,vod_name,vod_inputer,vod_scenario')->where('vod_reurl="'.$scenario['vod_reurl'].'"')->find();
		}else{
			$array = M('Vod')->field('vod_id,vod_name,vod_inputer,vod_scenario')->where('vod_name="'.$scenario['vod_name'].'"')->find();
		}
		// 检测是否站长锁定
		if('feifeicms' == $array['vod_inputer']){
			echo('<li class="p">站长已锁定该影片，不更新。</li>');
			return false;
		}
		if($array){
			$scenario_old = json_decode($array['vod_scenario'],true);
			if(count($scenario["vod_scenario"]['info']) > count($scenario_old['info'])){
				$data = array();
				$data['vod_id'] = $array['vod_id'];
				$data['vod_scenario'] = json_encode($scenario['vod_scenario']);
				$data['vod_addtime'] = time();
				$id = M('Vod')->save($data);
				echo('<li class="p blue">剧情更新成功（'.$array['vod_id'].'）！</li>');
			}else{
				echo('<li class="p">剧情未更新（'.$array['vod_id'].'），不做处理!</li>');
			}
		}else{
			echo('<li class="p">不存在，不做处理!</li>');
		}
	}
	
	/*----------------------------------------------------------news---------------------------------------------------------------------------*/	
	public function news_json($admin, $params){
		$params['g'] = 'plus';
		$params['m'] = 'api';
		$params['a'] = 'news';
		$params['p'] = $admin['page'];
		$params['key'] = $admin['apikey'];
		ksort($params);
		$url = base64_decode($admin['xmlurl']).'?'.http_build_query($params);
		$html = ff_file_get_contents($url);
		//是否采集到数据
		if(!$html){
			return array('status'=>601, 'infos'=>'连接API资源库失败，通常为服务器网络不稳定或禁用了采集。');
		}
		//数据包验证
		$json = json_decode($html, true);
		if( is_null($json) ){
			return array('status'=>602, 'infos'=>'JSON格式不正确，不支持采集。');
		}
		//资源库返回的状态501 502 503
		if($json['status'] != 200){
			return array('status'=>$json['status'], 'infos'=>$json['data'].$json['message']);
		}
		//返回正确的数据集合
		$json = $this->news_bind($admin, $json);
		return array('status'=>200, 'infos'=>$json);
	}
	private function news_bind($admin, $json){
		// 获取到的远程栏目数据增加对应的绑定ID
		foreach($json['list'] as $key=>$value){
			$json['list'][$key]['bind_key'] = $admin['cjid'].'_'.$value['list_id'];
		}
		// 获取到的远程视频列表数据格式化处理
		foreach($json['data'] as $key=>$value){
			$json['data'][$key]['news_cid'] = intval(ff_bind_id($admin['cjid'].'_'.$value['news_cid']));
			$json['data'][$key]['news_inputer'] = 'xml_'.$admin['cjid'];
			if(!$json['data'][$key]['news_reurl']){
				$json['data'][$key]['news_reurl'] = base64_decode($admin['xmlurl']).$json['data'][$key]['news_id'];
			}
		}
		return $json;
	}
	public function news_update($admin, $params, $json){
		echo'<style type="text/css">
			ul{margin:0 auto; width:60%;border:1px solid #666;}
			h5{text-align:center;}
			li{font-size:12px;color:#333;line-height:21px}
			li.p{color:#666;list-style:none;}
			li.red{color:#FF0000}
			li.blue{color:blue}
			span{font-weight:bold;color:#FF0000}
			</style><ul>
			<h5>文章资讯采集 共有<span>'.$json['page']['recordcount'].'</span>个数据，需要采集<span>'.$json['page']['pagecount'].'</span>次，正在执行第<span color=green>'.$admin['page'].'</span>次采集任务，每一次采集<span>'.$json['page']['pagesize'].'</span>个。
			</h5>';
		foreach($json['data'] as $key=>$news){
			echo '<li>第<span>'.(($admin['page']-1)*$json['page']['pagesize']+$key+1).'</span>个文章《'.$news['news_name'].'》</li>';
			$this->news_db($news);
			ob_flush();flush();
		}
		//是否分页采集
		$admin['g'] = 'admin';
		$admin['m'] = 'cj';
		$admin['a'] = 'apis';
		$admin['page'] = 'FFLINK';
		$page_link = '?'.http_build_query(array_merge($admin, $params));
		$this->jump($json['page']["pageindex"], $json['page']['pagecount'], $page_link);
		echo'</ul>';
	}
	public function news_db($news){
	  if(empty($news['news_name']) || empty($news['news_content'])){
			echo '<li class="p">文章名称或文章内容为空，不做处理!</li>';
			return false;
		}
		if(!$news['news_cid']){
			echo '<li class="p">未匹配到对应栏目分类，不做处理，请先转换分类!</li>';
			return false;
		}
		// 格式化常规字符
		if(empty($news['news_remark'])){
			$news['news_remark'] = msubstr(trim($news['news_content']),0,100,false);
		}
		// 要查询检查的字段
		$field = 'news_id,news_cid,news_name,news_remark,news_content';
		// 检测文章名称是否相等(需防止同名的冲突所以增加了CID条件)
		$array = M('News')->field($field)->where('news_cid='.$news['news_cid'].' and news_name="'.$news['news_name'].'" ')->find();
		if($array){
			return $this->news_db_update($news, $array);
		}
		// 添加文章开始
		unset($news['news_id']);	
		$news['news_addtime'] = time();	
		if( empty($news['news_hits']) ){
			$news['news_hits'] = mt_rand(1,C('collect_hits'));
		}
		if( empty($news['news_up']) ){
			$news['news_up'] = mt_rand(1,C('collect_updown'));
		}
		if( empty($news['news_down']) ){
			$news['news_down'] = mt_rand(1,C('collect_updown'));
		}
		if( empty($news['news_gold']) ){
			$news['news_gold'] = mt_rand(1,C('collect_gold'));
		}
		if( empty($news['news_golder']) ){
			$news['news_golder'] = mt_rand(1,C('collect_golder'));
		}
		if( empty($news['news_ename']) ){
			$news['news_ename'] = ff_pinyin($news['news_name']);
		}
		// 自动下载远程海报图片
		$img = D('Img');
		$news['news_pic'] = $img->down_load($news['news_pic']);	
		// 入库接口	
		$id = M('News')->data($news)->add();
		// 关联多分类及TAG相关
		if($id){
			// 增加多分类
			if( $news['news_type'] ){
				D('Tag')->tag_update($id, $news["news_type"], 'news_type');
			}
			// 增加关联tag
			if( $news['news_keywords'] ){
				D('Tag')->tag_update($id, $news["news_keywords"], 'news_tag');
			}
			echo '<li class="p">文章添加成功。</li>';
			return $id;
		}
		echo '<li class="p">文章添加失败。</li>';
		return false;
  }
	private function news_db_update($news, $news_old){	
		// 检测是否站长手动锁定更新
		if('feifeicms' == $news_old['news_inputer']){
			echo '<li class="p">站长手动锁定，不更新。</li>';
			return false;
		}
		$edit = array();
		if($news['news_content'] == $news_old['news_content']){
			echo '<li class="p">文章内容未变化，不需要更新。</li>';
			return false;
		}else{
			$edit['news_content'] = $news['news_content'];
		}
		// 组合更新条件及内容(以最后一次更新的库为检测依据)
		$edit['news_id'] = $news_old['news_id'];
		$edit['news_name'] = $news['news_name'];
		$edit['news_reurl'] = $news['news_reurl'];
		$edit['news_addtime'] = time();
		// 更新数据
		M('News')->data($edit)->save();
		//删除数据缓存
		if(C('cache_page_news')){
			S(md5('cache_page_news_'.$news_old['news_id']),NULL);
		}
		echo '<li class="p">文章已更新。</li>';
		return true;
	}	
	
	/*----------------------------------------------------------star---------------------------------------------------------------------------*/	
	public function star_json($admin, $params){
		$params['g'] = 'plus';
		$params['m'] = 'api';
		$params['a'] = 'star';
		$params['p'] = $admin['page'];
		$params['key'] = $admin['apikey'];
		ksort($params);
		$url = base64_decode($admin['xmlurl']).'?'.http_build_query($params);
		$html = ff_file_get_contents($url);
		//是否采集到数据
		if(!$html){
			return array('status'=>601, 'infos'=>'连接API资源库失败，通常为服务器网络不稳定或禁用了采集。');
		}
		//数据包验证
		$json = json_decode($html, true);
		if( is_null($json) ){
			return array('status'=>602, 'infos'=>'JSON格式不正确，不支持采集。');
		}
		//资源库返回的状态501 502 503
		if($json['status'] != 200){
			return array('status'=>$json['status'], 'infos'=>$json['data'].$json['message']);
		}
		//返回正确的数据集合
		$json = $this->person_bind($admin, $json);
		return array('status'=>200, 'infos'=>$json);
	}
	private function person_bind($admin, $json){
		// 读取绑定配置
		$bind = F('_cj/bind');
		// 获取到的远程栏目数据增加对应的绑定ID
		foreach($json['list'] as $key=>$value){
			$json['list'][$key]['bind_key'] = $admin['cjid'].'_'.$value['list_id'];
		}
		// 获取到的远程视频列表数据格式化处理
		foreach($json['data'] as $key=>$value){
			$json['data'][$key]['person_cid'] = intval($bind[$admin['cjid'].'_'.$value['person_cid']]);
			if(!$json['data'][$key]['person_reurl']){
				$json['data'][$key]['person_reurl'] = base64_decode($admin['xmlurl']).$json['data'][$key]['person_id'];
			}
		}
		return $json;
	}	
	public function star_update($admin, $params, $json){
		echo'<style type="text/css">
			ul{margin:0 auto; width:60%;border:1px solid #666;}
			h5{text-align:center;}
			li{font-size:12px;color:#333;line-height:21px}
			li.p{color:#666;list-style:none;}
			li.red{color:#FF0000}
			li.blue{color:blue}
			span{font-weight:bold;color:#FF0000}
			</style><ul>
			<h5>人物采集 共有<span>'.$json['page']['recordcount'].'</span>个数据，需要采集<span>'.$json['page']['pagecount'].'</span>次，正在执行第<span color=green>'.$admin['page'].'</span>次采集任务，每一次采集<span>'.$json['page']['pagesize'].'</span>个。
			</h5>';
		foreach($json['data'] as $key=>$star){
			echo '<li>第<span>'.(($admin['page']-1)*$json['page']['pagesize']+$key+1).'</span>个《'.$star['person_name'].'》</li>';
			$this->star_db($star);
			ob_flush();flush();
		}
		//是否分页采集
		$admin['g'] = 'admin';
		$admin['m'] = 'cj';
		$admin['a'] = 'apis';
		$admin['page'] = 'FFLINK';
		$page_link = '?'.http_build_query(array_merge($admin, $params));
		$this->jump($json['page']["pageindex"], $json['page']['pagecount'], $page_link);
		echo'</ul>';
	}	
	public function star_db($star){
		unset($star['person_id']);
	  if( empty($star['person_name']) ){
			echo '<li class="p">名称为空，不做处理!</li>';
			return false;
		}
		if(!$star['person_cid']){
			echo '<li class="p">请先转换分类，不做处理!</li>';
			return false;
		}
		//来源标识验证
		if(!$star['person_reurl']){
			echo '<li class="p">来源标识为空，不做处理!</li>';
			return false;
		}
		//重复采集验证
		$star_old = $this->star_db_find($star);
		if($star_old['person_id']){
			$status = '<li class="p"><strong>取消：</strong>明星已存在，不做处理!</li>';
		}else{
			$status = '<li class="p"><strong>新增：</strong>'.$this->star_db_insert($star).'</li>';
		}
		echo $status;
  }
	private function star_db_find($star){
		$field = 'person_id,person_cid,person_name,person_alias,person_birthday,person_reurl,person_douban_celebrities';
		$where = array();
		$where['person_cid'] = array('eq', $star['person_cid']);
		$where['person_sid'] = array('eq', 8);
		$where['person_name'] = array('like', $star['person_name'].'%');
		$array_list = M('Person')->field($field)->where($where)->limit(30)->order('person_id desc')->select();
		if(!$array_list){
			return $star;
		}
		// 来源判断
		foreach($array_list as $key=>$value){
			if($value['person_reurl'] == $star['person_reurl']){
				return $value;
			}
		}
		// 豆瓣检测
		foreach($array_list as $key=>$value){
			if($value['person_douban_celebrities'] == $star['person_douban_celebrities']){
				return $value;
			}
		}
		// 标题检测
		foreach($array_list as $key=>$value){
			if($value['person_name'] == $star['person_name']){
				if($star['person_alias'] && ($value['person_alias'] == $star['person_alias'])){
					return $value;
				}
				if($star['person_birthday'] && ($value['person_birthday'] == $star['person_birthday'])){
					return $value;
				}
				$star['person_status'] = -1;//需审核
			}
		}
		return $star;
	}
	private function star_db_insert($star){
		$star['person_addtime'] = time();
		$star['person_letter'] 	= ff_url_letter($star['person_name']);
		if(empty($star['person_ename'])){
			$star['person_ename'] = ff_pinyin($star['person_name']);
		}
		if(empty($star['person_intro'])){
			$star['person_intro'] = msubstr(trim($star['person_content']),0,100,false);
		}		
		if(empty($star['person_hits'])){
			$star['person_hits'] = mt_rand(0, C('collect_hits'));
		}
		if(empty($star['person_up'])){
			$star['person_up'] = mt_rand(0, C('collect_updown'));
		}
		if(empty($star['person_down'])){
			$star['person_down'] = mt_rand(0, C('collect_updown'));
		}
		if( empty($star['person_gold']) ){
			$star['person_gold'] = mt_rand(0, C('collect_gold'));
		}
		if( empty($star['person_golder']) ){
			$star['person_golder'] = mt_rand(0, C('collect_golder'));
		}
		$id = M('Person')->data($star)->add();
		if($id){
			return '添加成功('.$id.')';
		}
		return '添加失败：'.M('Person')->getDbError();
	}
	
	/*----------------------------------------------------------role---------------------------------------------------------------------------*/	
	public function role_json($admin, $params){
		$params['g'] = 'plus';
		$params['m'] = 'api';
		$params['a'] = 'role';
		$params['p'] = $admin['page'];
		$params['key'] = $admin['apikey'];
		ksort($params);
		$url = base64_decode($admin['xmlurl']).'?'.http_build_query($params);
		$html = ff_file_get_contents($url);
		//是否采集到数据
		if(!$html){
			return array('status'=>601, 'infos'=>'连接API资源库失败，通常为服务器网络不稳定或禁用了采集。');
		}
		//数据包验证
		$json = json_decode($html, true);
		if( is_null($json) ){
			return array('status'=>602, 'infos'=>'JSON格式不正确，不支持采集。');
		}
		//资源库返回的状态501 502 503
		if($json['status'] != 200){
			return array('status'=>$json['status'], 'infos'=>$json['data'].$json['message']);
		}
		//返回正确的数据集合
		$json = $this->person_bind($admin, $json);
		return array('status'=>200, 'infos'=>$json);
	}
	public function role_update($admin, $params, $json){
		echo'<style type="text/css">
			ul{margin:0 auto; width:60%;border:1px solid #666;}
			h5{text-align:center;}
			li{font-size:12px;color:#333;line-height:21px}
			li.p{color:#666;list-style:none;}
			li.red{color:#FF0000}
			li.blue{color:blue}
			span{font-weight:bold;color:#FF0000}
			</style><ul>
			<h5>人物采集 共有<span>'.$json['page']['recordcount'].'</span>个数据，需要采集<span>'.$json['page']['pagecount'].'</span>次，正在执行第<span color=green>'.$admin['page'].'</span>次采集任务，每一次采集<span>'.$json['page']['pagesize'].'</span>个。
			</h5>';
		foreach($json['data'] as $key=>$role){
			echo '<li>第<span>'.(($admin['page']-1)*$json['page']['pagesize']+$key+1).'</span>个《'.$role['person_name'].'》</li>';
			$this->role_db($role);
			ob_flush();flush();
		}
		//是否分页采集
		$admin['g'] = 'admin';
		$admin['m'] = 'cj';
		$admin['a'] = 'apis';
		$admin['page'] = 'FFLINK';
		$page_link = '?'.http_build_query(array_merge($admin, $params));
		$this->jump($json['page']["pageindex"], $json['page']['pagecount'], $page_link);
		echo'</ul>';
	}	
	public function role_db($role){
		unset($role['person_id']);
	  if( empty($role['person_name']) ){
			echo '<li class="p">名称为空，不做处理!</li>';
			return false;
		}
		if(!$role['person_cid']){
			echo '<li class="p">请先转换分类，不做处理!</li>';
			return false;
		}
		//来源标识验证
		if(!$role['person_reurl']){
			echo '<li class="p">来源标识为空，不做处理!</li>';
			return false;
		}
		//重复采集验证
		$role_old = $this->role_db_find($role);
		if($role_old['person_id']){
			$status = '<li class="p"><strong>取消：</strong>角色已存在，不做处理!</li>';
		}else{
			$status = '<li class="p"><strong>新增：</strong>'.$this->role_db_insert($role).'</li>';
		}
		echo $status;
  }
	private function role_db_find($role){
		$field = 'person_id,person_name,person_reurl,person_father_name,person_douban_id,person_object_name';
		$where = array();
		$where['person_sid'] = array('eq', 9);
		$where['person_name'] = array('like', $role['person_name'].'%');
		$array_list = M('Person')->field($field)->where($where)->limit(30)->order('person_id desc')->select();
		if(!$array_list){
			return $role;
		}
		// 来源判断
		foreach($array_list as $key=>$value){
			if($value['person_reurl'] == $role['person_reurl']){
				return $value;
			}
		}
		// 豆瓣检测
		foreach($array_list as $key=>$value){
			if($value['person_douban_id'] == $role['person_douban_id']){
				return $value;
			}
		}
		// 主演检测
		foreach($array_list as $key=>$value){
			if($value['person_father_name'] == $role['person_father_name']){
				return $value;
			}
		}
		// 片名检测
		foreach($array_list as $key=>$value){
			if($value['person_object_name'] == $role['person_object_name']){
				return $value;
			}
		}
		// 标题检测
		foreach($array_list as $key=>$value){
			if($value['person_name'] == $role['person_name']){
				$role['person_status'] = -1;//需审核
			}
		}
		return $role;
	}	
	private function role_db_insert($role){
		$role['person_addtime'] = time();
		$role['person_letter'] 	= ff_url_letter($role['person_name']);
		if(empty($role['person_ename'])){
			$role['person_ename'] = ff_pinyin($role['person_name']);
		}	
		if(empty($role['person_hits'])){
			$role['person_hits'] = mt_rand(0, C('collect_hits'));
		}
		if(empty($role['person_up'])){
			$role['person_up'] = mt_rand(0, C('collect_updown'));
		}
		if(empty($role['person_down'])){
			$role['person_down'] = mt_rand(0, C('collect_updown'));
		}
		if( empty($role['person_gold']) ){
			$role['person_gold'] = mt_rand(0, C('collect_gold'));
		}
		if( empty($role['person_golder']) ){
			$role['person_golder'] = mt_rand(0, C('collect_golder'));
		}
		//关联明星
		if( $role['person_douban_celebrities'] ){
			$father_id = M('Person')->where('person_sid=8 and person_douban_celebrities='.intval($role['person_douban_celebrities']))->getField('person_id');
		}
		if((!$father_id) && $role['person_father_name']){
			$father_id = M('Person')->where('person_sid=8 and person_name="'.$role['person_father_name'].'"')->getField('person_id');
		}
		$role['person_father_id'] = intval($father_id);
		//关联视频
		if( $role['person_douban_id'] ){
			$object_id = M('Vod')->where('vod_douban_id='.intval($role['person_douban_id']))->getField('vod_id');
		}
		if((!$object_id) && $role['person_object_name']){
			$object_id = M('Vod')->where('vod_name="'.$role['person_object_name'].'"')->getField('vod_id');
		}
		$role['person_object_id'] = intval($object_id);
		//状态修改
		if($role['person_object_id']<1 || $role['person_father_id']<1){
			$role['person_status'] = 0;
		}
		$id = M('Person')->data($role)->add();
		if($id){
			return '添加成功('.$id.')';
		}
		return '添加失败：'.M('Person')->getDbError();
	}
}
?>