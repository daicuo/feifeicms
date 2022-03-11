<!--在线观看TAB标签组件 -->
<notempty name="playurl_line">
<php>$play_count = count($playurl_line);</php>
<ul class="nav nav-tabs ff-playurl-tab" data-active=".ff-playurl-tab-{$play_sid}">
<volist name="playurl_line" id="feifei" key="n" offset="0" length="1">
	<li class="active">
	<a href="javascript:;" data-target=".ff-playurl-tab-{$feifei.player_sid}" data-toggle="tab">
	<span class="glyphicon glyphicon-film text-green"></span>
	{$feifei.player_name_zh}
	</a>
	</li>
</volist>
<volist name="playurl_line" id="feifei" key="n" offset="1" length="1">
	<li class="">
	<a href="javascript:;" data-target=".ff-playurl-tab-{$feifei.player_sid}" data-toggle="tab">
	<span class="glyphicon glyphicon-film text-green"></span>
	{$feifei.player_name_zh}
	</a>
	</li>
</volist>
<volist name="playurl_line" id="feifei" key="n" offset="2" length="6">
	<li class="hidden-xs hidden-sm">
		<a href="javascript:;" data-target=".ff-playurl-tab-{$feifei.player_sid}" data-toggle="tab">
		<span class="glyphicon glyphicon-film text-green"></span>
		{$feifei.player_name_zh}
		</a>
	</li>
</volist>
<if condition="$play_count gt 8">
	<li class="dropdown">
		<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
			<span class="glyphicon glyphicon-facetime-video text-green"></span>
			<span>更多</span>
			<b class="caret text-green"></b>
		</a>
		<ul class="dropdown-menu" role="menu">
		<volist name="playurl_line" id="feifei" key="k" offset="2" length="99">
			<lt name="k" value="7"><li class="hidden-md hidden-lg"><else/><li class=""></lt>
			<a href="javascript:;" data-target=".ff-playurl-tab-{$feifei.player_sid}" data-toggle="tab">{$feifei.player_name_zh}</a></li>
		</volist>
		</ul>
	</li>
<elseif condition="$play_count gt 2" />
	<li class="dropdown visible-xs visible-sm">
		<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
			<span class="glyphicon glyphicon-facetime-video text-green"></span>
			<span>更多</span>
			<b class="caret text-green"></b>
		</a>
		<ul class="dropdown-menu" role="menu">
		<volist name="playurl_line" id="feifei" key="k" offset="2" length="99">
			<li><a href="javascript:;" data-target=".ff-playurl-tab-{$feifei.player_sid}" data-toggle="tab">{$feifei.player_name_zh}</a></li>
		</volist>
		</ul>
	</li>
</if>
</ul>
<!-- -->
<div class="tab-content ff-playurl-tab">
  <volist name="playurl_line" id="feifei" key="k">
  <eq name="k" value="1"><assign name="active" value="active fade in" /><else/><assign name="active" value="fade"/></eq>
  <ul class="list-unstyled row text-center tab-pane ff-playurl ff-playurl-tab-{$feifei.player_sid} {$active}" data-active="{$vod_id}-{$play_sid}-{$play_pid}" data-more="{$Think.config.ui_playurl|intval}">
    <volist name="feifei.son" id="feifeison" key="pid">
    <li class="col-md-1 col-xs-4" data-id="{$vod_id}-{$feifei.player_sid}-{$pid}"><a href="{:ff_url_play($list_id,$list_dir,$vod_id,$vod_ename,$feifei['player_sid'],$pid)}" class="btn btn-default btn-block btn-sm text-ellipsis"><if condition="(strlen($feifeison['title']) eq 8) and is_numeric($feifeison['title'])">{$feifeison.title|msubstr=2,5}<else/>{$feifeison.title|msubstr=0,6}</if></a></li>
    </volist>
  </ul>
  </volist>
</div>
</notempty>