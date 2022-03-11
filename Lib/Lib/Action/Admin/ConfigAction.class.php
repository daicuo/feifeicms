<?php
class ConfigAction extends BaseAction{
	
	private $config;
	
	public function _initialize(){
	  parent::_initialize();
		$this->config = require './Runtime/Conf/config.php';
  }
	
	public function base(){
		$tpl = TMPL_PATH.'*';
		$list = glob($tpl);
		foreach ($list as $i=>$file){
			if( !in_array(basename($file) , array('base','base_m')) ){
				$dir[$i]['filename'] = basename($file);
			}
		}
		$this->assign('dir',$dir);
		$this->assign($this->config);
		$this->display('./Public/system/config_base.html');
	}
	
	public function _empty($action){
		$this->assign($this->config);
		$this->display('./Public/system/config_'.$action.'.html');
	}
	
	public function update(){
		$config = $_POST["config"];
		$type = $_GET['type'];
		if($type == 'base'){
			$config['site_tongji'] = stripslashes($config['site_tongji']);
			//子域名配置规则
			if($config['site_domain_m']){
				//子域名规则
				$domain = substr($config['site_domain_m'],0,strpos($config['site_domain_m'], '.'));
				$config['app_sub_domain_deploy'] = 1;
				$config['app_sub_domain_rules'] = array(
					$domain  => array('Home/','theme='.$config['default_theme_m']),
				);
			}else{
				$config['app_sub_domain_deploy'] = 0;
				$config['app_sub_domain_rules'] = '';
			}
			//是否删除首页index.html
			if(0 == $config['url_html']){
				@unlink('./index'.C('html_file_suffix'));//动态模式则删除首页静态文件
			}else{
				$config['html_home_suffix'] = $config['html_file_suffix'];//将静态后缀写入配置供前台生成的路径的时候调用
			}
			//搜索联想开关
			$config['ui_search_limit'] = intval($config['ui_search_limit']);
		}elseif($type == 'model'){
			$config['play_second'] = intval($config['play_second']);
			foreach(explode(chr(13),trim($config["play_server"])) as $v){
				list($key,$val) = explode('$$$',trim($v));
				$arrserver[trim($key)] = trim($val);
			}
			$config["play_server"] = $arrserver;
		}elseif($type == 'rewrite'){
			//路由规则定义及路由规则反向URL
			if($config['rewrite_route']){
				$routes_urls = ff_url_create(trim($config['rewrite_route']));
				$config['url_rewrite_rules'] = $routes_urls['rewrite_rules'];
				$config['url_route_rules'] = $routes_urls['route_rules'];
			}else{
				$config['url_rewrite_rules'] = '';
				$config['url_route_rules'] = '';
				$config['url_router_on'] = 0;
			}
		}elseif($type == 'cache'){
			$config['tmpl_cache_on'] = (bool) $config['tmpl_cache_on'];
			$config['html_cache_on'] = (bool) $config['html_cache_on'];	
			//静态网页缓存
			$config['html_cache_time'] = $config['html_cache_time']*3600;//其它页缓存
			if($config['html_cache_index'] > 0){
				$config['_htmls_']['home:index:index'] = array('{:action}',$config['html_cache_index']*3600);
			}else{
				$config['_htmls_']['home:index:index'] = NULL;
			}
			if($config['html_cache_type'] > 0){
				$config['_htmls_']['home:vod:type'] = array('{:module}_{:action}/{$_SERVER.REQUEST_URI|md5}',$config['html_cache_type']*3600);
				$config['_htmls_']['home:news:type'] = array('{:module}_{:action}/{$_SERVER.REQUEST_URI|md5}',$config['html_cache_type']*3600);
			}else{
				$config['_htmls_']['home:vod:type'] = NULL;
				$config['_htmls_']['home:news:type'] = NULL;
			}
			if($config['html_cache_list'] > 0){
				$config['_htmls_']['home:vod:show'] = array('{:module}_{:action}/{$_SERVER.REQUEST_URI|md5}',$config['html_cache_list']*3600);
				$config['_htmls_']['home:news:show'] = array('{:module}_{:action}/{$_SERVER.REQUEST_URI|md5}',$config['html_cache_list']*3600);
			}else{
					$config['_htmls_']['home:vod:show'] = NULL;
				$config['_htmls_']['home:news:show'] = NULL;
			}
			if($config['html_cache_content'] > 0){
					$config['_htmls_']['home:vod:read'] = array('{:module}_{:action}/{id|md5}',$config['html_cache_content']*3600);
				$config['_htmls_']['home:news:read'] = array('{:module}_{:action}/{$_SERVER.REQUEST_URI|md5}',$config['html_cache_content']*3600);
			}else{
					$config['_htmls_']['home:vod:read'] = NULL;
				$config['_htmls_']['home:news:read'] = NULL;
			}
			if($config['html_cache_play'] > 0){
					$config['_htmls_']['home:vod:play'] = array('{:module}_{:action}/{$_SERVER.REQUEST_URI|md5}',$config['html_cache_play']*3600);
			}else{
					$config['_htmls_']['home:vod:play'] = NULL;
			}						
			if($config['html_cache_ajax'] > 0){
					$config['_htmls_']['home:my:show'] = array('{:module}_{:action}/{$_SERVER.REQUEST_URI|md5}',$config['html_cache_ajax']*3600);
			}else{
					$config['_htmls_']['home:my:show'] = NULL;
			}
		}elseif($type == 'caiji'){
			$config['collect_original'] = (bool) $config['collect_original'];//伪原创
		}elseif($type == 'file'){
			$config['upload_water'] = (bool) $config['upload_water'];
			$config['upload_http'] = (bool) $config['upload_http'];
			$config['upload_ftp'] = (bool) $config['upload_ftp'];	
			$config['upload_thumb'] = intval($config['upload_thumb']);	
		}elseif($type == 'user'){
			
		}elseif($type == 'email'){
			
		}elseif($type == 'pay'){
			
		}elseif($type == 'weixin'){
			
		}elseif($type == 'wxkey'){
			foreach($config['wx_item']['keyword'] as $key=>$value){
				if(!$value){
					unset($config['wx_item']['keyword'][$key]);
					unset($config['wx_item']['title'][$key]);
					unset($config['wx_item']['content'][$key]);
					unset($config['wx_item']['pic'][$key]);
					unset($config['wx_item']['link'][$key]);
				}
			}
		}
		$config_new = array_merge($this->config, $config);
		arr2file('./Runtime/Conf/config.php',$config_new);
		@unlink('./Runtime/~app.php');
		$this->success('恭喜您，配置信息更新成功！');
		//cookie('think_template',NULL); //TMPL_PATH.TEMPLATE_NAME
	}
}
?>