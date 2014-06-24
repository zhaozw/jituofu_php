<?php

/**
 * This is the model class for table "rib_users".
 *
 * The followings are the available columns in table 'rib_users':
 */
class Users extends CActiveRecord
{
    //二次输入密码的字段
    public $cpassword;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rib_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            //必填字段
			array('registered_date, role_id', 'required', 'on' => 'register'),
            array('user_name', 'required', 'message' => F::lang("ACCOUNT_SPECIFY_USERNAME")),
            array('email', 'required', 'message' => F::lang("ACCOUNT_SPECIFY_EMAIL")),
            array('password', 'required', 'message' => F::lang("ACCOUNT_SPECIFY_PASSWORD")),
            array('cpassword', 'required', 'message' => F::lang("ACCOUNT_SPECIFY_CPASSWORD")),

            //允许空
            array('location', 'default', 'setOnEmpty' => true),
            array('last_sign_in_date', 'default', 'setOnEmpty' => true),
            array('from', 'default', 'setOnEmpty' => true),

            //去除所有空格
            array('user_name', 'filter', 'filter'=>array($this, 'TrimAllProcessor')),
            array('email', 'filter', 'filter'=>array($this, 'TrimAllProcessor')),
            array('password', 'filter', 'filter'=>array($this, 'TrimAllProcessor')),
            array('cpassword', 'filter', 'filter'=>array($this, 'TrimAllProcessor')),

            //验证字段长度
            array('user_name', 'length', 'min'=>2, 'max'=>25, 'message'=> F::lang("ACCOUNT_USER_CHAR_LIMIT", array(2, 25)), 'tooShort' => F::lang("ACCOUNT_USER_CHAR_LIMIT", array(2, 25)), 'tooLong' => F::lang("ACCOUNT_USER_CHAR_LIMIT", array(2, 25))),
            array('password', 'length', 'min'=>6,  'max'=>50, 'message'=> F::lang("ACCOUNT_PASS_CHAR_LIMIT", array(6, 50)), 'tooShort' => F::lang("ACCOUNT_PASS_CHAR_LIMIT", array(6, 50)), 'tooLong' => F::lang("ACCOUNT_PASS_CHAR_LIMIT", array(6, 50))),

            //验证数据格式
            array('email', 'email', 'message' =>  F::lang("ACCOUNT_INVALID_EMAIL")),

            //比较登录密码
            array (
                'cpassword',
                'compare',
                'compareAttribute' => 'password',
                'message' =>  F::lang("ACCOUNT_PASS_MISMATCH")
            ),

            //验证用户名和邮箱的唯一性
            array('user_name', 'unique','caseSensitive'=>false, 'className'=>'Users', 'message'=> F::lang("ACCOUNT_USERNAME_IN_USE", array("{value}"))),
            array('email', 'unique','caseSensitive'=>false, 'className'=>'Users', 'message'=> F::lang("ACCOUNT_EMAIL_IN_USE", array("{value}"))),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_name, password, email, from, location, last_sign_in_date, registered_date, role_id', 'safe', 'on'=>'search'),
		);
	}

    //去除所有空格
    public function TrimAllProcessor($data){
       return  F::trimAll(F::html2Str($data));
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '用户ID',
			'user_name' => '用户名',
			'password' => '登录密码',
			'email' => '邮箱',
			'from' => '用户来源',
			'location' => '用户的注册地',
			'last_sign_in_date' => '最后登录日期',
			'registered_date' => '注册日期',
			'role_id' => '用户角色'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('from',$this->from,true);
		$criteria->compare('location',$this->location,true);
		$criteria->compare('last_sign_in_date',$this->last_sign_in_date);
		$criteria->compare('registered_date',$this->registered_date,true);
		$criteria->compare('role_id',$this->role_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function beforeSave(){
        $this -> setAttribute('password',  F::generateHash($this -> attributes['password']));
        return true;
    }

    /**
     * 删除指定用户下的所有设备上的cookie
     * @param $userId
     * @return bool
     */
    public static function removeAllCookie($userId)
    {
        $records = Device::model()->findAllByAttributes(array('user_id' => $userId));

        if (!empty($records)) {
            foreach($records as $k => $record){
                $record->delete();
            }
            return true;
        }else{
            return false;
        }
    }

    /**
     * 删除指定用户,指定设备下面的cookie
     * @param $userId
     * @param $uuid
     * @param $cookie
     * @return bool
     */
    public static function removeCookie($userId, $uuid, $cookie)
    {
        $record = Device::model()->findByAttributes(array('user_id' => $userId, 'uuid' => $uuid, 'cookie' => $cookie));

        if ($record) {
            if($record->delete()){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 保存cookie
     * @param $cookie
     * @return bool
     */
    public static function saveCookie($cookie)
    {
        $operation = F::getOperationData();
        $clientId = $operation['clientId'];
        $userId = Yii::app()->user->id;

        $record = Device::model()->findByAttributes(array('user_id' => $userId, 'uuid' => $clientId));

        if ($record) {
            $criteria = new CDbCriteria;
            $criteria->condition = "user_id = $userId and uuid = '$clientId'";
            $rows = Device::model()->updateAll(
                array('cookie'=>$cookie),
                $criteria
            );
            if ($rows < 1) {
                F::error('更新userId为 ' . $userId . ' 的cookie失败');
                return false;
            } else {
                return true;
            }
        }else{
            $newData = array(
                'cookie' => $cookie, 'user_id' => $userId, 'uuid' => $clientId
            );
            $model = new Device();
            $model->attributes = $newData;
            if (!$model->save()) {
                F::error("保存cookie失败 ." . CJSON::encode($model->getErrors()));
                return false;
            }else{
                return true;
            }
        }
    }
}
