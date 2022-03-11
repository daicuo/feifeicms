<li class="col-xs-4 mb-1">
	<p class="image">
    <a href="{:ff_url_read_role($feifei['list_id'],$feifei['list_dir'],$feifei['person_id'],$feifei['person_ename'])}">
      <img class="img-responsive img-thumbnail ff-img" data-original="{:ff_url_img($feifei['person_pic'])}" alt="{$feifei.person_name}">
    </a>
  </p>
  <h2 class="text-ellipsis">
    <a href="{:ff_url_read_role($feifei['list_id'],$feifei['list_dir'],$feifei['person_id'],$feifei['person_ename'])}" title="{$feifei.person_name}">{$feifei.person_name}</a>
  </h2>
	<h4 class="text-ellipsis text-gray">
		{$feifei.person_father_name} 饰
	</h4>
</li>