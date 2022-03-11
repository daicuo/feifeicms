<?php
$config = require './Runtime/Conf/config.php';
$array = array(
	//'APP_DEBUG'       => true,    // 是否开启调试模式
  //'SHOW_RUN_TIME'		=> true,   // 运行时间显示
  //'SHOW_ADV_TIME'		=> true,   // 显示详细的运行时间
  //'SHOW_DB_TIMES'		=> true,   // 显示数据库查询和写入次数
	'URL_PATHINFO_DEPR'=>'-',
	'URL_MODEL' => 3,
	'APP_GROUP_LIST'=>'Admin,Home,Crontab,Plus',//项目分组
	'USER_AUTH_KEY'=>'feifeicms',// 用户认证SESSION标记
	'TMPL_FILE_DEPR'=>'_',//模板文件MODULE_NAME与ACTION_NAME之间的分割符，只对项目分组部署有效
	'LANG_SWITCH_ON'=>true,// 多语言包功能
	'LANG_AUTO_DETECT'=>false,//是否自动侦测浏览器语言
	'URL_CASE_INSENSITIVE'=>true,//URL是否不区分大小写 默认区分大小写
	'DB_FIELDTYPE_CHECK'=>true, //是否进行字段类型检查
	'DATA_CACHE_SUBDIR'=>true,//哈希子目录动态缓存的方式
	'TMPL_ACTION_ERROR'     => './Public/jump/jumpurl.html', // 默认错误跳转对应的模板文件
	'TMPL_ACTION_SUCCESS'   => './Public/jump/jumpurl.html', // 默认成功跳转对应的模板文件		
	'DATA_PATH_LEVEL'=>2,
  'ADMIN_TIME_LIMIT' => '300',
  'HOME_PAGENUM' => '3',
  'HOME_PAGEGO' => 'pagego',
	//3.3 add
	'TMPL_DETECT_THEME'=>true,
	'VAR_TEMPLATE'=>"theme",
	//4.1 add
	'TOKEN_ON'=>false, 
	'SESSION_AUTO_START'=>false,
	'LOG_EXCEPTION_RECORD'=>false,
	//'TMPL_EXCEPTION_FILE' => './Public/error/500.html',
);
return array_merge($config,$array);
?>