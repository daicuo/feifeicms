<dl class="types">
  <dt class="text-green hidden-xs">按类型</dt>
  <dd>
    <ul class="list-unstyled row">
      <volist name=":explode(',',$list_extend['type'])" id="feifei" offset="0" length="12">
      <li class="col-xs-3 text-nowrap"><a href="{:ff_url('list/select',array('id'=>$list_id,'type'=>urlencode($feifei),'area'=>'','year'=>'','star'=>'','state'=>'','order'=>'addtime','p'=>1),true)}">{$feifei|msubstr=0,4}</a></li>
      </volist>
    </ul>
  </dd>
  <dt class="text-green hidden-xs">按年份</dt>
  <dd class="hidden-xs">
    <ul class="list-unstyled row">
      <volist name=":explode(',',$list_extend['year'])" id="feifei">
      <li class="col-xs-3"><a href="{:ff_url('list/select',array('id'=>$list_id,'type'=>'','area'=>'','year'=>$feifei,'star'=>'','state'=>'','order'=>'addtime','p'=>1),true)}">{$feifei}</a></li>
      </volist>
			<php>$year_end = end(explode(',',$list_extend['year']));</php>
      <li class="col-xs-3"><a href="{:ff_url('list/select',array('id'=>$list_id,'type'=>'','area'=>'','year'=>'1990'.($year_end-1),'star'=>'','state'=>'','order'=>'addtime','p'=>1),true)}">更早</a></li>
    </ul>
  </dd>
  <dt class="text-green hidden-xs">按地区</dt>
  <dd class="hidden-xs">
    <ul class="list-unstyled row">
      <volist name=":explode(',',$list_extend['area'])" id="feifei" offset="0" length="12">
      <li class="col-xs-3"><a href="{:ff_url('list/select',array('id'=>$list_id,'type'=>'','area'=>urlencode($feifei),'year'=>'','star'=>'','state'=>'','order'=>'addtime','p'=>1),true)}">{$feifei}</a></li>
      </volist>
    </ul>
  </dd>
</dl>