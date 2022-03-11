cms_player.yun = false;
$.getScript("//cdn.daicuo.cc/ckplayer/ckplayer.min.js", function(){
	$('#cms_player').append('<div style="position:absolute;top:0;left:0;border:0;width:100%;height:100%;"><div class="Ckplayer" style="width:100%; height:100%"></div></div>');
	var player = new ckplayer({
		container: '.Ckplayer',
		variable: 'player',
		autoplay: true,
		flashplayer: false,
		poster: '//cdn.daicuo.cc/images/ico.player/bg_load.gif',
		video:cms_player.url
	});
});