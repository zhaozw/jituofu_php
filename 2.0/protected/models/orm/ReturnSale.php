<?php

/**
 * This is the model class for table "return_sale".
 *
 * The followings are the available columns in table 'return_sale':
 * @property integer $id
 * @property integer $user_id
 * @property integer $sale_id
 * @property string $reason
 * @property string $remark
 * @property string $who
 * @property string $date
 * @property double $count
 */
class ReturnSale extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'return_sale';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, sale_id, reason, date, count', 'required'),
			array('user_id, sale_id', 'numerical', 'integerOnly'=>true),
			array('count', 'numerical'),
			array('who', 'length', 'max'=>40),
			array('remark', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, sale_id, reason, remark, who, date, count', 'safe', 'on'=>'search'),
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
			'sale_id' => '销售记录id',
			'reason' => '退货原因',
			'remark' => '备注',
			'who' => '谁退的货',
			'date' => 'Date',
			'count' => '退货数量',
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
		$criteria->compare('sale_id',$this->sale_id);
		$criteria->compare('reason',$this->reason,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('who',$this->who,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('count',$this->count);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ReturnSale the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}



    /**
     * 添加一条退货记录
     * @param $model
     * @return array|bool
     */
    public static function add($model){
        if($model->save()){
            return true;
        }else{
            return false;
        }
    }
}
