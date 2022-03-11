<?php
class CaijiAction extends Action{
	public function index($params,$crontab=false){
		if(!$crontab){ exit; }
		list($id,$hour) = explode(',',$params);
		if(!$id){
			exit('参数错误，请填写采集ID');
		}
		$info = D('Cj')->where('cj_id='.$id)->find();
		if(!$info){
			exit('采集ID错误，请检查');
		}
		//后台运行
		header("Connection: close");
		header("HTTP/1.1 200 OK");
		echo str_repeat(" ", 1024*128*8);
		echo('<p style="font-size:13px;color:red;">任务已经开始，后台执行中</p>');
		ob_flush();flush();ob_end_clean();
		ignore_user_abort(true);
		//采集参数拼装
		$params = array();
		$params["h"] = !empty($hour)?intval($hour):6;
		$admin = array();
		$admin["cjid"] = $info["cj_id"];
		$admin["cjtype"] = '';//自动判断json|xml
		$admin["xmlurl"] = base64_encode($info["cj_url"]);
		$admin["action"] = 'days';
		$admin["page"] = 1;
		if($info["cj_type"] == 1){
			$this->vod($admin, $params);
		}elseif($info["cj_type"] == 2){
			$this->news($admin, $params);
		}else{
			echo('该采集ID暂不支持计划任务采集');
		}
	}
	//文章
	private function news($admin, $params){
		$news = D('Cj')->news_json($admin, $params);
		if($news['status'] != 200){ 
			return false;
		}
		$infos = array();
		$infos[1] = $news['infos']['data'];
		unset($news['list']);
		unset($news['infos']['data']);
		//第二页起
		for($i=2;$i<=$news['infos']["page"]["pagecount"];$i++){
			$admin["cjtype"] = $vod["type"];
			$admin["page"] = $i;
			$news_more = D('Cj')->news_json($admin, $params);
			$infos[$i] = $news_more['infos']['data'];
			unset($news_more);
		}
		foreach($infos as $key=>$value){
			foreach($value as $key2=>$data){
				D('Cj')->news_db($data);
			}
		}
		unset($infos);
		return true;
	}	
	//视频
	private function vod($admin, $params){
		//第一页
		$vod = D('Cj')->vod($admin, $params);
		if($vod['status'] != 200){ 
			return false;
		}
		$infos = array();
		$infos[1] = $vod['infos']['data'];
		unset($vod['list']);
		unset($vod['infos']['data']);
		//第二页起
		for($i=2;$i<=$vod['infos']["page"]["pagecount"];$i++){
			$admin["cjtype"] = $vod["type"];
			$admin["page"] = $i;
			$vod_more = D('Cj')->vod($admin, $params);
			$infos[$i] = $vod_more['infos']['data'];
			unset($vod_more);
		}
		foreach($infos as $key=>$value){
			foreach($value as $key2=>$data){
				D('Cj')->vod_db($data);
			}
		}
		unset($infos);
		return true;
	}
}
?>