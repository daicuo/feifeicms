<notempty name="playurl_down">
<style>dl,dd,h1,h2,h3,h4,h5,h6,p,form{margin:inherit;}</style>
<div class="page-header ff-playurl-down">
  <h2>
  	<span class="glyphicon glyphicon-cloud-download text-green"></span>
		下载观看 <small class="hidden-xs hidden-sm">友情提示：未安装工具时，会自动提示安装，安装完毕后即可高速下载。</small>
	</h2>
</div>
</notempty>
<volist name="playurl_down" id="feifei" key="sid">
<div class="table-responsive ff-playurl-down">
  <table class="table table-bordered">
  <thead>
  <tr>
    <th>{$feifei.player_name_zh} {$sid}</th>
    <th class="text-center">普通下载</th>
    <th class="text-center"><a class="thunder-link-all text-success" data-sid="{$sid}" href="javascript:;">批量下载</a></th></tr>
  </thead>
  <tbody>
  <volist name="feifei.son" id="feifeison" key="pid">
  <tr>
    <td class="text-center col-xs-8 col-md-10"><div class="input-group">
    <span class="input-group-addon">
    <input name="thunder-link-all-{$sid}" type="checkbox" value="{$feifeison.url|htmlspecialchars}" data-name="{$feifeison.title}" checked="checked">
    </span>
    <input class="form-control" type="text" value="{$feifeison.title} {$feifeison.url|htmlspecialchars}">
    </div></td>
    <td class="text-center col-xs-2 col-md-1"><a class="btn btn-info btn-sm" href="{$feifeison.url|htmlspecialchars}" target="_blank">{$feifei.player_name_zh}</a></td>
    <td class="text-center col-xs-2 col-md-1"><a class="btn btn-success btn-sm thunder-link" href="javascript:;" data-name="{$feifeison.title}" data-url="{$feifeison.url|htmlspecialchars}" >迅雷下载</a></td></tr>
  <tr>
  </volist>
  </tbody>
  </table>
</div>
<div class="clearfix"></div>
</volist>