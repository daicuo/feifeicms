<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/vod_list" />
</head>
<body class="vod-channel">
<include file="BlockTheme:header" />
<div class="container ff-bg">
  <include file="Slide:vod" />
</div>
<div class="clearfix mb-1"></div> 
<volist name=":explode(',',$list_extend['type'])" id="feifeilist" offset="0" length="8">
<php>$item_vod=ff_mysql_vod('cid:'.ff_list_ids($list_id).';tag_name:'.$feifeilist.';tag_list:vod_type;limit:15;cache_name:default;cache_time:default;order:vod_stars desc,vod_addtime;sort:desc');if(!$item_vod){continue;}</php>
<div class="container ff-bg">
  <div class="page-header">
    <h2>
    <span class="glyphicon glyphicon-film text-green"></span>
    {$feifeilist}
    <span class="pull-right"><a class="btn btn-success btn-xs" href="{:ff_url('list/select',array('id'=>$list_id,'type'=>urlencode($feifeilist),'area'=>'','year'=>'','star'=>'','state'=>'','order'=>'addtime','p'=>1),true)}">更多</a></span>
    </h2>
  </div>
  <ul class="list-unstyled vod-item-img ff-img-140">
    <volist name="item_vod" id="feifei">
    <include file="BlockTheme:item_img_vod" />
    </volist>
  </ul>
</div>
<div class="clearfix mb-1"></div> 
</volist>
<!-- -->
<div class="container pl-0 pr-0">
	<a class="btn btn-block btn-lg btn-success" href="{:ff_url('list/select',array('id'=>$list_id,'type'=>'','area'=>'','year'=>'','star'=>'','state'=>'','order'=>'addtime','p'=>1),true)}" style=" border-radius:0">全部分类</a>
</div>
<div class="clearfix mb-1"></div> 
<include file="BlockTheme:footer" />
</body>
</html>