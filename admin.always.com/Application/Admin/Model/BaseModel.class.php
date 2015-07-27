<?php 
namespace Admin\Model;
use Think\Model;
/**
 * 基础模型类,实现了基本的增删改查操作
 */
class BaseModel extends Model{

	// 是否批处理验证
    protected $patchValidate    =   true;
    
    /**
     * 获取所有列表数据
     * @return [array]    返回二维数组
     */
    public function getListData(){
    	return $this->where('status>-1')->select();
    }
    
    /**
     * 删除操作
     * @param  [int|array] $id     主键ID
     * @param  int         $status 要改变的状态
     * @return mixed               返回执行结果
     */
	public function changeStatus($id,$status){
		$rst=$this->where(array('id'=>array('in',$id)))->setField('status',$status);
		return $rst;
	}
	
	/**
	 * 获取分页列表数据信息
	 * @param  array  $where 查询附加条件
	 * @return array         返回分类数据以及分页工具条
	 */
	public function showList($where=array()){

		$orders=array();
		//如果也有status字段,则按status字段排序
		if(in_array('status',$this->getDbFields())){
            $orders['t.status'] = 'desc';//降序排序
            $where['t.status'] = array('egt',0);//选择状态大于0的数据(-1默认为删除状态)
        }
        //如果存在sort字段,则按sort升序排
        if(in_array('sort',$this->getDbFields())){
        	$orders['t.sort']='asc';
        }
        //设置表别名
        $this->alias('t');
        //获取表数据总数
		$count=$this->getCount($where);
		//实例化分页工具
		$page=new \Think\Page($count,8);
		//如果访问的页数超过总页数,返回最后一页数据
		if ($page->firstRow >= $count && $count - $page->listRows >= 0) {
            $page->firstRow = $count - $page->listRows;
        }
        //设置表别名
		$this->alias('t');
		//数据超过每页显示条数时,展示分页工具条
		if($count>$page->listRows){
			$page->setConfig('theme', '<ul class="pagination"><li>%HEADER%</li> <li class="paginate_button previous" aria-controls="dataTables-example" tabindex="0" id="dataTables-example_previous">%FIRST%</li> <li class="paginate_button previous" aria-controls="dataTables-example" tabindex="0" id="dataTables-example_previous">%UP_PAGE%</li> <li class="paginate_button" aria-controls="dataTables-example" tabindex="0">%LINK_PAGE%</li> <li class="paginate_button next" aria-controls="dataTables-example" tabindex="0" id="dataTables-example_next">%DOWN_PAGE%</li> <li class="paginate_button next" aria-controls="dataTables-example" tabindex="0" id="dataTables-example_next">%END%</li></ul>');
		}
		$html = $page->show();

		//钩子方法,查询前需要添加额外的查询条件时,使用
		$this->_setModel();

		//查询对应页面展示数据
		$rows=$this->where($where)->order($orders)->limit($page->firstRow.','.$page->listRows)->select();

		//钩子方法,需要对查询数据做额外处理时调用
		$this->_handleRows($rows);

		return array('html'=>$html,'rows'=>$rows);
	}
	
	/**
	 * 获取数据库数据总条数
	 * @param  [array] $where 查询条件
	 * @return int            数据条数
	 */
	public function getCount($where){
		return $this->where($where)->count();
	}

	/**
	 * 回调函数
	 */
	public function _setModel(){}
	public function _handleRows(&$rows){}

}


 ?>