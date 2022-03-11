<?php
//生成对应的路由规则及路由反向URL规则
function ff_url_create($rewrite_route){
	$rewrite_route = htmlspecialchars($rewrite_route);
	$array_rule = explode("\r\n",$rewrite_route);
	$create = array();
	foreach($array_rule as $key=>$value){
		$array = explode('===', $value);
		//生成每一行的路由规则
		$routes = ff_url_routes($array[0], $array[1], $array[2]);
		//生成每一行的反向URL规则
		$action = $routes[1];
		if($routes[2]){//路由对应变量 如forum-vod-p-1-s-2
			$action.= '/'.str_replace(',','/',$routes[2]);
		}
		//附加参数加上保存一一对应 'vod/showid=1' 'vod/showid=2'
		if($routes[3]){
			$action.= $routes[3];
		}
		//拼装二维数组
		$create['route_rules'][$key] = $routes;
		$create['rewrite_rules'][$action]['find'] = str_replace(array('(:num)','(:letter)','(:letternum)','(:any)'), array('([0-9]+)','([A-Za-z]+)','([A-Za-z0-9]+)','(.*)'), $array[0]);
		$create['rewrite_rules'][$action]['replace'] = ff_url_replace($array[1]);
	}
	return $create;
}
// 三个===隔开的地址格式生成对应的路由反向链接替换规则
function ff_url_replace($url_rule){
	preg_match_all('/\(:num\)|\(:letter\)|\(:letternum\)|\(:any\)/', $url_rule, $array);
	foreach($array[0] as $key=>$value){
		$value = preg_quote($value);
		$url_rule = preg_replace('/'.$value.'/','\$'.($key+1), $url_rule, 1);
	}
	return $url_rule;
}
// 三个===隔开的地址格式生成对应的路由定义规则 preg_quote($value);
function ff_url_routes($action, $routes, $params){
	//news-read-id-(\d+)-p-(\d+).html===news/(\d+)-(\d+).html
	$array = explode('-', $action);
	$count = count($array);
	for ($i=1; $i<$count; ++$i){
		if(isset($array[++$i])){
			$ids[] = $array[$i];
		}
	}
	$routes = str_replace('/','\/',$routes);//TP路由需要将/转义
	$routes = str_replace(array('(:num)','(:letter)','(:letternum)','(:any)'), array('([0-9]+)','([A-Za-z]+)','([A-Za-z0-9]+)','(.*)'), $routes);//转化为正则规则
	return array( '/'.$routes.'$/', $array[0].'/'.$array[1], implode(',',$ids), $params);
}
//星级转化数组
function admin_star_arr($stars){
	for ($i=1; $i<=5; $i++) {
		if ($i <= $stars){
			$ss[$i]=1;
		}else{
			$ss[$i]=0;
		}
	}
	return $ss;
}
// 安装测试写入文件
function testwrite($d){
	$tfile = '_feifeicms.txt';
	$d = ereg_replace('/$','',$d);
	$fp = @fopen($d.'/'.$tfile,'w');
	if(!$fp){
		return false;
	}else{
		fclose($fp);
		$rs = @unlink($d.'/'.$tfile);
		if($rs){
			return true;
		}else{
			return false;
		}
	}
}
// 获取文件夹大小
function getdirsize($dir){ 
	$dirlist = opendir($dir);
	while (false !==  ($folderorfile = readdir($dirlist))){ 
		if($folderorfile != "." && $folderorfile != "..") { 
			if (is_dir("$dir/$folderorfile")) { 
				$dirsize += getdirsize("$dir/$folderorfile"); 
			}else{ 
				$dirsize += filesize("$dir/$folderorfile"); 
			}
		}    
	}
	closedir($dirlist);
	return $dirsize;
}
//通过标签分类返回对应的模块
function admin_ff_taglist2modelname($tag_list){
	if( in_array($tag_list, array('vod_tag','vod_type')) ){
	    return 'Vod';
	}elseif( in_array($tag_list, array('news_tag','news_type')) ){
	    return 'News';
	}
}
//分页样式
function getpage($currentPage,$totalPages,$halfPer=5,$url,$pagego){
    $linkPage .= ( $currentPage > 1 )
        ? '<a href="'.str_replace('FFLINK',1,$url).'" class="pagegbk">首页</a>&nbsp;<a href="'.str_replace('FFLINK',($currentPage-1),$url).'" class="pagegbk">上一页</a>&nbsp;' 
        : '<em>首页</em>&nbsp;<em>上一页</em>&nbsp;';
    for($i=$currentPage-$halfPer,$i>1||$i=1,$j=$currentPage+$halfPer,$j<$totalPages||$j=$totalPages;$i<$j+1;$i++){
        $linkPage .= ($i==$currentPage)?'<span>'.$i.'</span>&nbsp;':'<a href="'.str_replace('FFLINK',$i,$url).'">'.$i.'</a>&nbsp;'; 
    }
    $linkPage .= ( $currentPage < $totalPages )
        ? '<a href="'.str_replace('FFLINK',($currentPage+1),$url).'" class="pagegbk">下一页</a>&nbsp;<a href="'.str_replace('FFLINK',$totalPages,$url).'" class="pagegbk">尾页</a>'
        : '<em>下一页</em>&nbsp;<em>尾页</em>';
	if(!empty($pagego)){
		$linkPage .='&nbsp;<input type="input" name="page" id="page" size=4 class="pagego"/><input type="button" value="跳 转" onclick="'.$pagego.'" class="pagebtn" />';
	}
	//
	if(C('url_html') && C('url_html_list')){
    	return str_replace('-1'.C('html_file_suffix'),C('html_file_suffix'),str_replace('index1'.C('html_file_suffix'),'',$linkPage));
	}else{
		return $linkPage;
	}
}
// 获取数据库表名描述
function ff_table_name($tablename){
	if (strpos($tablename,'ads')>0){
		return '广告';
	}
	if (strpos($tablename,'news')>0){
		return '文章';
	}
	if (strpos($tablename,'vod')>0){
		return '视频';
	}
	if (strpos($tablename,'list')>0){
		return '栏目';
	}
	if (strpos($tablename,'forum')>0){
		return '评论';
	}
	if (strpos($tablename,'admin')>0){
		return '管理员';
	}
	if (strpos($tablename,'special')>0){
		return '专题';
	}
	if (strpos($tablename,'user')>0){
		return '用户';
	}
	if (strpos($tablename,'slide')>0){
		return '首页轮播';
	}	
	if (strpos($tablename,'link')>0){
		return '友情链接';
	}
	if (strpos($tablename,'cj')>0){
		return '采集';
	}	
	if (strpos($tablename,'tag')>0){
		return '标签';
	}
	if (strpos($tablename,'nav')>0){
		return '导航';
	}	
	if (strpos($tablename,'player')>0){
		return '播放器';
	}
	if (strpos($tablename,'record')>0){
		return '操作日志';
	}		
	if (strpos($tablename,'score')>0){
		return '积分日志';
	}	
	if (strpos($tablename,'orders')>0){
		return '定单';
	}
	if (strpos($tablename,'card')>0){
		return '卡密';
	}							
}
//获取模板编辑名称
function ff_tpl_name($filename){
	if('footer.tpl' == $filename){
	    return '底部公用模板';
	}elseif('header.tpl' == $filename){
	    return '顶部公用模板';
	}elseif('index.tpl' == $filename){
	    return '网站首页模板';
	}elseif('news_detail.tpl' == $filename){
	    return '文章内容模板';
	}elseif('news_channel.tpl' == $filename){
	    return '文章频道列表模板';
	}elseif('news_list.tpl' == $filename){
	    return '文章列表页模板';
	}elseif('news_search.tpl' == $filename){
	    return '文章搜索模板';
	}elseif('news_tags.tpl' == $filename){
	    return '文章标签模板';
	}elseif('news_type.tpl' == $filename){
	    return '文章筛选模板';
	}elseif('vod_play.tpl' == $filename){
	    return '播放页模板';
	}elseif('vod_detail.tpl' == $filename){
	    return '视频内容模板';
	}elseif('vod_list.tpl' == $filename){
	    return '视频列表页模板';
	}elseif('vod_channel.tpl' == $filename){
	    return '视频频道列表模板';
	}elseif('vod_search.tpl' == $filename){
	    return '视频搜索模板';
	}elseif('vod_tags.tpl' == $filename){
	    return '视频标签模板';
	}elseif('vod_type.tpl' == $filename){
	    return '视频筛选模板';
	}elseif('vod_rss.tpl' == $filename){
	    return '视频RSS模板';
	}elseif('special_list.tpl' == $filename){
	    return '专题列表页模板';
	}elseif('special_detail.tpl' == $filename){
	    return '专题详请页模板';
	}elseif('comment.tpl' == $filename){
	    return '评论模板';
	}elseif('guestbook.tpl' == $filename){
	    return '留言模板';
	}elseif('system.css' == $filename){
	    return '模板主题样式表';
	}elseif('system.js' == $filename){
	    return 'Javascript文件';
	}else{
		if(stristr($filename,'my_')){
	   	return '自定义模板';
		}elseif(stristr($filename,'map_')){
	   	return '地图页模板';
		}elseif(stristr($filename,'block_')){
	   	return '区块标签';
		}elseif(stristr($filename,'wap_')){
	    return '移动模块模板';
		}else{
	    return '未知文件';
		}
	}
}
?>