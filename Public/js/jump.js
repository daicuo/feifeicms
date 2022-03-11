function pagego($url,$total){
	$page = document.getElementById('page').value;
	if($page>0&&($page<=$total)){
		$url=$url.replace('FFLINK',$page);
		if($url.split('index-1')){
			$url=$url.split('index-1')[0];
		}
		top.location.href = $url;
	}
	return false;
}