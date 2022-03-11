<!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="BlockTheme:header_meta" />
<include file="./Tpl/base/seo/star_ziliao" />
</head>
<body class="star-detail star-ziliao">
<include file="BlockTheme:header" />
<include file="BlockTheme:star_inc_detail" />
<div class="container ff-bg">
<div class="page-header">
  <h2><span class="glyphicon glyphicon-user text-green"></span> 主要成就</h2>
</div>
<div class="ff-content content">
	{$person_achievement|default="暂未录入"}
</div>
<div class="page-header">
  <h2><span class="glyphicon glyphicon-user text-green"></span> 演艺经历</h2>
</div>
<div class="ff-content content">
	{$person_content|default="暂未录入"}
</div>
</div><!--container end -->
<div class="clearfix mb-1"></div>
<include file="BlockTheme:footer" />
</body>
</html>