<?php

class UsersController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('create', 'login', 'forgot', 'forgot2', 'updateLSID'),
				'users'=>array('*'),
                'verbs' => array('post', 'get')
			),
            array('allow',
                'actions'=>array('getInfo', 'update', 'logout'),
                'users'=>array('*'),
                'verbs' => array('post', 'get')
            ),
			array('deny',  // deny all users
				'users'=>array('*'),
			)
		);
	}

	/**
	 * Creates a new model.
	 */
	public function actionCreate()
	{
        if(F::notLoggedCommonVerify()){
            $model=new Users('register');

            $operation = F::getOperationData();

            $clientId = @$operation['clientId'];

            if(!$clientId){
                F::returnError(F::lang('MEMO_NO_CLIENTID'));
            }

            //必填参数
            $username = @$operation['username'];
            $password = @$operation['password'];
            $cpassword = @$operation['cpassword'];
            $email = @$operation['email'];
            //可选参数
            $from = Yii::app()->name;
            $location = @$operation['location'];
            $last_sign_in_date = '';
            $registered_date = F::getCurrentDatetime();
            $role_id = 1;//普通用户

            $new_user_data = array(
                "user_name" => $username,
                "password" => $password,
                "cpassword" => $cpassword,
                "email" => strtolower($email),
                "from" => $from,
                "location" => $location,
                "last_sign_in_date" => $last_sign_in_date,
                "registered_date" => $registered_date,
                "role_id" => $role_id
            );
            $model->attributes = $new_user_data;

            $this->performAjaxValidation($model);
            if ($model->save()) {
                $hooks = array(
                    "searchStrs" => array("#USERNAME#"),
                    "subjectStrs" => array($model->attributes['user_name'])
                );
                $mailTem = new MailTemplate();
                $mailNewTem = @$mailTem -> getTemplate('register.txt', $hooks);
                Marketing::saveMarketing();
                Types::createDefaultType($model->primaryKey);
                F::sendMail($model->attributes['email'], F::lang('EMAILTITLE_REGISTER_SUCCESS'), $mailNewTem);
                //注册成功后自动登录
                $this->register2login();
            } else {
                F::returnError(F::lang('MEMO_REGISTER_ERROR'), $model->getErrors());
            }
        }
	}

    /**
     * 注册成功后自动登录
     */
    protected function register2login(){
        $operation = F::getOperationData();

        $username = $operation['username'];
        $password = $operation['password'];

        $identity = new UserIdentity($username, $password);
        if(!$identity -> authenticate()){
            F::returnSuccess(F::lang('EMAILTITLE_REGISTER_SUCCESS'));
        }else{
            if(Yii::app()->user->login($identity, 0)){
                $cookie = F::generateCookie($operation['clientId']);
                Users::saveCookie($cookie);
                StoreSettings::saveStoreSettings();
                Device::savePushToken();
                F::returnSuccess(F::lang('MEMO_REGISTER_LOGIN_SUCCESS'), array('id' => Yii::app()->user->id,
                    'cookie' => $cookie
                ));
            }else{
                F::returnSuccess(F::lang('EMAILTITLE_REGISTER_SUCCESS'));
            }
        }
    }

    public function actionUpdateLSID(){
        if(F::loggedCommonVerify()){
            $public = F::getPublicData();
            $userId = F::trimAll($public['userId']);

            if(Users::updateLSID($userId)){
                F::returnSuccess(F::lang('COMMON_CZ_SUCCESS'));
            }else{
                F::returnError(F::lang("COMMON_CZ_ERROR"));
            }
        }
    }
    public function actionLogin(){
        if(F::notLoggedCommonVerify()){
            $operation = F::getOperationData();

            $clientId = $operation['clientId'];

            if(!$clientId){
                F::returnError(F::lang('MEMO_NO_CLIENTID'));
            }

            $username = $operation['username'];
            $password = $operation['password'];

            $identity = new UserIdentity($username, $password);
            if(!$identity -> authenticate()){
                F::returnError($identity -> errorMessage);
            }else{
                if(Yii::app()->user->login($identity, 0)){
                    $cookie = F::generateCookie($operation['clientId']);
                    Users::saveCookie($cookie);
                    Marketing::saveMarketing();
                    Device::savePushToken();
                    F::returnSuccess(F::lang('MEMO_REGISTER_LOGIN_SUCCESS'), array('id' => Yii::app()->user->id,
                        'cookie' => $cookie
                    ));
                }else{
                    F::returnError(F::lang('MEMO_LOGIN_EXCEPTION'));
                }
            }
        }
    }

    public function actionForgot(){
        if(F::notLoggedCommonVerify()){
            $operation = F::getOperationData();

            $step = @F::trimAll($operation['step']);

            //第二步:设置新密码
            if($step && (int) $step === 2){
                return $this -> actionForgot2();
            }

            $username = F::trimAll($operation['username']);
            $email = F::trimAll($operation['email']);


            if(!$username){
                F::returnError(F::lang('ACCOUNT_SPECIFY_USERNAME'));
            }
            if(!$email){
                F::returnError(F::lang('ACCOUNT_SPECIFY_EMAIL'));
            }else if(!F::isValidEmail($email)){
                F::returnError(F::lang('ACCOUNT_INVALID_EMAIL'));
            }

            $record = Users::model()->findByAttributes(array('user_name'=>$username));

            if(!$record){
                F::returnError(F::lang('MEMO_NOEXIST_USER'));
            }

            if($record->getAttribute('email') !== $email){
                F::returnError(F::lang('ACCOUNT_USER_OR_EMAIL_INVALID'));
            }

            $check_code = F::generate6Random();
            $hooks = array(
                "searchStrs" => array("#USERNAME#", '#DATE#', '#CHECKCODE#'),
                "subjectStrs" => array($username, F::getCurrentDatetime(), $check_code)
            );
            $mailTem = new MailTemplate();
            $mailNewTem = $mailTem -> getTemplate('lost-password-request.txt', $hooks);
            if(F::send6Random($record, $check_code, "找回密码", $mailNewTem)){
                F::returnSuccess(F::lang('FORGOT_PASS_REQUEST_SUCCESS'), array("id" => $record->getAttribute("id")));
            }else{
                F::returnError(F::lang('FORGOT_PASS_ERROR'));
            }
        }
    }

    public function actionForgot2(){
        if(F::notLoggedCommonVerify()){
            $operation = F::getOperationData();
            $public = F::getPublicData();

            $userId = @F::trimAll($public['userId']);
            $password = @F::trimAll($operation['password']);
            $cpassword = @F::trimAll($operation['cpassword']);
            $token = @F::trimAll($operation['token']);

            if(!$userId){
                F::returnError(F::lang('MEMO_NO_USERID'));
            }

            $record = Users::model()->findByAttributes(array('id'=>$userId));

            if(!$record){
                F::returnError(F::lang('MEMO_NOEXIST_USER'));
            }

            if($token){
                $token = strtolower($token);
                $hasCCrecord = CheckCode::model() -> findByAttributes(array('user_id'=>$record -> getAttribute('id'), 'check_code' => md5($token)));
                if(!$hasCCrecord){
                    F::returnError(F::lang('CHECKCODE_ERROR'));
                }else{
                    //时间差
                    $timec = time()-$hasCCrecord -> getAttribute('time');
                    //验证码过期
                    if((int) $timec > Yii::app()->params['checkCodeInvalidTime']){
                        $hasCCrecord -> delete();
                        F::returnError(F::lang('CHECKCODE_INVALID'));
                    }else{
                        $model=$this->loadModel($userId);
                        $model->attributes = array(
                            'password' => $password,
                            'cpassword' => $cpassword
                        );

                        $this->performAjaxValidation($model);

                        if($model -> save(true, array( "password", "cpassword"))){
                            $hooks = array(
                                "searchStrs" => array("#USERNAME#", '#DATE#'),
                                "subjectStrs" => array($record->getAttribute('user_name'), F::getCurrentDatetime())
                            );
                            $mailTem = new MailTemplate();
                            $mailNewTem = $mailTem -> getTemplate('your-lost-password.txt', $hooks);
                            F::sendMail($record->getAttribute('email'), F::lang('EMAILTITLE_SECURITY_TIPS'), $mailNewTem);
                            //密码设置成功后,删除token
                            $hasCCrecord -> delete();
                            F::returnSuccess(F::lang('FORGOT_PASS_NEW_PASS_EMAIL_SUCCESS'));
                        }else{
                            F::returnError(F::lang('FORGOT_PASS_NEW_PASS_EMAIL_ERROR'), $model->getErrors());
                        }
                    }
                }
            }else{
                F::returnError(F::lang('CHECKCODE_SPECIFY'));
            }
        }
    }

    public function actionLogout(){
        if(F::loggedCommonVerify(true)){
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $userId = @$public['userId'];
            $cookie = @$public['cookie'];
            $uuid = @$operation['clientId'];

            if(!$uuid){
                F::returnError(F::lang('MEMO_NO_CLIENTID'));
            }

            $record = Users::model()->findByAttributes(array('id'=>$userId));

            if(!$record){
                F::returnError(F::lang('MEMO_NOEXIST_USER'));
            }else{
                if(Users::removeCookie($userId, $uuid, $cookie)){
                    F::returnSuccess(F::lang('MEMO_LOGOUT_SUCCESS'));
                }else{
                    F::returnVerifyError(F::lang('MEMO_USER_VERIFY_ERROR'));
                }
            }
        }
    }
    public function actionGetInfo(){
        if(F::loggedCommonVerify(true)){
            $public = F::getPublicData();
            $operation = F::getOperationData();

            if(!@$operation['clientId']){
                F::returnError(F::lang('MEMO_NO_CLIENTID'));
            }

            $userId = $public['userId'];

            $record = Users::model()->findByAttributes(array('id'=>$userId));

            if(!$record){
                F::returnError(F::lang('MEMO_NOEXIST_USER'));
            }else{
                $userInfo = array(
                    'id' => $record -> getAttribute('id'),
                    'username' => $record -> getAttribute('user_name'),
                    'email' => $record -> getAttribute('email'),
                    'from' => $record -> getAttribute('from')
                );
                F::returnSuccess(F::lang('MEMO_GET_USER_INFO_SUCCESS'), $userInfo);
            }
        }
    }

	public function actionUpdate()
	{
        if(F::loggedCommonVerify(true)){
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $userId = $public['userId'];
            $record = Users::model()->findByAttributes(array('id'=>$userId));

            if(!$record){
                F::returnError(F::lang('MEMO_NOEXIST_USER'));
            }

            $model=$this->loadModel($userId);
            $attributes = array();
            $user_data = array();

            $email = @$operation['email'];
            $password = @$operation['password'];
            $cpassword = @$operation['cpassword'];
            $oldPassword = @$operation['opassword'];

            //如果用户要修改登录密码,必须提供旧登录密码
            if($password){
                if(!$oldPassword){
                    F::returnError(F::lang('MEMO_NO_OLDPASSWORD'));
                }else{
                    if(F::trimAll($password) !== F::trimAll($cpassword)){
                        F::returnError(F::lang("ACCOUNT_PASSWORD_NO_EQUAL"));
                    }
                    $oldPassword = F::trimAll($oldPassword);
                    //验证老密码
                    $recordPassword = $record ->getAttribute('password');
                    $enteredPassword = F::generateHash($oldPassword, $recordPassword);

                    if($enteredPassword !== $recordPassword){
                        F::returnError(F::lang("ACCOUNT_INVALID_CURRENT_PASSWORD"));
                    }else if($oldPassword === F::trimAll($password)){
                        F::returnError(F::lang('ACCOUNT_PASSWORD_NOTHING_TO_UPDATE'));
                    }
                }
            }

            //更新邮箱
            if($email){
                $user_data = array(
                    "email" => strtolower($email)
                );
                $attributes = array('email');
            }
            //更新密码
            if($password && $cpassword){
                $user_data = array(
                    "password" => $password,
                    "cpassword" => $cpassword
                );
                $attributes = array( "password", "cpassword");
            }
            //更新密码和邮箱
            if($password && $cpassword && $email){
                $user_data = array(
                    "email" => strtolower($email),
                    "password" => $password,
                    "cpassword" => $cpassword
                );
                $attributes = array('email', "password", "cpassword");
            }
            $model->attributes = $user_data;

            $this->performAjaxValidation($model);

            //如果新邮箱和旧邮箱一致,不更新
            if($email && strtolower($email) === $record -> getAttribute('email')){
                $model -> addError('email', F::lang('ACCOUNT_EMAIL_NOTHING'));
                F::returnError(F::lang('ACCOUNT_EMAIL_NOTHING'), $model->getErrors());
            }

            if ($model->save(true, $attributes)) {
                $hooks = array(
                    "searchStrs" => array("#USERNAME#"),
                    "subjectStrs" => array($record -> getAttribute('user_name'))
                );
                $mailTem = new MailTemplate();
                $mailNewTem = $mailTem -> getTemplate('update_user_info.txt', $hooks);
                F::sendMail($model->attributes['email'], F::lang("EMAILTITLE_USERINFO_UPDATE_SUCCESS"), $mailNewTem);
                if($password){
                    $this -> update2logout();
                }else{
                    F::returnSuccess(F::lang('ACCOUNT_EMAIL_UPDATED'));
                }
            } else {
                F::returnError(F::lang('MEMO_USERINFO_UPDATE_ERROR'), $model->getErrors());
            }
        }
	}

    protected function update2logout(){
        $public = F::getPublicData();
        $userId = $public['userId'];

        if(Users::removeAllCookie($userId)){
            F::returnSuccess(F::lang('ACCOUNT_PASSWORD_UPDATED'));
        }else{
            F::returnSuccess(F::lang('ACCOUNT_DETAILS_UPDATED'));
        }
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Users the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Users::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Users $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
