<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 商品控制器类
 */
class GoodsController extends BaseController{

	/**
	 * 钩子方法,编辑或者添加商品时调用,用于展示数据
	 * @return null  无返回值
	 */
	public function _befor_edit(){

		//实例化商品分类模型
		$categoryModel=D('GoodsCategory');//实例化商品分类模型
		$supplierModel=D('Supplier');//实例化供应商模型
		$brandModel=D('Brand');//实例化商品类型
		$memberModel=D('MemberLevel');//实例化会员等级模型
		$goodsTypeModel=D('GoodsType');//实例化商品类型模型

		//获取商品分类列表
		$catList=$categoryModel->getListData();//获取商品分类数据
		$supList=$supplierModel->getListData();//获取供应商列表数据
		$brandList=$brandModel->getListData();//获取商品类型数据
		$memberLevelList=$memberModel->getListData();//获取会员等级信息数据
		$goodsTypeList=$goodsTypeModel->getListData();//获取商品类型数据
		//分配数据到页面
		$this->assign(array('catList'=>json_encode($catList),'supList'=>$supList,'brandList'=>$brandList));
		$this->assign(array('MemberLevelList'=>$memberLevelList,'goodsTypeList'=>$goodsTypeList));
	}

	/**
	 * 商品添加方法
	 */
	public function add(){
		//post方式请求时,持久化数据到商品表
		if(IS_POST){
			if($this->model->create()!==false){
				$all_params=I('post.',false);
				$all_params['intro']=$_POST['intro'];//单独获取商品描述信息,不需要对html标签转意
				
				if($this->model->add($all_params)!==false){
					$this->success('添加成功',U('index'));return ;
				}
			}
			$this->error('添加失败'.showErrorMsg($this->model));
		}else{//get方式请求时,分配表单页面
			$this->_befor_edit();//钩子方法,添加前提供数据操作
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
				$params=I('post.');

				$params['intro']=$_POST['intro'];
				if($this->model->save($params)!==false){
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
	 * 删除相册方法
	 * @return ajaxObject 成功放回success:true,失败返回success:false
	 */
	public function deleteGallery(){

		//获取传递过来的相册ID
		$id=I('post.gallery_id');
		//实例化商品相册模型
		$galleryModel=M('GoodsGallery');

		if($galleryModel->delete($id)!==false){
			//删除成功返回ajax数据success:true
			$this->ajaxReturn(array('success'=>true));
		}else{
			//删除失败时返回ajax数据success:false
			$this->ajaxReturn(array('success'=>false));
		}
	}

	

}