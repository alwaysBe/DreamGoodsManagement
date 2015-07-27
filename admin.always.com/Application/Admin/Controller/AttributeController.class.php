<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 商品属性控制器
 */
class AttributeController extends BaseController{

	/**
	 * 钩子函数.覆盖父类对应方法
	 * 		    编辑时调用,提供下拉框数据
	 * @return null 无返回值
	 */
	public function _befor_edit(){
		//实例化模型
		$model=D('GoodsType');
		$id=I('get.goods_type_id');
		if(is_numeric($id)&&$id!=0){
			$this->assign('goods_type_id',$id);
		}
		//获取商品类型数据
		$Goodstyps=$model->getListData();
		//分配数据
		$this->assign('goodsTypes',$Goodstyps);
	}

	/**
	 * 钩子方法.通过ID设置筛选条件
	 * @param array &$where 选择条件
	 */
	public function _set_gds_type_id(&$where){

		$id=I('get.id');
		if(!empty($id)){
			$where['goods_type_id']=$id;
		}
	}

	/**
	 * 钩子方法,进入index时页面调用.提供下拉列表数据
	 * @return null 无返回值
	 */
	public function _befor_index(){

		$model = D('GoodsType');
        $goodsTypes = $model->getListData();
        
        $this->assign('goodsTypes',$goodsTypes);
	}


	public function getAttribute($goods_type_id){

		$model=D('Attribute');
		$rows=$model->getAttrByGoodsTypeId($goods_type_id);
		
		// dump($rows);
		$this->ajaxReturn($rows);
	}
}