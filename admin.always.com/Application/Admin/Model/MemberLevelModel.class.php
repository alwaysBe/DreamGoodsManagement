<?php
namespace Admin\Model;
use Think\Model;
/**
 * 会员等级模型
 */
class MemberLevelModel extends BaseModel{

	//自动验证
	protected $_validate=array(
		array('name','require','会员级别名称不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('discount','require','折扣率不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('low','require','下限经验值不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('high','require','上限经验值不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('status','require','状态不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('intro','require','描述不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('sort','require','排序不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
	);
}



 ?>