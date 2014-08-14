<?php

/**
 * This is the model class for table "files".
 *
 * The followings are the available columns in table 'files':
 * @property integer $id
 * @property string $dir
 * @property string $name
 */
class Files extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'files';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dir, name', 'required'),
			array('dir', 'length', 'max'=>50),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, dir, name', 'safe', 'on'=>'search'),
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
			'dir' => '目录',
			'name' => '资源名称',
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
		$criteria->compare('dir',$this->dir,true);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Files the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * 上传文件到指定的目录
     * @param $dir 用户id
     * @param $extraFile 文件流的key
     * @return bool
     */
    public static function upload($dir, $extraFile){
        $file = $extraFile;
        $uploadDir = Yii::getPathOfAlias('webroot')."/uploadfiles/".$dir;

        $minWidth = 50;
        $minHeight = 50;

        //文件大小检查
        if((int)$file->size > Yii::app()->params['maxFileSize']){
            F::returnError(F::lang('UPLOAD_MAX_FILE_SIZE'));
        }
        //扩展名检查
        if(!in_array(strtolower($file->extensionName), Yii::app()->params['fileType'])){
            F::returnError(F::lang('UPLOAD_FILE_INVALID'));
        }
        //检查目录
        if(!is_dir($uploadDir)){
            mkdir($uploadDir);
        }
        //获取宽度和高度
        list($width, $height, $type, $attr)=getimagesize($file->tempName);
        if(!$width || !$height){
            F::returnError(F::lang('UPLOAD_IMAGE_WIDTH_HEIGHT_INVALID'));
        }else if($minWidth > $width || $minHeight > $height){
            F::returnError(F::lang('UPLOAD_WIDTH_HEIGHT_MIN'));
        }
        $splitFileName = preg_split("/\./", $file->name);
        $fileType = $splitFileName[1];
        $fileName = md5($splitFileName[0].time());

        //最终的文件名
        $uploadFileName = $fileName.'.'.$fileType;
        //低质量
        $lowUploadFileName = 'l_'.$uploadFileName;
        //中质量
        $middleUploadFileName = 'm_'.$uploadFileName;
        //高质量
        $highUploadFileName = 'h_'.$uploadFileName;
        if($file->saveAs($uploadDir.'/'.$uploadFileName)){
            if(Files::saveThumb($uploadDir.'/'.$uploadFileName, $uploadDir.'/'.$lowUploadFileName, 'l', $width, $height)){
                if(Files::saveThumb($uploadDir.'/'.$uploadFileName, $uploadDir.'/'.$middleUploadFileName, 'm', $width, $height)){
                    if(Files::saveThumb($uploadDir.'/'.$uploadFileName, $uploadDir.'/'.$highUploadFileName, 'h', $width, $height)){
                        return $uploadFileName;
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 保存缩略图
     * @param $original
     * @param $thumb
     * @param $resolution
     * @param $width
     * @param $height
     * @return mixed
     */
    public static function saveThumb($original, $thumb, $resolution, $width, $height){
        $ratio = 1;
        switch($resolution){
            case "l":
                $ratio = 6;
                break;
            case "m":
                $ratio = 4;
                break;
            case "h":
                $ratio = 2;
                break;
        }
        $image = Yii::app()->image->load($original);
        $image->resize($width/$ratio, $height/$ratio)->quality(100);
        return $image->save($thumb);
    }

    /**
     * 根据图片id获取完整资源地址
     * @param $picId
     * @return string
     */
    public static $getImgUser;
    public static function getImg($picId){
        global $getImgUser;
        $public = F::getPublicData();

        if(!$picId){
            return "";
        }

        //老系统的商品图片
        if(!preg_match("/^\d*$/", $picId)){
            if(!stristr($picId, "attachments")){
                $userId = $public['userId'];
                $record = $getImgUser ? $getImgUser : Users::model()->findByAttributes(array('id'=>$userId));
                $username = $record->getAttribute("user_name");

                $picId = "attachments/".md5($username)."/".$picId;
            }
            return Yii::app()->params['oldFileHost']."/".$picId;
        }

        $type = 'h';
        switch($public['display']){
            case "low":
                $type = 'l';
                break;
            case "middle":
                $type = 'm';
                break;
        }
        $baseUrl = Yii::app()->params['fileHost'];

        $record = Files::model()->findByPk($picId);

        if(!$record){
            return "";
        }else{
            $dir = $record->getAttribute('dir');
            $filename = $record->getAttribute('name');
            $path = Yii::getPathOfAlias('webroot')."/uploadfiles/".$dir."/".$type."_".$filename;

            F::debug("尝试读取文件: ".$path);

            //如果没有文件
            if(!is_file($path)){
                return "";
            }



            return $baseUrl."/".$dir."/".$type."_".$filename;
        }
    }

    /**
     * 删除指定的文件
     * @param $fileId
     * @return boolean
     */
    public static function remove($fileId){
        $public = F::getPublicData();

        if(!$fileId){
            return false;
        }

        $record = Files::model()->findByPk($fileId);

        if(!$record){
            return true;
        }else{
            $dir = $record->getAttribute('dir');
            $filename = $record->getAttribute('name');
            $hpath = Yii::getPathOfAlias('webroot')."/uploadfiles/".$dir."/"."h_".$filename;
            $mpath = Yii::getPathOfAlias('webroot')."/uploadfiles/".$dir."/"."m_".$filename;
            $lpath = Yii::getPathOfAlias('webroot')."/uploadfiles/".$dir."/"."l_".$filename;
            $rpath = Yii::getPathOfAlias('webroot')."/uploadfiles/".$dir."/".$filename;

            F::debug("尝试删除文件: ".$rpath);

            //如果没有文件
            if(!is_file($rpath)){
                return true;
            }else if($record -> delete()){
                if(unlink($rpath)){
                    if(unlink($hpath)){
                        if(unlink($mpath)){
                            if(unlink($lpath)){
                                return true;
                            }else{
                                return false;
                            }
                        }else{
                            return false;
                        }
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    }
}
