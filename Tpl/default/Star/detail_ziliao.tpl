<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="./Tpl/base/bootstrap3/inc_header" />
<include file="./Tpl/base/seo/star_ziliao" />
</head>
<body class="star-detail star-ziliao">
<include file="BlockTheme:header" />
<include file="BlockTheme:star_inc_detail" />
<div class="container ff-bg">
<p class="person-pic pt-2 pb-4 visible-xs visible-sm ff-content">
	<img class="img-responsive ff-img" data-original="{$person_pic|ff_url_img}">
</p>
<div class="page-header">
  <h4><span class="glyphicon glyphicon-user text-green"></span> 演技评分</h4>
</div>
<include file="./Tpl/base/bootstrap3/star_score" />
<div class="clearfix mb-4"></div>
<div class="page-header">
  <h4><span class="glyphicon glyphicon-user text-green"></span> 主要成就</h4>
</div>
<div class="ff-content lead">
	{$person_achievement|default="暂未录入"}
</div>
<div class="page-header">
  <h4><span class="glyphicon glyphicon-user text-green"></span> 演艺经历</h4>
</div>
<div class="ff-content lead">
	{$person_content|default="暂未录入"}
</div>
</div><!--container end -->
<div class="clearfix mb-2"></div>
<div class="container ff-bg">
  <include file="BlockTheme:footer" />
</div>
</body>
</html>