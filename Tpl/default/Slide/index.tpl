<php>$item_slide = ff_mysql_slide('limit:'.C('ui_slide_max').';cache_name:default;cache_time:default;order:slide_oid;sort:asc');</php>
<div class="carousel ff-slide" id="ff-slide" data-interval="{$Think.config.ui_slide_index}">
  <!-- 轮播（Carousel）指标 -->
  <ol class="carousel-indicators">
    <volist name="item_slide" id="feifei" offset="0" length="4">
    <eq name="key" value="0">
    <li data-target="#ff-slide" data-slide-to="{$key}" class="active"></li>
    <else/>
    <li data-target="#ff-slide" data-slide-to="{$key}"></li>
    </eq>
    </volist>
  </ol>   
  <!-- 轮播（Carousel）项目 -->
  <div class="carousel-inner">
    <volist name="item_slide" id="feifei" offset="0" length="4">
    <eq name="key" value="0">
      <div class="item active">
        <a href="{$feifei.slide_url}">
        <img src="{$feifei.slide_pic|ff_url_img}" alt="{$feifei.slide_name}">
        <div class="carousel-caption">{$feifei.slide_name}</div>
        </a>
      </div>
    <else/>
      <div class="item">
        <a href="{$feifei.slide_url}">
        <img src="{$feifei.slide_pic|ff_url_img}" alt="{$feifei.slide_name}">
        <div class="carousel-caption">{$feifei.slide_name}</div>
        </a>
      </div>
    </eq>
    </volist>
  </div>
  <!-- 轮播（Carousel）导航 -->
  <a class="carousel-control left" href="#ff-slide" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
  <a class="carousel-control right" href="#ff-slide" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
</div>