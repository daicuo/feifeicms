<div class="container ff-bg">
<div class="media media-inc pt-2 pb-2">
	<div class="media-left hidden-xs hidden-sm">
		<img class="media-object img-thumbnail img-responsive ff-img" data-original="{$person_pic|ff_url_img}">
	</div>
	<div class="media-body">
		<h2 class="media-heading text-nowrap mb-3">
			<a href="{:ff_url('star/read',array('id'=>$person_id),true)}" title="{$person_name}">{$person_name}</a>
			<small class="text-gray">{$person_alias}</small>
		</h2>
		<div class="row">
			<div class="col-md-3 mb-2"><strong>星座：</strong>{$person_astrology|default='未填写'}</div>
			<div class="col-md-3 mb-2"><strong>生日：</strong>{$person_birthday|default='未填写'}</div>
			<div class="col-md-3 mb-2"><strong>地区：</strong>{$person_nationality|default='未填写'}</div>
			<div class="col-md-3 mb-2"><strong>职业：</strong>{$person_profession|default='未填写'}</div>
			<div class="col-md-3 mb-2"><strong>身高：</strong>{$person_height|default='未填写'}</div>
			<div class="col-md-3 mb-2"><strong>体重：</strong>{$person_weight|default='未填写'}</div>
			<div class="col-md-3 mb-2"><strong>血型：</strong>{$person_blood|default='未填写'}</div>
			<div class="col-md-3 mb-2"><strong>毕业院校：</strong>{$person_school|default='未填写'}</div>
			<div class="col-md-12"><strong>简介：</strong>{$person_intro|default='未填写'}</div>
		</div>
	</div>
</div>
</div>
<div class="clearfix mb-2"></div>
<div class="container ff-bg pt-2">
<ul class="nav nav-tabs mb-2">
	<li class="<eq name="action" value="read">active</eq>"><a href="{:ff_url('star/read',array('id'=>$person_id),true)}"><span class="glyphicon glyphicon-home text-green"></span> 推 荐</a></li>
  <li class="<eq name="action" value="ziliao">active</eq>"><a href="{:ff_url('star/ziliao',array('id'=>$person_id),true)}">个人资料</a></li>
	<li class="hidden-xs hidden-sm <eq name="action" value="zixun">active</eq>"><a href="{:ff_url('star/zixun',array('id'=>$person_id),true)}">星闻资讯</a></li>
	<li class="hidden-xs hidden-sm <eq name="action" value="forum">active</eq>"><a href="{:ff_url('star/forum',array('id'=>$person_id),true)}">话题讨论</a></li>
	<li class="hidden-xs hidden-sm <eq name="action" value="juese">active</eq>"><a href="{:ff_url('star/juese',array('id'=>$person_id),true)}">饰演角色</a></li>
	<li class="hidden-xs hidden-sm <eq name="action" value="dianying">active</eq>"><a href="{:ff_url('star/dianying',array('id'=>$person_id),true)}">电影</a></li>
	<li class="hidden-xs hidden-sm <eq name="action" value="dianshiju">active</eq>"><a href="{:ff_url('star/dianshiju',array('id'=>$person_id),true)}">电视剧</a></li>
	<li class="dropdown visible-xs visible-sm">
		<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
			<span>更多</span>
			<b class="caret text-green"></b>
		</a>
		<ul class="dropdown-menu">
			<li class="<eq name="action" value="zixun">active</eq>"><a href="{:ff_url('star/zixun',array('id'=>$person_id),true)}">星闻资讯</a></li>
			<li class="<eq name="action" value="forum">active</eq>"><a href="{:ff_url('star/forum',array('id'=>$person_id),true)}">话题讨论</a></li>
			<li class="<eq name="action" value="juese">active</eq>"><a href="{:ff_url('star/juese',array('id'=>$person_id),true)}">饰演角色</a></li>	
			<li class="<eq name="action" value="dianying">active</eq>"><a href="{:ff_url('star/dianying',array('id'=>$person_id),true)}">电影</a></li>
			<li class="<eq name="action" value="dianshiju">active</eq>"><a href="{:ff_url('star/dianshiju',array('id'=>$person_id),true)}">电视剧</a></li>
		</ul>
	</li>	
</ul>
</div>