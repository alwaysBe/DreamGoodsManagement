<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 管理员控制器
 */
class AdminController extends BaseController{

	//使用POST接受的所有数据
	public $usePostParams = true;

	/**
	 * 编辑,添加前的钩子方法,用于获取相关数据
	 * @return null 无返回值
	 */
	public function _befor_edit(){

		//获取角色列表数据
		$roles = D('Role')->getListData(true);
		//获取权限列表数据
		$permissions = D('Permission')->getListData(true);
		//分配数据到页面
		$this->assign('roles',$roles);
		$this->assign('rows',json_encode($permissions));
	}

	/**
	 * 初始化密码
	 * @param  int $id    管理员账号ID
	 * @return null        无返回值
	 */
	public function initPassword($id){

		//初始化密码
		$rst=$this->model->initPassword($id);
		if($rst === false){
			//初始化失败
			$this->error('密码重置失败');
		}else{
			//初始化成功
			$this->success('密码已经重置为:123456,请牢记您的密码');
		}
	}

	/**
	 * 用户登录
	 * @return null 无返回值
	 */
	public function login(){

		//POST请求时
		if(IS_POST){
			//验证post数据
			if($this->model->create() !== false){
				$userinfo = $this->model->login();
				//登陆成功,返回用户信息和权限信息
				if(is_array($userinfo)){
					//登陆成功
					//存储登陆信息
					adminInfoSession($userinfo);
					//读取用户的权限
					//用户勾选自动登陆时,记录用户相关信息
					if(I('post.remember')){
						$rst=$this->model->remember($userinfo['id'],$userinfo['salt']);
						if($rst === false){
							$this->error('登陆失败'.showErrorMsg($this->model));
						}
					}
					defined('ADMINID') or define('ADMINID',$userinfo['id']);
					$this->success('登陆成功',U('Index/index'));exit;
				}
			}
			$this->error('登陆失败'.showErrorMsg($this->model),U('login'));
		}else{
			//get请求时,分配登陆页面
			$this->display();
		}
	}

	/**
	 * 注销登陆
	 * @return null 无返回值
	 */
	public function logout(){

		loginOut();
		$this->success('注销成功',U('login'));
		//避免继续执行后续代码
		exit;
	}

	/**
	 * 管理员修改密码
	 * @return null 无返回值
	 */
	public function changePassword(){

		if(IS_POST){
			if($this->model->create() !== false){
				$rst = $this->model->updatePassword(I('post.'));
				if($rst !== false){
					$this->success('修改成功',U('login'));
					//修改成功,注销账户,重新登录
					loginOut();
					return;
				}
			}
			$this->error('修改失败'.showErrorMsg($this->model));
		}else{
			$this->display('changePass');
		}
	}
}