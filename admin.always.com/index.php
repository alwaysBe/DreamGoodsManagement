<?php
define("APP_DEBUG", true);//开启调试模式
define("ROOT_PATH", dirname($_SERVER['SCRIPT_FILENAME']).'/');//定义项目路径
define('APP_PATH', ROOT_PATH.'Application/');//定义项目路径
define('THINK_PATH',dirname(ROOT_PATH).'/ThinkPHP/' );//定义框架路径
define('SRC_URL','http://admin.always.com');//服务器地址

define('RUNTIME_PATH',ROOT_PATH.'Runtime/');//缓存目录
define('DEFAULT_MODULE','Admin');

// define('BIND_MODULE','Admin');//绑定管理模块

require THINK_PATH.'ThinkPHP.php';

 ?>