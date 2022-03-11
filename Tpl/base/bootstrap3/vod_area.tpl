<notempty name="vod_area">
<volist name=":explode(',',$vod_area)" id="area">
<a href="{:ff_url('list/select',array('id'=>$vod_cid,'type'=>'','area'=>urlencode($area),'year'=>'','star'=>'','state'=>'','order'=>'addtime'),true)}">{$area}</a>
</volist>
<else/>
<a href="javascript:;">未知</a>
</notempty>