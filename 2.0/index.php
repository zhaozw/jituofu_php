<?php
//核心文件
$yii=dirname(__FILE__).'/framework/yii.php';
//配置文件
$config = dirname(__FILE__).'/protected/config/main.php';
date_default_timezone_set('Asia/Shanghai');
//项目根目录绝对路径
defined('SYS_ROOT_DIR') || define('SYS_ROOT_DIR',__DIR__);
//BUG
defined('YII_DEBUG') or define('YII_DEBUG', true);

// 定义 运行环境 正式环境用：production
if ( $_SERVER['SERVER_NAME'] == 'jizhangbao.com.cn:80' )
{
	define('ENVIRONMENT', 'production');
}
else
{
	define('ENVIRONMENT', 'development');
}

//BUG
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
//加载
require_once($yii);
//加载
Yii::createWebApplication($config)->run();
