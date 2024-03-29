<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

require_once("models/config.php");
include_once('config/config.php');
include_once($libs_dir.'/db.php');

$db = new DB($db_name,$db_host,$db_username,$db_password);
$db->query("SET NAMES 'UTF8'");

//Forms posted
if(!empty($_POST))
{
	$errors = array();
	$username = sanitize(trim($_POST["username"]));
	$password = trim($_POST["password"]);
	
	//Perform some validation
	//Feel free to edit / change as required
	if($username == "")
	{
		$errors[] = lang("ACCOUNT_SPECIFY_USERNAME");
	}
	if($password == "")
	{
		$errors[] = lang("ACCOUNT_SPECIFY_PASSWORD");
	}

	if(count($errors) == 0)
	{
		//A security note here, never tell the user which credential was incorrect
		if(!usernameExists($username))
		{
			$errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
		}
		else
		{
			$userdetails = fetchUserDetails($username);
            //Hash the password and use the salt from the database to compare the password.
            $entered_pass = generateHash($password, $userdetails["password"]);

            if($entered_pass != $userdetails["password"])
            {
                //Again, we know the password is at fault here, but lets not give away the combination incase of someone bruteforcing
                $errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
            }
            else
            {
                //Passwords match! we're good to go'

                //Construct a new logged in user object
                //Transfer some db data to the session object
                $redirect = "account.html";
                $loggedInUser = logining($userdetails);
                $user_id = $loggedInUser->user_id;

                $where = "(user_id=$user_id)";
                $result = array("bizCode" => 1, "memo" => "登录成功", "data"=>array("user"=>$loggedInUser, "redirect" => $redirect));
                echo json_encode($result);
                exit;
            }
		}

        if(count($errors) != 0){
            $result = array("bizCode" => 0, "memo" => "登录失败", "data"=>array("msg"=>$errors));
            echo json_encode($result);
            exit;
        }
	}else{
        $result = array("bizCode" => 0, "memo" => "登录失败", "data"=>array("msg"=>$errors));
        echo json_encode($result);
        exit;
    }
}else{
    $result = array("bizCode" => 0, "memo" => "只支持POST登录", "data" => array());
    echo json_encode($result);
    exit;
}

?>
