<!--预告片TAB标签组件 -->
<notempty name="playurl_yugao">
<ul class="nav nav-tabs ff-playurl-tab" data-active=".ff-playurl-tab-{$play_sid}">
  <volist name="playurl_yugao" id="feifei" key="n">
  <eq name="n" value="1"><assign name="active" value="active" /><else/><assign name="active" value=""/></eq>
  <li class="{$active}">
  	<a href="javascript:;" data-target=".ff-playurl-tab-{$feifei.player_sid}" data-toggle="tab"><span class="glyphicon glyphicon-film"></span> {$feifei.player_name_zh}</a>
  </li>
  </volist>
</ul>
<!-- -->
<div class="tab-content ff-playurl-tab">
  <volist name="playurl_yugao" id="feifei" key="k">
  <eq name="k" value="1"><assign name="active" value="active fade in" /><else/><assign name="active" value="fade"/></eq>
  <ul class="list-unstyled row text-center tab-pane ff-playurl ff-playurl-tab-{$feifei.player_sid} {$active}" data-active="{$vod_id}-{$play_sid}-{$play_pid}" data-more="{$Think.config.ui_playurl|intval}">
    <volist name="feifei.son" id="feifeison" key="pid">
    <li class="col-md-1 col-xs-4" data-id="{$vod_id}-{$feifei.player_sid}-{$pid}"><a href="{:ff_url_play($list_id,$list_dir,$vod_id,$vod_ename,$feifei['player_sid'],$pid)}" class="btn btn-default btn-block btn-sm text-ellipsis">{$feifeison.title|str_replace='片','',###}</a></li>
    </volist>
  </ul>
  </volist>
</div>
</notempty>