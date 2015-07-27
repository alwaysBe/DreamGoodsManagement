<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 角色控制器
 */
class RoleController extends BaseController{

	//使用post中所有数据
	public $usePostParams=true;

	/**
	 * 编辑添加钩子函数,获取并分配相关数据到页面
	 * @return [type] [description]
	 */
	public function _befor_edit(){

		//获取权限列表数据
		$rows=D('Permission')->getListData(true);
		$this->assign('rows',json_encode($rows));
	}

}