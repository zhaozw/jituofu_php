<?php
class ApiUtil {
	
	/**
	 * 是否是AJAX请求
	 *
	 * @return bool
	 */
	public static function isAjax()
	{
// 		var_dump($_SERVER);exit;
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']))
			return true;
		return false;
	}
	
	public static function yiiSessionLoginCheck($k='ssid'){ //return true;
		echo Yii::app()->session->sessionID." ";
		echo $_POST[$k];exit;
		$key = isset($_POST[$k])?$_POST[$k]:(isset($_GET[$k])?$_GET[$k]:'');
		$ssid = isset(Yii::app()->session->sessionID)?Yii::app()->session->sessionID:'';
		print_r($key,$ssid);exit;
		if($ssid==$key)
			return true;
		return false;
	}
	
	/**
	 * ajax 提交数据验证
	 * @param string $kn
	 * @return boolean
	 * @author zhangzhicheng
	 */
	public static function arkCheckApi($kn = 'ark'){
		$ark = isset($_GET[$kn])?strval($_GET[$kn]):'';
		$ark = empty($ark)?(isset($_POST[$kn])?strval($_POST[$kn]):''):'';
		if(!empty($ark)){
			$ar = preg_split('|', $ark);
			if(isset($ar[0]) && isset($ar[1]) && ApiUtil::checkKey($ar[0], $ar[1]) )
				return true;
		}
	}
	
	/**
	 * 加密验证
	 * @param string $data
	 * @param string $key
	 * @param number $type
	 * @return boolean
	 * @author zhangzhicheng
	 */
	public static function checkKey($data,$key,$type=1){
		if($data==self::arithmeticKey($key,$type))
			return true;
		return false;
	}
	
	/**
	 * 加密方式
	 * @param string $key
	 * @param int $type
	 * @return string
	 * @author zhangzhicheng
	 */
	public static function arithmeticKey($key,$type=1){
		if($type==1)
			return md5($key.substr($key, 5));
	}
}