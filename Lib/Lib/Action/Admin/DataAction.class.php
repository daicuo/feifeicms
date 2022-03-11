<?php
class DataAction extends BaseAction{
	// 数据库展示	
    public function show(){
		//$rs = new Model();
		if($_GET['type'] == 'repair'){
			$list = M()->query("select table_name,data_free,table_rows,data_length,index_length from information_schema.tables where table_schema='".C('db_name')."'");
			$this->assign('list_table', $list);
			$this->display('./Public/system/data_repair.html');
		}else{
			$list = M()->query('SHOW TABLES FROM '.C('db_name'));
			$tablearr = array();
			foreach ($list as $key => $val) {
				$tablearr[$key] = current($val);
			}
			$this->assign('list_table',$tablearr);
			$this->display('./Public/system/data_show.html');
		}
    }
	//数据表的修复与优化
	public function repair(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要操作的数据表！');
		}
		$tables = implode(',',$_POST['ids']);
		M()->query("repair table ".$tables);
		M()->query("optimize table ".$tables);
		$this->success('操作完成！');
	}
	//处理数据库备份
	public function insert(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要备份的数据库表！');
		}	
		$filesize = intval($_POST['filesize']);
		if ($filesize < 512) {
			$this->error('出错了,请为分卷大小设置一个大于512的整数值！');
		}
		$file = DATA_PATH.'_bak/';
		$random = md5(mt_rand(10000, 99999));
		$sql = ''; 
		$p = 1;
		foreach($_POST['ids'] as $table){
			$rs = D(ucfirst(str_replace(C('db_prefix'),'',$table)));
			$array = $rs->select();
			$sql.= "TRUNCATE TABLE `$table`;\n";
			foreach($array as $value){
				$sql.= $this->insertsql($table, $value);
				if (strlen($sql) >= $filesize*1000) {
					$filename = $file.date('Ymd').'_'.$random.'_'.$p.'.sql';
					write_file($filename,$sql);
					$p++;
					$sql='';
				}
			}
		}
		if(!empty($sql)){
			$filename = $file.date('Ymd').'_'.$random.'_'.$p.'.sql';
			write_file($filename,$sql);
		}
		$this->assign("jumpUrl",'?s=Admin-Data-Show');
		$this->success('数据库分卷备份已完成,共分成'.$p.'个sql文件存放！');
    }
	//生成SQL备份语句
	public function insertsql($table, $row){
		$sql = "INSERT INTO `{$table}` VALUES ("; 
		$values = array(); 
		foreach ($row as $value) { 
			$values[] = "'" . mysql_real_escape_string($value) . "'"; 
		} 
		$sql .= implode(', ', $values) . ");\n"; 
		return $sql;
	}
	//展示还原
    public function restore(){
		$filepath = DATA_PATH.'_bak/*.sql';
		$filearr = glob($filepath);
		if (!empty($filearr)) {
			foreach($filearr as $k=>$sqlfile){
				preg_match("/([0-9]{8}_[0-9a-z]{32}_)([0-9]+)\.sql/i",basename($sqlfile),$num);
				$restore[$k]['filename'] = basename($sqlfile);
				$restore[$k]['filesize'] = round(filesize($sqlfile)/(1024*1024), 2);
				$restore[$k]['maketime'] = date('Y-m-d H:i:s', filemtime($sqlfile));
				$restore[$k]['pre'] = $num[1];
				$restore[$k]['number'] = $num[2];
				$restore[$k]['path'] = DATA_PATH.'_bak/';
			}
			$this->assign('list_restore',$restore);
            $this->display('./Public/system/data_restore.html');
		}else{
			$this->assign("jumpUrl",'?s=Admin-Data-Show');
			$this->error('没有检测到备份文件,请先备份或上传备份文件到'.DATA_PATH.'_bak/');
		}
    }
	//导入还原
	public function back(){
		$rs = new Model();
		$pre = $_GET['id'];
		$fileid = $_GET['fileid'] ? intval($_GET['fileid']) : 1;
		$filename = $pre.$fileid.'.sql';
		$filepath = DATA_PATH.'_bak/'.$filename;
		if(file_exists($filepath)){
			$sql = read_file($filepath);
			$sql = str_replace("\r\n", "\n", $sql); 
			foreach(explode(";\n", trim($sql)) as $query) {
				$rs->query(trim($query));
			}
			$this->assign("jumpUrl",'?s=Admin-Data-Back-id-'.$pre.'-fileid-'.($fileid+1).'');
			$this->success('第'.$fileid.'个备份文件恢复成功,准备恢复下一个,请稍等！');
		}else{
			$this->assign("jumpUrl",'?s=Admin-Data-Show');
			$this->success('数据库恢复成功！');
		}
		
	}
    //文件名过滤
    private function file_id($file_name){
        return str_replace(array('..','/'), '', urldecode(trim($file_name)));
    }
	//下载还原
	public function down(){
        $filepath = DATA_PATH.'_bak/'.$this->file_id($_GET['id']);
		if (file_exists($filepath)) {
			$filename = $filename ? $filename : basename($filepath);
			$filetype = trim(substr(strrchr($filename, '.'), 1));
			$filesize = filesize($filepath);
			header('Cache-control: max-age=31536000');
			header('Expires: '.gmdate('D, d M Y H:i:s', time() + 31536000).' GMT');
			header('Content-Encoding: none');
			header('Content-Length: '.$filesize);
			header('Content-Disposition: attachment; filename='.$filename);
			header('Content-Type: '.$filetype);
			readfile($filepath);
			exit;
		}else{
			$this->error('出错了,没有找到分卷文件！');
		}
	}
	//删除分卷文件
	public function del(){
		$filename = $this->file_id($_GET['id']);
		@unlink(DATA_PATH.'_bak/'.$filename);
		$this->success($filename.'已经删除！');
	}
	//删除所有分卷文件
	public function delall(){
		foreach($_POST['ids'] as $value){
			@unlink(DATA_PATH.'_bak/'.$this->file_id($value));
		}
		$this->success('批量删除分卷文件成功！');
	}
	//展示高级SQL
    public function sql(){
		$this->display('./Public/system/data_sql.html');
    }
	//执行SQL语句
    public function upsql(){
		$sql = trim($_POST['sql']);
		if (empty($sql)) {
			$this->error('SQL语句不能为空！');
		}else{
			$rs = new Model();
			$array_sql = explode(';', $sql);
			foreach($array_sql as $key=>$value){
				$rs->query(trim(stripslashes($value)));
				dump($rs->getLastSql());
			}
			$this->assign("waitSecond",20);
			$this->success('SQL语句成功运行!');
		}
  }
	//展示批量替换
  public function replace(){
		$rs = new Model();
		$list = $rs->query('SHOW TABLES FROM '.C('db_name'));
		$tablearr = array();
		foreach ($list as $key => $val) {
			$tablearr[$key] = current($val);
		}
		$this->assign('list_table',$tablearr);	
		$this->display('./Public/system/data_replace.html');
  }	
  //Ajax展示字段信息
  public function ajaxfields(){
		$id = ucfirst(str_replace(C('db_prefix'),'',$_GET['id']));
		if (!empty($id)) {
			$array = M($id)->getDbFields();
			echo "<div style='border:2px solid #eee;background-color:#FEFFF0;padding:5px;'>";
			echo "<p>表(".C('db_prefix').$id.")含有的字段：</p><p>";
			foreach($array as $key=>$val){
				if(!is_int($key)){
					break;
				}
				/*if(ereg("cfile|username|userpwd|user|pwd",$val)){
					continue;
				}*/
				echo "<a href=\"javascript:rpfield('".$val."')\">".$val."</a>\r\n";
			}
			echo "</p></div>";
		}else{
			echo 'no fields';
		}
  }
	//执行批量替换
  public function upreplace(){
		if(empty($_POST['rpfield'])){
			$this->error("请手工指定要替换的字段！");
		}
		if(empty($_POST['rpstring'])){
			$this->error("请指定要被替换内容！");
		}
		$exptable = str_replace(C('db_prefix'),'',$_POST['exptable']);
		$rs = D(ucfirst($exptable));
		$exptable = C('db_prefix').$exptable;//表
		$rpfield = trim($_POST['rpfield']);//字段
		$rpstring = $_POST['rpstring'];//被替换的
		$tostring = $_POST['tostring'];//替换内容
		$condition = trim(stripslashes($_POST['condition']));//条件
		$condition = empty($condition) ? '' : " where $condition ";
		$rs->execute(" update $exptable set $rpfield = Replace($rpfield,'$rpstring','$tostring') $condition ");
		$lastsql = $rs->getLastSql();
		$this->success('批量替换完成!SQL执行语句!<br>'.$lastsql);
  }										
}
?>