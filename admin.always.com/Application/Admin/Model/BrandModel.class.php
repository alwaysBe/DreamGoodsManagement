<?php
namespace Admin\Model;
use Think\Model;
/**
 * 品牌模型类
 */
class BrandModel extends BaseModel{

	//自动验证
	protected $_validate=array(
		array('name','require','品牌名称不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('site_url','require','网址不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('logo','require','LOGO不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('intro','require','描述不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('status','require','是否显示不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('sort','require','排序不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
	);
}



 ?>