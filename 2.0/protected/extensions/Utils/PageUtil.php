<?php
class PageUtil {


	public static function getPage($pageName = 'page') {
		return isset ( $_GET [$pageName] ) ? strval ( $_GET [$pageName] ) : (isset ( $_POST [$pageName] ) ? strval ( $_POST [$pageName] ) : 1);
	}
	
	public static function getStart($page, $perPageNum) {
		return ($page - 1) * $perPageNum;
	}
	
	public static function getFullUrl() {
		return 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
	}
	
	public static function getNewActionUrl($url, $ac, $acName = 'ac') {
		$url = preg_replace ( '/\?ac=[a-z]{2,10}/', '?ac=' . $ac, $url );
		$url = preg_replace ( '/\&ac=[a-z]{2,10}/', '&ac=' . $ac, $url );
		$url = preg_replace ( '/page=[0-9]{1,10}/', '', $url );
		if (! strpos ( $url, 'ac=' )) {
			if (strpos ( $url, '?' ))
				$url = $url . '&ac=' . $ac;
			else
				$url = $url . '?ac=' . $ac;
		}
		$url = preg_replace ( '/\&\&/', '&', $url );
		return $url;
	}
	
}