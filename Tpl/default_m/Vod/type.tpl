<php>$item_vod = ff_mysql_vod('cid:'.$select_id.';limit:60;tag_name:'.$select_type.';tag_list:vod_type;area:'.$select_area.';year:'.implode(',',str_split($select_year,4)).';actor:'.$select_star.';state:'.$select_state.';page_is:true;page_id:type;page_p:'.$select_page.';cache_name:default;cache_time:default;order:vod_'.$select_order.';sort:desc');
$params = array('id'=>$select_id,'type'=>urlencode($select_type),'area'=>urlencode($select_area),'year'=>$select_year,'star'=>urlencode($select_star),'state'=>urlencode($select_state),'order'=>$select_order,'p'=>'FFLINK');
$page = ff_url_page('list/select', $params, true, 'type', 4);
$totalpages = ff_page_count('type', 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/vod_type" />
</head>
<body class="vod-type">
<include file="BlockTheme:header" />
<div class="container ff-bg">
	<div class="dl-fixed">
  <div class="page-header text-nowrap">
    <h2><span class="glyphicon glyphicon-film text-green"></span> {$list_name}
    <small>{$select_type} {$select_area} {$select_year} {$select_star} {$select_state} 共有<label class="text-green">{:ff_page_count('type', 'records')}</label>个影片  第<span class="text-green">{$select_page}</span>页</small>
    </h2>
  </div>
  <div class="clearfix"></div>
  <dl class="dl-horizontal">
    <dt>频道：</dt>
    <dd class="text-nowrap ff-gallery">
    <volist name=":ff_mysql_list('sid:1;limit:12;cahce_name:default;cahce_time:default;order:list_pid asc,list_oid;sort:asc')" id="feifei"><a href="{:ff_url('list/select',array('id'=>$feifei['list_id'],'type'=>'','area'=>'','year'=>'','star'=>'','state'=>'','order'=>'addtime','p'=>1),true)}" class="gallery-cell" id="id{:md5($feifei['list_id'])}">{$feifei.list_name}</a></volist></dd>
    <dt>类型：</dt>
    <dd class="text-nowrap text-mr-1 ff-gallery">
    <a href="{:ff_url('list/select',array('id'=>$select_id,'type'=>'','area'=>urlencode($select_area),'year'=>$select_year,'star'=>urlencode($select_star),'state'=>urlencode($select_state),'order'=>$select_order,'p'=>1),true)}" class="gallery-cell" id="type{:md5('')}">全部</a>
    <volist name=":explode(',',$list_extend['type'])" id="feifei" offset="0" length='15'><a href="{:ff_url('list/select',array('id'=>$select_id,'type'=>urlencode($feifei),'area'=>urlencode($select_area),'year'=>$select_year,'star'=>urlencode($select_star),'state'=>urlencode($select_state),'order'=>$select_order,'p'=>1),true)}" class="gallery-cell" id="type{:md5($feifei)}">{$feifei}</a></volist></dd>
		<notempty name="list_extend.area">
    <dt>地区：</dt>
    <dd class="text-nowrap ff-gallery">
    <a href="{:ff_url('list/select',array('id'=>$select_id,'type'=>urlencode($select_type),'area'=>'','year'=>$select_year,'star'=>urlencode($select_star),'state'=>urlencode($select_state),'order'=>$select_order,'p'=>1),true)}" class="gallery-cell" id="area{:md5('')}">全部</a>
    <volist name=":explode(',',$list_extend['area'])" id="feifei" offset="0" length='15'><a href="{:ff_url('list/select',array('id'=>$select_id,'type'=>urlencode($select_type),'area'=>urlencode($feifei),'year'=>$select_year,'star'=>urlencode($select_star),'state'=>urlencode($select_state),'order'=>$select_order,'p'=>1),true)}" class="gallery-cell" id="area{:md5($feifei)}">{$feifei}</a></volist></dd>
		</notempty>
		<notempty name="list_extend.year">
    <dt>年代：</dt>
    <dd class="text-nowrap ff-gallery">
    <a href="{:ff_url('list/select',array('id'=>$select_id,'type'=>urlencode($select_type),'area'=>urlencode($select_area),'year'=>'','star'=>urlencode($select_star),'state'=>urlencode($select_state),'order'=>$select_order,'p'=>1),true)}" class="gallery-cell" id="year{:md5('')}">全部</a>
    <volist name=":explode(',',$list_extend['year'])" id="feifei" offset="0" length="10"><a href="{:ff_url('list/select',array('id'=>$select_id,'type'=>urlencode($select_type),'area'=>urlencode($select_area),'year'=>$feifei,'star'=>urlencode($select_star),'state'=>urlencode($select_state),'order'=>$select_order,'p'=>1),true)}" class="gallery-cell" id="year{:md5($feifei)}">{$feifei}</a></volist><a href="{:ff_url('list/select',array('id'=>$select_id,'type'=>urlencode($select_type),'area'=>urlencode($select_area),'year'=>'20002010','star'=>urlencode($select_star),'state'=>urlencode($select_state),'order'=>$select_order,'p'=>1),true)}" class="gallery-cell" id="year{:md5('20002010')}">2010-2000</a><a href="{:ff_url('list/select',array('id'=>$select_id,'type'=>urlencode($select_type),'area'=>urlencode($select_area),'year'=>'19901999','star'=>urlencode($select_star),'state'=>urlencode($select_state),'order'=>$select_order,'p'=>1),true)}" class="gallery-cell" id="year{:md5('19901999')}">90年代</a><a href="{:ff_url('list/select',array('id'=>$select_id,'type'=>urlencode($select_type),'area'=>urlencode($select_area),'year'=>'18001989','star'=>urlencode($select_star),'state'=>urlencode($select_state),'order'=>$select_order,'p'=>1),true)}" class="gallery-cell" id="year{:md5('18001989')}">更早</a></dd>
		</notempty>
  </dl>
  </div> 
  <!-- -->
  <div class="clearfix mb-1"></div>
  <div class="btn-toolbar" role="toolbar">
    <div class="btn-group btn-group-sm">
      <a href="{:ff_url('list/select',array('id'=>$select_id,'type'=>urlencode($select_type),'area'=>urlencode($select_area),'year'=>$select_year,'star'=>urlencode($select_star),'state'=>urlencode($select_state),'order'=>hits,'p'=>1),true)}" class="btn btn-default" id="orderhits">最近热播</a>
      <a href="{:ff_url('list/select',array('id'=>$select_id,'type'=>urlencode($select_type),'area'=>urlencode($select_area),'year'=>$select_year,'star'=>urlencode($select_star),'state'=>urlencode($select_state),'order'=>addtime,'p'=>1),true)}" class="btn btn-default" id="orderaddtime">最新上映</a>
      <a href="{:ff_url('list/select',array('id'=>$select_id,'type'=>urlencode($select_type),'area'=>urlencode($select_area),'year'=>$select_year,'star'=>urlencode($select_star),'state'=>urlencode($select_state),'order'=>up,'p'=>1),true)}" class="btn btn-default" id="orderup">点赞最多</a>
    </div>
  </div>
  <div class="clearfix mb-1"></div>
	<div class="clearfix mb-1"></div>
  <script>
	$("#id{$select_id|md5}").addClass("text-green gallery-active");
	$("#type{$select_type|md5}").addClass("text-green gallery-active");
	$("#area{$select_area|md5}").addClass("text-green gallery-active");
	$("#year{$select_year|md5}").addClass("text-green gallery-active");
	$("#order{$select_order}").addClass("active");
	</script>
  <!-- -->
  <ul class="list-unstyled vod-item-img ff-img-140">
    <volist name="item_vod" id="feifei">
    <include file="BlockTheme:item_img_vod" />
    </volist>
  </ul>
  <gt name="totalpages" value="1">
  <div class="clearfix"></div>
  <div class="text-center">
    <ul class="pager">
      <gt name="page.totalpages" value="1">
        <php>$params['p'] = $select_page-1</php>
        <li><a id="ff-prev" href="{:ff_url('list/select', $params, true)}">上一页</a></li>
      </gt>
      <lt name="list_page" value="$totalpages">
        <php>$params['p'] = $select_page+1</php>
        <li><a id="ff-next" href="{:ff_url('list/select', $params, true)}">下一页</a></li>
      </lt>
    </ul>
  </div>
  </gt>
</div>
<!--container end -->
<include file="BlockTheme:footer" />
</body>
</html>