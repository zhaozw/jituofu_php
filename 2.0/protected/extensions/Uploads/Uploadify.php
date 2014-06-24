<?php
/**
 * @name Uploadify 上传组件
 * @author hugh
 * @see http://www.uploadify.com/
 */
class Uploadify extends CWidget{

        //A class name to add to the browse button DOM object
	public $buttonClass = '';
        // (String or null) The path to an image to use for the Flash browse button if not using CSS to style the button
        public $buttonImage = 'images/browse.png';
        // The text to use for the browse button
	public $buttonText = 'select file';
        // The name of the file object to use in your server-side script
        public $fileObjName = 'Filedata';
        // The maximum size of an uploadable file in KB (Accepts units B KB MB GB if string, 0 for no limit)
	public $fileSizeLimit = 0;
        // Allowed extensions in the browse dialog (server-side validation should also be used)
        public $fileTypeExts = '*.*';
        // The width of the browse button
        public $width = 120;
        // The height of the browse button
        public $height = 30;
        // Automatically upload files when added to the queue
        public $auto = true;
        // The method to use when sending files to the server-side upload script
        public $method = 'post';
        // Allow multiple file selection in the browse dialog
        public $multi = true;
        // An object with additional data to send to the server-side upload script with every file upload
        public $formData = '{}';
        // ('percentage' or 'speed') Data to show in the queue item during a file upload
        public $progressData = 'percentage';
        // The ID of the DOM object to use as a file queue (without the #)
        public $queueID = false;
        // The maximum number of files that can be in the queue at one time
	public $queueSizeLimit = 999;
        // Remove queue items from the queue when they are done uploading
        public $removeCompleted = 'false';
        // The path to the uploadify SWF file
	public $swf = '';
        // The path to the server-side upload script
        public $uploader = '';
        // The maximum number of files you can upload
        public $uploadLimit = 20;
        //Kuyuecs add requeueErrors
        //public $requeueErrors  = 'false';

	public function init() {
		if (!$this->fileObjName) {
			$this->fileObjName = 'Filedata';
                    }
		if (!$this->swf) {
			$this->swf = $this->path().'public/js/upload/uploadify.swf';
		}
	}
        /**
	 * 设置上传文件
	 * @param string $name
	 * @param string $defaultValue
	 * @param string $switchValue
	 * @return string
	 */
	public function makeOption($name, $defaultValue, $switchValue='') {
		if ($defaultValue !== $this->$name) {
			if ($switchValue === '') {
				$value = $this->$name;
				if (is_string($value)) {
					$value = "'{$value}'";
				} elseif(is_bool($value)) {
					if ($value) {
						$value = 'true';
					} else {
						$value = 'false';
					}
				}
				$switchValue = $value;
			}
			$option = "'{$name}':{$switchValue},";
		} else {
			$option = '';
		}
		return $option;
	}
	public function path() {
		return '/';
	}
}