<?php
class UploadAction extends BaseAction{
	// 上传表单
 	public function show(){
		$this->display('./Public/system/upload_show.html');
  }
	// 图片上传
	public function upload(){
		$module_name = !empty($_POST['sid']) ? trim($_POST['sid']) : 'vod';//模型
		$file_back = !empty($_POST['fileback']) ? trim($_POST['fileback']) : 'vod_pic';//回跳input
		$file_url = D('Img')->ff_upload($module_name, $file_back);//上传
		if($file_url){
			echo('<div style="font-size:12px; height:30px; line-height:30px">');
			echo "<script type='text/javascript'>parent.document.getElementById('".$file_back."').value='".$file_url."';</script>";
			echo '文件上传成功　[<a href="?s=Admin-Upload-Show-sid-'.$module_name.'-fileback-'.$file_back.'">重新上传</a>]';
			echo '</div>';
		}else{
			echo D('Img')->getError();
		}
  }	
	//CK编辑器图片上传
	public function ckeditor(){
		$module_name = !empty($_REQUEST['sid']) ? trim($_REQUEST['sid']) : 'vod';//模型
		$file_url = D('Img')->ff_upload($module_name, 'ckeditor');//上传
		if($file_url){
			echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction(".intval($_GET["CKEditorFuncNum"]).",'".C("site_path").C("upload_path").'/'.$file_url."','');</script>";
		}else{
			echo D('Img')->getError();
		}
	}
}
?>