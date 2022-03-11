<?php
/*----------------------------------------------文件夹与文件操作开始-----------------------------------------------*/
//读取文件
function read_file($l1){
	return @file_get_contents($l1);
}
//写入文件
function write_file($l1, $l2=''){
	$dir = dirname($l1);
	if(!is_dir($dir)){
		mkdirss($dir);
	}
	return @file_put_contents($l1, $l2);
}
//递归创建文件
function mkdirss($dirs,$mode=0777) {
	if(!is_dir($dirs)){
		mkdirss(dirname($dirs), $mode);
		return @mkdir($dirs, $mode);
	}
	return true;
}
// 数组保存到文件
function arr2file($filename, $arr=''){
	if(is_array($arr)){
		$con = var_export($arr,true);
	} else{
		$con = $arr;
	}
	$con = "<?php\nreturn $con;\n?>";//\n!defined('IN_MP') && die();\nreturn $con;\n
	write_file($filename, $con);
}
/*-------------------------------------------------系统路径相关函数开始------------------------------------------------------------------*/
//获取当前地址栏URL
function get_http_url(){
	return htmlspecialchars("http://".$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"]);
}
//获取根目录路径
function get_site_path($filename){
    $basepath = $_SERVER['PHP_SELF'];
    $basepath = substr($basepath,0,strpos($basepath,$filename));
	return $basepath;
}
//相对路径转绝对路径
function get_base_url($baseurl,$url){
	if("#" == $url){
		return "";
	}elseif(FALSE !== stristr($url,"http://")){
		return $url;
	}elseif( "/" == substr($url,0,1) ){
		$tmp = parse_url($baseurl);
		return $tmp["scheme"]."://".$tmp["host"].$url;
	}else{
		$tmp = pathinfo($baseurl);
		return $tmp["dirname"]."/".$url;
	}
}
//获取指定地址的域名
function get_domain($url){
	preg_match("|http://(.*)\/|isU", $url, $arr_domain);
	return $arr_domain[1];
}
/*-------------------------------------------------字符串处理开始------------------------------------------------------------------*/
// UT*转GBK
function u2g($str){
	return iconv("UTF-8","GBK",$str);
}
// GBK转UTF8
function g2u($str){
	return iconv("GBK","UTF-8//ignore",$str);
}
// 去掉换行
function nr($str){
	$str = str_replace(array("<nr/>","<rr/>"),array("\n","\r"),$str);
	return trim($str);
}
// 去掉连续空白
function nb($str){
	$str = str_replace("　",' ',str_replace("&nbsp;",' ',$str));
	$str = ereg_replace("[\r\n\t ]{1,}",' ',$str);
	return trim($str);
}
// 转换成JS
function t2js($l1, $l2=1){
  $I1 = str_replace(array("\r", "\n"), array('', '\n'), addslashes($l1));
  return $l2 ? "document.write(\"$I1\");" : $I1;
}
// 计算中文字符串长度 与mb_strlen相同 中文与英文都统一为一个字节
function ff_mb_strlen($string = null) {
	preg_match_all("/./us", $string, $match);
	return count($match[0]);
}
// 字符串截取(同时去掉HTML与空白)
function msubstr($str, $start=0, $length, $suffix=false){
	return ff_msubstr(eregi_replace('<[^>]+>','',ereg_replace("[\r\n\t ]{1,}",' ',nb($str))),$start,$length,'utf-8',$suffix);
}
function ff_msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true){
	if( function_exists('mb_strimwidth') ){
		if($suffix){
			return mb_strimwidth($str, $start, $length*2, '...', 'utf-8');
		}
		return mb_strimwidth($str, $start, $length*2, '', 'utf-8');
	}
	$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);
	$length_new = $length;
	for($i=$start; $i<$length; $i++){
		if (ord($match[0][$i]) > 0xa0){
			//中文
		}else{
			$length_new++;
			$length_chi++;
		}
	}
	if($length_chi<$length){
		$length_new = $length+($length_chi/2);
	}
	$slice = join("",array_slice($match[0], $start, $length_new));
    if($suffix && $slice != $str){
		return $slice."…";
	}
    return $slice;
}
// 汉字转拼单 $ishead 是否只返回首字母
function ff_pinyin($str, $ishead=0, $isclose=1){
	$str = u2g($str);//转成GBK
	global $pinyins;
	$restr = '';
	$str = trim($str);
	$slen = strlen($str);
	if($slen<2){
		return $str;
	}
	if(count($pinyins)==0){
		$fp = fopen('./Public/data/pinyin.dat','r');
		while(!feof($fp)){
			$line = trim(fgets($fp));
			$pinyins[$line[0].$line[1]] = substr($line,3,strlen($line)-3);
		}
		fclose($fp);
	}
	for($i=0;$i<$slen;$i++){
		if(ord($str[$i])>0x80){
			$c = $str[$i].$str[$i+1];
			$i++;
			if(isset($pinyins[$c])){
				if($ishead==0){
					$restr .= $pinyins[$c];
				}
				else{
					$restr .= $pinyins[$c][0];
				}
			}else{
				//$restr .= "_";
			}
		}else if( eregi("[a-z0-9]",$str[$i]) ){
			$restr .= $str[$i];
		}
		else{
			//$restr .= "_";
		}
	}
	if($isclose==0){
		unset($pinyins);
	}
	return $restr;
}
// 生成字母前缀
function ff_url_letter($s0){
	$firstchar_ord = ord(strtoupper($s0{0}));
	if (($firstchar_ord>=65 and $firstchar_ord<=91)or($firstchar_ord>=48 and $firstchar_ord<=57)) return $s0{0}; 
	$s=iconv("UTF-8","gb2312", $s0); 
	$asc=ord($s{0})*256+ord($s{1})-65536; 
	if($asc>=-20319 and $asc<=-20284)return "A";
	if($asc>=-20283 and $asc<=-19776)return "B";
	if($asc>=-19775 and $asc<=-19219)return "C";
	if($asc>=-19218 and $asc<=-18711)return "D";
	if($asc>=-18710 and $asc<=-18527)return "E";
	if($asc>=-18526 and $asc<=-18240)return "F";
	if($asc>=-18239 and $asc<=-17923)return "G";
	if($asc>=-17922 and $asc<=-17418)return "H";
	if($asc>=-17417 and $asc<=-16475)return "J";
	if($asc>=-16474 and $asc<=-16213)return "K";
	if($asc>=-16212 and $asc<=-15641)return "L";
	if($asc>=-15640 and $asc<=-15166)return "M";
	if($asc>=-15165 and $asc<=-14923)return "N";
	if($asc>=-14922 and $asc<=-14915)return "O";
	if($asc>=-14914 and $asc<=-14631)return "P";
	if($asc>=-14630 and $asc<=-14150)return "Q";
	if($asc>=-14149 and $asc<=-14091)return "R";
	if($asc>=-14090 and $asc<=-13319)return "S";
	if($asc>=-13318 and $asc<=-12839)return "T";
	if($asc>=-12838 and $asc<=-12557)return "W";
	if($asc>=-12556 and $asc<=-11848)return "X";
	if($asc>=-11847 and $asc<=-11056)return "Y";
	if($asc>=-11055 and $asc<=-10247)return "Z";
	return 0;//null
}
/*------------------------------------系统安全加密函数开始----------------------------------------------------*/
//加密
function ff_encrypt($data, $key){
	$key  = md5(empty($key) ? 'feifeicms' : $key);
	$x  = 0;
	$len = strlen($data);
	$l  = strlen($key);
	for ($i = 0; $i < $len; $i++)
	{
			if ($x == $l) 
			{
			 $x = 0;
			}
			$char .= $key{$x};
			$x++;
	}
	for ($i = 0; $i < $len; $i++)
	{
			$str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
	}
	return base64_encode($str);
}
//解密
function ff_decrypt($data, $key){
 	$key  = md5(empty($key) ? 'feifeicms' : $key);
	$x = 0;
	$data = base64_decode($data);
	$len = strlen($data);
	$l = strlen($key);
	for ($i = 0; $i < $len; $i++)
	{
			if ($x == $l) 
			{
			 $x = 0;
			}
			$char .= substr($key, $x, 1);
			$x++;
	}
	for ($i = 0; $i < $len; $i++)
	{
			if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1)))
			{
					$str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
			}
			else
			{
					$str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
			}
	}
	return $str;
}
/*--------------------------------------------------系统常用快捷函数开始------------------------------------------*/
// 默认值处理
function ff_default($value, $default){
	if($value){
		return $value;
	}
	return $default;
}
// 逗号分隔的字符串去重 如模板栏目ID参数 $cids = array(1,2,3,...)
function ff_unique($cids){
	$cidarr = explode(',',$cid);
	$cidarr = array_unique($cidarr);
	return $cidarr;
}
// 判断是否为windows/nginx 环境
function ff_isNginxWin(){
	if(PATH_SEPARATOR==';'){
		if(stristr(strtolower($_SERVER['SERVER_SOFTWARE']),'nginx')){
			return true;
		}
	}
	return false;
}
// 返回数组的值
function ff_array($array, $key=0){
	return $array[$key];
}
// 递归多维数组转为一级数组
function ff_arrays2array($array){
	static $result_array=array();
	foreach($array as $value){
		if(is_array($value)){
			ff_arrays2array($value);
		}else{
			$result_array[]=$value;
		}
	}
	return $result_array;
}
//内容模型定义
function ff_configModel(){
	$module = array();
	$module[1] = 'vod';
	$module[2] = 'news';
	$module[3] = 'special';
	$module[4] = 'tag';
	$module[5] = 'guestbook';
	$module[6] = 'forum';
	$module[7] = 'scenario';
	$module[8] = 'star';
	$module[9] = 'role';
	$module[10] = 'pic';
	$module[11] = 'link';
	$module[12] = 'ads';
	$module[13] = 'list';
	$module[14] = 'nav';
	$module[15] = 'player';
	$module[16] = 'slide';
	$module[17] = 'user';
	$module[18] = 'record';
	$module[19] = 'score';
	$module[20] = 'orders';
	$module[21] = 'cj';
	$module[22] = 'pay';
	$module[23] = 'card';
	$module[24] = 'error';
	$module[25] = 'email';
	$module[26] = 'weixin';
	$module[27] = 'url';
	$module[28] = 'epg';
	$module[29] = 'map';
	$module[30] = 'search';
	$module[31] = 'ajax';
	return $module;
}
// 获取模型ID
function ff_module2sid($sidname){
	$module = array_flip(ff_configModel());
	return intval($module[$sidname]);
}
// 获取模型名称
function ff_sid2module($sid){
	$module = ff_configModel();
	return $module[$sid];
}
// 获取COOKIE用户信息
function ff_user_cookie(){
	$encrypt = cookie('ff_user');
	if($encrypt){
		$user_cookie = explode('$feifeicms$', ff_decrypt($encrypt));
		$array = array();
		$array['user_id'] = intval($user_cookie[0]);
		$array['user_name'] = htmlspecialchars($user_cookie[1]);
		return $array;
	}else{
		return false;
	}
}
function ff_create_statusGet($fileName='category'){
	return @file_get_contents('./Runtime/Data/_create/'.$fileName.'.txt');
}
function ff_create_statusSet($fileName='category',$status='ing'){
	return @file_put_contents('./Runtime/Data/_create/'.$fileName.'.txt',$status);
}
/*-------------------------------------------------系统采集函数开始------------------------------------------------------------------*/
// 采集内核
function ff_file_get_contents($url, $timeout=10, $referer='', $post_data=''){
	if(function_exists('curl_init')){
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		curl_setopt ($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt ($ch, CURLOPT_REFERER, $referer);
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		//post
		if($post_data){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		}
		//https
		$http = parse_url($url);
		if($http['scheme'] == 'https'){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		}
		$content = curl_exec($ch);
		curl_close($ch);
		if($content){
			return $content;
		}
	}
	$ctx = stream_context_create(array('http'=>array('timeout'=>$timeout)));
	$content = @file_get_contents($url, 0, $ctx);
	if($content){
		return $content;
	}
	return false;
}
// 采集-匹配规则结果
function ff_preg_match($rule,$html){
	$arr = explode('$$$',$rule);
	if(count($arr) == 2){
	    preg_match('/'.$arr[1].'/', $html, $data);
		return $data[$arr[0]].'';
	}else{
	    preg_match('/'.$rule.'/', $html, $data);
		return $data[1].'';
	}
}
// 采集-匹配规则结果all
function ff_preg_match_all($rule,$html){
	$arr = explode('$$$',$rule);
	if(count($arr) == 2){
	    preg_match_all('/'.$arr[1].'/', $html, $data);
		return $data[$arr[0]];
	}else{
	    preg_match_all('/'.$rule.'/', $html, $data);
		return $data[1];
	}
}
// 采集-倒序采集
function ff_krsort_url($listurl){
   krsort($listurl);
   foreach($listurl as $val){
     $list[]=$val;
   }
   return $list;
}
// 采集-将所有替换规则保存在一个字段
function ff_implode_rule($arr){
    foreach($arr as $val){
	    $array[] = trim(stripslashes($val));
	}
	return implode('|||',$array);
}
//  采集-规则替换
function ff_replace_rule($str){
	//$str = str_replace(array("\n","\r"),array("<nr/>","<rr/>"),strtolower($str));
	$arr1 = array('?','"','(',')','[',']','.','/',':','*','||');
	$arr2 = array('\?','\"','\(','\)','\[','\]','\.','\/','\:','.*?','(.*?)');
	//$str = str_replace(array("\n","\r"),array("<nr/>","<rr/>"),strtolower($str));
	return str_replace('\[$feifeicms\]','([\s\S]*?)',str_replace($arr1,$arr2,$str));
}
//生成随机伪静态简介
function ff_rand_str($string){
  $arr = C('collect_original_data');//同义词数据库
  //$all=mb_strlen($string,'utf-8');
	$all=iconv_strlen($string,'utf-8');
  $len=floor(mt_rand(0,$all-1));
  $str = msubstr($string,0,$len);
	$str2 = msubstr($string,$len,$all);
	return $str.$arr[array_rand($arr,1)].$str2;
}
//获取绑定分类对应ID值
function ff_bind_id($key){
	$bindcache = F('_cj/bind');
	return $bindcache[$key];
}
//TAG分词自动获取
function ff_tag_auto($title, $content){
	$data = ff_file_get_contents('http://cdn.feifeicms.co/server/v3/kw.php?key='.C('apikey_keyword').'&source='.rawurlencode(msubstr($content.$title,0,200)));
	$data = json_decode($data,true);
	if($data['code'] == 200) {
		return implode(',', $data['data']);
	}else if ($data['code'] == 501){
		return '';
	}else{
		return 'TAG出错：'.$data['message'];
	}
}
// 格式化采集影片名称
function ff_xml_vodname($vodname){
	$vodname = str_replace(array('【','】','（','）','(',')','{','}'),array('[',']','[',']','[',']','[',']'),$vodname);
	$vodname = preg_replace('/\[([a-z][A-Z])\]|([a-z][A-Z])版/i','',$vodname);
	$vodname = preg_replace('/TS清晰版|枪版|抢先版|HD|BD|TV|DVD|VCD|TS|\/版|\[\]/i','',$vodname);
	return trim($vodname);
}
// 格式化采集影片主演
function ff_xml_vodactor($vodactor){
	return str_replace(array('/','，','|','、',' ',',,,',',,',';'),',',$vodactor);	
}
/*-----------------------------------------------------------模板标签解析函数开始-----------------------------------------------------------------*/
//获得某天前的时间戳
function ff_linux_time($day){
	$day = intval($day);
	return mktime(23,59,59,date("m"),date("d")-$day,date("y"));
}
// 获取标题颜色
function ff_color($str, $color){
	if(empty($color)){
		return $str;
	}else{
		return '<font color="'.$color.'">'.$str.'</font>';
	}
}
// 处理积分样式
function ff_gold($fen){
	$array = explode('.',$fen);
	return '<strong>'.ff_default($array[0],1).'</strong>.'.$array[1];
}
// 自动下载简介里面的附件
function ff_content_img($content, $sid='news'){
	preg_match_all('#<img.*?src=[\'|\"]([^"]*)[\'|\"][^>]*>#i', $content, $match);
	foreach($match[1] as $imgurl){
		$imgsave = D('Img')->down_load(trim($imgurl), 'news');
		if($imgsave != $imgurl){
			$content = str_replace($imgurl, C('site_path').C('upload_path').'/'.$imgsave, $content);
		}
	}
	return $content;
}
// 获取时间颜色
function ff_color_date($type='Y-m-d H:i:s',$time,$color='red'){
	if((time()-$time)>86400){
		return date($type,$time);
	}else{
		return '<font color="'.$color.'">'.date($type,$time).'</font>';
	}
}
// 获取循环标签分页统计 records|currentpage|totalpages
function ff_page_count($pageid='pageid', $key='records'){
	// 通过GET全局变量获当前定义的
	$page = $_GET['ff_page_'.$pageid];
	if(!$page){
		return false;
	}
	return $page[$key];
}
// 处理最大分页参数
function ff_page_max($currentpage, $totalpages){
	if ($currentpage > $totalpages){
		$currentpage = $totalpages;
	}
	return $currentpage;
}
// 获取热门关键词
function ff_site_hot($type='home'){
	if(!C('site_hot')){
		return false;
	}
	$array_hot = array();
	foreach(explode(chr(13),trim(C('site_hot'))) as $key=>$value){
		$array = explode('|',$value);
		if($array[2]){
			$target = ' target="'.$array[2].'"';
		}else{
			$target = '';
		}
		if($array[1]){
			$array_hot[$key] = '<a href="'.$array[1].'"'.$target.'>'.trim($array[0]).'</a>';
		}else{
			$array_hot[$key] = '<a href="'.ff_url('vod/search',array('wd'=>urlencode(trim($array[0]))),true).'"'.$target.'>'.trim($array[0]).'</a>';
		}
	}
	if(C('url_html') && $type=='home'){
		return '<span class="ff-site-hot" id="ff-site-hot">'.implode('',$array_hot).'</span>';
	}
	return '<span class="ff-site-hot">'.implode('',$array_hot).'</span>';
}
// 获取与处理人气值
function ff_get_hits($sidname, $type='hits', $array, $js=true){
	if((C('url_html') && $js) || $type=='insert'){
		return '<span class="ff-hits" data-sid="'.$sidname.'" data-id="'.$array[$sidname.'_id'].'" data-type="'.$type.'"></span>';
	}else{
		return $array[$type];
	}
}
// 返回下一篇或上一篇的内容的信息
function ff_detail_array($module='vod', $type='next', $id, $cid, $field='vod_id,vod_cid,vod_status,vod_name,vod_ename,vod_jumpurl'){
	$where = array();
	$where[$module.'_cid'] = $cid;
	$where[$module.'_status'] = 1;
	if($type == 'next'){
		$where[$module.'_id'] = array('gt', $id);
		$order = $module.'_id asc';
	}else{
		$where[$module.'_id'] = array('lt', $id);
		$order = $module.'_id desc';
	}
	if($module != 'vod'){
		$field = str_replace('vod_', $module.'_', $field);
	}
	$array = D(ucfirst($module))->ff_find($field, $where, 'cache_page_'.$module.$type.'_'.$id, false, $order);
	return $array;
}
//播放器载入
function ff_player($array){
	$json = array();
	$json['yun'] = true;
	$json['url'] = $array['play_url'];
	$json['copyright'] = $array['play_copygiht'];
	$json['name'] = $array['play_name_en']; if($array['play_copygiht']){ $json['name'] = 'copyright'; }	
	$json['jiexi'] = $array['play_jiexi'];
	$json['time'] = $array['play_second'];
	$json['buffer'] = $array['play_buffer'];
	$json['pause'] = $array['play_pause'];
	$json['next_path'] = NULL;
	$json['next_url'] = $array['play_url_next'];
	if($json['next_url']){
		$json['next_path'] = ff_url_play($array['list_id'],$array['list_dir'],$array['play_id'],$array['vod_ename'],$array['play_sid'],$array['play_pid']+1);
	}
	return '<script>var cms_player = '.json_encode($json).';</script><script type="text/javascript" src="'.C('site_path').'Public/player/'.$json['name'].'.js"></script><script type="text/javascript" src="'.C('site_path').'Public/player/yun.js"></script>';
}
//播放列表格式化
function ff_play_list($server, $play, $url){
	//加载配置文件
	$conf_play = F('_feifeicms/player');
	$conf_server = C('play_server');
	//分解播放器组
	$array_server = explode('$$$',$server);
	$array_play = explode('$$$',$play);
	$array_url = explode('$$$',$url);
	//定义播放器每一组对应的地址合集(如有多个youku)
	$array = array();
	foreach($array_play as $sid=>$val){
		$array[$val][$sid] = $array_url[$sid];
	}
	//按播放器配置排序组合成前台循环的二维数组
	$play_list = array();
	foreach($conf_play as $conf_key=>$conf_value){
		if($play_one = $array[$conf_key]){
			foreach($play_one as $sid=>$url_one){
				$son = ff_play_list_one($url_one, $conf_server[$array_server[$sid]]);
				$play_list[$sid+1] = array(
					'server_name' => $array_server[$sid],
					'server_url' => $conf_server[$array_server[$sid]],
					'player_sid' => $sid+1,
					'player_name_en' => $conf_key,
					'player_name_zh' => $conf_value[0],
					'player_copyright' => $conf_value[2],
					'player_info' => $conf_value[1],
					'player_jiexi' => $conf_value[3],
					'player_count' => count($son),
					'son' => $son,
				);
			}
		}
	}
	return $play_list;
}
//分解单组播放地址链接
function ff_play_list_one($url_one, $server_url){
	$url_list = array();
	$array_url = explode(chr(13),str_replace(array("\r\n", "\n", "\r"),chr(13),$url_one));
	foreach($array_url as $key=>$val){
		list($title, $url, $logo, $title_rc, $player) = explode('$', $val);
		if ( empty($url) ) {
			$url_list[$key]['title'] = '第'.($key+1).'集';
			$url_list[$key]['url'] = $server_url.$title;
		}else{
			$url_list[$key]['title'] = $title;
			$url_list[$key]['url'] = $server_url.$url;
		}
		$url_list[$key]['url'] = str_replace('{feifeicms}','',$url_list[$key]['url']);
		$url_list[$key]['logo'] = str_replace('{feifeicms}','',$logo);
		$url_list[$key]['title_rc'] = str_replace('{feifeicms}','',$title_rc);
		$url_list[$key]['player'] = str_replace('{feifeicms}','',$player);
	}
	return $url_list;
}
//获取某一组的播放地址
function ff_play_one($vod_play_list, $play_name='max'){
	if($play_name == 'max'){
		$max = $vod_play_list[0]['player_count'];
		$max_key = 0;
		foreach($vod_play_list as $key=>$value){
			if( $value['player_count'] > $max && (!in_array($value['player_name_en'], array('yugao','down','magnet'))) ){
				$max = $value['player_count'];
				$max_key = $key;
			}
		}
		return $vod_play_list[$max_key];
	}else{
		$play = list_search($vod_play_list, array('player_name_en'=>$play_name));
		return $play[0];
	}
}
//格式化在线充值列表
function ff_PaymentItem(){
	$array = array();
	if(C('pay_code_appid')){
		$code_pay_type = explode(',',C('pay_code_type'));
		if(in_array(1,$code_pay_type)){
			array_push($array, 'code_ali');
		}
		if(in_array(3,$code_pay_type)){
			array_push($array, 'code_wxpay');
		}
		if(in_array(2,$code_pay_type)){
			array_push($array, 'code_qq');
		}
	}	
	if(C('pay_rj_appid')){
		array_push($array, 'rj');
	}
	if(C('pay_paypal_account')){
		array_push($array, 'paypal');
	}
	if(C('pay_alipay_account')){
		array_push($array, 'alipay');
	}
	if(C('pay_wxpay_account')){
		array_push($array, 'wxpay');
	}
	return $array;
}
//分钟转秒数
function ff_Mintue2Length($minute){
	return intval($minute*60);
}
//时长转秒数 return 7200
function ff_Length2Second($length){
	list($hour,$minute,$second) = explode(':',$length);
	return intval($hour*3600)+intval($minute*60)+intval($second);
}
//秒数转时长 return 01:59:59
function ff_Second2Length($second){
	if($second){
		$array = array();
		$array['houer'] = floor($second/3600); 
		$array['minute'] = floor(($second-3600 * $array['houer'])/60);
		$array['second'] = floor((($second-3600 * $array['houer']) - 60 * $array['minute']) % 60);
		return sprintf("%02d",$array['houer']).':'.sprintf("%02d",$array['minute']).':'.sprintf("%02d",$array['second']);
	}else{
		return '00:00:00';
	}
}
//迅雷专用链
function ff_ThunderEncode($url) {
	$thunderPrefix = "AA";
	$thunderPosix = "ZZ";
	$thunderTitle = "thunder://";
	if(strstr($url,"thunder://")){
		return $url;
	}elseif(strstr($url,"magnet")){
		return $url;
	}else{
		$thunderUrl = $thunderTitle.base64_encode($thunderPrefix.$url.$thunderPosix);
	}
	return $thunderUrl;
}
/*-------------------------------------------------访问路径函数开始------------------------------------------------------------------*/
//生成动态链接函数 ff_url('news/read',array('id'=>3,'p'=>2),true);
function ff_url($model, $params, $suffix=true){
	//是否存在跳转链接
	if ($params['jumpurl']) {
		return $params['jumpurl'];
	}
	//分页处理
	if($params['p'] == 1){
		unset($params['p']);
	}elseif($params['p'] == 'FFLINK'){
		$params['p'] = 0.20161212;
		return str_replace('0.20161212', 'FFLINK', ff_url($model, $params, $suffix));
	}
	/*过滤掉无效参数
	foreach($params as $key=>$value){
		if(!$value){
			unset($params[$key]);
		}
	}*/
	//TP系统动态风址
	$url = U($model, $params, false, $suffix);
	$url = str_replace(array('Admin-','Home-','Plus-','Crontab-'),'',$url);
	// 自定义路由反向生成对应的URL
	if( C('URL_ROUTER_ON') ){
		$url = ff_url_replace_route($model, $params, $url);
	}
	// 伪静态
	if(C('url_rewrite')){
		$url = str_replace('index.php?s=/','',$url);
	}
	return $url;
}
// 内容页链接函数
function ff_url_detail($model='vod/read', $params, $suffix=true){
	//$params['id'],$params['p'],$params['pinyin'],$params['list_id'],$params['list_dir'],$params['jumpurl']
	// 跳转地址
	if ($params['jumpurl']) {
		return $params['jumpurl'];
	}
	// 静态模式
	if( C('url_html') ){
		if( $model=='news/read' ){
			if(C('url_html') && C('url_news_detail')){
				$url = ff_url_build('news/read', $params).C('html_file_suffix');
				return ff_url_replace_html($model, $url);
			}
		}else if( $model=='vod/read' ){
			if(C('url_html') && C('url_vod_detail')){
				$url = ff_url_build('vod/read', $params).C('html_file_suffix');
				return ff_url_replace_html($model, $url);
			}
		}else if($model=='vod/play'){
			if(C('url_html') && C('url_vod_play')){
				$url = ff_url_build('vod/play', $params).C('html_file_suffix');
				return ff_url_replace_html($model, $url);
			}
		}
	}
	// 动态模式
	unset($params['list_id']);
	unset($params['list_dir']);
	unset($params['pinyin']);
	unset($params['jumpurl']);
	return ff_url($model, $params, $suffix);
}
// 分类页链接函数
function ff_url_show($model='list/read', $params, $suffix=true){
	//$params['id'],$params['list_dir'],$params['p']
	// 静态模式
	if( C('url_html') ){
		if( $model=='list/read' && C('url_list')){
			$url = ff_url_build('list/read', $params).C('html_file_suffix');
			return ff_url_replace_html($model, $url);
		}
	}
	// 生成伪静态自定义拼音特殊结构链接
	if( C('url_rewrite') && C('url_router_on') && $params['list_dir'] ){
		if( C('url_rewrite_rules.list/ename/id/p') ){
			$params['id'] = $params['list_dir'];
			$model = 'list/ename';
		}elseif( C('url_rewrite_rules.list/ename/id') ){
			$params['id'] = $params['list_dir'];
			$model = 'list/ename';
		}
	}
	// 动态模式
	unset($params['list_dir']);	
	return ff_url($model, $params, $suffix);
}
//分页带链接展示函数
function ff_url_page($model, $params, $suffix=false, $pageid='pageid', $halfPer=5, $page = false){
	$first_page = ff_url_show($model, array_merge($params,array('p'=>1)), $suffix);
	if(!$page){
		$page = $_GET['ff_page_'.$pageid];
	}
	if(!$page){
		return false;
	}
	$jumpurl = ff_url_show($model, $params, $suffix);
	//
	if($page['currentpage'] < $halfPer){
		$halfPer = $halfPer+($halfPer-$page['currentpage']);
	}else{
		if($page['currentpage']+$halfPer>$page['totalpages']){
			$halfPer = $halfPer+($halfPer-($page['totalpages']-$page['currentpage']));
		}
	}
	$link = '';
	if($page['currentpage'] > $halfPer){
		$link = '<li class="page-item"><a class="page-link" href="'.$first_page.'">1..</a></li>';
	}
	if( $page['currentpage'] == 2){
		$link .= '<li class="page-item"><a class="page-link" href="'.$first_page.'">&laquo;</a></li>';
	}else if( $page['currentpage'] > 2){
		$link .= '<li class="page-item"><a class="page-link" href="'.str_replace('FFLINK', ($page['currentpage']-1), $jumpurl).'">&laquo;</a></li>';
	}
	for($i=$page['currentpage']-$halfPer,$i>1||$i=1,$j=$page['currentpage']+$halfPer,$j<$page['totalpages']||$j=$page['totalpages'];$i<$j+1;$i++){
		if($i == 1){
			if($page['currentpage']==1){
				$link .= '<li class="page-item disabled"><a class="page-link" href="'.$first_page.'">1</a></li>';
			}else{
				$link .= '<li class="page-item"><a class="page-link" href="'.$first_page.'">1</a></li>';
			}
		}else{
			if($i == $page['currentpage']){
				$link .= '<li class="page-item disabled"><a class="page-link" href="'.str_replace('FFLINK', $i, $jumpurl).'">'.$i.'</a></li>';
			}else{
				$link .= '<li class="page-item"><a class="page-link" href="'.str_replace('FFLINK', $i, $jumpurl).'">'.$i.'</a></li>';
			}
		}
	}
	if($page['currentpage']+$halfPer < $page['totalpages']){
		$link .= '<li class="page-item"><a class="page-link" href="'.str_replace('FFLINK', $page['totalpages'], $jumpurl).'">...'.$page['totalpages'].'</a></li>';
	}
	if($page['currentpage'] < $page['totalpages']){
		$link .= '<li class="page-item"><a class="page-link" href="'.str_replace('FFLINK', ($page['currentpage']+1), $jumpurl).'">&raquo;</a></li>';
	}
	//动态删除多余标签与自定义路由反向对应规则
	unset($params['list_dir']);
	return $link;
}
// 视频分类页链接函数
function ff_url_vod_show($list_id, $list_dir, $list_page, $suffix=true){
	return ff_url_show('list/read', array('id'=>$list_id,'list_dir'=>$list_dir,'p'=>$list_page), $suffix);
}
// 文章分类页链接函数
function ff_url_news_show($list_id,$list_dir,$list_page,$suffix=true){
	return ff_url_show('list/read', array('id'=>$list_id,'list_dir'=>$list_dir,'p'=>$list_page), $suffix);
}
// 视频详情页链接函数
function ff_url_read_vod($list_id, $list_dir, $vod_id, $vod_ename, $vod_jumpurl, $suffix=true){
	if($vod_jumpurl){
		return $vod_jumpurl;
	}
	//生成伪静态自定义拼音特殊结构链接
	if( C('url_rewrite') && C('url_router_on') && $list_dir && $vod_ename ){
		if( C('url_rewrite_rules.vod/ename/dir/id') ){//list_dir/vod_pinyin
			return ff_url('vod/ename', array('dir'=>$list_dir,'id'=>$vod_ename), $suffix);
		}elseif( C('url_rewrite_rules.vod/read/dir/id') ){//list_dir/vod_id
			return ff_url('vod/read', array('dir'=>$list_dir,'id'=>$vod_id), $suffix);
		}elseif( C('url_rewrite_rules.vod/ename/cid/id') ){//list_id/vod_pinyin
			return ff_url('vod/ename', array('cid'=>$list_id,'id'=>$vod_ename), $suffix);
		}elseif( C('url_rewrite_rules.vod/read/cid/id') ){//list_id/vod_id
			return ff_url('vod/read', array('cid'=>$list_id,'id'=>$vod_id), $suffix);
		}
	}
	//生成详情页链接
	return ff_url_detail('vod/read', array('list_id'=>$list_id,'list_dir'=>$list_dir,'id'=>$vod_id,'pinyin'=>$vod_ename,'jumpurl'=>$vod_jumpurl), $suffix);
}
//兼容4.1前
function ff_url_vod_read($list_id,$list_dir,$id,$ename,$jumpurl,$suffix=true){
	ff_url_read_vod($list_id,$list_dir,$id,$ename,$jumpurl,$suffix);
}
// 视频播放页链接函数
function ff_url_play($list_id,$list_dir,$vod_id,$vod_ename,$play_sid,$play_pid,$suffix=true){
	//生成伪静态自定义拼音特殊结构链接
	if( C('url_rewrite') && C('url_router_on') && $list_dir && $vod_ename ){
		if( C('url_rewrite_rules.vod/eplay/dir/id/sid/pid') ){
			return ff_url('vod/eplay', array('dir'=>$list_dir,'id'=>$vod_ename,'sid'=>$play_sid,'pid'=>$play_pid), $suffix);
		}elseif( C('url_rewrite_rules.vod/play/dir/id/sid/pid') ){
			return ff_url('vod/play', array('dir'=>$list_dir,'id'=>$vod_id,'sid'=>$play_sid,'pid'=>$play_pid), $suffix);
		}elseif( C('url_rewrite_rules.vod/eplay/cid/id/sid/pid') ){
			return ff_url('vod/eplay', array('cid'=>$list_id,'id'=>$vod_ename,'sid'=>$play_sid,'pid'=>$play_pid), $suffix);
		}elseif( C('url_rewrite_rules.vod/play/cid/id/sid/pid') ){
			return ff_url('vod/play', array('cid'=>$list_id,'id'=>$vod_id,'sid'=>$play_sid,'pid'=>$play_pid), $suffix);
		}elseif( C('url_rewrite_rules.vod/eplay/id/sid/pid') ){
			return ff_url('vod/eplay', array('id'=>$vod_ename,'sid'=>$play_sid,'pid'=>$play_pid), $suffix);
		}
	}
	return ff_url_detail('vod/play', array('list_id'=>$list_id,'list_dir'=>$list_dir,'id'=>$vod_id,'pinyin'=>$vod_ename,'sid'=>$play_sid,'pid'=>$play_pid),$suffix);
}
//兼容4.1前
function ff_url_vod_play($list_id,$list_dir,$vod_id,$vod_ename,$play_sid,$play_pid,$suffix=true){
	ff_url_play($list_id,$list_dir,$vod_id,$vod_ename,$play_sid,$play_pid,$suffix);
}
// 获取影片最后一集 return array(sid,pid,jiname,jipath,pic,info)
function ff_url_play_end($vod_url){
	$arr_url = array();
	$arr_urls = explode('$$$',trim($vod_url));
	$array = array();
	foreach($arr_urls as $key=>$value){
		$arr_url[$key] = explode(chr(13), str_replace(array("\r\n", "\n", "\r"), chr(13), $value) );
		$array[$key] = array(count($arr_url[$key]), $key);
	}
	$max_key = max($array);//最多的播放地址的总集数与KEY
	$play_url_max = explode('$',end($arr_url[$max_key[1]]));//最多播放地址的最后一集
	if($play_url_max[1]){
		return array($max_key[1]+1, $max_key[0], $play_url_max[0], $play_url_max[1], $play_url_max[2], $play_url_max[3]);
	}else{
		return array($max_key[1]+1, $max_key[0], '第'.$max_key[0].'集', $play_url_max[0], $play_url_max[2], $play_url_max[3]);
	}
}
// 文章详情页链接函数
function ff_url_read_news($list_id,$list_dir,$id,$ename,$jumpurl,$page=1,$suffix=true){
	if($jumpurl){
		return $jumpurl;
	}
	if( C('url_rewrite') && C('url_router_on') && $list_dir && $ename ){
		if( C('url_rewrite_rules.news/ename/dir/id/p') ){//list_dir/pinyin/page
			return ff_url('news/ename', array('dir'=>$list_dir,'id'=>$ename,'p'=>$page), $suffix);
		}elseif( C('url_rewrite_rules.news/read/dir/id/p') ){//list_dir/id/page
			return ff_url('news/read', array('dir'=>$list_dir,'id'=>$id,'p'=>$page), $suffix);
		}elseif( C('url_rewrite_rules.news/ename/cid/id/p') ){//list_id/pinyin/page
			return ff_url('news/ename', array('cid'=>$list_id,'id'=>$ename,'p'=>$page), $suffix);
		}elseif( C('url_rewrite_rules.news/read/cid/id/p') ){//list_id/id/page
			return ff_url('news/read', array('cid'=>$list_id,'id'=>$id,'p'=>$page), $suffix);
		}elseif( C('url_rewrite_rules.news/ename/dir/id') ){//list_dir/pinyin
			return ff_url('news/ename', array('dir'=>$list_dir,'id'=>$ename), $suffix);
		}elseif( C('url_rewrite_rules.news/read/dir/id') ){//list_dir/id
			return ff_url('news/read', array('dir'=>$list_dir,'id'=>$id), $suffix);
		}elseif( C('url_rewrite_rules.news/ename/cid/id') ){//list_id/pinyin
			return ff_url('news/ename', array('cid'=>$list_id,'id'=>$ename), $suffix);
		}elseif( C('url_rewrite_rules.news/read/cid/id') ){//list_id/id
			return ff_url('news/read', array('cid'=>$list_id,'id'=>$id), $suffix);
		}
	}	
	return ff_url_detail('news/read', array('list_id'=>$list_id,'list_dir'=>$list_dir,'id'=>$id,'pinyin'=>$ename,'jumpurl'=>$jumpurl,'p'=>$page), $suffix);
}
//兼容4.1前
function ff_url_news_read($list_id,$list_dir,$id,$ename,$jumpurl,$page=1,$suffix=true){
	ff_url_read_news($list_id,$list_dir,$id,$ename,$jumpurl,$page=1,$suffix);
}
// 专题详情页
function ff_url_read_special($list_id, $list_dir, $id, $ename, $suffix=true){
	//伪静态自定义拼音特殊结构链接
	if( C('url_rewrite') && C('url_router_on') && $list_dir && $ename ){
		if( C('url_rewrite_rules.special/ename/dir/id') ){//list_dir/pinyin
			return ff_url('special/ename', array('dir'=>$list_dir,'id'=>$ename), $suffix);
		}elseif( C('url_rewrite_rules.special/read/dir/id') ){//list_dir/id
			return ff_url('special/read', array('dir'=>$list_dir,'id'=>$id), $suffix);
		}elseif( C('url_rewrite_rules.special/ename/cid/id') ){//list_id/pinyin
			return ff_url('special/ename', array('cid'=>$list_id,'id'=>$ename), $suffix);
		}elseif( C('url_rewrite_rules.special/read/cid/id') ){//list_id/id
			return ff_url('special/read', array('cid'=>$list_id,'id'=>$id), $suffix);
		}
	}
	//生成详情页链接
	return ff_url_detail('special/read', array('list_id'=>$list_id,'list_dir'=>$list_dir,'id'=>$id,'pinyin'=>$ename), $suffix);
}
// 人物详情页
function ff_url_read_star($list_id, $list_dir, $id, $ename, $suffix=true){
	if($jumpurl){
		return $jumpurl;
	}
	//伪静态自定义拼音特殊结构链接
	if( C('url_rewrite') && C('url_router_on') && $list_dir && $ename ){
		if( C('url_rewrite_rules.star/ename/dir/id') ){//list_dir/pinyin
			return ff_url('star/ename', array('dir'=>$list_dir,'id'=>$ename), $suffix);
		}elseif( C('url_rewrite_rules.star/read/dir/id') ){//list_dir/id
			return ff_url('star/read', array('dir'=>$list_dir,'id'=>$id), $suffix);
		}elseif( C('url_rewrite_rules.star/ename/cid/id') ){//list_id/pinyin
			return ff_url('star/ename', array('cid'=>$list_id,'id'=>$ename), $suffix);
		}elseif( C('url_rewrite_rules.star/read/cid/id') ){//list_id/id
			return ff_url('star/read', array('cid'=>$list_id,'id'=>$id), $suffix);
		}
	}
	//生成详情页链接
	return ff_url_detail('star/read', array('list_id'=>$list_id,'list_dir'=>$list_dir,'id'=>$id,'pinyin'=>$ename), $suffix);
}
// 角色详情页
function ff_url_read_role($list_id, $list_dir, $id, $ename, $suffix=true){
	if($jumpurl){
		return $jumpurl;
	}
	//伪静态自定义拼音特殊结构链接
	if( C('url_rewrite') && C('url_router_on') && $list_dir && $ename ){
		if( C('url_rewrite_rules.role/ename/dir/id') ){//list_dir/pinyin
			return ff_url('role/ename', array('dir'=>$list_dir,'id'=>$ename), $suffix);
		}elseif( C('url_rewrite_rules.role/read/dir/id') ){//list_dir/id
			return ff_url('role/read', array('dir'=>$list_dir,'id'=>$id), $suffix);
		}elseif( C('url_rewrite_rules.role/ename/cid/id') ){//list_id/pinyin
			return ff_url('role/ename', array('cid'=>$list_id,'id'=>$ename), $suffix);
		}elseif( C('url_rewrite_rules.role/read/cid/id') ){//list_id/id
			return ff_url('role/read', array('cid'=>$list_id,'id'=>$id), $suffix);
		}
	}
	//生成详情页链接
	return ff_url_detail('role/read', array('list_id'=>$list_id,'list_dir'=>$list_dir,'id'=>$id,'pinyin'=>$ename), $suffix);
}
// 获取广告调用地址
function ff_url_ads($str,$charset="utf-8"){
	return '<script type="text/javascript" src="'.C('site_path').C('admin_ads_file').'/'.$str.'.js" charset="'.$charset.'"></script>';
}
// 获取搜索带链接
function ff_url_search($str, $type="actor", $sidname='vod', $action='search'){
	if(!$str){
		return '未知';
	}
	$array = array();
	foreach(explode(',', ff_xml_vodactor($str)) as $key=>$val){
		$array[$key] = '<a href="'.ff_url($sidname.'/'.$action, array($type=>urlencode(trim($val))), true).'">'.trim($val).'</a>';
	}
	return implode('',$array);
}
// Tag链接
function ff_url_tags($str, $tag_list='vod_tag'){
	list($module,$type) = explode('_',$tag_list);
	return ff_url($module.'/tags', array('name' => urlencode($str) ), true);
}
// 内容页Tag链接
function ff_url_tags_content($content, $array_tag){
	if($array_tag){
		foreach($array_tag as $key=>$value){
			if($value['tag_name']){
				$keyword = stripslashes($value['tag_name']);
				$url = '<a href="'.ff_url_tags($value['tag_name'], $value['tag_list']).'" class="ff-tag-link" >'.$keyword.'</a>';
				$regEx = '\'(?!((<.*?)|(<a.*?)))('. $keyword . ')(?!(([^<>]*?)>)|([^>]*?</a>))\'s';
				$content = preg_replace($regEx, $url, $content);
			}
		}
	}
	return $content;
}
// 获取某图片的访问地址
function ff_url_img($file, $content, $number=1){
	if(!$file){
		return ff_url_img_preg($content, $number);
	}
	$array = parse_url($file);
	if(in_array($array['scheme'],array('http','https','ftp'))){
		if( C('upload_referer') ){//第三方防盗链处理
			return C('upload_referer').base64_encode($file);
		}
		$img_host = explode(chr(13), str_replace(array("\r\n", "\n", "\r"),chr(13),C('upload_safety')));
		if( in_array($array['host'], $img_host) ){
			return C('site_path').'index.php?g=home&m=images&a=read&url='.base64_encode($file);
		}
		return $file;
	}
	$prefix = C('upload_http_prefix');
	if(!empty($prefix)){
		return $prefix.$file;
	}else{
		return C('site_path').C('upload_path').'/'.$file;
	}
}
// 获取某图片的缩略图地址
function ff_url_img_small($file, $content, $number=1){
	if(!$file){
		return ff_url_img_preg($content, $number);
	}
	$array = parse_url($file);
	if(in_array($array['scheme'],array('http','https','ftp'))){
		return $file;
	}
	$length = strpos($file, '/');
	$file_s = substr($file, 0, $length).'-s'.substr($file, $length);
	$prefix = C('upload_http_prefix');
	if(!empty($prefix)){
		return $prefix.$file_s;
	}else{
		return C('site_path').C('upload_path').'/'.$file_s;
	}
}
//正则提取正文里指定的第几张图片地址
function ff_url_img_preg($content, $number=1, $ext='gif|jpg|jpeg|bmp|png'){
	preg_match_all("/(href|src)=([\"|']?)([^ \"'>]+\.($ext))\\2/i", $content, $matches);
	$imgarr = array_unique($matches[3]);
	$countimg = count($imgarr);
	if($number > $countimg){
		$number = $countimg;
	}
	$imgurl = $imgarr[($number-1)];
	if($imgurl){
		return $imgurl;
	}
	return C('site_path').'Public/images/no.jpg';
}
// 获取26个字母链接
function ff_url_letters($file='vod',$str=''){
	if(C('url_html')){
		$index='index.html';
	}else{
		$index='index.php';
	}
    for($i=1;$i<=26;$i++){
	   $url = ff_url($file.'/search', array('id'=>chr($i+64),'x'=>'letter'), true);
	   $str.='<a href="'.$url.'" class="letter_on">'.chr($i+64).'</a>';
	}
	return $str;
}
//导航自动处理函数
function ff_url_nav($nav_link, $nav_tips=''){
	preg_match('/list-read-id-([0-9]+)/i', $nav_link, $data);
	if($data[1]){
		return ff_url_show('list/read', array('id'=>$data[1],'list_dir'=>$nav_tips) );
	}
	return $nav_link;
}
// 动态模式 自定义路由反向生成对应的链接URL
function ff_url_replace_route($model, $params, $url){
	//加载反向对应规则
	$url_rules = C('url_rewrite_rules');
	//固定格式或附加参数伪静态
	$key = $model.http_build_query($params);
	if($url_rules[$key]){
		return C('site_path').$url_rules[$key]['replace'];
	}
	//正则表达式伪静态
	$array_key = explode('/',$model);
	foreach($params as $key=>$value){
		array_push($array_key, $key);
	}
	$key = implode('/',$array_key);
	//将对应的URL网址按对应规则替换
	if($url_rules[$key]){
		$url = preg_replace("/".$url_rules[$key]['find']."/i", $url_rules[$key]['replace'], $url);
	}
	return $url;
}
// 根据后台设置的静态网页规则生成保存路径
function ff_url_build($module, $params){
	//$params['list_id'],$params['list_dir'],$params['id'],$params['pinyin'],$params['p'],$params['jumpurl']
	$old = array('{listid}', '{listdir}', '{pinyin}', '{id}', '{md5}', '{page}', '{sid}', '{pid}');
	$new = array($params['list_id'], $params['list_dir'], $params['pinyin'], $params['id'], md5($params['id']), $params['p'], $params['sid'], $params['pid']);
	if('list/read' == $module){
		$html_path = C('url_list');
	}else if('vod/read' == $module){
		$html_path = C('url_vod_detail');
	}else if('vod/play' == $module){
		$html_path = C('url_vod_play');
	}else if('news/read' == $module){
		$html_path = C('url_news_detail');
	}
	$html_path = str_replace($old, $new, $html_path);
	//第一页去除页码规则
	if($params['p'] == 1){
		$html_path .= 'BUILD';
		$old = array( '/1BUILD', '-1BUILD', '_1BUILD');
		$new = array('/indexBUILD', 'BUILD', 'BUILD');
		$html_path = str_replace('BUILD', '', str_replace($old, $new, $html_path));
	}
	//首页index处理
	$suffix = strrchr($html_path, '/');
	if($suffix == '/'){
		$html_path .= 'index';
	}
	return C('site_path').$html_path;
}
//静态模式 格式化页码为1的首个链接
function ff_url_replace_html($model, $link){
	$replace = false;
	if( $model=='list/read' ){
		if(C('url_list')){
			$replace = true;
		}
	}else if( $model=='vod/read' ){
		if(C('url_vod_detail')){
			$replace = true;
		}
	}else if( $model=='vod/play' ){
		if(C('url_vod_play')){
			$replace = true;
		}
	}else if( $model=='news/read' ){
		if(C('url_news_detail')){
			$replace = true;
		}
	}
	if($replace){
		if( $model=='vod/play' ){
			$old = array('/index'.C('html_file_suffix'));
		}else{
			$old = array('/index'.C('html_file_suffix'), '-1'.C('html_file_suffix'), '_1'.C('html_file_suffix'));
		}
		$new = array('/', C('html_file_suffix'), C('html_file_suffix'));
		$link = str_replace($old, $new, $link);
	}
	return $link;
}
/*-------------------------------------------------栏目分类相关函数开始------------------------------------------------------------------*/
//通过栏目条件获取对应的栏目名称/别名等
function ff_list_find($cid, $field='list_name'){
	$info = D("List")->ff_find($field, array('list_id'=>array('eq',$cid)), false);
	if($info){
		return $info[$field];
	}else{
		return false;
	}
}
// 检查当前栏目是否没有小类
function ff_list_isson($pid){
	$count = M("List")->where('list_pid='.$pid)->count('list_id');
	if($count){
		return false;
	}else{
		return true;
	}
}
//获取当前分类的子类
function ff_list_ids($cid){
	$tree = list_search(ff_mysql_list(array('limit'=>0,'order'=>'list_oid','sort'=>'asc','cache_name'=>'default','cache_time'=>'default')), 'list_id='.$cid);
	$array = array();
	if (!empty($tree[0]['list_son'])) {
		foreach($tree[0]['list_son'] as $val){
			$array[] = $val['list_id'];
		}
	}
	array_push($array, $cid);
	return implode(',', array_unique($array)); 
}
// 获取栏目数据统计
function ff_list_count($cid=999){
	$where = array();
	if(999 == $cid){
		$where['vod_cid'] = array('gt',0);
		$where['vod_addtime'] = array('gt',ff_linux_time(1));//当天更新的影视
		$count = M("Vod")->where($where)->count('vod_id');
	}elseif(0 == $cid){
		$where['vod_cid'] = array('gt',0);
		$count = M("Vod")->where($where)->count('vod_id');
	}else{
		$sid = ff_list_find($cid,'list_sid');
		if ($sid == '1'){
			$where['vod_cid'] = array('in',ff_list_ids($cid));	
			$where['vod_status'] = 1;
			$count = M("Vod")->where($where)->count('vod_id');
		}elseif ($sid == '2'){
			$where['news_cid'] = array('in',ff_list_ids($cid));
			$where['news_status'] = 1;
			$count = M("News")->where($where)->count('news_id');
		}elseif ($sid == '3'){
			$where['special_cid'] = array('in',ff_list_ids($cid));
			$where['special_status'] = 1;
			$count = M("Special")->where($where)->count('special_id');
		}elseif ($sid == '7'){	
			$where['vod_scenario'] = array('neq','');
			$where['vod_status'] = 1;
			$count = M("Vod")->where($where)->count('vod_id');
		}elseif ($sid == '8'){
			$where['person_cid'] = array('in',ff_list_ids($cid));
			$where['person_status'] = 1;
			$count = M("Person")->where($where)->count('person_id');
		}elseif ($sid == '9'){
			$where['person_cid'] = array('in',ff_list_ids($cid));
			$where['person_status'] = 1;
			$count = M("Person")->where($where)->count('person_id');
		}
	}
	return $count+0;
}
/*------------------------------------------------------标签解析函数开始--------------------------------------------------------*/
//路径参数处理函数
function ff_param_url(){
	$array = array();
	$array['id'] = intval($_REQUEST['id']);
	$array['cid'] = intval($_REQUEST['cid']);
	$array['sid'] = intval($_REQUEST['sid']);
	$array['limit'] = !empty($_GET['limit']) ? intval($_GET['limit']) : 10;
	$array['page'] = !empty($_GET['p']) ? intval($_GET['p']) : 1;
	$array['order'] = ff_order_by($_GET['order']);
	$array['ajax'] = intval($_REQUEST['ajax']);
	//
	$array['wd'] = htmlspecialchars(urldecode(trim($_REQUEST['wd'])));
	$array['type'] = htmlspecialchars(urldecode(trim($_REQUEST['type'])));
	$array['area'] = htmlspecialchars(urldecode(trim($_REQUEST['area'])));
	$array['year'] = htmlspecialchars(urldecode($_REQUEST['year']));
	$array['state'] = htmlspecialchars(urldecode(trim($_REQUEST['state'])));
	$array['ispay'] = htmlspecialchars(trim($_REQUEST['ispay']));
	$array['language'] = htmlspecialchars(urldecode(trim($_REQUEST['language'])));
	$array['star'] = htmlspecialchars(urldecode(trim($_REQUEST['star'])));
	$array['letter'] = htmlspecialchars(trim($_REQUEST['letter']));
	$array['actor'] = htmlspecialchars(urldecode(trim($_REQUEST['actor'])));
	$array['director'] = htmlspecialchars(urldecode(trim($_REQUEST['director'])));
	$array['writer'] = htmlspecialchars(urldecode(trim($_REQUEST['writer'])));
	$array['name'] = htmlspecialchars(urldecode(trim($_REQUEST['name'])));
	$array['ename'] = htmlspecialchars(trim($_REQUEST['ename']));
	$array['remark'] = htmlspecialchars(urldecode(trim($_REQUEST['remark'])));
	$array['play'] = htmlspecialchars(urldecode(trim($_REQUEST['play'])));
	$array['inputer'] = htmlspecialchars(urldecode(trim($_REQUEST['inputer'])));
	$array['tag'] = htmlspecialchars(urldecode(trim($_REQUEST['tag'])));
	$array['gender'] = htmlspecialchars(urldecode(trim($_REQUEST['gender'])));//性别
	$array['profession'] = htmlspecialchars(urldecode(trim($_REQUEST['profession'])));//职业
	return $array;
}
//分页跳转参数处理(多余空的将去除)
function ff_param_jump($where){
	if($where['id']){
		$jumpurl['id'] = $where['id'];
	}	
	if($where['cid']){
		$jumpurl['cid'] = $where['cid'];
	}	
	if($where['sid']){
		$jumpurl['sid'] = $where['sid'];
	}
	if($where['wd']){
		$jumpurl['wd'] = urlencode($where['wd']);
	}	
	if($where['area']){
		$jumpurl['area'] = urlencode($where['area']);
	}
	if($where['actor']){
		$jumpurl['actor'] = urlencode($where['actor']);
	}
	if($where['director']){
		$jumpurl['director'] = urlencode($where['director']);
	}
	if($where['year']){
		$jumpurl['year'] = $where['year'];
	}
	if($where['language']){
		$jumpurl['language'] = urlencode($where['language']);
	}
	if($where['star']){
		$jumpurl['star'] = urlencode($where['star']);
	}
	if($where['state']){
		$jumpurl['state'] = urlencode($where['state']);
	}
	if($where['gender']){
		$jumpurl['gender'] = urlencode($where['gender']);
	}
	if($where['profession']){
		$jumpurl['profession'] = urlencode($where['profession']);
	}	
	if($where['letter']){
		$jumpurl['letter'] = $where['letter'];
	}	
	if($where['limit']){
		$jumpurl['limit'] = $where['limit'];
	}
	if($where['order'] != 'addtime' && $where['order']){
		$jumpurl['order'] = $where['order'];
	}
	$jumpurl['p'] = '';
	return $jumpurl;
}
//返回安全的orderby
function ff_order_by($order = 'addtime'){
	if(empty($order)){
		return 'hits';
	}
	$array = array();
	$array['id'] = 'id';
	$array['hits'] = 'hits';
	$array['hits_month'] = 'hits_month';
	$array['hits_week'] = 'hits_week';
	$array['stars'] = 'stars';
	$array['up'] = 'up';
	$array['down'] = 'down';
	$array['gold'] = 'gold';
	$array['golder'] = 'golder';
	$array['score'] = 'score';
	$array['year'] = 'year';
	$array['letter'] = 'letter';
	$array['addtime'] = 'addtime';
	$array['filmtime'] = 'filmtime';
	$array['logtime'] = 'logtime';
	$array['deadtime'] = 'deadtime';
	return $array[trim($order)];
}
// 生成参数列表,以数组形式返回
function ff_param_lable($tag = ''){
	//3.3增加传入数组则直接解析
	if(is_array($tag)){
		return $tag;
	}
	//标签解析
	$param = array();
	$array = explode(';', $tag);
	foreach ($array as $v){
		list($key,$val) = explode(':',trim($v));
		$param[trim($key)] = trim($val);
	}
	//4.1默认只查询已审核的
	if(!isset($param['status'])) {
		$param['status'] = 1;
	}
	return $param;
}
// 循环标签查询参数格式化
function ff_mysql_param($tag){
	$params = array();
	// 数据表字段
	$params['field']= isset($tag['field']) ? $tag['field'] : '*';
	// 查询条目
	$params['limit']= isset($tag['limit']) ? $tag['limit'] : '10';
	// 排序参数
	$params['order']= isset($tag['order']) ? $tag['order'] : '';
	$params['sort']= isset($tag['sort']) ? $tag['sort'] : '';
	// 分组参数
	$params['group']= isset($tag['group']) ? $tag['group'] : '';
	// 分页参数
	$params['page_is']= isset($tag['page_is']) ? $tag['page_is'] : false;
	$params['page_id']= isset($tag['page_id']) ? $tag['page_id'] : '';
	$params['page_p']= isset($tag['page_p']) ? $tag['page_p'] : '';
	// 缓存参数
	if($tag['cache_name'] ==  'default'){
		$params['cache_name'] = md5(C('cache_foreach_prefix').'_'.implode('_',$tag));
	}else{
		$params['cache_name']= isset($tag['cache_name']) ? md5(C('cache_foreach_prefix').'_'.$tag['cache_name']) : '';
	}
	// 缓存时间
	if($tag['cache_time'] == 'default'){
		$params['cache_time']= intval(C('cache_foreach'));
	}else{
		$params['cache_time']= isset($tag['cache_time']) ? intval($tag['cache_time']) : '';
	}
	return $params;
}
// 循环标签.导航
function ff_mysql_nav($tag){
	$tag = ff_param_lable($tag);
	$where = array();
	if (isset($tag['status'])) {
		$where['nav_status'] = array('eq', $tag['status']);
	}
	if ($tag['ids']) {
		$where['nav_id'] = array('in',$tag['ids']);
	}
	if (isset($tag['pid'])) {
		$where['nav_pid'] = array('in',$tag['pid']);
	}
	return D('Nav')->ff_select_page(ff_mysql_param($tag), $where);
}
// 循环标签.轮播
function ff_mysql_slide($tag){
	$tag = ff_param_lable($tag);
	$where = array();
	if (isset($tag['status'])) {
		$where['slide_status'] = array('eq', $tag['status']);
	}
	if ($tag['ids']) {
		$where['slide_id'] = array('in',$tag['ids']);
	}
	if ($tag['cid']) {
		$where['slide_cid'] = array('in',$tag['cid']);
	}
	return D('Slide')->ff_select_page(ff_mysql_param($tag), $where);
}
// 循环标签.分类
function ff_mysql_list($tag){
	$tag = ff_param_lable($tag);
	$where = array();
	if (isset($tag['status'])) {
		$where['list_status'] = array('eq', $tag['status']);
	}
	if ($tag['ids']) {
		$where['list_id'] = array('in',$tag['ids']);
	}
	if (isset($tag['pid'])) {
		$where['list_pid'] = array('in',$tag['pid']);
	}
	if ($tag['sid']) {
		$where['list_sid'] = array('in',$tag['sid']);
	}
	return D('List')->ff_select_page(ff_mysql_param($tag), $where);
}
// 循环标签.友链
function ff_mysql_link($tag){
	$tag = ff_param_lable($tag);
	$where = array();
	if ($tag['ids']) {
		$where['link_id'] = array('in',$tag['ids']);
	}
	if ($tag['type']) {
		$where['link_type'] = array('eq',$tag['type']);
	}
	return D('Link')->ff_select_page(ff_mysql_param($tag), $where);
}
// 循环标签.TAG话题
function ff_mysql_tags($tag_str){
	$tag = ff_param_lable($tag_str);
	$where = array();
	if ($tag['ids']) {
		$where['tag_id'] = array('in',$tag['ids']);
	}
	if ($tag['cid']) {
		$where['tag_cid'] = array('in',$tag['cid']);
	}
	if ($tag['list']) {
		$where['tag_list'] = array('eq',''.$tag['list'].'');
	}
	$rs = D('Tag');
	return $rs->ff_select_page(ff_mysql_param($tag), $where);
}
// 循环标签.视频
function ff_mysql_vod($tag_str){
	$tag = ff_param_lable($tag_str);
	$where = array();
	if(isset($tag['status'])){
		$where['vod_status'] = array('eq', $tag['status']);
	}
	if($tag['list_ename']){
		$where['list_dir'] =  array('eq',$tag['list_ename']);
	}
	if($tag['pic_slide'] == 'true'){
		$where['vod_pic_slide'] = array('neq','');
	}else if($tag['pic_slide'] == 'false'){
		$where['vod_pic_slide'] = array('eq','');
	}
	if($tag['pic_bg'] == 'true'){
		$where['vod_pic_bg'] = array('neq','');
	}else if($tag['pic_bg'] == 'false'){
		$where['vod_pic_bg'] = array('eq','');
	}
	if($tag['pic'] == 'true'){
		$where['vod_pic'] = array('neq','');
	}if($tag['pic'] == 'false'){
		$where['vod_pic'] = array('eq','');
	}
	if($tag['url'] == 'true'){
		$where['vod_url'] = array('neq','');
	}if($tag['url'] == 'false'){
		$where['vod_url'] = array('eq','');
	}	
	if($tag['scenario'] == 'true'){//剧情
		$where['vod_scenario'] = array('neq','');
	}else if($tag['scenario'] == 'false'){
		$where['vod_scenario'] = array('eq','');
	}
	if($tag['continu'] == 'true'){//连载信息
		$where['vod_continu'] = array('neq',0);
	}if($tag['continu'] == 'false'){
		$where['vod_continu'] = array('eq',0);
	}	
	if($tag['isend'] == 'true'){
		$where['vod_isend'] = array('eq', 1);
	}else if($tag['isend'] == 'false'){
		$where['vod_isend'] = array('eq', 0);
	}	
	if($tag['lines'] == 'true'){//台词4.1
		$where['vod_lines'] = array('neq','');
	}if($tag['lines'] == 'false'){
		$where['vod_lines'] = array('eq','');
	}
	if($tag['ending'] == 'true'){//大结局4.1
		$where['vod_ending'] = array('neq','');
	}if($tag['ending'] == 'false'){
		$where['vod_ending'] = array('eq','');
	}
	if($tag['price'] == 'true'){//4.1
		$where['vod_price'] = array('gt',0);
	}else if($tag['price'] == 'false'){
		$where['vod_price'] = array('lt',1);
	}
	if($tag['trysee'] == 'true'){//4.1
		$where['vod_trysee'] = array('gt',0);
	}else if($tag['trysee']  == 'false'){
		$where['vod_trysee'] = array('lt',1);
	}
	if($tag['ispay'] == 'true'){//4.1
		$where['vod_ispay'] = array('gt',0);
	}else if($tag['ispay'] == 'false'){//4.1
		$where['vod_ispay'] = array('lt',1);
	}
	if($tag['douban'] == 'true'){//4.1
		$where['vod_douban_id'] = array('gt',0);
	}else if($tag['douban'] == 'false'){
		$where['vod_douban_id'] = array('lt',1);
	}else if($tag['douban']){
		$where['vod_douban_id'] = array('in',$tag['douban']);
	}
	if($tag['series'] == 'true'){//系列
		$where['vod_series'] = array('neq','');
	}else if($tag['series'] == 'false'){
		$where['vod_series'] = array('eq','');
	}else if($tag['series']){
		$where['vod_series'] = array('eq',$tag['series']);
	}
	if($tag['inputer']){
		$where['vod_inputer'] = array('eq',$tag['inputer']);
	}
	if($tag['state']){
		$where['vod_state'] = array('eq',$tag['state']);
	}
	if($tag['version']){
		$where['vod_version'] = array('eq',$tag['version']);
	}
	if($tag['ids']){
		$where['vod_id'] = array('in',$tag['ids']);
	}
	if($tag['ids_not']){
		$where['vod_id'] = array('not in',$tag['ids_not']);
	}
	if($tag['cid']){
		$where['vod_cid'] = array('in',$tag['cid']);
	}
	if($tag['cid_not']){
		$where['vod_cid'] = array('not in',$tag['cid_not']);
	}
	if($tag['stars']){
		$where['vod_stars'] = array('in',$tag['stars']);
	}		
	if($tag['id_min']){//4.0
		$where['vod_id'] = array('gt',$tag['id_min']);
	}
	if($tag['id_max']){//4.0
		$where['vod_id'] = array('lt',$tag['id_max']);
	}
	if($tag['addtime']){//4.1
		$where['vod_addtime'] = array('gt',$tag['addtime']);
	}	
	if($tag['letter'] || $tag['letter'] == '0'){
		$where['vod_letter'] = array('in',$tag['letter']);
	}	
	if(isset($tag['upday'])){
		$where['vod_addtime'] = array('gt',ff_linux_time($tag['upday']));
	}
	if(isset($tag['lastday'])){
		$where['vod_hits_lasttime'] = array('gt',ff_linux_time($tag['lastday']));
	}
	if(isset($tag['filmday'])){
		$where['vod_filmtime'] = array('gt',ff_linux_time($tag['filmday']));
	}
	if(isset($tag['copyright'])){
		$where['vod_copyright'] = array('gt',$tag['copyright']);
	}
	if($tag['up']){
		$up = explode(',',$tag['up']);
		if (count($up) > 1) {
			$where['vod_up'] = array('between',$up[0].','.$up[1]);
		}else{
			$where['vod_up'] = array('gt',$up[0]);
		}
	}
	if($tag['down']){
		$down = explode(',',$tag['down']);
		if (count($down) > 1) {
			$where['vod_down'] = array('between',$down[0].','.$down[1]);
		}else{
			$where['vod_down'] = array('gt',$down[0]);
		}
	}
	if($tag['gold']){
		$gold = explode(',',$tag['gold']);
		if (count($gold) > 1) {
			$where['vod_gold'] = array('between',$gold[0].','.$gold[1]);
		}else{
			$where['vod_gold'] = array('gt',$gold[0]);
		}
	}
	if($tag['golder']){
		$golder = explode(',',$tag['golder']);
		if (count($golder) > 1) {
			$where['vod_golder'] = array('between',$golder[0].','.$golder[1]);
		}else{
			$where['vod_golder'] = array('gt',$golder[0]);
		}
	}
	if($tag['hits']){
		$hits = explode(',',$tag['hits']);
		if (count($hits) > 1) {
			$where['vod_hits'] = array('between',$hits[0].','.$hits[1]);
		}else{
			$where['vod_hits'] = array('gt',$hits[0]);
		}
	}
	if($tag['year']){
		$year = explode(',',$tag['year']);
		if (count($year) > 1) {
			$where['vod_year'] = array('between',$year[0].','.$year[1]);
		}else{
			$where['vod_year'] = array('eq',$tag['year']);
		}
	}
	if($tag['wd']){
		$search = array();
		$search['vod_name'] = array('like','%'.$tag['wd'].'%');
		$search['vod_title'] = array('like','%'.$tag['wd'].'%');
		$search['vod_actor'] = array('like','%'.$tag['wd'].'%');
		$search['vod_director'] = array('like','%'.$tag['wd'].'%');
		$search['_logic'] = 'or';
		$where['_complex'] = $search;
		//$where['vod_weekday'] = array('in','周一,周二');
		//$where['vod_weekday'] = array(array('like','周一%'), array('like','周二%'), 'or');
	}
	if($tag['person']){//4.1
		$search = array();
		$where['vod_actor'] = array('like','%'.$tag['actor'].'%');
		$where['vod_director'] = array('like','%'.$tag['director'].'%');
		$where['vod_writer'] = array('like','%'.$tag['writer'].'%');
		$where['vod_producer'] = array('like','%'.$tag['producer'].'%');
		$where['vod_camera'] = array('like','%'.$tag['camera'].'%');
		$where['vod_editor'] = array('like','%'.$tag['editor'].'%');
		$where['vod_music'] = array('like','%'.$tag['music'].'%');
		$where['vod_art'] = array('like','%'.$tag['art'].'%');
		$search['_logic'] = 'or';
		$where['_complex'] = $search;
	}
	if($tag['name']){
		$where['vod_name'] = array('like','%'.$tag['name'].'%');
	}
	if($tag['title']){
		$where['vod_title'] = array('like','%'.$tag['title'].'%');
	}
	if($tag['ename']){
		$where['vod_ename'] = array('like','%'.$tag['ename'].'%');
	}	
	if($tag['play']){
		foreach(explode(',',$tag['play']) as $key=>$value){
			$where['vod_play'][] = array('like','%'.$value.'%');
		}
		$where['vod_play'][] = 'or';
	}
	if($tag['tv']){
		foreach(explode(',',$tag['tv']) as $key=>$value){
			$where['vod_tv'][] = array('like','%'.$value.'%');
		}
		$where['vod_tv'][] = 'or';
	}
	if($tag['actor']){
		foreach(explode(',',$tag['actor']) as $key=>$value){
			$where['vod_actor'][] = array('like','%'.$value.'%');
		}
		$where['vod_actor'][] = 'or';
	}
	if($tag['director']){
		foreach(explode(',',$tag['director']) as $key=>$value){
			$where['vod_director'][] = array('like','%'.$value.'%');
		}
		$where['vod_director'][] = 'or';
	}
	if($tag['writer']){//4.1
		foreach(explode(',',$tag['writer']) as $key=>$value){
			$where['vod_writer'][] = array('like','%'.$value.'%');
		}
		$where['vod_writer'][] = 'or';
	}
	if($tag['producer']){//4.1
		foreach(explode(',',$tag['producer']) as $key=>$value){
			$where['vod_producer'][] = array('like','%'.$value.'%');
		}
		$where['vod_producer'][] = 'or';
	}
	if($tag['camera']){//4.1
		foreach(explode(',',$tag['camera']) as $key=>$value){
			$where['vod_camera'][] = array('like','%'.$value.'%');
		}
		$where['vod_camera'][] = 'or';
	}
	if($tag['editor']){//4.1
		foreach(explode(',',$tag['editor']) as $key=>$value){
			$where['vod_editor'][] = array('like','%'.$value.'%');
		}
		$where['vod_editor'][] = 'or';
	}
	if($tag['music']){//4.1
		foreach(explode(',',$tag['music']) as $key=>$value){
			$where['vod_music'][] = array('like','%'.$value.'%');
		}
		$where['vod_music'][] = 'or';
	}
	if($tag['art']){//4.1
		foreach(explode(',',$tag['art']) as $key=>$value){
			$where['vod_art'][] = array('like','%'.$value.'%');
		}
		$where['vod_art'][] = 'or';
	}		
	if($tag['weekday']){
		foreach(explode(',',$tag['weekday']) as $key=>$value){
			$where['vod_weekday'][] = array('like','%'.$value.'%');
		}
		$where['vod_weekday'][] = 'or';
	}
	if($tag['area']){
		foreach(explode(',',$tag['area']) as $key=>$value){
			$where['vod_area'][] = array('like','%'.$value.'%');
		}
		$where['vod_area'][] = 'or';
	}
	if($tag['language']){
		foreach(explode(',',$tag['language']) as $key=>$value){
			$where['vod_language'][] = array('like','%'.$value.'%');
		}
		$where['vod_language'][] = 'or';
	}
	// 同名查询条件
	if(isset($tag['like_length'])){
		$list = D('Vod')->ff_select_name($tag['like_length'], $tag['cid']);
		foreach($list as $key=>$value){
			if($tag['like_length'] > 0 ){
				$where['vod_name'][] = array('like', $value["vod_name"].'%');
			}else{
				$where['vod_name'][] = array('eq', $value["vod_name"]);
			}
		}
		$where['vod_name'][] = 'or';
		$tag['order'] = 'vod_name';
	}
	// 标签聚合条件
	if($tag['tag_name']){
		$where['tag_name'] = array('in',$tag['tag_name']);
	}
	if($tag['tag_ename']){
		$where['tag_ename'] = array('in',$tag['tag_ename']);
	}
	//分支加载不同的模型查询数据开始
	if($tag['tag_name'] || $tag['tag_ename']){
		if($tag['tag_cid']){
			$where['tag_cid'] = array('in',$tag['tag_cid']);
		}
		if($tag['tag_list']){
			$where['tag_list'] = array('in',$tag['tag_list']);
		}		
		$rs = D('TagvodView');
	}else{
		$rs = D('VodView');
	}
	return $rs->ff_select_page(ff_mysql_param($tag), $where);
}
// 循环标签.文章
function ff_mysql_news($tag_str){
	$tag = ff_param_lable($tag_str);
	$where = array();
	if (isset($tag['status'])) {
		$where['news_status'] = array('eq', $tag['status']);
	}
	if ($tag['list_ename']) {
		$where['list_dir'] =  array('eq',$tag['list_ename']);
	}	
	if ($tag['pic_slide'] == 'true') {
		$where['news_pic_slide'] = array('neq','');
	}else if ($tag['pic_slide'] == 'false') {
		$where['news_pic_slide'] = array('eq','');
	}
	if ($tag['pic_bg'] == 'true') {
		$where['news_pic_bg'] = array('neq','');
	}else if ($tag['pic_bg'] == 'false') {
		$where['news_pic_bg'] = array('eq','');
	}
	if ($tag['pic'] == 'true') {
		$where['news_pic'] = array('neq','');
	}else if ($tag['pic'] == 'false') {
		$where['news_pic'] = array('eq','');
	}
	if($tag['series'] == 'true'){
		$where['news_series'] = array('neq','');
	}else if($tag['series'] == 'false'){
		$where['news_series'] = array('eq','');
	}else if($tag['series']){
		$where['news_series'] = array('eq',$tag['series']);
	}
	if ($tag['inputer']) {
		$where['news_inputer'] = array('eq',$tag['inputer']);
	}
	if ($tag['ids']) {
		$where['news_id'] = array('in',$tag['ids']);
	}
	if ($tag['ids_not']) {
		$where['news_id'] = array('not in',$tag['ids_not']);
	}
	if ($tag['cid']) {
		$where['news_cid'] = array('in',$tag['cid']);
	}	
	if ($tag['cid_not']) {
		$where['news_cid'] = array('not in',$tag['cid_not']);
	}
	if ($tag['stars']) {
		$where['news_stars'] = array('in',$tag['stars']);
	}	
	if ($tag['letter'] || $tag['letter'] == '0') {
		$where['news_letter'] = array('in',$tag['letter']);
	}
	if ($tag['id_min']) {
		$where['news_id'] = array('gt',$tag['id_min']);
	}
	if ($tag['id_max']) {
		$where['news_id'] = array('lt',$tag['id_max']);
	}
	if ($tag['addtime']) {//4.1
		$where['news_addtime'] = array('gt',$tag['addtime']);
	}	
	if ($tag['upday']) {
		$where['news_addtime'] = array('gt',ff_linux_time($tag['upday']));
	}
	if (isset($tag['lastday'])) {
		$where['news_hits_lasttime'] = array('gt',ff_linux_time($tag['lastday']));
	}
	if ($tag['hits']) {
		$hits = explode(',',$tag['hits']);
		if (count($hits) > 1) {
			$where['news_hits'] = array('between',$hits[0].','.$hits[1]);
		}else{
			$where['news_hits'] = array('gt',$hits[0]);
		}
	}
	if ($tag['up']) {
		$up = explode(',',$tag['up']);
		if (count($up) > 1) {
			$where['news_up'] = array('between',$up[0].','.$up[1]);
		}else{
			$where['news_up'] = array('gt',$up[0]);
		}
	}
	if ($tag['down']) {
		$down = explode(',',$tag['down']);
		if (count($down) > 1) {
			$where['news_down'] = array('between',$down[0].','.$down[1]);
		}else{
			$where['news_down'] = array('gt',$down[0]);
		}
	}
	if ($tag['gold']) {
		$gold = explode(',',$tag['gold']);
		if (count($gold) > 1) {
			$where['news_gold'] = array('between',$gold[0].','.$gold[1]);
		}else{
			$where['news_gold'] = array('gt',$gold[0]);
		}
	}
	if ($tag['golder']) {
		$golder = explode(',',$tag['golder']);
		if (count($golder) > 1) {
			$where['news_golder'] = array('between',$golder[0].','.$golder[1]);
		}else{
			$where['news_golder'] = array('gt',$golder[0]);
		}
	}	
	if ($tag['name']) {
		$where['news_name'] = array('like','%'.$tag['name'].'%');
	}	
	if ($tag['title']) {
		$where['news_title'] = array('like','%'.$tag['title'].'%');
	}
	if ($tag['remark']) {
		$where['news_remark'] = array('like','%'.$tag['remark'].'%');
	}
	if($tag['names']){
		foreach(explode(',',$tag['names']) as $key=>$value){
			$where['news_name'][] = array('like',$value.'%');
		}
		$where['news_name'][] = 'or';
	}	
	if ($tag['wd']) {
		$search = array();
		$search['news_name'] = array('like','%'.$tag['wd'].'%');
		$search['news_remark'] = array('like','%'.$tag['wd'].'%');
		$search['_logic'] = 'or';
		$where['_complex'] = $search;
	}
	// 标签聚合条件
	if($tag['tag_name']){
		$where['tag_name'] = array('in',$tag['tag_name']);
	}
	if($tag['tag_ename']){
		$where['tag_ename'] = array('in',$tag['tag_ename']);
	}
	//分支加载不同的模型查询数据开始
	if($tag['tag_name'] || $tag['tag_ename']){
		if($tag['tag_cid']){
			$where['tag_cid'] = array('in',$tag['tag_cid']);
		}
		if($tag['tag_list']){
			$where['tag_list'] = array('in',$tag['tag_list']);
		}		
		$rs = D('TagnewsView');
	}else{
		$rs = D('NewsView');
	}
	return $rs->ff_select_page(ff_mysql_param($tag), $where);
}
// 循环标签.专题
function ff_mysql_special($tag){
	$tag = ff_param_lable($tag);
	$where = array();
	//根据参数生成查询条件
	if (isset($tag['status'])) {
		$where['special_status'] = array('eq', $tag['status']);
	}
	if ($tag['ids']) {
		$where['special_id'] = array('in',$tag['ids']);
	}
	if ($tag['ids_not']) {
		$where['special_id'] = array('not in',$tag['ids_not']);
	}	
	if ($tag['cid']) {
		$where['special_cid'] = array('in',$tag['cid']);
	}
	if ($tag['cid_not']) {
		$where['special_cid'] = array('not in',$tag['cid_not']);
	}
	if ($tag['logo'] == 'true') {
		$where['special_logo'] = array('neq','');
	}else if ($tag['logo'] == 'false') {
		$where['special_logo'] = array('eq','');
	}
	if ($tag['banner'] == 'true') {//4.1
		$where['special_banner'] = array('neq','');
	}else if ($tag['pic'] == 'false') {
		$where['special_banner'] = array('eq','');
	}	
	if ($tag['id_min']) {
		$where['special_id'] = array('gt',$tag['id_min']);
	}
	if ($tag['id_max']) {
		$where['special_id'] = array('lt',$tag['id_max']);
	}
	if ($tag['upday']) {
		$where['special_addtime'] = array('gt',ff_linux_time($tag['upday']));
	}	
	if ($tag['hits']) {
		$hits = explode(',',$tag['hits']);
		if (count($hits) > 1) {
			$where['special_hits'] = array('between',$hits[0].','.$hits[1]);
		}else{
			$where['special_hits'] = array('gt',$hits[0]);
		}
	}
	if ($tag['name']) {
		$where['special_name'] = array('like','%'.$tag['name'].'%');
	}
	if ($tag['ename']) {
		$where['special_ename'] = array('like','%'.$tag['ename'].'%');
	}
	// 标签聚合条件
	if($tag['tag_name']){
		$where['tag_name'] = array('in',$tag['tag_name']);
	}
	if($tag['tag_ename']){
		$where['tag_ename'] = array('in',$tag['tag_ename']);
	}
	//分支加载不同的模型查询数据开始
	if($tag['tag_name'] || $tag['tag_ename']){
		if($tag['tag_cid']){
			$where['tag_cid'] = array('in',$tag['tag_cid']);
		}
		if($tag['tag_list']){
			$where['tag_list'] = array('in',$tag['tag_list']);
		}		
		$rs = D('TagspecialView');
	}else{
		$rs = D('SpecialView');
	}
	return $rs->ff_select_page(ff_mysql_param($tag), $where);
}
// 循环标签.讨论
function ff_mysql_forum($tag_str){
	$tag = ff_param_lable($tag_str);
	$where = array();
	//根据参数生成查询条件
	if (isset($tag['status'])) {
		$where['forum_status'] = array('eq', $tag['status']);
	}
	if ($tag['sid']) {
		$where['forum_sid'] = array('in',$tag['sid']);
	}	
	if ($tag['sid_not']) {
		$where['forum_sid'] = array('not in',$tag['sid_not']);
	}
	if ($tag['ids']) {
		$where['forum_id'] = array('in',$tag['ids']);
	}
	if ($tag['ids_not']) {
		$where['forum_id'] = array('not in',$tag['ids_not']);
	}
	if ($tag['uid']) {
		$where['forum_uid'] = array('in',$tag['uid']);
	}
	if ($tag['uid_not']) {
		$where['forum_uid'] = array('not in',$tag['uid_not']);
	}
	if (isset($tag['cid'])) {
		$where['forum_cid'] = array('in',$tag['cid']);
	}
	if (isset($tag['cid_not'])) {
		$where['forum_cid'] = array('not in',$tag['cid_not']);
	}
	if (isset($tag['pid'])) {
		$where['forum_pid'] = array('in',$tag['pid']);
	}	
	if (isset($tag['pid_not'])) {
		$where['forum_pid'] = array('not in',$tag['pid_not']);
	}	
	if ($tag['id_min']) {
		$where['forum_id'] = array('gt',$tag['id_min']);
	}
	if ($tag['id_max']) {
		$where['forum_id'] = array('lt',$tag['id_max']);
	}
	if ($tag['upday']) {
		$where['forum_addtime'] = array('gt',ff_linux_time($tag['upday']));
	}		
	if ($tag['up']) {
		$up = explode(',',$tag['up']);
		if (count($up) > 1) {
			$where['forum_up'] = array('between',$up[0].','.$up[1]);
		}else{
			$where['forum_up'] = array('gt',$up[0]);
		}
	}
	if ($tag['down']) {
		$down = explode(',',$tag['down']);
		if (count($down) > 1) {
			$where['forum_down'] = array('between',$down[0].','.$down[1]);
		}else{
			$where['forum_down'] = array('gt',$down[0]);
		}
	}
	if ($tag['content']) {//4.1
		$where['forum_content'] = array('like','%'.$tag['content'].'%');
	}
	if ($tag['user']) {//4.1
		$where['user_name'] = array('like','%'.$tag['user'].'%');
	}
	if ($tag['ip']) {//4.1
		$where['forum_ip'] = array('like','%'.$tag['ip'].'%');
	}
	if ($tag['wd']) {
		$search = array();
		$search['forum_content'] = array('like','%'.$tag['wd'].'%');
		$search['forum_ip'] = array('like','%'.$tag['wd'].'%');
		$search['user_name'] = array('like','%'.$tag['wd'].'%');
		$search['_logic'] = 'or';
		$where['_complex'] = $search;
	}
	// 分支加载不同的视图模型查询
	if($tag['sid'] == 1){
		$viewFields = array (
			'Forum'=>array('*'),
			'User'=>array('user_id','user_name','user_face','_on'=>'Forum.forum_uid = User.user_id'),
			'Vod'=>array('vod_id','vod_cid','vod_name','vod_title','vod_ename','vod_type','vod_actor','vod_director','vod_year','vod_area','vod_language','vod_pic','vod_content','vod_continu','vod_isend','vod_total','vod_hits','vod_up','vod_down','vod_addtime','vod_play','_on'=>'Forum.forum_cid = Vod.vod_id'),
		);
	}else if($tag['sid'] == 2){
		$viewFields = array (
			'Forum'=>array('*'),
			'User'=>array('user_id','user_name','user_face','_on'=>'Forum.forum_uid = User.user_id'),
			'News'=>array('*','_on'=>'Forum.forum_cid = News.news_id'),
		);
	}else if($tag['sid'] == 3){
		$viewFields = array (
			'Forum'=>array('*'),
			'User'=>array('user_id','user_name','user_face','_on'=>'Forum.forum_uid = User.user_id'),
			'Special'=>array('*','_on'=>'Forum.forum_cid = Special.special_id'),
		);
	}else{
		$viewFields = '';
	}
	return D('ForumView')->ff_select_page(ff_mysql_param($tag), $where, $viewFields);
}
// 循环标签.用户资料
function ff_mysql_user($tag_str){
	$tag = ff_param_lable($tag_str);
	$where = array();
	if (isset($tag['status'])) {
		$where['user_status'] = array('eq', $tag['status']);
	}	
	if ($tag['ids']) {
		$where['user_id'] = array('in',$tag['ids']);
	}
	if ($tag['ids_not']) {//4.1
		$where['user_id'] = array('not in',$tag['ids']);
	}	
	if ($tag['pid']) {
		$where['user_pid'] = array('in',$tag['pid']);
	}
	if ($tag['pid_not']) {//4.1
		$where['user_pid'] = array('not in',$tag['pid']);
	}	
	if ($tag['id_min']) {
		$where['user_id'] = array('gt',$tag['id_min']);
	}
	if ($tag['id_max']) {
		$where['user_id'] = array('lt',$tag['id_max']);
	}
	if ($tag['addtime']) {
		$where['user_addtime'] = array('gt',ff_linux_time($tag['addtime']));
	}
	if ($tag['logtime']) {
		$where['user_logtime'] = array('gt',ff_linux_time($tag['logtime']));
	}	
	if ($tag['follow']) {
		$arr = explode(',',$tag['follow']);
		if (count($arr) > 1) {
			$where['user_follow'] = array('between',$arr[0].','.$arr[1]);
		}else{
			$where['user_follow'] = array('gt',$arr[0]);
		}
	}
	if ($tag['up']) {
		$up = explode(',',$tag['up']);
		if (count($up) > 1) {
			$where['user_up'] = array('between',$up[0].','.$up[1]);
		}else{
			$where['user_up'] = array('gt',$up[0]);
		}
	}
	if ($tag['down']) {
		$down = explode(',',$tag['down']);
		if (count($down) > 1) {
			$where['user_down'] = array('between',$down[0].','.$down[1]);
		}else{
			$where['user_down'] = array('gt',$down[0]);
		}
	}
	if ($tag['hits']) {
		$arr = explode(',',$tag['hits']);
		if (count($arr) > 1) {
			$where['user_hits'] = array('between',$arr[0].','.$arr[1]);
		}else{
			$where['user_hits'] = array('gt',$arr[0]);
		}
	}
	if ($tag['wd']) {
		$search = array();
		$search['user_name'] = array('like','%'.$tag['wd'].'%');
		$search['user_email'] = array('like','%'.$tag['wd'].'%');
		//$search['user_ip'] = array('like','%'.$tag['wd'].'%');
		$search['_logic'] = 'or';
		$where['_complex'] = $search;
	}
	return D('User')->ff_select_page(ff_mysql_param($tag), $where);
}
// 循环标签.订单
function ff_mysql_orders($tag_str){
	$tag = ff_param_lable($tag_str);
	$where = array();
	if (isset($tag['status'])) {
		$where['order_status'] = array('eq', $tag['status']);
	}	
	if (isset($tag['ispay'])) {
		$where['order_ispay'] = array('eq', $tag['ispay']);
	}
	if (isset($tag['shipping'])) {
		$where['order_shipping'] = array('eq', $tag['shipping']);
	}
	if ($tag['ids']) {
		$where['order_id'] = array('in',$tag['ids']);
	}
	if ($tag['uid']) {
		$where['order_uid'] = array('in',$tag['uid']);
	}
	if ($tag['gid']) {
		$where['order_gid'] = array('in',$tag['gid']);
	}
	if ($tag['addtime']) {
		$where['order_addtime'] = array('gt',ff_linux_time($tag['addtime']));
	}
	if ($tag['paytime']) {
		$where['order_paytime'] = array('gt',ff_linux_time($tag['paytime']));
	}
	if ($tag['confirmtime']) {
		$where['order_confirmtime'] = array('gt',ff_linux_time($tag['confirmtime']));
	}	
	if ($tag['money']) {
		$arr = explode(',',$tag['money']);
		if (count($arr) > 1) {
			$where['order_money'] = array('between',$arr[0].','.$arr[1]);
		}else{
			$where['order_money'] = array('gt',$arr[0]);
		}
	}
	if ($tag['wd']) {
		$search = array();
		$search['order_sign'] = array('like','%'.$tag['wd'].'%');
		$search['order_info'] = array('like','%'.$tag['wd'].'%');
		$search['_logic'] = 'or';
		$where['_complex'] = $search;
	}
	return D('OrdersView')->ff_select_page(ff_mysql_param($tag), $where);
}
// 循环标签.记录
function ff_mysql_record($tag_str){
	$tag = ff_param_lable($tag_str);
	$where = array();
	if ($tag['ids']) {
		$where['record_id'] = array('in',$tag['ids']);
	}
	if ($tag['ids_not']) {//4.1
		$where['record_id'] = array('not in',$tag['ids']);
	}	
	if ($tag['sid']) {
		$where['record_sid'] = array('in',$tag['sid']);
	}
	if ($tag['sid_not']) {//4.1
		$where['record_sid'] = array('not in',$tag['sid_not']);
	}	
	if ($tag['did']) {
		$where['record_did'] = array('in',$tag['did']);
	}
	if ($tag['did_not']) {//4.1
		$where['record_did'] = array('not in',$tag['did']);
	}	
	if ($tag['uid']) {
		$where['record_uid'] = array('in',$tag['uid']);
	}
	if ($tag['uid_not']) {//4.1
		$where['record_uid'] = array('not in',$tag['uid']);
	}		
	if ($tag['type']) {
		$where['record_type'] = array('in',$tag['type']);
	}
	if ($tag['upday']) {
		$where['record_time'] = array('gt',ff_linux_time($tag['upday']));
	}
	// 分支加载不同的关联条件
	if($tag['sid'] == 1){
		$viewFields = array (
		 'Record'=>array('*'),
		 'User'=>array('user_id','user_name','user_email','user_face','_on'=>'Record.record_uid = User.user_id'),
		 'Vod'=>array('*','_on'=>'Record.record_did = Vod.vod_id'),
		 'List'=>array('list_id','list_name','list_dir','list_skin', '_on'=>'Vod.vod_cid = List.list_id'),
		);
	}else if($tag['sid'] == 2){
		$viewFields = array (
			'Record'=>array('*'),
			'User'=>array('user_id','user_name','user_email','user_face','_on'=>'Record.record_uid = User.user_id'),
			'News'=>array('*','_on'=>'Record.record_did = News.news_id'),
			'List'=>array('list_id','list_name','list_dir','list_skin', '_on'=>'News.news_cid = List.list_id'),
		);
	}else if($tag['sid'] == 3){
		array (
			'Record'=>array('*'),
			'User'=>array('user_id','user_name','user_email','user_face','_on'=>'Record.record_uid = User.user_id'),
			'Special'=>array('*','_on'=>'Record.record_did = Special.special_id'),
			'List'=>array('list_id','list_name','list_dir','list_skin', '_on'=>'Special.special_cid = List.list_id'),
		);
	}else if($tag['sid'] == 8 || $tag['sid'] == 9){
		array (
			'Record'=>array('*'),
			'User'=>array('user_id','user_name','user_email','user_face','_on'=>'Record.record_uid = User.user_id'),
			'Person'=>array('*','_on'=>'Record.record_did = Person.person_id'),
			'List'=>array('list_id','list_name','list_dir','list_skin', '_on'=>'Person.person_cid = List.list_id'),
		);
	}else{
		$viewFields = '';
	}
	return D('RecordView')->ff_select_page(ff_mysql_param($tag), $where, $viewFields);
}
// 循环标签.积分
function ff_mysql_score($tag_str){
	$tag = ff_param_lable($tag_str);
	$where = array();
	if ($tag['ids']) {
		$where['score_id'] = array('in',$tag['ids']);
	}
	if ($tag['ids_not']) {//4.1
		$where['score_id'] = array('not in',$tag['ids']);
	}	
	if ($tag['sid']) {
		$where['score_sid'] = array('in',$tag['sid']);
	}
	if ($tag['sid_not']) {//4.1
		$where['score_sid'] = array('not in',$tag['sid_not']);
	}	
	if ($tag['did']) {
		$where['score_did'] = array('in',$tag['did']);
	}
	if ($tag['did_not']) {//4.1
		$where['score_did'] = array('not in',$tag['did']);
	}	
	if ($tag['uid']) {
		$where['score_uid'] = array('in',$tag['uid']);
	}
	if ($tag['uid_not']) {//4.1
		$where['score_uid'] = array('not in',$tag['uid']);
	}	
	if ($tag['type']) {
		$where['score_type'] = array('in',$tag['type']);
	}
	if ($tag['addtime']) {
		$where['score_addtime'] = array('gt',ff_linux_time($tag['addtime']));
	}
	// 分支加载不同的关联条件
	if($tag['sid'] == 1){
		$viewFields = array (
		 'Score'=>array('*'),
		 'User'=>array('user_id','user_name','user_face','_on'=>'Score.score_uid = User.user_id'),
		 'Vod'=>array('vod_name'=>'detail_name','vod_cid'=>'detail_cid','_on'=>'Record.record_did = Vod.vod_id'),
		);
	}else if($tag['sid'] == 2){
		$viewFields = array (
			'Score'=>array('*'),
			'User'=>array('user_id','user_name','user_face','_on'=>'Score.score_uid = User.user_id'),
			'News'=>array('new_name'=>'detail_name','news_cid'=>'detail_cid','_on'=>'Record.record_did = News.news_id'),
		);
	}else if($tag['sid'] == 3){
		array (
			'Score'=>array('*'),
			'User'=>array('user_id','user_name','user_face','_on'=>'Score.score_uid = User.user_id'),
			'Special'=>array('special_name'=>'detail_name','_on'=>'Record.record_did = Special.special_id'),
		);
	}else{
		$viewFields = '';
	}
	return D('ScoreView')->ff_select_page(ff_mysql_param($tag), $where, $viewFields);
}
// 循环标签.卡密
function ff_mysql_card($tag_str){
	$tag = ff_param_lable($tag_str);
	$where = array();
	if (isset($tag['status'])) {
		$where['card_status'] = array('eq', $tag['status']);
	}
	if ($tag['ids']) {
		$where['card_id'] = array('in',$tag['ids']);
	}
	if ($tag['ids_not']) {//4.1
		$where['card_id'] = array('not in',$tag['ids']);
	}	
	if ($tag['uid']) {
		$where['card_did'] = array('in',$tag['uid']);
	}
	if ($tag['uid_not']) {//4.1
		$where['card_did'] = array('not in',$tag['uid']);
	}	
	if ($tag['face']) {
		$where['card_face'] = array('eq',$tag['face']);
	}
	if ($tag['wd']) {
		$where['card_number'] = array('like','%'.$tag['wd'].'%');
	}
	return D('CardView')->ff_select_page(ff_mysql_param($tag), $where, false);
}
// 循环标签.明星
function ff_mysql_star($tag_str){
	$tag = ff_param_lable($tag_str);
	$tag['sid'] = '8';
	return ff_mysql_person($tag);
}
// 循环标签.角色
function ff_mysql_role($tag_str){
	$tag = ff_param_lable($tag_str);
	$tag['sid'] = '9';
	return ff_mysql_person($tag);
}
// 循环标签.人物
function ff_mysql_person($tag){
	$where = array();
	if (isset($tag['status'])) {
		$where['person_status'] = array('eq', $tag['status']);
	}
	if($tag['list_ename']){
		$where['list_dir'] =  array('eq',$tag['list_ename']);
	}	
	if ($tag['gender']) {//性别
		$where['person_gender'] = array('eq', $tag['gender']);
	}
	if ($tag['astrology']) {//星座
		$where['person_astrology'] = array('eq', $tag['astrology']);
	}
	if ($tag['pic_slide'] == 'true') {
		$where['person_pic_slide'] = array('neq','');
	}else if ($tag['pic_slide'] == 'false') {
		$where['person_pic_slide'] = array('eq','');
	}
	if ($tag['pic_bg'] == 'true') {
		$where['person_pic_bg'] = array('neq','');
	}else if ($tag['pic_bg'] == 'false') {
		$where['person_pic_bg'] = array('eq','');
	}
	if ($tag['pic']  == 'true') {
		$where['person_pic'] = array('neq','');
	}else if ($tag['pic'] == 'false') {
		$where['person_pic'] = array('eq','');
	}
	if ($tag['father_id'] == 'true') {
		$where['person_father_id'] = array('gt',0);
	}else if ($tag['father_id'] == 'false') {
		$where['person_father_id'] = array('lt',1);
	}else if ($tag['father_id']) {
		$where['person_father_id'] = array('in',$tag['father_id']);
	}
	if ($tag['object_id'] == 'true') {
		$where['person_object_id'] = array('gt',0);
	}else if ($tag['object_id'] == 'false') {
		$where['person_object_id'] = array('lt',1);
	}else if ($tag['object_id']) {
		$where['person_object_id'] = array('in',$tag['object_id']);
	}
	if ($tag['douban_id'] == 'true') {
		$where['person_douban_id'] = array('gt',0);
	}else if ($tag['douban_id'] == 'false') {
		$where['person_douban_id'] = array('lt',1);
	}else if ($tag['douban_id']) {
		$where['person_douban_id'] = array('in',$tag['douban_id']);
	}
	if($tag['names']){
		foreach(explode(',',$tag['names']) as $key=>$value){
			$where['person_name'][] = array('eq',$value);
		}
		$where['person_name'][] = 'or';
	}
	if($tag['enames']){
		foreach(explode(',',$tag['enames']) as $key=>$value){
			$where['person_ename'][] = array('eq',$value);
		}
		$where['person_ename'][] = 'or';
	}
	if($tag['father_names']){
		foreach(explode(',',$tag['father_names']) as $key=>$value){
			$where['person_father_name'][] = array('eq',$value);
		}
		$where['person_father_name'][] = 'or';
	}
	if ($tag['ids']) {
		$where['person_id'] = array('in',$tag['ids']);
	}
	if ($tag['ids_not']) {
		$where['person_id'] = array('not in',$tag['ids_not']);
	}
	if ($tag['cid']) {
		$where['person_cid'] = array('in',$tag['cid']);
	}
	if ($tag['cid_not']) {
		$where['person_cid'] = array('not in',$tag['cid_not']);
	}
	if ($tag['sid']) {
		$where['person_sid'] = array('in',$tag['sid']);
	}
	if ($tag['sid_not']) {
		$where['person_sid'] = array('not in',$tag['sid_not']);
	}	
	if ($tag['stars']) {
		$where['person_stars'] = array('in',$tag['stars']);
	}
	if ($tag['father_name']) {
		$where['person_father_name'] = array('in',$tag['father_name']);
	}	
	if ($tag['object_name']) {
		$where['person_object_name'] = array('in',$tag['object_name']);
	}
	if ($tag['douban_name']) {
		$where['person_douban_id'] = array('in',$tag['douban_name']);
	}
	if ( $tag['letter'] || $tag['letter'] == '0' ) {
		$where['person_letter'] = array('in',$tag['letter']);
	}
	if ( isset($tag['upday']) ) {
		$where['person_addtime'] = array('gt',ff_linux_time($tag['day']));
	}
	if ( isset($tag['lastday']) ) {
		$where['person_hits_lasttime'] = array('gt',ff_linux_time($tag['lastday']));
	}
	if ($tag['id_min']) {
		$where['person_id'] = array('gt',$tag['id_min']);
	}
	if ($tag['id_max']) {
		$where['person_id'] = array('lt',$tag['id_max']);
	}
	if ($tag['addtime']) {
		$where['person_addtime'] = array('gt',$tag['addtime']);
	}	
	if ($tag['hits']) {
		$hits = explode(',',$tag['hits']);
		if (count($hits) > 1) {
			$where['person_hits'] = array('between',$hits[0].','.$hits[1]);
		}else{
			$where['person_hits'] = array('gt',$hits[0]);
		}
	}
	if ($tag['up']) {
		$up = explode(',',$tag['up']);
		if (count($up) > 1) {
			$where['person_up'] = array('between',$up[0].','.$up[1]);
		}else{
			$where['person_up'] = array('gt',$up[0]);
		}
	}
	if ($tag['down']) {
		$down = explode(',',$tag['down']);
		if (count($down) > 1) {
			$where['person_down'] = array('between',$down[0].','.$down[1]);
		}else{
			$where['person_down'] = array('gt',$down[0]);
		}
	}
	if ($tag['gold']) {
		$gold = explode(',',$tag['gold']);
		if (count($gold) > 1) {
			$where['person_gold'] = array('between',$gold[0].','.$gold[1]);
		}else{
			$where['person_gold'] = array('gt',$gold[0]);
		}
	}
	if ($tag['golder']) {
		$golder = explode(',',$tag['golder']);
		if (count($golder) > 1) {
			$where['person_golder'] = array('between',$golder[0].','.$golder[1]);
		}else{
			$where['person_golder'] = array('gt',$golder[0]);
		}
	}
	if ($tag['wd']) {
		$search = array();
		$search['person_name'] = array('like','%'.$tag['wd'].'%');
		$search['person_alias'] = array('like','%'.$tag['wd'].'%');
		$search['_logic'] = 'or';
		$where['_complex'] = $search;
	}
	if ($tag['name']) {
		$where['person_name'] = array('like','%'.$tag['name'].'%');
	}
	if ($tag['alias']) {//别名
		$where['person_alias'] = array('like','%'.$tag['title'].'%');
	}
	if ($tag['ename']) {//自定义连接
		$where['person_ename'] = array('like',$tag['ename']);
	}
	if ($tag['school']) {//毕业学校
		$where['person_school'] = array('like',$tag['school']);
	}
	if ($tag['broker']) {//经纪公司
		$where['person_broker'] = array('like',$tag['broker']);
	}		
	if ($tag['intro']) {//简介
		$where['person_intro'] = array('like','%'.$tag['intro'].'%');
	}
	if ($tag['achievement']) {//主要成就
		$where['person_achievement'] = array('like','%'.$tag['achievement'].'%');
	}
	if ($tag['content']) {//详细介绍
		$where['person_content'] = array('like','%'.$tag['content'].'%');
	}
	if($tag['nationality']){//籍贯
		foreach(explode(',',$tag['nationality']) as $key=>$value){
			$where['person_nationality'][] = array('like','%'.$value.'%');
		}
		$where['person_nationality'][] = 'or';
	}
	if($tag['profession']){//职业
		foreach(explode(',',$tag['profession']) as $key=>$value){
			$where['person_profession'][] = array('like','%'.$value.'%');
		}
		$where['person_profession'][] = 'or';
	}
	return D('PersonView')->ff_select_page(ff_mysql_param($tag), $where, $viewFields);
}
// 循环标签.演员表
function ff_mysql_yanyuan($tag){
	$tag = ff_param_lable($tag);
	$where = array();
	if (isset($tag['status'])) {
		$where['role.person_status'] = array('eq', $tag['status']);
	}
	if ($tag['cid']) {
		$where['role.person_cid'] = array('in',$tag['cid']);
	}
	if ($tag['cid_not']) {
		$where['role.person_cid'] = array('not in',$tag['cid_not']);
	}
	if ($tag['ids']) {
		$where['role.person_id'] = array('in',$tag['ids']);
	}
	if ($tag['ids_not']) {
		$where['role.person_id'] = array('not in',$tag['ids_not']);
	}
	if ($tag['id_min']) {
		$where['role.person_id'] = array('gt',$tag['id_min']);
	}
	if ($tag['id_max']) {
		$where['role.person_id'] = array('lt',$tag['id_max']);
	}	
	if ($tag['father_id']  == 'true') {
		$where['role.person_father_id'] = array('gt',0);
	}else if ($tag['father_id'] == 'false') {
		$where['role.person_father_id'] = array('eq',0);
	}	
	return D('Yanyuan')->ff_select_page(ff_mysql_param($tag), $where);
}
// 循环标签.视频对应人物关系（视频->角色->明星）
function ff_mysql_vod_person($tag){
	$tag = ff_param_lable($tag);
	$where = array();
	$where['role.person_sid'] = array('eq',9);
	if (isset($tag['status'])) {
		$where['role.person_status'] = array('eq', $tag['status']);
	}
	if ($tag['ids']) {
		$where['role.person_object_id'] = array('in', $tag['ids']);
	}
	if ($tag['id_min']) {
		$where['role.person_object_id'] = array('gt',$tag['id_min']);
	}
	if ($tag['id_max']) {
		$where['role.person_object_id'] = array('lt',$tag['id_max']);
	}
	return D('Person')->ff_select_join(ff_mysql_param($tag), $where);
}
/*---------------------------------------ThinkPhp扩展函数库开始------------------------------------------------------------------
 * @category   Think
 * @package  Common
 * @author   liu21st <liu21st@gmail.com>*/
// 获取客户端IP地址
function get_client_ip(){
   if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
       $ip = getenv("HTTP_CLIENT_IP");
   else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
       $ip = getenv("HTTP_X_FORWARDED_FOR");
   else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
       $ip = getenv("REMOTE_ADDR");
   else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
       $ip = $_SERVER['REMOTE_ADDR'];
   else
       $ip = "unknown";
   return htmlspecialchars($ip, ENT_QUOTES);
}
//输出安全的html
function h($text, $tags = null){
	$text	=	trim($text);
	//完全过滤注释
	$text	=	preg_replace('/<!--?.*-->/','',$text);
	//完全过滤动态代码
	$text	=	preg_replace('/<\?|\?'.'>/','',$text);
	//完全过滤js
	$text	=	preg_replace('/<script?.*\/script>/','',$text);

	$text	=	str_replace('[','&#091;',$text);
	$text	=	str_replace(']','&#093;',$text);
	$text	=	str_replace('|','&#124;',$text);
	//过滤换行符
	$text	=	preg_replace('/\r?\n/','',$text);
	//br
	$text	=	preg_replace('/<br(\s\/)?'.'>/i','[br]',$text);
	$text	=	preg_replace('/(\[br\]\s*){10,}/i','[br]',$text);
	//过滤危险的属性，如：过滤on事件lang js
	while(preg_match('/(<[^><]+)( lang|on|action|background|codebase|dynsrc|lowsrc)[^><]+/i',$text,$mat)){
		$text=str_replace($mat[0],$mat[1],$text);
	}
	while(preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i',$text,$mat)){
		$text=str_replace($mat[0],$mat[1].$mat[3],$text);
	}
	if(empty($tags)) {
		$tags = 'table|td|th|tr|i|b|u|strong|img|p|br|div|strong|em|ul|ol|li|dl|dd|dt|a';
	}
	//允许的HTML标签
	$text	=	preg_replace('/<('.$tags.')( [^><\[\]]*)>/i','[\1\2]',$text);
	//过滤多余html
	$text	=	preg_replace('/<\/?(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|script|style|xml)[^><]*>/i','',$text);
	//过滤合法的html标签
	while(preg_match('/<([a-z]+)[^><\[\]]*>[^><]*<\/\1>/i',$text,$mat)){
		$text=str_replace($mat[0],str_replace('>',']',str_replace('<','[',$mat[0])),$text);
	}
	//转换引号
	while(preg_match('/(\[[^\[\]]*=\s*)(\"|\')([^\2=\[\]]+)\2([^\[\]]*\])/i',$text,$mat)){
		$text=str_replace($mat[0],$mat[1].'|'.$mat[3].'|'.$mat[4],$text);
	}
	//过滤错误的单个引号
	while(preg_match('/\[[^\[\]]*(\"|\')[^\[\]]*\]/i',$text,$mat)){
		$text=str_replace($mat[0],str_replace($mat[1],'',$mat[0]),$text);
	}
	//转换其它所有不合法的 < >
	$text	=	str_replace('<','&lt;',$text);
	$text	=	str_replace('>','&gt;',$text);
	$text	=	str_replace('"','&quot;',$text);
	 //反转换
	$text	=	str_replace('[','<',$text);
	$text	=	str_replace(']','>',$text);
	$text	=	str_replace('|','"',$text);
	//过滤多余空格
	$text	=	str_replace('  ',' ',$text);
	return $text;
}
//XSS漏洞过滤
function remove_xss($val) {
   $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
   $search = 'abcdefghijklmnopqrstuvwxyz';
   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $search .= '1234567890!@#$%^&*()';
   $search .= '~`";:?+/={}[]-_|\'\\';
   for ($i = 0; $i < strlen($search); $i++) {
      $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
      $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
   }
   $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
   $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
   $ra = array_merge($ra1, $ra2);
   $found = true; // keep replacing as long as the previous round replaced something
   while ($found == true) {
      $val_before = $val;
      for ($i = 0; $i < sizeof($ra); $i++) {
         $pattern = '/';
         for ($j = 0; $j < strlen($ra[$i]); $j++) {
            if ($j > 0) {
               $pattern .= '(';
               $pattern .= '(&#[xX]0{0,8}([9ab]);)';
               $pattern .= '|';
               $pattern .= '|(&#0{0,8}([9|10|13]);)';
               $pattern .= ')*';
            }
            $pattern .= $ra[$i][$j];
         }
         $pattern .= '/i';
         $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
         $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
         if ($val_before == $val) {
            // no replacements were made, so exit the loop
            $found = false;
         }
      }
   }
   return $val;
}
/*** 把返回的数据集转换成Tree
 +----------------------------------------------------------
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array 
 */
function list_to_tree($list, $pk='id',$pid = 'pid',$child = '_child',$root=0)
{
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}
/**----------------------------------------------------------
 * 在数据列表中搜索
 +----------------------------------------------------------
 * @param array $list 数据列表
 * @param mixed $condition 查询条件
 * 支持 array('name'=>$value) 或者 name=$value
 * @return array
 */
function list_search($list,$condition) {
    if(is_string($condition))
        parse_str($condition,$condition);
    // 返回的结果集合
    $resultSet = array();
    foreach ($list as $key=>$data){
        $find   =   false;
        foreach ($condition as $field=>$value){
            if(isset($data[$field])) {
                if(0 === strpos($value,'/')) {
                    $find   =   preg_match($value,$data[$field]);
                }elseif($data[$field]==$value){
                    $find = true;
                }
            }
        }
        if($find)
            $resultSet[]     =   &$list[$key];
    }
    return $resultSet;
}
/**
 +----------------------------------------------------------
 * 字节格式化 把字节数格式为 B K M G T 描述的大小
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function byte_format($size, $dec=2)
{
	$a = array("B", "KB", "MB", "GB", "TB", "PB");
	$pos = 0;
	while ($size >= 1024) {
		 $size /= 1024;
		   $pos++;
	}
	return round($size,$dec)." ".$a[$pos];
}
/**
 +----------------------------------------------------------
 * 对查询结果集进行排序
 +----------------------------------------------------------
 * @access public
 +----------------------------------------------------------
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 +----------------------------------------------------------
 * @return array
 +----------------------------------------------------------
 */
function list_sort_by($list,$field, $sortby='asc') {
   if(is_array($list)){
       $refer = $resultSet = array();
       foreach ($list as $i => $data)
           $refer[$i] = &$data[$field];
       switch ($sortby) {
           case 'asc': // 正向排序
                asort($refer);
                break;
           case 'desc':// 逆向排序
                arsort($refer);
                break;
           case 'nat': // 自然排序
                natcasesort($refer);
                break;
       }
       foreach ( $refer as $key=> $val)
           $resultSet[] = &$list[$key];
       return $resultSet;
   }
   return false;
}
?>