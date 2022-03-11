<li class="col-xs-4 mb-1">
	<p class="image">
    <a href="{:ff_url_read_star($feifei['list_id'],$feifei['list_dir'],$feifei['person_id'],$feifei['person_ename'])}">
      <img class="img-responsive img-thumbnail ff-img" data-original="{:ff_url_img($feifei['person_pic'])}" alt="{$feifei.person_name}">
			<span class="continu">{$feifei.person_gold|ff_gold}分</span>
    </a>
  </p>
  <h2 class="text-ellipsis">
     <a href="{:ff_url_read_star($feifei['list_id'],$feifei['list_dir'],$feifei['person_id'],$feifei['person_ename'])}" title="{$feifei.person_name}">{$feifei.person_name}</a>
  </h2>
</li>