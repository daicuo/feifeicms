<volist name="playurl_yugao" id="feifei" key="sid">
<if condition="count($playurl_yugao) gt 1">
<div class="page-header ff-playurl-yugao">
  <h2 class="text-green">
	<span class="glyphicon glyphicon-facetime-video"></span>
	{$feifei.player_name_zh}<sup>{$key+1}</sup>
	</h2>
</div>
<else/>
<div class="clearfix mb-2"></div>
</if>
<ul class="list-unstyled row text-center ff-playurl ff-playurl-yugao" data-active="{$vod_id}-{$play_sid}-{$play_pid}" data-more="{$Think.config.ui_playurl|intval}">
  <volist name="feifei.son" id="feifeison" key="pid">
  <li class="col-xs-4" data-id="{$vod_id}-{$feifei.player_sid}-{$pid}"><a href="{:ff_url_play($list_id,$list_dir,$vod_id,$vod_ename,$feifei['player_sid'],$pid)}" class="btn btn-default btn-block btn-sm text-ellipsis">{$feifeison.title|str_replace='片','',###}</a></li>
  </volist>
</ul>
<div class="clearfix"></div>
</volist>
<empty name="playurl_yugao"><p class="pt-2">对不起，此视频还未添加片花预告。</p></empty>