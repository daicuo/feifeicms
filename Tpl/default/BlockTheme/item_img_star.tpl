<li class="col-md-2 col-sm-3 col-xs-4 mb-1">
	<a class="image" href="{:ff_url_read_star($feifei['list_id'],$feifei['list_dir'],$feifei['person_id'],$feifei['person_ename'])}">
		<img class="img-responsive img-thumbnail ff-img h-y-130" data-original="{:ff_url_img($feifei['person_pic'])}" alt="{$feifei.person_name}">
		<p class="ff-txt text-white">
			{$feifei.person_gold|ff_gold}分
		</p>
	</a>
  <h5 class="title text-ellipsis pl-1">
    <a href="{:ff_url_read_star($feifei['list_id'],$feifei['list_dir'],$feifei['person_id'],$feifei['person_ename'])}" title="{$feifei.person_name}">{$feifei.person_name|msubstr=0,8,true}</a>
  </h5>
</li>