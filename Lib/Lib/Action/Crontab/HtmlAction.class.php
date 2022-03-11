<?php
class HtmlAction extends HomeAction{
	public function index($params,$crontab=false){
		if(!$crontab){ exit; }
		//参数初始化
		list($hour,$round) = explode(',',$params);
		$hour = ff_default($hour, 1);
		$round = ff_default($round, 3);
		//后台运行
		header("Connection: close");
		header("HTTP/1.1 200 OK");
		echo str_repeat(" ", 1024*128*8);
		echo('<p style="font-size:13px;color:red;">任务已经开始，后台执行中</p>');
		ob_flush();flush();ob_end_clean();
		ignore_user_abort(true);
		//调用生成组件
		$this->CreateIndex();
		$this->CreateVod(0,1,0,$hour);
		$this->CreateNews(0,1,0,$hour);
		if( rand(1,$round) == 1){
			$this->CreateCategory(0,1,0);
		}
	}
}
?>