<?php

/**
 * This is the model class for table "notice".
 *
 * The followings are the available columns in table 'notice':
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $author
 * @property string $min_version
 * @property string $max_version
 * @property string $date
 * @property integer $is_last
 * @property integer $position
 */
class Notice extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'notice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, content, date', 'required'),
			array('is_last, position', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>50),
			array('author', 'length', 'max'=>10),
			array('min_version, max_version', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, content, author, min_version, max_version, date, is_last, position', 'safe', 'on'=>'search'),
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
			'title' => '公告标题',
			'content' => '公告内容',
			'author' => '公告作者',
			'min_version' => '最小的版本号',
			'max_version' => '最大的版本',
			'date' => '发布时间或更新时间',
			'is_last' => '是否是最新公告',
			'position' => '1为全局公告，在所有页面顶端显示；0为普通公告，只在用户首页显示',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('author',$this->author,true);
		$criteria->compare('min_version',$this->min_version,true);
		$criteria->compare('max_version',$this->max_version,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('is_last',$this->is_last);
		$criteria->compare('position',$this->position);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Notice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
