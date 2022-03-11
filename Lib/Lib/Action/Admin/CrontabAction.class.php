<?php
class CrontabAction extends BaseAction{
	public function index(){
		$this->assign('task',F('_crontab/task'));
    $this->display('./Public/system/crontab_index.html');
	}
	public function add(){
		$name = $_GET['name'];
		if($name){
			$task = F('_crontab/task');
			$task[$name]['crontab_name'] = $name;
			$task[$name]['crontab_week'] = explode(',',$task[$name]["crontab_week"]);
			$task[$name]['crontab_hour'] = explode(',',$task[$name]["crontab_hour"]);
			$this->assign($task[$name]);
		}
		$this->assign('list_cj',D('Cj')->where('cj_type in (1,2)')->order('cj_id asc')->select());
    $this->display('./Public/system/crontab_add.html');
	}
	public function update(){
		$name = $_POST['crontab_name'];
		if(!$name){ $this->error('请填写任务名称！'); }
		$task = F('_crontab/task');
		if(!$task){ $task = array(); }
		$task[$name]['crontab_status'] = $_POST['crontab_status'];
		$task[$name]['crontab_info'] = trim($_POST['crontab_info']);
		$task[$name]['crontab_type'] = $_POST['crontab_type'];
		$task[$name]['crontab_params'] = trim($_POST['crontab_params']);
		$task[$name]['crontab_week'] = implode(',',$_POST["crontab_week"]);
		$task[$name]['crontab_hour'] = implode(',',$_POST["crontab_hour"]);
		$task[$name]['crontab_time'] = $_POST['crontab_time'];
		F('_crontab/task',$task);
		$this->success('任务保存成功！');
	}
	public function status(){
		$name = $_GET['name'];
		$value = intval($_GET['value']);
		$task = F('_crontab/task');
		$task[$name]['crontab_status'] = $value;
		F('_crontab/task',$task);
		$this->success('状态修改成功！');
	}
	public function del(){
		$task = F('_crontab/task');
		$name = $_GET['name'];
		unset($task[$name]);
		F('_crontab/task',$task);
		$this->success('删除成功！');
	}
}
?>