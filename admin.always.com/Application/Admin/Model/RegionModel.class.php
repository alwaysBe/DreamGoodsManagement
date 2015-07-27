<?php
namespace Admin\Model;
use Think\Model;
class RegionModel extends BaseModel{
	protected $_validate=array(
		array('name','require','地区名字不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
array('parent_id','require','父级地区不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
array('intro','require','简介不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
array('status','require','状态不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
	);
}



 ?>