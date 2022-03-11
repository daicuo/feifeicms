<a class="label label-success" href="{:ff_url_vod_show($list_id,$list_dir,1)}">{$list_name}</a>
<a class="label label-default" href="{:ff_url_read_vod($list_id,$list_dir,$vod_id,$vod_ename,$vod_jumpurl)}" title="{$vod_name}免费观看">影片详情</a>
<a class="label label-default" href="{:ff_url('vod/juqing',array('id'=>$vod_id),true)}" title="{$vod_name}剧情介绍">剧情介绍</a>
<a class="label label-success" href="{:ff_url('vod/yanyuan',array('id'=>$vod_id),true)}" title="{$vod_name}演员表">演员表</a>
<a class="label label-default" href="{:ff_url('vod/forum',array('id'=>$vod_id),true)}" title="{$vod_name}影评">影评</a>
<a class="label label-default hidden-xs hidden-sm" href="{:ff_url('vod/zixun',array('id'=>$vod_id),true)}" title="{$vod_name}新闻资讯">新闻资讯</a>
<a class="label label-default hidden-xs hidden-sm" href="{:ff_url('vod/jieju',array('id'=>$vod_id),true)}" title="{$vod_name}大结局">大结局</a>
<a class="label label-default hidden-xs hidden-sm" href="{:ff_url('vod/kandian',array('id'=>$vod_id),true)}" title="{$vod_name}上映时间">看点</a>
<a class="label label-default hidden-xs hidden-sm" href="{:ff_url('vod/pingfen',array('id'=>$vod_id),true)}" title="{$vod_name}评分">评分</a>
<a class="label label-default hidden-xs hidden-sm" href="{:ff_url('vod/shoubo',array('id'=>$vod_id),true)}" title="{$vod_name}上映时间">首播</a>
<a class="label label-default hidden-xs hidden-sm" href="{:ff_url('vod/rss',array('id'=>$vod_id),true)}" target="_blank" title="{$vod_name}订阅">RSS</a>
<notempty name="playurl_down">
<a class="label label-default hidden-xs hidden-sm" href="{:ff_url('vod/xiazai',array('id'=>$vod_id),true)}" title="{$vod_name}下载地址">下载观看</a>&nbsp;
</notempty>
<notempty name="playurl_yugao">
<a class="label label-default hidden-xs hidden-sm" href="{:ff_url('vod/yugao',array('id'=>$vod_id),true)}" title="{$vod_name}预告片">预告片</a>&nbsp;
</notempty>
<notempty name="vod_lines">
<a class="label label-default hidden-xs hidden-sm" href="{:ff_url('vod/taici',array('id'=>$vod_id),true)}" title="{$vod_name}经典台词">经典台词</a>&nbsp;
</notempty>