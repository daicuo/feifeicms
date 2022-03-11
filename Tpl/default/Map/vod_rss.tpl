<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0">
<channel>
<title>{$site_name}</title> 
<description>{$site_name}</description> 
<link>{$site_url}</link> 
<language>zh-cn</language> 
<docs>{$site_name}</docs> 
<generator>Rss Powered By {$site_url}</generator> 
<image>
<url>{$site_url}/Public/images/logo.gif</url> 
</image>
<volist name=":ff_mysql_vod('field:list_id,list_dir,vod_id,vod_name,vod_title,vod_actor,vod_director,vod_ename,vod_jumpurl,vod_content,vod_addtime;limit:'.$limit.';page_is:true;page_id:list;page_p:'.$page.';cache_name:default;cache_time:default;order:vod_addtime;sort:desc')" id="feifei">
<item>
<title>{$feifei.vod_name|htmlspecialchars|nb}{$feifei.vod_title|htmlspecialchars|nb}</title> 
<link>{$site_url}{:ff_url_read_vod($feifei['list_id'],$feifei['list_dir'],$feifei['vod_id'],$feifei['vod_ename'],$feifei['vod_jumpurl'])}</link>
<author>{$feifei.vod_actor|htmlspecialchars|nb}</author>
<pubDate>{$feifei.vod_addtime|date='Y-m-d H:i:s',###}</pubDate>
<description><![CDATA["{$feifei.vod_content|msubstr=0,200}"]]></description> 
</item>
</volist>
</channel>
</rss>