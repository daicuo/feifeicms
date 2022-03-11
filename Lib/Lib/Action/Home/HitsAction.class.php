<?php
class HitsAction extends HomeAction{
	//静态模式JS展示人气
  public function show(){
		$id	= intval($_GET['id']);
		$sid = trim($_GET['sid']);
		$type = trim($_GET['type']);
		if( in_array($sid, array('vod','news','special','person')) ){
			$where = array();
			$where[$sid.'_id'] = $id;
			$rs = M(ucfirst($sid));
			$array = $rs->field($sid.'_id,'.$sid.'_hits,'.$sid.'_hits_month,'.$sid.'_hits_week,'.$sid.'_hits_day,'.$sid.'_addtime,'.$sid.'_hits_lasttime')->where($where)->find();
			if($type == 'insert'){
				$this->hits_insert($sid,$array);
			}
			echo(json_encode($array));
		}
  }
	//处理各模块的人气值刷新
	public function hits_insert($sid, $array){
		//初始化值
		$hits[$sid.'_hits'] = $array[$sid.'_hits'];
		$hits[$sid.'_hits_month'] = $array[$sid.'_hits_month'];
		$hits[$sid.'_hits_week'] = $array[$sid.'_hits_week'];
		$hits[$sid.'_hits_day'] = $array[$sid.'_hits_day'];
		$new = getdate();
		$old = getdate($array[$sid.'_hits_lasttime']);
		//月
		if($new['year'] == $old['year'] && $new['mon'] == $old['mon']){
			$hits[$sid.'_hits_month'] ++;
		}else{
			$hits[$sid.'_hits_month'] = 1;
		}
		//周
		$weekStart = mktime(0,0,0,$new["mon"],$new["mday"],$new["year"]) - ($new["wday"] * 86400);//本周开始时间,本周日0点0
		$weekEnd = mktime(23,59,59,$new["mon"],$new["mday"],$new["year"]) + ((6 - $new["wday"]) * 86400);//本周结束时间,本周六12点59
		if($array[$sid.'_hits_lasttime'] >= $weekStart && $array[$sid.'_hits_lasttime'] <= $weekEnd){
			$hits[$sid.'_hits_week'] ++;
		}else{
			$hits[$sid.'_hits_week'] = 1;
		}
		//日
		if($new['year'] == $old['year'] && $new['mon'] == $old['mon'] && $new['mday'] == $old['mday']){
			$hits[$sid.'_hits_day'] ++;
		}else{
			$hits[$sid.'_hits_day'] = 1;
		}
		//更新数据库
		$hits[$sid.'_id'] = $array[$sid.'_id'];
		$hits[$sid.'_hits'] = $hits[$sid.'_hits']+1;
		$hits[$sid.'_hits_lasttime'] = time();
		$rs = M(ucfirst($sid));
		$rs->save($hits);
		return $hits;
	}
	//计划任务执行
	public function task($crontab=false){
		if(!$crontab){ exit; }
		D('Vod')->where('vod_id>0')->save(array('vod_hits_day'=>0));
		D('News')->where('news_id>0')->save(array('news_hits_day'=>0));
		D('Special')->where('special_id>0')->save(array('special_hits_day'=>0));
		D('Person')->where('person_id>0')->save(array('person_hits_day'=>0));
		if(date('w', time()) == '0'){//本周第一天
			D('Vod')->where('vod_id>0')->save(array('vod_hits_week'=>0));
			D('News')->where('news_id>0')->save(array('news_hits_week'=>0));
			D('Special')->where('special_id>0')->save(array('special_hits_week'=>0));
			D('Person')->where('person_id>0')->save(array('person_hits_week'=>0));
		}
		if(date('d', time()) == '01'){//本月第一天
			D('Vod')->where('vod_id>0')->save(array('vod_hits_month'=>0));
			D('News')->where('news_id>0')->save(array('news_hits_month'=>0));
			D('Special')->where('special_id>0')->save(array('special_hits_month'=>0));
			D('Person')->where('person_id>0')->save(array('person_hits_month'=>0));
		}
	}
}
?>