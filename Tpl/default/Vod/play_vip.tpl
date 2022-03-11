<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>iframe播放器{$play_status}</title>
<style>body{background:#000;overflow-x:hidden;overflow-y:hidden;}.embed-responsive .embed-responsive-item,.embed-responsive iframe,.embed-responsive embed,.embed-responsive object{position:absolute;top:0;bottom:0;left:0;width:100%;height:100%;border:0;}.embed-responsive.embed-responsive-16by9{padding-bottom:56.25%;}.embed-responsive.embed-responsive-4by3{padding-bottom:75%;}</style>
<script>var cms = {
	root:"{$root}"
};</script><script type="text/javascript" src="{$public_path}jquery/1.11.3/jquery.min.js"></script>
</head>
<body>
<div class="embed-responsive embed-responsive-4by3" id="cms_player">
<gt name="play_trysee" value="0">{$vod_player}<else/><eq name="play_status" value="200">{$vod_player}<else/>参数错误</eq></gt>
<script>window.parent.feifei.playurl.vip_callback('{$play_id}','{$play_sid}','{$play_pid}','{$play_status}','{$play_trysee}','{$play_tips|nb}');</script>
</div>
</body>
</html>