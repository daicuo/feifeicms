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
		cms_player_fun.$('xf_iframe').src = "//cdn.feifeicms.co/player/1.0/install.php?playname=xfplay";
		cms_player_fun.$('xf_player').style.display = 'none';
	},
	'buffer': function(){
		if(cms_player.time){
			cms_player_fun.$('xf_iframe').src = cms_player.buffer;
			cms_player_fun.$('xf_iframe').style.height = (cms_player_fun.height-50)+'px';
			setTimeout('cms_player_fun.status()', cms_player.time*1000);
		}else{
			cms_player_fun.status();
		}
	},
	'status': function(){
		cms_player_fun.$('xf_iframe').style.display = 'none';
	},
	'play' : function(){
		cms_player_fun._height();
		document.write('<div style="width:100%;height:'+cms_player_fun.height+'px;overflow:hidden;position:relative;"><iframe id="xf_iframe" style="position:absolute;z-index:2;top:0px;left:0px" src="" frameBorder="0" width="480" height="320" scrolling="no"></iframe>');
		if( cms_player_fun.isie() ){
			document.write('<object id="xf_player" name="xf_player" classid="clsid:E38F2429-07FE-464A-9DF6-C14EF88117DD" width="480" height="320" onerror="cms_player_fun.install();"><param name="URL" value="'+cms_player.url+'"/><param name="Status" value="1"/></object></div>');
			if(cms_player_fun.isinstall){
				cms_player_fun.buffer();
			}
		}else{
			if (navigator.plugins) {
				cms_player_fun.isinstall = false;
				for (i=0; i < navigator.plugins.length; i++ ) {
					var n = navigator.plugins[i].name;
					if( navigator.plugins[n][0]['type'] == 'application/xfplay-plugin'){
						cms_player_fun.isinstall = true;
						break;
					}
				} 
				if(cms_player_fun.isinstall){
					document.write('<embed id="xf_player" name="xf_player" type="application/xfplay-plugin" PARAM_URL="'+cms_player.url+'" PARAM_Status="1" width="480" height="320"></embed></div>');
					cms_player_fun.buffer();
				}else{
					document.write('<div id="xf_player" name="xf_player">请安装影音先锋播放器</div></div>');
					cms_player_fun.install();
				}
			}else{
				alert('不支持该浏览器点播，推荐使用Goolge Chrome');
			}
		}
	}
};
cms_player_fun.play();