<span class="vod-content-default text-justify">{$vod_content|strip_tags|msubstr=0,150,true}</span>
<span class="vod-content ff-collapse text-justify">{:ff_url_tags_content(nb(strip_tags($vod_content,"<a>")),$Tag)}</span>
<a href="javascript:;" data-toggle="ff-collapse" data-target=".vod-content" data-default=".vod-content-default" data-html="收起">详情</a>