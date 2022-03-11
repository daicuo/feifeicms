<notempty name="vod_director">
<volist name=":explode(',',ff_xml_vodactor($vod_director))" id="director" offset="0" length="3">
<a href="{:ff_url('vod/search',array('director'=>urlencode($director)),true)}">{$director}</a>
</volist>
<else />
<a href="javascript:;">未知</a>
</notempty>