<?php
class CreateAction extends BaseAction{
  public function _initialize(){
		//@set_time_limit(3600);
	  parent::_initialize();
		C('tmpl_cache_on',1);
		C('cache_foreach',600);
		C('TMPL_TEMPLATE_SUFFIX','.tpl');
		$this->assign($this->Lable_Style());
		$this->assign("waitSecond",C('url_time'));
  }
	public function index(){
		$this->echo_ob_flush();
		$this->CreateIndex();
		echo('<p style="font-size:13px;color:red;">首页生成完毕</p>');
	}
	public function category(){
		list($key,$page,$cid) = array(0,1,intval($_REQUEST['categoryid']));
		if($continue = F('_create/category')){//断点记录起始点
			list($key,$page,$cid) = array($continue['nextKey'],$continue['nextPage'],intval($continue['nextCid']));
		}
		$this->echo_ob_flush();
		$this->CreateCategory($key,$page,$cid);
	}
	public function categorystop(){
		ff_create_statusSet('category','stop');
		echo('<p style="font-size:13px;color:red;">任务已经停止，您可以接着上一次继续生成</p>');
	}
	public function vod(){
		list($key,$page,$cid,$hour) = array(0,1,intval($_REQUEST['vodcid']),intval($_REQUEST['vodhour']));
		if($continue = F('_create/vod')){
			list($key,$page,$cid,$hour) = array($continue['nextKey'],$continue['nextPage'],intval($continue['nextCid']),intval($continue['nextHour']));
		}
		$this->echo_ob_flush();
		$this->CreateVod($key,$page,$cid,$hour);
	}
	public function vodid(){
		if($info = $this->CreateVodDb(trim($_REQUEST["ids"]),true)){
			header("Connection: close");
			header("HTTP/1.1 200 OK");
			$path = ff_url_build('vod/read', array('list_id'=>$info['list_id'],'list_dir'=>$info['list_dir'],'pinyin'=>$info['vod_ename'],'id'=>$info['vod_id']));
			echo str_repeat(" ", 4096).'<p style="font-size:13px;color:red;">'.$info['vod_id'].' <a href="'.$path.C('html_file_suffix').'" target="_blank">'.$path.C('html_file_suffix').'</a></p>';
			ob_flush();flush();
			ignore_user_abort(true);
			$this->CreateVodHtml($info);
		}else{
			echo '<p style="font-size:13px;color:red;">数据错误，请检查</p>';
		}
	}
	public function vodstop(){
		ff_create_statusSet('vod','stop');
		echo('<p style="font-size:13px;color:red;">任务已经停止，您可以接着上一次继续生成</p>');
	}
	public function news(){
		list($key,$page,$cid,$hour) = array(0,1,intval($_REQUEST['newscid']),intval($_REQUEST['newshour']));
		if($continue = F('_create/news')){
			list($key,$page,$cid,$hour) = array($continue['nextKey'],$continue['nextPage'],intval($continue['nextCid']),intval($continue['nextHour']));
		}
		$this->echo_ob_flush();
		$this->CreateNews($key,$page,$cid,$hour);
	}
	public function newsid(){
		if($info = $this->CreateNewsDb(trim($_REQUEST["ids"]),true)){
			header("Connection: close");
			header("HTTP/1.1 200 OK");
			$path = ff_url_build('news/read',array('list_id'=>$info['list_id'],
			'list_dir'=>$info['list_dir'],'pinyin'=>$info['news_ename'],'id'=>$info['news_id'],'p'=>1));
			echo str_repeat(" ", 4096).'<p style="font-size:13px;color:red;">'.$info['news_id'].' <a href="'.$path.C('html_file_suffix').'" target="_blank">'.$path.C('html_file_suffix').'</a></p>';
			ob_flush();flush();
			ignore_user_abort(true);
			$this->CreateNewsHtml($info);
		}else{
			echo '<p style="font-size:13px;color:red;">数据错误，请检查</p>';
		}
	}
	public function newsstop(){
		ff_create_statusSet('news','stop');
		echo('<p style="font-size:13px;color:red;">任务已经停止，您可以接着上一次继续生成</p>');
	}
	public function clear(){
		F('_create/category',NULL);
		F('_create/vod',NULL);
		F('_create/news',NULL);
		unlink('./Runtime/Data/_create/category.txt');
		unlink('./Runtime/Data/_create/vod.txt');
		unlink('./Runtime/Data/_create/news.txt');
		echo('<p style="font-size:13px;color:red;">清除成功</p>');
	}
	public function goon(){
		$array = array();
		$array['category']['status'] = ff_create_statusGet('category');
		$array['category']['goon'] = F('_create/category');
		$array['vod']['status'] = ff_create_statusGet('vod');
		$array['vod']['goon'] = F('_create/vod');
		$array['news']['status'] = ff_create_statusGet('news');
		$array['news']['goon'] = F('_create/news');
		echo json_encode($array);
	}
	public function task(){
		$hour = ff_default(intval($_GET['hour']),1);
		$type = ff_default(intval($_GET['type']),1);
		$this->echo_ob_flush();
		$this->CreateIndex();
		$this->CreateVod(0,1,0,$hour);
		$this->CreateNews(0,1,0,$hour);
		if( rand(1,$type) == 1){
			$this->CreateCategory(0,1,0);
		}
	}	
  public function show(){
		if(ff_isNginxWin()){
			$this->error('检测到您的运行环境为（Window+Nginx）建议您更换为IIS或Apache<br/><br/>因为此环境如没有进行特殊配置（FasctCgi）将不能进行多线程处理<br/><br/>任何PHP程序都将在服务器上排队等上一个请求完了才处理下一个<br/>');
		}			
		$array = array();
		$array['url_html'] = 'disabled';
		$array['url_list'] = 'disabled';
		$array['url_vod_detail'] = 'disabled';
		$array['url_news_detail'] = 'disabled';
		if(C('url_html')){
			$array['url_html'] = '';
			if(C('url_list')){
				$array['url_list'] = '';
			}
			if(C('url_vod_detail')){
				$array['url_vod_detail'] = '';
			}
			if(C('url_news_detail')){
				$array['url_news_detail'] = '';
			}
		}	
		$this->assign($array);
    $this->display('./Public/system/html_show.html');
  }
	private function echo_ob_flush(){
		header("Connection: close");
		header("HTTP/1.1 200 OK");
		echo str_repeat(" ", 1024*128*8);
		echo('<p style="font-size:13px;color:red;">任务已经开始，后台执行中</p>');
		ob_flush();flush();
		ob_end_clean();//销毁缓冲区，后面的不会显示在浏览器上了
		ignore_user_abort(true);//后台运行
	}
}
?>