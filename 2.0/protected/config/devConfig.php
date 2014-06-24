<?php
return CMap::mergeArray( require(dirname(__FILE__).'/base.php'), array(
	// preloading 'log' component
	'preload'=>array('log'),
	'modules'=>array(
        'admin',
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123456',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','10.2.2.37','::1'),
		),
	),
	// application components
	'components'=>array(
        'user'=>array(
                'class' => 'WebUser',
                // enable cookie-based authentication
                'allowAutoLogin'=>true,
                'loginUrl' => '/users/login',
        ),
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=127.0.0.1;dbname=xdjzb_dev_new',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'ZHUqi@159',
			'charset' => 'utf8',
			'tablePrefix' => '',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
                //应用程序日志
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning, info, trace',
					'filter'=>'CLogFilter',
                    'categories'=> 'application.*',
                    'logPath' => '/htdocs/xdjzb/protected/runtime/logs',
                    'logFile' => 'application.log'
				),
                //debug日志
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'trace',
                    'filter'=>'CLogFilter',
                    'categories'=> 'debug.*',
                    'logPath' => '/htdocs/xdjzb/protected/runtime/logs',
                    'logFile' => 'debug.log'
                ),
                //发送邮件日志
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'trace, warning, info, error',
                    'filter'=>'CLogFilter',
                    'categories'=> 'mail.*',
                    'logPath' => '/htdocs/xdjzb/protected/runtime/logs',
                    'logFile' => 'mail.log'
                ),
                // show log in firebugs
                array(
                    'class'=>'CWebLogRoute',
                    //'levels'=>'trace',     //级别为trace
                    'categories'=>'system.db.*', //只显示关于数据库信息,包括数据库连接,数据库执行语句
                    'showInFireBug'=>true,
                ),
			),
		),
        'image'=>array(
            'class'=>'application.extensions.image.CImageComponent',
            // GD or ImageMagick
            'driver'=>'GD',
            // ImageMagick setup path
            //'params'=>array('directory'=>'/opt/local/bin'),
        )
		//memcache 配置
		/*
		'memcache'=>array(
			'class'=>'system.caching.CMemCache',
			'servers'=>array(
				array(
					'host'=>'127.0.0.1',
					'port'=>11211,
					'weight'=>600,
				),
			),
		),*/
	),
    'params'=>array(
           /* 'session_user' => 'model',
    		'fastdfs_server' => 'http://upload.adt100.net',
    		'fastdfs_upload_url' => 'http://upload.adt100.net/c.php',
    		//redis配置
    		'redis'=>array(
			'servers'=>array("default"=>
					array(
						'host'=>'10.2.30.21',
						'port'=>'6379',
						'timeout'=>'300',
						'auth'=>'',
						'persistent'=>'true',
						'db'=>'0'
					),
			),
		),*/
    ),
));
