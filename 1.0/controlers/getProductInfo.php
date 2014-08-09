<?php
/**
 * Created by JetBrains PhpStorm.
 * User: praise
 * Date: 9/20/13
 * Time: 6:44 PM
 * To change this template use File | Settings | File Templates.
 */
require_once("models/config.php");

switch($client_action){
    case "query":
        $data = array("productname" => $websiteName, "version" => "1.1.4", "url" => $websiteUrl, "email" => $emailAddress, "android_version" => "2.0", "android_update_log" => $android_update_log);
        $result = array("bizCode" => 1, "memo" => "", "data" => $data);
        echo json_encode($result);
        break;
    default:
        exit(json_encode(array("bizCode" => 0, "memo" => "没有action参数", "data" => array())));
        break;
}
?>