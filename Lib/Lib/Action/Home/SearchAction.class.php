<?php
class SearchAction extends HomeAction{
	public function index(){
		$this->display("Search:index");
	}
	public function api(){
		$model = 'vod';
		$sid = intval($_REQUEST['sid']);
		if(in_array($sid,array(1,2,3,5,6,8,9))){
			$model = ff_sid2module($sid);
		}
		$this->$model();//call_user_func
	}
	private function vod(){
		$params = array();
		$params['status'] = '1';
		$params['name'] = htmlspecialchars(urldecode(trim($_REQUEST['wd'])));
		$params['field'] = 'list_id,list_dir,vod_id,vod_name,vod_ename,vod_jumpurl';
		$params['limit'] = !empty($_GET['limit'])?intval($_GET['limit']):20;
		$params['order'] = 'vod_stars desc,vod_hits desc,vod_id';
		$params['sort'] = 'desc';
		$params['cache_name'] = 'default';
		$params['cache_time']= 'default';
		$infos = ff_mysql_vod($params);
		if($infos){
			foreach($infos as $key=>$value){
				$infos[$key]['name'] = trim($value['vod_name']);
				$infos[$key]['link'] = ff_url_read_vod($value['list_id'],$value['list_dir'],$value['vod_id'],$value['vod_ename'],$value['vod_jumpurl']);
			}
			$this->ajaxReturn($infos,"ok",1);
		}else{
			$this->ajaxReturn($infos,"error",0);
		}
	}
	private function news(){
		$params = array();
		$params['status'] = '1';
		$params['name'] = htmlspecialchars(urldecode(trim($_REQUEST['wd'])));
		$params['field'] = 'list_id,list_dir,news_id,news_name,news_ename,news_jumpurl';
		$params['limit'] = !empty($_GET['limit'])?intval($_GET['limit']):20;
		$params['order'] = 'news_stars desc,news_id';
		$params['sort'] = 'desc';
		$params['cache_name'] = 'default';
		$params['cache_time']= 'default';
		$infos = ff_mysql_news($params);
		if($infos){
			foreach($infos as $key=>$value){
				$infos[$key]['name'] = trim($value['news_name']);
				$infos[$key]['link'] = ff_url_read_news($value['list_id'],$value['list_dir'],$value['news_id'],$value['news_ename'],$value['news_jumpurl']);
			}
			$this->ajaxReturn($infos, "ok", 1);
		}else{
			$this->ajaxReturn($infos, "error", 0);
		}
	}
	private function special(){
		$params = array();
		$params['name'] = htmlspecialchars(urldecode(trim($_REQUEST['wd'])));
		$params['field'] = 'list_id,list_dir,special_id,special_name,special_ename';
		$params['limit'] = !empty($_GET['limit'])?intval($_GET['limit']):20;
		$params['order'] = 'special_stars desc,special_id';
		$params['sort'] = 'desc';
		$params['cache_name'] = 'default';
		$params['cache_time']= 'default';
		$infos = ff_mysql_special($params);
		if($infos){
			foreach($infos as $key=>$value){
				$infos[$key]['name'] = trim($value['special_name']);
				$infos[$key]['link'] = ff_url_read_special($value['list_id'],$value['list_dir'],$value['special_id'],$value['special_ename']);
			}
			$this->ajaxReturn($infos, "ok", 1);
		}else{
			$this->ajaxReturn($infos, "error", 0);
		}
	}
	private function guestbook(){
		$params = array();
		$params['content'] = htmlspecialchars(urldecode(trim($_REQUEST['wd'])));
		$params['field'] = 'user_id,user_name,forum_id,forum_name,forum_ename';
		$params['limit'] = !empty($_GET['limit'])?intval($_GET['limit']):20;
		$params['sid'] = '5';
		$params['status'] = '1';
		$params['order'] = 'forum_id';
		$params['sort'] = 'desc';
		$params['cache_name'] = 'default';
		$params['cache_time']= 'default';
		$infos = ff_mysql_forum($params);
		if($infos){
			foreach($infos as $key=>$value){
				$infos[$key]['name'] = trim($value['user_name']).'发表的留言';
				$infos[$key]['link'] = ff_url('guestbook/read',array('id'=>$value['forum_id']),true);
			}
			$this->ajaxReturn($infos, "ok", 1);
		}else{
			$this->ajaxReturn($infos, "error", 0);
		}
	}
	private function forum(){
		$params = array();
		$params['content'] = htmlspecialchars(urldecode(trim($_REQUEST['wd'])));
		$params['field'] = 'user_id,user_name,forum_id,forum_name,forum_ename';
		$params['limit'] = !empty($_GET['limit'])?intval($_GET['limit']):20;
		$params['sid_not'] = '5';
		$params['status'] = '1';
		$params['order'] = 'forum_id';
		$params['sort'] = 'desc';
		$params['cache_name'] = 'default';
		$params['cache_time']= 'default';
		$infos = ff_mysql_forum($params);
		if($infos){
			foreach($infos as $key=>$value){
				$infos[$key]['name'] = trim($value['user_name']).'发表的评论';
				$infos[$key]['link'] = ff_url('forum/read',array('id'=>$value['forum_id']),true);
			}
			$this->ajaxReturn($infos, "ok", 1);
		}else{
			$this->ajaxReturn($infos, "error", 0);
		}
	}
	private function star(){
		$params = array();
		$params['status'] = '1';
		$params['name'] = htmlspecialchars(urldecode(trim($_REQUEST['wd'])));
		$params['field'] = 'list_id,list_dir,person_id,person_name,person_ename,person_jumpurl';
		$params['limit'] = !empty($_GET['limit'])?intval($_GET['limit']):20;
		$params['order'] = 'person_stars desc,person_hits desc,person_id';
		$params['sort'] = 'desc';
		$params['cache_name'] = 'default';
		$params['cache_time']= 'default';
		$infos = ff_mysql_star($params);
		if($infos){
			foreach($infos as $key=>$value){
				$infos[$key]['name'] = trim($value['person_name']);
				$infos[$key]['link'] = ff_url_read_star($value['list_id'],$value['list_dir'],$value['person_id'],$value['person_ename'],$value['person_jumpurl']);
			}
			$this->ajaxReturn($infos,"ok",1);
		}else{
			$this->ajaxReturn($infos,"error",0);
		}
	}
	private function role(){
		$params = array();
		$params['status'] = '1';
		$params['name'] = htmlspecialchars(urldecode(trim($_REQUEST['wd'])));
		$params['field'] = 'list_id,list_dir,person_id,person_name,person_ename,person_jumpurl';
		$params['limit'] = !empty($_GET['limit'])?intval($_GET['limit']):20;
		$params['order'] = 'person_stars desc,person_hits desc,person_id';
		$params['sort'] = 'desc';
		$params['cache_name'] = 'default';
		$params['cache_time']= 'default';
		$infos = ff_mysql_star($params);
		if($infos){
			foreach($infos as $key=>$value){
				$infos[$key]['name'] = trim($value['person_name']);
				$infos[$key]['link'] = ff_url_read_role($value['list_id'],$value['list_dir'],$value['person_id'],$value['person_ename'],$value['person_jumpurl']);
			}
			$this->ajaxReturn($infos,"ok",1);
		}else{
			$this->ajaxReturn($infos,"error",0);
		}
	}
}
?>