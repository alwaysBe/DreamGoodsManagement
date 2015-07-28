<?php
namespace Admin\Model;
use Think\Model;
/**
 * 送货方式模型
 */
class DeliveryModel extends BaseModel{

	//自动验证方法
	protected $_validate=array(
		array('name','require','送货方式名称不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('price','require','价格不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('is_default','require','是否默认不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('intro','require','简介不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
	);
}



 ?>