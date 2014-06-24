<?php
return array(
		//地域文件
		'area_code' => $_SERVER['DOCUMENT_ROOT']."/area_code.dat",

		//影视列表接口
		'c_name_api'=>'http://182.140.244.133:8080/chra/service/ad',

		//允许上传文件类型
		'upload_types'=>"*.swf;*.jpg;*.jpeg;*.png;*.gif;*.bin;*.zip;*.mp3;*.flv;*.mp4;*.rmvb;*.rm;*.ts;*.bmp;",

		// Memcache 保存周期
		'memcacheLifetime'=>5*60,

		// Memcache 前缀标识
		'memcachePrefix'=>'CHAD',

		// 请求日志存放目录
		'requestLog'=>'./uploadfiles/request/',

		// 展示日志记录目录
		'freshenLog'=>'./uploadfiles/freshen/',

		// 终端数据日志目录
		'terminusLog'=>'./uploadfiles/terminus/',

		// 广告防刷新时间
        'freshenTime'=>5*50,

        // 广告日志定向存储地址
        //'adLogAdd'=>'http://'.$_SERVER['HTTP_HOST'].'/adinterface/adlog',

		//服务器定时任务日志文本文件地址
		'crontab_log'=>  dirname(dirname(__DIR__)).'/crontab/',

        'lang' => require_once 'lang.php',
        'mail' => require_once 'mailConfig.php',
        'mailTemplatesDir' => 'mail-templates/',
        'appUrl' => "http://jizhangbao.com.cn",
        'checkCodeInvalidTime' => 300,//验证码过期时间(秒)
        'localKey' => "JZB_ANDROID",
        'maxFileSize' => 1000*1000*10,//文件最大为10mb
        'fileType' => array('png', 'jpeg', 'jpg'),
        'defaultImgPlaceholder' => "http://baidu.com",//默认占位图片
        'fileHost' => "http://192.168.1.109/uploadfiles"//附件主机名
);
