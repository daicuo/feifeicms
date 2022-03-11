<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/vod_play" />
</head>
<body class="vod-play">
<include file="BlockTheme:header" />
<include file="./Tpl/base/bootstrap3/vod_playurl" />
<div class="container ff-bg">
<div class="page-header">
  <h2 class="text-ellipsis">
    <span class="glyphicon glyphicon-film text-green"></span> 
    <a href="{:ff_url_vod_show($list_id,$list_dir,1)}">{$list_name}</a>
    <a href="{:ff_url_read_vod($list_id,$list_dir,$vod_id,$vod_ename,$vod_jumpurl)}" class="text-green">{$vod_name|msubstr=0,8}</a>
    <small>第{$play_pid}集 <a class="ff-playurl-error" href="javascript:;" data-id="{$play_id}" data-sid="{$play_sid}" data-pid="{$play_pid}" data-content="{$vod_name} 不能播放">报错</a></small>
		<label class="pull-right hidden-xs hidden-sm"><include file="./Tpl/base/bootstrap3/inc_share" /></label>
  </h2>
</div>
<div class="row ff-row">
  <div class="col-md-8 ff-col">
    <include file="./Tpl/base/bootstrap3/vod_player" />
    <div class="clearfix"></div>
    <ul class="list-unstyled ff-row ff-player-tool">
      <li class="col-sm-4 col-xs-7 ff-col"><include file="./Tpl/base/bootstrap3/vod_updown" /></li>
      <li class="col-sm-4 hidden-xs"><include file="./Tpl/base/bootstrap3/vod_score" /></li>
      <li class="col-sm-4 col-xs-5 ff-col text-right"><include file="./Tpl/base/bootstrap3/vod_playurl_next" /></li>
    </ul>
  </div>
  <div class="col-md-4 ff-col hidden-xs hidden-sm">
    <p class="text-center ff-ads ff-ads-300">
      {:ff_url_ads('300_300')}
    </p>
		<p class="ff-ads-btn">
			{:ff_url_ads('300_15')}
		</p>
    <div class="media">
      <a class="media-left" href="{:ff_url_read_vod($list_id,$list_dir,$vod_id,$vod_ename,$vod_jumpurl)}">
        <img class="media-object img-thumbnail img-responsive ff-img" data-original="{$vod_pic|ff_url_img=$vod_content}" alt="{$vod_name}免费观看">
      </a>
      <div class="media-body">
        <h5 class="text-nowrap">
          <a href="{:ff_url_read_vod($list_id,$list_dir,$vod_id,$vod_ename,$vod_jumpurl)}" title="{$vod_name}第{$play_pid}集在线观看">{$vod_name}</a>
        </h5>
        <dl class="dl-horizontal">
          <dt>主演：</dt>
          <dd class="text-nowrap text-mr-1"><include file="./Tpl/base/bootstrap3/vod_actor" /></dd>
          <dt>导演：</dt>
          <dd class="text-nowrap text-mr-1"><include file="./Tpl/base/bootstrap3/vod_director" /></dd>
					<dt>编剧：</dt>
					<dd class="text-nowrap text-mr-1"><include file="./Tpl/base/bootstrap3/vod_writer" /></dd>
          <dt>类型：</dt>
          <dd class="text-nowrap text-mr-1"><include file="./Tpl/base/bootstrap3/vod_type" /></dd>
          <dt>地区：</dt>
          <dd class="text-nowrap text-mr-1"><include file="./Tpl/base/bootstrap3/vod_area" /></dd>
          <dt>年份：</dt>
          <dd class="text-mr-1"><a href="{:ff_url('list/select',array('id'=>$list_id,'type'=>'','area'=>'','year'=>$vod_year,'star'=>'','state'=>'','order'=>'hits'),true)}">{$vod_year|default='2019'}</a></dd>
        </dl>
      </div>
    </div><!-- -->
  </div>
</div>
<div class="clearfix mb-4"></div>
<!-- -->
<eq name="play_name_en" value="yugao">
<include file="./Tpl/base/bootstrap3/vod_playurl_yugao_dropdown" />
<else/>
<include file="./Tpl/base/bootstrap3/vod_playurl_line_dropdown" />
</eq>
<!-- -->
<include file="BlockTheme:vod_inc_hot" />
<!-- -->
<include file="BlockTheme:vod_inc_actor" />
<!-- -->
<include file="BlockTheme:vod_inc_series" />
<!-- -->
<include file="./Tpl/base/bootstrap3/forum_ajax_vod" />
<!-- -->
<include file="./Tpl/base/bootstrap3/vod_record_set" />
{$vod_hits_insert}
</div><!--container end -->
<div class="clearfix mb-2"></div>
<div class="container ff-bg">
  <include file="BlockTheme:footer" />
</div>
</body>
</html>