<?php
namespace Admin\Model;
use Think\Model;
/**
 * 供应商模型
 */
class SupplierModel extends BaseModel{

	//自动完成
	protected $_validate=array(
		array('name','require','供货商名称不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('intro','require','描述不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('status','require','状态不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('sort','require','排序不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
	);
}



 ?>