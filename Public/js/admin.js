/*******************************************************************************
$(document).ready(function(){
	$feifeicms.show.table();
});
*******************************************************************************/
var $feifeicms = {};
$feifeicms.version = '4.1';
$feifeicms.show = ({
	'table' : function(){//所有class为table表格的隔行换色效果	  
		var tabLen = $("table.table").length;
		if (tabLen>0){
			$("table.table tbody tr:even").attr("class","bgEven");  
			$("table.table tbody tr:odd").attr("class","bgOdd");
			//鼠标移至tbody区域时换色
			$("table.table tbody tr").hover(
				function(){
					$(this).addClass("bgOver");
				},
				function(){
					$(this).removeClass("bgOver");
				}
			);
		};
	},
	'sort':function($order){//上下箭头正倒序效果
		$('img.sort').on('click',function(){
			if($(this).attr('data-sort') == 'desc'){
				window.location.href = $(this).attr('data-url').replace('sort=desc','sort=asc');
			}else{
				window.location.href = $(this).attr('data-url').replace('sort=asc','sort=desc');
			}
		});
		$("img.sort[data-order="+$order+"").addClass('active');
	},
	'collapse':function($model,$id){//展开与收起折叠效果
		$('.select .all').on('click',function(){
			ff_select_show($model, $(this).attr('data-id'));
		});
		ff_select_show($model, $id);
	},
	'isend':function(){//设置连载
		$('.vod-isend sup').on("click", function(){
			setcontinu($(this).attr('data-id'),$(this).attr('data-continu'));
		});
	},
	'create':function(){//生成静态
		$('.create0').on("click", function(){
			alert('核心页面，动态模式运行，不需要生成');
		});
		$('.create1').on("click", function(){
			ff_dialog('?s=Admin-Create-'+$(this).attr('data-model')+'id-ids-'+$(this).attr('data-id'),'生成HTML','100%','300');
		});
	}
});
//表单提交验证
$feifeicms.form = ({	  
	//$("#"+$id).val().match(/([w]){2,12}$/) 2-12位数字字母组合	  
	empty : function($formname,$id){
		if(!$("#"+$id).val()){
			$("#"+$id).focus();
			$("#"+$id).css("border-color","#FF0000");
			$("#"+$id+"_error").html($('#'+$id).attr('error'));//html>><input ... error='not empty'></input><span id="$id_error">error</span>
			return false;
		}else{
			return true;
		}
	},
	repwd : function($formname,$id,$reid){
		if($("#"+$id).val()){
			if($("#"+$id).val() != $("#"+$reid).val()){
				$("#"+$id).focus();
				$("#"+$id).css("border-color","#FF0000");
				$("#"+$reid+"_error").html($('#'+$reid).attr('error'));
				return false;
			}else{
				return true;
			}
		}
	}	
});
//表单提交
function post($url){
	$('#myform').attr('action',$url);
	$('#myform').submit();
}
//tab切换
function showtab(mid,no,n){
	for(var i=0;i<=n;i++){
		$('#'+mid+i).hide();
	}
	$('#'+mid+no).show();
}
//全选与反选
function checkall($all){
	if($all){
		$("input[name='ids[]']").each(function(){
				this.checked = true;
		});
	}else{
		$("input[name='ids[]']").each(function(){
			if(this.checked == false)
				this.checked = true;
			else
			   this.checked = false;
		});		
	}
}
//分页跳转
function pagego($url,$total){
	$page=document.getElementById('page').value;
	if($page>0&&($page<=$total)){
		$url=$url.replace('FFLINK',$page);
		location.href=$url;
	}
	return false;
}
// 图片预览
function showpic(event,imgsrc,path){	
	var left = event.clientX+document.body.scrollLeft+20;
	var top = event.clientY+document.body.scrollTop+20;
	$("#showpic").css({left:left,top:top,display:""});
	if(imgsrc.indexOf('://')<0){
		imgsrc = path+imgsrc;
	}
	$("#showpic_img").attr("src",imgsrc);
}
// 取消图片预览
function hiddenpic(){
	$("#showpic").css({display:"none"});
}
//设置星级
function setstars(mid, id, stars){
	$.get('?s=Admin-'+mid+'-Ajaxstars-id-'+id+'-stars-'+stars, function(obj){
		if(obj == 'ok'){
			if(stars == 0){
				$('#star_'+id+'_0').hide();
			}else{
				$('#star_'+id+'_0').show();
			}
			for(i=1; i<=5; i++){
				$('#star_'+id+'_'+i).attr("src","./Public/images/admin/star0.gif");
				//$('#star_'+id+'_'+i).removeClass('star1');
				//$('#star_'+id+'_'+i).addClass('star0');
			}
			for(i=1; i<=stars; i++){
				$('#star_'+id+'_'+i).attr("src","./Public/images/admin/star1.gif");
			}	
		}
	});
}
//设置星级(添加与编辑)
function addstars(sid,stars){
	for(i=1; i<=5; i++){
		$('#star_'+i).attr("src","./Public/images/admin/star0.gif");
	}
	for(i=1; i<=stars; i++){
		$('#star_'+i).attr("src","./Public/images/admin/star1.gif");
	}
	$('#'+sid+'_stars').val(stars);
}
//设置连载
function setcontinu(id,string){
	//var width = document.body.scrollWidth;
	//var height = document.body.scrollHeight;
	$('#isend-'+id).after('<span class="continu" id="htmlcontinu">更新至（集、期）<input type="text" size="12" maxlength="8" value="'+string+'" name="continuajax" id="continuajax" onMouseOver="this.select();"> <input type="button" value="确定" onclick="ajaxcontinu('+id+',continuajax.value);" class="submit navpoint"/> <input type="button" value="取消" onclick="hidecontinu()" class="submit navpoint"/></span>');
	var offset = $('#isend-'+id).offset();
	$('#htmlcontinu').css({left:offset.left,top:offset.top});
	$('#showbg').css({width:$(window).width(),height:$(window).height()});	
}
//取消连载
function hidecontinu(){
	$('#htmlcontinu').remove();
	$('#showbg').css({width:0,height:0});
}
//AJAX连载
function ajaxcontinu(id,value){
	if(value==0){
		$('#isend-'+id+' sup[data-id]').html('<img src="./Public/images/admin/ct.gif">');
	}else{
		$('#isend-'+id+' sup[data-id]').html(value);
	}
	$.get('?s=Admin-Vod-Ajaxcontinu-id-'+id+'-continu-'+value);
	hidecontinu();
}
/*滚动
$(window).scroll(function() { 		
	$("#div0").css({left:'50%',top:$(this).scrollTop()+100});
});
*/
//绑定分类
function setbind(event, sid, key, val){
	$('#showbg').css({width:$(window).width(),height:$(window).height()});	
	var left = event.pageX-120;
	var top = event.pageY+20;
	$.ajax({
		url: '?s=Admin-Cj-Setbind-sid-'+sid+'-key-'+key+'-val-'+val,
		cache: false,
		async: false,
		success: function(res){
			if(res.indexOf('status') > 0){
				alert('对不起,您没有该功能的管理权限!');
			}else{
				$("#setbind").css({left:left,top:top,display:""});			
				$("#setbind").html(res);
			}
		}
	});
}
//提交绑定分类
function submitbind (bind_key, bind_val){
	$.ajax({
		url: '?s=Admin-Cj-Insertbind-key-'+bind_key+'-val-'+bind_val,
		success: function(res){
			if(bind_val){
				$("#bind_"+bind_key).html('<font color="green">已转换</font>');
			}else{
				$("#bind_"+bind_key).html('<font color="red">未转换</font>');
			}
			hidebind();
		}
	});	
}
//取消绑定
function hidebind(){
	$('#showbg').css({width:0,height:0});
	$('#setbind').hide();
}
//模态框dialog
function ff_dialog($strPath,$title,$width,$height){
	$('#ff_dialog_title').html($title);
	$('#ff_dialog_body').html('<iframe src="'+$strPath+'" width="'+$width+'" height="'+$height+'" frameborder="0" scrolling="auto" style="overflow-x:hidden;"></iframe>');
	$("#ff-dialog-back").show();
	$("#ff-dialog-box").show();
}
//模态框关闭
function ff_dialog_close(){
	$("#ff-dialog-back").hide();
	$("#ff-dialog-box").hide();
}
//筛选展开
function ff_select_show($model,$id){
	$.get("./index.php?s=admin-"+$model+"-select-id-"+$id, function(data){
		if(data == 1){
			$('.select tr').show();
			$('.select .all').val('收起^').attr('data-id','null');
		}else{
			$('.select tr').eq(2).nextAll().hide();
			$('.select .all').val('展开+').attr('data-id','set');
		}
	});
}
//数组排重函数 arr.unique()
Array.prototype.unique = function(){
 var res = [];
 var json = {};
 for(var i = 0; i < this.length; i++){
  if(!json[this[i]]){
   res.push(this[i]);
   json[this[i]] = 1;
  }
 }
 return res;
}