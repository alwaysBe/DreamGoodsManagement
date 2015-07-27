<?php 
namespace Admin\Controller;
use Think\Controller;
/**
 * 个人测试控制器,用户测试个别功能
 *  	>>>项目完成后将会删除<<<
 */
class TestController extends Controller{
	public function index(){
		if(IS_POST&&!empty($_FILES['head']['name'])){
			dump($_FILES['head']['name']);
			$model=D('Supplier');
			$model->uploadImg($_FILES['head']);
		}else{
			$this->display();
		}
	}
}

 ?>