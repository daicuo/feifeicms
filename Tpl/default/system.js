//主题自定义JS库
$(document).ready(function(){
	//悬浮广告
	$(".ff-fixed").each(function(i){
		feifei.scroll.fixed($(this).attr('id'));
	});
});