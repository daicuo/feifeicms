/*主题JS库*/
var feifei_theme = {
	'm':{
		'nav_goback':function(){//goback
			if($('.ff-goback').css('display') == 'inline'){
				$(".glyphicon-home").hide();
			}
		},
		'nav_active':function(){//滑动导航高亮
			$id = $('.nav-gallery[data-dir]').attr('data-dir');
			$($id).addClass("active");
		},
		'nav_gallery':function(){//滑动导航
			if($(".nav-gallery").length){
				var $index = $(".nav-gallery").find('.active').index()*1;
				if($index > 3){
					$index = $index-3;
				}else{
					$index = 0;
				}
				$(".nav-gallery").flickity({
					cellAlign: 'left',
					freeScroll: true,
					lazyLoad: true,
					contain: true,
					prevNextButtons: false,
					resize: true,
					initialIndex: $index,
					pageDots: false
				});
			}
		},
		'user_login':function(){//静态登录处理
			if($('.ff-user').length && (cms.urlhtml == 1)){
				$('.ff-user').html($('.ff-user').html().replace('登录','我的'));
			}
		}
	}
}
$(document).ready(function(){
	if(!feifei.browser.useragent.mobile){
		feifei.mobile.flickity();
	}
	feifei_theme.m.nav_goback();
	feifei_theme.m.nav_active();
	feifei_theme.m.nav_gallery();
	feifei_theme.m.user_login();
});