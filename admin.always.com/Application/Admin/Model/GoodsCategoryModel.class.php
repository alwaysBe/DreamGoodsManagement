<?php
namespace Admin\Model;
use Think\Model;
use Admin\Service\NestedSetsService;
/**
 *商品分类模型类 
 */
class GoodsCategoryModel extends BaseModel{

	protected $_validate=array(
		array('name','require','分类名称不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('parent_id','require','父分类不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('lft','require','左边界不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('rght','require','右边界不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('level','require','级别不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('intro','require','描述不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('status','require','状态不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('sort','require','排序不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
	);

	/**
	 * 获取数据信息
	 * @param  boolean $flag 是否查询最父分类
	 * @return array         c查询结果
	 */
	public function getPageList($flag=false){

		$where['status']=array('GT',-1);
		if(!$flag){
			$where['level']=array('NEQ',1);
		}
		return $this->field('id,name,parent_id,status,intro,level')->where($where)->order('lft')->select();
	}

	/**
	 * 分类添加方法
	 */
	public function add(){

		$this->startTrans();//开始事务
		//实例化DB类
		$db=new DbMysqlInterfaceImplModel();
		//实例化nestedSets服务类
		$netstedService=new NestedSetsService($db,'tp_goods_category','lft','rght','parent_id','id', 'level');
		//执行插入操作
		$rst=$netstedService->insert($this->data['parent_id'], $this->data,'bottom');

		if($rst===false){
			//执行失败,事务回滚
			$this->rollback();
			return false;
		}
		$this->commit();
		return $rst;
	}

	/**
	 * 分类编辑保存
	 * @return mixed 执行成功:返回执行结果,执行失败:返回false
	 */
	public function save(){

		$this->startTrans();//开启事务
		//实例化db类
		$db=new DbMysqlInterfaceImplModel();
		//实例化netsted服务类
		$netstedService=new NestedSetsService($db,'tp_goods_category','lft','rght','parent_id','id', 'level');
		//执行修改操作
		$rst=$netstedService->moveUnder($this->data['id'],$this->data['parent_id']);

		if($rst===false){
			//执行失败,回滚事务
			$this->rollback();
			//设置错误信息
			$this->error='不要乱了辈分';
			return false;
			exit;
		}
		if(parent::save()===false){
			//保存其他字段信息
			$this->rollback();
			$this->error='更改信息失败';
			return false;
			exit;
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
	    	$sql = "update tp_goods_category as children,tp_goods_category as parent set children.status = $status,children.name=concat(children.name,'_del')  where children.lft>=parent.lft and children.rght<=parent.rght and parent.id = $id";
		}else{
	    	$sql = "update tp_goods_category as children,tp_goods_category as parent set children.status = $status  where children.lft>=parent.lft and children.rght<=parent.rght and parent.id = $id";
		}
        return $this->execute($sql);
	}

}



 ?>