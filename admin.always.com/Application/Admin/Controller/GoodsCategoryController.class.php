<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 商品分类控制器类
 */
class GoodsCategoryController extends BaseController{
	
	/**
	 * index列表方法
	 * @return null  无返回
	 */
	public function index(){

		$rows=$this->model->getPageList();
		//存储请求前的地址,方便跳转
		cookie('__forward__',$_SERVER['REQUEST_URI']);

		$this->assign('rows',$rows);
		$this->display();
	}


	/**
	 * 钩子函数,覆盖父类的方法,执行edit方法调用 
	 * @return [null] 无返回值
	 */
	public function _befor_edit(){

		//>>1.准备添加页面上需要的数据
        $rows = $this->model->getListData(true);
        
        $this->assign('rows',json_encode($rows));
	}
}