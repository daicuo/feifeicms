cms_player.yun=false;
var cms_player_fun = {
	'weburl' : unescape(window.location.href),
	'isinstall': true,
	'height': 320,
	'next_weburl': '',
	'$':function(id){
		return document.getElementById(id);
	},
	'_height':function(){
		this.height = this.$('cms_player').offsetHeight;
	},
	'_next_weburl':function(){
		var url = '';
		if(cms_player.next_url){
			var a = this.weburl.match(/(\d+)/g);
			var len = a.length;
			var i = 0;
			var url = this.weburl.replace(/(\d+)/g,function(){
				if (a[i]){
					if((i+1)==len){
						return a[len-1]*1+1;
					}else{
						return a[i++];
					}
				}
			});
		}
		this.next_weburl = url;
	},
	'isie' : function(){
		if (!!window.ActiveXObject || "ActiveXObject" in window){
			return true;
		}else{
			return false;
		}
	},
	'install': function(){
		cms_player_fun.isinstall = false;
		cms_player_fun.$('xigua_iframe').src = "//cdn.feifeicms.co/player/1.0/install.php?playname=xigua";
		cms_player_fun.$('xigua_player').style.display = 'none';
	},
	'buffer': function(){
		cms_player_fun.$('xigua_iframe').src = cms_player.buffer;
		cms_player_fun.$('xigua_iframe').style.height = (cms_player_fun.height-63)+'px';
	},
	'status': function(){
		if( xigua_player.IsPlaying() ){
    	cms_player_fun.$('xigua_iframe').style.display = 'none';
    }else if( xigua_player.IsBuffing() ){
    	cms_player_fun.$('xigua_iframe').style.display = 'block';
    }else if( xigua_player.IsPause() ){
    	cms_player_fun.$('xigua_iframe').style.display = 'block';
    }
	},
	'play' : function(){
		cms_player_fun._next_weburl();
		cms_player_fun._height();
		document.write('<div style="width:100%;height:'+cms_player_fun.height+'px;overflow:hidden;position:relative;"><iframe id="xigua_iframe" style="position:absolute;z-index:2;top:0px;left:0px" src="" frameBorder="0" width="480" height="320" scrolling="no"></iframe>');
		if( cms_player_fun.isie() ){
			document.write('<object id="xigua_player" name="xigua_player" classid="clsid:BEF1C903-057D-435E-8223-8EC337C7D3D0" width="480" height="320" onerror="cms_player_fun.install();"><param name="URL" value="'+cms_player.url+'"/><param name="NextCacheUrl" value="'+cms_player.next_url+'"><param name="NextWebPage" value="'+cms_player_fun.next_weburl+'"><param name="Autoplay" value="1"/></object></div>');
			if(cms_player_fun.isinstall){
				cms_player_fun.buffer();
				setInterval('cms_player_fun.status()','1000');
			}
		}else{
			if (navigator.plugins) {
				cms_player_fun.isinstall = false;
				for (var i=0; i<navigator.plugins.length; i++) {
					if(navigator.plugins[i].name == 'XiGua Yingshi Plugin'){
						cms_player_fun.isinstall = true;
						break;
					}
				}
				if(cms_player_fun.isinstall){
					document.write('<object id="xigua_player" name="xigua_player" type="application/xgyingshi-activex" progid="xgax.player.1" width="480" height="320" progid="Xbdyy.PlayCtrl.1" param_URL="'+cms_player.url+'" param_NextCacheUrl="'+cms_player.next_url+'" param_NextWebPage="'+cms_player_fun.next_weburl+'" param_Autoplay="1"></object></div>');
					cms_player_fun.buffer();
					setInterval('cms_player_fun.status()','1000');
				}else{
					document.write('<div id="xigua_player" name="xigua_player">请安装西瓜影音播放器</div></div>');
					cms_player_fun.install();
				}
			}else{
				alert('不支持该浏览器点播，推荐使用Goolge Chrome');
			}
		}
	}
};
cms_player_fun.play();