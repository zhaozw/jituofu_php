<?php

class StringUtil {

	//随机字符串生成
	public static function generateString($length = 6) {
		$chars = 'acdefghikmnpqrtuvwxyABCDEFGHIJKLMNPQRSTUVWXY34578';//zZ2 s0oO6bj91l!@#$%^&*()-_ []{}<>~`+=,.;:/?|
		$str = '';
		for ($i = 0; $i < $length; $i++) {
			// 这里提供两种字符获取方式
			// 第一种是使用 substr 截取$chars中的任意一位字符；
			// 第二种是取字符数组 $chars 的任意元素
			// $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
			$str .= $chars[mt_rand(0, strlen($chars) - 1)];
		}
		return $str;
	}
	
	/**
	 * PHP获取字符串中英文混合长度
	 * @param $str string 字符串
	 * @param $$charset string 编码
	 * @return 返回长度，1中文=1位，2英文=1位
	 */
	function strLength($str,$charset='utf-8'){
		if($charset=='utf-8') $str = iconv('utf-8','gb2312',$str);
		$num = strlen($str);
		$cnNum = 0;
		for($i=0;$i<$num;$i++){
			if(ord(substr($str,$i+1,1))>127){
				$cnNum++;
				$i++;
			}
		}
		$enNum = $num-($cnNum*2);
		$number = ($enNum/2)+$cnNum;
		return ceil($number);
	}
	
    /** 
     * 截取中文字符串，超过长度用....代替
     * @param string $string
     * @param int $from
     * @param int $len
     * @param string $dot
     * @return mixed|string
     */
	/**截取中文字符串，超过长度用....代替
	 * @param string $string
	 * @param int $from
	 * @param int $len
	 * @param string $dot
	 * @return mixed|string
	 */
	public static function utf8substr($string = '', $from = 0, $len = 0, $dot = '...')
	{
	    if (empty($string)) {
	        return $string;
	    }
	    $str_mode = '#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $from . '}' . '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $len . '}).*#s';
	    $substr = preg_replace($str_mode, '$1', $string);
	    if (mb_strlen($substr, 'UTF8') < mb_strlen($string, 'UTF8')) {
	        $substr .= $dot;
	    }
	    return $substr;
	}
	/* *
	 * del html
	*/
	public static function filtrate_str($str)
	{
		if(!trim($str)) return $str;
		$search = array ("'<[^>]*?>.*?'is",// 去掉javacript：：
				"'<[\/\!]*?[^<>]*?>'is", // 去掉 HTML 标记
				"'([\'\"])'",
				"'([\r\n])[\s]+'", // 去掉空白字符
				"'&(quot|#34);'i", // 替换 HTML 实体
				"'&(amp|#38);'i",
				"'&(lt|#60);'i",
				"'&(gt|#62);'i",
				"'&(nbsp|#160);'i",
				"'&(iexcl|#161);'i",
				"'&(cent|#162);'i",
				"'&(pound|#163);'i",
				"'&(copy|#169);'i",
				//"'&#(\d+);'e",
		);// 作为 PHP 代码运行
		$replace = array ("",
				"",
				"",
				"\\1",
				"\"",
				"&",
				"<",
				">",
				" ",
				chr(161),
				chr(162),
				chr(163),
				chr(169),
				//chr(\\1),
		);
		return preg_replace ($search, $replace, $str);
	}
	
	/* *
	 * del html
	*/
	public static function delhtml($document)
	{
		$document=trim($document);
		if (strlen($document) <=0)
		{
			return $document;
		}
		$search= array ("'<script[^>]*?>.*?</script>'si", // 去掉 javascript
				"'<[\/\!]*?[^<>]*?>'si", // 去掉 HTML 标记
				"'([\r\n])[\s]+'", // 去掉空白字符
				"'&(quot|#34);'i", // 替换 HTML 实体
				"'&(amp|#38);'i",
				"'&(lt|#60);'i",
				"'&(gt|#62);'i",
				"'&(nbsp|#160);'i",
				"'{pagecount}'"
		);
		// 作为 PHP 代码运行
		$replace= array ("",
				"",
				"\\1",
				"\"",
				"&",
				"<",
				">",
				" ",
				""
		);
		return @preg_replace($search,$replace,$document);
	}
}
