<div class="media news-item-medial">
  <a class="media-left" href="{:ff_url_read_news($feifei['list_id'],$feifei['list_dir'],$feifei['news_id'],$feifei['news_name'],$feifei['news_jumpurl'],1)}">
  <img class="media-object img-thumbnail img-responsive ff-img" data-original="{:ff_url_img($feifei['news_pic'],$feifei['news_content'])}" title="{$feifei.news_name}">
  </a>
  <div class="media-body">
    <h4 class="media-heading">
      <a href="{:ff_url_read_news($feifei['list_id'],$feifei['list_dir'],$feifei['news_id'],$feifei['news_ename'],$feifei['news_jumpurl'])}">
      {$feifei.news_name|msubstr=0,36,true}
      </a>
    </h4>
    <p class="text-muted">
      {$feifei.news_addtime|date='Y/m/d',###}
    </p>
  </div>
</div>