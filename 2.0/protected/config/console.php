<?php
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
define('appPath', dirname(__FILE__).DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR);

$confType = 'dev';
//$confType = 'pro';

return CMap::mergeArray( require(dirname(__FILE__).'/'.$confType.'Config.php'), array(
    'name'=>'记托付',
));
