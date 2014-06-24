<?php

/**
 * This is the model class for table "products".
 *
 * The followings are the available columns in table 'products':
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property double $count
 * @property string $from
 * @property string $man
 * @property double $price
 * @property string $pic
 * @property string $date
 * @property integer $type
 * @property integer $status
 * @property string $remark
 */
class Products extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'products';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, date', 'required'),
            array('remark', 'safe'),
            array('user_id, type, status', 'numerical', 'integerOnly'=>true),

            //必填
            array('name', 'required', 'message' => F::lang("PRODUCT_NAME_SPECIFY")),
            array('price', 'required', 'message' => F::lang("PRODUCT_PRICE_SPECIFY")),
            array('count', 'required', 'message' => F::lang("PRODUCT_COUNT_SPECIFY")),
            array('type', 'required', 'message' => F::lang("PRODUCT_TYPE_SPECIFY")),

            //去除所有空格
            array('name', 'filter', 'filter'=>array($this, 'TrimAllProcessor')),
            array('price', 'filter', 'filter'=>array($this, 'TrimAllProcessor')),
            array('remark', 'filter', 'filter'=>array($this, 'TrimAllProcessor')),
            array('count', 'filter', 'filter'=>array($this, 'TrimAllProcessor')),
            array('pic', 'filter', 'filter'=>array($this, 'TrimAllProcessor')),

            //数据类型
            array('count', 'numerical', 'message' => F::lang("PRODUCT_COUNT_INVALID")),
            array('price', 'numerical', 'message' => F::lang("PRODUCT_PRICE_INVALID")),

            //长度
			array('name', 'length', 'min'=>2, 'max'=>15, 'message'=> F::lang("PRODUCT_CHAR_LIMIT", array(2, 15)), 'tooShort' => F::lang("PRODUCT_CHAR_LIMIT", array(2, 15)), 'tooLong' => F::lang("PRODUCT_CHAR_LIMIT", array(2, 15))),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, name, count, from, man, price, pic, date, type, status, remark', 'safe', 'on'=>'search'),
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
			'id' => '产品ID',
			'user_id' => '用户id',
			'name' => '产品名称',
			'count' => '产品数量',
			'from' => '产品采购源',
			'man' => '采购人',
			'price' => '产品价格',
			'pic' => '产品图片',
			'date' => '采购日期',
			'type' => '产品类型',
			'status' => '1表示产品正常显示； 0表示产品不在页面显示或删除；',
			'remark' => '备注信息',
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
		$criteria->compare('count',$this->count);
		$criteria->compare('from',$this->from,true);
		$criteria->compare('man',$this->man,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('pic',$this->pic,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('status',$this->status);
		$criteria->compare('remark',$this->remark,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Products the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * 删除指定分类下的商品
     * @param array $ids array("parent"=>array(), "child"=>array())
     * @return bool
     */
    public static function deleteByTypeID($ids=array("parent"=>array(),"child"=>array())){
        $public = F::getPublicData();
        $operation = F::getOperationData();
        $parent = $ids['parent'];
        $child = $ids['child'];

        if(count($parent) > 0 || count($child) > 0){
            $criteria = new CDbCriteria;
            if(count($parent) <= 0){
                $parent = array();
            }
            if(count($child) <= 0){
                $child = array();
            }
            $criteria->addInCondition('type', array_merge($parent, $child));
            $criteria->addInCondition('status', array(1));
            $criteria->addInCondition('user_id', array($public['userId']));

            $records = Products::model()->findAll($criteria);

            F::debug("删除商品".count($records)."个");

            if(count($records) <= 0){
                return true;
            }

            if(
              (Products::model() -> updateAll(array("status" => 0), $criteria)) === count($records)
            ){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }

    /**
     * 更新商品的分类
     * @param array $ids array("parent"=>array(), "child"=>array(), targetTypeId)
     * @return bool
     */
    public static function updateType($ids=array("parent"=>array(),"child"=>array()), $targetTypeId){
        if(!$targetTypeId){
            return false;
        }

        $public = F::getPublicData();
        $operation = F::getOperationData();
        $parent = $ids['parent'];
        $child = $ids['child'];

        if(count($parent) > 0 || count($child) > 0){
            $criteria = new CDbCriteria;
            if(count($parent) <= 0){
                $parent = array();
            }
            if(count($child) <= 0){
                $child = array();
            }
            $criteria->addInCondition('type', array_merge($parent, $child));
            $criteria->addInCondition('status', array(1));
            $criteria->addInCondition('user_id', array($public['userId']));

            $records = Products::model()->findAll($criteria);

            F::debug("更新商品分类".count($records)."个");

            if(count($records) <= 0){
                return true;
            }

            if(
                (Products::model() -> updateAll(array("type" => $targetTypeId), $criteria)) === count($records)
            ){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }

    //在指定商品分类下查询是否有重名的商品
    public static function isExistByName($name, $typeId){
        $public = F::getPublicData();
        $record = Products::model()->findByAttributes(array('name'=>$name, 'user_id' => $public['userId'], 'status'=>1, 'type' => $typeId));

        return $record ? $record : false;
    }

    /**
     * 是否存在某个商品
     * @param $id
     * @return bool|CActiveRecord
     */
    public static function isExistById($id){
        $public = F::getPublicData();
        $record = Products::model()->findByAttributes(array('id'=>$id, 'user_id' => $public['userId'], 'status'=>1));

        return $record ? $record : false;
    }

    /**
     * 添加商品
     * @param $model
     * @param bool $verifyType 是否检查商品分类是否存在
     * @return array|bool
     */
    public static function add($model, $verifyType = true){
        $typeName = Types::getTypeNameById($model->attributes['type']);
        if(!$typeName && $verifyType){
            F::returnError(F::lang('PRODUCT_ADD_ERROR').' '.F::lang('TYPE_NO_EXIST'));
        }
        if($model->save()){
            return array(
                'id' => $model->primaryKey,
                'type' => $typeName
            );
        }else{
            return false;
        }
    }
}
