<?php
class HitsAction extends HomeAction{
	public function index($crontab=false){
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