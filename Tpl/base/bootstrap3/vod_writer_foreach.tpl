<notempty name="feifei.vod_writer">
<volist name=":explode(',',ff_xml_vodactor($feifei['vod_writer']))" id="write" offset="0" length="2">
<a href="{:ff_url('vod/search',array('writer'=>urlencode($write)),true)}">{$write}</a>
</volist>
<else />
<a href="javascript:;">未知</a>
</notempty>