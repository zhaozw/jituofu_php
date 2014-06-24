<?php
/**
 * 常用函数类封装
 * @author hugh
 * @version v1.0
 */
class F
{
    /**
     * 当前时间, 用于对时间精度不高的时间获取
     * @var int
     */
    private static $currentTime;

    private static $clientIp;

    /**
     * 获取用户IP
     */
    public static function clientIp()
    {
        if (self::$clientIp === null) {
            if (isset($_SERVER)) {
                if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                } else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
                    $realip = $_SERVER['HTTP_CLIENT_IP'];
                } else {
                    $realip = $_SERVER['REMOTE_ADDR'];
                }
            } else {
                if (getenv('HTTP_X_FORWARDED_FOR')) {
                    $realip = getenv('HTTP_X_FORWARDED_FOR');
                } else if (getenv('HTTP_CLIENT_IP')) {
                    $realip = getenv('HTTP_CLIENT_IP');
                } else {
                    $realip = getenv('REMOTE_ADDR');
                }
            }
            self::$clientIp = $realip;
        }
        return self::$clientIp;
    }

    /* *
     * 将ip转化为数字
     */
    public static function ipton($ip)
    {
        $ipstr = '';
        $ip_arr = explode('.', $ip);
        foreach ($ip_arr as $value) {
            $iphex = dechex($value);
            if (strlen($iphex) < 2) {
                $iphex = '0' . $iphex;
            }
            $ipstr .= $iphex;
        }
        return hexdec($ipstr);
    }

    /* *
     * 将数字转换为IP
     */
    public static function ntoip($n)
    {
        $iphex = dechex($n);
        $len = strlen($iphex);
        if (strlen($iphex) < 8) {
            $iphex = '0' . $iphex;
            $len = strlen($iphex);
        }
        for ($i = 0, $j = 0; $j < $len; $i = $i + 1, $j = $j + 2) {
            $ippart = substr($iphex, $j, 2);
            $fipart = substr($ippart, 0, 1);
            if ($fipart == '0') {
                $ippart = substr($ippart, 1, 1);
            }
            $ip[] = hexdec($ippart);
        }
        return implode('.', $ip);
    }

    /* *
     * 二维数组按照键值排序
     */
    public static function array_sort2($array, $key)
    {
        if (is_array($array)) {
            $key_array = null;
            $new_array = null;
            for ($i = 0; $i < count($array); $i++) {
                $key_array[$array[$i][$key]] = $i;
            }
            ksort($key_array);
            $j = 0;
            foreach ($key_array as $k => $v) {
                $new_array[$j] = $array[$v];
                $j++;
            }
            unset($key_array);
            return $new_array;
        } else {
            return $array;
        }
    }

    /* *
     * 数组按照键值排序
     */
    public static function array_sort($array, $on, $order = SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();
        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }
            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }
            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }
        return $new_array;
    }

    /* *
     * 键值比较
     */
    public static function cmp($a, $b)
    {
        if ($a == $b) {
            return 0;
        }
        return ($a > $b) ? -1 : 1;
    }

    /* *
     * 根据IP获取地埋位置
     */
    public static function convertip_full($ip, $ipdatafile)
    {
        if (!$fd = fopen($ipdatafile, 'rb')) {
            return '- Invalid IP data file';
        }

        $ip = explode('.', $ip);
        $ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];

        if (!($DataBegin = fread($fd, 4)) || !($DataEnd = fread($fd, 4))) return;
        @$ipbegin = implode('', unpack('L', $DataBegin));
        if ($ipbegin < 0) $ipbegin += pow(2, 32);
        @$ipend = implode('', unpack('L', $DataEnd));
        if ($ipend < 0) $ipend += pow(2, 32);
        $ipAllNum = ($ipend - $ipbegin) / 7 + 1;

        $BeginNum = $ip2num = $ip1num = 0;
        $ipAddr1 = $ipAddr2 = '';
        $EndNum = $ipAllNum;

        while ($ip1num > $ipNum || $ip2num < $ipNum) {
            $Middle = intval(($EndNum + $BeginNum) / 2);

            fseek($fd, $ipbegin + 7 * $Middle);
            $ipData1 = fread($fd, 4);
            if (strlen($ipData1) < 4) {
                fclose($fd);
                return '- System Error';
            }
            $ip1num = implode('', unpack('L', $ipData1));
            if ($ip1num < 0) $ip1num += pow(2, 32);

            if ($ip1num > $ipNum) {
                $EndNum = $Middle;
                continue;
            }

            $DataSeek = fread($fd, 3);
            if (strlen($DataSeek) < 3) {
                fclose($fd);
                return '- System Error';
            }
            $DataSeek = implode('', unpack('L', $DataSeek . chr(0)));
            fseek($fd, $DataSeek);
            $ipData2 = fread($fd, 4);
            if (strlen($ipData2) < 4) {
                fclose($fd);
                return '- System Error';
            }
            $ip2num = implode('', unpack('L', $ipData2));
            if ($ip2num < 0) $ip2num += pow(2, 32);

            if ($ip2num < $ipNum) {
                if ($Middle == $BeginNum) {
                    fclose($fd);
                    return '- Unknown';
                }
                $BeginNum = $Middle;
            }
        }

        $ipFlag = fread($fd, 1);
        if ($ipFlag == chr(1)) {
            $ipSeek = fread($fd, 3);
            if (strlen($ipSeek) < 3) {
                fclose($fd);
                return '- System Error';
            }
            $ipSeek = implode('', unpack('L', $ipSeek . chr(0)));
            fseek($fd, $ipSeek);
            $ipFlag = fread($fd, 1);
        }

        if ($ipFlag == chr(2)) {
            $AddrSeek = fread($fd, 3);
            if (strlen($AddrSeek) < 3) {
                fclose($fd);
                return '- System Error';
            }
            $ipFlag = fread($fd, 1);
            if ($ipFlag == chr(2)) {
                $AddrSeek2 = fread($fd, 3);
                if (strlen($AddrSeek2) < 3) {
                    fclose($fd);
                    return '- System Error';
                }
                $AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
                fseek($fd, $AddrSeek2);
            } else {
                fseek($fd, -1, SEEK_CUR);
            }

            while (($char = fread($fd, 1)) != chr(0))
                $ipAddr2 .= $char;

            $AddrSeek = implode('', unpack('L', $AddrSeek . chr(0)));
            fseek($fd, $AddrSeek);

            while (($char = fread($fd, 1)) != chr(0))
                $ipAddr1 .= $char;
        } else {
            fseek($fd, -1, SEEK_CUR);
            while (($char = fread($fd, 1)) != chr(0))
                $ipAddr1 .= $char;

            $ipFlag = fread($fd, 1);
            if ($ipFlag == chr(2)) {
                $AddrSeek2 = fread($fd, 3);
                if (strlen($AddrSeek2) < 3) {
                    fclose($fd);
                    return '- System Error';
                }
                $AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
                fseek($fd, $AddrSeek2);
            } else {
                fseek($fd, -1, SEEK_CUR);
            }
            while (($char = fread($fd, 1)) != chr(0))
                $ipAddr2 .= $char;
        }
        fclose($fd);

        if (preg_match('/http/i', $ipAddr2)) {
            $ipAddr2 = '';
        }
        $ipaddr = "$ipAddr1";
        $ipaddr = preg_replace('/CZ88\.NET/is', '', $ipaddr);
        $ipaddr = preg_replace('/^\s*/is', '', $ipaddr);
        $ipaddr = preg_replace('/\s*$/is', '', $ipaddr);
        if (preg_match('/http/i', $ipaddr) || $ipaddr == '') {
            $ipaddr = '- Unknown';
        }
        return mb_convert_encoding($ipaddr, 'utf-8', 'gb2312');
    }

    /**
     * getDay 返回星期(星期天=0)
     * @param
     *  $time int
     * 假定传入的日期合法
     */
    public static function week($time)
    {
        $weekarray = array("日", "一", "二", "三", "四", "五", "六");
        return "星期" . $weekarray[date("w", $time)];
    }

    /*
     * 返回最近三天时间
     */
    public static function get3days()
    {
        $now = self::now();
        $days = $now - 24 * 60 * 60 * 3;
        return $days;
    }

    /**
     * 字符串 预定义的 HTML 转实体
     * @param string $string
     * @return string $string
     */
    public static function str2Html($string)
    {
        $string = trim($string);
        $string = htmlspecialchars($string, ENT_QUOTES);
        return $string;
    }

    /**
     * 字符串 预定义的 HTML 转实体
     * @param string $string
     * @return string $string
     */
    public static function html2Str($string)
    {
        $string = trim($string);
        $string = htmlspecialchars_decode($string, ENT_QUOTES);
        $string = stripslashes($string);
        return $string;
    }

    /**
     * 分解tag
     * @param string $tags
     */
    public static function splitTag($tags)
    {
        $newTags = array();
        $tags = self::filterStr($tags);
        $tags = explode(',', $tags);
        foreach ($tags as $tag) {
            $newTags[] = trim($tag);
        }
        return $newTags;
    }

    /**
     * 去除所有空格
     * @param $str
     * @return mixed
     */
    public static function trimAll($str)
    {
        return preg_replace('/\s+/', '', $str);
    }

    //返回指定的错误消息
    public static function returnError($msg = "", $data = array("" => ""))
    {
        $resultData = array();
        $resultData['public'] = array('updateUrl' => "", 'updateTitle' => "", 'resultStatus' => 400, 'memo' => $msg);
        $resultData['operation'] = $data;
        echo CJSON::encode($resultData);
        Yii::app()->end();
    }

    //业务处理成功后，返回指定的消息
    public static function returnSuccess($msg = "", $data = array("" => ""))
    {
        $resultData = array();
        $resultData['public'] = array('updateUrl' => "", 'updateTitle' => "", 'resultStatus' => 100, 'memo' => $msg);
        $resultData['operation'] = $data;
        echo CJSON::encode($resultData);
        Yii::app()->end();
    }

    //用户信息验证失败
    public static function returnVerifyError($msg = "", $data = array("" => ""))
    {
        $resultData = array();
        $resultData['public'] = array('updateUrl' => "", 'updateTitle' => "", 'resultStatus' => 300, 'memo' => $msg);
        $resultData['operation'] = $data;
        echo CJSON::encode($resultData);
        Yii::app()->end();
    }

    /**
     * 根据指定的最小和最大值验证字符串的长度
     * @param $min
     * @param $max
     * @param $str
     * @return bool
     */
    public static function minMaxRange($min, $max, $str)
    {
        if (mb_strlen(trim($str), "UTF8") < $min)
            return true;
        else if (mb_strlen(trim($str), "UTF8") > $max)
            return true;
        else
            return false;
    }

    /**
     * 本地化语言
     * 用法:：lang("ACCOUNT_USER_CHAR_LIMIT", array(2, 25));//用户名必须是2到25位中英文或数字
     * @param $key
     * @param null $markers 一个数组
     * @return mixed|string
     */
    public static function lang($key, $markers = NULL)
    {
        $lang = Yii::app()->params['lang'];
        if ($markers == NULL) {
            $str = @$lang[$key];
        } else {
            //Replace any dyamic markers
            $str = $lang[$key];
            $iteration = 1;
            foreach ($markers as $marker) {
                $str = str_replace("%m" . $iteration . "%", $marker, $str);
                $iteration++;
            }
        }
        //Ensure we have something to return
        if ($str == "") {
            return ("没有找到匹配的本地消息");
        } else {
            return $str;
        }
    }

    /**
     * 是否是有效的邮箱
     * @param $email
     * @return bool
     */
    public static function isValidEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取当前的yyyy-mm-dd H:i:s
     * @return bool|string
     */
    public static function getCurrentDatetime()
    {
        return date("Y-m-d H:i:s");
    }

    /**
     * @param $emailAddress
     * @param $subject
     * @param $emailContent
     * @return bool
     */
    public static function sendMail($emailAddress, $subject, $emailContent)
    {
        if(!$emailAddress || !$subject || !$emailContent){
            return false;
        }

        return Wse::add($emailAddress, $subject, $emailContent);
    }

    /**
     * 替换邮件模版中的默认勾子
     * @param $str
     * @return mixed
     */
    public static function replaceDefaultHook($str)
    {
        $default_hooks = array("#APPNAME#", "#APPURL#", "#DATE#");
        $default_replace = array(Yii::app()->name, Yii::app()->params['appUrl'], F::getCurrentDatetime());

        return (str_replace($default_hooks, $default_replace, $str));
    }

    //@ Thanks to - http://phpsec.org
    public static function generateHash($plainText, $salt = null)
    {
        if ($salt === null) {
            $salt = substr(md5(uniqid(rand(), true)), 0, 25);
        } else {
            $salt = substr($salt, 0, 25);
        }

        return $salt . sha1($salt . $plainText);
    }

    /**
     * 生成6位随机字符串
     * @return string
     */
    public static function generate6Random()
    {
        $order_id = uniqid(rand(10, 1000), false);
        $order_id = substr($order_id, rand(0, strlen($order_id) - 6), 6);
        return strtolower($order_id);
    }

    /**
     * 登录状态下的通用验证函数
     * @param $verifyCookie boolean 是否需要做cookie校验
     * @return bool
     */
    public static function loggedCommonVerify($verifyCookie = false)
    {
        if (!F::notLoggedCommonVerify()) {
            return false;
        }

        $result = false;
        $public = F::getPublicData();
        $operation = F::getOperationData();

        $userId = $public['userId'];
        $cookie = $public['cookie'];

        if (!$userId) {
            F::returnError(F::lang("MEMO_NO_USERID"));
        }
        if (!$cookie) {
            F::returnError(F::lang('MEMO_NO_COOKIE'));
        }

        $result = true;

        //如果需要验证cookie
        if ($verifyCookie) {
            $condition = array('user_id' => $userId, 'cookie' => $cookie);

            //如果客户端传入clientId
            if(@$operation['clientId']){
                $condition = array('user_id' => $userId, 'cookie' => $cookie, 'uuid' => $operation['clientId']);
            }

            $record = Device::model()->findByAttributes($condition);

            if ($record) {
                $result = true;
            } else {
                $result = false;
                F::returnVerifyError(F::lang('MEMO_NO_LOGIN'));
            }
        }

        return $result;
    }

    /**
     * 未登录状态下的通用验证函数
     * @return bool
     */
    public static function notLoggedCommonVerify()
    {
        $result = false;
        $requestData = @Yii::app()->request->getParam('requestData');

        if (!$requestData) {
            F::returnError(F::lang('MEMO_NO_REQUESTDATA'));
            return false;
        }

        $requestData = @CJSON::decode($requestData);

        if (!is_array($requestData) || !$requestData) {
            F::error("无法解析 " . $requestData);
            F::returnError(F::lang('MEMO_DONOT_PARSE_REQUESTDATA'));
            return false;
        }

        $operation = @$requestData['operation'];
        $public = @$requestData['public'];

        if (!$public) {
            F::returnError(F::lang('MEMO_NO_PUBLIC'));
            return false;
        }

        if (!$operation) {
            F::returnError(F::lang('MEMO_NO_OPERATION'));
            return false;
        }

        $productVersion = @$public['productVersion'];
        $productId = @$public['productId'];
        $channelId = @$public['channelId'];
        $network = @$public['network'];
        $display = @$public['display'];
        $sign = @$public['sign'];
        $time = @$public['time'];

        if (!$productVersion) {
            F::returnError(F::lang('MEMO_NO_PRODUCTVERSION'));
        }
        if (!$productId) {
            F::returnError(F::lang('MEMO_NO_PRODUCTID'));
        }
        if (!$channelId) {
            F::returnError(F::lang('MEMO_NO_CLIENTID'));
        }
        if (!$network) {
            F::returnError(F::lang('MEMO_NO_NETWORK'));
        }
        if (!$display) {
            F::returnError(F::lang('MEMO_NO_DISPLAY'));
        }
        if (!$sign) {
            F::returnError(F::lang('MEMO_NO_SIGN'));
        }
        if (!$time) {
            F::returnError(F::lang('MEMO_NO_TIME'));
        }

        $localKey = Yii::app()->params['localKey'];
        $str = json_encode($operation, JSON_UNESCAPED_UNICODE) . $time . $localKey;
        $md5_sign = md5($str);
        F::debug("当前签名 " . $md5_sign);
        F::debug("业务数据 " . $str);
        if ($sign !== $md5_sign) {
            F::debug("客户端签名 " . $sign);
            F::returnError(F::lang("MEMO_SIGN_INVALID"));
        }

        $result = true;

        return $result;
    }

    /**
     * @return array
     */
    public static function getOperationData()
    {
        $requestData = Yii::app()->request->getParam('requestData');
        $requestData = CJSON::decode($requestData);
        $operation = $requestData['operation'];

        return $operation;
    }

    /**
     * @return array
     */
    public static function getPublicData()
    {
        $requestData = Yii::app()->request->getParam('requestData');
        $requestData = CJSON::decode($requestData);
        $public = $requestData['public'];

        return $public;
    }


    /**
     * 根据客户端传入的设备ID生成唯一的COOKIE
     * @param $clientId
     * @return string
     */
    public static function generateCookie($clientId)
    {
        return F::generateHash($clientId);
    }


    /**
     * 下发6位随机验证码
     * @param $record
     * @param $check_code
     * @param string $title
     * @param $tem
     * @return bool
     */
    public static function send6Random($record, $check_code, $title = '', $tem)
    {
        $hasCCrecord = CheckCode::model()->findByAttributes(array('user_id' => $record->getAttribute('id')));
        if ($hasCCrecord) {
            $criteria = new CDbCriteria;
            $criteria->condition = "user_id = " . $record->getAttribute('id');
            $rows = CheckCode::model()->updateAll(
                array('check_code' => md5($check_code), 'time' => time()),
                $criteria
            );
            if ($rows < 1) {
                F::error('向数据库写入6位随机码失败');
                return false;
            } else {
                if (!F::sendMail($record->getAttribute('email'), $title, $tem)) {
                    F::error('下发6位随机码失败');
                    return false;
                } else {
                    F::debug("下发 " . $check_code . " 到 " . $record->getAttribute('email'));
                    return true;
                }
            }
        } else {
            $model = new CheckCode();
            $model->attributes = array(
                'user_id' => $record->getAttribute('id'),
                'check_code' => md5($check_code),
                'time' => time()
            );
            if ($model->save()) {
                if (!F::sendMail($record->getAttribute('email'), $title, $tem)) {
                    F::error('下发6位随机码失败');
                    return false;
                } else {
                    F::debug("下发 " . $check_code . " 到 " . $record->getAttribute('email'));
                    return true;
                }
            } else {
                F::error('向数据库写入6位随机码失败');
                return false;
            }
        }
    }

    public static function debug($content, $cat="debug"){
        Yii::log($content, CLogger::LEVEL_TRACE, $cat);
    }

    public static function info($content, $cat="application"){
        Yii::log($content, CLogger::LEVEL_INFO, $cat);
    }

    public static function warn($content, $cat="application"){
        Yii::log($content, CLogger::LEVEL_WARNING, $cat);
    }

    public static function error($content, $cat="application"){
        Yii::log($content, CLogger::LEVEL_ERROR, $cat);
    }

    public static function mailTrace($content){
        Yii::log($content, CLogger::LEVEL_TRACE, "mail");
    }

    public static function mailError($content){
        Yii::log($content, CLogger::LEVEL_ERROR, "mail");
    }
}
