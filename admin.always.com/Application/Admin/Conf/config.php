<?php
return array(
	//'配置项'=>'配置值'
	'URL_MODEL'             =>  2,  
	'TMPL_PARSE_STRING'    =>array(
            '__IMG__'=>SRC_URL."/Public/images",
            '__CSS__'=>SRC_URL.'/Public/css',
            '__JS__'=>SRC_URL.'/Public/js',
            '__PUBLIC__'=>SRC_URL.'/Public/common',
            '__LIB__'=>SRC_URL.'/Public/lib', 
            '__FONTS__'=>SRC_URL.'/Public/fonts',
            '__UPLOADIFY__'=>SRC_URL.'/Public/uploadify',
            '__BRAND__'=> 'http://itsource-brand.b0.upaiyun.com/',
            '__GOODS__'=> 'http://itsource-goods.b0.upaiyun.com/',
            // '__GOODS__'=> 'http://7xkfn3.com1.z0.glb.clouddn.com',
            '__TREEGRID__'=>SRC_URL.'/Public/treegrid',
            '__ZTREE__'=>SRC_URL.'/Public/ztree',
            '__UEDITOR__'=>SRC_URL.'/Public/ueditor',
    ),
    'LANG_SWITCH_ON' => true,   // 开启语言包功能
    'LANG_AUTO_DETECT' => true, // 自动侦测语言 开启多语言功能后有效
    'LANG_LIST'        => 'zh-cn,en-us', // 允许切换的语言列表 用逗号分隔
    'VAR_LANGUAGE'     => 'l', // 默认语言切换变量

    'URL_PARAMS_BIND'       =>  true, // URL变量绑定到操作方法作为参数

    //数据库配置
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    'DB_NAME'               =>  'think',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'root',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'tp_',    // 数据库表前缀

    'SHOW_PAGE_TRACE' =>true,//开启trace调试工具

    'IGNORE_URL'=>array('Admin/login','Verify/index','Admin/logout'),

    'SUPPER_ADMIN'=>3,//超级管理员
    

);