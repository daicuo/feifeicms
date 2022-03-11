<volist name="playurl_line" id="feifei" key="sid">
<if condition="count($playurl_line) gt 1">
<div class="page-header ff-playurl-line">
  <h2 class="text-green">
	<span class="glyphicon glyphicon-facetime-video"></span>
	{$feifei.player_name_zh}
	</h2>
</div>
</if>
<ul class="list-unstyled row text-center ff-playurl-line ff-playurl" data-active="{$vod_id}-{$play_sid}-{$play_pid}" data-more="{$Think.config.ui_playurl|intval}">
  <volist name="feifei.son" id="feifeison" key="pid">
  <li class="col-xs-4" data-id="{$vod_id}-{$feifei.player_sid}-{$pid}"><a href="{:ff_url_play($list_id,$list_dir,$vod_id,$vod_ename,$feifei['player_sid'],$pid)}" class="btn btn-default btn-block btn-sm text-ellipsis"><if condition="(strlen($feifeison['title']) eq 8) and is_numeric($feifeison['title'])">{$feifeison.title|msubstr=2,5}<else/>{$feifeison.title|msubstr=0,6}</if></a></li>
  </volist>
</ul>
<div class="clearfix"></div>
</volist>
<empty name="playurl_line"><p>对不起，此视频还未添加观看地址。</p></empty>