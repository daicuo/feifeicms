<?php
class VcodeAction extends AllAction{
	public function index(){
		session_start();
		import("ORG.Util.Image");
		Image::buildImageVerify();//6,0,'png',1,20,'verify'
	}
}
?>