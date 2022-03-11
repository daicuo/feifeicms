<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/vod_detail" />
</head>
<body class="vod-detail">
<include file="BlockTheme:header" />
<include file="BlockTheme:vod_inc_info" />
<include file="./Tpl/base/bootstrap3/vod_playurl" />
<div class="container ff-bg">
  <ul class="nav nav-tabs nav-tabs-play">
    <li class="text-center active">
      <a href="javascript:;" data-target=".vod-nav-play" data-toggle="tab"><span class="glyphicon glyphicon-film"></span> 在线播放</a>
    </li>
    <li class="text-center">
      <a href="javascript:;" data-target=".vod-nav-content" data-toggle="tab"><span class="glyphicon glyphicon-th-large"></span> 剧情简介</a>
    </li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane fade in active vod-nav-play">
			<include file="./Tpl/base/bootstrap3/vod_playurl_line_m" />
    </div>
    <div class="tab-pane fade vod-nav-content">
      {:ff_url_tags_content(nb(strip_tags($vod_content,"<a>")),$Tag)}
    </div>
  </div>
</div>
<!-- -->
<div class="clearfix mb-1"></div>
<include file="BlockTheme:vod_inc_hot" />
<!--container end -->
<include file="BlockTheme:footer" />
</body>
</html>