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
<php>$item_vod=ff_mysql_vod('cid:'.ff_list_ids($list_id).';tag_name:'.$feifeilist.';tag_list:vod_type;limit:12;cache_name:default;cache_time:default;order:vod_stars desc,vod_addtime;sort:desc');if(!$item_vod){continue;}</php>
<div class="container ff-bg">
<div class="row ff-row">
  <div class="col-md-8 ff-col">
    <div class="page-header">
      <h2>
      <span class="glyphicon glyphicon-film text-green"></span>
      {$feifeilist}
      <span class="pull-right"><a href="{:ff_url('list/select',array('id'=>$list_id,'type'=>urlencode($feifeilist),'area'=>'','year'=>'','star'=>'','state'=>'','order'=>'addtime','p'=>1),true)}" class="btn btn-success btn-xs">更多</a></span>
      </h2>
    </div>
    <ul class="list-unstyled vod-item-img ff-img-215">
      <volist name="item_vod" id="feifei">
      <include file="BlockTheme:item_img_vod_left" />
      </volist>
    </ul>
  </div><!--lg-8end -->
  <div class="col-md-4 ff-col visible-lg visible-md">
    <div class="page-header">
      <h2><span class="glyphicon glyphicon-signal text-green"></span> 热播榜</h2>
    </div>
    <ol class="vod-item-ol">
      <volist name=":ff_mysql_vod('field:list_id,list_dir,vod_id,vod_cid,vod_name,vod_ename,vod_jumpurl,vod_hits;cid:'.ff_list_ids($list_id).';tag_name:'.$feifeilist.';tag_list:vod_type;limit:20;cache_name:default;cache_time:default;order:vod_hits desc')" id="feifei">
      <include file="BlockTheme:item_txt_vod_hits" />
      </volist>
    </ol>
  </div><!--lg-4 end -->
</div><!--row end -->
</div>
<div class="clearfix mb-2"></div>
</volist>
<!-- -->
<div class="container ff-bg">
<include file="BlockTheme:footer" />
</div>
</body>
</html>