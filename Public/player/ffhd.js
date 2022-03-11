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
	'isie' : function(){
		if (!!window.ActiveXObject || "ActiveXObject" in window){
			return true;
		}else{
			return false;
		}
	},
	'install': function(){
		cms_player_fun.isinstall = false;
		cms_player_fun.$('ffhd_iframe').src = "//cdn.feifeicms.co/player/1.0/install.php?playname=ffhd";
		cms_player_fun.$('ffhd_player').style.display = 'none';
	},
	'buffer': function(){
		if(cms_player.time){
			cms_player_fun.$('ffhd_iframe').src = cms_player.buffer;
			cms_player_fun.$('ffhd_iframe').style.height = (cms_player_fun.height-50)+'px';
			setTimeout('cms_player_fun.status()', cms_player.time*1000);
		}else{
			cms_player_fun.status();
		}
	},
	'status': function(){
		cms_player_fun.$('ffhd_iframe').style.display = 'none';
	},
	'play' : function(){
		cms_player_fun._height();
		document.write('<div style="width:100%;height:'+cms_player_fun.height+'px;overflow:hidden;position:relative;"><iframe id="ffhd_iframe" style="position:absolute;z-index:2;top:0px;left:0px" src="" frameBorder="0" width="480" height="320" scrolling="no"></iframe>');
		if( cms_player_fun.isie() ){
			document.write('<object id="ffhd_player" name="ffhd_player" classid="clsid:D154C77B-73C3-4096-ABC4-4AFAE87AB206" width="480" height="320" onerror="cms_player_fun.install();"><param name="url" value="'+cms_player.url+'"/><param name="CurWebPage" value="'+cms_player_fun.weburl+'"/></object></div>');
			if(cms_player_fun.isinstall){
				cms_player_fun.buffer();
			}
		}else{
			if (navigator.plugins) {
				cms_player_fun.isinstall = false;
				for (var i=0; i<navigator.plugins.length; i++) {
					if(navigator.plugins[i].name == 'FFPlayer Plug-In'){
						cms_player_fun.isinstall = true;
						break;
					}
				}
				if(cms_player_fun.isinstall){
					document.write('<object id="ffhd_player" name="ffhd_player" type="application/npFFPlayer" width="480" height="320" progid="XLIB.FFPlayer.1" url="'+cms_player.url+'" CurWebPage="'+cms_player_fun.weburl+'"></object></div>');
					cms_player_fun.buffer();
				}else{
					document.write('<div id="ffhd_player" name="ffhd_player">请安装非凡影音播放器</div></div>');
					cms_player_fun.install();
				}
			}else{
				alert('不支持该浏览器点播，推荐使用Goolge Chrome');
			}
		}
	}
};
cms_player_fun.play();