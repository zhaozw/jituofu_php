<?php

/**
 * This is the model class for table "device".
 *
 * The followings are the available columns in table 'device':
 * @property integer $id
 * @property integer $user_id
 * @property string $uuid
 * @property string $token
 * @property string $name
 * @property string $cookie
 */
class Device extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'device';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, uuid', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('uuid', 'length', 'max'=>128),
			array('token', 'length', 'max'=>64),
			array('name', 'length', 'max'=>50),
			array('cookie', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, uuid, token, name, cookie', 'safe', 'on'=>'search'),
		);
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
			'id' => 'ID',
			'user_id' => 'User',
			'uuid' => '设备uuid',
			'token' => '设备的push token',
			'name' => '设备名称',
			'cookie' => '设备上的cookie',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('uuid',$this->uuid,true);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('cookie',$this->cookie,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    /**
     * 更新push token
     * @return bool
     */
    public static function savePushToken(){
        $public = F::getPublicData();
        $operation = F::getOperationData();
        $userId = Yii::app()->user->id;

        $pushToken = $public['pushToken'];
        $clientId = $operation['clientId'];

        if (!$pushToken) {
            $pushToken = '';
        }

        if (!$clientId) {
            F::warn(F::lang("MEMO_NO_CLIENTID"));
            return false;
        }

        $record = Device::model()->findByAttributes(array('user_id' => $userId, 'uuid' => $clientId));

        if ($record) {
            $criteria = new CDbCriteria;
            $criteria->condition = "user_id=$userId and uuid='$clientId'";
            $rows = Device::model()->updateAll(
                array('token' => $pushToken),
                $criteria
            );
            if ($rows < 1) {
                F::warn('更新userId为 ' . $userId . ' 的pushToken失败');
                return false;
            } else {
                return true;
            }
        } else {
            $model = new Device();
            $model->attributes = array('user_id' => $userId, 'uuid' => $clientId, 'token' => $pushToken);
            if ($model->save()) {
                return true;
            } else {
                F::error(CJSON::encode($model->getErrors()));
                return false;
            }
        }
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Device the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
