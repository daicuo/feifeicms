<notempty name="feifei.vod_type">
<volist name=":explode(',',$feifei['vod_type'])" id="type">
<a href="{:ff_url('list/select',array('id'=>$feifei['list_id'],'type'=>urlencode($type),'area'=>'','year'=>'','star'=>'','state'=>'','order'=>'hits'),true)}">{$type}</a>
</volist>
<else/>
<a href="javascript:;">未知</a>
</notempty>