<?php
class IndexAction extends HomeAction{
	public function _empty($action){
		if(!$action){
			exit('请输入任务标识');
		}
		$tasks = F('_crontab/task');
		if(!($task = $tasks[$action])){
			exit('任务不存在');
		}
		if(!$task['crontab_status']){
			exit('任务未开启');
		}
		$thisWeek = date('w', time());
		if( !in_array($thisWeek, explode(',',$task['crontab_week']) ) ){
			exit('任务不在执行周期内');
		}
		$thisHour = date('H', time());
		if( !in_array($thisHour, explode(',',$task['crontab_hour']) ) ){
			exit('任务不在执行时间内');
		}
		$oldWeek = date('w',$task['crontab_time']);
		$oldHour = date('H',$task['crontab_time']);
		if( $thisWeek.'/'.$thisHour == $oldWeek.'/'.$oldHour ){
			//exit('此任务在'.date('Y-m-d H:i:s',$task['crontab_time']).'已经执行过');
		}
		//记录任务时间
		$tasks[$action]['crontab_time'] = time();
		F('_crontab/task', $tasks);
		//执行对应的任务
		$class = $task['crontab_type'].'Task';
		$this->$class($task['crontab_params']);
	}
	private function caijiTask($params){
		A('Crontab.Caiji')->index($params,true);
	}
	private function createTask($params){
		A('Crontab.Html')->index($params,true);
	}
	private function hitsTask($params){
		A('Crontab.Hits')->index(true);
	}	
}
?>