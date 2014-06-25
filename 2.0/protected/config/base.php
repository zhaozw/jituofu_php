<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'language'=>'zh_cn',
	// preloading 'log' component
	'preload'=>array('log'),
	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.models.orm.*',//mysql orm
		'application.models.service.*',//service
		'application.models.cform.*',//mysql orm
		'application.widget.*',
		'application.components.*',
		'application.extensions.Utils.*',
		'application.extensions.memcache.*',
	),
	// application components
	'components'=>array(
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
 			'urlSuffix'=>'',
			'rules'=>array(
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>'
			),
		),
		'errorHandler'=>array(
			'errorAction'=>'default/error',
		), 

		/* *
		 *验证码
		 */
		'captcha' => array(
			'class'=>'CCaptchaAction',
			'foregroundColor'=>'#FFAA00'
		),

		/* *
		 * session组件
		 */
//		'session'=>array(
//			'class'=>'system.web.CDbHttpSession',
//			'timeout'=>	30*60,
//			'connectionID' => 'db',
//			'sessionTableName' => 'rib_session',
//		),
		
		'fileCache'=>array(
			'class'=>'system.caching.CFileCache',
			'varyByParam'=>true,
		),
		'dbCache'=>array(
			'class'=>'system.caching.CDbCache',
		),

	),
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require_once 'params.php',
);