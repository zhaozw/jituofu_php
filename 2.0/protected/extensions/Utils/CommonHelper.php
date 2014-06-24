<?php
/**
 *
 * @author qianc
 * @date 2013-2-4
 * @description 通用助手类
 */

class CommonHelper{
    /**
     *
     * @author qianc
     * @date 2013-2-4
     * @description 打印变量
     */
    public static function dump($var, $output = null) {
        if($output == null){
            echo "<pre>";
            print_r($var);
            echo "</pre>";
        }elseif($output == 'firephp'){
            FB::info($var);
        }
    }

	/**
	 * 友好的时间显示
	 *
	 * @param int    $sTime 待显示的时间
	 * @param string $type  类型. mohu | full | ymd | other
	 * @return string
	 */
	public static function friendlyDate($sTime, $type = 'mohu'){
	    //sTime=源时间，cTime=当前时间，dTime=时间差
	    $cTime = time();
	    $todayTime = mktime('0', '0', '0', date('m'), date('d'), date('Y'));
	    $yestodayTime = mktime('0', '0', '0', date('m'), date('d') - 1, date('Y'));
	    $tommrrowTime = mktime('0', '0', '0', date('m'), date('d') + 1, date('Y'));
	    $weekTime = $todayTime - date('w', $cTime) * 86400;
	    $dTime = $cTime - $sTime;
	
	    if ($type == 'mohu') {
	        if ($dTime < 10) {
	            return '刚刚';
	        }
	        if (10 <= $dTime && $dTime < 60) {
	            return (ceil($dTime) + 0) . " 秒前";
	        } elseif ($dTime < 3600) {
	            return intval($dTime / 60) . " 分钟前";
	        }
	        //时间在今天0点到明天0点之间
	        elseif ($sTime < $tommrrowTime && $sTime > $todayTime) {
	            $h = intval($dTime / 3600);
	            if (ceil($dTime % 3600 / 60) > 30) {
	                $h++;
	            }
	            if ($h >= 3) {
	                return "今天  " . date('H:i', intval($sTime));
	            }
	            return $h . " 小时前";
	        }
	        //时间在本周0点到今天0点之间
	        elseif ($sTime < $todayTime && $sTime > $weekTime) {
	            //时间在今天0点到昨天0点之间
	            if ($sTime > $yestodayTime && $sTime < $todayTime) {
	                return "昨天 " . date('H:i', intval($sTime));
	            }
	            //时间在前天0点到昨天0点之间
	            elseif ($sTime > ($yestodayTime - 86400) && $sTime < $yestodayTime) {
	                return "前天 " . date('H:i', intval($sTime));
	            }
	            //其他
	            else {
	                return date("Y年n月j日H:i", intval($sTime));
	            }
	        } else {
	            return date("Y年n月j日H:i", intval($sTime));
	        }
	    } elseif ($type == 'full') {
	        return date("Y-m-d , H:i:s", intval($sTime));
	    } elseif ($type == 'ymd') {
	        return date("Y-m-d", intval($sTime));
	    } else {
	        return date("Y-m-d H:i:s", intval($sTime));
	    }
	}
	
	/**
	 * 序列化加密
	 *
	 * @author qianc
	 * @date 2013/06/19
	 */
	public static function serialize_and_ecode($array) {
		if(!empty($array)){
			$arr_to_str = serialize($array);
			$arr_to_str = self::authcode($arr_to_str,'ecode');
			return $arr_to_str;
		}
		return false;
	}



	/**
	 * 反序列化解密
	 *
	 * @author qianc
	 * @date 2013/06/19
	 */
	public static function unserialize_and_decode($has_been_ecode_and_serialize_string) {
		if(!empty($has_been_ecode_and_serialize_string)){
			$str_to_arr = self::authcode($has_been_ecode_and_serialize_string);
			$str_to_arr = unserialize($str_to_arr);		
			return $str_to_arr;
		}
		return false;
	
	}


	/**
	 * @author  qianc
	 * 参数解释 :  视频模块用到的加密解密方法
	 * $string： 明文 或 密文   
	 * $operation：DECODE表示解密,其它表示加密   
	 * $key： 密匙   
	 * $expiry：密文有效期
	 */
	public static function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0 )
	{
		$ckey_length = 4;
		$key = $key ? $key : Yii::app()->params['encryptKey'];
		$key = md5($key);
		$keya = md5(substr($key, 0, 16));
		$keyb = md5(substr($key, 16, 16));
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
		$cryptkey = $keya.md5($keya.$keyc);
		$key_length = strlen($cryptkey); 
		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
		$string_length = strlen($string);
	
		$result = '';
		$box = range(0, 255);
		$rndkey = array();
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}  
		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}  
		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}
	
		if($operation == 'DECODE') {
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
				return substr($result, 26);
			} else {
				return '';
			}
		} else {
			return $keyc.str_replace('=', '', base64_encode($result));
		}
	}


	/**
	 * 字符串过滤函数
	 *
	 * @author qianc
	 * @date 2013/06/20
	 * @param $string
	 */
	public static function strFilter($string) {
	        if (is_array($string)) {
	            foreach ($string as $key => $val) {
	                $string[$key] = strFilter($val);
	            }
	        } else {
	        	$string = htmlspecialchars(trim($string));
	            //$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1', str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
	        }
	        //完全过滤JS
	        $string = preg_replace('/<script?.*\/script>/', '********', $string);
	        return $string;
	}
	
	
	/**
	 * 弹出信息框
	 *
	 * @author	    qianc
	 * @date	    2012/07/14
	 * @param	    $msg(信息内容)
	 */
	public static	function alertMsg($msg) {
		$script = "<script type=\"text/javascript\">\n";
		if($msg){
			$script .= "alert('".$msg."');\n";
		}
		$script .="</script>\n";
		echo $script;
		exit;
	}	

}