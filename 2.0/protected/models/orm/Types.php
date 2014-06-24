<?php

/**
 * This is the model class for table "types".
 *
 * The followings are the available columns in table 'types':
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property integer $parent_id
 * @property integer $child_id
 * @property integer $time
 * @property integer $status
 */
class Types extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'types';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, name, time', 'required', 'message' =>F::lang('TYPE_NAME_SPECIFY')),
			array('user_id, parent_id, status', 'numerical', 'integerOnly'=>true),

            //去除所有空格
            array('name', 'filter', 'filter'=>array($this, 'TrimAllProcessor')),

            //长度
            array('name', 'length', 'min'=>2, 'max'=>10, 'message'=> F::lang("TYPE_NAME_CHAR_LIMIT", array(2, 10)), 'tooShort' => F::lang("TYPE_NAME_CHAR_LIMIT", array(2, 10)), 'tooLong' => F::lang("TYPE_NAME_CHAR_LIMIT", array(2, 10))),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, name, parent_id, child_id, time, status', 'safe', 'on'=>'search'),
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
			'id' => '商品分类ID',
			'user_id' => '用户id',
			'name' => '商品分类名称',
			'parent_id' => 'Parent',
			'child_id' => 'Child',
            'time' => "创建时间",
			'status' => '是否启用,1为启用,为不停用',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('child_id',$this->child_id);
        $criteria->compare('time',$this->time);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Types the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * 根据商品分类id检测该分类是否是小分类
     * @param $id
     * @return bool
     */
    public static function isChild($id){
        $public = F::getPublicData();
        $record = Types::model()->findByAttributes(array('id'=>$id, 'user_id' => $public['userId']));
        if(!$record){
            return false;
        }

        if($record->getAttribute('parent_id')){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 通过分类id获取分类名称
     * @param $id
     * @return array
     */
    public static function getTypeNameById($id){
        if(!$id){
            return false;
        }

        $public = F::getPublicData();
        if(Types::isChild($id)){
            $record = Types::model()->findByAttributes(array('id'=>$id, 'user_id' => $public['userId']));
            if(!$record){
                return false;
            }
            $childName = $record->getAttribute('name');
            $parent = $record->getAttribute('parent_id');
            $parentRecord = Types::model()->findByAttributes(array('id'=>$parent, 'user_id' => $public['userId']));
            $parentName = $parentRecord->getAttribute('name');

            return array('child' => $childName, 'parent' => $parentName);
        }else{
            $record = Types::model()->findByAttributes(array('id'=>$id, 'user_id' => $public['userId']));
            if(!$record){
                return false;
            }
            $parentName = $record->getAttribute('name');
            return array('parent' => $parentName);
        }
    }

    /**
     * 创建默认分类
     * @param $userId
     */
    public static function createDefaultType($userId){
        $model=new Types('createParent');
        $model->attributes=array(
            'user_id' => $userId,
            'name' => "默认分类"
        );
        $model->save();
    }

    /**
     * 获取默认分类
     * @param $userId
     * @param $typeName
     * @return record
     */
    public static function getDefaultType($userId, $typeName){
        $public = F::getPublicData();
        $record = Types::model()->findByAttributes(array('name'=>$typeName, 'user_id' => $userId, 'parent_id'=>null));

        return $record;
    }
}
