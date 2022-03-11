<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/vod_list" />
</head>
<body class="vod-channel">
<include file="BlockTheme:header" />
<div class="container ff-bg pt-1 pb-1">
  <div class="row ff-row">
    <div class="col-md-8 ff-col">
      <include file="Slide:vod" />
    </div>
    <div class="col-md-4 ff-col">
      <include file="BlockTheme:vod_inc_channel" />
    </div>
  </div>
</div>
<div class="clearfix mb-2"></div> 
<volist name=":explode(',',$list_extend['type'])" id="feifeilist" offset="0" length="8">
<php>$item_vod=ff_mysql_vod('cid:'.ff_list_ids($list_id).';tag_name:'.$feifeilist.';tag_list:vod_type;limit:48;cache_name:default;cache_time:default;order:vod_stars desc,vod_addtime;sort:desc');if(!$item_vod){continue;}</php>
<div class="container ff-bg">
  <div class="page-header">
    <h2>
    <span class="glyphicon glyphicon-film text-green"></span>
    {$feifeilist}
    <span class="pull-right"><a href="{:ff_url('list/select',array('id'=>$list_id,'type'=>urlencode($feifeilist),'area'=>'','year'=>'','star'=>'','state'=>'','order'=>'addtime','p'=>1),true)}" class="btn btn-success btn-xs">更多</a></span>
    </h2>
  </div>
  <ul class="list-unstyled vod-item-img ff-img-90">
    <volist name="item_vod" id="feifei">
    <include file="BlockTheme:item_img_vod_sp" />
    </volist>
  </ul>
</div>
<div class="clearfix mb-2"></div> 
</volist>
<!-- -->
<div class="container ff-bg">
<include file="BlockTheme:footer" />
</div>
</body>
</html>