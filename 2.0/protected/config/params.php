<?php
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
        'appUrl' => "http://192.168.1.100",
        'checkCodeInvalidTime' => 300,//验证码过期时间(秒)
        'localKey' => "JTF_ANDROID",
        'maxFileSize' => 1000*1000*10,//文件最大为10mb
        'fileType' => array('png', 'jpeg', 'jpg'),
        'defaultImgPlaceholder' => "http://baidu.com",//默认占位图片
        'fileHost' => "http://192.168.1.100/uploadfiles"//附件主机名
);
