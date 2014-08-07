<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/
require_once("db-settings.php"); //Require DB connection

ini_set("display_errors", "1");
error_reporting(E_ALL);
date_default_timezone_set('Asia/Shanghai');

//Set Settings
$emailActivation = false;
$mail_templates_dir = "models/mail-templates/";
$websiteName = "记托付";
$websiteUrl = "jituofu.com";
$emailAddress = "service@jituofu.com";
$version = "2.0.0";
$android_version = "2.0.0";
$android_update_log = "";
$resend_activation_threshold = 3;
$date = date("Y-m-d H:i:s");
$language = "languages/en.php";
$template = "site-templates/default.css";

$master_account = -1;

$default_hooks = array("#WEBSITENAME#","#WEBSITEURL#","#DATE#");
$default_replace = array($websiteName,$websiteUrl,$date);

if (!file_exists($language)) {
	$language = "languages/en.php";
}

if(!isset($language)) $language = "languages/en.php";

//Pages to require
require_once($language);
require_once("class.mail.php");
require_once("class.user.php");
require_once("class.newuser.php");
require_once("funcs.php");

session_start();

//Global User Object Var
//loggedInUser can be used globally if constructed
if(isset($_SESSION["userCakeUser"]) && is_object($_SESSION["userCakeUser"]))
{
	$loggedInUser = $_SESSION["userCakeUser"];
}else if(isset($_COOKIE['rib_user_name']) && isset($_COOKIE['rib_user_pw']) && $_COOKIE['rib_user_name'] && $_COOKIE['rib_user_pw']){
    $userdetails = fetchUserDetails($_COOKIE['rib_user_name']);

    if($_COOKIE['rib_user_pw'] === $userdetails["password"]){
        $loggedInUser = logining($userdetails);
    }
}
?>
