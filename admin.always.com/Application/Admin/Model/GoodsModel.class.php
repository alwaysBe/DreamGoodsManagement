<?php
namespace Admin\Model;
use Think\Model;
/**
 * 商品模型类
 */
class GoodsModel extends BaseModel{

	//自动验证
	protected $_validate=array(
		array('name','require','商品名称不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('logo','require','LOGO不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('goods_category_id','require','分类不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('supplier_id','require','供货商不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('brand_id','require','品牌不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('market_price','require','市场价不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('shop_price','require','本店价格不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('is_on_sale','require','是否上架不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('goods_type_id','require','商品类型不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		// array('goods_status','require','商品状态不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('status','require','状态不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
		array('sort','require','排序不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),
	);

	protected $_auto=array(
		array('inputtime',NOW_TIME),
		array('admin_id',UID),
	);

	/**
	 * 商品添加,覆盖父类add方法
	 * @param array $params post接受的所有数据
	 */
	public function add($params){
		
		$this->startTrans();//开启事务

		$this->handlerGoodsStatus();//处理商品状态
		$id=parent::add();
		if($id===false){
			$this->error='商品添加失败';
			$this->rollback();
			return false;
		}
		//处理商品简介
		$rst=$this->handlerGoodsIntro($params,$id);
		if($rst===false){
			$this->error='添加商品描述出错';
			$this->rollback();
			return false;
		}
		//处理会员价格
		$rst=$this->handlerMemberPrice($params,$id);
		if($rst===false){
			$this->error='添加会员价格出错';
			$this->rollback();
			return false;
		}
		//处理商品相册
		$rst=$this->handlerGoodsGallery($params,$id);
		if($rst===false){
			$this->error='相册添加失败';
			$this->rollback();
			return false;
		}
		//处理商品文章
		$rst=$this->handlerGoodsArticle($params,$id);
		if($rst===false){
			$this->error='添加商品文章失败';
			$this->rollback();
			return false;
		}
		//处理商品属性
		$rst=$this->handleGoodsAttribute($params,$id);
		if($rst===false){
			$this->error='添加商品属性失败';
			$this->rollback();
			return false;
		}
		$this->commit();
		return $id;

	}

	/**
	 * 处理商品属性
	 * @param  array     $params post接受的所有参数
	 * @param  int       $id     商品ID
	 * @return mixed             成功返回增加成功的返回值,失败返回false
	 */
	public function handleGoodsAttribute($params,$id){
		$goodsAttributes=$params['attribute'];
		$temps=array();
		foreach($goodsAttributes as $attr_id=>$value){
			if(is_array($value)){
				foreach($value as $v){
					$temps[]=array('goods_id'=>$id,'attribute_id'=>$attr_id,'value'=>$v);
				}
			}else{
				$temps[]=array('goods_id'=>$id,'attribute_id'=>$attr_id,'value'=>$value);
			}
		}

		if(!empty($temps)){
			return M('GoodsAttribute')->addAll($temps);
		}
	}

	/**
	 * 处理商品文章
	 * @param  array   $params post接受的所有数据
	 * @param  int     $id     商品id
	 * @return mixed           成功返回添加成功消息,失败返回false
	 */
	public function handlerGoodsArticle($params,$id){

		$article_ids=$params['article_id'];
		$articleModel=D('GoodsArticle');
		//当文章不为空时
		if(!empty($article_ids)){
			//先删除商品文章
			$articleModel->where(array('goods_id'=>$id))->delete();
			$tmp=array();
			foreach($article_ids as $article_id){
				$tmp[]=array('goods_id'=>$id,'article_id'=>$article_id);
			}
			//添加数据
			if(!empty($tmp)){
				$rst=$articleModel->addAll($tmp);
				return $rst;
			}
		}

		
	}


	/**
	 * 处理商品相册
	 * @param  array   $params post接受的所有数据
	 * @param  int     $id     商品ID
	 * @return mixed           成功返回添加成功消息,失败返回false
	 */
	public function handlerGoodsGallery($params,$id){

		$goodsGalleryModel=M('GoodsGallery');
		$gallerys=$params['gallery'];
		//相册不为空时
		if(!empty($gallerys)){
			$rows=array();
			foreach ($gallerys as $item) {
				$rows[]=array('goods_id'=>$id,'path'=>$item);
			}
			//添加到数据库
			if(!empty($rows)){
				$rst=$goodsGalleryModel->addAll($rows);
				return $rst;
			}
		}
	}

	/**
	 * 处理商品介绍
	 * @param  array   $params post请求的所有数据
	 * @param  int     $id     商品id
	 * @return mixed           成功返回添加成功消息,失败返回false
	 */
	public function handlerGoodsIntro($params,$id){

		$intro=$params['intro'];
		if(!empty($intro)){
			//intro存在时,先删除再添加
			$goodsIntrModel=M('GoodsIntro');
			$goodsIntrModel->delete($id);
			$rst=$goodsIntrModel->add(array('goods_id'=>$id,'intro'=>$intro));
		}

		return $rst;
	}

	/**
	 * 处理会员价格
	 * @param  array   $params post接受 的所有数据
	 * @param  int     $id     商品ID
	 * @return mixed           成功返回添加成功消息,失败返回false
	 */
	public function handlerMemberPrice($params,$id){

		$MemberPriceModel=M('GoodsMemberPrice');
		$MemberPriceModel->where(array('goods_id'=>$id))->delete();

		$prices=$params['memberPrice'];

		$tmp=array();
		foreach($prices as $ml_id=>$price){
			if($price && is_numeric($price)){
				$tmp[]=array('member_level_id'=>$ml_id,'goods_id'=>$id,'price'=>$price);
			}
		}

		if(!empty($tmp)){
			$rst=$MemberPriceModel->addAll($tmp);
			return $rst;
		}

	}

	/**
	 * 处理商品状态,讲商品状态变为二进制
	 * @return null 无返回值
	 */
	public function handlerGoodsStatus(){
		$new_status=0;
		foreach ($this->data['goods_status'] as $value) {
			$new_status=$new_status|$value;
		}
		$this->data['goods_status']=$new_status;

	}

	/**
	 * 钩子方法,查询时添加额外的查询条件
	 */
	public function _setModel(){

		$this->field('t.*,gc.name as goods_category_name,s.name as supplier_name,b.name as brand_name');
		$this->join('__GOODS_CATEGORY__ as gc on t.goods_category_id=gc.id');
		$this->join('__SUPPLIER__ as s on t.supplier_id=s.id');
		$this->join('__BRAND__ as b on t.brand_id=b.id');

	}

	/**
	 * 钩子方法.处理商品状态		
	 * @param  array &$rows 需要处理的数组
	 * @return null         无返回值        
	 */	
	public function _handleRows(&$rows){

		foreach ($rows as &$row) {
			$status=$row['goods_status'];
			$row['is_mad']  = $status & 1 ? 1 : 0;
			$row['is_hot'] = $status & 2 ? 1 : 0;
			$row['is_tui']  = $status & 4 ? 1 : 0;
			$row['is_new']  = $status & 8 ? 1 : 0;
			$row['is_like']  = $status & 16 ? 1 : 0;
		}
	}

	/**
	 * 商品查询,查询出商品的介绍,会员价格,商品相册,相关文章等信息.覆盖父类查询方法
	 * @param  int    $id 商品ID
	 * @return array      查询到的商品数据
	 */
	public function find($id){
		
		$row=parent::find($id);
		//兼容基础类
		if(!$row){
			return $row;
		}
		//查询商品介绍信息
		$intro=M('GoodsIntro')->getFieldByGoods_id($id,'intro');
		$row['intro']=$intro;
		//查询商品价格
		$goodsMemberPrice=M('GoodsMemberPrice')->field('member_level_id,price')->where(array('goods_id'=>$id))->select();
		
		if(!empty($goodsMemberPrice)){
			$member_level_ids=array_column($goodsMemberPrice,'member_level_id');
			$prices=array_column($goodsMemberPrice,'price');
			$goodsMemberPrices=array_combine($member_level_ids,$prices);
			$row['goodsMemberPrices']=$goodsMemberPrices;
		}
		//查询商品相册数据
		$galleryList=M('GoodsGallery')->field('id,path')->where(array('goods_id'=>$id))->select();
		$row['goodsGallery']=$galleryList;
		//查询相关文章信息
		$goodsArticleList=$this->getGoodsArticleByGoodsId($id);	
		$row['goodsArticleList']=$goodsArticleList;

		return $row;
	}

	/**
	 * 查找商品文章
	 * @param  int    $id 商品ID
	 * @return array      查找到的数据
	 */
	public function getGoodsArticleByGoodsId($id){

		$model=M('GoodsArticle');

		$model->alias('ga');
		$model->field('ga.*,a.name as article_name');
		$model->where(array('ga.goods_id'=>$id));
		$rst=$model->join('__ARTICLE__ as a on a.id=ga.article_id')->select();

		return $rst;
	}

	/**
	 * 编辑保存
	 * @param  array   $params post接受的所有信息
	 * @return mixed           保存成功返回保存成功返回值,失败返回false
	 */
	public function save($params){

		$this->startTrans();//开启事务

		$id=$params['id'];
		$this->handlerGoodsStatus();//处理商品状态
		$rst=parent::save();
		if($rst===false){//保存基本信息到goods表失败,回滚事务
			$this->rollback();
			return false;
		}
		//处理商品介绍
		$rest=$this->handlerGoodsIntro($params,$id);
		if($rest===false){//保存商品介绍到表中失败,回滚
			$this->rollback();
			return false;
		}
		//处理会员价格
		$rst=$this->handlerMemberPrice($params,$id);
		if($rst===false){
			$this->rollback();
			return false;
		}
		//处理商品相册
		$rst=$this->handlerGoodsGallery($params,$id);
		if($rst===false){
			$this->error='相册添加失败';
			$this->rollback();
			return false;
		}
		//处理商品文章
		$rst=$this->handlerGoodsArticle($params,$id);
		if($rst===false){
			$this->rollback();
			return false;
		}
		return $this->commit();
	}

}



 ?>