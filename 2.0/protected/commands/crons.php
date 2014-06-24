<?php
/**
 * Created by JetBrains PhpStorm.
 * User: praise
 * Date: 5/23/14
 * Time: 3:04 PM
 * To change this template use File | Settings | File Templates.
 */

$yii = dirname(__FILE__).'/../../framework/yii.php';

require_once($yii);

$configFile = dirname(__FILE__).'/../config/console.php';

defined('YII_DEBUG') or define('YII_DEBUG',true);

Yii::createConsoleApplication($configFile)->run();