<?php 
namespace Admin\Behavior;
use \Think\Behavior;
/**
 * 权限拦截器
 */
class CheckPermissionBehavior extends Behavior{

	public function run(&$params){
		//获取用户当前请求路径
		$requestUrl = CONTROLLER_NAME.'/'.ACTION_NAME;
		
		//请求是否为忽略地址(如登陆,验证码等)
		if(in_array($requestUrl,C(IGNORE_URL))){
			return;
		}

		//用户未登录
		if(!isLogin()){
			//尝试自动登录
			$rst = D('Admin')->autoLogin();

			if($rst === false){
				//自动登陆失败,跳转到登陆页面
				header('Content-Type: text/html;charset=utf-8');
				redirect(U('Admin/login'), $time=2, $msg='请先登录,2秒后自动跳转到登陆页面');
			}
		}

		//记录用户信息到缓存
		$userinfo=adminInfoSession();
		
		//登陆成功,记录用户ID
		defined('UID') or define('UID',$userinfo['id']);
		//当超级管理员登陆时,不做任何验证
		if($userinfo['id']==C('SUPPER_ADMIN')){
			return;
		}
		//获取用户拥有的权限列表
		$urls=array_column($userinfo['permission_ids'],'url');
		
		//判断用户请求是否在权限列表中
		if(!in_array($requestUrl,$urls)){
			header("Content-Type: text/html;charset=utf-8");
			echo '没有权限访问';
			exit;
		}
	}
}


 ?>