<li class="col-md-2 col-sm-3 col-xs-6">
	<p class="image">
    <a href="{:ff_url_read_vod($feifei['list_id'],$feifei['list_dir'],$feifei['vod_id'],$feifei['vod_ename'],$feifei['vod_jumpurl'])}">
      <img class="img-responsive img-thumbnail ff-img" data-original="{:ff_url_img($feifei['vod_pic'],$feifei['vod_content'])}" alt="{$feifei.vod_name}">
      <span class="continu">{$feifei.vod_length|default=99|ff_Second2Length}</span>
      <i class="playbg"></i>
      <i class="playbtn"></i>
    </a>
  </p>
  <h4 class="text-mr-1">
    <a href="{:ff_url_read_vod($feifei['list_id'],$feifei['list_dir'],$feifei['vod_id'],$feifei['vod_ename'],$feifei['vod_jumpurl'])}" title="{$feifei.vod_name}">{$feifei.vod_name|msubstr=0,22,true}</a>
  </h4>
</li>