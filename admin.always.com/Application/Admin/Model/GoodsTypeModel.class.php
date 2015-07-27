<?php
namespace Admin\Model;
use Think\Model;
/**
 *商品类型模型
 */
class GoodsTypeModel extends BaseModel{

	//自动验证
	protected $_validate=array(
		array('name','require','类型名称不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('intro','require','描述不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('status','require','是否显示不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('sort','require','排序不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
	);
}



 ?>