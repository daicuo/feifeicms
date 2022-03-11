<?php
class ImagesAction extends HomeAction{
	public function read(){
		$img_url = trim(base64_decode($_GET['url']));
		$img_host = explode(chr(13), str_replace(array("\r\n", "\n", "\r"),chr(13),C('upload_safety')) );
		$img_tmp = parse_url($img_url);
        //安全协议修补
        if(!in_array($img_tmp['scheme'], array('http','https','ftp'))){
            header("Location: ./Public/images/no.jpg"); 
            exit();
        }
        //验证域名
		if( in_array($img_tmp['host'], $img_host) ){
			header('Content-type: image/jpeg');
			//优先缓存读取
			$cache_name = md5($img_url);
			if( C('cache_page_images') ){
				if($img = S($cache_name)){
					exit($img);
				}
			}
			//远程获取及缓存
			$img = ff_file_get_contents($img_url);
			if( C('cache_page_images') && $img ){
				S($cache_name, $img, intval(C('cache_page_images')));
			}
			exit($img);
		}
		header("Location: ./Public/images/no.jpg"); 
	}
}
?>