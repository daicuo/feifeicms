<?php
class IndexAction extends HomeAction{
	public function index(){
		if (!is_file('./Runtime/Install/install.lock')) {
			$this->assign("jumpUrl",'?s=Admin-Install');
			$this->error('您还没安装本程序，请运行 install.php 进入安装!');
		}
		if(C('url_html')){
			redirect('index'.C('url_html_suffix'));
		}
		$this->assign($this->Lable_Index());
		$this->display('index');
	}
}
?>