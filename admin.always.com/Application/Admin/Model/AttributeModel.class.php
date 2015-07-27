<?php
namespace Admin\Model;
use Think\Model;
/**
 *商品属性模型
 */
class AttributeModel extends BaseModel{

	//自动验证
	protected $_validate=array(
		array('name','require','属性名称不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('goods_type_id','require','不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('attribute_type','require','类型不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('input_type','require','录入方式不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('option_values','require','可选值不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('intro','require','描述不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('status','require','状态不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('sort','require','排序不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
	);

	/**
	 * 回调函数.设置额外的查询条件
	 */
	public function _setModel(){

		$this->field('t.*,gt.name as goods_type_name');
		//连接查询
		$this->join('__GOODS_TYPE__  as gt on t.goods_type_id=gt.id');
	}

	/**
	 * 处理属性字段信息.回调函数
	 * @param  array &$rows 属性数组 
	 * @return null         无返回值
	 */
	public function _handleRows(&$rows){
		
		foreach($rows as &$row){
			$row['attribute_type']=$row['attribute_type']==1?'单值':'多值';
		
			switch ($row['input_type']) {
				case '1':
					$row['input_type']='手动录入';
					break;
				case '2':
					$row['input_type']='从下面的列表中选择';
					break;
				case '3':
					$row['input_type']='多行文本框';
					break;
				default:
					# code...
					break;
			}
		}
	}

	public function getAttrByGoodsTypeId($id){
		$this->where(array('goods_type_id'=>$id));
		$rows = $this->select();
		foreach($rows as &$row){
			if(!empty($row['option_values'])){
				$row['option_values']=str2arr($row['option_values'],"\r\n");
			}
		}
		return $rows;
	}

}



 ?>