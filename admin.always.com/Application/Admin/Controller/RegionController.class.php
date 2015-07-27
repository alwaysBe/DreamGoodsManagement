<?php
namespace Admin\Controller;
use Think\Controller;
class RegionController extends BaseController{

	public function _befor_edit(){
		$rows=$this->model->getListData();
		$this->assign('parentRegion',$rows);
	}
}