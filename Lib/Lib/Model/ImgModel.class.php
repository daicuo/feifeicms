<?php
class ImgModel extends Model {
	//调用接口
	public function down_load($url, $sid='vod'){
		if (C('upload_http')) {
			return $this->down_img($url, $sid);
		}else{
			return $url;
		}
	}
	//远程下载图片
	public function down_img($url, $sid='vod'){
		//是否远程图片
		$array = parse_url($url);
		if(!in_array($array['scheme'],array('http','https','ftp'))){
			return $url;
		}
		//无后缀自动添加
		$chr = strrchr($url,'.');
		if(!in_array($chr,array('gif','jpg','jpeg','bmp','png'))){
			$chr = '.jpg';
		}
		//保存开始
		$imgUrl = uniqid();
		$imgfile = date(C('upload_style'),time());
		$imgPath = $sid.'/'.$imgfile.'/';	
		$imgPath_s = $sid.'-s/'.$imgfile.'/';
		$filename = './'.C('upload_path').'/'.$imgPath.$imgUrl.$chr;
		$filename_s = './'.C('upload_path').'/'.$imgPath_s.$imgUrl.$chr;
		$get_file = ff_file_get_contents($url);
		if ($get_file) {
			write_file($filename,$get_file);
			//是否添加水印
			if(C('upload_water')){
				import('ORG.Util.Image');
				Image::water($filename,C('upload_water_img'),'',C('upload_water_pct'),C('upload_water_pos'));
			}		   
			//是否生成缩略图
			if(C('upload_thumb')){
				mkdirss('./'.C('upload_path').'/'.$imgPath_s);
				import('ORG.Util.Image');
				if (C('upload_thumb') == 1) {
					Image::thumb($filename,$filename_s,'',C('upload_thumb_w') ,C('upload_thumb_h'),true);
				}else{
					Image::crop($filename,$filename_s,C('upload_thumb_w'),C('upload_thumb_h'));
				}
			}
			//是否上传远程
			if (C('upload_ftp')) {
				$this->ftp_upload($sid, $imgfile.'/'.$imgUrl.$chr);
			}
			return $imgPath.$imgUrl.$chr;
		}else{
			return $url;
		} 
	}	
	//远程ftp附件
	public function ftp_upload($sid, $imgurl){
		Vendor('Ftp.Ftp');
		$ftpcon = array(
			'ftp_host'=>C('upload_ftp_host'),
			'ftp_port'=>C('upload_ftp_port'),
			'ftp_user'=>C('upload_ftp_user'),
			'ftp_pwd'=>C('upload_ftp_pass'),
			'ftp_dir'=>C('upload_ftp_dir'),
		);
		$ftp = new ftp();
		$ftp->config($ftpcon);
		$ftp->connect();
		$ftpimg = $ftp->put(C('upload_path').'/'.$sid.'/'.$imgurl, C('upload_path').'/'.$sid.'/'.$imgurl);
		if(C('upload_thumb')){
			$ftpimg_s = $ftp->put(C('upload_path').'/'.$sid.'-s/'.$imgurl, C('upload_path').'/'.$sid.'-s/'.$imgurl);
		}
		if(C('upload_ftp_del')){
			if($ftpimg){
				@unlink(C('upload_path').'/'.$sid.'/'.$imgurl);
			}
			if($ftpimg_s){
				@unlink(C('upload_path').'/'.$sid.'-s/'.$imgurl);
			}
		}
		$ftp->bye();
	}
	//上传图片
	public function ff_upload($module_name = 'vod', $file_back){
		//附件保存目录
		$uppath = './'.C('upload_path').'/'.$module_name.'/';
		mkdirss($uppath);
		if (C('upload_thumb')) {
			if($file_back == 'thumb'){//小图直接覆盖原图 如头像
				$uppath_s = './'.C('upload_path').'/'.$module_name.'/';
			}else{
				$uppath_s = './'.C('upload_path').'/'.$module_name.'-s/';
			}
			mkdirss($uppath_s);
		}
		//导入上传组件
		import("ORG.Net.UploadFile");
		$up = new UploadFile();
		//$up->maxSize = 3292200;
		$up->savePath = $uppath;
		$up->saveRule = uniqid;
		$up->uploadReplace = true;
		$up->allowExts = explode(',',C('upload_class'));
		$up->autoSub = true;
		$up->subType = date;
		$up->dateFormat = C('upload_style');
    if (!$up->upload()) {
			$error = $up->getErrorMsg();
			if($error == '上传文件类型不允许'){
				$error .= '，可上传<font color=red>'.C('upload_class').'</font>';
				if($file_back != 'ckeditor'){
					$error .= '[<a href="?s=Admin-Upload-Show-sid-'.$module_name.'-fileback-'.$file_back.'">重新上传</a>]';
				}
			}
			$this->error = $error ;
			return false;
		}
		$uploadList = $up->getUploadFileInfo();
		// 是否添加水印
		if (C('upload_water')) {
		   import("ORG.Util.Image");
		   Image::water($uppath.$uploadList[0]['savename'],C('upload_water_img'),'',C('upload_water_pct'),C('upload_water_pos'));
		}
		// 是否生成缩略图
		if (C('upload_thumb')) {
		   $thumbdir = substr($uploadList[0]['savename'],0,strrpos($uploadList[0]['savename'], '/'));
		   mkdirss($uppath_s.$thumbdir);
		   import("ORG.Util.Image");
			 if (C('upload_thumb') == 1) {
				 Image::thumb($uppath.$uploadList[0]['savename'],$uppath_s.$uploadList[0]['savename'],'',C('upload_thumb_w'),C('upload_thumb_h'),true);
			 }else{
				 Image::crop($uppath.$uploadList[0]['savename'],$uppath_s.$uploadList[0]['savename'],C('upload_thumb_w'),C('upload_thumb_h'));
			 }
		}
		// 是否远程图片
		if (C('upload_ftp')) {
			$this->ftp_upload($module_name, $uploadList[0]['savename']);
		}
		return $module_name.'/'.$uploadList[0]['savename'];
	}
}
?>