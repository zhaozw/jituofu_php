<?php

/**
 * This is the model class for table "marketing".
 *
 * The followings are the available columns in table 'marketing':
 * @property integer $id
 * @property string $productVersion
 * @property string $productId
 * @property string $channelId
 * @property string $network
 * @property string $display
 * @property string $model
 * @property string $os
 * @property string $imsi
 * @property string $imei
 * @property string $mac
 */
class Marketing extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'marketing';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('productVersion, productId, channelId, network, display', 'length', 'max'=>10),
			array('model, os, imsi, imei, mac', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, productVersion, productId, channelId, network, display, model, os, imsi, imei, mac', 'safe', 'on'=>'search'),
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
			'productVersion' => '产品版本',
			'productId' => '产品ID',
			'channelId' => '产品渠道',
			'network' => '网络类型',
			'display' => '设备分辨率',
			'model' => '手机型号',
			'os' => '操作系统',
			'imsi' => '手机卡',
			'imei' => '手机imei',
			'mac' => '手机mac地址',
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
		$criteria->compare('productVersion',$this->productVersion,true);
		$criteria->compare('productId',$this->productId,true);
		$criteria->compare('channelId',$this->channelId,true);
		$criteria->compare('network',$this->network,true);
		$criteria->compare('display',$this->display,true);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('os',$this->os,true);
		$criteria->compare('imsi',$this->imsi,true);
		$criteria->compare('imei',$this->imei,true);
		$criteria->compare('mac',$this->mac,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Marketing the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * 向markeing表中写入一条数据
     * @return bool
     */
    public static function saveMarketing()
    {
        $public = F::getPublicData();
        $operation = F::getOperationData();

        $productVersion = $public['productVersion'];
        $productId = $public['productId'];
        $channelId = $public['channelId'];
        $network = $public['network'];
        $display = $public['display'];

        $model = @$operation['model'];
        $os = @$operation['os'];
        $imsi = @$operation['imsi'];
        $imei = @$operation['imei'];
        $mac = @$operation['mac'];

        if(!$model){
            $model = '';
        }
        if(!$os){
            $os = '';
        }
        if(!$imsi){
            $imsi = '';
        }
        if(!$imei){
            $imei = '';
        }
        if(!$mac){
            $mac = '';
        }

        $marketing_data = array(
            "productVersion" => strtolower($productVersion),
            "productId" => strtolower($productId),
            "channelId" => $channelId,
            "network" => strtolower($network),
            "display" => strtolower($display),
            "model" => strtolower($model),
            "os" => strtolower($os),
            "imsi" => strtolower($imsi),
            "imei" => strtolower($imei),
            "mac" => strtolower($mac)
        );
        $marketing_model = new Marketing();
        $marketing_model->attributes = $marketing_data;
        if (!$marketing_model->save()) {
            F::error("记录marketing数据失败 ." . CJSON::encode($marketing_model->getErrors()));
            return false;
        } else {
            return true;
        }
    }
}
