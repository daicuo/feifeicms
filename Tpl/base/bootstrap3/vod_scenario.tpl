<notempty name="vod_scenario.info">
<div class="page-header">
<h2>
<span class="glyphicon glyphicon-list text-green"></span>
<a href="{:ff_url('vod/juqing',array('id'=>$vod_id),true)}">分集剧情</a>
</h2>
</div> 
<ul class="nav nav-pills ff-scenario-pill" data-max="{$Think.CONFIG.ui_scenario}">
  <!--<li><a href="javascript:;" data-target=".ff-scenario-1" data-toggle="pill">1</a></li> -->
</ul>
<div class="tab-content ff-scenario-content">
  <volist name="vod_scenario.info" id="feifei">
  <dl class="tab-pane active vod-scenario-{$i}">
    <dt><a href="{:ff_url('scenario/read', array('id'=>$vod_id, pid=>$i), true)}">{$vod_name} 第{$i}集剧情介绍</a></dt>
    <dd>{$feifei|msubstr=0,140,true} <a class="text-green"href="{:ff_url('scenario/read', array('id'=>$vod_id, pid=>$i), true)}" target="_blank">详情...</a></dd>
  </dl>
  </volist>
</div>
</notempty>
