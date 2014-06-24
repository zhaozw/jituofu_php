<?php
/* *
 * @name EncryptUtil 类
 * @author hugh
 * @version 1.0
 */

class EncryptUtil {

	const _key = '^time-recorder6#$!@&*(';
	/* *
	 * 活动密匙长度
	 */
	const _activeKeyLength = 12;

	/* *
	 * 加密字符串
	 * @param string str加密字符
	 * @param key str 加密密匙
	 */
	public static function encode($string, $key=self::_key) {
		list($randTime,$result) = self::createKey($string, $key, 'encode');
		$result = self::createResult($string, $result,'encode');
		return $randTime.str_replace('=', '', base64_encode($result));
	}

	/* *
	 * 解密字符
	 * @param string str 解密字符
	 * @param key str 解密密匙
	 */
	public static function decode($string, $key=self::_key) {
		list($randTime,$result) = self::createKey($string, $key, 'decode');
		$result = self::createResult($string, $result);
		return $result;
	}
	
	/* *
	 * 创建真实静态密匙
	 */
	public static function createKey($string, $key=self::_key, $option) {
		if($key ==null) {
			$key = self::_key;
		}
		/* *
		 * 生成不同的活动密码
		 */
		$randTime = substr(md5(microtime()), -self::_activeKeyLength);
		if($option=='decode'){
			$randTime = substr($string, 0, self::_activeKeyLength);
		}
		return array($randTime,$randTime.md5($randTime.$key.$randTime));
	}

	/* *
	 * 创建加密明文
	 */
	public static function createResult($string,$randKey,$option ='decode') {
		$string = $option =='decode' ? base64_decode(substr($string, self::_activeKeyLength)) : $string;
		$boxKey = array();
		$num = 31;
		/* *
		 * 密码盒子
		 */
		$box = range(0, $num);
		$randLen = strlen($randKey);
		for($j = $i = 0; $i <= $num; $i++) {
			$boxKey[$i] = ord($randKey[$i % $randLen]);
			
			//搅乱盒子
			$j = ($j + $box[$i] + $boxKey[$i]) % ($num+1);
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}

		/* *
		 * 生成加密明文
		 */
		$result = '';
		// 核心加解密部分
		$strLen= strlen($string);
		for($a = $j = $i = 0; $i < $strLen; $i++) {
			$a = ($a + 1) % ($num+1);
			$j = ($j + $box[$a]) % ($num+1);
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			// 从密匙簿得出密匙进行异或，再转成字符
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % ($num+1)]));
		}
		return $result;
	}
}