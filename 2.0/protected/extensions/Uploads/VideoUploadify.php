<?php
/**
 * @name VideoUploadify
 * @author hugh
 * @version 1.0 
 */
class VideoUploadify {

	protected $allowed = array('rm','RM','rmvb','RMVB','mov','MOV','mtv','MTV','dat','DAT','wmv','WMV','avi','AVI','3gp','3GP','amv','AMV','dmv','DMV','flv','FLV','swf','SWF');

    /*
     * @param model
     * @param avatar
     * @return array
     */
    public function uploadFile($model,$avatar)
    {
		$ext = $this->allowed;
        $ret = array();
        if(!is_object($model) || !is_string($avatar) || !is_array($ext) || empty($ext)){
            $ret['msg'] = 'Been given the wrong parameters!';
            $ret['result'] = 0;
        }else{
            $image = CUploadedFile::getInstance($model,$avatar);
            if(is_object($image) && get_class($image) === 'CUploadedFile')
            {
                $ret['result'] = 1;
                if(!in_array($image->getExtensionName(),$ext)){
                    $ret['msg'] = 'The file format is wrong!';
                    $ret['result'] = 0;
                }
                if($image->getSize()>2048000){
                    $ret['msg'] = 'This file is too large!';
                    $ret['result'] = 0;
                }
                if($ret['result']){
					$time = F::now();
					$date = date('Ymd', $time);
                    $imageFile = $time.'.jpg';
                    $dir = Yii::app()->params['videoDir'].$date.'/';
                    if(!is_dir($dir))
						mkdir($dir);
                    $image->saveAs($dir.'/'.$imageFile);
					$model->$avatar = $date.'/'.$imageFile;
                    $ret['msg'] = 'This file has been uploaded!';
                    $ret['filename'] = $model->$avatar;
                    $ret['result'] = 1;
                }
            }else{
                $ret['msg'] = 'The uploaded file can not be empty!';
                $ret['result'] = 0;
            }
        }
        return $ret;
    }

    /**
     *
     * @param string $file
     * @param boolean
     */
    public function delFile($file)
    {
        if(checkFile($file)){
            unlink($file);return true;
        }else
        {
            return false;
        }
    }

    /**
     *
     * @param string $file
     * @return boolean
     */
    public function checkFile($file)
    {
        if(empty($file)) return false;
        if(file_exist($file)){
            return true;
        }else{
            return false;
        }
    }
}
?>
