<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 文章控制器
 */
class ArticleController extends BaseController{

	/**
	 * 编辑,添加前的钩子方法,用于获取相关数据
	 * @return null 无返回值
	 */
	public function _befor_edit(){
		
		$artCatModel = D('ArticleCategory');
		//获取文章分类数据
		$artCategoryList = $artCatModel->getListData();
		//分配到页面
		$this->assign('artCategoryList',$artCategoryList);
	}

	/**
	 * add方法,向数据库添加数据时调用
	 * 		当以GET方式请求时,分配表单页面
	 *   	当以POST方式请求时,保存数据到数据存
	 */
	public function add(){

		if(IS_POST){//判断是否为POST方式请求
			
			// 自动手机提交的数据
			if($this->model->create() !== false){
				//将数据添加到数据库
				$params = I('post.');
				$params['content'] = $_POST['content'];
				if($this->model->add($params) !== false){
					$this->success('添加成功',U('index'));return ;
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
	public function edit($id = ''){
		
		//判断: 当以POST方式请求时,保存数据到数据存
		if(IS_POST){

			if($this->model->create() !== false){
				$params = I('post.');
				$params['content'] = $_POST['content'];
				if($this->model->save($params) !== false){
					$this->success('更新成功',cookie('__forward__'));return ;
				}
			}

			$this->error('更新失败'.showErrorMsg($this->model));

		//当以GET方式请求时,分配表单页面
		}else{
			//钩子函数,分配表单页面前,如果要分配其它数据,可以实现此方法
			$this->_befor_edit();

			//通过ID查找到对应的数据
			$row = $this->model->find($id);
			//将数据分配到页面
			$this->assign($row);
			$this->display();//展示页面

		}
	}

	/**
	 * 通过关键字查找文章
	 * @param  [string] $kw [文章名称关键字]
	 * @return [array]      [查找到的文章]
	 */
	public function searchArticle($kw){

		$model = D('Article');//实例化模型
		
		$where['name'] = array('LIKE',"%$kw%");
		$model->where($where);
		$model->field('id,name');
		$rows = $model->select();

		echo json_encode($rows);
	}
}