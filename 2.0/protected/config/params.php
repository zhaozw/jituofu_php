<?php
$fileHost = defined('ENVIRONMENT') && ENVIRONMENT === 'development' ? "http://192.168.1.101/uploadfiles" : "http://115.29.39.106:1111/uploadfiles";
$oldFileHost = defined('ENVIRONMENT') && ENVIRONMENT === 'development' ? "http://192.168.1.101/uploadfiles" : "http://115.29.39.106";

return array(
		//地域文件
		'area_code' => $_SERVER['DOCUMENT_ROOT']."/area_code.dat",

		//允许上传文件类型
		'upload_types'=>"*.swf;*.jpg;*.jpeg;*.png;*.gif;*.bin;*.zip;*.mp3;*.flv;*.mp4;*.rmvb;*.rm;*.ts;*.bmp;",

		// Memcache 保存周期
		'memcacheLifetime'=>5*60,

		// Memcache 前缀标识
		'memcachePrefix'=>'CHAD',

		//服务器定时任务日志文本文件地址
		'crontab_log'=>  dirname(dirname(__DIR__)).'/crontab/',

        'lang' => require_once 'lang.php',
        'mail' => require_once 'mailConfig.php',
        'mailTemplatesDir' => 'mail-templates/',
        'appUrl' => "http://jituofu.com",
        'checkCodeInvalidTime' => 300,//验证码过期时间(秒)
        'localKey' => "JITUOFU.COM",
        'maxFileSize' => 1000*1000*10,//文件最大为10mb
        'fileType' => array('png', 'jpeg', 'jpg'),
        'defaultImgPlaceholder' => "http://baidu.com",//默认占位图片
        'fileHost' => $fileHost,//附件主机名
        'oldFileHost' => $oldFileHost//2.0版本升级前的附件主机名
);
