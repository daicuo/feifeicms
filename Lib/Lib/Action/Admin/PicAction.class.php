<?php
class PicAction extends BaseAction{
	public function tool(){
		$this->display('./Public/system/pic_tool.html');
	}
	// 本地附件展示
    public function show(){
        //当前目录
        $dirpath = $this->file_id($_GET['id']);
		if (!$dirpath) {
			$dirpath = './'.C('upload_path');
		}
        //上级目录
		$dirlast = $this->dirlast();
		import("ORG.Io.Dir");
		$dir = new Dir($dirpath);
		$list_dir = $dir->toArray();
		foreach($list_dir as $key=>$value){
            $list_dir[$key]['pathfile'] = $value['path'].'/'.$value['filename'];
		}
		if (empty($list_dir)){
			$this->error('还没有上传任何附件,无需管理！');
		}	
		if($dirlast && $dirlast != '.'){
			$this->assign('dirlast', $dirlast);
		}
		$this->assign('dirpath',$dirpath);
		$this->assign('list_dir',$list_dir);
		$this->display('./Public/system/pic_show.html');
    }
	//获取上一层路径
	private function dirlast(){
        $id = $this->file_id($_GET['id']);
		if ($id) {
			return substr($id, 0, strrpos($id, '/'));
		}else{
			return false;
		}
	}
    //文件目录过滤
    private function file_id($file_name){
        $file_name = urldecode(trim($file_name));
        $file_name = str_replace('../', '', $file_name);
        $file_name = str_replace('..', '', $file_name);
        //目录锁定
        $length = strlen(C('upload_path'))+3;
        if(substr($file_name, 0, $length) != './'.C('upload_path').'/'){
            return false;
        }
        return $file_name;
    }
	// 删除单个本地附件
    public function del(){
		$path = $this->file_id($_GET['id']);
        if(!$path){
            $this->error('不在上传文件夹范围内！');
        }
		@unlink($path);
		@unlink(str_replace(C('upload_path').'/',C('upload_path').'-s/',$path));
		$this->success('删除附件成功！');
    }
	// AJAX清理无效图片
	public function clear(){
		$path = $this->file_id($_GET['id']);
        if(!$path){
            exit('不在上传文件夹范围内！');
        }
		//根据参数组合生成当前目录下的图片数组
		$list = glob($path.'/*');
		if(empty($list)){
			exit('无图片');
		}
		foreach ($list as $i=>$file){
			$dir[] = str_replace('./'.C('upload_path').'/','',$path.'/'.basename($file));
		}
		//根据条件查询数据库并将图片保存为数组
		if(stristr($path,'/vod')){
			$rs = M("Vod");
			$array = $rs->field('vod_pic')->where('Left(vod_pic,4)!="http"')->order('vod_id desc')->select();
			foreach ($array as $value){
				$dir2[] = $value['vod_pic'];
			}
		}elseif(stristr($path,'/news')){
			$rs = M("News");
			$array = $rs->field('news_pic')->where('Left(news_pic,4)!="http"')->order('news_id desc')->select();
			foreach ($array as $value){
				$dir2[] = $value['news_pic'];
			}
		}elseif(stristr($path,'/slide')){
			$rs = D("Slide");
			$array = $rs->field('slide_pic')->where('Left(slide_pic,4)!="http"')->order('slide_id desc')->select();
			foreach ($array as $value){
				$dir2[] = $value['slide_pic'];
			}
		}elseif(stristr($path,'/link')){
			$rs = M("Link");
			$array = $rs->field('link_logo')->where('Left(link_logo,4)!="http"')->order('link_id desc')->select();
			foreach ($array as $value){
				$dir2[] = $value['link_logo'];
			}
		}
		//筛选出当前目录下的无效图片
		$del = array_diff($dir,$dir2);
		foreach ($del as $key=>$value){
			@unlink('./'.C('upload_path').'/'.$value);
		};
		exit('清理完成');
    }
	//裁剪小图
	public function crop(){
		if (!C('upload_thumb')) {
			exit('<h2 style="margin-top:100px; padding:0 auto; text-align:center">未开启缩略图生成功能，如有需要请先配置。</h2>');
		}
		if(($jumpurl = F('_img/crop')) && $_GET['first']){
			echo '<meta http-equiv="refresh" content=2;url='.$jumpurl.'>';
			exit('<h2 style="margin-top:100px; padding:0 auto; text-align:center">系统检测到上一次的任务未完成，2秒后继续执行。</h2>');
		}
		$sid = $_GET['sid'];
		$cid = intval($_GET['cid']);
		$page = !empty($_GET['p']) ? intval($_GET['p']) : 1;
		import("ORG.Util.Image");
		$rs = D(ucfirst($sid));
		echo '<style type="text/css">p{font-size:12px;color: #333;line-height:21px;}</style>';
		//视频类
		if($sid == "vod"){
			if($cid){
				$where = 'Left(vod_pic,4)="vod/" and vod_cid='.$cid;
			}else{
				$where = 'Left(vod_pic,4)="vod/"';
			}
			$list = $rs->field('vod_id,vod_pic')->where($where)->limit(C('upload_http_down'))->page($page)->order('vod_id desc')->select();
			foreach($list as $key=>$value){
				$img = '.'.ff_url_img($value['vod_pic']);
				$img_s = '.'.ff_url_img_small($value['vod_pic']);
				mkdirss(substr($img_s,0,strrpos($img_s, '/')));//小图保存目录自动创建
				if (C('upload_thumb') == 1) {
					$img_create = Image::thumb($img, $img_s, '', C('upload_thumb_w'), C('upload_thumb_h'), true);
				}else{
					$img_create = Image::crop($img, $img_s, C('upload_thumb_w'), C('upload_thumb_h'));
				}
				if($img_create){
					echo('<p>ID:'.$value['vod_id'].'生成小图成功：'.$img_s.'</p>');
				}else{
					echo('<p>ID:'.$value['vod_id'].'生成小图失败：未找到原图，或没有写入权限。</p>');
				}
				ob_flush();flush();
			}
		}else if($sid == "news"){
			if($cid){
				$where = 'Left(news_pic,5)="news/" and news_cid='.$cid;
			}else{
				$where = 'Left(news_pic,5)="news/"';
			}
			$list = $rs->field('news_id,news_pic')->where($where)->limit(C('upload_http_down'))->page($page)->order('news_id desc')->select();
			foreach($list as $key=>$value){
				$img = '.'.ff_url_img($value['news_pic']);
				$img_s = '.'.ff_url_img_small($value['news_pic']);
				mkdirss(substr($img_s,0,strrpos($img_s, '/')));//小图保存目录自动创建
				if (C('upload_thumb') == 1) {
					$img_create = Image::thumb($img, $img_s, '', C('upload_thumb_w'), C('upload_thumb_h'), true);
				}else{
					$img_create = Image::crop($img, $img_s, C('upload_thumb_w'), C('upload_thumb_h'));
				}
				if($img_create){
					echo('<p>ID:'.$value['news_id'].'生成小图成功：'.$img_s.'</p>');
				}else{
					echo('<p>ID:'.$value['news_id'].'生成小图失败：未找到原图，或没有写入权限。</p>');
				}
				ob_flush();flush();
			}
		}
		//分页处理
		if($list){
			$jumpurl = '?s=Admin-Pic-Crop-sid-'.$sid.'-cid-'.$cid.'-p-'.($page+1);
			echo '<meta http-equiv="refresh" content='.C('collect_time').';url='.$jumpurl.'>';
			echo '<p>请稍等一会，正在释放服务器资源...</p>';
			F('_img/crop',$jumpurl);
		}else{
			F('_img/crop', NULL);
			echo '<h2 style="margin-top:100px; padding:0 auto; text-align:center">生成缩略图任务完成。</h2>';
		}
	}
	//下载远程图片
    public function down(){
		$sid = $_GET['sid'];
		$cid = intval($_GET['cid']);
		$img = D('Img');
		$rs = M(ucfirst($sid));
		echo '<style type="text/css">p{font-size:12px;color: #333;line-height:21px;}span{font-weight:bold;color:#FF0000}</style>';
		//视频类
		if($sid == "vod"){
			$where = 'Left(vod_pic,4)="http"';
			if($cid){
				$where .= ' and vod_cid='.$cid;
			}
			$count = $rs->where($where)->count('vod_id');
			echo'<p>共有<span>'.$count.'</span>张远程图片，每次下载<span>'.C('upload_http_down').'</span>张，<span>'.C('collect_time').'</span>秒后执行下一次操作。</p>';
			if($count){
				$list = $rs->field('vod_id,vod_pic')->where($where)->limit(C('upload_http_down'))->order('vod_id desc')->select();
				$failid = array();
				foreach($list as $key=>$value){
					$imgnew = $img->down_img($value['vod_pic'], 'vod');
					if($value['vod_pic'] == $imgnew){
						$failid[] = $value['vod_id'];
						echo('<p>ID:'.$value['vod_id'].'下载失败：'.$value['vod_pic'].'</p>');
					}else{
						$rs->where('vod_id = '.$value['vod_id'])->setField('vod_pic', $imgnew);
						echo('<p>ID:'.$value['vod_id'].'下载成功：'.$imgnew.'</p>');			
					}
					ob_flush();flush();
				}
				//下载失败的图片前缀处理
				if($failid){
					$sql = 'update '.C("db_prefix").'vod set vod_pic=concat("fail://",vod_pic) where vod_id in('.implode(",",$failid).')';
					$rs->query($sql);
				}
			}else{
				//还原所有被标记为fail://的图片
				$rs->execute('update '.C('db_prefix').'vod set vod_pic = REPLACE(vod_pic,"fail://", "")');
			}
		}else if($sid == "news"){
			$where = 'Left(news_pic,4)="http"';
			if($cid){
				$where .= ' and news_cid='.$cid;
			}
			$count = $rs->where($where)->count('news_id');
			echo'<p>共有<span>'.$count.'</span>张远程图片，每次下载<span>'.C('upload_http_down').'</span>张，<span>'.C('collect_time').'</span>秒后执行下一次操作。</p>';
			if($count){
				$list = $rs->field('news_id,news_pic')->where($where)->limit(C('upload_http_down'))->order('news_id desc')->select();
				$failid = array();
				foreach($list as $key=>$value){
					$imgnew = $img->down_img($value['news_pic'], 'news');
					if($value['news_pic'] == $imgnew){
						$failid[] = $value['news_id'];
						echo('<p>ID:'.$value['news_id'].'下载失败：'.$value['news_pic'].'</p>');
					}else{
						$rs->where('news_id = '.$value['news_id'])->setField('news_pic', $imgnew);
						echo('<p>ID:'.$value['news_id'].'下载成功：'.$imgnew.'</p>');			
					}
					ob_flush();flush();
				}
				//下载失败的图片前缀处理
				if($failid){
					$sql = 'update '.C("db_prefix").'news set news_pic=concat("fail://",news_pic) where news_id in('.implode(",",$failid).')';
					$rs->query($sql);
				}
			}else{
				//还原所有被标记为fail://的图片
				$rs->execute('update '.C('db_prefix').'news set news_pic = REPLACE(news_pic,"fail://", "")');
			}
		}
		//分页处理
		if($list){
			$jumpurl = '?s=Admin-Pic-Down-sid-'.$sid.'-cid-'.$cid;
			echo '<meta http-equiv="refresh" content='.C('collect_time').';url='.$jumpurl.'>';
			echo '<p>请稍等一会，正在释放服务器资源...</p>';
		}else{
			echo '<h2 style="margin-top:100px; padding:0 auto; text-align:center">下载远程图片任务完成。</h2>';
		}
	}				
}
?>