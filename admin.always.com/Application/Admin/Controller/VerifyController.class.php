<?php 
namespace Admin\Controller;
use Think\Controller;
//验证码生成控制器
class VerifyController extends Controller{
	/**
	 * 验证码方法
	 * @return [type] [description]
	 */
	public function index(){
		$verify=new \Think\Verify();//实例化
		$verify->length=4;//设置验证码位数
		$verify->entry();//生成验证码
	}
}



 ?>