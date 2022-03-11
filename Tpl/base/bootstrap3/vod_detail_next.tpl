<php>
$item_prev = ff_array(ff_mysql_vod(array(
	'cid'=>$vod_cid,
	'id_max'=> $vod_id,
	'order'=> 'vod_id',
	'sort'=> 'desc',
	'limit'=> '1',
	'status'=> '1',	
	'cache_name'=> 'default',
	'cache_time'=> 'default',	
	'field'=>'list_id,list_dir,vod_id,vod_ename,vod_name,vod_jumpurl'
)));
$item_next = ff_array(ff_mysql_vod(array(
	'cid'=>$vod_cid,
	'id_min'=> $vod_id,
	'order'=> 'vod_id',
	'sort'=> 'asc',
	'limit'=> '1',
	'status'=> '1',
	'cache_name'=> 'default',
	'cache_time'=> 'default',	
	'field'=>'list_id,list_dir,vod_id,vod_ename,vod_name,vod_jumpurl'
)));
</php>
<div class="btn-group">
<a id="ff-prev" href="{:ff_url_read_vod($item_prev['list_id'],$item_prev['list_dir'],$item_prev['vod_id'],$item_prev['vod_ename'],$item_prev['vod_jumpurl'])}" class="btn btn-default btn-sm <empty name="item_prev">disabled</empty>"><span class="glyphicon glyphicon-chevron-left ml-2 mr-1"></span></a>
<a id="ff-next" href="{:ff_url_read_vod($item_next['list_id'],$item_next['list_dir'],$item_next['vod_id'],$item_next['vod_ename'],$item_next['vod_jumpurl'])}" class="btn btn-success btn-sm <empty name="item_next">disabled</empty>"><span class="glyphicon glyphicon-chevron-right ml-1 mr-2"></span></a>
</div>