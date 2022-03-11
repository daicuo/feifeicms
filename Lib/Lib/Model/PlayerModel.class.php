<?php
class PlayerModel extends AdvModel {
	//自动验证
	protected $_validate=array(
		array('player_name_zh','require','播放器名称必须填写！',1),
		array('player_name_en','require','播放器标识必须填写！',1),
		array('player_name_en','','播放器标识重复，请重新填写',2,'unique',1),
	);
}
?>