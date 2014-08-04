<?php

class DefaultController extends Controller{
    public function actionError(){
        if($error=Yii::app()->errorHandler->error){
            echo "HTTP 状态码：". $error['code'].'<br />';
            echo "错误类型：". $error['type'].'<br />';
            echo "错误信息：". $error['message'].'<br />';
            echo "发生错误的PHP文件名：". $error['file'].'<br />';
            echo "错误所在的行：". $error['line'].'<br />';
            echo "错误的调用栈信息：". $error['trace'].'<br />';
        }
    }
}
?>
