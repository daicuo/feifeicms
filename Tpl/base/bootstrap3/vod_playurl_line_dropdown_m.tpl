<!--在线观看下拉菜单组件 -->
<notempty name="playurl_line">
<div class="page-header">
  <h2>
  	<span class="glyphicon glyphicon-facetime-video text-green"></span>
    <if condition="count($playurl_line) gt 1">
      <div class="btn-group ff-playurl-dropdown" data-active=".ff-playurl-dropdown-{$play_sid}">
        <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown">
          <span class="select"><volist name="playurl_line" id="feifei" offset="0" length="1">{$feifei.player_name_zh}</volist></span> 
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
          <volist name="playurl_line" id="feifei" key="k">
          <li><a href="javascript:;" data-target=".ff-playurl-dropdown-{$feifei.player_sid}" data-toggle="tab">{$feifei.player_name_zh}</a></li>
          </volist>
        </ul>
      </div>
    <else/>
    	{$playurl_line.0.player_name_zh}
    </if>
  </h2>
</div>
<!-- -->
<div class="tab-content ff-playurl-dropdown">
  <volist name="playurl_line" id="feifei" key="k">
  <eq name="k" value="1"><assign name="active" value="fade in active" /><else/><assign name="active" value="fade"/></eq>
  <ul class="list-unstyled row text-center tab-pane ff-playurl ff-playurl-dropdown-{$feifei.player_sid} {$active}" data-active="{$vod_id}-{$play_sid}-{$play_pid}" data-more="{$Think.config.ui_playurl|intval}">
    <volist name="feifei.son" id="feifeison" key="pid">
    <li class="col-xs-4" data-id="{$vod_id}-{$feifei.player_sid}-{$pid}"><a href="{:ff_url_play($list_id,$list_dir,$vod_id,$vod_ename,$feifei['player_sid'],$pid)}" class="btn btn-default btn-block btn-sm text-ellipsis"><if condition="(strlen($feifeison['title']) eq 8) and is_numeric($feifeison['title'])">{$feifeison.title|msubstr=2,5}<else/>{$feifeison.title|msubstr=0,6}</if></a></li>
    </volist>
  </ul>
  </volist>
</div>
</notempty>