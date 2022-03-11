<notempty name="feifei.vod_year">
<volist name=":explode(',',$feifei['vod_year'])" id="year">
<a href="{:ff_url('list/select',array('id'=>$list_id,'type'=>'','area'=>'','year'=>$year,'star'=>'','state'=>'','order'=>'hits'),true)}">{$year}</a></volist>
<else />
未知
</notempty>