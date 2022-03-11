<php>$item_slide = ff_mysql_vod('field:list_id,list_dir,vod_id,vod_name,vod_ename,vod_jumpurl,vod_pic_slide;cid:'.$list_id.';limit:'.C('ui_slide_max').';pic_slide:true;cache_name:default;cache_time:default;order:vod_stars desc,vod_id;sort:desc');</php>
<div class="carousel ff-slide" id="ff-slide" data-interval="{$Think.config.ui_slide_index}">
  <!-- 轮播（Carousel）指标 -->
  <ol class="carousel-indicators">
    <volist name="item_slide" id="feifei">
    <eq name="key" value="0">
    <li data-target="#ff-slide" data-slide-to="{$key}" class="active"></li>
    <else/>
    <li data-target="#ff-slide" data-slide-to="{$key}"></li>
    </eq>
    </volist>
  </ol>   
  <!-- 轮播（Carousel）项目 -->
  <div class="carousel-inner">
    <volist name="item_slide" id="feifei">
    <eq name="key" value="0">
      <div class="item active">
        <a href="{:ff_url_read_vod($feifei['list_id'],$feifei['list_dir'],$feifei['vod_id'],$feifei['vod_ename'],$feifei['vod_jumpurl'])}">
        <img src="{$feifei.vod_pic_slide|ff_url_img}" alt="{$feifei.vod_name}">
        <div class="carousel-caption">{$feifei.vod_name}</div>
        </a>
      </div>
    <else/>
      <div class="item">
        <a href="{:ff_url_read_vod($feifei['list_id'],$feifei['list_dir'],$feifei['vod_id'],$feifei['vod_ename'],$feifei['vod_jumpurl'])}">
        <img src="{$feifei.vod_pic_slide|ff_url_img}" alt="{$feifei.vod_name}">
        <div class="carousel-caption">{$feifei.vod_name}</div>
        </a>
      </div>
    </eq>
    </volist>
  </div>
  <!-- 轮播（Carousel）导航 -->
  <a class="carousel-control left" href="#ff-slide" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
  <a class="carousel-control right" href="#ff-slide" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
</div>