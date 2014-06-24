<?php
return CMap::mergeArray( require(dirname(__FILE__).'/base.php'), array(
	// preloading 'log' component
	'preload'=>array('log'),
	'modules'=>array(
        'admin',
		// uncomment the following to enable the Gii tool
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
                    'levels'=>'error, warning',
                    'filter'=>'CLogFilter',
                    'categories'=> 'application.*',
                    'logPath' => '/htdocs/xdjzb/protected/runtime/logs',
                    'logFile' => 'application.log'
                ),
                //发送邮件日志
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'warning, error',
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
	),
    'params'=>array(
           /* 'session_user' => 'model',
    		'fastdfs_server' => 'http://material.smart-tv.cn',
    		'fastdfs_upload_url' => 'http://material.smart-tv.cn/ch.php',
// 			'adLogAdd'=>'http://ch.adt100.com/adinterface/adlog',
			'terminusLog'=>'/home/admin/logs/changhong/terminus/',
    		//redis配置
    		'redis'=>array(
    				'servers'=>array("default"=>
    						array(
    								'host'=>'127.0.0.1',
    								'port'=>'6381',
    								'timeout'=>'300',
    								'auth'=>'',
    								'persistent'=>'true',
    								'db'=>'0'
    						),
    				),
    		),*/
    )
));
