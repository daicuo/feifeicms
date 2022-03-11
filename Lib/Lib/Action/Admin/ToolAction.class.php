<?php
class ToolAction extends BaseAction{
	//批量工具
	public function batch(){
		$this->display('./Public/system/tool_batch.html');
	}
	//同名检测
	public function name(){
		$this->display('./Public/system/tool_name.html');
	}	
}
?>