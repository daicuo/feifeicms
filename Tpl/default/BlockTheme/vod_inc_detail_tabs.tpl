<div class="media vod-base">
  <div class="media-left">
    <a class="vod-pic" href="{:ff_url_read_vod($list_id,$list_dir,$vod_id,$vod_ename,$vod_jumpurl)}">
      <img class="media-object img-thumbnail ff-img" data-original="{$vod_pic|ff_url_img=$vod_content}" alt="{$vod_name}免费观看">
    </a>
  </div>
  <div class="media-body">
    <dl class="dl-horizontal">
      <dt>主演：</dt>
      <dd class="text-nowrap text-mr-1"><include file="./Tpl/base/bootstrap3/vod_actor" /></dd>
      <dt>导演：</dt>
      <dd class="text-nowrap text-mr-1">
      	{$vod_director|ff_url_search='director'}
      </dd>
      <dt>地区：</dt>
      <dd class="text-nowrap text-mr-1">
      	<include file="./Tpl/base/bootstrap3/vod_area" />
      </dd>
      <dt>年份：</dt>
      <dd class="text-nowrap text-mr-1">
      	<a href="{:ff_url('list/select',array('id'=>$list_id,'type'=>'','area'=>'','year'=>$vod_year,'star'=>'','state'=>'','order'=>'hits'),true)}">{$vod_year|default='2019'}</a>
      </dd>
      <dt>人气：</dt>
      <dd class="text-nowrap text-mr-1">
				{$vod_hits}
      </dd>
    </dl>
  </div>
</div>
<include file="./Tpl/base/bootstrap3/vod_playurl" />
<ul class="nav nav-tabs">
  <li class="<eq name="action" value="juqing">active</eq>"><a href="{:ff_url('vod/juqing',array('id'=>$vod_id),true)}">剧情介绍</a></li>
	<li class="<eq name="action" value="yanyuan">active</eq>"><a href="{:ff_url('vod/yanyuan',array('id'=>$vod_id),true)}">演员表</a></li>
	<li class="hidden-xs hidden-sm <eq name="action" value="zixun">active</eq>"><a href="{:ff_url('vod/zixun',array('id'=>$vod_id),true)}">新闻资讯</a></li>
	<li class="hidden-xs hidden-sm <eq name="action" value="taici">active</eq>"><a href="{:ff_url('vod/taici',array('id'=>$vod_id),true)}">经典台词</a></li>
	<li class="hidden-xs hidden-sm <eq name="action" value="forum">active</eq>"><a href="{:ff_url('vod/forum',array('id'=>$vod_id),true)}">精彩影评</a></li>
	<li class="hidden-xs hidden-sm <eq name="action" value="pingfen">active</eq>"><a href="{:ff_url('vod/pingfen',array('id'=>$vod_id),true)}">评分</a></li>
	<li class="hidden-xs hidden-sm <eq name="action" value="kandian">active</eq>"><a href="{:ff_url('vod/kandian',array('id'=>$vod_id),true)}">看点</a></li>
	<li class="hidden-xs hidden-sm <eq name="action" value="shoubo">active</eq>"><a href="{:ff_url('vod/shoubo',array('id'=>$vod_id),true)}">播出时间</a></li>
	<li class="hidden-xs hidden-sm <eq name="action" value="jieju">active</eq>"><a href="{:ff_url('vod/jieju',array('id'=>$vod_id),true)}">大结局</a></li>
	<li class="hidden-xs hidden-sm <eq name="action" value="yugao">active</eq>"><a href="{:ff_url('vod/yugao',array('id'=>$vod_id),true)}">预告片</a></li>
	<li class="hidden-xs hidden-sm <eq name="action" value="xiazai">active</eq>"><a href="{:ff_url('vod/xiazai',array('id'=>$vod_id),true)}">下载地址</a></li>
	<li class="dropdown visible-xs visible-sm <in name="action" value="zixun,taici,forum,pingfen,kandian,shoubo,jieju,yugao,xiazai">active</in>">
		<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
			<span>更多</span>
			<b class="caret text-green"></b>
		</a>
		<ul class="dropdown-menu">
		<li class="<eq name="action" value="zixun">active</eq>"><a href="{:ff_url('vod/zixun',array('id'=>$vod_id),true)}">新闻资讯</a></li>
		<li class="<eq name="action" value="taici">active</eq>"><a href="{:ff_url('vod/taici',array('id'=>$vod_id),true)}">经典台词</a></li>
		<li class="<eq name="action" value="forum">active</eq>"><a href="{:ff_url('vod/forum',array('id'=>$vod_id),true)}">精彩影评</a></li>
		<li class="<eq name="action" value="pingfen">active</eq>"><a href="{:ff_url('vod/pingfen',array('id'=>$vod_id),true)}">评分</a></li>
		<li class="<eq name="action" value="kandian">active</eq>"><a href="{:ff_url('vod/kandian',array('id'=>$vod_id),true)}">看点</a></li>
		<li class="<eq name="action" value="shoubo">active</eq>"><a href="{:ff_url('vod/shoubo',array('id'=>$vod_id),true)}">播出时间</a></li>
		<li class="<eq name="action" value="jieju">active</eq>"><a href="{:ff_url('vod/jieju',array('id'=>$vod_id),true)}">大结局</a></li>
		<li class="<eq name="action" value="yugao">active</eq>"><a href="{:ff_url('vod/yugao',array('id'=>$vod_id),true)}">预告片</a></li>
		<li class="<eq name="action" value="xiazai">active</eq>"><a href="{:ff_url('vod/xiazai',array('id'=>$vod_id),true)}">下载地址</a></li>		
		</ul>
	</li>	
</ul>
