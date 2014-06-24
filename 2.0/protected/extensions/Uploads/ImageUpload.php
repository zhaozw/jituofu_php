<?php
/**
 * ImageUpload
 * @author hugh
 * @version 1.0 
 */
class ImageUpload{
    /**
    * 原文件路径
    * @var string
    */
    protected $_originPath;
    /**
    * 图片宽度
    * @var int
    */
    protected $_width;
    /**
    * 图片高度
    * @var int
    */
    protected $_height;
    /**
    * 图片类型
    * @var string
    */
    protected $_type;
    /**
    * 类型ID
    */
    protected $_typeId;
    /**
    * 图片资源
    * @var resource
    */
    protected $_res;
    /**
    * 是否校验过图片了
    * @var boolean
    */
    protected $_isImage = false;
    /**
    * 是否有错
    * @var boolean
    */
    protected $_hasError = false;

    /**
    * 错误信息
    * @var string
    */
    protected $_error = '';
    
    /**
    * 构造函数
    * @param string $imagePath
    */
    public function __construct($imagePath) {
            list($this->_width, $this->_height, $this->_typeId) = getimagesize($imagePath);
            $this->_originPath = $imagePath;
            $this->preProcess();
    }
    /**
    * 处理图片
    */
    protected function preProcess() {
            try {
                    $this->beforePreProcess();
                    $this->createImage();
            } catch (CHttpException $e) {
                    $this->_error = $e->getMessage();
                    $this->_hasError = true;
            }
    }

    /**
    * 处理前的鉴别
    */
    protected function beforePreProcess() {
            if(!$this->isImage()) {
                    throw new CHttpException('File is not a image', 1);
            } else {
                    $this->_isImage = true;
            }
    }

    /**
    * 是否是图片
    * @return boolean
    */
    protected function isImage() {
            $boolean = true;
            $types = array(1=>'gif', 2=>'jpeg', 3=>'png');
            if (isset($types[$this->_typeId])) {
                    $this->_type = $types[$this->_typeId];
            } else {
                    $boolean = false;
            }
            return $boolean;
    }

    /**
    * 创建图片资源
    */
    protected function createImage() {
            $function = "imageCreateFrom{$this->_type}";
            $this->_res = $function($this->_originPath);
    }

    /**
    * 缩放
    */
    public function zoom($path, $size) {
            list($tw, $th) = explode('x', $size);
            $fw = $this->_width;
            $fh = $this->_height;
            $function = "image{$this->_type}";
            $res = imagecreatetruecolor($tw, $th);
            imagecopyresampled($res, $this->_res, 0, 0, 0, 0, $tw, $th, $fw, $fh);
            return $this->callFunction($function, $res, $path);
    }

    /**
    * 缩放多个尺寸
    * @param array $config
    */
    public function zoomMany($config) {
            $this->processMany('zoom', $config);
    }

    /**
    * 固定宽度缩放
    */
    public function fixWidth($path, $tw) {
            if ($this->_width < $tw) {
                    return true;
            }
            $fw = $this->_width;
            $fh = $this->_height;
            $th = $fh*$tw/$fw;
            $function = "image{$this->_type}";
            $res = imagecreatetruecolor($tw, $th);
            imagecopyresampled($res, $this->_res, 0, 0, 0, 0, $tw, $th, $fw, $fh);
            return $this->callFunction($function, $res, $path);
    }

    /**
    * 固定多个尺寸的宽度
    * @param array $config
    */
    public function fixWidthMany() {
            $this->processMany('fixWidth', $config);
    }

    /**
    * 裁剪图片
    */
    public function tailor($path, $size) {
            list($tw, $th) = explode('x', $size);
            $fw = $this->_width;
            $fh = $this->_height;
            $function = "image{$this->_type}";
            if($fw/$tw > $fh/$th) {
                    $fw = $tw * ($fh/$th);
            }else {
                    $fh = $th * ($fw/$tw);
            }
            $res = imagecreatetruecolor($tw, $th);
            imagecopyresampled($res, $this->_res, 0, 0, 0, 0, $tw, $th, $fw, $fh);
            return $this->callFunction($function, $res, $path);
    }

    /**
     * 固定宽高的裁剪图片
     */
    public function tailorWH($path,$size='1000x1000') {
    	list($tw, $th) = explode('x', $size);
    	$fw = $this->_width;
    	$fh = $this->_height;
    	$function = "image{$this->_type}";
    	if($fw>$fh) {
    		if($fw>$tw){
    			$th = $fh*(1000/$fw);
    			$tw = 1000;
    		}else{
    			$th = $fh;
    			$tw = $fw;
    		}
    	}else {
    		if($fh>$th){
    			$th = 1000;
    			$tw = $fw*(1000/$fh);
    		}else{
    			$th = $fh;
    			$tw = $fw;
    		}
    	}
    	$res = imagecreatetruecolor($tw, $th);
    	imagecopyresampled($res, $this->_res, 0, 0, 0, 0, $tw, $th, $fw, $fh);
    	return $this->callFunction($function, $res, $path);
    }

    /**
    * 裁剪多个尺寸
    * @param array $config=array('path'=>$path,'size'=>$size)
    */
    public function tailorMany($config) {
            $this->processMany('tailor', $config);
    }

    /**
    * 图片型水印
    * @param string $waterImage
    * @param string $waterPos 'x*y' or '%x*y'
    * @param string $path
    */
    public function imageWaterMark($waterImage, $waterPos, $path = null) {
            $function = "image{$this->_type}";
            //校验水印图片
            $types = array(1=>'gif', 2=>'jpeg', 3=>'png');
            $function = "image{$this->_type}";
            list($_w, $_h, $_t) = getimagesize($waterImage);
            //计算水印的X,Y位置, 如果是%开头则按百分比计算
            if ($waterPos[0] === '%') {
                    $waterPos = str_replace('%', '', $waterPos);
                    $waterPos = explode('*', $waterPos);
                    $_posX = $this->_width*($waterPos[0]/100)-$_w/2;
                    $_posY = $this->_height*($waterPos[1]/100)-$_h/2;
            } else {
                    list($_posX, $_posY) = explode('*', $waterPos);
            }
            $_posX = intval($_posX);
            $_posY = intval($_posY);
            $_f = 'imageCreateFrom'.$types[$_t];
            $_res = $_f($waterImage);
            imagecopy($this->_res, $_res, $_posX, $_posY, 0, 0, $_w, $_h);
            imagedestroy($_res);
            if (!$path) {
                    header('Content-type: image/'.$this->_type);
            }
            return $this->callFunction($function, $this->_res, $path);
    }

    /**
    * 多操作接口
    * @param string $operation
    * @param array $config
    */
    protected function processMany($operation, $config) {
            foreach ($config as $conf) {
                    $this->$operation($conf['path'], $conf['size']);
            }
    }
    
    /**
    * 保存文件
    * @param string $newName
    * @return bool
    */
    public function saveAs($newName) {
            if($this->_originPath) {
                if (move_uploaded_file($this->_originPath, $newName)) {
                    return true;
                } else {
                    return false;
                }
            }else{
                return false;
            }
    }

    /**
    * 调用图片处理方式
    */
    protected function callFunction($function,$res,$path) {
            if($function=="imagejpeg") {
                    if($function($res, $path,100)) {
                            return true;
                    }else {
                            return false;
                    }
            }else {
                    if($function($res, $path)) {
                            return true;
                    }else {
                            return false;
                    }
            }
    }
}    
?>