<notempty name="feifei.vod_actor">
<volist name=":explode(',',ff_xml_vodactor($feifei['vod_actor']))" id="actor" offset="0" length="2">
<a href="{:ff_url('vod/search',array('actor'=>urlencode($actor)),true)}">{$actor}</a>
</volist>
</notempty>