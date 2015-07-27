<?php 
namespace Admin\Controller;
use Think\Controller;
/**
 * 基础控制器类
 */
class BaseController extends Controller{

	protected $model;
	//使用create收集到的数据操作数据库
	public $usePostParams=false;

    /*
     * 析构函数,实例化或者被继承时自动执行
     */
	public function _initialize(){
		
		//实例化模型
		$this->model=D(CONTROLLER_NAME);
		$menuList=session('menuList');
		$this->assign('menuList',$menuList);
	}
	
	/**
	 * index方法,通过用户请求,分配展示页面(并根据需要分配数据)
	 * @return null 无返回值
	 */
	public function index(){

		//>>获取关键字
		$kw=I('get.kw');
		// dump(strpos($kw,'%'));
		$kw=rawurldecode($kw);//自动解码关键字,无需解码时,不执行

		//如果存在关键字,则添加查询条件
		if(!empty($kw)){
			$where['t.name']=array('like',"%{$kw}%");
		}

		//钩子方法,通过商品类型ID过滤
		$this->_set_gds_type_id($where);
		if($where['goods_type_id']){
			$this->assign('goods_type_id',$where['goods_type_id']);
		}

		$rst=$this->model->showList($where);
		//存储地址,方便回跳
		cookie('__forward__',$_SERVER['REQUEST_URI']);

		$this->assign($rst);//分配数据
		//钩子方法,子类定义时,huidiaoyong
		$this->_befor_index();
		$this->display();
	}

	/**
	 * add方法,向数据库添加数据时调用
	 * 		当以GET方式请求时,分配表单页面
	 *   	当以POST方式请求时,保存数据到数据存
	 */
	public function add(){

		if(IS_POST){//判断是否为POST方式请求
			
			// 自动手机提交的数据
			if($this->model->create()!==false){
				//将数据添加到数据库
				$id=I('post.goods_type_id');
				if($this->model->add($this->usePostParams?I('post.'):'')!==false){
					$this->success('添加成功',U('index',array('id'=>$id)));return ;
				}
			}
			$this->error('添加失败'.showErrorMsg($this->model));
		}else{
			//钩子函数,分配表单页面前,如果要分配其它数据,可以实现此方法
			$this->_befor_edit();
			$this->display('edit');
		}
	}

	/**
	 * edit编辑方法,修改数据时调用
	 * @param  int  $id 参数绑定,get请求时自动获取传递过来的id
	 * @return null     无返回值
	 */
	public function edit($id=''){
		
		//判断: 当以POST方式请求时,保存数据到数据存
		if(IS_POST){

			if($this->model->create()!==false){
				if($this->model->save($this->usePostParams?I('post.'):'')!==false){
					$this->success('更新成功',cookie('__forward__'));return ;
				}
			}

			$this->error('更新失败'.showErrorMsg($this->model));

		//当以GET方式请求时,分配表单页面
		}else{
			//钩子函数,分配表单页面前,如果要分配其它数据,可以实现此方法
			$this->_befor_edit();

			//通过ID查找到对应的数据
			$row=$this->model->find($id);
			
			//将数据分配到页面
			$this->assign($row);
			$this->display();//展示页面

		}
	}

	/**
	 * 删除数据方法
	 * @param  int|array  $id     要删除的数据对于的ID,单个或者数组
	 * @param  integer    $status 数据状态,默认-1表示删除
	 * @return null               无返回值
	 */
	public function changeStatus($id=0,$status=-1){
		
		if(IS_POST){
			$id=I('post.id');
			foreach($id as $k=>$v){
				$id[$k]=$k;
			}
			if(empty($id)){
				$this->error('操作失败,请选择要删除的项');
				return false;
			}
		}
		$rst=$this->model->changeStatus($id,$status);
		
		if($rst!==false){
			$this->success('操作成功',cookie('__forward__'));return ;
		}

		$this->error('操作失败'.showErrorMsg($this->model));
	}

	
	/**
	 * 钩子函数,被子类覆盖
	 * @return [mixed] [description]
	 */
	public function _befor_edit(){}
	public function _befor_index(){}
	public function _set_gds_type_id(&$where){}

}


 ?>