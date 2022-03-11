<notempty name="feifei.vod_area">
<volist name=":explode(',',$feifei['vod_area'])" id="area">
<a href="{:ff_url('list/select',array('id'=>$feifei['vod_cid'],'type'=>'','area'=>urlencode($area),'year'=>'','star'=>'','state'=>'','order'=>'addtime'),true)}">{$area}</a>
</volist>
<else/>
<a href="javascript:;">未知</a>
</notempty>