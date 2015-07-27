<?php 

/**
 * 显示model的所有错误,并返回list
 * @param  模型对象 $model 模型
 * @return string          模型里面的所有错误信息
 */
function showErrorMsg($model){
    
    //获取所有错误信息
	$errors = $model->getError();
    $errorHtml = '<ul>';
    //如果错误信息为数组,则遍历出所有错误
    if(is_array($errors)){
        foreach($errors as $error){
            $errorHtml.="<li>$error</li>";
        }
    //只有一条错误信息
    }else{
        $errorHtml.="<li>$errors</li>";
    }
    $errorHtml .= '</ul>';
    return $errorHtml;
}

/**
 * 如果函数不存在则定义，返回二维数组里面指定的键对应的值
 * @param  [array] $data  需要处理的二维数组
 * @param  [string]       $field 返回的键名
 * @return array          返回一维数组
 */
if(!function_exists(array_column)){
   function array_column($data,$field){
       $tmp=array();
       foreach ($data as $item) {
           $tmp[]=$item[$field];
       }
       return $tmp;
    } 
}


/**
 * 字符串变数组,同explode方法
 * @param  [string] $str  [要处理的字符串]
 * @param  string   $spar [分割符号,默认逗号]
 * @return [array]        [分割后的数组]
 */
function str2arr($str,$spar=','){

    return explode($spar,$str);
}

/**
 * 数组变字符串,痛implode
 * @param  [array]   $arr  [需要处理的数组]
 * @param  string    $spar [分割符号]
 * @return [string]        [分割后的字符串]
 */
function arr2str($arr,$spar=','){

    return implode($spar,$arr);
}

/**
 * 数组变select选项
 * @param  array    $items        需要处理的数组
 * @param  string   $name         select上面name对应的值
 * @param  string   $defaultvalue 默认选中值
 * @param  string   $val_field    选项option的value值
 * @param  string   $txt_field    选项option的显示值
 * @return string                 拼接好的select字符串
 */
function arr2select($items,$name,$defaultvalue,$val_field='id',$txt_field='name'){

    $str="<select class='form-control {$name}' name='$name'>";
        $str.="<option value='0'>-- 请选择 --</option>";
        foreach($items as $item){
            $select='';
            if($item[$val_field]==$defaultvalue){
                $select = 'selected';
            }
            $str.="<option value='{$item[$val_field]}' $select>{$item[$txt_field]}</option>";
        }
    $str.="</select>";
    echo $str;
}

/**
 * 自定义md5加密方式,先对字符串加密,再拼接盐字符串,再次加密
 * @param  string $password 需要加密的字符串
 * @param  string $salt     加密拼接的盐字符串,默认空
 * @return [type]           加密后的32位字符串
 */
function myMd5($password,$salt=''){

    return md5(md5($password).$salt);
}

/**
 * 验证码验证函数,检测验证码是否正确
 * @param  string $code 用户填写的验证码
 * @return mixed        验证结果
 */
function checkVerify($code){

    $verify=new \Think\Verify();//实例化
    return $verify->check($code);

}

/**
 * 存储管理员的用户信息,及权限信息,没有参数时返回管理员信息
 * @param  array $userinfo 管理员信息
 * @return array           管理员信息
 */
function adminInfoSession($userinfo=''){
    if($userinfo){
        session('userinfo',$userinfo);
    }else{
        return session('userinfo');
    }
}


/**
 * 检测是否登陆
 * @return boolean 已经登陆,返回true,没有登陆返回false
 */
function isLogin(){
    return adminInfoSession()?true:false;
}

/**
 * 推出登陆,请客所有session数据
 * @return null 无返回值
 */
function loginOut(){
    session('userinfo',null);
    session('menuList',null);
    cookie('uid',null);
    cookie('uk',null);
}



 ?>