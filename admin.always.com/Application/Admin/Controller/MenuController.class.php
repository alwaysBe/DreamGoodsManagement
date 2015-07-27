<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 菜单控制器
 */
class MenuController extends BaseController{

	//使用post所有数据
	public $usePostParams=true;
	/**
	 * index列表方法
	 * @return null  无返回
	 */
	public function index(){

		$rows=$this->model->getListData(true);
		//存储请求前的地址,方便跳转
		cookie('__forward__',$_SERVER['REQUEST_URI']);

		$this->assign('rows',$rows);
		$this->display();
	}

	/**
	 * 钩子函数
	 * @return null 无返回值
	 */
	public function _befor_edit(){

		$row=$this->model->getListData(true);
		$permissions=D('Permission')->getListData(true);

		$this->assign('menus',json_encode($row));
		$this->assign('permissions',json_encode($permissions));
	}


}