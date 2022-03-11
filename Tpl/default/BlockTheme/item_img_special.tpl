<li class="col-md-2 col-sm-3 col-xs-6">
  <p class="image">
    <a href="{:ff_url('special/read', array('id'=>$feifei['special_id']), true)}">
      <img class="img-responsive img-thumbnail ff-img" data-original="{:ff_url_img($feifei['special_logo'])}" alt="{$feifei.special_name}">
    </a>
  </p>
  <h2 class="text-ellipsis">
    <a href="{:ff_url('special/read', array('id'=>$feifei['special_id']), true)}">{$feifei.special_name}</a>
  </h2>
</li>