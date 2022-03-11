<div class="container ff-bg vod-detail-inc">
  <div class="media pt-1 pb-1">
    <div class="media-left">
      <a href="{:ff_url_read_vod($list_id,$list_dir,$vod_id,$vod_ename,$vod_jumpurl)}">
        <img class="media-object img-thumbnail ff-img" data-original="{$vod_pic|ff_url_img=$vod_content}" alt="{$vod_name}免费观看">
      </a>
    </div>
    <div class="media-body">
      <h2 class="text-ellipsis mt-0">
        <a class="text-green" href="{:ff_url_read_vod($list_id,$list_dir,$vod_id,$vod_ename,$vod_jumpurl)}">{$vod_name}</a>
        <small><include file="./Tpl/base/bootstrap3/vod_continu" /></small>
      </h2>
      <dl class="dl-horizontal">
        <dt>主演：</dt>
        <dd class="text-mr-1 text-nowrap"><include file="./Tpl/base/bootstrap3/vod_actor" /></dd>
        <dt>导演：</dt>
        <dd class="text-mr-1 text-nowrap"><include file="./Tpl/base/bootstrap3/vod_director" /></dd>
        <dt>类型：</dt>
        <dd class="text-mr-1 text-nowrap"><include file="./Tpl/base/bootstrap3/vod_type" /></dd>
        <dt>地区：</dt>
        <dd class="text-mr-1 text-nowrap"><include file="./Tpl/base/bootstrap3/vod_area" /></dd>
        <dt>年份：</dt>
        <dd class="text-mr-1 text-nowrap"><include file="./Tpl/base/bootstrap3/vod_year" /></dd>
				<dt class="dt-more">更多：</dt>
        <dd></dd>	
      </dl>
    </div>
  </div>
	<div class="btn-group dd-more">
		<button type="button" class="btn btn-link btn-xs text-green pt-0 pl-0 dropdown-toggle" data-toggle="dropdown">
			<span class="glyphicon glyphicon-chevron-down"></span>
		</button>
		<ul class="dropdown-menu">
			<li <eq name="action" value="read">class="active"</eq>><a href="{:ff_url_read_vod($list_id,$list_dir,$vod_id,$vod_ename,$vod_jumpurl)}">在线观看</a></li>
			<li <eq name="action" value="yanyuan">class="active"</eq>><a href="{:ff_url('vod/yanyuan',array('id'=>$vod_id),true)}">演员表</a></li>
			<li <eq name="action" value="yugao">class="active"</eq>><a href="{:ff_url('vod/yugao',array('id'=>$vod_id),true)}">片花预告</a></li>
			<li <eq name="action" value="xiazai">class="active"</eq>><a href="{:ff_url('vod/xiazai',array('id'=>$vod_id),true)}">下载观看</a></li>
			<li class="divider"></li>
			<li <eq name="action" value="forum">class="active"</eq>><a href="{:ff_url('vod/forum',array('id'=>$vod_id),true)}">精彩影评</a></li>
			<li <eq name="action" value="zixun">class="active"</eq>><a href="{:ff_url('vod/zixun',array('id'=>$vod_id),true)}">新闻资讯</a></li>
			<li <eq name="action" value="juqing">class="active"</eq>><a href="{:ff_url('vod/juqing',array('id'=>$vod_id),true)}">剧情介绍</a></li>				
			<li <eq name="action" value="taici">class="active"</eq>><a href="{:ff_url('vod/taici',array('id'=>$vod_id),true)}">经典台词</a></li>
			<li class="divider"></li>
			<li <eq name="action" value="kandian">class="active"</eq>><a href="{:ff_url('vod/kandian',array('id'=>$vod_id),true)}">影片看点</a></li>
			<li <eq name="action" value="pingfen">class="active"</eq>><a href="{:ff_url('vod/pingfen',array('id'=>$vod_id),true)}">影片评分</a></li>
			<li <eq name="action" value="shoubo">class="active"</eq>><a href="{:ff_url('vod/shoubo',array('id'=>$vod_id),true)}">首播/首映</a></li>
			<li <eq name="action" value="jieju">class="active"</eq>><a href="{:ff_url('vod/jieju',array('id'=>$vod_id),true)}">大结局</a></li>
		</ul>
	</div>
</div>
<div class="clearfix mb-1"></div>