<?php
class WeixinAction extends HomeAction{

	// 微信消息真实性认证
	public function _initialize(){
		if(C('wx_check') == 0){
			exit('success');
		}
		if($this->checkSignature() == false){
			exit();
		}else{
			if($_GET['echostr']){
				exit($_GET['echostr']);
			}
		}
	}
	
	// 微信服务器推送
	public function index(){
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		//$postStr = file_get_contents("php://input"); 
		if (!empty($postStr)){
			$postObj = simplexml_load_string($postStr);
			$RX_TYPE = trim($postObj->MsgType);
			switch($RX_TYPE){
			case "event": //接收事件
				$resultStr = $this->handleevent($postObj);
				break;
			case "text": //接收文本消息
				$resultStr = $this->handletext($postObj);
				break;	
			default:
				$resultStr = "Unknow msg type: ".$RX_TYPE;
				break;
			}
			echo $resultStr;
		}else {
			echo('success');
		}
	}
	
	// 接收事件推送
	private function handleevent($object){
		switch($object->Event){
			case "subscribe":
				$content = $this->event_subscribe($object);
				break;
			case "unsubscribe":
				$content = $this->response_text($object, "取消关注");
				break;
			case "LOCATION":
				$content = $this->response_text($object, "地理位置");
				break;
			case "CLICK":
				$content = $this->response_text($object, "自定义菜单".$object->EventKey);
				break;
			case "VIEW":
				$content = $this->response_text($object, "自定义菜单".$object->EventKey);
				break;
			case "SCAN":
				$content = $this->event_scan($object);
				break;
			default:
				$content = $this->response_text($object, "待开发事件");
				break;
		}
		return $content;
	}
	
	// 接收文字消息
	private function handletext($object){
		//关键字定义
		$content = trim($object->Content);
		//是否为网页URL
		if( filter_var ($content, FILTER_VALIDATE_URL ) ){
			return $this->response_text($object, C('wx_jiexi').$content);
		}
		//自定义关键词
		$wx_item = C('wx_item');
		if($wx_item){
			$key = array_search($content,$wx_item['keyword']);
			if($key !== false){
				$array = array();
				$array['title'] = $wx_item['title'][$key];
				$array['content'] = $wx_item['content'][$key];
				$array['picurl'] = $wx_item['pic'][$key];
				$array['url'] = $wx_item['link'][$key];
				return $this->response_news($object, $array);
			}
		}
		//字符串长度
		if(ff_mb_strlen($content) > 8){
			return $this->response_text($object, "请输入2~8个字符搜索!");
		}
		//按关键字搜索
		if($data = $this->select_vod($content)){
			if( count($data) > 1){//多条结果搜索页
				$array = array();
				$array['title'] = "[搜索] ".$content;
				$array['content'] = C("site_name")."为您找到多个包含（".$content."）的视频，请点击浏览...";
				$array['picurl'] = ff_url_img($data[0]['vod_pic']);
				$array['url'] =  C('wx_domain').'/index.php?g=home&m=vod&a=search&wd='.urlencode($content);
				return $this->response_news($object, $array);
			}else{
				if(C('wx_check') == 2){//播放页
					$play_max = ff_play_one(ff_play_list($data[0]['vod_server'], $data[0]['vod_play'], $data[0]['vod_url']), 'max');
					$url_jump = C('wx_domain').'/index.php?s=/vod-play-id-'.$data[0]['vod_id'].'-sid-'.$play_max['player_sid'].'-pid-'.$play_max['player_count'].'.html';
				}else{//详情页
					$url_jump = C('wx_domain').'/index.php?s=/vod-read-id-'.$data[0]['vod_id'].'.html';
				}
				$array = array();
				$array['title'] = "[".$data[0]['list_name']."] ".$data[0]['vod_name'];
				$array['content'] = msubstr($data[0]['vod_content'],0,200,true);
				$array['picurl'] = ff_url_img($data[0]['vod_pic']);
				$array['url'] =  $url_jump;
				return $this->response_news($object, $array);
			}
		}
		//无搜索结果
		if( C('wx_none_txt') ){
			if(C('wx_none_url')){
				return $this->response_text($object, '<a href="'.C('wx_none_url').'">'.C('wx_none_txt').'</a>');
			}else{
				return $this->response_text($object, C('wx_none_txt'));
			}
		}
		//默认原样返回
		return $this->response_text($object, '对不起，没有找到您的幸福，请重新输入关键词！');		
	}

	/*-----------------------------------被动回复开始-----------------------------------*/
	// 订阅事件
	private function event_subscribe($object){
		//F('_feifeicms/weixin',$object);
		return $this->response_text($object, C('wx_follow'));
	}
	
	/*-----------------------------------系统函数-----------------------------------*/
	// 被动回复文字信息格式
	private function response_text($object, $content){
		$textTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[text]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			</xml>";
		if(empty($content)){
			$content = "请先在网站后台配置相关回复信息。";
		}
		$content = str_replace("<br>",chr(13),$content);
		return sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
	}
	
	// 被动回复图文信息格式
	private function response_news($object, $array){
		$textTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[news]]></MsgType>
			<ArticleCount>1</ArticleCount>
			<Articles>
			<item>
			<Title><![CDATA[%s]]></Title> 
			<Description><![CDATA[%s]]></Description>
			<PicUrl><![CDATA[%s]]></PicUrl>
			<Url><![CDATA[%s]]></Url>
			</item>
			</Articles>
			</xml>";
		return sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $array['title'], $array['content'], $array['picurl'], $array['url']);
	}
	
	// 被动回复多图文格式
	private function response_item($object, $xml, $limit){
		$textTpl = "<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[news]]></MsgType>
		<ArticleCount>".$limit."</ArticleCount>
		<Articles>%s</Articles>
		</xml>";
		return sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $xml);
	}
	
	//关键字搜索
	private function select_vod($keyword){
		//参数
		$params = array();
		$params['field'] = 'list_name,vod_id,vod_name,vod_content,vod_pic,vod_server,vod_play,vod_url';
		$params['cache_name'] = 'default';
		$params['cache_time'] = 'default';
		$params['order'] = 'vod_addtime desc';	
		$params['limit'] = 2;
		$params['wd'] = $keyword;
		if( C('wx_cids') ){
			$params['cid'] = ''.C('wx_cids').'';
		}
		if( C('wx_order') ){
			$params['order'] = 'vod_'.trim(C('wx_order')).' desc';
		}
		$data = ff_mysql_vod($params);
		return $data;
	}
	
	// 微信接入认证
	private function checkSignature() {
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];
							
		$token = C('wx_token');
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
	
}
?>