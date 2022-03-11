<li class="col-md-2 col-sm-3 col-xs-4">
	<p class="image">
    <a href="{:ff_url_read_vod($feifei['list_id'],$feifei['list_dir'],$feifei['vod_id'],$feifei['vod_ename'],$feifei['vod_jumpurl'])}">
      <img class="img-responsive img-thumbnail ff-img" data-original="{:ff_url_img($feifei['vod_pic'],$feifei['vod_content'])}" alt="{$feifei.vod_name}">
      <span class="continu"><include file="./Tpl/base/bootstrap3/vod_continu_foreach" /></span>
      <i class="playbg"></i>
      <i class="playbtn"></i>
    </a>
  </p>
  <h2 class="text-ellipsis text-mr-1">
    <a href="{:ff_url_read_vod($feifei['list_id'],$feifei['list_dir'],$feifei['vod_id'],$feifei['vod_ename'],$feifei['vod_jumpurl'])}" title="{$feifei.vod_name}">{$feifei.vod_name}</a>
  </h2>
  <h4 class="text-nowrap text-mr-1">
  	<notempty name="feifei.vod_actor">
      <include file="./Tpl/base/bootstrap3/vod_actor_foreach" />
     <else/>
     	<include file="./Tpl/base/bootstrap3/vod_type_foreach" />
      <include file="./Tpl/base/bootstrap3/vod_area_foreach" />
    </notempty>
  </h4>
</li>