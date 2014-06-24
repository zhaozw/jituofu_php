<?php
/**
 * 用户登录校验
 * Class UserIdentity
 */
class UserIdentity extends CUserIdentity
{
    public $errorMessage;
    public $userId;
    public $user;
    public $device;

	public function authenticate()
	{
        $username = F::trimAll(F::html2Str($this -> username));
        $password = F::trimAll(F::html2Str($this -> password));

        if(!$username){
            $this -> errorMessage = F::lang("ACCOUNT_SPECIFY_USERNAME");
            return false;
        }else if(!$password){
            $this -> errorMessage = F::lang("ACCOUNT_SPECIFY_PASSWORD");
            return false;
        }

        $record = Users::model()->findByAttributes(array('user_name'=>$username));
        if(!$record){
            $this -> errorMessage = F::lang("ACCOUNT_INVALID_USERNAME");
            return false;
        }

        $recordPassword = $record ->getAttribute('password');
        $enteredPassword = F::generateHash($password, $recordPassword);

        if($enteredPassword !== $recordPassword){
            $this -> errorMessage = F::lang("ACCOUNT_USER_OR_PASS_INVALID");
            return false;
        }else{
            $rows = Users::model() -> updateByPk(
                $record -> getAttribute('id'),
                array('last_sign_in_date' => F::getCurrentDatetime())
            );
            //更新最后登录日期失败
            if($rows < 1){
                F::error('更新'.$record ->getAttribute('user_name').'的最后登录日期失败');
            }

            $this -> userId = $record -> getAttribute('id');
            return true;
        }
    }
 
    public function getId()
    {
        return $this->userId;
    }
}