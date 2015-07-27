<?php 
namespace Admin\Controller;
use Think\Controller;
/**
 * 代码自动生成类,需要先创建对应的数据表
 */
class GenerateCodeController extends Controller{

    /**
     * 代码自动生成方法
     * @return null  无返回值
     */
	public function index(){
        //判断:POST请求方式时,执行生成代码操作
		if(IS_POST){
            //定义模板路径
            defined('TPL_PATH') or define('TPL_PATH',ROOT_PATH.'Public/tpl/');

			$table_name=I('post.name');//获取填写的表明
			$db_prefix=C('DB_PREFIX');//获取配置的表前缀
			$name=parse_name(ltrim($table_name,$db_prefix),1);//通过用户填写的表明和表前缀得到表名

            //查询表信息
			$rst=M()->query("show table status from think like '$table_name'");
			$title=$rst[0]['comment'];//获得表中文名
            //查询表字段信息
            $fields=M()->query("show full columns from {$table_name}");

            //通过表注释获得每个字段对应的中文名
            foreach($fields as &$filed){
                $filed['name']=strstr($filed['comment'],'@',true);
            }

        /*
        * 生成控制器模块
        */
            ob_start();//开启OB缓存
            require TPL_PATH.'controller.tpl';
            $content="<?php\r\n".ob_get_clean();
            //定义控制器存放路径
            $controller_path=APP_PATH.'Admin/Controller/';
            //如果存放控制器的路径不存在,则创建
            if(!is_dir($controller_path)){
                mkdir($controller_path,777);
            }
            //控制器全路径级控制器名
            $controller_full_name=$controller_path.$name."Controller.class.php";
            file_put_contents($controller_full_name,$content);
        /*
        * 生成模型模块
        */
            ob_start();//开启OB缓存
            require TPL_PATH.'model.tpl';
            $content="<?php\r\n".ob_get_clean();
            $model_path=APP_PATH.'Admin/Model/';
            if(!is_dir($model_path)){
                mkdir($model_path,777);
            }
            $model_full_name=$model_path.$name."Model.class.php";
            file_put_contents($model_full_name,$content);

        /*
        *生成视图文件
        */
            //生成index.html页面
            ob_start();//开启OB缓存
            require TPL_PATH.'index.tpl';
            $content=ob_get_clean();
            $index_path=APP_PATH."Admin/View/".$name.'/';
            if(!is_dir($index_path)){
                mkdir($index_path,777);
            }
            $index_full_path=$index_path.'index.html';
            file_put_contents($index_full_path,$content);

            //生成edit页面
            ob_start();//开启OB缓存
            require TPL_PATH.'edit.tpl';
            $content=ob_get_clean();
            $edit_full_path=$index_path.'edit.html';
            file_put_contents($edit_full_path,$content);
            $this->success('添加成功',U('Index/index'));
        }else{
			$this->display();
		}
	}
}


 ?>