<?php

/**
 * This is the model class for table "cashier".
 *
 * The followings are the available columns in table 'cashier':
 * @property integer $id
 * @property integer $user_id
 * @property integer $pid
 * @property double $count
 * @property double $selling_price
 * @property string $who
 * @property string $date
 * @property string $remark
 * @property integer $merge_id
 */
class Cashier extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cashier';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, pid, count, selling_price, date', 'required'),
			array('user_id, pid, merge_id', 'numerical', 'integerOnly'=>true),
			array('count, selling_price', 'numerical'),
			array('who', 'length', 'max'=>40),
			array('remark', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, pid, count, selling_price, who, date, remark, merge_id', 'safe', 'on'=>'search'),
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
			'id' => '交易ID',
			'user_id' => '用户id',
			'pid' => '商品ID',
			'count' => '销售数量',
			'selling_price' => '销售价格',
			'who' => '销售员',
			'date' => '销售日期',
			'remark' => '备注',
			'merge_id' => '合并记账的id,如果有这个id,表示该条记录是属于某条合并记账流水',
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
		$criteria->compare('pid',$this->pid);
		$criteria->compare('count',$this->count);
		$criteria->compare('selling_price',$this->selling_price);
		$criteria->compare('who',$this->who,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('merge_id',$this->merge_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cashier the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
