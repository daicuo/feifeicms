<!--弹出框展示观看记录 -->
<notempty name="vod_ids">
	<php>
	$item_vod = ff_mysql_vod('ids:'.$vod_ids.';field:list_id,list_dir,vod_id,vod_ename,vod_name,vod_play,vod_server,vod_url;cache_name:default;cache_time:default;order:vod_id;sort:desc');
	$array_vod = array();
	foreach($item_vod as $key=>$value){
		$array_vod[$value['vod_id']] = $value;
	}
	unset($item_vod);
	$array_id = explode(',',$vod_ids);
	krsort($array_id);//倒序按最后时间
	foreach($array_id as $key=>$value){
		$item_vod[$value] = $array_vod[$value];
		$item_vod[$value]['sid'] = $vod_json['vod'][$value]['sid'];
		$item_vod[$value]['pid'] = $vod_json['vod'][$value]['pid'];
	}
	</php>
  <ol class="ff-record">
  <volist name="item_vod" id="feifei">
  <li><a href="{:str_replace('video/eplay/',$feifei['list_dir'].'/',ff_url_play($feifei['list_id'],$feifei['list_dir'],$feifei['vod_id'],$feifei['vod_ename'],$feifei['sid'],$feifei['pid']))}" title="{$feifei.vod_name}">{$feifei.vod_name|msubstr=0,12,true} 第{$feifei.pid}集</a></li>
  </volist>
  </ol>
  <gt name="site_user_id" value="0"><p class="ff-record-more text-center"><a href="{:ff_url('user/center',array('action'=>'history'))}"><span class="glyphicon glyphicon-time"></span> 更多观看记录</a></p></gt>
<else/>
	<gt name="site_user_id" value="0">
		<p class="ff-record-more text-center"><a class="ff-user user-login-modal" href="{:ff_url('user/login')}"><span class="glyphicon glyphicon-time"></span> 暂无观看记录</a></p>
	<else/>
		<p class="ff-record-more text-center">暂无观看记录</p>
	</gt>
</notempty>