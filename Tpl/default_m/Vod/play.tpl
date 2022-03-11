<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/vod_play" />
</head>
<body class="vod-play">
<include file="BlockTheme:header" />
<include file="./Tpl/base/bootstrap3/vod_playurl" />
<div class="container ff-bg">
  <ul class="list-unstyled ff-row">
    <include file="./Tpl/base/bootstrap3/vod_player" />
  </ul>
  <ul class="list-unstyled ff-row ff-player-tool">
    <li class="col-xs-7 ff-col"><include file="./Tpl/base/bootstrap3/vod_updown" /></li>
    <li class="col-xs-5 ff-col text-right"><include file="./Tpl/base/bootstrap3/vod_playurl_next" /></li>
  </ul>
</div>
<div class="clearfix mb-1"></div>
<!-- -->
<div class="container ff-bg pt-0 pr-0 pb-0 pl-0">	
	{:ff_url_ads('300_15m')}
</div>
<div class="clearfix mb-1"></div>
<!-- -->
<div class="container ff-bg">
<eq name="play_name_en" value="yugao">
<include file="./Tpl/base/bootstrap3/vod_playurl_yugao_dropdown_m" />
<else/>
<include file="./Tpl/base/bootstrap3/vod_playurl_line_dropdown_m" />
</eq>
</div>
<div class="clearfix mb-1"></div>
<!-- -->
<div class="clearfix mb-1"></div>
<include file="BlockTheme:vod_inc_info" />
<!-- -->
<include file="BlockTheme:vod_inc_hot" />
<!-- -->
<include file="BlockTheme:vod_inc_actor" />
<!-- -->
<include file="BlockTheme:vod_inc_series" />
<!-- -->
<div class="container ff-bg">
<include file="./Tpl/base/bootstrap3/forum_ajax_vod" />
</div><!--container end -->
{$vod_hits_insert}
<include file="./Tpl/base/bootstrap3/vod_record_set" />
<div class="clearfix mb-1"></div>
<include file="BlockTheme:footer" />
</body>
</html>