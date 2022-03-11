<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0">
<channel>
<title>{$vod_name}</title> 
<link>{$site_url}{:ff_url_read_vod($list_id,$list_dir,$vod_id,$vod_ename,$vod_jumpurl)}</link> 
<description><![CDATA["{$vod_content|strip_tags|msubstr=0,200,true}"]]></description> 
<language>zh-cn</language> 
<generator>{$site_url}</generator> 
<webmaster>{$site_eamil}</webmaster> 
<volist name="vod_play_list" id="feifei">
<volist name="feifei.son" id="feifeison" key="pid">
<item>
<title>{$vod_name|htmlspecialchars|nb}（{$feifei.player_name_zh}）{$feifeison.title}</title> 
<link>{$site_url}{:ff_url_play($list_id,$list_dir,$vod_id,$vod_ename,$feifei['player_sid'],$pid)}</link>
<description><![CDATA["{$vod_name|htmlspecialchars|strip_tags|nb}{$feifei.player_name_zh}{$feifeison.title}在线观看地址"]]></description>
<pubDate>{$vod_addtime|date='Y-m-d H:i:s',###}</pubDate>
<category>{$vod_name}</category> 
<author>{:ff_xml_vodactor(nb($vod_actor))}</author>
<comments>{$site_name}</comments>
</item>
</volist>
</volist>
</channel>
</rss>