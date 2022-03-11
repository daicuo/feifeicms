<?php
class InstallAction extends Action{	
  public function _initialize() {
		if(is_file('./Runtime/Install/install.lock')){
			$this->assign("waitSecond",60);
			$this->error('Sorry，您已经安装了飞飞PHP影视系统 V'.C('ffvod_version').' 版<br />重新安装请先删除 Runtime/install/install.lock 文件。');
		}
  }
  public function index(){	
  	$this->display('./Public/system/install.html');
  }
  public function second(){	
		$this->display('./Public/system/install.html');
  }
  public function setup(){
		$this->display('./Public/system/install.html');
  }
  public function install(){
		$data = $_POST['data'];
		$rs = @mysql_connect($data['db_host'].":".intval($data['db_port']),$data['db_user'],$data['db_pwd']);
		if(!$rs){
			$this->error(L('install_db_connect'));	
		}
		// 数据库不存在,尝试建立
		if(!@mysql_select_db($data['db_name'])){
			$sql = "CREATE DATABASE `".$data["db_name"]."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
			mysql_query($sql);
		}
		// 建立不成功
		if(!@mysql_select_db($data['db_name'])){
			$this->error(L('install_db_select'));
		}
		// 保存配置文件
		$config = array(
		  'site_path'=>$data['site_path'],
			'db_host'=>$data['db_host'],
			'db_name'=>$data['db_name'],
			'db_user'=>$data['db_user'], 
			'db_pwd'=>$data['db_pwd'],
			'db_port'=>$data['db_port'],
			'db_prefix'=>$data['db_prefix'],
		);
		$config_old = require './Runtime/Conf/config.php';
		$config_new = array_merge($config_old,$config);
		arr2file('./Runtime/Conf/config.php', $config_new);
		// 批量导入安装SQL
		$db_config = array(
			'dbms'=>'mysql',
			'username'=>$data['db_user'],
			'password'=>$data['db_pwd'],
			'hostname'=>$data['db_host'],
			'hostport'=>$data['db_port'],
			'database'=>$data['db_name']
		);	
		$sql = read_file('./Runtime/Install/install.sql');
		$sql = str_replace('ff_',$data['db_prefix'],$sql);
		$this->batQuery($sql,$db_config);
		touch('./Runtime/Install/install.lock');
		@unlink('./Runtime/~app.php');
		@unlink('./Runtime/~runtime.php');
		$this->assign("jumpUrl",'./admin.php');
		$this->success(L('install_success'));
  }
	public function batQuery($sql,$db_config){
	    // 连接数据库
		require THINK_PATH.'/Lib/Think/Db/Driver/DbMysql.class.php';
		$db = new Dbmysql($db_config);
		$sql = str_replace("\r\n", "\n", $sql); 
		$ret = array(); 
		$num = 0; 
		foreach(explode(";\n", trim($sql)) as $query){
			$queries = explode("\n", trim($query)); 
			foreach($queries as $query) { 
				$ret[$num] .= $query[0] == '#' || $query[0].$query[1] == '--' ? '' : $query; 
			} 
			$num++; 
		} 
		unset($sql); 
		foreach($ret as $query) {  
			if(trim($query)) { 
			    $db->query($query); 
			} 
		} 
		return true; 
	}								
}
?>