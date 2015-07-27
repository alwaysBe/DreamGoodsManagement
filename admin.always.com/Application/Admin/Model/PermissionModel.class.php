<?php
namespace Admin\Model;
use Think\Model;
/**
 * 权限模型类
 */
use Admin\Service\NestedSetsService;

class PermissionModel extends BaseModel{

	//自动完成
	protected $_validate=array(
		array('name','require','权限名称不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		// array('url','require','权限地址不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('parent_id','require','父权限不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		// array('lft','require','左边界不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		// array('rght','require','右边界不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		// array('level','require','级别不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('intro','require','描述不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('status','require','状态不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('sort','require','排序不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
	);

	/**
	 * 权限添加,覆盖父类方法
	 */
	public function add(){
		$this->startTrans();//开始事务
		$db=new DbMysqlInterfaceImplModel();
		//实例化nestedSett类
		$nestedSetService=new NestedSetsService($db,'tp_permission', 'lft', 'rght', 'parent_id', 'id', 'level');
		//保存到数据库
		$rst=$nestedSetService->insert($this->data['parent_id'], $this->data,'bottom');
		if($rst===false){
			$this->rollback();
			return false;
		}
		$this->commit();
		return $rst;
	}

	/**
	 * 获取权限列表数据
	 * @param  boolean $include_root  是否包含最顶级分类信息,默认不包含
	 * @return array                  查询到的数据信息
	 */
	public function getListData($include_root=false){

		$where=array();
		if(!$include_root){
			//不包含时
			$where['id']=array('NEQ',1);
		}
		$rst=$this->where($where)->field('id,name,parent_id,status,intro,level')->order('lft')->where('status>-1')->select();
		return $rst;
	}

	/**
	 * 编辑保存,利用nestedSet工具,覆盖父类方法
	 * @return mixed  修改失败返回false,修改成功返回修改成功的返回值
	 */
	public function save(){

		$this->startTrans();
		//实例化DB类
		$db=new DbMysqlInterfaceImplModel();
		//实例化nestedSet工具类
		$nestedSetService=new NestedSetsService($db, 'tp_permission','lft', 'rght', 'parent_id', 'id', 'level');
		//移动权限
		$rst=$nestedSetService->moveUnder($this->data['id'], $this->data['parent_id']);
		if($rst===false){
			$this->rollback();
			return false;
		}
		//保存相关信息到数据库
		if(parent::save()===false){
			$this->rollback();
			return false;
		}
		return $this->commit();
	}

	/**
	 * 删除|修改状态
	 * @param  int  $id        主键ID
	 * @param  integer $status 要修改的状态,默认为-1->删除
	 * @return mixed           返回操作结果
	 */
	public function changeStatus($id,$status=-1){
		if($status==-1){
	    	$sql = "update tp_permission as children,tp_permission as parent set children.status = $status,children.name=concat(children.name,'_del')  where children.lft>=parent.lft and children.rght<=parent.rght and parent.id = $id";
		}else{
	    	$sql = "update tp_permission as children,tp_permission as parent set children.status = $status  where children.lft>=parent.lft and children.rght<=parent.rght and parent.id = $id";
		}
        return $this->execute($sql);
	}

}



 ?>