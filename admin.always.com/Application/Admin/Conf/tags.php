<?php 
return array(
	//设置语言
	'app_begin' => array('Behavior\CheckLangBehavior'),
	'action_begin'=>array('Admin\Behavior\CheckPermissionBehavior'),
);



 ?>