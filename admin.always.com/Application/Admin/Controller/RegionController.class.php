<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 地区控制器
 */
class RegionController extends BaseController{

	public function _befor_edit(){
		$rows=$this->model->getListData();
		$this->assign('parentRegion',$rows);
	}
}