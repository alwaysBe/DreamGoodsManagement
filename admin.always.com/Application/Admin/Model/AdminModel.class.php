<?php
namespace Admin\Model;
use Think\Model;
use Org\Util\String;
/**
 * 管理员模型类
 */
class AdminModel extends BaseModel{

	//自动验证规则
	protected $_validate=array(
		array('username','require','角色名称不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('password','require','密码不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('repassword','password','确认密码与密码不一致不正确',0,'confirm'), // 验证确认密码是否和密码一致
		array('email','require','Email不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('salt','require','随机字符串不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('intro','require','描述不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('status','require','状态不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('sort','require','排序不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('verify','checkVerify','验证码验证失败',self::EXISTS_VALIDATE,'function',self::MODEL_BOTH),
	);

	//自动完成规则
	protected $_auto=array(
		array('salt','\Org\Util\String::randString',self::MODEL_INSERT,'function'),
	);

	/**
	 * 管理员添加方法,覆盖父类add方法,完成其他相关逻辑
	 * @param array $params post接受的所有数据
	 */
	public function add($params){
		
		//对密码进行加密处理
		$this->data['password']=myMd5($this->data['password'],$this->data['salt']);
		//调用父类ADD方法,添加到管理员表
		$id=parent::add();

		if($id===false){
			//添加到管理员表失败时返回false
			return false;
		}
		//处理管理员权限
		$rst=$this->handlerAdminRole($params,$id);
		//添加失败时返回false
		if($rst===false){
			return false;
		}
		//处理管理员额外权限
		$rst=$this->handleExtraPermission($params,$id);
		//添加额外失败,返回false
		if($rst===false){
			return false;
		}
		//添加成功,,返回管理员id
		return $id;
	}

	/**
	 * 处理管理员角色方法
	 * @param  array    $params    post接受的所有数据
	 * @param  int      $id        管理员ID
	 * @param  boolean  $is_update 是否为更新角色信息
	 * @return mixed               添加失败返回false,成功时返回添加成功的返回信息
	 */
	public function handlerAdminRole($params,$id,$is_update=false){

		$model=M('AdminRole');
		if($is_update){
			//更新数据时,先删除管理员角色相关数据,在进行添加
			$rst=$model->where(array('admin_id'=>$id))->delete();
			//删除失败时
			if($rst===false){
				return false;
			}
		}
		//获得角色相关数据
		$roles=$params['role'];
		$tmp=array();
		//遍历出所有角色数据
		foreach($roles as $role){
			$tmp[]=array('admin_id'=>$id,'role_id'=>$role);
		}
		if(!empty($tmp)){
			//角色数据非空时,添加所有
			return $model->addAll($tmp);
		}
	}

	/**
	 * 处理额外权限方法
	 * @param  array    $params    post接收的所有数据
	 * @param  int      $id        管理员ID
	 * @param  boolean  $is_update 是否是更新管理员数据,默认否
	 * @return mixed               添加失败返回false,成功时返回添加成功的返回值        
	 */
	public function handleExtraPermission($params,$id,$is_update=false){

		$model=M('AdminPermission');
		if($is_update){
			//更新数据时,先删除相关权限,再添加
			$rst=$model->where(array('admin_id'=>$id))->delete();
			if($rst===false){
				return false;
			}
		}
		//获得额外权限信息
		$extraPerms=$params['permission_id'];
		$tmp=array();
		//遍历出所有的额外权限数据
		foreach($extraPerms as $extraPerm){
			$tmp[]=array('admin_id'=>$id,'permission_id'=>$extraPerm);
		}
		if(!empty($tmp)){
			//添加额外数据
			return $model->addAll($tmp);
		}
	}

	/**
	 * 管理员查找,用于编辑回显对应管理员信息,包括角色,额外权限信息
	 * @param  int    $id 要查询的管理员ID
	 * @return array      查找到的用户信息
	 */
	public function find($id){

		//查询管理员数据
		$row=parent::find($id);
		if($row){
			$where=array('admin_id'=>$id);
			//查询管理员角色信息
			$role_ids=M('AdminRole')->where($where)->select();
			//查询额外权限信息
			$permission_ids=M('AdminPermission')->where($where)->select();
			//展示时需要json对象,转换为json对象
			$row['role_id']=json_encode(array_column($role_ids,'role_id'));
			$row['permission_id']=json_encode(array_column($permission_ids,'permission_id'));
		}

		return $row;
	}

	/**
	 * 编辑保存管理员信息
	 * @param  array $params  post接收的所有数据
	 * @return mixed          保存成功返回保存成功的返回值,失败是返回false
	 */
	public function save($params){

		//保存到管理员表
		$rst=parent::save();
		if($rst===false){
			//保存失败时,返回false
			$this->model->error='管理员信息修改失败';
			return false;
		}
		//处理角色数据
		$rst=$this->handlerAdminRole($params,$params['id'],true);
		if($rst===false){
			$this->model->error='修改管理员角色失败';
			return false;
		}
		//处理额外权限数据i
		$rst=$this->handleExtraPermission($params,$params['id'],true);
		if($rst===false){
			$this->model->error='修改管理员额外权限失败';
			return false;
		}
		return $rst;
	}

	/**
	 * 初始化密码
	 * @param  int   $id 管理员ID
	 * @return mixed     保存结果,失败时返回false
	 */
	public function initPassword($id){

		//重新生成盐字符串
		$salt=String::randString();
		$password=myMd5('123456',$salt);
		
		$this->where(array('id'=>$id));
		//保存盐和密码到数据库
		return parent::save(array('password'=>$password,'salt'=>$salt));
	}
	/**
	 * 用户登录方法
	 * @return mixed 登陆成功返回用户信息,失败返回false
	 */
	public function login(){
		
		$username=$this->data['username'];
		$password=$this->data['password'];
		$row=$this->where('status>-1')->getByUsername($username);
		if($row){
			if($row['status']==0){
				$this->error='您的账号已被冻结,请联系相关负责人';
				return false;
			}
			if($row['password']==myMd5($password,$row['salt'])){
				//登陆成功,如果登陆用户不是超级管理员,则获取用户的权限信息
				if($row['id']!=C('SUPPER_ADMIN')){
					$row['permission_ids']=$this->getPermissionByAdminId($row['id']);
				}
				return $row;
			}else{
				$this->error='密码有误';
				$row=null;
				return false;
			}
		}else{
			$this->error='账号不存在';
			return false;
		}
	}

	/**
	 * 根据管理员ID查找对应的权限信息
	 * @param  int    $id 管理员ID
	 * @return array       查询结果
	 */
	public function getPermissionByAdminId($id){

		$sql = <<<SQL
select p.id,p.url from tp_permission as p where p.id in
(select rp.permission_id from tp_admin_role as ar  join tp_role_permission as rp on ar.role_id = rp.role_id where ar.admin_id={$id})
or p.id in  (select ap.permission_id from tp_admin_permission as ap where ap.admin_id={$id})
SQL;
	$rows=$this->query($sql);

	return $rows;
	}

	/**
	 * 自动登陆方法,记录用户信息
	 * @param  int     $id   用户ID
	 * @param  string  $salt 盐字符串
	 * @return null          无返回值
	 */
	public function remember($id,$salt){

		cookie('uid',$id,604800);//存储一个星期
		//随机生成uk字符串
		$uk=String::randString();
		//保存uk字符串到数据库
		$rst=parent::save(array('id'=>$id,'uk'=>$uk));
		if($rst===false){
			$this->error='记录信息失败';
		}
		cookie('uk',myMd5($uk,$salt),604800);//存储一个星期
	}

	/**
	 * 自动登陆方法
	 * @return boolean 自动登录成功时返回true,失败返回false
	 */
	public function autoLogin(){

		//获取cookie里面的uid,uk数据
		$uid=cookie('uid');
		$uk=cookie('uk');

		if(empty($uid)||empty($uk)){
			//不存在时,不自动登陆,返回false
			return false;
		}
		//更具uid查询相关信息
		$this->field('id,username,uk,salt,email,status,password')->where('status>-1');
		$row=parent::find($uid);
		//有数据,uk相同,账户可以用时,登陆成功
		if( $row && ($uk==myMd5($row['uk'],$row['salt'])) && $row['status']==1 ){
			if($row['id']!=C('SUPPER_ADMIN')){
				$row['permission_ids']=$this->getPermissionByAdminId($row['id']);
			}
			//记录信息到session
			adminInfoSession($row);
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 修改密码
	 * @param  array $params post接收的所有数据
	 * @return mixed         修改失败时返回false,成功返回成功的返回消息
	 */
	public function updatePassword($params){
		
		//新旧密码不能相同
		if($params['oldpassword']==$params['password']){
			$this->error='新密码不能与旧密码相同';
			return false;
		}
		$userinfo=adminInfoSession();

		if($userinfo['password'] == myMd5($params['oldpassword'],$userinfo['salt'])){
			$salt=String::randString();//修改密码,重新生成盐加密
			$newPass=myMd5($params['password'],$salt);
			//保存新密码及新生成的盐
			$this->where(array('id'=>$userinfo['id']));
			return parent::save(array('password'=>$newPass,'salt'=>$salt));
		}else{
			$this->error='旧密码有误';
			return false;
		}
	}

	


}
 ?>