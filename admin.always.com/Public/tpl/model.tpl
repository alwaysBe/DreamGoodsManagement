namespace Admin\Model;
use Think\Model;
class <?php echo $name;?>Model extends BaseModel{
	protected $_validate=array(
		<?php foreach($fields as $b){
			if($b['key']=='PRI' || $fieldk['null']=='YES'){
                 continue;
           }
           echo "array('{$b['field']}','require','{$b['name']}不能为空!',self::EXISTS_VALIDATE,'',self::MODEL_BOTH),\r\n";
		}?>
	);
}



 ?>