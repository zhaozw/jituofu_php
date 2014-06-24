<?php

class MailController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions'=>array('send'),
                'users'=>array('*'),
                'verbs' => array('post', 'get')
            )
        );
    }

    public function actionSend(){
//        $emailAddress = Yii::app()->request->getParam('mail');
//        $emailContent = Yii::app()->request->getParam('content');
//        $subject = Yii::app()->request->getParam('subject');
//
//        //fsocket连接时,无法使用Yii的方法
//        $mailConfig = require Yii::app()->basePath.'/config/mailConfig.php';
//        require Yii::app()->basePath.'/extensions/PHPMailer_5.2.4/class.phpmailer.php';
//        require Yii::app()->basePath."/extensions/PHPMailer_5.2.4/class.smtp.php";
//
//        $mail  = new PMailer();
//        $mail->IsHTML(true);
//        $mail->CharSet = "UTF-8"; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置为 UTF-8
//        $mail->IsSMTP(); // 设定使用SMTP服务
//        $mail->SMTPAuth = true; // 启用 SMTP 验证功能
//        $mail->SMTPSecure = "ssl"; // SMTP 安全协议
//        $mail->Host = $mailConfig['host']; // SMTP 服务器
//        $mail->Port = $mailConfig['port']; // SMTP服务器的端口号
//        $mail->Username = $mailConfig['username']; // SMTP服务器用户名
//        $mail->Password = $mailConfig['password']; // SMTP服务器密码
//        $mail->SetFrom($mailConfig['username'], Yii::app()->name); // 设置发件人地址和名称
//        $mail->AddReplyTo($mailConfig['username'], Yii::app()->name);
//
//        $header = "MIME-Version: 1.0\r\n";
//        $header .= "Content-type: text/html; charset=utf-8\r\n";
//        $header .= "From: " . Yii::app()->name . " <" . $mailConfig['username'] . ">\r\n";
//
//        // 设置邮件回复人地址和名称
//        $mail->Subject = $subject; // 设置邮件标题
//        $mail->AltBody = "为了查看该邮件，请切换到支持 HTML 的邮件客户端";
//        // 可选项，向下兼容考虑
//        $mail->MsgHTML($emailContent); // 设置邮件内容
//        $mail->AddAddress($emailAddress, "");
//        if (!$mail->Send()) {
//            return false;
//        } else {
//            return true;
//        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Users the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Users::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Users $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
