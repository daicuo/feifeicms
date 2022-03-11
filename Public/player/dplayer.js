var cms_player_fun = {
	'canplay': false,
	'buffer': function(){
		$('#dplayer').before('<iframe id="cms_player_buffer" class="embed-responsive-item" src="'+cms_player.buffer+'" frameborder="0" scrolling="no" allowtransparency="true"></iframe>');
	},
	'pause': function(){
		$width = $('#dplayer').width()/2;
		$height = $('#dplayer').height()/2;
		$top = $('#dplayer').height()/4;
		$left = $('#dplayer').width()/4;
		$('#dplayer').before('<iframe id="cms_player_pause" class="embed-responsive-item" src="'+cms_player.pause+'" frameborder="0" scrolling="no" allowtransparency="true" style="position:absolute;z-index:2;top:'+$top+'px;left:'+$left+'px;width:'+$width+'px;height:'+$height+'px"></iframe>');
	},
	'remove': function(){
		$('#cms_player_buffer').remove();
		$('#cms_player_pause').remove();
	},
	'dplayer_plus' : function(){
		document.write('<script src="//lib.baomitu.com/hls.js/0.10.1/hls.min.js"></script>');
		document.write('<script src="//lib.baomitu.com/flv.js/1.4.2/flv.min.js"></script>');
	},
	'dplayer_css' : function(){
		$("<link>").attr({ rel: "stylesheet",type: "text/css",href: "//lib.baomitu.com/dplayer/1.25.0/DPlayer.min.css"}).appendTo("head");
		document.write('<style>.dplayer-menu{display: none !important;}</style><div id="dplayer" class="embed-responsive-item"></div>');
	},
	'dplayer_html' : function(){
		// 调用播放器
		var dp = new DPlayer({
			container:document.getElementById('dplayer'),
			autoplay:true,
			allowfullscreen:true,
			preload:'auto',
			video: {
				url: cms_player.url,
				type: 'auto'
			}
		});
		// 足够播放时
		dp.on('canplay',function(){
			cms_player_fun.canplay = true;
		});
		// 播放开始时
		dp.on('play',function(){
			cms_player_fun.remove();
		});
		// 播放暂停时
		dp.on('pause',function(){
			if(cms_player.pause && cms_player_fun.canplay){
				cms_player_fun.pause();
			}
		});		
		// 播放完成时
		dp.on('ended',function(){
			if(cms_player.next_path){
				top.location.href = cms_player.next_path;
			}
		});
	},
	'play': function(){
		cms_player.yun = false;//取消云播放器
		cms_player_fun.dplayer_plus();//m3u8 flv插件
		cms_player_fun.dplayer_css();//dplayer 样式
		$.getScript("//lib.baomitu.com/dplayer/1.25.0/DPlayer.min.js", function(){
			if(cms_player.time && cms_player.buffer){
				cms_player_fun.buffer();
				setTimeout(function(){cms_player_fun.remove();cms_player_fun.dplayer_html();},(cms_player.time*1000));
			}else{
				cms_player_fun.dplayer_html();
			}
		});
	}
};
cms_player_fun.play();