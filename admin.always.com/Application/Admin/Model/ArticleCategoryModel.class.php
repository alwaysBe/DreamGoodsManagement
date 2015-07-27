<?php
namespace Admin\Model;
use Think\Model;
/**
 * 文章分类模型
 */
class ArticleCategoryModel extends BaseModel{

	//自动验证
	protected $_validate=array(
		array('name','require','分类名称不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('intro','require','简介不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('is_help','require','是否为帮助的分类不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('status','require','状态不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('sort','require','排序不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
	);
}



 ?>