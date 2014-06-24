<?php

/**
 * This is the model class for table "wse".
 *
 * The followings are the available columns in table 'wse':
 * @property integer $id
 * @property string $address
 * @property string $subject
 * @property string $content
 * @property string $time
 */
class Wse extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'wse';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('address, subject, content, time', 'required'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, address, subject, content, time', 'safe', 'on'=>'search'),
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
			'address' => '邮件地址',
			'subject' => '邮件主题',
			'content' => '邮件内容',
			'time' => 'Time',
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
		$criteria->compare('address',$this->address,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('time',$this->time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Wse the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * 添加一条待发送邮件
     * @param $address
     * @param $subject
     * @param $content
     * @return boolean
     */
    public static function add($address, $subject, $content){
        $model=new Wse;

        if(!$address || !$subject || !$content){
            return false;
        }

        $model->attributes= array(
            "address" => $address,
            "subject" => $subject,
            "content" => $content,
            "time" => F::getCurrentDatetime()
        );

        return $model -> save();
    }

    /**
     * 根据id删除待发送邮件
     * @param $id
     * @return bool
     */
    public static function deleteById($id){
        if(!$id){
            return false;
        }

        $record = Wse::model()->findByAttributes(array('id' => $id));
        if($record){
            return $record->delete();
        }else{
            return false;
        }
    }

    /**
     * 获取所有待发送邮件
     * @return array
     */
    public static function getAllRecords(){
        $criteria=new CDbCriteria;
        $criteria->order = 'time ASC';
        $criteria->limit = 10;

        $records = Wse::model()->findAll($criteria);

        return $records;
    }
}
