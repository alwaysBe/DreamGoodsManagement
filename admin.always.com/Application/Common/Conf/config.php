<?php
return array(
	//'配置项'=>'配置值'
	'DEFAULT_MODULE'        =>  'Admin',  // 默认模块
    'DEFAULT_CONTROLLER'    =>  'Index', // 默认控制器名称
    'DEFAULT_ACTION'        =>  'index', // 默认操作名称

    //文件上传的配置
    'FILE_UPLOAD'=>array(
                'maxSize'       =>  0, //上传的文件大小限制 (0-不做限制)
                'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
                'rootPath'      =>  ROOT_PATH.'Public/Uploads/', //保存根路径
    ),//设置上传配置
);