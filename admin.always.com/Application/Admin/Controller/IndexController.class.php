<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 后台首页控制器
 */
class IndexController extends Controller {

    public function index(){
    	$menuList=session('menuList');
    	//如果session里面存在菜单列表,则直接获取,否则连接数据库查询
    	if(empty($menuList)){
	    	//请求地址不在忽略列表时,获取菜单列表
				$userinfo=adminInfoSession();
				if($userinfo['id']==C('SUPPER_ADMIN')){
					///超级管理员,获取所有菜单信息
					$menuList=M()->query("select distinct m.id,m.name,m.url,m.url,m.parent_id,m.level from tp_menu as m");
				}else{
					//其他管理员,按照权限获取菜单列表
					$permission_ids=array_column($userinfo['permission_ids'],'id');
					$permission_ids=arr2str($permission_ids);
					$menuList=M()->query("select distinct m.id,m.name,m.url,m.url,m.parent_id,m.level from tp_menu as m join tp_menu_permission as mp on m.id=mp.menu_id where mp.permission_id in ({$permission_ids}) order by m.lft");
				}
				//存储菜单列表到session方便下次使用
				session('menuList',$menuList);
			}
		//分配到页面
		$this->assign('menuList',$menuList);
    	$this->display();
    }
}