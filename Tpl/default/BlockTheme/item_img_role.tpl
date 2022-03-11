<li class="col-md-2 col-sm-3 col-xs-4 mb-1">
	<a class="image" href="{:ff_url_read_role($feifei['list_id'],$feifei['list_dir'],$feifei['person_id'],$feifei['person_ename'],$feifei['person_jumpurl'])}">
		<img class="img-responsive img-thumbnail ff-img h-y-130" data-original="{:ff_url_img($feifei['person_pic'])}" alt="{$feifei.person_name}">
		<p class="ff-txt text-white">
			<span class="glyphicon glyphicon-eye-open"></span>
			{$feifei.person_hits|number_format|default=99}
		</p>
	</a>
  <h5 class="title text-ellipsis pl-1 text-dark">
    <a href="{:ff_url_read_role($feifei['list_id'],$feifei['list_dir'],$feifei['person_id'],$feifei['person_ename'],$feifei['person_jumpurl'])}" title="{$feifei.person_name}">{$feifei.person_name|msubstr=0,8}（<span class="text-gray">{$feifei.person_father_name|msubstr=0,4,true} 饰</span>）</a>
  </h5>
</li>