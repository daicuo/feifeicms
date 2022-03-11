<div class="clearfix"></div>
<div class="row ff-footer">
	<div class="col-md-2 text-muted text-center hidden-xs hidden-sm">
		<img class="img-thumbnail" src="{$public_path}images/qrcode/weixin.jpg"><br />微信公众号
	</div>
  <div class="col-md-8 col-xs-12 text-center">
    <p class="text-nowrap">{$site_description}</p>
    <p class="text-nowrap">{$site_copyright}</p>
    <p class="text-nowrap">
      <a href="{:ff_url('map/vod',array('id'=>'rss','limit'=>100,'p'=>1),false)}" target="_blank">rss</a>
      <a href="{:ff_url('map/vod',array('id'=>'baidu','limit'=>100,'p'=>1),false)}" target="_blank">baidu</a>
			<a href="{:ff_url('map/vod',array('id'=>'sogou','limit'=>100,'p'=>1),false)}" target="_blank">sogou</a>
			<a href="{:ff_url('map/vod',array('id'=>'360','limit'=>100,'p'=>1),false)}" target="_blank">360</a>
			<a href="{:ff_url('map/vod',array('id'=>'bing','limit'=>100,'p'=>1),false)}" target="_blank">bing</a>
      <a href="{:ff_url('map/vod',array('id'=>'google','limit'=>100,'p'=>1),false)}" target="_blank">google</a>
      <include file="./Tpl/base/bootstrap3/inc_footer.tpl" />
    </p>
  </div>
	<div class="col-md-2 text-muted text-center hidden-xs hidden-sm">
		<img class="img-thumbnail" src="{$public_path}images/qrcode/wap.jpg"><br />手机访问
	</div>
	<span class="ff-tongji">{$site_tongji}</span>
</div>