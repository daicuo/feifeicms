<li class="col-xs-6">
  <p class="image">
    <a href="{:ff_url_read_special($feifei['list_id'],$feifei['list_dir'],$feifei['special_id'],$feifei['special_ename'])}">
      <img class="img-responsive img-thumbnail ff-img" data-original="{:ff_url_img($feifei['special_logo'])}" alt="{$feifei.special_name}">
    </a>
  </p>
  <h2 class="text-ellipsis">
    <a href="{:ff_url_read_special($feifei['list_id'],$feifei['list_dir'],$feifei['special_id'],$feifei['special_ename'])}" title="{$feifei.special_name}">{$feifei.special_name}</a>
  </h2>
</li>