<?php
namespace Admin\Model;
use Think\Model;
/**
 * 文章模型
 */
class ArticleModel extends BaseModel{

	//自动验证
	protected $_validate=array(
		array('name','require','新闻名称不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('article_category_id','require','不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('status','require','状态不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('sort','require','排序不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('inputtime','require','添加时间不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('clicknum','require','不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
	);

	//自动完成
	protected $_auto=array(
		array('inputtime',NOW_TIME),//密码自动加密
	);

	/**
	 * 文章添加,覆盖父类添加方法
	 * @param array $params post接收的所有数据
	 */
	public function add($params){

		//添加到文章表,并接受生成的ID
		$id=parent::add();

		if($id===false){
			//添加失败,返回false
			return false;
		}

		$content=array('article_id'=>$id,'content'=>$params['content']);
		//添加到文章内容表
		$rst=M('ArticleContent')->add($content);
		if($rst===false){
			return false;
		}
		return $id;
	}

	/**
	 * 文章查找
	 * @param  int   $id 要查找的文章ID
	 * @return array     查找到的文章及文章内容数据
	 */
	public function find($id){

		$row=parent::find($id);
		if(!$row){
			return $row;
		}
		//查找文章内容数据
		$content=D('ArticleContent')->getFieldByArticle_id($id,'content');
		$row['content']=$content;
		return $row;
	}

	/**
	 * 文章编辑保存,覆盖父类save方法
	 * @param  array  $params post请求接受的所有数据
	 * @return mixed          保存失败是返回false,成功返回保存成功的信息
	 */
	public function save($params){

		$rst=parent::save();
		if($rst===false){
			return false;
		}
		//保存到文章内容表
		$rst=M('ArticleContent')->where(array('article_id'=>$params[id]))->setField('content',$params['content']);
		if($rst===false){
			return false;
		}
		return $rst;
	}

	
}



 ?>