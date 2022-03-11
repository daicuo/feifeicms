<?php
$_GET['g'] = 'Home';
$_GET['m'] = 'Notify';
$_GET['a'] = 'alipay';
define('THINK_PATH','./Lib/ThinkPHP');
define('RUNTIME_PATH','./Runtime/');
define('APP_NAME','feifeicms');
define('APP_PATH','./Lib/');
require(THINK_PATH.'/ThinkPHP.php');
$App = new App();
$App->run();
?>