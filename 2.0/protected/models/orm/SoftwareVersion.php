<?php

/**
 * This is the model class for table "software_version".
 *
 * The followings are the available columns in table 'software_version':
 * @property integer $id
 * @property string $version
 * @property string $update_log
 * @property integer $is_last
 * @property string $url
 * @property string $date
 * @property integer $platform
 */
class SoftwareVersion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'software_version';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('version, update_log, is_last, url, date, platform', 'required'),
			array('is_last, platform', 'numerical', 'integerOnly'=>true),
			array('version', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, version, update_log, is_last, url, date, platform', 'safe', 'on'=>'search'),
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
			'version' => '产品版本',
			'update_log' => '更新日志',
			'is_last' => '是否是最新版本',
			'url' => '下载地址',
			'date' => 'Date',
			'platform' => '1是Android；2是iOS',
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
		$criteria->compare('version',$this->version,true);
		$criteria->compare('update_log',$this->update_log,true);
		$criteria->compare('is_last',$this->is_last);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('platform',$this->platform);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SoftwareVersion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
