<?php
namespace Admin\Model;
use Think\Model;
/**
 * 菜单模型
 */
use Admin\Service\NestedSetsService;
class MenuModel extends BaseModel{

	//自动验证
	protected $_validate=array(
		array('name','require','菜单名称不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		// array('url','require','菜单地址不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		// array('parent_id','require','父菜单不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('lft','require','左边界不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('rght','require','右边界不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('level','require','级别不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('intro','require','描述不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('status','require','状态不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('sort','require','排序不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
	);
	
	/**
	 * 菜单添加,并添加对应的权限,覆盖父类方法
	 * @param array $params post接受的所有数据
	 */
	public function add($params){
		$db=new DbMysqlInterfaceImplModel();
		$nestedSetService=new NestedSetsService($db,'tp_menu', 'lft', 'rght', 'parent_id', 'id', 'level');

		$menu_id=$nestedSetService->insert($this->data['parent_id'], $this->data,'bottom');
		if($menu_id===false){
			$this->error='添加菜单出错';
			return false;
		}
		$rest=$this->handleMenuPermission($params,$menu_id);
		if($rest===false){
			$this->error='添加菜单权限信息失败';
		}

		return $menu_id;
	}

	/**
	 * 处理菜单对应权限表数据
	 * @param  [array]  $params   接受的请求数据
	 * @param  [int]    $id       菜单ID
	 * @param  boolean 	$isUpdate 是否为更新数据,默认否
	 * @return [type]             保存成功返回保存成功的返回值,失败时返回false
	 */
	public function handleMenuPermission($params,$id,$isUpdate=false){

		$permissionModel=M('MenuPermission');
		if($isUpdate){
			//更新时,先删除对应信息
			$permissionModel->where(array('menu_id'=>$id))->delete();
		}
		$permissionIds=$params['permission_id'];
		$tmp=array();
		foreach ($permissionIds as $permission_id) {
			$tmp[]=array('menu_id'=>$id,'permission_id'=>$permission_id);
		}
		if(!empty($tmp)){
			//菜单对应权限不为空时,添加到数据库
			return $permissionModel->addAll($tmp);
		}

	}

	/**
	 * 获取所有数据列表
	 * @param  boolean $includeRootPath 是否包含最顶级分类项
	 * @return array                    查询到的数据
	 */
	public function getListData($includeRootPath=false){

		$where['status']=array('GT',-1);
		if(!$includeRootPath){
			//不需要包含最顶级信息时
			$where['level']=array('NEQ',1);
		}
		return  $this->field('id,name,url,parent_id,status,intro,sort,level')->where($where)->order('lft')->select();
	}

	/**
	 * 根据ID查找到对应菜单的所有数据,包括权限信息
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function find($id){

		$row=parent::find($id);

		if(!$row){
			return $row;
		}
		//查询权限信息
		$permissionIds=M('MenuPermission')->field('permission_id')->where(array('menu_id'=>$id))->select();
		$permissionIds=array_column($permissionIds,'permission_id');
		$row['permission_ids']=json_encode($permissionIds);

		return $row;
	}

	/**
	 * 编辑保存到数据,保存菜单级对应权限到数据库,覆盖父类方法
	 * @param  array $params post接受的所有数据
	 * @return mixed         保存失败时返回false,成功返回保存成功的返回信息
	 */
	public function save($params){

		$rst=parent::save();
		if($rst===false){
			$this->error='修改菜单出错';
			return false;
		}
		$rst=$this->handleMenuPermission($params,$params['id'],true);
		if($rst===false){
			$this->error='修改菜单权限出错';
			return false;
		}
		return $rst;
	}

	/**
	 * 删除|修改状态
	 * @param  int  $id        主键ID
	 * @param  integer $status 要修改的状态,默认为-1->删除
	 * @return mixed           返回操作结果
	 */
	public function changeStatus($id,$status=-1){
		if($status==-1){
	    	$sql = "update tp_menu as children,tp_menu as parent set children.status = $status,children.name=concat(children.name,'_del')  where children.lft>=parent.lft and children.rght<=parent.rght and parent.id = $id";
		}else{
	    	$sql = "update tp_menu as children,tp_menu as parent set children.status = $status  where children.lft>=parent.lft and children.rght<=parent.rght and parent.id = $id";
		}
        return $this->execute($sql);
	}
}



 ?>