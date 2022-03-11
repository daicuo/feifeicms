 <?php
class TagAction extends BaseAction{	
	// 显示标签列表
  public function show(){
		//查询参数
		$admin['type'] = !empty($_GET['type'])?$_GET['type']:'';
		$admin['limit'] = !empty($_GET['limit'])?intval($_GET['limit']):20;
		$admin['p'] = !empty($_GET['p'])?intval($_GET['p']):1;
		$limit = ($admin['p']-1)*$admin['limit'].','.$admin['limit'];
		//查询数据
		$rs = D("Tag");
		if($admin['type']){
			$count = $rs->query("select count(1) as count from (select tag_id,tag_list from ".C('db_prefix')."tag group by tag_list,tag_name having tag_list='".$admin['type']."') aa");
			$array = $rs->query("select * from (select tag_id,tag_cid,tag_list,tag_name,count(tag_id) as tag_count from ".C('db_prefix')."tag group by tag_cid,tag_name having tag_list='".$admin['type']."' order by tag_count desc) aa limit ".$limit."");
		}else{
			$count = $rs->query("select count(1) as count from (select tag_id,tag_list from ".C('db_prefix')."tag group by tag_list,tag_name) aa");
			$array = $rs->query("select * from (select tag_id,tag_cid,tag_list,tag_name,count(tag_id) as tag_count from ".C('db_prefix')."tag group by tag_cid,tag_name order by tag_cid asc,tag_count desc) aa limit ".$limit."");
		}
		foreach($array as $key=>$val){
			$array[$key]['tag_url'] = U('Admin-'.admin_ff_taglist2modelname($array[$key]['tag_list']).'/Show',array('tag_name'=>urlencode($array[$key]['tag_name']),'tag_list'=>urlencode($array[$key]['tag_list'])),'',false,true);
		}
		//组合分页
		$count = $count[0]['count'];
		$totalpages = ceil($count/$admin['limit']);
		$currentpage = ff_page_max($admin['p'],$totalpages);
		$pageurl = U('Admin-Tag/Show',array('type'=>$admin['type'],'limit'=>$admin['limit'],'p'=>'FFLINK'),false,false).C('url_html_suffix');
		//定义分页变量
		$admin['pages'] = '共'.$count.'个标签&nbsp;当前:'.$currentpage.'/'.$totalpages.'页&nbsp;'.getpage($currentpage,$totalpages,8,$pageurl,'pagego(\''.$pageurl.'\','.$totalpages.')');
		$this->assign($admin);
		$this->assign('list_tag',$array);
		$this->display('./Public/system/tag_show.html');
		//$rs = new Model() ;
		//$count = $rs->query("select count(1) as count from (select tag_id,tag_list from ".C('db_prefix')."tag where tag_cid=1 group by tag_list,tag_name) aa");
		//$array = $rs->field('*,count(tag_name) as tag_count')->limit($limit)->page($currentpage)->group('tag_list,tag_name')->order('tag_count desc')->select();
  }	
	// 显示标签AJAX方式
  public function showajax(){
		$rs = D("Tag");
		$tag_list = !empty($_GET['sid'])?$_GET['sid']:'vod_tag';
		$array = $rs->query("select * from (select tag_id,tag_cid,tag_list,tag_name,count(tag_id) as tag_count from ".C('db_prefix')."tag group by tag_cid,tag_name having tag_list='".$tag_list."' order by tag_count desc) aa limit 0,10");
		$this->assign('tag_input',trim($_GET['input']));
		$this->assign('tag_list',$array);
		$this->display('./Public/system/tag_ajax.html');
		//$where['tag_list'] = array('eq',$_GET['sid']);
		//$array = $rs->field('*,count(tag_name) as tag_count')->where($where)->limit('15')->group('tag_name,tag_list')->order('tag_count desc')->select();
  }
	// 删除标签
  public function del(){
		$where = array();
		$where['tag_name'] = trim($_GET['id']);
		D("Tag")->where($where)->delete();
		$this->success('标签:'.$tag.'删除成功！');
  }	
	// 批量生成标签
	public function create(){
		if(!C('apikey_keyword')){
			$this->assign("jumpUrl","?s=Admin-Config-Base");
			$this->error('请配置您的中文分词ApiKey，未申请的请先免费申请。');
		}
		$sid = $_GET['sid'];
		$minid = intval($_GET['minid']);
		$limit = 10;
		$apistatus = 200;//API权限是否需要升级
		$where = array();
		if($sid == 1){
			$where['vod_id'] = array('gt',$minid);
			$where['vod_keywords'] = array('eq','');
			$list = M('Vod')->field('vod_id,vod_name,vod_content')->where($where)->limit($limit)->order('vod_id asc')->select();
			if(!$list){
				$this->error('所有视频都存在TAG，不需要批量操作。');
			}
			foreach($list as $key=>$value){
				$vod_id = $value["vod_id"];
				$vod_keywords = ff_tag_auto($value["vod_name"], $value["vod_content"]);
				//TAG词验证
				if( strpos($vod_keywords,'出错：') ){
					$array = explode('出错：',$vod_keywords);
					$apistatus = $array[1];
					break;
				}
				//入库验证
				if($vod_keywords){
					D('Vod')->where('vod_id='.$vod_id)->save( array('vod_keywords'=>$vod_keywords) );
					D('Tag')->tag_update($vod_id, $vod_keywords, 'vod_tag');
					echo('<p>'.$vod_id.'('.$value["vod_name"].')自动生成的TAG：'.$vod_keywords.'</p>');
					ob_flush();flush();
				}else{
					echo('<p>'.$vod_id.'('.$value["vod_name"].')无法判断TAG，请手动填写</p>');
					ob_flush();flush();
				}
			}
			$jumpurl = '?s=Admin-Tag-Create-sid-1-minid-'.$vod_id;
		}elseif($sid == 2){
			$where['news_id'] = array('gt',$minid);
			$where['news_keywords'] = array('eq','');
			$list = M('News')->field('news_id,news_name,news_content')->where($where)->limit($limit)->order('news_id asc')->select();
			if(!$list){
				$this->error('所有文章都存在TAG，不需要批量操作。');
			}
			foreach($list as $key=>$value){
				$news_id = $value["news_id"];
				$news_keywords = ff_tag_auto($value["news_name"], $value["news_content"]);
				//TAG词验证
				if( strpos($news_keywords,'出错：') ){
					$array = explode('出错：',$news_keywords);
					$apistatus = $array[1];
					break;
				}
				//入库验证
				if($news_keywords){
					D('News')->where('news_id='.$news_id)->save( array('news_keywords'=>$news_keywords) );
					D('Tag')->tag_update($news_id, $news_keywords, 'news_tag');
					echo('<p>'.$news_id.'自动生成的TAG：'.$news_keywords.'</p>');
					ob_flush();flush();
				}else{
					echo('<p>'.$news_id.'无法判断TAG，请手动填写</p>');
					ob_flush();flush();
				}
			}
			$jumpurl = '?s=Admin-Tag-Create-sid-2-minid-'.$news_id;	
		}
		//API权限验证
		if($apistatus != 200){
			$this->assign("waitSecond", 8);
			$this->assign("jumpUrl", "?s=Admin-Tool-Batch");
			$this->error($apistatus);
		}
		//跳转下一页
		if( in_array($sid, array(1,2)) ){
			echo '<meta http-equiv="refresh" content='.C('collect_time').';url='.$jumpurl.'>';
			echo '<h4>'.C('collect_time').'秒后将自动采集下一页!</h4>';
		}else{
			$this->error('参数错误。');
		}
	}								
}
?>