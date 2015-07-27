<?php
namespace Admin\Model;
use Think\Model;
/**
 * 角色模型
 */
class RoleModel extends BaseModel{

	//自动验证
	protected $_validate=array(
		array('name','require','角色名称不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('intro','require','描述不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('status','require','状态不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('sort','require','排序不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
	);

	/**
	 * 添加角色,保存角色对应的权限信息,覆盖父类方法
	 * @param array $params post接受的所有数据
	 */
	public function add($params){
		$id=parent::add();
		if($id===false){
			return false;
		}
		//保存数据到角色权限中间表
		$this->handleRolePermission($params,$id);
		return $id;
		
	}

	/**
	 * 处理角色权限信息
	 * @param  array    $params    post接受的所有数据
	 * @param  int      $id        角色ID
	 * @param  boolean  $is_update 是否更新,默认否,如果为更新,则先删除对应权限信息
	 * @return boolean             保存失败返回false,成功无返回值
	 */
	public function handleRolePermission($params,$id,$is_update=false){

        $permission_ids=$params['permission_id'];
		$rows=array();
		if($is_update){
			//请求为更新数据 时,先进行删除操作
			M('RolePermission')->where(array('role_id'=>$id))->delete();
		}
		foreach ($permission_ids as $permission_id) {
			$rows[]=array('role_id'=>$id,'permission_id'=>$permission_id);
		}
		if(!empty($rows)){
			$rst=M('RolePermission')->addAll($rows);
			if($rst===false){
				return false;
			}
		}
    }

    /**
     * 根据ID查找角色信息以及对应的权限信息
     * @param  int    $id  角色ID
     * @return array       查询到的对应信息
     */
	public function find($id){

		$row=parent::find($id);
		if(!$row){
			return $row;
		}
		//h获取对应的权限信息
		$permission_ids=M('RolePermission')->field('permission_id')->where(array('role_id'=>$id))->select();
		$permission_ids=array_column($permission_ids,'permission_id');
		$row['permission_ids']=json_encode($permission_ids);
		return $row;
	}

	/**
	 * 编辑更新角色信息,保存对应权限,覆盖父类方法
	 * @param  array $params post接受的所有信息
	 * @return mixed         保存失败返回false,成功返回保存成功的返回值
	 */
	public function save($params){
		
		$rst=parent::save();
		if($rst===false){
			return false;
		}
		$rst=$this->handleRolePermission($params,$params['id'], true);
		return $rst;
	}

}



 ?>