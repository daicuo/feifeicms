<php>
$playurl_end = ff_url_play_end($vod_url);
$playurl_line = array();
$playurl_down = array();
$playurl_yugao = array();
foreach($vod_play_list as $key=>$value){
	if($value['player_name_en'] == "yugao"){
  	array_push($playurl_yugao, $value);
 	}else if($value['player_name_en'] == "down"){
  	array_push($playurl_down, $value);
 	}else if($value['player_name_en'] == "magnet"){
  	array_push($playurl_down, $value);
 	}else{
  	array_push($playurl_line, $value);
 	}
}
unset($vod_play_list);
</php>