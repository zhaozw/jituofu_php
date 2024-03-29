<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

require_once("models/config.php");

if (empty($_POST)) {
    $result = array("bizCode" => 0, "memo" => "只支持POST登录", "data" => array());
    echo json_encode($result);
    exit;
}
$errors = array();
$success = array();

if ($client_action === "find") {
    $email = @trim($_POST["email"]);
    $username = @sanitize($_POST["username"]);
    $captcha = @md5($_POST["captcha"]);

    if ($captcha != $_SESSION['captcha']) {
        $errors[] = lang("CAPTCHA_FAIL");
    } else if (trim($email) == "") {
        $errors[] = lang("ACCOUNT_SPECIFY_EMAIL");
    } //Check to ensure email is in the correct format / in the db
    else if (!isValidEmail($email)) {
        $errors[] = lang("ACCOUNT_INVALID_EMAIL");
    } else if (!emailExists($email)) {
        $errors[] = lang("EMAIL_NO_REGISTER", array($email));
    }

    if (trim($username) == "") {
        $errors[] = lang("ACCOUNT_SPECIFY_USERNAME");
    } else if (!usernameExists($username)) {
        $errors[] = lang("ACCOUNT_NOEXIST_USERNAME", array($websiteName));
    }

    if (count($errors) == 0) {
        //Check that the username / email are associated to the same account
        if (!emailUsernameLinked($email, $username)) {
            $errors[] = lang("ACCOUNT_USER_OR_EMAIL_INVALID");
        } else {
            //Check if the user has any outstanding lost password requests
            $userdetails = fetchUserDetails($username);
            //Email the user asking to confirm this change password request
            //We can use the template builder here

            //We use the activation token again for the url key it gets regenerated everytime it's used.

            $mail = new userCakeMail();
            $checkcode = generate6Random();

            //保存检验码
            saveCheckCode($userdetails['id'], $checkcode);

            //Setup our custom hooks
            $hooks = array(
                "searchStrs" => array("#WEBSITENAME#", "#ACTIVATIONCODE#", "#USERNAME#"),
                "subjectStrs" => array($websiteName, $checkcode, $userdetails["user_name"])
            );

            if (!$mail->newTemplateMsg("lost-password-request-client.txt", $hooks)) {
                $errors[] = lang("MAIL_TEMPLATE_BUILD_ERROR");
            } else {
                if (!$mail->sendMail($userdetails["email"], "找回密码")) {
                    $errors[] = lang("MAIL_ERROR", array($websiteName));
                } else {
                    $msg = lang("FORGOTPASS_REQUEST_SUCCESS");
                    $result = array("bizCode" => 1, "memo" => "", "data" => array("msg" => array($msg)));
                    echo json_encode($result);
                    exit;
                }
            }
        }
        if (count($errors) != 0) {
            $result = array("bizCode" => 0, "memo" => "", "data" => array("msg" => $errors));
            echo json_encode($result);
            exit;
        }
    } else {
        $result = array("bizCode" => 0, "memo" => "", "data" => array("msg" => $errors));
        echo json_encode($result);
        exit;
    }
}

if ($client_action === "reset") {
    $token = @trim($_POST["token"]);
    $password = trim($_POST["password"]);
    $confirm_pass = trim($_POST["passwordc"]);

    if ($token == "") {
        $errors[] = lang("FORGOTPASS_INVALID_TOKEN");
    }else if(!getUidByCheckCode($token)){
        $errors[] = lang("FORGOTPASS_INVALID_TOKEN");
    } else {
        $uid = getUidByCheckCode($token);
        if(!$uid){
            $errors[] = lang("FORGOTPASS_INVALID_TOKEN");
        }else{
            if (minMaxRange(6, 50, $password) && minMaxRange(6, 50, $confirm_pass)) {
                $errors[] = lang("ACCOUNT_PASS_CHAR_LIMIT", array(6, 50));
            } else if ($password != $confirm_pass) {
                $errors[] = lang("ACCOUNT_PASS_MISMATCH");
            }

            $userdetails = fetchUserDetails(NULL, $uid); //Fetchs user details
            $mail = new userCakeMail();

            //Setup our custom hooks
            $hooks = array(
                "searchStrs" => array("#WEBSITENAME#", "#USERNAME#"),
                "subjectStrs" => array($websiteName, $userdetails["user_name"])
            );

            if (!$mail->newTemplateMsg("your-lost-password-client.txt", $hooks)) {
                $errors[] = lang("MAIL_TEMPLATE_BUILD_ERROR");
            } else {
                $secure_pass = generateHash($password);

                if (!$mail->sendMail($userdetails["email"], "修改密码")) {
                    $errors[] = lang("MAIL_ERROR", array($websiteName));
                } else {
                    if (!updatePasswordFromToken($secure_pass, $uid)) {
                        $errors[] = lang("SQL_ERROR");
                    } else {
                        deleteCheckCode($uid);
                        $msg = lang("FORGOTPASS_NEW_PASS_EMAIL_CLIENT");
                        $result = array("bizCode" => 1, "memo" => "", "data" => array("msg" => array($msg)));
                        echo json_encode($result);
                        exit;
                    }
                }
            }
        }

        if (count($errors) != 0) {
            $result = array("bizCode" => 0, "memo" => "", "data" => array("msg" => $errors));
            echo json_encode($result);
            exit;
        }
    }

    if (count($errors) != 0) {
        $result = array("bizCode" => 0, "memo" => "", "data" => array("msg" => $errors));
        echo json_encode($result);
        exit;
    }
}

?>
