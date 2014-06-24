<?php
/**
 * @name Meta
 * @author hugh
 * @version 1.0
 */
class Meta {
	//配置
	public static $config = array(
            'register'=>array(
                    't'=> "一起成长 - 珍藏童年的乐趣",
                    'k'=> "一起成长 - 珍藏童年的乐趣",
                    'd'=> "一起成长 - 珍藏童年的乐趣",
            ),
            'glogin'=>array(
                    't'=> "一起成长 - 珍藏童年的乐趣",
                    'k'=> "一起成长 - 珍藏童年的乐趣",
                    'd'=> "一起成长 - 珍藏童年的乐趣",
            ),
            'users'=>array(
                    't'=> "一起成长 - 珍藏童年的乐趣",
                    'k'=> "一起成长 - 珍藏童年的乐趣",
                    'd'=> "一起成长 - 珍藏童年的乐趣",
            ),
            'smsg'=>array(
                    't'=> "一起成长 - 珍藏童年的乐趣",
                    'k'=> "一起成长 - 珍藏童年的乐趣",
                    'd'=> "一起成长 - 珍藏童年的乐趣",
            ),
            'garten'=>array(
                    't'=> "一起成长 - 珍藏童年的乐趣",
                    'k'=> "一起成长 - 珍藏童年的乐趣",
                    'd'=> "一起成长 - 珍藏童年的乐趣",
            ),
            'gfindpwd'=>array(
                    't'=> "一起成长 - 珍藏童年的乐趣",
                    'k'=> "一起成长 - 珍藏童年的乐趣",
                    'd'=> "一起成长 - 珍藏童年的乐趣",
            ),
            'teacher'=>array(
                    't'=> "一起成长 - 珍藏童年的乐趣",
                    'k'=> "一起成长 - 珍藏童年的乐趣",
                    'd'=> "一起成长 - 珍藏童年的乐趣",
            ),
            'backend'=>array(
            		't'=> "一起成长 - 珍藏童年的乐趣",
                    'k'=> "一起成长 - 珍藏童年的乐趣",
                    'd'=> "一起成长 - 珍藏童年的乐趣",
            )
	);

	public static function getInfo($name = '') {
		$arr = self::$config;
		if(!$name) {
			return $arr;
		} else {
			return $arr[$name];
		}
	}

	public static function setSeo(Controller $controller, $name, $params = array()) {
		$config = Meta::getInfo($name);
		$paramCount = count($params);
		$seoTypes = array('t','k','d');
		if ($paramCount > 0) {
			$i = 1;
			while ($i<=$paramCount) {
				$searches[] = "#{KEY{$i}}";
				$i++;
			}
			foreach($seoTypes as $seo) {
				$config[$seo] = str_replace($searches, $params, $config[$seo]);
			}
		}
		foreach($seoTypes as $seo) {
			${$seo} = $config[$seo];
		}
		$controller->meta['title'] = $t;
		$controller->meta['keyword'] = $k;
		$controller->meta['description'] = $d;
	}
        
        public static function getPageSeo() {
		return isset($_GET['page'])?' - Page '.intval($_GET['page']):'';
	}

	public static function getCharSeo(&$params) {
		$typeSeo = '';
		$typeSeoT = '';
		if (isset($_GET['type'])) {
			$typeSeo = ' Letter '.strtoupper(trim($_GET['type']));
			$typeSeoT = ' -'.$typeSeo;
		}
		$params = array_merge($params, array($typeSeoT, $typeSeo));
	}

}