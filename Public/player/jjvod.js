cms_player.yun=false;
var cms_player_fun = {
	'weburl' : unescape(window.location.href),
	'isinstall': true,
	'height': 320,
	'$':function(id){
		return document.getElementById(id);
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
		cms_player_fun.$('jjvod_iframe').src = "//cdn.feifeicms.co/player/1.0/install.php?playname=jjvod";
		cms_player_fun.$('jjvod_player').style.display = 'none';
	},
	'buffer': function(){
		cms_player_fun.$('jjvod_iframe').src = cms_player.buffer;
		cms_player_fun.$('jjvod_iframe').style.height = (cms_player_fun.height-50)+'px';
	},
	'status': function(){
		if(cms_player_fun.$('jjvod_player').PlayState == 3){
    	cms_player_fun.$('jjvod_iframe').style.display = 'none';
    }else if(cms_player_fun.$('jjvod_player').PlayState == 2 || cms_player_fun.$('jjvod_player').PlayState == 4){
    	cms_player_fun.$('jjvod_iframe').style.display = 'block';
    }
	},
	'play' : function(){
		cms_player_fun.height = cms_player_fun.$('cms_player').offsetHeight;
		document.write('<div style="width:100%;height:'+cms_player_fun.height+'px;overflow:hidden;position:relative;"><iframe id="jjvod_iframe" style="position:absolute;z-index:2;top:0px;left:0px" src="" frameBorder="0" width="480" height="320" scrolling="no"></iframe>');
		if( cms_player_fun.isie() ){
			document.write('<object id="jjvod_player" classid="clsid:C56A576C-CC4F-4414-8CB1-9AAC2F535837" width="480" height="320" onerror="cms_player_fun.install();"><PARAM NAME="URL" VALUE="'+cms_player.url+'"><PARAM NAME="WEB_URL" VALUE="'+cms_player_fun.weburl+'"><param name="Autoplay" value="1"></object></div>');
			if(cms_player_fun.isinstall){
				cms_player_fun.buffer();
				setInterval('cms_player_fun.status()','1000');
			}
		}else{
			if (navigator.plugins) {
				cms_player_fun.isinstall = false;
				for (var i=0; i<navigator.plugins.length; i++) {
					if(navigator.plugins[i].name == 'JJvod Plugin'){
						cms_player_fun.isinstall = true;
						break;
					}
				}
				if(cms_player_fun.isinstall){
					document.write('<object id="jjvod_player" name="jjvod_player" type="application/x-itst-activex" width="480" height="320" progid="WEBPLAYER.WebPlayerCtrl.2" param_URL="'+cms_player.url+'" param_WEB_URL="'+cms_player_fun.weburl+'"></object></div>');
					cms_player_fun.buffer();
					setInterval('cms_player_fun.status()','1000');
				}else{
					document.write('<div id="jjvod_player" name="jjvod_player">请安装JJVOD播放器</div></div>');
					cms_player_fun.install();
				}
			}else{
				alert('不支持该浏览器点播，推荐使用Goolge Chrome');
			}
		}
	}
};
cms_player_fun.play();