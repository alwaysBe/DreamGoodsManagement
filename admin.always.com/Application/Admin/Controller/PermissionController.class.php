<?php
namespace Admin\Controller;
use Think\Controller;
/**
 *权限控制器
 */
class PermissionController extends BaseController{

	//编辑添加钩子函数
	public function _befor_edit(){

		$rows=$this->model->getListData(true);
		$this->assign('rows',json_encode($rows));
	}

	/**
	 * index列表方法
	 * @return null  无返回
	 */
	public function index(){

		$rows=$this->model->getListData();
		//存储请求前的地址,方便跳转
		cookie('__forward__',$_SERVER['REQUEST_URI']);

		$this->assign('rows',$rows);
		$this->display();
	}
}