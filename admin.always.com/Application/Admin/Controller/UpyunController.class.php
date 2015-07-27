<?php 
namespace Admin\Controller;
use Think\Controller;
use Think\Upload;
/**
 * 文件上传控制器
 */
class UploadController extends Controller{
	/**
	 * 文件上传方法
	 * @return string 上传成功:返回上传后文件保存的路径
	 *         		  上传失败不做任何操作
	 */
	public function index(){

		//获取传递过来的保存目录信息
		$dir=I('post.dir');
		//配置上传相关配置
		$config = array(
	        'exts'          =>  array('jpg','png','gif','jpeg'), //允许上传的文件后缀
	        'rootPath'      =>  './', //保存根路径
	        'savePath'      =>  $dir.'/', //保存路径
	        'driver'        =>  'Upyun', // 文件上传驱动
	        'driverConfig'  =>  array(
                'host'     => 'v1.api.upyun.com', //又拍云服务器
                'username' => 'itsource', //又拍云操作员的用户
                'password' => 'itsource', //又拍云操作员的密码
                'bucket'   => 'itsource-'.$dir, //空间名称
                'timeout'  => 90, //超时时间
            ), // 上传驱动配置
    	);

		$upload=new Upload($config);//实例化上传类对象
		$info = $upload->uploadOne($_FILES['Filedata']);  //执行上传,并接受上传结果
		
		if($info!==false){
			//上传成功,上传返回路径(与上传根目录相对应)
            echo $info['savepath'].$info['savename'];
        }else{
            dump($upload->getError());
        }
        exit;
	}

}


 ?>