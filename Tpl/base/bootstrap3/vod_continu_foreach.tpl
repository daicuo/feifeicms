<switch name="feifei.list_name">
<case value="电影">
	{$feifei.vod_gold}分
</case>
<case value="电视剧">
	<eq name="feifei.vod_isend" value="1">
    <gt name="feifei.vod_total" value="0">
      {$feifei.vod_total}集全
    <else/>
      {$feifei.vod_continu|default='全'}集
    </gt>
  <else/>
    <if condition="$feifei['vod_continu'] eq $feifei['vod_total']">
      <gt name="feifei.vod_total" value="0">
        {$feifei.vod_total}集全
      <else/>
        {$feifei.vod_continu|default='全'}集
      </gt>
    <elseif condition="$feifei['vod_continu'] lt $feifei['vod_total']"/>
   	 <gt name="feifei.vod_continu" value="0">
      	更新至{$feifei.vod_continu}集
      <else/>
        全集
      </gt>
     <elseif condition="$feifei['vod_continu'] gt $feifei['vod_total']"/>
     	全{$feifei.vod_continu|default=''}集
    </if>
  </eq>
</case>
<case value="动漫">
	<eq name="feifei.vod_isend" value="1">
    <gt name="feifei.vod_total" value="0">
      {$feifei.vod_total}集全
    <else/>
      {$feifei.vod_continu|default='全'}集
    </gt>
  <else/>
    <if condition="$feifei['vod_continu'] eq $feifei['vod_total']">
      <gt name="feifei.vod_total" value="0">
        {$feifei.vod_total}集全
      <else/>
        {$feifei.vod_continu|default='全'}集
      </gt>
    <elseif condition="$feifei['vod_continu'] lt $feifei['vod_total']"/>
      <gt name="feifei.vod_continu" value="0">
        更新至{$feifei.vod_continu}集
      <else/>
        全集
      </gt>
     <elseif condition="$feifei['vod_continu'] gt $feifei['vod_total']"/>
     	全{$feifei.vod_continu|default=''}集
    </if>
  </eq>
</case>
<case value="综艺">
	<if condition="strlen($feifei['vod_continu']) gt 6">
    {$feifei.vod_continu|strtotime|date='Y-m-d',###}
  <else/>
    {$feifei.vod_continu}期
  </if>
</case>
<default />
高清
</switch>