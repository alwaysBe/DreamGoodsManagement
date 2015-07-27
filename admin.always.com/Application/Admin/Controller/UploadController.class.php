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
	        // 'savePath'      =>  $dir.'/', //保存路径
	        'driver'        =>  'Qiniu', // 文件上传驱动
	        'driverConfig'  =>  array(
		        'secrectKey'     => 'Mi_RqYLWx4U7TaZ5MOM0XIbvO0IFxGOsiqW5fLJ6', //七牛服务器
		        'accessKey'      => 'qXZYn0APx8S8FXOOCm5EaE8c9KYvdT1qVLCvZK9A', //七牛用户
		        'domain'         => 'always.qiniudn.com', //七牛密码
		        'bucket'         => 'always', //空间名称
		        'timeout'        => 300, //超时时间
            ), // 上传驱动配置
    	);

		$upload=new Upload($config);//实例化上传类对象
		$info = $upload->uploadOne($_FILES['Filedata']);  //执行上传,并接受上传结果
		
		if($info!==false){
			//上传成功,上传返回路径(与上传根目录相对应)
            // echo $info['savepath'].$info['savename'];
            dump($info) ;
        }else{
            dump($upload->getError());
        }
        exit;
	}

}


 ?>