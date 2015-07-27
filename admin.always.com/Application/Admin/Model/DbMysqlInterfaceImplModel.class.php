<?php 
namespace Admin\Model;
use Admin\Service;
/**
 * 数据库工具接口实现类
 */
class DbMysqlInterfaceImplModel implements Service\DbMysqlInterfaceService{
	/**
	 * DB connect
	 *
	 * @access public
	 *
	 * @return resource connection link
	 */
	public function connect(){
		echo 'connection';exit;
	}

	/**
	 * Disconnect from DB
	 *
	 * @access public
	 *
	 * @return viod
	 */
	public function disconnect(){
		echo 'disconnect';exit;
	}

	/**
	 * Free result
	 *
	 * @access public
	 * @param resource $result query resourse
	 *
	 * @return viod
	 */
	public function free($result){
		echo 'free';exit;
	}

	/**
	 * Execute simple query
	 *
	 * @access public
	 * @param string $sql SQL query
	 * @param array $args query arguments
	 *
	 * @return resource|bool query result
	 */
	public function query($sql, array $args = array()){
		$args=func_get_args();
		$sql=$this->buildSql($args);
		return M()->execute($sql);
		// dump($args);
	}

	/**
	 * Insert query method
	 *
	 * @access public
	 * @param string $sql SQL query
	 * @param array $args query arguments
	 *
	 * @return int|false last insert id
	 */
	public function insert($sql, array $args = array()){
		$args=func_get_args();
		$sql=array_shift($args);
		$sql=str_replace('?T',$args[0],$sql);
		foreach($args[1] as $k=>$v){
			if($k=='id'){
				$set.="`id`=null,";
				continue;
			}
			$set.="`$k`='$v',";
		}
		$set=rtrim($set,',');
		$sql=str_replace('?%',$set,$sql);
		// echo $sql;
		$rst=M()->execute($sql);
		if($rst===false){
			return false;
		}else{
			return M()->getLastInsID();
		}
	}

	/**
	 * Update query method
	 *
	 * @access public
	 * @param string $sql SQL query
	 * @param array $args query arguments
	 *
	 * @return int|false affected rows
	 */
	public function update($sql, array $args = array()){
		echo 'update';exit;
	}

	/**
	 * Get all query result rows as associated array
	 *
	 * @access public
	 * @param string $sql SQL query
	 * @param array $args query arguments
	 *
	 * @return array associated data array (two level array)
	 */
	public function getAll($sql, array $args = array()){
		echo 'getAll';exit;
	}

	/**
	 * Get all query result rows as associated array with first field as row key
	 *
	 * @access public
	 * @param string $sql SQL query
	 * @param array $args query arguments
	 *
	 * @return array associated data array (two level array)
	 */
	public function getAssoc($sql, array $args = array()){
		echo 'getAssoc';exit;
	}

	/**
	 * Get only first row from query
	 *
	 * @access public
	 * @param string $sql SQL query
	 * @param array $args query arguments
	 *
	 * @return array associated data array
	 */
	public function getRow($sql, array $args = array()){
		$args=func_get_args();
		$sql=$this->buildSql($args);
		$rows=M()->query($sql);
		return $rows[0];
	}

	/**
	 * 根据参数生成sql语句
	 * @param  [array] $args 数组,数组里面第一个参数为模板sql语句
	 * @return [string]    返回拼接好的sql语句
	 */
	public function buildSql($args){
		$tpl_sql=array_shift($args);
		$arr_tpl=preg_split('/\?[FTN]/',$tpl_sql);
		$sql='';
		foreach($arr_tpl as $k=>$v){
			$sql.=$v.$args[$k];
		}
		return $sql;
	}

	/**
	 * Get first column of query result
	 *
	 * @access public
	 * @param string $sql SQL query
	 * @param array $args query arguments
	 *
	 * @return array one level data array
	 */
	public function getCol($sql, array $args = array()){
		echo 'getCol';exit;
	}

	/**
	 * Get one first field value from query result
	 *
	 * @access public
	 * @param string $sql SQL query
	 * @param array $args query arguments
	 *
	 * @return string field value
	 */
	public function getOne($sql, array $args = array()){
		$sql=$this->buildSql(func_get_args());
		$rst=M()->query($sql);
		return $rst;
	}
}



 ?>