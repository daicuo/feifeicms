<php>$detail_prev = ff_detail_array('news', 'prev', $news_id, $news_cid);
$detail_next = ff_detail_array('news', 'next', $news_id, $news_cid);</php>           
<ul class="ff-prev-next">
<empty name="detail_prev">
	<li>上一篇：没有了</li>
<else/>
	<li>上一篇：<a id="ff-prev" href="{:ff_url_read_news($list_id,$list_dir,$detail_prev['news_id'],$detail_prev['news_ename'],$detail_prev['news_jumpurl'],1)}">{$detail_prev.news_name}</a></li>
</empty>
<empty name="detail_next">
	<li>下一篇：没有了</li>
<else/>
	<li>下一篇：<a id="ff-next" href="{:ff_url_read_news($list_id,$list_dir,$detail_next['news_id'],$detail_next['news_ename'],$detail_next['news_jumpurl'],1)}">{$detail_next.news_name}</a></li>
</empty>
</ul>