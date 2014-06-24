<?php
/* *
 * @name TrHelper
 * @author hugh
 * @version 1.0
 */
class TrHelper {

	/* *
	 * 星座
	 * @param int $month 出生月份
	 * @param int $day 出生日份
	 * @return string
	 */
	public static function getConstellation($month, $day)
	{
		if ($month < 1 || $month > 12 || $day < 1 || $day > 31)
			return false;
		$signs = array(
				array( "20" => "水瓶座"),
				array( "19" => "双鱼座"),
				array( "21" => "白羊座"),
				array( "20" => "金牛座"),
				array( "21" => "双子座"),
				array( "22" => "巨蟹座"),
				array( "23" => "狮子座"),
				array( "23" => "处女座"),
				array( "23" => "天秤座"),
				array( "24" => "天蝎座"),
				array( "22" => "射手座"),
				array( "22" => "摩羯座")
		);
		list($signStart, $signName) = each($signs[(int)$month-1]);
		if ($day < $signStart)
			list($signStart, $signName) = each($signs[($month -2 < 0) ? $month = 11: $month -= 2]);
		return $signName;
	}

	/* *
	 * 产生随机数
	 */
	public static function randomkeys($length,$pattern='',$num='')
	{
		$key="";
		if($pattern==''){
			$pattern='23456789abcdefghkmnpqrstuvwxyzABCDEFGHILJKMNPQRSTUVWXYZ';
		}
		if($num==''){
			$num=35;
		}
		for($i=0;$i<$length;$i++)
		{
			$key .= $pattern{mt_rand(0,$num)};    //生成php随机数
		}
		return $key;
	}
    /* *
     * 判断电话号码
     * @
     */
	public static function telNoValid($telno) {
		$telno = trim($telno);
		$pattern = '/^1[3|4|5|8]\d{9}$/';
		if(11==strlen($telno)){
			if(preg_match($pattern, $telno)){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	/* *
	 * 根据生日判断生肖
	 * @param int $year 出生年份
	 * @return string
	 */
	public static function getZodiac($year)
	{
		$zodiacList = array("猴", "鸡", "狗", "猪", "鼠", "牛", "虎", "兔", "龙", "蛇", "马", "羊");
		$zodiacName=$zodiacList[$year%12];
		return $zodiacName;
	}

	/* *
	 * 性别
	 * @param string $sex
	 * @return string
	 */
	public static function getSex($sex)
	{
		$sexes = array(
		'f'=>'女',
		'm'=>'男',
		'父亲'=>'男',
		'母亲'=>'女'
		);
		return $sexes[$sex];
	}

	/* *
	 * 用户类型
	 * @param string $type
	 * @return string
	 */
	public static function getAccountType($type)
	{
		$accountType = array(
			'g'=>'校长',
			'm'=>'管理员',
			't'=>'老师',
			'p'=>'子帐号',
			's'=>'家长'
		);
		return $accountType[$type];
	}

	/* *
	 * 用户类型
	 * @param string $type
	 * @return string
	 */
	public static function getCodeType($type)
	{
		$codeType = array(
				'g'=>'幼儿园',
				'u'=>'匹配用户',
				'n'=>'非匹配用户'
			);
		return $codeType[$type];
	}

	/* *
	 * 用户类型
	 * @param string $type
	 * @return string
	 */
	public static function getCodeStatus($type)
	{
		$codeStatus = array(
				'1'=>'未使用',
				'2'=>'<span style="color:#F00">已使用</span>'
		);
		return $codeStatus[$type];
	}

	/* *
	 * 班级类型
	 * @param string $type
	 * @return string
	 */
	public static function getClassType($type='')
	{
		$classType = array(
			'b'=>'大班',
			'm'=>'中班',
			's'=>'小班',
			'c1'=>'年级一',
			'c2'=>'年级二',
			'c3'=>'年级三'
		);
		return !empty($type)?$classType[$type]:$classType;
	}

	/* *
	 * 学生成长记录类型
	 * @param string $type
	 * @return string
	 */
	public static function getGrowthType($type)
	{
		$growthType = array(
			'p'=>'添加一个专辑的图片',
			'v'=>'添加一段新的视频',
			'r'=>'添加一段新的录音',
			'l'=>'添加一篇新的日志'
		);
		return $growthType[$type];
	}

	/* *
	 *
	 */
	public static function getArrayUrl($value='',$type='')
	{
		$return = array();
		$return = array(
			//add
			array(
				'addImage'=>'upload/sAddImage',
				'addVideo'=>'upload/sAddVideo',
				'addLog'=>'upload/sAddLog',
				'addAudio'=>'upload/sAddAudio',
				'addGrowup'=>'account/addGrowup',
			),
			//edit
			array(
				'avatar'=>'upload/avatar',
			),
			//mgt
			array(
				'mgtParent'=>'account/familyMember',
			),
			//cttg
			array(
				'cttg'=>'account/sMsg',
			),
			//set
			array(
				'set'=>'account/setting',
			),
		);
		return empty($type)?$return[$value]:$return;
	}

	/* *
	 * 根据权限，筛选访问地址
	 */
	public static function getRightsUrl($params)
	{
		$return = array();
		if(is_array($params)){
			for($i=0;$i<count($params);$i++){
				$return[] = $params[$i]==1?self::getArrayUrl($i):array();
			}
		}
		return $return;
	}

	/* *
	 * 根据路径输出头像
	 * @param string $imgPath 图片路径
	 * @param string $size
	 * @param boolean $style
	 */
	public static function avartar($imgPath, $size="normal",$style=TRUE,$davatar='parent',$type="b")
	{
		$avatarSize = Yii::app()->params['webAvatar'];
		$avatarWebDir = Yii::app()->params['webAvatar'];
		$avatarDir = Yii::app()->params['avatarDir'];
		$avatars = array(
					'boy'=>'d_boy',
					'garten'=>'d_garten',
					'girl'=>'d_girl',
					'manager'=>'d_manager',
					'parent'=>'d_parent',
					'teacher'=>'d_teacher',
					'user'=>'d_user'
				);
		switch ($size) {
			case 'small':
				$avatarWebDir .= 'small/';
                $avatarDir .= 'small/';
				$avatarSize .= 'small/';
				break;
            case 'middle':
	            $avatarWebDir .= 'middle/';
				$avatarDir .= 'middle/';
				$avatarSize .= 'middle/';
                break;
            case 'big':
            	$avatarWebDir .= 'big/';
				$avatarDir .= 'big/';
				$avatarSize .= 'big/';
                break;
            case 'origin':
	            $avatarWebDir .= 'origin/';
				$avatarDir .= 'origin/';
				$avatarSize .= 'origin/';
            	break;
		}
		if($imgPath!="" && file_exists($avatarDir.$imgPath)) {
			$avatarUrl = $avatarWebDir.$imgPath;
		} else {
			$avatarUrl = '/public/images/'.$avatars[$davatar].'_'.$type.'.jpg';
		}
		if($style==FALSE) {
			$str = $avatarUrl;
		}else {
			$str = '<img alt="" src="'.$avatarUrl.'" />';
		}
		return $str;
	}

        /* *
         * 图片读取
         * @param string $name 名称
         * @param string $filename 路径
         * @param boolean $download
         */
	public static function read($name, $filename, $download = false)
	{

		$dir = Yii::app()->basePath.'/thumbnails/';
		$fileType = 'application/octet-stream';
		if (!$download) {
			$file = basename($filename);
			header("Content-Disposition: filename={$file}");
			$type = explode('.', $filename);
			$typeShort = $type[count($type)-1];
			switch ($typeShort) {
				case 'jpg':
				case 'jpeg':
					$fileType = 'image/jpeg';
					break;
				case 'png':
					$fileType = 'image/png';
					break;
				case 'gif':
					$fileType = 'image/gif';
					break;
			}
		}
		header("Content-Disposition: attachment; filename={$name}");
		header("Cache-control: max-age=600");
		header("Content-Type: {$fileType}");
		//Xsendfile
		if(strpos($_SERVER['SERVER_SOFTWARE'],'Apache')!==FALSE) {
			header("X-Sendfile:{$dir}{$filename}");
		}else {
			//protected 在 Nginx 配置中定义
			header("X-Accel-Redirect:/protected/{$filename}");
		}
		//缓存
		header("X-Accel-Buffering: yes");
		//单线程 限制多线程
		header("Accept-Ranges: 1");
		exit;
	}

	/* *
	 * 创建验证码
	 */
	public static function captcha()
	{
		$url = Yii::app()->createUrl("api/captcha");
		$html ='<img src="'.$url.'" width="145" height="37" alt="captcha" onclick="clickCaptcha(this)" style="cursor:pointer;" title="Click to Refresh!"/>';
		$js = self::captchaJs();
		echo $html.$js;
	}

	/* *
	 * 生成captchaJs
	 */
	public static function captchaJs()
	{
		$url = Yii::app()->createUrl("api/captcha");
		return '<script type="text/javascript">
					function clickCaptcha(o){
						o.src = "'.$url.'?sn="+Math.random();
					}
				</script>';
	}

	/* *
	 * 生成Check Box
	 * @param string $name
	 * @param array $data
	 * @param string $seprator
	 * @param array $options
	 */
	public static function checkBox($name, $data, $value=array(), $seprator=' ', $options=array())
	{
		$htmls = array();
		foreach ($data as $kd => $vd) {
			if (in_array($kd, $value)) {
				$checked = 'checked';
			} else {
				$checked = '';
			}
			$htmls[] = '<input type="checkbox" name="'.$name.'[]" '.self::htmlOptions($options).' value="'.$kd.'" '.$checked.' /><label for="'.$kd.'">'.$vd.'</label>';
		}
		return implode($seprator, $htmls);
	}

	/* *
	 * 生成Select
	 * @param string $name
	 * @param array $data
	 * @param array $options
	 */
	public static function select($name, $data, $now,$options=array())
	{
		$option = '';
		$htmls = array();
		$htmls[] = '<select name="'.$name.'" '.self::htmlOptions($options).'>';
		foreach ($data as $kd => $vd) {
			$selected = $now==$vd ?'selected' :'';
			$htmls[] = '<option '.$selected.' value="'.$kd.'" />'.$vd.'</option>';
		}
		$htmls[] = '</select>';
		return implode("\n", $htmls);
	}

	/* *
	 * 生成link
	 * @param string $title
	 * @param array $urlParams
	 * @param array $options
	 */
	public static function link($title, $urlParams, $options=array())
	{
		if (is_array($urlParams)) {
			$action = $urlParams[0];
			unset($urlParams[0]);
		} else {
			$action = $urlParams;
			$urlParams = array();
		}
		return '<a href="'.Yii::app()->createUrl(
				$action, $urlParams).'" '.self::htmlOptions($options).'>'.$title.'</a>';
	}

	/* *
	 * 生成HTML组件的OPTION
	 * @param array $options
	 */
	public static function htmlOptions($options = array())
	{
		$option = '';
		foreach ($options as $ko => $vo) {
			$option .= $ko.'="'.$vo.'" ';
		}
		return $option;
	}

	/* *
	 * 图片
         * @param string $path
         * @param string $file
         * @param string $origin=array('detail','cover','origin)
	 */
	public static function imagesPath($path,$file,$origin='detail')
	{
		return Yii::app()->params['webThumbnail'].$origin.'/'.$path.'/'.$file;
	}

	/* *
	 * name 转 code
	 */
	public static function nameToCode($name)
	{
		$search = array("/[^A-Za-z0-9_\s]/","/[\s]/");
		$replace = array('','-');
		return strtolower(preg_replace($search, $replace, $name));
	}

	/* *
	 * 图片
	 * @param string $path
	 * @param string $file
	 * @param string $type=array('small','big','origin)
	 */
	public static function videoPhoto($path,$name)
	{
		$filename = explode('.',Yii::app()->params['videoPhotourl'].$path."/".$name);
		 if(file_exists(str_replace($filename[1],'jpg',Yii::app()->params['videoPhoto'].$path.'/'.$name))){
			return str_replace($filename[1],'jpg',Yii::app()->params['videoPhotourl'].$path.'/'.$name);
		}else{
			return '/public/images/u_view/003.jpg';
		}
	}
	/* *
	 * 视频
         * @param string $path
         * @param string $file
	 */
	public static function videoCover($path,$name)
	{
		$filename = explode('.',Yii::app()->params['webVideo'].$path.'/'.$name);
		if(file_exists(str_replace($filename[1],'flv',Yii::app()->params['videoDir'].$path.'/'.$name))){
			return str_replace($filename[1],'flv',Yii::app()->params['webVideo'].$path.'/'.$name);
		}else{
			return null;
		}
	}

	/* *
	 * 录音
         * @param string $path
         * @param string $name
	 */
	public static function recorderCover($path,$name)
	{
		return str_replace('wav','mp3',Yii::app()->params['webRecorder'].$path.'/'.$name);
	}

	/* *
	 * 字符长度截取GB2312
	 */
	public static function cut($str,$len,$start=0)
	{
		if (strlen($str)<=$len) {
			return $str;
		}else {
			$tmpstr = "";
			$strlen = $start + $len;
			for($i = 0; $i < $strlen; $i++) {
				if(ord(substr($str, $i, 1)) > 0xa0) {
					$tmpstr .= substr($str, $i, 2);
					$i++;
				} else
					$tmpstr .= substr($str, $i, 1);
			}
			return $tmpstr."...";
		}
	}
	/* *
	 *
	 */
	public static function cut_str($string, $sublen, $start = 0, $code = 'UTF-8')
	{
		if($code == 'UTF-8')
		{
			$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
			preg_match_all($pa, $string, $t_string);

			if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."...";
			return join('', array_slice($t_string[0], $start, $sublen));
		}
		else
		{
			$start = $start*2;
			$sublen = $sublen*2;
			$strlen = strlen($string);
			$tmpstr = '';

			for($i=0; $i< $strlen; $i++)
			{
				if($i>=$start && $i< ($start+$sublen))
				{
					if(ord(substr($string, $i, 1))>129)
					{
						$tmpstr.= substr($string, $i, 2);
					}else{
						$tmpstr.= substr($string, $i, 1);
					}
				}
				if(ord(substr($string, $i, 1))>129) $i++;
			}
			if(strlen($tmpstr)< $strlen ) $tmpstr.= "...";
			return $tmpstr;
		}
	}

	/* *
	 *
	 * 根据数组内容输出下拉菜单
	 * @param array $data 数组数据
	 * @param bool $key_value true时,以$key为下拉框值,$value为下拉框表现字;否则$value为前面两者
	 * @param string $check 选中的值,以下拉框值为匹配对象,默认""
	 * @param string $name 下拉框的name属性,默认""
	 * @param string $id 下拉框的id属性,默认""
	 * @param string $class 下拉框的class属性,默认""
	 */
	public static function dropList($data,$key_value,$check="",$name="",$id="",$class="",$vvTips="")
	{
		$output="<select id='$id' class='$class' name='$name'>";
		if($key_value) {
			foreach($data as $key=>$value) {
				$selected=($key==$check)?"selected":"";
				$output.="<option value='$key' $selected>$value</option>";
			}
		}
		else {
			if($vvTips!="") {
				$selected=($check=="")?"selected":"";
				$output.="<option value='' $selected>$vvTips</option>";
			}
			foreach($data as $value) {
				$selected=($value==$check)?"selected":"";
				$output.="<option value='$value' $selected>$value</option>";
			}
		}
		$output.="</select>";
		return $output;
	}

	/* *
	 *
	 * Enter description here ...
	 * @param int $val
	 */
	public static function getStar($val)
	{
		$full=$val;
		$output="";
		for($i=1;$i<=5;$i++) {
			if($i<=$full) {
				$output.='<i class="rating_full"></i>';
			}
			else if($i<$full+1) {
				$output.='<i class="rating_half"></i>';
			}
			else {
				$output.='<i class="rating_empty"></i>';
			}
		}
		$output = '<a href="javascript:void(0)" title="'.$val.'">'.$output.'</a>';
		return $output;
	}

	/* *
	 * 删除图片
	 * @param string $imageName
	 */
	public static function deleteImages($imageName,$imageType,$resolutions)
	{
		//原始图
		$OriginDir = Yii::app()->params['thumbnailDir'].'origin/';
		if (file_exists($OriginDir.$imageName)) {
			unlink($OriginDir.$imageName);
		}
		//big
		$thumbnailDir = Yii::app()->params['thumbnailDir'];
		foreach (WpData::$wpFixWidth as $wpFixWidthName => $wpFixWidth) {
			if (file_exists($thumbnailDir.$wpFixWidthName.'/'.$imageName)) {
				unlink($thumbnailDir.$wpFixWidthName.'/'.$imageName);
			}
		}
		//small
		foreach (WpData::$wpCover as $wpCoverName => $wpCover) {
			if (file_exists($thumbnailDir.$wpCoverName.'/'.$imageName)) {
				unlink($thumbnailDir.$wpCoverName.'/'.$imageName);
			}
		}
	}

	/* *
	 * 过滤URL字符
	 */
	public static function t($string)
	{
		$string = preg_replace("/[^A-Za-z0-9]/", '_', $string);
		$string = preg_replace("/(_){2,}/", '_', $string);
		$string = preg_replace("/_$|^_/",'',$string);
		return $string;
	}

	/* *
	 * 为每个字符串加关键字
	 */
	public static function title($string,$keywords)
	{
		return strpos(strtolower($string),$keywords)!==FALSE ? $string : $string." ".$keywords;
	}

	/* *
	 * 快捷的加载缓存
	 */
	public static function getCache($key,$call,$params=array(),$time=600)
	{
		$cache = Yii::app()->cache;
		$data = $cache->get($key);
		if(!$data || (isset ($data['data']) && empty ($data['data']))) {
			$data = call_user_func_array($call,$params);
			$cache->set($key,$data,$time);
		}
		return $data;
	}

	/* *
	 * 实例变量，以下函数用到
	 */
	public static function getLunarInfo()
	{
		return array(
		'minYear' => 1891,
		'maxYear' => 2100,
		'lunarInfo' => array(array(0,2,9,21936),array(6,1,30,9656),array(0,2,17,9584),array(0,2,6,21168),array(5,1,26,43344),array(0,2,13,59728),array(0,2,2,27296),array(3,1,22,44368),array(0,2,10,43856),array(8,1,30,19304),array(0,2,19,19168),array(0,2,8,42352),array(5,1,29,21096),array(0,2,16,53856),array(0,2,4,55632),array(4,1,25,27304),array(0,2,13,22176),array(0,2,2,39632),array(2,1,22,19176),array(0,2,10,19168),array(6,1,30,42200),array(0,2,18,42192),array(0,2,6,53840),array(5,1,26,54568),array(0,2,14,46400),array(0,2,3,54944),array(2,1,23,38608),array(0,2,11,38320),array(7,2,1,18872),array(0,2,20,18800),array(0,2,8,42160),array(5,1,28,45656),array(0,2,16,27216),array(0,2,5,27968),array(4,1,24,44456),array(0,2,13,11104),array(0,2,2,38256),array(2,1,23,18808),array(0,2,10,18800),array(6,1,30,25776),array(0,2,17,54432),array(0,2,6,59984),array(5,1,26,27976),array(0,2,14,23248),array(0,2,4,11104),array(3,1,24,37744),array(0,2,11,37600),array(7,1,31,51560),array(0,2,19,51536),array(0,2,8,54432),array(6,1,27,55888),array(0,2,15,46416),array(0,2,5,22176),array(4,1,25,43736),array(0,2,13,9680),array(0,2,2,37584),array(2,1,22,51544),array(0,2,10,43344),array(7,1,29,46248),array(0,2,17,27808),array(0,2,6,46416),array(5,1,27,21928),array(0,2,14,19872),array(0,2,3,42416),array(3,1,24,21176),array(0,2,12,21168),array(8,1,31,43344),array(0,2,18,59728),array(0,2,8,27296),array(6,1,28,44368),array(0,2,15,43856),array(0,2,5,19296),array(4,1,25,42352),array(0,2,13,42352),array(0,2,2,21088),array(3,1,21,59696),array(0,2,9,55632),array(7,1,30,23208),array(0,2,17,22176),array(0,2,6,38608),array(5,1,27,19176),array(0,2,15,19152),array(0,2,3,42192),array(4,1,23,53864),array(0,2,11,53840),array(8,1,31,54568),array(0,2,18,46400),array(0,2,7,46752),array(6,1,28,38608),array(0,2,16,38320),array(0,2,5,18864),array(4,1,25,42168),array(0,2,13,42160),array(10,2,2,45656),array(0,2,20,27216),array(0,2,9,27968),array(6,1,29,44448),array(0,2,17,43872),array(0,2,6,38256),array(5,1,27,18808),array(0,2,15,18800),array(0,2,4,25776),array(3,1,23,27216),array(0,2,10,59984),array(8,1,31,27432),array(0,2,19,23232),array(0,2,7,43872),array(5,1,28,37736),array(0,2,16,37600),array(0,2,5,51552),array(4,1,24,54440),array(0,2,12,54432),array(0,2,1,55888),array(2,1,22,23208),array(0,2,9,22176),array(7,1,29,43736),array(0,2,18,9680),array(0,2,7,37584),array(5,1,26,51544),array(0,2,14,43344),array(0,2,3,46240),array(4,1,23,46416),array(0,2,10,44368),array(9,1,31,21928),array(0,2,19,19360),array(0,2,8,42416),array(6,1,28,21176),array(0,2,16,21168),array(0,2,5,43312),array(4,1,25,29864),array(0,2,12,27296),array(0,2,1,44368),array(2,1,22,19880),array(0,2,10,19296),array(6,1,29,42352),array(0,2,17,42208),array(0,2,6,53856),array(5,1,26,59696),array(0,2,13,54576),array(0,2,3,23200),array(3,1,23,27472),array(0,2,11,38608),array(11,1,31,19176),array(0,2,19,19152),array(0,2,8,42192),array(6,1,28,53848),array(0,2,15,53840),array(0,2,4,54560),array(5,1,24,55968),array(0,2,12,46496),array(0,2,1,22224),array(2,1,22,19160),array(0,2,10,18864),array(7,1,30,42168),array(0,2,17,42160),array(0,2,6,43600),array(5,1,26,46376),array(0,2,14,27936),array(0,2,2,44448),array(3,1,23,21936),array(0,2,11,37744),array(8,2,1,18808),array(0,2,19,18800),array(0,2,8,25776),array(6,1,28,27216),array(0,2,15,59984),array(0,2,4,27424),array(4,1,24,43872),array(0,2,12,43744),array(0,2,2,37600),array(3,1,21,51568),array(0,2,9,51552),array(7,1,29,54440),array(0,2,17,54432),array(0,2,5,55888),array(5,1,26,23208),array(0,2,14,22176),array(0,2,3,42704),array(4,1,23,21224),array(0,2,11,21200),array(8,1,31,43352),array(0,2,19,43344),array(0,2,7,46240),array(6,1,27,46416),array(0,2,15,44368),array(0,2,5,21920),array(4,1,24,42448),array(0,2,12,42416),array(0,2,2,21168),array(3,1,22,43320),array(0,2,9,26928),array(7,1,29,29336),array(0,2,17,27296),array(0,2,6,44368),array(5,1,26,19880),array(0,2,14,19296),array(0,2,3,42352),array(4,1,24,21104),array(0,2,10,53856),array(8,1,30,59696),array(0,2,18,54560),array(0,2,7,55968),array(6,1,27,27472),array(0,2,15,22224),array(0,2,5,19168),array(4,1,25,42216),array(0,2,12,42192),array(0,2,1,53584),array(2,1,21,55592),array(0,2,9,54560))
		);
	}

	/* *
	 * 将阳历转换为阴历
	 * @param year 公历-年
	 * @param month 公历-月
	 * @param date 公历-日
	 */
	public static function convertSolarToLunar($year,$month,$date)
	{
		$lunarInfo = self::getLunarInfo();
		$yearData = $lunarInfo['lunarInfo'][$year-$lunarInfo['minYear']];
		if($year==$lunarInfo['minYear']&&$month<=2&&$date<=9){
			return array(1891,'正月','初一','辛卯',1,1,'兔');
		}
		return self::getLunarByBetween($year,self::getDaysBetweenSolar($year,$month,$date,$yearData[1],$yearData[2]));
	}

	/* *
	 * 将阴历转换为阳历
	 * @param year 阴历-年
	 * @param month 阴历-月，闰月处理：例如如果当年闰五月，那么第二个五月就传六月，相当于阴历有13个月，只是有的时候第13个月的天数为0
	 * @param date 阴历-日
	 */
	public static function convertLunarToSolar($year,$month,$date)
	{
		$yearData = $this->lunarInfo[$year-$this->MIN_YEAR];
		$between = self::getDaysBetweenLunar($year,$month,$date);
		$res = mktime(0,0,0,$yearData[1],$yearData[2],$year);
		$res = date('Y-m-d',$res+$between*24*60*60);
		$day        = split_date($res);
		$year        = $day[0];
		$month= $day[1];
		$day        = $day[2];
		return array($year, $month, $day);
	}

	/* *
	 * 判断是否是闰年
	 * @param year
	 */
	public static function isLeapYear($year)
	{
		return (($year%4==0 && $year%100 !=0) || ($year%400==0));
	}

	/* *
	 * 获取干支纪年
	 * @param year
	 */
	public static function getLunarYearName($year)
	{
		$sky = array('庚','辛','壬','癸','甲','乙','丙','丁','戊','己');
		$earth = array('申','酉','戌','亥','子','丑','寅','卯','辰','巳','午','未');
		$year = $year.'';
		return $sky[$year{3}].$earth[$year%12].'年';
	}

	/* *
	 * 根据阴历年获取生肖
	 * @param year 阴历年
	 */
	public static function getYearZodiac($year)
	{
		$zodiac = array('猴','鸡','狗','猪','鼠','牛','虎','兔','龙','蛇','马','羊');
		return $zodiac[$year%12];
	}

	/* *
	 * 获取阳历月份的天数
	 * @param year 阳历-年
	 * @param month 阳历-月
	 */
	public static function getSolarMonthDays($year,$month)
	{
		$monthHash = array('1'=>31,'2'=>self::isLeapYear($year)?29:28,'3'=>31,'4'=>30,'5'=>31,'6'=>30,'7'=>31,'8'=>31,'9'=>30,'10'=>31,'11'=>30,'12'=>31);
		return $monthHash["$month"];
	}


	/* *
	 * 获取阴历月份的天数
	 * @param year 阴历-年
	 * @param month 阴历-月，从一月开始
	 */
	public static function getLunarMonthDays($year,$month)
	{
		$monthData = self::getLunarMonths($year);
		return $monthData[$month-1];
	}

	/* *
	 * 获取阴历每月的天数的数组
	 * @param year
	 */
	public static function getLunarMonths($year){
		$lunarInfo = self::getLunarInfo();
		$yearData = $lunarInfo['lunarInfo'][$year - $lunarInfo['minYear']];
		$leapMonth = $yearData[0];
		$bit = decbin($yearData[3]);
		for ($i = 0; $i < strlen($bit);$i ++) {
			$bitArray[$i] = substr($bit, $i, 1);
		}
		for($k=0,$klen=16-count($bitArray);$k<$klen;$k++){
			array_unshift($bitArray, '0');
		}
		$bitArray = array_slice($bitArray,0,($leapMonth==0?12:13));
		for($i=0; $i<count($bitArray); $i++){
			$bitArray[$i] = $bitArray[$i] + 29;
		}
		return $bitArray;
	}

	/* *
	 * 获取农历每年的天数
	 * @param year 农历年份
	 */
	public static function getLunarYearDays($year)
	{
		$lunarInfo = self::getLunarInfo();
		$yearData = $lunarInfo['lunarInfo'][$year-$lunarInfo['minYear']];
		$monthArray = self::getLunarYearMonths($year);
		$len = count($monthArray);
		return ($monthArray[$len-1]==0?$monthArray[$len-2]:$monthArray[$len-1]);
	}

	public static function getLunarYearMonths($year)
	{
		//debugger;
		$monthData = self::getLunarMonths($year);
		$res=array();
		$temp=0;
		$lunarInfo = self::getLunarInfo();
		$yearData = $lunarInfo['lunarInfo'][$year-$lunarInfo['minYear']];
		$len = ($yearData[0]==0?12:13);
		for($i=0;$i<$len;$i++){
			$temp=0;
			for($j=0;$j<=$i;$j++){
				$temp+=$monthData[$j];
			}
			array_push($res, $temp);
		}
		return $res;
	}

	/* *
	 * 获取闰月
	 * @param year 阴历年份
	 */
	public static function getLeapMonth($year)
	{
		$lunarInfo = self::getLunarInfo();
		$yearData = $lunarInfo['lunarInfo'][$year-$lunarInfo['minYear']];
		return $yearData[0];
	}

	/* *
	 * 计算阴历日期与正月初一相隔的天数
	 * @param year
	 * @param month
	 * @param date
	 */
	public static function getDaysBetweenLunar($year,$month,$date)
	{
		$yearMonth = self::getLunarMonths($year);
		$res=0;
		for($i=1;$i<$month;$i++){
			$res +=$yearMonth[$i-1];
		}
		$res+=$date-1;
		return $res;
	}

	/* *
	 * 计算2个阳历日期之间的天数
	 * @param year 阳历年
	 * @param cmonth
	 * @param cdate
	 * @param dmonth 阴历正月对应的阳历月份
	 * @param ddate 阴历初一对应的阳历天数
	 */
	public static function getDaysBetweenSolar($year,$cmonth,$cdate,$dmonth,$ddate)
	{
		$a = mktime(0,0,0,$cmonth,$cdate,$year);
		$b = mktime(0,0,0,$dmonth,$ddate,$year);
		return ceil(($a-$b)/24/3600);
	}

	/* *
	 * 根据距离正月初一的天数计算阴历日期
	 * @param year 阳历年
	 * @param between 天数
	 */
	public static function getLunarByBetween($year,$between)
	{
		//debugger;
		$lunarArray = array();
		$yearMonth=array();
		$t=0;
		$e=0;
		$leapMonth=0;
		$m='';

		if($between==0){
			array_push($lunarArray, $year,'正月','初一');
			$t = 1;
			$e = 1;
		}else{
			$year = $between>0? $year : ($year-1);
			$yearMonth = self::getLunarYearMonths($year);
			$leapMonth = self::getLeapMonth($year);
			$between = $between>0?$between : (self::getLunarYearDays($year)+$between);
			for($i=0;$i<13;$i++){
				if($between==$yearMonth[$i]){
					$t=$i+2;
					$e=1;
					break;
				}else if($between<$yearMonth[$i]){
					$t=$i+1;
					$e=$between-(empty($yearMonth[$i-1])?0:$yearMonth[$i-1])+1;
					break;
				}
			}
			$m = ($leapMonth!=0&&$t==$leapMonth+1)?('闰'.self::getCapitalNum($t- 1,true)):self::getCapitalNum(($leapMonth!=0&&$leapMonth+1<$t?($t-1):$t),true);
			array_push($lunarArray,$year,$m,self::getCapitalNum($e,false));
		}
		array_push($lunarArray,self::getLunarYearName($year));//天干地支
		array_push($lunarArray,$t,$e);
		array_push($lunarArray,self::getYearZodiac($year));//12生肖
		array_push($lunarArray,$leapMonth);//闰几月
		return $lunarArray;
	}

	/* *
	 * 获取数字的阴历叫法
	 * @param num 数字
	 * @param isMonth 是否是月份的数字
	 */
	public static function getCapitalNum($num,$isMonth)
	{
		$isMonth = $isMonth || false;
		$dateHash=array('0'=>'','1'=>'一','2'=>'二','3'=>'三','4'=>'四','5'=>'五','6'=>'六','7'=>'七','8'=>'八','9'=>'九','10'=>'十 ');
		$monthHash=array('0'=>'','1'=>'正月','2'=>'二月','3'=>'三月','4'=>'四月','5'=>'五月','6'=>'六月','7'=>'七月','8'=>'八月','9'=>'九月','10'=>'十月','11'=>'冬月','12'=>'腊月');
		$res='';

		if($isMonth){
			$res = $monthHash[$num];
		}else{
			if($num<=10){
				$res = '初'.$dateHash[$num];
			}else if($num>10&&$num<20){
				$res = '十'.$dateHash[$num-10];
			}else if($num==20){
				$res = "二十";
			}else if($num>20&&$num<30){
				$res = "廿".$dateHash[$num-20];
			}else if($num==30){
				$res = "三十";
			}
		}
		return $res;
	}

	/* *
	 * 大小写转化
	 * @param string
	 * @param Month
	 */
	public static function getTransformMonth($value)
	{
		$LowerCapital = array('1'=>'一','2'=>'二','3'=>'三','4'=>'四','5'=>'五','6'=>'六','7'=>'七','8'=>'八','9'=>'九','10'=>'十','11'=>'十一','12'=>'十二');
        $value = intval($value);
		return $LowerCapital[$value];
	}

	/* *
	 * 健康成长标准参考值
	 * @return array
	 */
	public static function getHealthReferTable($month,$sex,$height,$weight,$isbmi='')
	{
		$return = array();
		$months = array('1','2','3','4','5','6','8','10','12','15','18','21','24','30','36','42','48','54','60','66','72','84','96','108','120');
		$sexs['boy'] = array(
				'height'=>array('48.2|52.8','52.1|57.0','55.5|60.7','58.5|63.7','61.0|66.4','65.1|70.5','68.3|73.6','71.0|76.3','73.4|78.8','76.6|82.3','79.4|85.4','81.9|88.4','84.3|91.0','88.9|95.8','91.1|98.7','95.0|103.1','98.7|107.2','102.1|111.0','105.3|114.5','108.4|117.8','111.2|121.0','116.6|126.8','121.6|132.2','126.5|137.8','131.4|143.6'),
				'weight'=>array('3.6|5.0','4.3|6.0','5.0|6.9','5.7|7.6','6.3|8.2','6.9|8.8','7.8|9.8','8.6|10.6','9.1|11.3','9.8|12.0','10.3|12.7','10.8|13.3','11.2|14.0','12.1|15.3','13.0|16.4','13.9|17.6','14.8|18.7','15.7|19.9','16.6|21.1','17.4|22.3','18.4|23.6','20.2|26.5','22.2|30.0','24.3|34.0','26.8|38.7')
			);
		$sexs['girl'] = array(
				'height'=>array('48.2|52.8','51.2|55.8','54.4|59.2','57.1|59.5','59.4|64.5','63.3|68.6','66.4|71.8','69.0|74.5','71.5|77.1','74.8|80.7','77.9|84.0','80.6|87.0','83.3|89.8','87.9|94.7','90.2|98.1','94.0|101.8','97.6|105.7','100.9|109.3','104.0|112.8','106.9|116.2','109.7|119.6','115.1|126.2','120.4|132.4','125.7|138.7','131.5|145.1'),
				'weight'=>array('2.7|3.6','3.4|4.5','4.0|5.4','4.7|6.2','5.3|6.9','6.3|8.1','7.2|9.1','7.9|9.9','8.5|10.6','9.1|11.3','9.7|12.0','10.2|12.6','10.6|13.2','11.7|14.7','12.6|16.1','13.5|17.2','14.3|18.3','15.0|19.4','15.7|20.4','16.5|21.6','17.3|22.9','19.1|26.0','21.4|30.2','24.1|35.3','27.2|40.9')
			);
		for($i=0;$i<count($months);$i++){
			if($months[$i]<=$month) $keyValue = $i<25?$i:$i-1;
		}
		$heightMin = $sexs[$sex]['height'][$keyValue];
		$heightMax = $sexs[$sex]['height'][$keyValue+1];
		$weightMin = $sexs[$sex]['weight'][$keyValue];
		$weightMax = $sexs[$sex]['weight'][$keyValue+1];
		$heightMinVal = explode('|', $heightMin);
		$heightMaxVal = explode('|', $heightMax);
		$weightMinVal = explode('|', $weightMin);
		$weightMaxVal = explode('|', $weightMax);
		if($weight<$weightMinVal[0]){
			$return['res'] = '偏瘦';
			$return['msg'] = '增加宝宝营养，增强锻炼';
		}elseif($weight>$weightMaxVal[1]){
			$return['res'] = '偏胖';
			$return['msg'] = '建议合理饮食，多做锻炼';
		}else{
			$return['res'] = '标准';
			$return['msg'] = '太棒了，继续保持';
		}
		if($isbmi){
			$bmi = $weight/pow($height/100,2);
			if($month<72){
				if($bmi<=15){
					$return['res'] = '偏瘦';
					$return['msg'] = '增加宝宝营养，增强锻炼';
				}elseif($bmi>=18){
					$return['res'] = '偏胖';
					$return['msg'] = '建议合理饮食，多做锻炼';
				}else{
					$return['res'] = '标准';
					$return['msg'] = '太棒了，继续保持';
				}
			}else{
				if($bmi<=16){
					$return['res'] = '偏瘦';
					$return['msg'] = '增加宝宝营养，增强锻炼';
				}elseif($bmi>=19){
					$return['res'] = '偏胖';
					$return['msg'] = '建议合理饮食，多做锻炼';
				}else{
					$return['res'] = '标准';
					$return['msg'] = '太棒了，继续保持';
				}
			}
		}
		return $return;
	}

	/* *
	 * 健康评测模板初始值
	 * @return array
	 */
	public static function getHealthEvaluteTpl()
	{
		$return = array();
		//月份注意事项
		$return['notice'] = array(
				'深冬时节，天气寒冷。除了注意孩子的衣着与室内保暖外，遇上好天气，应该多让孩子们在太阳下面活动，促进内钙吸收，增强幼儿体质。另外，临近春节，放假期间，家长们应合理安排孩子的生活，注意饮食，防止暴饮暴食或吃零食过度，而且更要注意宝宝们的安全，千万不能让宝宝靠近烟花或者在马路上玩耍哦。',
				'2',
				'3',
				'4',
				'5',
				'6',
				'7',
				'8',
				'9',
				'10',
				'冬季来临，天气渐渐变冷。给小孩穿衣一定要合理、随时增减。有的家长给小孩穿衣过多，孩子稍有活动，汗水把内衣湿透，而小孩不会表示需要更换内衣，只能凭自身的体温把湿衣暖干，天天如此，容易伤风感冒，对健康不利。同时应随气候变化而增减，在活动前或进入有暖气房间时应脱去外衣。',
				'冬季气温低，孩子户外玩耍的机会少了，容易缺维生素D、感冒、上火，要给孩子吃鱼肝油，增加饮食营养以提高抵抗力。如果已经上火，可以吃去火的药，如板蓝根、太极丸，也可以用鲜百合熬粥，加点冰糖，美味又败火。要多喝排骨汤、鸡汤、鱼汤等汤水，增强抵抗力，起到预防感冒的作用。',
			);
		//我喜欢的
		$return['my_favorite'] = array(
				'玩具',
				'颜色',
				'食物',
				'游戏',
				'动画片'
			);
		//成长目标
		$return['dev_target'] = array(
				'{3～4岁}基本适应幼儿园的集体生活，学习独立进餐和入厕等行为；乐于参加幼儿园的活动，学习分享；亲近大自然，喜爱并学习爱护周围的动植物；进行简单的美术、音乐活动。',
				'{4～5岁}了解安全常识及卫生保健知识，养成不挑食，主动喝水等行为习惯；学习注意倾听，理解日常用语；能口齿清楚地朗诵儿歌，学习复述故事；能用自己喜欢的工具和方式进行美术活动；学习有表情的歌唱。',
				'{5～6岁}学习用适当的方式表达情绪，认识身体的主要器官及基本功能和保护方法；了解不同职业的成人及其劳动的社会意义，学会尊重他人；能够独立地朗读儿歌和复述故事和进行艺术活动；开始学习多种唱歌形式。',
				'{?～?岁}基本适应幼儿园的集体生活，学习独立进餐和入厕等行为；乐于参加幼儿园的活动，学习分享；亲近大自然，喜爱并学习爱护周围的动植物；进行简单的美术、音乐活动。',
				'{?～?岁}基本适应幼儿园的集体生活，学习独立进餐和入厕等行为；乐于参加幼儿园的活动，学习分享；亲近大自然，喜爱并学习爱护周围的动植物；进行简单的美术、音乐活动。',
				'{?～?岁}基本适应幼儿园的集体生活，学习独立进餐和入厕等行为；乐于参加幼儿园的活动，学习分享；亲近大自然，喜爱并学习爱护周围的动植物；进行简单的美术、音乐活动。',
			);
		//健康
		$return['dev_health'] = array(
				'能以愉快情绪参加各项活动，用适应的方式表达情绪',
				'饮食、睡眠、排泄等有规律，养成良好的习惯',
				'喜欢参加体育活动，活动中勇敢、不怕困难',
				'有初步的自我保护意识，不玩危险物品'
			);
		//语言
		$return['dev_lang'] = array(
				'喜欢与人交流，交流时能用礼物的语音',
				'会耐心倾听别人的谈话，能理解和运用日常用语',
				'大胆、清楚的向成人说出自己想说、想做的事',
				'喜欢听故事，能正确的翻阅图书，对阅读有兴趣'
			);
		//科学
		$return['dev_scie'] = array(
				'喜欢动手、动脑探索身边常见的事物和现象',
				'在各种探索活动中，有初步的合作学习意识、能与老师和同伴交流、分享探索过程和结果',
				'对生活中的数、量、形现象有兴趣，并能运用到生活和游戏中',
				'喜欢小动物，有初步的环保意识'
			);
		//艺术
		$return['dev_art'] = array(
				'能初步感受并喜欢环境、生活和艺术中的美',
				'喜欢参加艺术活动，并大胆的进行表达',
				'喜欢用身边的物品和废旧材料制作玩具、手工艺品',
				'能与同伴和老师相互交流、欣赏自己的艺术作品'
		);
		//社会
		$return['dev_social'] = array(
				'喜欢和老师、同伴在一起，能愉快的进行交流',
				'会自主的选择自己喜欢的活动，并尝试自己克服困难',
				'能遵守集体规则，有一定自律意识和尊重他人的意识',
				'会爱护玩具、图书和幼儿园的设施'
			);
		//在校
		$return['state_inschool'] = array(
				'文明礼貌行为',
				'学习兴趣、主动性',
				'操作、表现能力',
				'语言发展',
				'日常习惯',
				'同伴相处'
			);
		//在家
		$return['state_onhome'] = array(
				'礼貌待人',
				'懂得道理',
				'按时入睡',
				'进餐习惯',
				'收拾玩具',
				'讲究卫生',
				'懂得节约',
				'学做家务'
			);
		//老师寄语
		$return['t_word'] = '亲爱的家长，您好！这个月宝宝在幼儿园里身体健康，各方面表现都很优秀。宝宝比以前活泼了很多，喜欢听故事、问很多“为什么“，也喜欢和小朋友们交往，会主动分享自己的东西给他们，而且懂得爱护比自己小的小朋友。另外，宝宝对绘画有很大兴趣，希望您能多鼓励和支持宝宝哦。有什么问题和建议您也可以直接和我沟通。感谢您的支持！';
		//园长寄语
		$return['g_word'] = '亲爱的家长：我们的教育是走进孩子的世界的教育，作为幼儿教育工作者的我们，以极大的热诚投身于儿童世界之中，爱着孩子的所爱，乐着孩子的所乐，和他一起快乐、哭泣、和他们一起成长！我们会努力给孩子一个快乐的童年，并且获得有益于身体发展的多方面经验。';
		//家长寄语
		$return['p_word'] = '谢谢老师的细心教导与照顾，宝宝又度过了愉快的一个月。宝宝每天见到我的第一时间就是跟我分享学校里面的发生的事情，而且的确比以前开朗活泼，不在羞于表现自己了。真的很感谢老师！';
		return $return;
	}

	/* *
	 *
	 */
	public static function getCustom()
	{
		$return = array(
				array('custom_id'=>'3','custom_name'=>'大班','class_type'=>'b','orderid'=>'3'),
				array('custom_id'=>'2','custom_name'=>'中班','class_type'=>'m','orderid'=>'2'),
				array('custom_id'=>'1','custom_name'=>'小班','class_type'=>'s','orderid'=>'1')
			);
		return $return;
	}

	/* *
	 * @param type $date 日期
	 * @return array 日期所在月份的第一天和最后一天
	 */
	public static function getTheMonth($date)
	{
		$firstday = date('Y-m-01',$date);
		$lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
		return array($firstday, $lastday);
	}

	/* *
	 * send email
	 */
	public static function sendMail($params)
	{
		$mail  =Yii::app()->mailer;
		$message = $params['content'];
		$mail->CharSet = "utf-8";
		$mail->Host = 'smtp.adtime.com';
		$mail->Port = 25;
		$mail->ContentType = 'text/html';
		$mail->IsSMTP();
		//$mail->IsSendmail();
		//$mail->SMTPSecure = "ssl";
		$mail->Encoding = "base64";
		$mail->SMTPAuth= true;
		$mail->Username = 'noreply';//你的用户名，或者完整邮箱地址
		$mail->Password = '4c2799d976';//邮箱密码
		$mail->SetFrom('noreply@'.Yii::app()->params['shortSiteUrl'], "Yii::app()->params['siteName']");//发送的邮箱和发送人
		$mail->AddAddress($params['toMail']);
		$mail->IsHTML(true);
		$mail->Subject = $params['Subject'];
		$mail->Body = $message;
		if ($mail->Send()) {
			return true;
		}else{
			return false;
		}
	}
	/* *
	 * 导入excel
	 */
	public static function importExcel($params)
	{
		$data = array();
		spl_autoload_unregister(array('YiiBase','autoload'));
		require("./protected/extensions/phpExcelReader/reader.php");
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('utf-8');
		$data->read($params['excel']);
		if(isset($data->_ole->error) && ($data->_ole->error==1)){
			if(!unlink($params['excel'])){
				rename($params['excel'],Yii::app()->params['webExcel'].time().'.xls');
			}
			$data = array();
		}
		error_reporting(E_ALL ^ E_NOTICE);
		return $data;
	}
	/* *
	 * phpmailer
	 */
	public static function phpmailer($params)
	{
		require("./protected/extensions/PHPMailer/class.phpmailer.php");
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->CharSet = "utf-8";
		$mail->Port = 25;
		//$mail->SMTPSecure = "ssl";
		$mail->Encoding = "base64";
		$mail->Host = "smtp.adtime.com";
		$mail->SMTPAuth = true;
		$mail->Username = "noreply";
		$mail->Password = "4c2799d976";
		$mail->From = "noreply@".Yii::app()->params['shortSiteUrl'];
		$mail->FromName = Yii::app()->params['siteName'];
		$mail->AddAddress($params['toMail']);
		$mail->IsHTML(true);
		$mail->Subject = $params['Subject'];
		$mail->Body = $params['content'];
		if ($mail->Send()) {
			return true;
		}else{
			return false;
		}
	}
	/* *
	 * phpword
	 */
	public static function generateWord($params){
		spl_autoload_unregister(array('YiiBase','autoload'));
		require_once("./protected/extensions/PHPWord.php");
		$PHPWord = new PHPWord();
		$section = $PHPWord->createSection();
		if($params['type']=='u'){
			if(count($params['content'])){
				foreach ($params['content'] as $w){
					// Add title styles
					$PHPWord->addTitleStyle('title1', array('size'=>17, 'bold'=>true));
					$PHPWord->addTitleStyle('title2', array('size'=>14, 'bold'=>true));
					$PHPWord->addFontStyle('default', array('size'=>11));
					$PHPWord->addParagraphStyle('center', array('align'=>'center','spacing'=>150));
					$PHPWord->addParagraphStyle('lHeight', array('spacing'=>150));
					$section->addTitle('					告家长书', 'title1');
					$section->addTextBreak(1);
					$section->addTitle('尊敬的各位家长：', 'title2');
					$section->addTextBreak(1);
					$section->addText('　　　幼儿园是孩子从家庭生活走向社会生活的第一步，是孩子们的另一个家。把孩子在幼儿园点点滴滴记录下来，是我们所有家长及幼儿教师应该做的工作。大部分幼儿园的都是采取如：纸质档案、博客、QQ群等记录方式，缺乏统一性，而且容易造成信息泄露；另外，纸质档案工作繁杂、不利环保、且工作量大；因此我们推出全新的移动互联平台“'.Yii::app()->params['siteName'].'”来弥补这些问题。', 'default', 'lHeight');
					$section->addText('　　　“'.Yii::app()->params['siteName'].'”是专业的学前教育成长记录系统，能够为每一个在园孩子建立独立的成长空间；全程、全面地记录孩子在各个阶段的成长状况，孩子在学校和生活中的点滴成长、进步和改变家长都可以通过“'.Yii::app()->params['siteName'].'”来查看。 ', 'default', 'lHeight');
					$section->addText('　　　“'.Yii::app()->params['siteName'].'”包含了7大功能：成长日志、照片、视频、童声世界、时光电台、睡前故事、亲友分享等。记录包括：孩子生活、学习、幼儿园表现、老师评价、家长评价、学习、生活视频、童言趣语等等各个方面内容。', 'default', 'lHeight');
					$section->addText('　　　“'.Yii::app()->params['siteName'].'”不仅能够让家长全面了解孩子在园情况，还能直接与老师在线交流，通过手机、pc等终端接收幼儿园的通知等内容，也可以随时、随地的添加反馈意见，从而与幼儿园搭起更加及时、便捷、统一的桥梁。', 'default', 'lHeight');
					$section->addText('　　　在学期结束之后，我会提供家长们一个下载的方法。让家长们将这份珍贵的成长记录保存起来。也为孩子的成长留下一笔宝贵的财富。', 'default', 'lHeight');
					$section->addTextBreak(1);
					$section->addText('　　　提醒：本系统仅支持Chrome (https://www.google.com/intl/en/chrome/browser/)、Firefox (http://firefox.com.cn/) 浏览器', 'default', 'lHeight');
					$section->addTextBreak(1);
					$textrun = $section->createTextRun('lHeight');
					$textrun->addText('姓名：'.$w['realName'].'		班级：'.$params['className'].'		', 'default', 'lHeight');
					$textrun->addText('授权码：'.$w['code'], array('bold'=>true, 'underline'=>PHPWord_Style_Font::UNDERLINE_SINGLE),'lHeight');
					$section->addTextBreak(1);
					$section->addText('服务电话：'.Yii::app()->params['serviceNum'],'default' , 'center');
					$section->addText('网站：http://'.Yii::app()->params['siteUrl'],'default' ,'center');
					$section->addPageBreak();
				}
			}
			$userId = $params['userId'];
		}else{
			$section->addText($params['address'], array('name'=>'Verdana', 'color'=>'006699'));
			$section->addTextBreak(2);
			$styleTable = array('borderSize'=>6, 'borderColor'=>'006699', 'cellMargin'=>80);
			$styleFirstRow = array('borderBottomSize'=>18, 'borderBottomColor'=>'0000FF', 'bgColor'=>'66BBFF');
			// Add table style
			$PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);
			// Add table
			$table = $section->addTable('myOwnTableStyle');
			// Add row
			$table->addRow(900);
			$codeArray = explode('|', $params['content']);
			$count = (count($codeArray)-1);
			for($i = 0; $i < $count; $i++) {
				$table->addRow();
				if($i % 5==0){
					$table->addCell(2000)->addText($codeArray[$i]);
					$table->addCell(2000)->addText($codeArray[$i+1]);
					$table->addCell(2000)->addText($codeArray[$i+2]);
					$table->addCell(2000)->addText($codeArray[$i+3]);
					$table->addCell(2000)->addText($codeArray[$i+4]);
				}
			}
			$userId = $params['userId'].'/'.F::now();
			F::noDir2Create(Yii::app()->params['webWord'].$params['type'].'/'.$params['userId']);
		}
		$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
		$objWriter->save(Yii::app()->params['webWord'].$params['type'].'/'.$userId.'.docx');
		spl_autoload_register(array('YiiBase','autoload'));
		if(file_exists(Yii::app()->params['webWord'].$params['type'].'/'.$userId.'.docx')){
			return array('res'=>'ok', 'msg'=>'<a target="_blank" href="/'.(Yii::app()->params['webWord'].$params['type'].'/'.$userId.'.docx').'">word 生成成功，请点击下载!</a>', 'msg1'=>'<a target="_blank" href="/'.(Yii::app()->params['webWord'].$params['type'].'/'.$userId.'.docx').'">下载</a>');
		}else{
			return array('res'=>'err', 'msg'=>'word 生成失败，请重新操作!');
		}
	}

	/* *
	 * 遍历目录文件
	 */
	public static function blist($path){
		$arr = array();
		if(is_dir($path)){
			$hd = opendir($path);
			while(($file = readdir($hd))!==false){
				if($file=="."||$file=="..") {continue;}
				$newpath=iconv('utf-8', 'gbk', $path) .'/'.$file;
				if(is_dir($newpath)){
					$arr[] = blist($path."/".$file);
				}else if(is_file($newpath)){
					$arr[] = iconv('gbk','utf-8',$file);
				}
			}
			closedir($hd);
		}
		return $arr;
	}
	
	/* *
	 * 短信接口
	 * kuyuecs
	 */
	public static function mInterface($param)
	{
		$result = array();
		if(!empty($param['tel'])){
			$sname = 'dlsgjly8';	//接入帐号
			$spwd = '87654321';		//接入密码
			$object = $param['tel'];
			$scorpid = '';			//类型-短信发送
			$sprdid = '1012812';
			$SMSContent = $param['intro'].' 【'.Yii::app()->params['siteName'].'】';
			$params = 'http://60.28.200.150/submitdata/Service.asmx/g_Submit?sname='.$sname.'&spwd='.$spwd.'&sprdid='.$sprdid.'&scorpid='.$scorpid.'&sdst='.$object.'&smsg='.urlencode($SMSContent);
			$return = file_get_contents($params);
			preg_match_all("/<state>(.*?)<\/state>/is", $return, $state);
			preg_match_all("/<msgid>(.*?)<\/msgid>/is", $return, $msgids);
			if($state[1][0]=='0'){
				$result['res'] = 'ok';
				$result['msg'] = '短信发送成功!';
			}
			MessageCount::model()->updateCounters(array('spent'=>count($param['tel'])),'garten_id = '.$param['gartenId']);
		}
		return $result;
	}
}