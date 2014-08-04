<?php

/**
 * This is the model class for table "store_settings".
 *
 * The followings are the available columns in table 'store_settings':
 * @property integer $id
 * @property integer $user_id
 * @property integer $tip_rent
 * @property integer $name
 */
class StoreSettings extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'store_settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, tip_rent', 'required'),
			array('user_id, tip_rent', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, tip_rent, name', 'safe', 'on'=>'search'),
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
			'tip_rent' => '是否开启每日录入租金',
            'name' => '商户名称',
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
		$criteria->compare('tip_rent',$this->tip_rent);
        $criteria->compare('name',$this->name);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StoreSettings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * 保存商户设置
     * @return bool
     */
    public static function saveStoreSettings()
    {
        $operation = F::getOperationData();
        $userId = Yii::app()->user->id;

        @$tip_rent = $operation['tip_rent'];

        //默认关闭每日录入租金提示
        if (!$tip_rent) {
            $tip_rent = 0;
        }

        $record = StoreSettings::model()->findByAttributes(array('user_id' => $userId));

        if ($record) {
            $criteria = new CDbCriteria;
            $criteria->condition = "user_id = $userId";
            $rows = StoreSettings::model()->updateAll(
                array('tip_rent' => $tip_rent),
                $criteria
            );
            if ($rows < 1) {
                F::error("更新 $userId 的每日提醒录入租金失败");
                return false;
            } else {
                return true;
            }
        } else {
            $model = new StoreSettings();
            $model->attributes = array('user_id' => $userId, 'tip_rent' => $tip_rent);
            if ($model->save()) {
                return true;
            } else {
                F::error("保存 $userId 每日提醒录入租金失败: ".CJSON::encode($model->getErrors()));
                return false;
            }
        }
    }
}
